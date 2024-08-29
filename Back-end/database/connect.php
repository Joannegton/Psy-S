<?php

declare(strict_types=1);

//declaração das variáveis globais
const dbDrive = 'mysql'; //protocolo de acesso
const dbHost = 'localhost'; //endereço ou link do servidor
const dbName = 'psy_terapeuta_virtual'; //nome do seu Banco de dados (database)
const dbUser = 'root'; //login local
const dbPass =''; //senha

//criando a conexão com o banco de dados
try {
    $pdo = new PDO(dbDrive . ':host=' . dbHost . ';dbname=' . dbName, dbUser, dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //configuração de erros
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}