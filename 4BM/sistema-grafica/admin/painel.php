<?php
// =====================================================
//  PAINEL ADMINISTRATIVO (4.6) - só administrador
//  Resumo do mês atual: vendas, gastos, lucro, estoque baixo.
// =====================================================
require '../includes/auth.php';
require '../includes/resumo.php';
exigirAdmin();

$inicio = date('Y-m-01');
$fim    = date('Y-m-d');
$r      = resumoPeriodo($pdo, $inicio, $fim);
$baixo  = produtosEstoqueBaixo($pdo);

// Últimas vendas
$ultimas = $pdo->query('SELECT v.id, v.total, v.data_venda, u.nome AS funcionario
                        FROM vendas v JOIN usuarios u ON u.id = v.usuario_id
                        ORDER BY v.data_venda DESC LIMIT 5')->fetchAll();

$meses = ['', 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
          'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];

$titulo = 'Painel administrativo';
$pagina = 'painel';
$raiz   = '../';
require '../includes/header.php';
?>

<div class="acoes" style="justify-content:space-between">
    <div>
        <h1>Painel administrativo</h1>
        <p class="subtitulo">Resumo de <?= $meses[(int) date('n')] ?> de <?= date('Y') ?></p>
    </div>
    <a class="botao" href="relatorio.php">Gerar relatório</a>
</div>

<div class="indicadores">
    <div class="indicador">
        <span>Total de vendas (ganhos)</span>
        <strong><?= dinheiro($r['total_vendas']) ?></strong>
        <small><?= $r['qtd_vendas'] ?> venda(s) · média <?= dinheiro($r['ticket_medio']) ?></small>
    </div>
    <div class="indicador">
        <span>Gastos registrados</span>
        <strong><?= dinheiro($r['total_gastos']) ?></strong>
        <small><a href="gastos.php">ver gastos</a></small>
    </div>
    <div class="indicador">
        <span>Lucro estimado</span>
        <strong class="<?= $r['lucro'] >= 0 ? 'positivo' : 'negativo' ?>"><?= dinheiro($r['lucro']) ?></strong>
        <small>vendas − gastos</small>
    </div>
    <div class="indicador">
        <span>Estoque baixo</span>
        <strong class="<?= $baixo ? 'negativo' : '' ?>"><?= count($baixo) ?></strong>
        <small>produto(s) precisando de reposição</small>
    </div>
</div>

<div class="grade grade-2">
    <div class="cartao">
        <h2>Mais vendidos no mês</h2>
        <?php if (!$r['mais_vendidos']): ?>
            <p class="vazio">Nenhuma venda este mês.</p>
        <?php else:
            $maior = max(array_column($r['mais_vendidos'], 'valor'));
            foreach ($r['mais_vendidos'] as $p): ?>
                <div class="barra-linha">
                    <span><?= e($p['nome']) ?></span>
                    <div class="barra"><i style="width: <?= round($p['valor'] / $maior * 100) ?>%"></i></div>
                    <strong><?= dinheiro($p['valor']) ?></strong>
                </div>
        <?php endforeach; endif; ?>
    </div>

    <div class="cartao">
        <h2>Produtos com estoque baixo</h2>
        <?php if (!$baixo): ?>
            <p class="vazio">Tudo certo com o estoque.</p>
        <?php else: ?>
            <table>
                <tr><th>Produto</th><th class="numero">Atual</th><th class="numero">Mínimo</th></tr>
                <?php foreach ($baixo as $p): ?>
                    <tr>
                        <td><?= e($p['nome']) ?></td>
                        <td class="numero"><span class="selo <?= $p['estoque'] == 0 ? 'selo-esgotado' : 'selo-baixo' ?>"><?= $p['estoque'] ?></span></td>
                        <td class="numero"><?= $p['estoque_minimo'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <div class="cartao">
        <h2>Últimas vendas</h2>
        <table>
            <tr><th>Nº</th><th>Data</th><th>Funcionário</th><th class="numero">Total</th></tr>
            <?php foreach ($ultimas as $v): ?>
                <tr>
                    <td>#<?= $v['id'] ?></td>
                    <td><?= dataBr($v['data_venda'], true) ?></td>
                    <td><?= e($v['funcionario']) ?></td>
                    <td class="numero"><?= dinheiro($v['total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p style="margin-top:12px"><a href="../vendas_historico.php">Ver histórico completo →</a></p>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
