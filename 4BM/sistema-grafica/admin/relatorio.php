<?php
// =====================================================
//  RELATÓRIOS (4.9) - só administrador
//  Escolhe o período, vê uma prévia e baixa em PDF
//  para encaminhar à diretoria / APM.
// =====================================================
require '../includes/auth.php';
require '../includes/resumo.php';
exigirAdmin();

$inicio = $_GET['inicio'] ?? date('Y-m-01');
$fim    = $_GET['fim']    ?? date('Y-m-d');

if ($inicio > $fim) {
    [$inicio, $fim] = [$fim, $inicio]; // se inverteram as datas, desinverte
}

$r = resumoPeriodo($pdo, $inicio, $fim);

$titulo = 'Relatórios';
$pagina = 'relatorio';
$raiz   = '../';
require '../includes/header.php';
?>

<h1>Relatórios</h1>
<p class="subtitulo">Gere o relatório financeiro de um período em PDF para enviar à diretoria ou à APM.</p>

<div class="cartao">
    <form method="get" class="filtros">
        <div>
            <label>De</label>
            <input type="date" name="inicio" value="<?= e($inicio) ?>" required>
        </div>
        <div>
            <label>Até</label>
            <input type="date" name="fim" value="<?= e($fim) ?>" required>
        </div>
        <button class="botao">Atualizar prévia</button>
        <a class="botao botao-destaque" href="relatorio_pdf.php?inicio=<?= urlencode($inicio) ?>&fim=<?= urlencode($fim) ?>" target="_blank">
            Baixar PDF
        </a>
    </form>
    <p class="dica" style="margin:0">
        Atalhos:
        <a href="?inicio=<?= date('Y-m-01') ?>&fim=<?= date('Y-m-d') ?>">este mês</a> ·
        <a href="?inicio=<?= date('Y-m-01', strtotime('first day of last month')) ?>&fim=<?= date('Y-m-t', strtotime('first day of last month')) ?>">mês passado</a> ·
        <a href="?inicio=<?= date('Y-01-01') ?>&fim=<?= date('Y-m-d') ?>">este ano</a>
    </p>
</div>

<h2>Prévia: <?= dataBr($inicio) ?> a <?= dataBr($fim) ?></h2>

<div class="indicadores">
    <div class="indicador">
        <span>Vendas (ganhos)</span>
        <strong><?= dinheiro($r['total_vendas']) ?></strong>
        <small><?= $r['qtd_vendas'] ?> venda(s)</small>
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
        <span>Valor médio por venda</span>
        <strong><?= dinheiro($r['ticket_medio']) ?></strong>
    </div>
</div>

<div class="grade grade-2">
    <div class="cartao">
        <h2>Mais vendidos</h2>
        <table>
            <tr><th>Produto</th><th class="numero">Qtd.</th><th class="numero">Valor</th></tr>
            <?php foreach ($r['mais_vendidos'] as $p): ?>
                <tr><td><?= e($p['nome']) ?></td><td class="numero"><?= $p['qtd'] ?></td><td class="numero"><?= dinheiro($p['valor']) ?></td></tr>
            <?php endforeach; ?>
            <?php if (!$r['mais_vendidos']): ?><tr><td colspan="3" class="vazio">Sem vendas.</td></tr><?php endif; ?>
        </table>
    </div>
    <div class="cartao">
        <h2>Gastos por categoria</h2>
        <table>
            <tr><th>Categoria</th><th class="numero">Valor</th></tr>
            <?php foreach ($r['gastos_categoria'] as $g): ?>
                <tr><td><?= e($g['categoria']) ?></td><td class="numero"><?= dinheiro($g['total']) ?></td></tr>
            <?php endforeach; ?>
            <?php if (!$r['gastos_categoria']): ?><tr><td colspan="2" class="vazio">Sem gastos.</td></tr><?php endif; ?>
        </table>
    </div>
</div>

<p class="dica" style="margin-top:8px">
    O envio do relatório não é automático: depois de baixar o PDF, o responsável encaminha
    para a diretoria/APM pelo meio combinado (por exemplo, e-mail).
</p>

<?php require '../includes/footer.php'; ?>
