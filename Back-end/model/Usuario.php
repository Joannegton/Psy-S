<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php'; //inclui o arquivo de configuração

class Usuario
{
    private $pdo;

    public function __construct()
    {
        try {
            // Configura a conexão PDO com o banco de dados
            $this->pdo = new PDO('mysql:host=' . dbHost . ';dbname=' . dbName . ';charset=utf8',dbUser, dbPass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Conexão falhou: ' . $e->getMessage();
            exit(); // Encerrar a execução do script se a conexão falhar
        }
    }

    /**
     * Recupera todos os usuários do banco de dados
     *
     * @return array Lista de usuários
     */
    public function listUser(): array
    {
        $sql = "SELECT * FROM usuario"; // Instrução SQL

        try {
            $stmt = $this->pdo->query($sql); // stmt - statement 
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Recupera todos os usuários 

        } catch (Exception $e) {
            error_log($e->getMessage());
            return []; // Retorna um array vazio em caso de erro
        }
    }

    /**
     * Criar um novo usuário
     *
     * @param string $nome Nome do usuário.
     * @param string $email Endereço de e-mail do usuário.
     * @param string $senha Senha do usuário, deve ser armazenada de forma segura (criptografada).
     *
     * @return string Mensagem de sucesso se o usuário for criado com sucesso ou mensagem de erro se falhar.
     */
    public function createUser($nome, $email, $senha): string
    {
        $sql = "INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)"; // Instrução SQL
    
        try {
            $stmt = $this->pdo->prepare($sql); // Prepara a instrução SQL
            $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha]); // Executa a instrução SQL com os parâmetros
    
            return 'Usuário criado com sucesso!'; 
        } catch (PDOException $e) {
            return 'Erro ao criar usuário: ' . $e->getMessage();
        }
    }

    




    
}