<?php

require __DIR__ . '/config/config.php';
require __DIR__ . '/controller/UserController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Obtém o caminho da URL da requisição
$uri = trim($uri, '/'); // Remove as barras no início e no final da URL

$uriParts = explode( '/', $uri ); // Divide o caminho da URL em partes e armazena em um array

try {
    // Verifica se a requisição é para a API e o recurso correto
    if(isset($uriParts[0]) && $uriParts[0] === 'api' && $uriParts[1] === 'v1'){ // isset verifica se existe a primeira parte da uri
        if(isset($uriParts[2]) && $uriParts[2] === 'users'){
            $userController = new UserController();
            
//Se a quarta parte da URL existir, ela é concatenada com 'Action' para formar o nome do método a ser chamado no controlador.
            $method = isset($uriParts[3]) ? $uriParts[3] . 'Action' : 'listAction'; //o método padrão chamado será listAction, que normalmente retornaria uma lista de usuários.
        
            if(method_exists($userController, $method)){
                $userController->{$method}(); // Chama o método correspondente no controlador de usuário

            } else{
                http_response_code(404);
                echo json_encode(['error' => 'Método não encontrado']);
            }
        } else{
            http_response_code(404);
            echo json_encode(['error' => 'Recurso não encontrado']);
        }  
    } else{
        http_response_code(404);
        echo json_encode(['error' => 'API não encontrado']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor', 'message' => $e->getMessage()]);
}