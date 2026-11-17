<?php
require 'includes/funcoes.php';

// Apaga os dados da sessão e volta para o login
$_SESSION = [];
session_destroy();

session_start();
avisar('Você saiu do sistema.');
ir('login.php');
