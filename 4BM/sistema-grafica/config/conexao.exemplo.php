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

$pdo = null;
foreach ($portas as $porta) {
    try {
        $pdo = new PDO("mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4", $user, $senha, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT            => 2,
        ]);
        break; // conectou, para de tentar
    } catch (PDOException $e) {
        $pdo = null;
    }
}

if (!$pdo) {
    die('Erro ao conectar com o banco. Verifique se o MySQL está ligado no XAMPP. Se for a primeira vez, abra <a href="/grafica/instalar.php">instalar.php</a> para criar o banco.');
}

date_default_timezone_set('America/Sao_Paulo');
