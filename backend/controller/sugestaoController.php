<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../service/SugestaoService.php';

class SugestaoController
{
    private SugestaoService $sugestaoService;

    public function __construct(SugestaoService $sugestaoService)
    {
        $this->sugestaoService = $sugestaoService;
    }

    public function createAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_usuario']) || empty($data['id_profissional'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos. Todos os campos são obrigatórios.']);
            return;
        }

        // Define a data atual como data da sugestão
        $data_sugestao = date('d-m-Y');

        $result = $this->sugestaoService->createSugestao($data['id_usuario'], $data['id_profissional'], $data_sugestao);

        if ($result === 'Sugestão criada com sucesso!') {
            http_response_code(201);
            echo json_encode(['message' => $result]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => $result]);
        }
    }

    public function listAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'GET') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : null;

        if ($id_usuario === null) {
            http_response_code(400);
            echo json_encode(['message' => 'ID do usuário não informado']);
            return;
        }

        $sugestoes = $this->sugestaoService->listSugestoes($id_usuario);

        if (empty($sugestoes)) {
            http_response_code(404);
            echo json_encode(['message' => 'Nenhuma sugestão encontrada.']);
            return;
        }

        http_response_code(200);
        echo json_encode($sugestoes);
    }

    public function suggestAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_usuario'])) {
            http_response_code(400);
            echo json_encode(['message' => 'ID do usuário não informado']);
            return;
        }

        $sugestoes = $this->sugestaoService->suggestProfissionais($data['id_usuario']);

        http_response_code(200);
        echo json_encode($sugestoes);
    }

    public function deleteAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : null;

        if ($id_usuario === null) {
            http_response_code(400);
            echo json_encode(['message' => 'ID do usuário não informado']);
            return;
        }

        $result = $this->sugestaoService->deleteSugestoes($id_usuario);

        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'Sugestões deletadas com sucesso']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Erro ao deletar sugestões']);
        }
    }
}
?>