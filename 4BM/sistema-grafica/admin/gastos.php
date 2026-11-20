<?php
// =====================================================
//  REGISTRO DE GASTOS (4.7) - só administrador
// =====================================================
require '../includes/auth.php';
exigirAdmin();

// Categorias sugeridas no formulário (o admin também pode digitar outra)
$categoriasPadrao = ['Insumos', 'Manutenção', 'Contas', 'Mercadoria para revenda', 'Outros'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    // Novo gasto: descrição, valor, data e categoria (requisito 4.7)
    if ($acao === 'salvar') {
        $descricao = trim($_POST['descricao'] ?? '');
        $valor     = paraNumero($_POST['valor'] ?? 0);
        $categoria = trim($_POST['categoria'] ?? '');
        $data      = $_POST['data_gasto'] ?? '';

        // strtotime devolve false se a data for inválida
        if ($descricao === '' || $valor <= 0 || $categoria === '' || !strtotime($data)) {
            avisar('Preencha todos os campos com valores válidos.', 'erro');
        } else {
            $sql = $pdo->prepare('INSERT INTO gastos (descricao, valor, categoria, data_gasto, usuario_id) VALUES (?,?,?,?,?)');
            $sql->execute([$descricao, $valor, $categoria, $data, usuarioLogado()['id']]);
            avisar('Gasto registrado.');
        }
    }

    // Excluir serve para corrigir um lançamento feito errado
    if ($acao === 'excluir') {
        $sql = $pdo->prepare('DELETE FROM gastos WHERE id = ?');
        $sql->execute([(int) $_POST['id']]);
        avisar('Gasto excluído.');
    }

    // Recarrega a página no mesmo mês (evita reenviar o formulário com F5)
    ir('gastos.php?mes=' . urlencode($_GET['mes'] ?? date('Y-m')));
}

// Mês exibido na lista (padrão: mês atual)
$mes = $_GET['mes'] ?? date('Y-m');
// Confere se o mês está no formato "2026-11"; se não estiver, usa o mês atual
if (!preg_match('/^\d{4}-\d{2}$/', $mes)) {
    $mes = date('Y-m');
}
$inicio = "$mes-01";
$fim    = date('Y-m-t', strtotime($inicio)); // último dia do mês

$sql = $pdo->prepare('SELECT g.*, u.nome AS registrado_por FROM gastos g
                      JOIN usuarios u ON u.id = g.usuario_id
                      WHERE g.data_gasto BETWEEN ? AND ?
                      ORDER BY g.data_gasto DESC, g.id DESC');
$sql->execute([$inicio, $fim]);
$gastos = $sql->fetchAll();
// array_column pega só a coluna "valor" de cada gasto; array_sum soma tudo
$total = array_sum(array_column($gastos, 'valor'));

// Junta as categorias padrão com as que já foram usadas
$usadas = $pdo->query('SELECT DISTINCT categoria FROM gastos')->fetchAll(PDO::FETCH_COLUMN);
$categorias = array_unique(array_merge($categoriasPadrao, $usadas));

$titulo = 'Gastos';
$pagina = 'gastos';
$raiz   = '../';
require '../includes/header.php';
?>

<h1>Gastos</h1>
<p class="subtitulo">Despesas da gráfica.</p>

<div class="grade grade-form">
    <div class="cartao">
        <h2>Novo gasto</h2>
        <form method="post">
            <input type="hidden" name="acao" value="salvar">

            <label>Descrição</label>
            <input type="text" name="descricao" required placeholder="Ex.: Toner da impressora">

            <div class="linha-form">
                <div>
                    <label>Valor (R$)</label>
                    <input type="text" name="valor" required placeholder="0,00">
                </div>
                <div>
                    <label>Data</label>
                    <input type="date" name="data_gasto" required value="<?= date('Y-m-d') ?>">
                </div>
            </div>

            <label>Categoria</label>
            <input type="text" name="categoria" list="lista-categorias" required>
            <datalist id="lista-categorias">
                <?php foreach ($categorias as $c): ?><option value="<?= e($c) ?>"><?php endforeach; ?>
            </datalist>

            <button class="botao botao-destaque">Registrar gasto</button>
        </form>
    </div>

    <div class="cartao">
        <form method="get" class="filtros">
            <div>
                <label>Mês</label>
                <input type="month" name="mes" value="<?= e($mes) ?>">
            </div>
            <button class="botao">Ver</button>
        </form>

        <div class="tabela-box">
            <table>
                <thead>
                    <tr><th>Data</th><th>Descrição</th><th>Categoria</th><th class="numero">Valor</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($gastos as $g): ?>
                    <tr>
                        <td><?= dataBr($g['data_gasto']) ?></td>
                        <td><?= e($g['descricao']) ?><br><small style="color:var(--texto-suave)">por <?= e($g['registrado_por']) ?></small></td>
                        <td><span class="selo"><?= e($g['categoria']) ?></span></td>
                        <td class="numero"><?= dinheiro($g['valor']) ?></td>
                        <td class="numero">
                            <form method="post" class="inline" onsubmit="return confirm('Excluir este gasto?')">
                                <input type="hidden" name="acao" value="excluir">
                                <input type="hidden" name="id" value="<?= $g['id'] ?>">
                                <button class="botao-link" style="color:var(--vermelho)">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$gastos): ?>
                    <tr><td colspan="5" class="vazio">Nenhum gasto neste mês.</td></tr>
                <?php endif; ?>
                </tbody>
                <?php if ($gastos): ?>
                    <tfoot><tr><td colspan="3">Total do mês</td><td class="numero"><?= dinheiro($total) ?></td><td></td></tr></tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
