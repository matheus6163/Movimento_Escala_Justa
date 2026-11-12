/*Criação do banco de dados e da tabela de usuários*/
CREATE DATABASE meu_mvc CHARACTER SET utf8mb4;
USE meu_mvc;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY, 
name VARCHAR(100) NOT NULL,
last_name VARCHAR(100) NOT NULL,
email VARCHAR(150) NOT NULL UNIQUE,
);
