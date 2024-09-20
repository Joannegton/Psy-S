<?php
// stmt - statement
declare(strict_types=1);

// Verifica se as constantes já estão definidas para evitar redefinições
if (!defined('ROOT_PATH')) {
    // Define o caminho raiz do aplicativo, que é o diretório onde este arquivo está localizado
    define('ROOT_PATH', __DIR__ . "/");
}

if (!defined('DB_DRIVE')) {
    // Define o tipo de driver de banco de dados a ser usado (por exemplo, mysql, pgsql)
    define('DB_DRIVE', 'mysql');
}

if (!defined('DB_HOST')) {
    // Define o endereço do servidor de banco de dados (geralmente 'localhost' para desenvolvimento local)
    define('DB_HOST', 'localhost');
}

if (!defined('DB_NAME')) {
    // Define o nome do banco de dados que será utilizado
    define('DB_NAME', 'psy_terapeuta_virtual');
}

if (!defined('DB_USER')) {
    // Define o nome de usuário para acesso ao banco de dados
    define('DB_USER', 'root');
}

if (!defined('DB_PASS')) {
    // Define a senha para acesso ao banco de dados (deixe em branco se não houver senha)
    define('DB_PASS', '');
}

if (!defined('APP_NAME')) {
    // Define o nome do aplicativo
    define('APP_NAME', 'Psy, Seu Terapeuta Virtual');
}
