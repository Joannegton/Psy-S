<?php
//stmt - statement
declare(strict_types=1);

//declaração das variáveis globais
// Verifica se as constantes já estão definidas antes de defini-las
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . "/"); // __DIR__ é uma constante mágica que retorna o diretório do arquivo atual
}

if (!defined('dbDrive')) {
    define('dbDrive', 'mysql'); //protocolo de acesso
}

if (!defined('dbHost')) {
    define('dbHost', 'localhost'); //endereço ou link do servidor
}

if (!defined('dbName')) {
    define('dbName', 'psy_terapeuta_virtual'); //nome do seu Banco de dados (database)
}

if (!defined('dbUser')) {
    define('dbUser', 'root'); //login local
}

if (!defined('dbPass')) {
    define('dbPass', ''); //senha
}

if (!defined('APP_NAME')) {
    define('APP_NAME', 'Psy, seu Terapeuta Virtual');
}


