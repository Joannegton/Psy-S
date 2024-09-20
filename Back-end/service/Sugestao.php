<?php

require_once __DIR__ . '/../config/config.php';

class SugestaoService
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createSugestao($id_usuario, $id_profissional, $data_sugestao)
    {
        $stmt = $this->db->prepare("INSERT INTO Sugestao (id_usuario, id_profissional, data_sugestao) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id_usuario, $id_profissional, $data_sugestao); // i = integer, s = string
        if ($stmt->execute()) {
            return "Sugestão criada com sucesso!";
        } else {
            return "Erro ao criar sugestão.";
        }
    }

    public function listSugestoes(int $id_usuario)
    {
        $stmt = $this->db->prepare("SELECT * FROM Sugestao WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Retorna todas as linhas
    }

    public function suggestProfissionais($id_usuario)
    {
        // Deleta sugestões anteriores
        $this->deleteSugestoes($id_usuario);

        // Seleciona 3 profissionais com base na quantidade de estrelas
        $stmt = $this->db->prepare("
            SELECT p.id_profissional 
            FROM Profissional p 
            ORDER BY p.estrelas DESC 
            LIMIT 3
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $profissionais = $result->fetch_all(MYSQLI_ASSOC);

        // Cria novas sugestões
        $data_sugestao = date('Y-m-d');
        foreach ($profissionais as $profissional) {
            $this->createSugestao($id_usuario, $profissional['id_profissional'], $data_sugestao);
        }

        return $this->listSugestoes($id_usuario);
    }

    public function deleteSugestoes($id_usuario)
    {
        $stmt = $this->db->prepare("DELETE FROM Sugestao WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }
}
?>