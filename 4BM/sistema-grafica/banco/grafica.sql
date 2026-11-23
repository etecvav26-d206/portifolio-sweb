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
('Cópia A4 colorida',                   'Cópias e impressões', 'servico',  1.00, 0, 0),
('Cópia A4 branca e preta',             'Cópias e impressões', 'servico',  0.50, 0, 0),
('Impressão colorida',                  'Cópias e impressões', 'servico',  1.00, 0, 0),
('Impressão branca e preta',            'Cópias e impressões', 'servico',  0.50, 0, 0),
('Encadernação A4 até 100 folhas',      'Acabamento',          'servico',  6.00, 0, 0),
('Encadernação A3',                     'Acabamento',          'servico',  8.00, 0, 0),
('Plastificação A4',                    'Acabamento',          'servico',  4.00, 0, 0),
('Plastificação de documentos',         'Acabamento',          'servico',  2.50, 0, 0),
('Caderno 10 matérias',                 'Material escolar',    'produto', 20.00, 30, 5),
('Caneta Bic azul',                     'Material escolar',    'produto',  2.00, 120, 20),
('Caneta Bic preta',                    'Material escolar',    'produto',  2.00, 100, 20),
('Caneta Bic vermelha',                 'Material escolar',    'produto',  2.00, 80, 20),
('Lápis HB nº 02',                      'Material escolar',    'produto',  1.00, 80, 20),
('Borracha branca pequena',             'Material escolar',    'produto',  2.00, 4, 10),
('Apontador com reserva',               'Material escolar',    'produto',  4.00, 25, 5),
('Apontador sem reserva',               'Material escolar',    'produto',  2.00, 40, 10),
('Calculadora',                         'Material escolar',    'produto', 12.00, 10, 3),
('Cola bastão pequena',                 'Cola e fitas',        'produto',  4.00, 30, 5),
('Cola bastão grande',                  'Cola e fitas',        'produto',  5.00, 20, 5),
('Cola líquida pequena',                'Cola e fitas',        'produto',  4.00, 25, 5),
('Bastão de cola quente',               'Cola e fitas',        'produto',  3.50, 15, 5),
('Durex pequeno',                       'Cola e fitas',        'produto',  4.00, 20, 5),
('Corretivo em fita',                   'Material escolar',    'produto',  8.00, 12, 4),
('Cadeado',                             'Diversos',            'produto',  8.00, 8, 2),
('Pasta plástica com elástico pequena', 'Papelaria',           'produto',  6.00, 12, 5),
('Papel sulfite A4 branca ou colorida', 'Papelaria',           'produto',  0.10, 3, 5),
('Papel milimetrado A4',                'Papelaria',           'produto',  0.50, 60, 10),
('Papel canson A4',                     'Papelaria',           'produto',  1.00, 50, 10),
('Post-it',                             'Papelaria',           'produto',  5.00, 18, 5),
('Régua acrílica 30 cm',                'Material escolar',    'produto',  5.00, 15, 5);

-- Algumas vendas de exemplo nos últimos dias
-- (DATE_SUB(NOW(), INTERVAL 6 DAY) = 6 dias antes da instalação,
--  assim o painel sempre tem dados do mês na apresentação)
INSERT INTO vendas (usuario_id, total, data_venda) VALUES
(2, 44.00, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(2, 25.00, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 56.00, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(2, 38.00, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(2, 100.00, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(1, 132.00, DATE_SUB(NOW(), INTERVAL 1 DAY));

INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario, subtotal) VALUES
(1, 9, 2, 20.00, 40.00),
(1, 10, 2, 2.00,  4.00),
(2, 4, 50, 0.50, 25.00),
(3, 17, 2, 12.00, 24.00),
(3, 9, 1, 20.00, 20.00),
(3, 24, 1, 8.00,  8.00),
(3, 15, 1, 4.00,  4.00),
(4, 3, 30, 1.00, 30.00),
(4, 5, 1,  6.00,  6.00),
(4, 10, 1, 2.00,  2.00),
(5, 7, 10, 4.00, 40.00),
(5, 6, 5,  8.00, 40.00),
(5, 1, 20, 1.00, 20.00),
(6, 17, 5, 12.00, 60.00),
(6, 9, 3, 20.00, 60.00),
(6, 23, 1, 8.00,  8.00),
(6, 20, 1, 4.00,  4.00);

UPDATE vendas SET observacao = 'Trabalho de escola - cliente volta para buscar' WHERE id = 4;

INSERT INTO gastos (descricao, valor, categoria, data_gasto, usuario_id) VALUES
('Tinta para impressora (kit 4 cores)', 180.00, 'Insumos',     DATE_SUB(CURDATE(), INTERVAL 4 DAY), 1),
('Conta de energia',                    95.00,  'Contas',      DATE_SUB(CURDATE(), INTERVAL 2 DAY), 1),
('Manutenção da impressora laser',      60.00,  'Manutenção',  DATE_SUB(CURDATE(), INTERVAL 1 DAY), 1);

-- Fotos dos produtos (arquivos em assets/img/produtos/)
UPDATE produtos SET imagem = 'produto_10_1795099730.jpg' WHERE id = 1;
UPDATE produtos SET imagem = 'produto_9_1795099730.jpg' WHERE id = 2;
UPDATE produtos SET imagem = 'produto_10_1795099730.jpg' WHERE id = 3;
UPDATE produtos SET imagem = 'produto_9_1795099730.jpg' WHERE id = 4;
UPDATE produtos SET imagem = 'produto_11_1795099730.jpg' WHERE id = 5;
UPDATE produtos SET imagem = 'produto_11_1795099730.jpg' WHERE id = 6;
UPDATE produtos SET imagem = 'produto_12_1795099730.jpg' WHERE id = 7;
UPDATE produtos SET imagem = 'produto_12_1795099730.jpg' WHERE id = 8;
UPDATE produtos SET imagem = 'produto_1_1795099729.jpg' WHERE id = 9;
UPDATE produtos SET imagem = 'produto_2_1795099730.jpg' WHERE id = 10;
UPDATE produtos SET imagem = 'produto_3_1795099730.jpg' WHERE id = 13;
UPDATE produtos SET imagem = 'produto_4_1795099730.jpg' WHERE id = 14;
UPDATE produtos SET imagem = 'produto_5_1795099730.jpg' WHERE id = 25;
UPDATE produtos SET imagem = 'produto_6_1795099730.jpg' WHERE id = 26;
