<?php
// =====================================================
//  MODELO de conexão com o banco.
//  Copie este arquivo como "conexao.php" e ajuste os dados.
//  O conexao.php NÃO vai para o Git (está no .gitignore).
// =====================================================

$host  = '127.0.0.1';
$banco = 'grafica';
$user  = 'root';
$senha = '';          // no XAMPP a senha padrão do root é vazia

// Portas que o sistema tenta, em ordem. A padrão do XAMPP é 3306;
// se o PC já tiver outro MySQL usando a 3306, o XAMPP pode ficar na 3307.
$portas = [3306, 3307];

// $pdo é o objeto que todas as páginas usam para conversar com o banco
$pdo = null;
foreach ($portas as $porta) {
    try {
        // "charset=utf8mb4" garante que acentos (ç, ã, é...) sejam salvos certinho
        $pdo = new PDO("mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4", $user, $senha, [
            // Se der erro no SQL, o PHP lança uma exceção (em vez de falhar em silêncio)
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // fetch() devolve um array com o nome das colunas: $linha['nome']
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Espera no máximo 2 segundos por porta antes de tentar a próxima
            PDO::ATTR_TIMEOUT            => 2,
        ]);
        break; // conectou, para de tentar
    } catch (PDOException $e) {
        $pdo = null; // não conectou nesta porta, tenta a próxima
    }
}

// Nenhuma porta funcionou: mostra uma mensagem simples e para a página
if (!$pdo) {
    die('Erro ao conectar com o banco. Verifique se o MySQL está ligado no XAMPP. Se for a primeira vez, abra <a href="/grafica/instalar.php">instalar.php</a> para criar o banco.');
}

// Horário de Brasília para as datas das vendas e dos relatórios
date_default_timezone_set('America/Sao_Paulo');
