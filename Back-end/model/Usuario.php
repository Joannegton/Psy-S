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
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)"; // Instrução SQL
    
        try {
            $stmt = $this->pdo->prepare($sql); // Prepara a instrução SQL
            $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senha]); // Executa a instrução SQL com os parâmetros
    
            return 'Usuário criado com sucesso!'; 
        } catch (PDOException $e) {
            return 'Erro ao criar usuário: ' . $e->getMessage();
        }
    }

    


    
}