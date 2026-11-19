<?php
// =====================================================
//  INSTALADOR - cria o banco "grafica" a partir de banco/grafica.sql
//  É só abrir http://localhost/grafica/instalar.php uma vez.
//  (Faz o mesmo que importar o .sql pelo phpMyAdmin.)
// =====================================================

$host = '127.0.0.1';
$user = 'root';
$senha = '';
$portas = [3306, 3307]; // mesma ordem do config/conexao.php

$mensagem = '';
$tipo = '';

$pdo = null;
foreach ($portas as $porta) {
    try {
        $pdo = new PDO("mysql:host=$host;port=$porta;charset=utf8mb4", $user, $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 2,
        ]);
        break;
    } catch (PDOException $e) {
        $pdo = null;
    }
}
if (!$pdo) {
    die('Não foi possível conectar ao MySQL. Ligue o MySQL no painel do XAMPP e recarregue a página.');
}

$jaExiste = (bool) $pdo->query("SHOW DATABASES LIKE 'grafica'")->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($jaExiste && empty($_POST['confirmo'])) {
        $mensagem = 'Marque a confirmação para reinstalar (os dados atuais serão apagados).';
        $tipo = 'erro';
    } else {
        // Lê o arquivo .sql, tira os comentários e executa comando por comando
        $linhas = file(__DIR__ . '/banco/grafica.sql');
        $sql = '';
        foreach ($linhas as $linha) {
            if (strpos(trim($linha), '--') === 0) continue;
            $sql .= $linha;
        }
        foreach (explode(";\n", str_replace("\r", '', $sql)) as $comando) {
            if (trim($comando) !== '') {
                $pdo->exec($comando);
            }
        }
        $mensagem = 'Banco de dados instalado com sucesso!';
        $tipo = 'sucesso';
        $jaExiste = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Instalação | Sistema da Gráfica</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="publico">
<main class="conteudo-publico">
    <div class="login-box" style="max-width:480px">
        <h1>Instalação</h1>
        <p class="subtitulo">Cria o banco de dados <strong>grafica</strong> com os dados de exemplo.</p>

        <div class="cartao">
            <?php if ($mensagem): ?>
                <div class="aviso aviso-<?= $tipo ?>"><?= htmlspecialchars($mensagem) ?></div>
            <?php endif; ?>

            <?php if ($tipo === 'sucesso'): ?>
                <p style="margin-bottom:14px">Logins de teste: <strong>admin / admin123</strong> e <strong>maria / func123</strong>.</p>
                <a class="botao botao-destaque" href="login.php">Ir para o sistema</a>
            <?php else: ?>
                <form method="post">
                    <?php if ($jaExiste): ?>
                        <p style="margin-bottom:14px">O banco já está instalado. Reinstalar apaga tudo e volta aos dados de exemplo.</p>
                        <label style="display:flex;gap:8px;font-weight:400">
                            <input type="checkbox" name="confirmo" value="1" style="width:auto;margin:0">
                            Entendo que os dados atuais serão apagados
                        </label>
                        <br>
                        <button class="botao botao-perigo">Reinstalar banco</button>
                        <a class="botao botao-claro" href="login.php">Voltar ao sistema</a>
                    <?php else: ?>
                        <button class="botao botao-destaque">Instalar banco de dados</button>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </div>
</main>
</body>
</html>
