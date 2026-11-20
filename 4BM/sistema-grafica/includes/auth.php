<?php
// =====================================================
//  Controle de acesso.
//  Coloque no topo de cada página interna:
//    require 'includes/auth.php';  exigirLogin();
//    ou exigirAdmin(); nas páginas da pasta admin/
// =====================================================

require_once __DIR__ . '/funcoes.php';

// $raiz serve para montar o caminho certo do login.php:
//   ''    nas páginas da raiz   -> login.php
//   '../' nas páginas de admin/ -> ../login.php
function exigirLogin($raiz = '') {
    global $pdo; // usa a conexão criada no conexao.php

    // 1) Ninguém logado? Manda para o login.
    if (!usuarioLogado()) {
        avisar('Faça login para acessar o sistema.', 'erro');
        ir($raiz . 'login.php');
    }

    // 2) Confere no banco se a conta continua ativa e se o nível mudou
    // (assim, se o admin desativar alguém, a pessoa perde o acesso na hora)
    $sql = $pdo->prepare('SELECT id, nome, login, nivel, ativo FROM usuarios WHERE id = ?');
    $sql->execute([usuarioLogado()['id']]);
    $u = $sql->fetch();

    if (!$u || !$u['ativo']) {
        unset($_SESSION['usuario']);
        avisar('Sua conta foi desativada. Fale com o administrador.', 'erro');
        ir($raiz . 'login.php');
    }

    // 3) Atualiza a sessão com os dados mais recentes do banco
    unset($u['ativo']);
    $_SESSION['usuario'] = $u;
}

// Usada nas páginas de admin/: primeiro exige login,
// depois bloqueia quem tem nível "funcionario" (requisito 4.1 do plano)
function exigirAdmin($raiz = '../') {
    exigirLogin($raiz);
    if (!ehAdmin()) {
        avisar('Acesso permitido apenas para administradores.', 'erro');
        ir($raiz . 'inicio.php');
    }
}
