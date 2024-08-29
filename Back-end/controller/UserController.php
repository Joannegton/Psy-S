<?php
require_once __DIR__ . '/../config/config.php'; // Inclui o arquivo de configuração
require_once __DIR__ . '/../model/Usuario.php';

class UserController
{
    /**
     * Endpoint '/api/v1/users/list' - recupera os usuarios
     *
     * @return 
     */
    public function listAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"]; // Recupera o método da requisição

        if($requestMethod !== 'GET'){
            http_response_code(405); // Método não permitido
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        // Conectar ao banco de dados usando a classe Usuario
        $userModel = new Usuario();
        $usersArray = $userModel->listUser(); // Recupera todos os usuários
        
        if(empty($usersArray)){
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao consultar SQL.']);
            return;
        }
 
        http_response_code(200);
        echo json_encode($usersArray);
    }


    /**
     * Endpoint '/api/v1/users/create' - cria um novo usuário
     */

    public function createAction()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"]; // Recupera o método da requisição

        if($requestMethod !== 'POST'){
            http_response_code(405); // Método não permitido
            echo json_encode(['message' => 'Método não permitido']);
            return;
        }

        // Recupera os dados do corpo da requisição
        $data = json_decode(file_get_contents('php://input'), true);

        // Verifica se os dados foram enviados
        if(empty($data['nome']) || empty($data['email']) || empty($data['senha'])){
            http_response_code(400); // Requisição inválida
            echo json_encode(['message' => 'Dados inválidos']);
            return;
        }

        // Conectar ao banco de dados usando a classe Usuario
        $usuarioModel = new Usuario();

        // Criar um novo usuário
        $result = $usuarioModel->createUser($data['nome'], $data['email'], $data['senha']);

        if(strpos($result, 'Erro') !== false){
            http_response_code(500); // Erro interno do servidor
        }

        http_response_code(201); // Criado
        echo json_encode(['message' => $result]);
    }
}