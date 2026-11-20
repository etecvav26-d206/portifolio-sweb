<?php
// =====================================================
//  LOGIN (4.1) - acesso só para funcionários cadastrados
// =====================================================
require 'includes/funcoes.php';

// Se já está logado, vai direto para o início
if (usuarioLogado()) {
    ir('inicio.php');
}

$erro = '';
$loginDigitado = '';

// O formulário foi enviado (o método POST só acontece quando clica em "Entrar")
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginDigitado = trim($_POST['login'] ?? ''); // trim tira espaços do começo e do fim
    $senha = $_POST['senha'] ?? '';

    // Prepared statement: o "?" é trocado pelo valor com segurança,
    // o que impede SQL Injection (ex.: digitar ' OR 1=1 -- no login)
    $sql = $pdo->prepare('SELECT * FROM usuarios WHERE login = ?');
    $sql->execute([$loginDigitado]);
    $usuario = $sql->fetch();

    // password_verify compara a senha digitada com o hash salvo no banco.
    // A mensagem é a mesma para login errado e senha errada, para não
    // revelar quais logins existem no sistema.
    if (!$usuario || !password_verify($senha, $usuario['senha'])) {
        $erro = 'Login ou senha incorretos.';
    } elseif (!$usuario['ativo']) {
        $erro = 'Esta conta está desativada. Fale com o administrador.';
    } else {
        session_regenerate_id(true); // segurança: novo id de sessão após login
        // Guarda na sessão só o necessário (nunca a senha)
        $_SESSION['usuario'] = [
            'id'    => $usuario['id'],
            'nome'  => $usuario['nome'],
            'login' => $usuario['login'],
            'nivel' => $usuario['nivel'],
        ];
        ir('inicio.php');
    }
}

// A partir daqui é o HTML da tela de login
$titulo = 'Entrar';
$publico = true; // usa o topo público (sem o menu lateral)
require 'includes/header.php';
?>

<?php if (imagem('fachada.jpg')): ?>
    <div class="login-foto" style="background-image: url('assets/img/fachada.jpg')"></div>
<?php endif; ?>

<div class="login-box">
    <h1>Área do funcionário</h1>
    <p class="subtitulo">Entre com sua conta para acessar o sistema</p>

    <div class="cartao">
        <?php if ($erro): ?>
            <div class="aviso aviso-erro"><?= e($erro) ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="login">Login</label>
            <input type="text" id="login" name="login" value="<?= e($loginDigitado) ?>" required autofocus>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" required>

            <button class="botao botao-destaque">Entrar</button>
        </form>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
