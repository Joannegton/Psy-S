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
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Define o modo de fetch padrão
        return $pdo;
    } catch (PDOException $e) {
        // Utiliza error_log para registrar o erro em um arquivo de log, sem expor detalhes ao usuário
        error_log('Conexão falhou: ' . $e->getMessage(), 3, __DIR__ . '/../logs/error.log');
        throw new PDOException('Conexão com o banco de dados não pode ser estabelecida.'); // Mensagem genérica para o usuário
    }
}
