<?php
// =====================================================
//  Controle de acesso.
//  Coloque no topo de cada página interna:
//    require 'includes/auth.php';  exigirLogin();
//    ou exigirAdmin(); nas páginas da pasta admin/
// =====================================================

require_once __DIR__ . '/funcoes.php';

function exigirLogin($raiz = '') {
    global $pdo;

    if (!usuarioLogado()) {
        avisar('Faça login para acessar o sistema.', 'erro');
        ir($raiz . 'login.php');
    }

    // Confere no banco se a conta continua ativa e se o nível mudou
    // (assim, se o admin desativar alguém, a pessoa perde o acesso na hora)
    $sql = $pdo->prepare('SELECT id, nome, login, nivel, ativo FROM usuarios WHERE id = ?');
    $sql->execute([usuarioLogado()['id']]);
    $u = $sql->fetch();

    if (!$u || !$u['ativo']) {
        unset($_SESSION['usuario']);
        avisar('Sua conta foi desativada. Fale com o administrador.', 'erro');
        ir($raiz . 'login.php');
    }

    unset($u['ativo']);
    $_SESSION['usuario'] = $u;
}

function exigirAdmin($raiz = '../') {
    exigirLogin($raiz);
    if (!ehAdmin()) {
        avisar('Acesso permitido apenas para administradores.', 'erro');
        ir($raiz . 'inicio.php');
    }
}
