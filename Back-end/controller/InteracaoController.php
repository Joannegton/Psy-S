<?php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../model/Interacao.php';

class InteracaoController
{
    /**
     * Endpoint '/api/v1/interacoes/send' - envia uma mensagem
     * 
     * @param int $id_usuario ID do usuário que enviou a mensagem
     * @param int $id_terapeuta ID do terapeuta que recebeu a mensagem
     * @param string $mensagem Mensagem a ser enviada
     * @param string $tipo Tipo da mensagem
     * 
     * @return string Mensagem de sucesso se a mensagem for enviada com sucesso ou mensagem de erro se falhar
     */
    public function sendAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if ($requestMethod !== "POST") {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if(empty($data['id_usuario']) || empty($data['id_terapeuta']) || empty($data['mensagem']) || empty($data['tipo'])){
            http_response_code(400);
            echo json_encode(['message', 'Dados invalidos']);
            return;
        }

        $interacaoModel = new Interacao();

        $result = $interacaoModel->sendMessage($data['id_usuario'], $data['id_terapeuta'], $data['mensagem'], $data['tipo']);

        if($result === false){
            http_response_code(500);
        }

        http_response_code(201); // Criado
        echo json_encode(['message' => $result]);

    }

    public function listAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if($requestMethod !== 'GET'){
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        // Captura os parâmetros da URL
        $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : null;
        $id_terapeuta = isset($_GET['id_terapeuta']) ? (string)$_GET['id_terapeuta'] : null;

        // Verifica se os parâmetros foram passados
        if ($id_usuario === null || $id_terapeuta === null) {
            http_response_code(400);
            echo json_encode(['message' => 'Parâmetros de consulta faltando']);
            return;
        }

        $interacaoModel = new Interacao();
        $messageArray = $interacaoModel->listInteracoes($id_usuario, $id_terapeuta);

        if(empty($messageArray)){
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao consultar SQL.']);
            return;
        }

        http_response_code(200);
        echo json_encode($messageArray);
    }
}