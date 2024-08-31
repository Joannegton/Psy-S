<?php

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../model/Interacao.php';

/**
 * Controlador para gerenciar interações entre usuários e terapeutas.
 */
class InteracaoController
{
    private Interacao $interacaoModel;

    /**
     * Construtor da classe InteracaoController.
     * 
     * @param Interacao $interacaoModel Instância do modelo de interação.
     */
    public function __construct(Interacao $interacaoModel)
    {
        $this->interacaoModel = $interacaoModel;
    }

    /**
     * Endpoint '/api/v1/interacoes/send' - Envia uma mensagem.
     * 
     * @return void
     */
    public function sendAction(): void
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // Verifica se o método é POST
        if ($requestMethod !== "POST") {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        // Lê os dados enviados no corpo da requisição
        $data = json_decode(file_get_contents('php://input'), true);

        // Valida os dados recebidos
        if (empty($data['id_usuario']) || empty($data['id_terapeuta']) || empty($data['mensagem']) || empty($data['tipo'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos']);
            return;
        }

        // Tenta enviar a mensagem e verifica se houve sucesso
        $result = $this->interacaoModel->sendMessage($data['id_usuario'], $data['id_terapeuta'], $data['mensagem'], $data['tipo']);

        if ($result === false || isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['message' => $result['error'] ?? 'Falha ao enviar a mensagem']);
            return;
        }

        http_response_code(201); // Sucesso, recurso criado
        echo json_encode(['message' => 'Mensagem enviada com sucesso']);
    }

    /**
     * Endpoint '/api/v1/interacoes/list' - Lista as interações.
     * 
     * @return void
     */
    public function listAction(): void
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // Verifica se o método é GET
        if ($requestMethod !== 'GET') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        // Captura os parâmetros da URL
        $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : null;
        $id_terapeuta = isset($_GET['id_terapeuta']) ? $_GET['id_terapeuta'] : null;

        // Verifica se os parâmetros foram passados
        if ($id_usuario === null || $id_terapeuta === null) {
            http_response_code(400);
            echo json_encode(['message' => 'Parâmetros de consulta faltando']);
            return;
        }

        $messageArray = $this->interacaoModel->listInteracoes($id_usuario, $id_terapeuta);

        // Verifica se houve erro ao buscar as interações
        if (empty($messageArray)) {
            http_response_code(404);
            echo json_encode(['message' => 'Nenhuma interação encontrada']);
            return;
        }

        http_response_code(200); // Sucesso
        echo json_encode($messageArray);
    }
}
