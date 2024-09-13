<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../database/dbConnect.php';

class Content
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Adiciona um novo conteúdo.
     * 
     * @param string $nome Nome do conteúdo.
     * @param string $link Link do conteúdo.
     * @param string $tipo Tipo do conteúdo (article, video, meditation).
     * @return string
     */
    public function createContent(string $nome, string $link, string $tipo): string
    {
        $sql = "INSERT INTO conteudos (nome, link, tipo) VALUES (:nome, :link, :tipo)";
        $tipoSanitized = $this->sanitizeType($tipo);

        if (!$tipoSanitized) {
            return 'Tipo de conteúdo inválido.';
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nome' => $nome,
                'link' => $link,
                'tipo' => $tipoSanitized
            ]);
            return 'Conteúdo adicionado com sucesso!';
        } catch (PDOException $e) {
            error_log('Erro ao adicionar conteúdo: ' . $e->getMessage());
            return 'Erro ao adicionar conteúdo.';
        }
    }

    /**
     * Obtém um conteúdo pelo ID.
     * 
     * @param int $id ID do conteúdo.
     * @return array
     */
    public function getContent(int $id): array
    {
        $sql = "SELECT * FROM conteudos WHERE id = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $content = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($content) {
                return $content;
            } else {
                return ['error' => 'Conteúdo não encontrado.'];
            }
        } catch (PDOException $e) {
            error_log('Erro ao obter conteúdo: ' . $e->getMessage());
            return ['error' => 'Erro ao obter conteúdo.'];
        }
    }

    /**
     * Atualiza um conteúdo existente.
     * 
     * @param int $id ID do conteúdo.
     * @param string $nome Nome do conteúdo.
     * @param string $link Link do conteúdo.
     * @param string $tipo Tipo do conteúdo (article, video, meditation).
     * @return string
     */
    public function updateContent(int $id, string $nome, string $link, string $tipo): string
    {
        $sql = "UPDATE conteudos SET nome = :nome, link = :link, tipo = :tipo WHERE id = :id";
        $tipoSanitized = $this->sanitizeType($tipo);

        if (!$tipoSanitized) {
            return 'Tipo de conteúdo inválido.';
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nome' => $nome,
                'link' => $link,
                'tipo' => $tipoSanitized,
                'id' => $id
            ]);
            return 'Conteúdo atualizado com sucesso!';
        } catch (PDOException $e) {
            error_log('Erro ao atualizar conteúdo: ' . $e->getMessage());
            return 'Erro ao atualizar conteúdo.';
        }
    }

    /**
     * Deleta um conteúdo pelo ID.
     * 
     * @param int $id ID do conteúdo.
     * @return string
     */
    public function deleteContent(int $id): string
    {
        $sql = "DELETE FROM conteudos WHERE id = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return 'Conteúdo deletado com sucesso!';
        } catch (PDOException $e) {
            error_log('Erro ao deletar conteúdo: ' . $e->getMessage());
            return 'Erro ao deletar conteúdo.';
        }
    }

    /**
     * Sanitiza o tipo de conteúdo para garantir que ele seja válido.
     * 
     * @param string $tipo Tipo de conteúdo.
     * @return string|null Retorna o tipo se for válido, ou null se não for.
     */
    private function sanitizeType(string $tipo): ?string
    {
        $tiposValidos = ['article', 'video', 'meditation'];
        return in_array($tipo, $tiposValidos) ? $tipo : null;
    }
}
