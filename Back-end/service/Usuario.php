<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

class Usuario
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Cria um novo terapeuta e retorna seu ID.
     * 
     * @return string
     */
    public function createTerapeuta(): string
    {
        $sql = 'INSERT INTO terapeuta (id_terapeuta, nome_terapeuta) VALUES (:id_terapeuta, :nome_terapeuta)';
        $id_terapeuta = 'psy' . time() . rand(0, 50000);

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id_terapeuta' => $id_terapeuta,
                'nome_terapeuta' => "Psy"
            ]);
            return $id_terapeuta;
        } catch (PDOException $e) {
            error_log('Erro ao criar terapeuta: ' . $e->getMessage());
            return 'Erro ao criar terapeuta.';
        }
    }

    /**
     * Cria um novo usuário com as informações fornecidas.
     * 
     * @param string $nome Nome do usuário.
     * @param string $email Email do usuário.
     * @param string $senha Senha do usuário.
     * @return string
     */
    public function createUser(string $nome, string $email, string $senha): string
    {
        $sql = "INSERT INTO usuario (nome, email, senha, id_terapeuta) VALUES (:nome, :email, :senha, :id_terapeuta)";
        $id_terapeuta = $this->createTerapeuta();
        $senhaCriptografada = password_hash($senha, PASSWORD_BCRYPT);

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'senha' => $senhaCriptografada,
                'id_terapeuta' => $id_terapeuta
            ]);
            return 'Usuário criado com sucesso!';
        } catch (PDOException $e) {
            error_log('Erro ao criar usuário: ' . $e->getMessage());
            return 'Erro ao criar usuário.';
        }
    }

    public function excludeUser(string $email): string
    {
        $sql = "DELETE FROM usuario WHERE email = :email";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            return 'Usuário excluído com sucesso!';
        } catch (PDOException $e) {
            error_log('Erro ao excluir usuário: ' . $e->getMessage());
            return 'Erro ao excluir usuário.';
        }
    }

    /**
     * Lista todos os usuários cadastrados.
     * 
     * @return array
     */
    public function listUser(): array
    {
        $sql = "SELECT * FROM usuario";

        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao listar usuários: ' . $e->getMessage());
            return [];
        }
    }


    public function login(string $email, string $senha): array
    {
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['error' => 'Usuário não encontrado.'];
        }

        if (!password_verify($senha, $user['senha'])) { 
            return ['error' => 'Senha incorreta.'];
        }

        return $user;
    }
}
