<?php

declare(strict_types=1);
include_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../service/Profissionais.php';

class ProfissionaisController
{
    private Profissionais $profissionaisService;

    public function __construct(Profissionais $profissionaisService)
    {
        $this->profissionaisService = $profissionaisService;
    }

    /**
     * Cria um profissional
     * 
     * @param string $nome_profissional Nome do profissional
     * @param string $email Email do profissional
     * @param string $senha Senha do profissional
     * @param string $cpf CPF do profissional
     * @param string $crth CRTH do profissional
     * @return void
     * 
     */
    public function createAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Valida se todos os campos necessários foram preenchidos
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha']) || empty($data['cpf']) || empty($data['crth'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Todos os campos são obrigatórios.']);
            return;
        }

        // Valida se o email possui um formato válido
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['message' => 'Email inválido.']);
            return;
        }

        $result = $this->profissionaisService->createProfissional($data['nome'], $data['email'], $data['senha'], $data['cpf'], $data['crth']);

        if ($result === 'Erro ao criar profissional.') {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao criar profissional.']);
            return;
        }

        http_response_code(201);
        echo json_encode(['message' => $result]);
    }

    /**
     * Lista todos os profissionais
     * 
     * @return void
     */
    public function listAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'GET') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $profissionaisArray = $this->profissionaisService->listProfissional();

        if (empty($profissionaisArray)) {
            http_response_code(404);
            echo json_encode(['error' => 'Nenhum profissional encontrado.']);
            return;
        }

        http_response_code(200);
        echo json_encode($profissionaisArray);
    }

    /**
     * Busca um profissional pelo ID
     * 
     * @param string $id_profissional ID do profissional
     * @return void
     */
    public function findAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'GET') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $id_profissional = isset($_GET['id']) ? (int)$_GET['id'] : null;

        $profissional = $this->profissionaisService->findProfissional($id_profissional);

        if (empty($profissional)) {
            http_response_code(404);
            echo json_encode(['error' => 'Profissional não encontrado.']);
            return;
        }

        http_response_code(200);
        echo json_encode($profissional);
    }

    /**
     * Atualiza um profissional
     * 
     * @param string $nome_profissional Nome do profissional
     * @param string $senha Senha do profissional
     * @return void
     */
    public function updateAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Valida se todos os campos necessários foram preenchidos
        if (empty($data['id_profissional'])|| empty($data['nome']) || empty($data['senha'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Todos os campos são obrigatórios.']);
            return;
        }

        $result = $this->profissionaisService->updateProfissional($data['id_profissional'], $data['nome'], $data['senha']);

        if ($result === 'Erro ao atualizar profissional.') {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao atualizar profissional.']);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => $result]);
    }

    /**
     * Exclui um profissional
     * 
     * @param string $id_profissional ID do profissional
     * @return void
     */
    public function excludeAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }
        
        $id_profissional = isset($_GET['id']) ? (int)$_GET['id'] : null;

        $result = $this->profissionaisService->deleteProfissional($id_profissional);

        if ($result === 'Erro ao excluir profissional.') {
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao excluir profissional.']);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => $result]);
    }
    
    /**
     * Faz login de um profissional
     * 
     * Responde com um código 200 e os dados do profissional se o login for bem sucedido,
     * ou um código 404 se o profissional não for encontrado.
     */
    public function loginAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        // Valida se todos os campos necessários foram preenchidos
        if (empty($data['email']) || empty($data['senha'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos. Todos os campos são obrigatórios.']);
            return;
        }

        $result = $this->profissionaisService->loginProfissional($data['email'], $data['senha']);

        if (isset($result['error'])) {
            http_response_code(404);
            echo json_encode($result);
            return;
        }

        http_response_code(200);
        echo json_encode($result);
    }
}