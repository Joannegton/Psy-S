<?php

// Requer o arquivo de configuração e o modelo de Interação
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../service/Interacao.php';
require __DIR__ . '/../service/OpenAiGpt.php';

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
    public function sendAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if ($requestMethod !== "POST") {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_usuario']) || empty($data['id_terapeuta']) || empty($data['mensagem'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos']);
            return;
        }

        // Recupera o histórico de interações
        $messageHistory = $this->interacaoModel->listInteracoes($data['id_usuario'], $data['id_terapeuta']);

        // Verifica se houve interações anteriores
        if (!empty($messageHistory)) {
            // Obtém o horario da última mensagem
            $lastMessage = end($messageHistory);
            $lastMessageTimestamp = strtotime($lastMessage['data_hora']); 

            // Verifica se 1 hora se passou desde a última mensagem
            if (time() - $lastMessageTimestamp > 3600) {
                // Zera o histórico se o tempo de inatividade for superior a 1 hora
                $messageHistory = [];
            }
        }

        // Envia a mensagem e o histórico para o ChatGPT
        $chatGpt = new OpenAIChatGPT();
        $chatGptResponse = $chatGpt->sendRequest($data['mensagem'], $messageHistory);

        // Salva a mensagem do usuário no banco de dados
        $result = $this->interacaoModel->sendMessage($data['id_usuario'], $data['id_terapeuta'], $data['mensagem'], 'Usuario');

        if ($result === false || isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['message' => $result['error'] ?? 'Falha ao enviar a mensagem']);
            return;
        }

        // Verifica se houve erro na resposta do ChatGPT
        if (isset($chatGptResponse['error']) || !isset($chatGptResponse['choices'][0]['message']['content'])) {
            http_response_code(500);
            echo json_encode(['message' => 'Falha ao obter resposta do ChatGPT', 'detalhes' => $chatGptResponse]);
            return;
        }

        // Salva a resposta do ChatGPT no banco de dados
        $chatGptMessage = $chatGptResponse['choices'][0]['message']['content'];
        $result = $this->interacaoModel->sendMessage($data['id_usuario'], $data['id_terapeuta'], $chatGptMessage, 'Terapeuta');

        if ($result === false || isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['message' => $result['error'] ?? 'Falha ao salvar a resposta do ChatGPT']);
            return;
        }

        http_response_code(201);
        echo json_encode(['message' => 'Mensagem enviada com sucesso', 'resposta' => $chatGptMessage]);
    }


    /**
     * Endpoint '/api/v1/interacoes/list' - Lista as interações.
     * 
     * @return void
     */
    public function listAction(): void
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // Verifica se o método da requisição é GET
        if ($requestMethod !== 'GET') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        // Captura os parâmetros da URL
        $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : null;
        $id_terapeuta = isset($_GET['id_terapeuta']) ? $_GET['id_terapeuta'] : null;

        // Verifica se os parâmetros necessários foram passados
        if ($id_usuario === null || $id_terapeuta === null) {
            http_response_code(400);
            echo json_encode(['message' => 'Parâmetros de consulta faltando']);
            return;
        }

        // Busca as interações entre o usuário e o terapeuta
        $messageArray = $this->interacaoModel->listInteracoes($id_usuario, $id_terapeuta);

        // Verifica se há interações para serem retornadas
        if (empty($messageArray)) {
            http_response_code(404);
            echo json_encode(['message' => 'Nenhuma interação encontrada']);
            return;
        }

        http_response_code(200); // Sucesso
        echo json_encode($messageArray);
    }

    /**
     * Endpoint '/api/v1/interacoes/delete' - Deleta as interações.
     * 
     * @return void
     */
    public function deleteAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if ($requestMethod !== "DELETE") {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_usuario']) || empty($data['id_terapeuta'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos']);
            return;
        }

        $result = $this->interacaoModel->excludeListaInteracoes($data['id_usuario'], $data['id_terapeuta']);

        if ($result === false || isset($result['error'])) {
            http_response_code(500);
            echo json_encode(['message' => $result['error'] ?? 'Falha ao deletar as interações']);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => 'Interações deletadas com sucesso']);
    }
}