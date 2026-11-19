<?php
// =====================================================
//  CADASTRO DE FUNCIONÁRIOS (4.8) - só administrador
//  Cadastrar, editar, alterar nível e desativar contas.
// =====================================================
require '../includes/auth.php';
exigirAdmin();

$eu = usuarioLogado();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $id   = (int) ($_POST['id'] ?? 0);

    if ($acao === 'salvar') {
        $nome  = trim($_POST['nome'] ?? '');
        $login = trim($_POST['login'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $nivel = ($_POST['nivel'] ?? '') === 'admin' ? 'admin' : 'funcionario';

        // O admin não pode tirar o próprio acesso de administrador
        if ($id === (int) $eu['id']) {
            $nivel = 'admin';
        }

        // Verifica se o login já é usado por outra pessoa
        $sql = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE login = ? AND id <> ?');
        $sql->execute([$login, $id]);
        $loginRepetido = $sql->fetchColumn() > 0;

        if ($nome === '' || $login === '') {
            avisar('Preencha nome e login.', 'erro');
        } elseif ($loginRepetido) {
            avisar("O login \"$login\" já está em uso.", 'erro');
        } elseif (!$id && strlen($senha) < 6) {
            avisar('A senha precisa ter pelo menos 6 caracteres.', 'erro');
        } elseif ($id && $senha !== '' && strlen($senha) < 6) {
            avisar('A nova senha precisa ter pelo menos 6 caracteres.', 'erro');
        } elseif ($id) {
            $sql = $pdo->prepare('UPDATE usuarios SET nome=?, login=?, nivel=? WHERE id=?');
            $sql->execute([$nome, $login, $nivel, $id]);
            // Senha só muda se o campo foi preenchido
            if ($senha !== '') {
                $sql = $pdo->prepare('UPDATE usuarios SET senha=? WHERE id=?');
                $sql->execute([password_hash($senha, PASSWORD_DEFAULT), $id]);
            }
            avisar("Dados de $nome atualizados.");
            ir('funcionarios.php');
        } else {
            $sql = $pdo->prepare('INSERT INTO usuarios (nome, login, senha, nivel) VALUES (?,?,?,?)');
            $sql->execute([$nome, $login, password_hash($senha, PASSWORD_DEFAULT), $nivel]);
            avisar("$nome foi cadastrado(a).");
            ir('funcionarios.php');
        }
        ir('funcionarios.php' . ($id ? "?editar=$id" : ''));
    }

    if ($acao === 'situacao') {
        if ($id === (int) $eu['id']) {
            avisar('Você não pode desativar a sua própria conta.', 'erro');
        } else {
            $sql = $pdo->prepare('UPDATE usuarios SET ativo = 1 - ativo WHERE id = ?');
            $sql->execute([$id]);
            avisar('Situação da conta alterada.');
        }
        ir('funcionarios.php');
    }
}

$editando = null;
if (!empty($_GET['editar'])) {
    $sql = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
    $sql->execute([(int) $_GET['editar']]);
    $editando = $sql->fetch();
}

$usuarios = $pdo->query('SELECT u.*, (SELECT COUNT(*) FROM vendas v WHERE v.usuario_id = u.id) AS vendas
                         FROM usuarios u ORDER BY u.ativo DESC, u.nome')->fetchAll();

$titulo = 'Funcionários';
$pagina = 'funcionarios';
$raiz   = '../';
require '../includes/header.php';
?>

<h1>Funcionários</h1>
<p class="subtitulo">Somente o administrador cadastra e gerencia as contas de acesso.</p>

<div class="grade grade-form">
    <div class="cartao">
        <h2><?= $editando ? 'Editar funcionário' : 'Novo funcionário' ?></h2>
        <form method="post">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">

            <label>Nome completo</label>
            <input type="text" name="nome" required value="<?= e($editando['nome'] ?? '') ?>">

            <label>Login</label>
            <input type="text" name="login" required value="<?= e($editando['login'] ?? '') ?>">

            <label><?= $editando ? 'Nova senha' : 'Senha' ?></label>
            <input type="password" name="senha" <?= $editando ? '' : 'required' ?> minlength="6">
            <?php if ($editando): ?>
                <p class="dica">Deixe em branco para manter a senha atual.</p>
            <?php endif; ?>

            <label>Nível de acesso</label>
            <select name="nivel" <?= ($editando['id'] ?? 0) == $eu['id'] ? 'disabled' : '' ?>>
                <option value="funcionario">Funcionário</option>
                <option value="admin" <?= ($editando['nivel'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>

            <div class="acoes">
                <button class="botao botao-destaque"><?= $editando ? 'Salvar alterações' : 'Cadastrar' ?></button>
                <?php if ($editando): ?>
                    <a class="botao botao-claro" href="funcionarios.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="cartao">
        <div class="tabela-box">
            <table>
                <thead>
                    <tr><th>Nome</th><th>Login</th><th>Nível</th><th class="numero">Vendas</th><th class="numero">Situação</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($usuarios as $usu): ?>
                    <tr class="<?= $usu['ativo'] ? '' : 'inativo' ?>">
                        <td><strong><?= e($usu['nome']) ?></strong><?= $usu['id'] == $eu['id'] ? ' <small>(você)</small>' : '' ?></td>
                        <td><?= e($usu['login']) ?></td>
                        <td><span class="selo <?= $usu['nivel'] === 'admin' ? 'selo-admin' : '' ?>"><?= $usu['nivel'] === 'admin' ? 'Administrador' : 'Funcionário' ?></span></td>
                        <td class="numero"><?= $usu['vendas'] ?></td>
                        <td class="numero">
                            <span class="selo <?= $usu['ativo'] ? 'selo-ok' : 'selo-esgotado' ?>"><?= $usu['ativo'] ? 'Ativo' : 'Desativado' ?></span>
                        </td>
                        <td class="numero">
                            <a href="?editar=<?= $usu['id'] ?>">Editar</a>
                            <?php if ($usu['id'] != $eu['id']): ?>
                                &nbsp;
                                <form method="post" class="inline">
                                    <input type="hidden" name="acao" value="situacao">
                                    <input type="hidden" name="id" value="<?= $usu['id'] ?>">
                                    <button class="botao-link"><?= $usu['ativo'] ? 'Desativar' : 'Reativar' ?></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
