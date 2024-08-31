<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../database/dbConnect.php';

class Interacao
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = getDbConnection(); // Chama a função para obter a conexão PDO
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit(); // Encerrar a execução do script se a conexão falhar
        }
    }


    /**
     * Enviar uma mensagem
     * 
     * @param int $id_usuario ID do usuário que enviou a mensagem
     * @param string $id_terapeuta ID do terapeuta que recebeu a mensagem
     * @param string $mensagem Mensagem a ser enviada
     * @param string $tipo Tipo da mensagem
     * 
     * @return bool
     */
    public function sendMessage(int $id_usuario, string $id_terapeuta,  string $mensagem, string $tipo): bool
    {
        $sql = "INSERT INTO interacao (id_usuario, id_terapeuta, data_hora, mensagem, tipo) 
                VALUES (:user_id, :therapist_id, NOW(), :mensagem, :tipo)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $id_usuario,
            ':therapist_id' => $id_terapeuta,
            ':mensagem' => $mensagem,
            ':tipo' => $tipo
        ]);
    }

    /**
     * Listar todas as interações de um usuário com um terapeuta
     * 
     * @param int $id_usuario ID do usuário
     * @param string $id_terapeuta ID do terapeuta
     * 
     * @return array
     */
    public function listInteracoes(int $id_usuario, string $id_terapeuta): array
    {
        $sql = "SELECT * FROM interacao WHERE id_usuario = :user_id AND id_terapeuta = :therapist_id ORDER BY data_hora ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $id_usuario,
            ':therapist_id' => $id_terapeuta
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}