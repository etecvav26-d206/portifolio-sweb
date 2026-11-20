<?php
// =====================================================
//  Arquivo carregado em TODAS as páginas.
//  Inicia a sessão, conecta no banco e traz funções úteis.
// =====================================================

// A sessão guarda dados do usuário entre uma página e outra
// (é assim que o sistema "lembra" quem fez login)
session_start();

// __DIR__ é a pasta deste arquivo; assim o caminho funciona tanto
// nas páginas da raiz quanto nas da pasta admin/
require_once __DIR__ . '/../config/conexao.php';

// Nome que aparece no topo das páginas e no relatório
// (const = valor fixo, que não muda durante o programa)
const NOME_GRAFICA = 'Gráfica da ETEC';

// Imagens reais (coloque os arquivos na pasta assets/img/)
//   logo.png     -> logo da ETEC / APM (aparece no topo e no menu)
//   fachada.jpg  -> foto da escola ou da gráfica (aparece no catálogo e no login)
function imagem($nome) {
    return file_exists(__DIR__ . '/../assets/img/' . $nome);
}

// Evita que textos digitados quebrem o HTML (proteção contra XSS).
// Ex.: se alguém cadastrar o produto "<script>alert(1)</script>",
// ele aparece na tela como texto e o código NÃO é executado.
// Todo dado que vem do banco ou do usuário passa por e() antes de ser exibido.
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

// Converte o preço digitado no formato brasileiro para número.
// "R$ 1.234,50" -> tira "R$", espaços e o ponto de milhar -> "1234,50"
//               -> troca a vírgula por ponto -> 1234.50
function paraNumero($texto) {
    $texto = str_replace(['R$', ' ', '.'], '', (string) $texto);
    $texto = str_replace(',', '.', $texto);
    return (float) $texto;
}

// Mensagens que aparecem uma vez depois de um redirecionamento.
// Como a página é recarregada, a mensagem fica guardada na sessão
// até ser mostrada (ex.: "Venda registrada!").
function avisar($mensagem, $tipo = 'sucesso') {
    $_SESSION['aviso'] = ['msg' => $mensagem, 'tipo' => $tipo];
}

// Chamada no header.php: mostra o aviso guardado e apaga em seguida,
// para ele não aparecer de novo na próxima página
function mostrarAviso() {
    if (!empty($_SESSION['aviso'])) {
        $a = $_SESSION['aviso'];
        echo '<div class="aviso aviso-' . e($a['tipo']) . '">' . e($a['msg']) . '</div>';
        unset($_SESSION['aviso']);
    }
}

// Redireciona para outra página e encerra o script.
// O exit é importante: sem ele o resto da página continuaria rodando.
function ir($pagina) {
    header('Location: ' . $pagina);
    exit;
}

// Devolve os dados de quem está logado (id, nome, login, nível)
// ou null se ninguém fez login. O "??" evita erro quando a chave não existe.
function usuarioLogado() {
    return $_SESSION['usuario'] ?? null;
}

// true se o usuário logado for administrador
function ehAdmin() {
    return (usuarioLogado()['nivel'] ?? '') === 'admin';
}
