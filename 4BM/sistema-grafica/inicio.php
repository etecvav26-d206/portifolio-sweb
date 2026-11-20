<?php
// =====================================================
//  INÍCIO - primeira tela depois do login
// =====================================================
require 'includes/auth.php';
exigirLogin();

$u = usuarioLogado();

// Vendas de hoje feitas por este funcionário
// (CURDATE() é a data de hoje no MySQL)
$sql = $pdo->prepare('SELECT COUNT(*) AS qtd, COALESCE(SUM(total),0) AS soma
                      FROM vendas WHERE usuario_id = ? AND DATE(data_venda) = CURDATE()');
$sql->execute([$u['id']]);
$minhasHoje = $sql->fetch();

// Produtos com estoque baixo (aviso útil para quem está no balcão)
$estoqueBaixo = $pdo->query("SELECT nome, estoque FROM produtos
                             WHERE ativo = 1 AND tipo = 'produto' AND estoque <= estoque_minimo
                             ORDER BY estoque")->fetchAll();

$titulo = 'Início';
$pagina = 'inicio';
require 'includes/header.php';
?>

<?php // explode separa o nome nos espaços; [0] pega só o primeiro nome ?>
<h1>Olá, <?= e(explode(' ', $u['nome'])[0]) ?>!</h1>
<p class="subtitulo"><?= dataBr(date('Y-m-d')) ?></p>

<!-- Três números rápidos para o funcionário -->
<div class="indicadores">
    <div class="indicador">
        <span>Suas vendas hoje</span>
        <strong><?= $minhasHoje['qtd'] ?></strong>
    </div>
    <div class="indicador">
        <span>Valor vendido por você hoje</span>
        <strong><?= dinheiro($minhasHoje['soma']) ?></strong>
    </div>
    <div class="indicador">
        <span>Itens com estoque baixo</span>
        <strong class="<?= $estoqueBaixo ? 'negativo' : '' ?>"><?= count($estoqueBaixo) ?></strong>
    </div>
</div>

<!-- Atalhos para as telas mais usadas (o do painel só aparece para o admin) -->
<div class="atalhos" style="margin-bottom:24px">
    <a class="atalho" href="vendas_nova.php">
        <strong>+ Nova venda</strong>
        <span>Registrar venda ou pedido</span>
    </a>
    <a class="atalho" href="produtos.php">
        <strong>Produtos e estoque</strong>
        <span>Consultar e atualizar itens</span>
    </a>
    <a class="atalho" href="vendas_historico.php">
        <strong>Histórico</strong>
        <span>Vendas já registradas</span>
    </a>
    <?php if (ehAdmin()): ?>
        <a class="atalho" href="admin/painel.php">
            <strong>Painel administrativo</strong>
            <span>Resumo financeiro do mês</span>
        </a>
    <?php endif; ?>
</div>

<?php // Lista de produtos que precisam de reposição (só aparece se tiver algum) ?>
<?php if ($estoqueBaixo): ?>
    <div class="cartao">
        <h2>Atenção ao estoque</h2>
        <table>
            <tr><th>Produto</th><th class="numero">Quantidade</th></tr>
            <?php foreach ($estoqueBaixo as $p): ?>
                <tr>
                    <td><?= e($p['nome']) ?></td>
                    <td class="numero">
                        <span class="selo <?= $p['estoque'] == 0 ? 'selo-esgotado' : 'selo-baixo' ?>">
                            <?= $p['estoque'] == 0 ? 'Esgotado' : $p['estoque'] . ' un.' ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
