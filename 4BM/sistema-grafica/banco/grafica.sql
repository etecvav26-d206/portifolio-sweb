-- =====================================================
--  Sistema de Gestão para Gráfica - Banco de Dados
--  ETEC Vasco Antônio Venchiarutti - 2°D INFONET
-- =====================================================
--  Como usar: abra o phpMyAdmin > aba "Importar" >
--  escolha este arquivo > Executar.
-- =====================================================

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS grafica;
CREATE DATABASE grafica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE grafica;

-- -----------------------------------------------------
-- 1) USUÁRIOS (funcionários e administradores)
-- -----------------------------------------------------
CREATE TABLE usuarios (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(100) NOT NULL,
    login      VARCHAR(50)  NOT NULL UNIQUE,
    senha      VARCHAR(255) NOT NULL,              -- guardada com password_hash()
    nivel      ENUM('funcionario','admin') NOT NULL DEFAULT 'funcionario',
    ativo      TINYINT(1) NOT NULL DEFAULT 1,      -- 1 = ativo, 0 = desativado
    criado_em  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- 3) VENDAS (cabeçalho da venda)
-- -----------------------------------------------------
CREATE TABLE vendas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT NOT NULL,
    total       DECIMAL(10,2) NOT NULL,
    observacao  VARCHAR(255) NULL,
    data_venda  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- -----------------------------------------------------
-- 4) ITENS DA VENDA (o que foi vendido em cada venda)
-- -----------------------------------------------------
CREATE TABLE itens_venda (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    venda_id        INT NOT NULL,
    produto_id      INT NOT NULL,
    quantidade      INT NOT NULL,
    preco_unitario  DECIMAL(10,2) NOT NULL,        -- preço no momento da venda
    subtotal        DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (venda_id)   REFERENCES vendas(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- =====================================================
--  DADOS INICIAIS (para testar e apresentar)
-- =====================================================

-- Usuários de teste
--   admin / admin123   (administrador)
--   maria / func123    (funcionária)
INSERT INTO usuarios (nome, login, senha, nivel) VALUES
('Administrador', 'admin', '$2y$10$0oYNQkK/sQrQWzUvGGoHcuHKaYpNt74RyZFzwacdR80E1hK9p6iyW', 'admin'),
('Maria Souza',   'maria', '$2y$10$30gh2LL5lgdiU4UJS0lPweMIziJM.YELog/Y4fjDESdmfvocUs0LK', 'funcionario');

