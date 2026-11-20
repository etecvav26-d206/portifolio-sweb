<?php
// =====================================================
//  LOGOUT - encerra a sessão do usuário
// =====================================================
require 'includes/funcoes.php';

// Apaga os dados da sessão e volta para o login
$_SESSION = [];
session_destroy();

// Abre uma sessão nova e vazia só para guardar o aviso "Você saiu do sistema."
session_start();
avisar('Você saiu do sistema.');
ir('login.php');
