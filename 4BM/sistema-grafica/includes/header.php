<?php
// Variáveis que cada página pode definir antes de incluir este arquivo:
//   $titulo  -> título da aba
//   $raiz    -> '' para páginas na raiz, '../' para páginas em admin/
//   $pagina  -> identifica o item do menu que fica destacado
//   $publico -> true nas páginas sem login (catálogo e login)
$titulo  = $titulo  ?? NOME_GRAFICA;
$raiz    = $raiz    ?? '';
$pagina  = $pagina  ?? '';
$publico = $publico ?? false;
$u = usuarioLogado();

// Escreve um link do menu lateral. Se o $id do link for igual à $pagina
// atual, ele recebe a classe "ativo" e fica destacado em vermelho.
function itemMenu($link, $texto, $id, $pagina) {
    $classe = $id === $pagina ? ' class="ativo"' : '';
    echo "<a href=\"$link\"$classe>$texto</a>";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> | <?= e(NOME_GRAFICA) ?></title>
    <link rel="stylesheet" href="<?= $raiz ?>assets/css/style.css">
    <?php if (imagem('logo.png')): ?>
        <link rel="icon" type="image/png" href="<?= $raiz ?>assets/img/logo.png">
    <?php endif; ?>
</head>
<!-- A classe do body muda o layout: "publico" (barra no topo) ou "interno" (menu lateral) -->
<body class="<?= $publico ? 'publico' : 'interno' ?>">

<?php if ($publico): ?>
    <!-- ===== TOPO DAS PÁGINAS PÚBLICAS (catálogo e login) ===== -->
    <header class="topo-publico">
        <!-- Se existir assets/img/logo.png usa a imagem; senão, desenha 4 quadradinhos com CSS -->

        <a class="logo" href="<?= $raiz ?>catalogo.php">
            <?php if (imagem('logo.png')): ?>
                <img class="logo-img" src="<?= $raiz ?>assets/img/logo.png" alt="Logo">
            <?php else: ?>
                <span class="logo-cmyk"><i></i><i></i><i></i><i></i></span>
            <?php endif; ?>
            <?= e(NOME_GRAFICA) ?>
        </a>
        <nav>
            <a href="<?= $raiz ?>catalogo.php">Catálogo</a>
            <?php if ($u): ?>
                <a class="botao botao-pequeno" href="<?= $raiz ?>inicio.php">Área interna</a>
            <?php else: ?>
                <a class="botao botao-pequeno" href="<?= $raiz ?>login.php">Entrar</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="conteudo-publico">

<?php else: ?>
    <!-- ===== MENU LATERAL DA ÁREA INTERNA (só aparece depois do login) ===== -->
    <aside class="menu-lateral">
        <a class="logo" href="<?= $raiz ?>inicio.php">
            <?php if (imagem('logo.png')): ?>
                <img class="logo-img" src="<?= $raiz ?>assets/img/logo.png" alt="Logo">
            <?php else: ?>
                <span class="logo-cmyk"><i></i><i></i><i></i><i></i></span>
            <?php endif; ?>
            <?= e(NOME_GRAFICA) ?>
        </a>

        <nav>
            <span class="menu-titulo">Atendimento</span>
            <?php
            itemMenu($raiz . 'inicio.php',           'Início',            'inicio',    $pagina);
            itemMenu($raiz . 'vendas_nova.php',      'Nova venda',        'nova',      $pagina);
            itemMenu($raiz . 'vendas_historico.php', 'Histórico',         'historico', $pagina);
            itemMenu($raiz . 'produtos.php',         'Produtos e estoque','produtos',  $pagina);
            ?>

            <?php // Os links de administração só aparecem para o administrador.
                  // Mesmo assim, cada página de admin/ também chama exigirAdmin(),
                  // porque esconder o link não impede alguém de digitar o endereço. ?>
            <?php if (ehAdmin()): ?>
                <span class="menu-titulo">Administração</span>
                <?php
                itemMenu($raiz . 'admin/painel.php',       'Painel',       'painel',       $pagina);
                itemMenu($raiz . 'admin/gastos.php',       'Gastos',       'gastos',       $pagina);
                itemMenu($raiz . 'admin/funcionarios.php', 'Funcionários', 'funcionarios', $pagina);
                itemMenu($raiz . 'admin/relatorio.php',    'Relatórios',   'relatorio',    $pagina);
                ?>
            <?php endif; ?>

            <span class="menu-titulo">Site</span>
            <a href="<?= $raiz ?>catalogo.php" target="_blank">Catálogo público</a>
        </nav>

        <!-- Quem está logado, o nível e o botão de sair -->
        <div class="usuario-box">
            <strong><?= e($u['nome']) ?></strong>
            <span class="selo <?= $u['nivel'] === 'admin' ? 'selo-admin' : '' ?>">
                <?= $u['nivel'] === 'admin' ? 'Administrador' : 'Funcionário' ?>
            </span>
            <a href="<?= $raiz ?>logout.php">Sair</a>
        </div>
    </aside>
    <main class="conteudo">
<?php endif; ?>

<?php // Mostra a mensagem de sucesso/erro da última ação (se houver)
mostrarAviso(); ?>
