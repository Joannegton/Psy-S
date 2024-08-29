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
    data_nascimento DATE NOT NULL,
    sexo ENUM('M', 'F', 'Outro') NOT NULL
);

-- Criação da tabela de Interações
CREATE TABLE Interacao (
    id_interacao INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    data_hora DATETIME NOT NULL,
    mensagem_usuario TEXT NOT NULL,
    resposta_ia TEXT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario) ON DELETE CASCADE
);

-- Criação da tabela de Conteúdo Educacional
CREATE TABLE Conteudo_Educacional (
    id_conteudo INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('Artigo', 'Video') NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    link VARCHAR(255) NOT NULL
);

-- Criação da tabela de Profissionais
CREATE TABLE Profissional (
    id_profissional INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especialidade VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100)
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
