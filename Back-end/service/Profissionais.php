<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

class Profissionais
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Cria um novo profissional.
     * @param string $nome_profissional Nome do usuário.
     * @param string $cpf CPF do usuário.
     * @param string $crth crth-br do usuário.
     * @return string
     */
    public function createProfissional(string $nome_profissional, string $email, string $senha, string $cpf, string $crth): string
    {
        $sql = 'INSERT INTO profissional (nome, email, senha, cpf, crth) VALUES (:nome, :email, :senha, :cpf, :crth)';
        $senhaCriptografada = password_hash($senha, PASSWORD_BCRYPT);

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nome' => $nome_profissional,
                'email' => $email,
                'senha' => $senhaCriptografada,
                'cpf' => $cpf,
                'crth' => $crth
            ]);
            return 'Usuário criado com sucesso!';
        } catch (PDOException $e) {
            error_log('Erro ao criar profissional: ' . $e->getMessage());
            return 'Erro ao criar profissional.';
        }
    }

    /**
     * Lista todos os profissionais.
     * @return array
     */
    public function listProfissional(): array
    {
        $sql = 'SELECT * FROM profissional';

        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Erro ao listar profissionais: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um profissional pelo ID.
     * @param string $id_profissional ID do profissional.
     * @return array
     */
    public function findProfissional(int $id_profissional): array
    {
        $sql = 'SELECT * FROM profissional WHERE id_profissional = :id_profissional';

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id_profissional' => $id_profissional]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Erro ao buscar profissional: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza um profissional.
     * @param string $nome_profissional Nome do profissional.
     * @param string $senha Senha do profissional.
     * @return string
     */
    public function updateProfissional(int $id_profissional, string $nome, string $senha): string
    {
        $sql = 'UPDATE profissional SET nome = :nome, senha = :senha WHERE id_profissional = :id_profissional';
        $senhaCriptografada = password_hash($senha, PASSWORD_BCRYPT);

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                "id_profissional" => $id_profissional,
                'nome' => $nome,
                'senha' => $senhaCriptografada
            ]);
            return 'Profissional atualizado com sucesso!';
        } catch (PDOException $e) {
            error_log('Erro ao atualizar profissional: ' . $e->getMessage());
            return 'Erro ao atualizar profissional.';
        }
    }
    
    /**
     * Deleta um profissional.
     * @param string $id_profissional ID do profissional.
     * @return string
     */
    public function deleteProfissional(int $id_profissional): string
    {
        $sql = 'DELETE FROM profissional WHERE id_profissional = :id_profissional';

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id_profissional' => $id_profissional]);
            return 'Profissional deletado com sucesso!';
        } catch (PDOException $e) {
            error_log('Erro ao deletar profissional: ' . $e->getMessage());
            return 'Erro ao deletar profissional.';
        }
    }

    /**
     * Faz login de um profissional.
     * @param string $email Email do profissional.
     * @param string $senha Senha do profissional.
     * @return array
     */ 
    public function loginProfissional(string $email, string $senha): array
    {
        $sql = 'SELECT * FROM profissional WHERE email = :email';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $profissional = $stmt->fetch();

        if (!$profissional || !password_verify($senha, $profissional['senha'])) {
            return ['error' => 'Senha incorreta.'];
        }

        return $profissional;
    }


}
