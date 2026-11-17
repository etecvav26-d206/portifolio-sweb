<?php
// =====================================================
//  Arquivo carregado em TODAS as páginas.
//  Inicia a sessão, conecta no banco e traz funções úteis.
// =====================================================

session_start();
require_once __DIR__ . '/../config/conexao.php';

// Nome que aparece no topo das páginas e no relatório
const NOME_GRAFICA = 'Gráfica da ETEC';

// Imagens reais (coloque os arquivos na pasta assets/img/)
//   logo.png     -> logo da ETEC / APM (aparece no topo e no menu)
//   fachada.jpg  -> foto da escola ou da gráfica (aparece no catálogo e no login)
function imagem($nome) {
    return file_exists(__DIR__ . '/../assets/img/' . $nome);
}

// Evita que textos digitados quebrem o HTML (proteção contra XSS)
function e($texto) {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

// 12.5 -> "R$ 12,50"
function dinheiro($valor) {
    return 'R$ ' . number_format((float) $valor, 2, ',', '.');
}

// "2026-11-19 10:30:00" -> "19/11/2026 10:30"
function dataBr($data, $comHora = false) {
    $formato = $comHora ? 'd/m/Y H:i' : 'd/m/Y';
    return date($formato, strtotime($data));
}

// Aceita "12,50" ou "12.50" vindo dos formulários
function paraNumero($texto) {
    $texto = str_replace(['R$', ' ', '.'], '', (string) $texto);
    $texto = str_replace(',', '.', $texto);
    return (float) $texto;
}

// Mensagens que aparecem uma vez depois de um redirecionamento
function avisar($mensagem, $tipo = 'sucesso') {
    $_SESSION['aviso'] = ['msg' => $mensagem, 'tipo' => $tipo];
}

function mostrarAviso() {
    if (!empty($_SESSION['aviso'])) {
        $a = $_SESSION['aviso'];
        echo '<div class="aviso aviso-' . e($a['tipo']) . '">' . e($a['msg']) . '</div>';
        unset($_SESSION['aviso']);
    }
}

function ir($pagina) {
    header('Location: ' . $pagina);
    exit;
}

function usuarioLogado() {
    return $_SESSION['usuario'] ?? null;
}

function ehAdmin() {
    return (usuarioLogado()['nivel'] ?? '') === 'admin';
}
