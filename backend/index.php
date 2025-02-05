<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controller/InteracaoController.php';
require_once __DIR__ . '/controller/UserController.php';
require_once __DIR__ . '/service/Interacao.php';
require_once __DIR__ . '/service/Usuario.php';
require_once __DIR__ . '/service/Conteudo.php';
require_once __DIR__ . '/controller/ConteudoController.php';
require_once __DIR__ . '/service/Interacao.php';
require_once __DIR__ . '/controller/ProfissionaisController.php';

// Habilitar Cabeçalhos CORS:
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Verificar se é uma solicitação OPTIONS (preflight) para permitir CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Função para criar instâncias de controllers
function getController(string $controllerName, PDO $pdo): ?object
{
    switch ($controllerName) {
        case 'users':
            return new UserController(new Usuario($pdo));
        case 'interacoes':
            return new InteracaoController(new Interacao($pdo));
        case 'conteudos':
            return new ContentController(new Content($pdo));
        case 'profissional':
            return new ProfissionaisController(new Profissionais($pdo));
        default:
            return null;
    }
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');
$uriParts = explode('/', $uri);

//localhost:8000/api/v1/serviço/metodo

try {
    if (isset($uriParts[0]) && $uriParts[0] === 'api' && $uriParts[1] === 'v1') {
        if (isset($uriParts[2])) {
            $controllerName = $uriParts[2];
            $pdo = getDbConnection();
            $controller = getController($controllerName, $pdo);

            if ($controller !== null) {
                $method = isset($uriParts[3]) ? $uriParts[3] . 'Action' : 'listAction';

                if (method_exists($controller, $method)) {
                    $controller->{$method}();
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Método não encontrado']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Recurso não encontrado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Recurso não encontrado']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'API não encontrada']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor', 'message' => $e->getMessage()]);
}
