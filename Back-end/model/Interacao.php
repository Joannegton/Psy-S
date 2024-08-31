<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../database/dbConnect.php';

/**
 * Classe para gerenciar interações entre usuários e terapeutas.
 */
class Interacao
{
    private PDO $pdo;

    /**
     * Construtor da classe Interacao.
     * 
     * @param PDO $pdo Instância do PDO para interação com o banco de dados.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Envia uma mensagem para um terapeuta.
     * 
     * @param int $id_usuario ID do usuário que enviou a mensagem
     * @param string $id_terapeuta ID do terapeuta que recebeu a mensagem
     * @param string $mensagem Mensagem a ser enviada
     * @param string $tipo Tipo da mensagem
     * 
     * @return bool|array Retorna true se sucesso ou array com mensagem de erro
     */
    public function sendMessage(int $id_usuario, string $id_terapeuta, string $mensagem, string $tipo)
    {
        // Verifica se os parâmetros são válidos
        if (empty($id_usuario) || empty($id_terapeuta) || empty($mensagem) || empty($tipo)) {
            return ['error' => 'Parâmetros inválidos'];
        }

        // Sanitiza e valida o tipo da mensagem
        $tipo = filter_var($tipo);
        if (!in_array($tipo, ['Terapeuta', 'Usuario'])) { // Ajuste conforme os tipos permitidos
            return ['error' => 'Tipo de mensagem inválido'];
        }

        $sql = "INSERT INTO interacao (id_usuario, id_terapeuta, data_hora, mensagem, tipo) 
                VALUES (:user_id, :therapist_id, NOW(), :mensagem, :tipo)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $executed = $stmt->execute([
                ':user_id' => $id_usuario,
                ':therapist_id' => $id_terapeuta,
                ':mensagem' => $mensagem,
                ':tipo' => $tipo
            ]);

            if (!$executed) {
                return ['error' => 'Falha ao enviar a mensagem'];
            }

            return true;
        } catch (PDOException $e) {
            // Log do erro para análise futura
            error_log($e->getMessage(), 3, __DIR__ . '/../logs/error.log');
            return ['error' => 'Erro ao enviar a mensagem'];
        }
    }

    /**
     * Lista todas as interações de um usuário com um terapeuta.
     * 
     * @param int $id_usuario ID do usuário
     * @param string $id_terapeuta ID do terapeuta
     * 
     * @return array Retorna uma lista de interações ou um array vazio
     */
    public function listInteracoes(int $id_usuario, string $id_terapeuta): array
    {
        $sql = "SELECT * FROM interacao WHERE id_usuario = :user_id AND id_terapeuta = :therapist_id ORDER BY data_hora ASC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $id_usuario,
                ':therapist_id' => $id_terapeuta
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log do erro para análise futura
            error_log($e->getMessage(), 3, __DIR__ . '/../logs/error.log');
            return [];
        }
    }
}
