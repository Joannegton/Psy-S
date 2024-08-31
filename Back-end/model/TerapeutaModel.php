<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php'; //inclui o arquivo de configuração

class Terapeuta{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = getDbConnection();
        } catch (PDOException $e) {
            echo 'Conexão falhou: ' . $e->getMessage();
            exit(); // Encerrar a execução do script se a conexão falhar
        }
    }

    
}