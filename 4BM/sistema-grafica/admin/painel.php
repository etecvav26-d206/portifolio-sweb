<?php
// =====================================================
//  PAINEL ADMINISTRATIVO (4.6) - só administrador
//  Resumo do mês atual: vendas, gastos, lucro, estoque baixo.
// =====================================================
require '../includes/auth.php';
require '../includes/resumo.php';
exigirAdmin();

// Período do painel: do dia 1 do mês atual até hoje
$inicio = date('Y-m-01');
$fim    = date('Y-m-d');
// Os cálculos ficam no includes/resumo.php (os mesmos usados no relatório)
$r      = resumoPeriodo($pdo, $inicio, $fim);
$baixo  = produtosEstoqueBaixo($pdo);

// Últimas vendas
$ultimas = $pdo->query('SELECT v.id, v.total, v.data_venda, u.nome AS funcionario
                        FROM vendas v JOIN usuarios u ON u.id = v.usuario_id
                        ORDER BY v.data_venda DESC LIMIT 5')->fetchAll();

// Nome do mês em português (date('n') devolve 1 a 12; a posição 0 fica vazia)
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

<!-- Os 4 números principais do mês (requisito 4.6) -->
<div class="indicadores">
    <div class="indicador">
        <span>Vendas</span>
        <strong><?= dinheiro($r['total_vendas']) ?></strong>
    </div>
    <div class="indicador">
        <span>Gastos</span>
        <strong><?= dinheiro($r['total_gastos']) ?></strong>
    </div>
    <div class="indicador">
        <span>Lucro estimado</span>
        <strong class="<?= $r['lucro'] >= 0 ? 'positivo' : 'negativo' ?>"><?= dinheiro($r['lucro']) ?></strong>
    </div>
    <div class="indicador">
        <span>Quantidade de vendas</span>
        <strong><?= $r['qtd_vendas'] ?></strong>
    </div>
</div>

<div class="grade grade-2">
    <div class="cartao">
        <h2>Mais vendidos no mês</h2>
        <?php if (!$r['mais_vendidos']): ?>
            <p class="vazio">Nenhuma venda este mês.</p>
        <?php else:
            // Gráfico de barras feito só com CSS: o mais vendido ocupa 100%
            // e os outros ficam proporcionais a ele
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
        <h2>Vendas por funcionário</h2>
        <table>
            <tr><th>Funcionário</th><th class="numero">Vendas</th><th class="numero">Total</th></tr>
            <?php foreach ($r['por_funcionario'] as $f): ?>
                <tr><td><?= e($f['nome']) ?></td><td class="numero"><?= $f['qtd'] ?></td><td class="numero"><?= dinheiro($f['total']) ?></td></tr>
            <?php endforeach; ?>
            <?php if (!$r['por_funcionario']): ?>
                <tr><td colspan="3" class="vazio">Sem vendas no mês.</td></tr>
            <?php endif; ?>
        </table>
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
            <?php if (!$ultimas): ?>
                <tr><td colspan="4" class="vazio">Nenhuma venda registrada ainda.</td></tr>
            <?php endif; ?>
        </table>
        <p style="margin-top:12px"><a href="../vendas_historico.php">Ver histórico completo</a></p>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
