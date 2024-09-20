-- Criação do banco de dados
CREATE DATABASE psy_terapeuta_virtual;

-- Uso do banco de dados
USE psy_terapeuta_virtual;

-- Criação da tabela de Usuários
CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    id_terapeuta VARCHAR(255) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    sexo ENUM('M', 'F', 'Outro') NOT NULL
);

-- Criação da tabela de Terapeuta
CREATE TABLE Terapeuta (
    id_terapeuta VARCHAR(100) NOT NULL UNIQUE,
    nome_terapeuta VARCHAR(100) NOT NULL,
    data_nascimento DATE 
);

-- Criação da tabela de Interações
CREATE TABLE Interacao (
    id_interacao INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_terapeuta TEXT NOT NULL,
    data_hora DATETIME NOT NULL,
    mensagem TEXT NOT NULL,
    tipo ENUM('Usuario', 'Terapeuta') NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- Criação da tabela de Conteúdo Educacional
CREATE TABLE Conteudos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    link TEXT,
    tipo ENUM('article', 'video', 'meditation') NOT NULL
);


-- Criação da tabela de Profissionais
CREATE TABLE Profissional (
    id_profissional INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(100) NOT NULL,
    crth VARCHAR(20)
    estrelas DECIMAL(2,1) DEFAULT 0.0
);

-- Criação da tabela de Sugestões
CREATE TABLE Sugestao (
    id_sugestao INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_profissional INT NOT NULL,
    data_sugestao DATE NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_profissional) REFERENCES Profissional(id_profissional) ON DELETE CASCADE
);


