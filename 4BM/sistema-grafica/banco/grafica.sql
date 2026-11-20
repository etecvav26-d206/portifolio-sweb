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
-- 2) PRODUTOS E SERVIÇOS
--    Serviços (impressão, encadernação...) não usam estoque.
-- -----------------------------------------------------
CREATE TABLE produtos (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(100) NOT NULL,
    categoria       VARCHAR(50)  NOT NULL,
    tipo            ENUM('produto','servico') NOT NULL DEFAULT 'produto',
    preco           DECIMAL(10,2) NOT NULL,
    estoque         INT NOT NULL DEFAULT 0,
    estoque_minimo  INT NOT NULL DEFAULT 5,        -- abaixo disso aparece no painel
    imagem          VARCHAR(255) NULL,             -- foto (arquivo em assets/img/produtos/)
    ativo           TINYINT(1) NOT NULL DEFAULT 1, -- inativar em vez de excluir
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- 3) VENDAS (cabeçalho da venda)
--    FOREIGN KEY (chave estrangeira) liga a venda ao
--    funcionário que a registrou: o banco não deixa
--    salvar uma venda com um usuario_id que não existe.
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
--    Uma venda pode ter vários itens (relação 1 para N).
--    O preço é copiado para cá: se o preço do produto
--    mudar depois, as vendas antigas não mudam.
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

-- -----------------------------------------------------
-- 5) GASTOS
-- -----------------------------------------------------
CREATE TABLE gastos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    descricao   VARCHAR(150) NOT NULL,
    valor       DECIMAL(10,2) NOT NULL,
    categoria   VARCHAR(50)  NOT NULL,
    data_gasto  DATE NOT NULL,
    usuario_id  INT NOT NULL,                      -- quem registrou
    criado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- =====================================================
--  DADOS INICIAIS (para testar e apresentar)
-- =====================================================

-- Usuários de teste
--   admin / admin123   (administrador)
--   maria / func123    (funcionária)
-- As senhas abaixo já estão no formato de password_hash() ("$2y$10$...")
INSERT INTO usuarios (nome, login, senha, nivel) VALUES
('Administrador', 'admin', '$2y$10$0oYNQkK/sQrQWzUvGGoHcuHKaYpNt74RyZFzwacdR80E1hK9p6iyW', 'admin'),
('Maria Souza',   'maria', '$2y$10$30gh2LL5lgdiU4UJS0lPweMIziJM.YELog/Y4fjDESdmfvocUs0LK', 'funcionario');

INSERT INTO produtos (nome, categoria, tipo, preco, estoque, estoque_minimo) VALUES
('Caderno universitário 10 matérias', 'Material escolar', 'produto', 24.90, 30, 5),
('Caneta esferográfica azul',         'Material escolar', 'produto',  2.50, 120, 20),
('Lápis preto nº 2',                  'Material escolar', 'produto',  1.50, 80, 20),
('Borracha branca',                   'Material escolar', 'produto',  1.80, 4, 10),
('Pasta catálogo 50 folhas',          'Material escolar', 'produto', 14.00, 12, 5),
('Resma papel A4 500 folhas',         'Papelaria',        'produto', 32.00, 3, 5),
('Camiseta personalizada',            'Personalizados',   'produto', 45.00, 18, 5),
('Caneca personalizada',              'Personalizados',   'produto', 30.00, 0, 3),
('Impressão P&B (por folha)',         'Impressão',        'servico',  0.50, 0, 0),
('Impressão colorida (por folha)',    'Impressão',        'servico',  2.00, 0, 0),
('Encadernação espiral',              'Acabamento',       'servico',  8.00, 0, 0),
('Plastificação A4',                  'Acabamento',       'servico',  5.00, 0, 0),
('Banner 60x90 cm',                   'Impressão',        'servico', 55.00, 0, 0);

-- Algumas vendas de exemplo nos últimos dias
-- (DATE_SUB(NOW(), INTERVAL 6 DAY) = 6 dias antes da instalação,
--  assim o painel sempre tem dados do mês na apresentação)
INSERT INTO vendas (usuario_id, total, data_venda) VALUES
(2, 54.80, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(2, 25.00, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 90.00, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(2, 38.00, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(2, 165.00, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(1, 135.00, DATE_SUB(NOW(), INTERVAL 1 DAY));

INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario, subtotal) VALUES
(1, 1, 2, 24.90, 49.80),
(1, 2, 2,  2.50,  5.00),
(2, 9, 50, 0.50, 25.00),
(3, 7, 2, 45.00, 90.00),
(4, 10, 15, 2.00, 30.00),
(4, 11, 1,  8.00,  8.00),
(5, 13, 3, 55.00, 165.00),
(6, 7, 3, 45.00, 135.00);

UPDATE vendas SET observacao = 'Trabalho de escola - cliente volta para buscar' WHERE id = 4;

INSERT INTO gastos (descricao, valor, categoria, data_gasto, usuario_id) VALUES
('Tinta para impressora (kit 4 cores)', 180.00, 'Insumos',     DATE_SUB(CURDATE(), INTERVAL 4 DAY), 1),
('Conta de energia',                    95.00,  'Contas',      DATE_SUB(CURDATE(), INTERVAL 2 DAY), 1),
('Manutenção da impressora laser',      60.00,  'Manutenção',  DATE_SUB(CURDATE(), INTERVAL 1 DAY), 1);

-- Fotos dos produtos (arquivos em assets/img/produtos/)
UPDATE produtos SET imagem = 'produto_1_1795099729.jpg'  WHERE id = 1;
UPDATE produtos SET imagem = 'produto_2_1795099730.jpg'  WHERE id = 2;
UPDATE produtos SET imagem = 'produto_3_1795099730.jpg'  WHERE id = 3;
UPDATE produtos SET imagem = 'produto_4_1795099730.jpg'  WHERE id = 4;
UPDATE produtos SET imagem = 'produto_5_1795099730.jpg'  WHERE id = 5;
UPDATE produtos SET imagem = 'produto_6_1795099730.jpg'  WHERE id = 6;
UPDATE produtos SET imagem = 'produto_7_1795228822.jpg'  WHERE id = 7;
UPDATE produtos SET imagem = 'produto_8_1795228822.jpg'  WHERE id = 8;
UPDATE produtos SET imagem = 'produto_9_1795099730.jpg'  WHERE id = 9;
UPDATE produtos SET imagem = 'produto_10_1795099730.jpg' WHERE id = 10;
UPDATE produtos SET imagem = 'produto_11_1795099730.jpg' WHERE id = 11;
UPDATE produtos SET imagem = 'produto_12_1795099730.jpg' WHERE id = 12;
UPDATE produtos SET imagem = 'produto_13_1795228822.jpg' WHERE id = 13;
