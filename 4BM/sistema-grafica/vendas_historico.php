<?php
// =====================================================
//  HISTÓRICO DE VENDAS (4.5)
//  Mostra data, itens, valor total e funcionário responsável.
// =====================================================
require 'includes/auth.php';
exigirLogin();

// Filtros (padrão: mês atual)
$inicio      = $_GET['inicio'] ?? date('Y-m-01');
$fim         = $_GET['fim']    ?? date('Y-m-d');
$funcionario = (int) ($_GET['funcionario'] ?? 0);

$sql = 'SELECT v.*, u.nome AS funcionario
        FROM vendas v
        JOIN usuarios u ON u.id = v.usuario_id
        WHERE DATE(v.data_venda) BETWEEN ? AND ?';
$params = [$inicio, $fim];
if ($funcionario) {
    $sql .= ' AND v.usuario_id = ?';
    $params[] = $funcionario;
}
$sql .= ' ORDER BY v.data_venda DESC';

$consulta = $pdo->prepare($sql);
$consulta->execute($params);
$vendas = $consulta->fetchAll();

// Busca os itens de cada venda
$buscaItens = $pdo->prepare('SELECT i.*, p.nome FROM itens_venda i
                             JOIN produtos p ON p.id = i.produto_id
                             WHERE i.venda_id = ?');
$somaTotal = 0;
foreach ($vendas as &$v) {
    $buscaItens->execute([$v['id']]);
    $v['itens'] = $buscaItens->fetchAll();
    $somaTotal += $v['total'];
}
unset($v);

$funcionarios = $pdo->query('SELECT id, nome FROM usuarios ORDER BY nome')->fetchAll();

$titulo = 'Histórico de vendas';
$pagina = 'historico';
require 'includes/header.php';
?>

<div class="acoes" style="justify-content:space-between">
    <div>
        <h1>Histórico de vendas</h1>
        <p class="subtitulo">Vendas e pedidos já registrados no sistema.</p>
    </div>
    <a class="botao botao-destaque" href="vendas_nova.php">+ Nova venda</a>
</div>

<div class="cartao">
    <form method="get" class="filtros">
        <div>
            <label>De</label>
            <input type="date" name="inicio" value="<?= e($inicio) ?>">
        </div>
        <div>
            <label>Até</label>
            <input type="date" name="fim" value="<?= e($fim) ?>">
        </div>
        <div>
            <label>Funcionário</label>
            <select name="funcionario">
                <option value="0">Todos</option>
                <?php foreach ($funcionarios as $f): ?>
                    <option value="<?= $f['id'] ?>" <?= $f['id'] == $funcionario ? 'selected' : '' ?>><?= e($f['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="botao">Filtrar</button>
    </form>

    <div class="tabela-box">
        <table>
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Data</th>
                    <th>Itens</th>
                    <th>Funcionário</th>
                    <th class="numero">Total</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($vendas as $v): ?>
                <tr>
                    <td>#<?= $v['id'] ?></td>
                    <td><?= dataBr($v['data_venda'], true) ?></td>
                    <td>
                        <details>
                            <summary><?= count($v['itens']) ?> item(ns)</summary>
                            <ul>
                                <?php foreach ($v['itens'] as $item): ?>
                                    <li><?= $item['quantidade'] ?>× <?= e($item['nome']) ?> — <?= dinheiro($item['subtotal']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if ($v['observacao']): ?>
                                <p style="font-size:13px;margin-top:6px"><strong>Obs.:</strong> <?= e($v['observacao']) ?></p>
                            <?php endif; ?>
                        </details>
                    </td>
                    <td><?= e($v['funcionario']) ?></td>
                    <td class="numero"><strong><?= dinheiro($v['total']) ?></strong></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$vendas): ?>
                <tr><td colspan="5" class="vazio">Nenhuma venda neste período.</td></tr>
            <?php endif; ?>
            </tbody>
            <?php if ($vendas): ?>
                <tfoot>
                    <tr>
                        <td colspan="4"><?= count($vendas) ?> venda(s) no período</td>
                        <td class="numero"><?= dinheiro($somaTotal) ?></td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
