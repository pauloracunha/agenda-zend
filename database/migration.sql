CREATE DATABASE IF NOT EXISTS contatos;
USE contatos;

CREATE TABLE IF NOT EXISTS cliente (
    id int(11) NOT NULL auto_increment,
    nome varchar(100) NOT NULL,
    cnpj char(14) NOT NULL,
    endereco varchar(100) NOT NULL,
    status int(1) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS contato (
    id int(11) NOT NULL auto_increment,
    id_cliente INT(11) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cpf CHAR(11) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id) ON DELETE CASCADE
);