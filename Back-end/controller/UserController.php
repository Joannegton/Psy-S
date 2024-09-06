<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../service/Usuario.php';

class UserController
{
    private Usuario $usuarioModel;

    /**
     * Construtor da classe UserController.
     * 
     * @param Usuario $usuarioModel Instância do modelo de usuário.
     */
    public function __construct(Usuario $usuarioModel)
    {
        $this->usuarioModel = $usuarioModel;
    }

    /**
     * Lista todos os usuários.
     * 
     * Responde com um código 200 e os dados dos usuários se encontrados,
     * ou um código 404 se nenhum usuário for encontrado.
     */
    public function listAction(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'GET') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $usersArray = $this->usuarioModel->listUser();

        if (empty($usersArray)) {
            http_response_code(404);
            echo json_encode(['error' => 'Nenhum usuário encontrado.']);
            return;
        }

        http_response_code(200);
        echo json_encode($usersArray);
    }

    /**
     * Cria um novo usuário.
     * 
     * Responde com um código 201 e uma mensagem de sucesso se o usuário for criado com sucesso,
     * ou um código 500 se ocorrer um erro ao criar o usuário.
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
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Dados inválidos. Todos os campos são obrigatórios.']);
            return;
        }

        // Valida se o email possui um formato válido
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['message' => 'Email inválido.']);
            return;
        }

        $result = $this->usuarioModel->createUser($data['nome'], $data['email'], $data['senha']);

        if (strpos($result, 'Erro') !== false) {
            http_response_code(500);
            echo json_encode(['message' => $result]);
            return;
        }

        http_response_code(201);
        echo json_encode(['message' => $result]);
    }

    /**
     * Atualiza um usuário.
     * 
     * Responde com um código 200 e uma mensagem de sucesso se o usuário for atualizado com sucesso,
     * ou um código 500 se ocorrer um erro ao atualizar o usuário.
     */
    public function deleteAction(): void
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        
        if($requestMethod != "DELETE"){
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if(empty($data['id_usuario']) || empty(['email'])){
            http_response_code(400);
            echo json_encode(['message' => 'Dados invalidos']);
            return;
        }

        $result = $this->usuarioModel->excludeUser($data['id_usuario'], $data['email']);

        if($result === false || isset($result['error'])){
            http_response_code(500);
            echo json_encode(['message' => $result['error'] ?? 'Falha ao deletar o usuário']);
            return;
        }

        http_response_code(200);
        echo json_encode(['message' => 'Usuário deletado com sucesso']);
    }

    /**
     * Faz login de um usuário.
     * 
     * Responde com um código 200 e os dados do usuário se o login for bem sucedido,
     * ou um código 404 se o usuário não for encontrado.
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

        $result = $this->usuarioModel->login($data['email'], $data['senha']);

        if (isset($result['error'])) {
            http_response_code(404);
            echo json_encode($result);
            return;
        }

        http_response_code(200);
        echo json_encode($result);
    }
}
