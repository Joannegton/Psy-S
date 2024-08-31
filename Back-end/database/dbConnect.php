<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php'; // Inclui o arquivo de configuração

/**
 * Função para obter a conexão PDO com o banco de dados
 *
 * @return PDO
 * @throws PDOException
 */
function getDbConnection(): PDO
{
    try {
        $pdo = new PDO('mysql:host=' . dbHost . ';dbname=' . dbName . ';charset=utf8', dbUser, dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        throw new PDOException('Conexão falhou: ' . $e->getMessage());
    }
}