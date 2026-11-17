<?php
// =====================================================
//  CATÁLOGO INTERNO E CONTROLE DE ESTOQUE (4.2)
//  Funcionário: consulta, adiciona e edita produtos.
//  Administrador: também pode inativar / reativar.
// =====================================================
require 'includes/auth.php';
exigirLogin();

// ---------- Salvar (novo ou edição) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'salvar') {
    $id        = (int) ($_POST['id'] ?? 0);
    $nome      = trim($_POST['nome'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $tipo      = ($_POST['tipo'] ?? '') === 'servico' ? 'servico' : 'produto';
    $preco     = paraNumero($_POST['preco'] ?? 0);
    $estoque   = $tipo === 'servico' ? 0 : max(0, (int) ($_POST['estoque'] ?? 0));
    $minimo    = $tipo === 'servico' ? 0 : max(0, (int) ($_POST['estoque_minimo'] ?? 0));

    if ($nome === '' || $categoria === '' || $preco <= 0) {
        avisar('Preencha nome, categoria e um preço maior que zero.', 'erro');
        ir('produtos.php' . ($id ? "?editar=$id" : ''));
    }

    if ($id) {
        $sql = $pdo->prepare('UPDATE produtos SET nome=?, categoria=?, tipo=?, preco=?, estoque=?, estoque_minimo=? WHERE id=?');
        $sql->execute([$nome, $categoria, $tipo, $preco, $estoque, $minimo, $id]);
        $mensagem = "Produto \"$nome\" atualizado.";
    } else {
        $sql = $pdo->prepare('INSERT INTO produtos (nome, categoria, tipo, preco, estoque, estoque_minimo) VALUES (?,?,?,?,?,?)');
        $sql->execute([$nome, $categoria, $tipo, $preco, $estoque, $minimo]);
        $id = (int) $pdo->lastInsertId();
        $mensagem = "Produto \"$nome\" cadastrado.";
    }

        avisar($mensagem);
    ir('produtos.php');
}

// ---------- Produto que está sendo editado ----------
$editando = null;
if (!empty($_GET['editar'])) {
    $sql = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
    $sql->execute([(int) $_GET['editar']]);
    $editando = $sql->fetch();
}

// ---------- Listagem com filtros ----------
$busca = trim($_GET['busca'] ?? '');

$sql = 'SELECT * FROM produtos WHERE 1=1';
$params = [];
if ($busca !== '') {
    $sql .= ' AND (nome LIKE ? OR categoria LIKE ?)';
    $params[] = "%$busca%";
    $params[] = "%$busca%";
}
$sql .= ' ORDER BY ativo DESC, categoria, nome';
$consulta = $pdo->prepare($sql);
$consulta->execute($params);
$produtos = $consulta->fetchAll();

// Categorias já usadas, para sugerir no formulário
$categorias = $pdo->query('SELECT DISTINCT categoria FROM produtos ORDER BY categoria')->fetchAll(PDO::FETCH_COLUMN);

$titulo = 'Produtos e estoque';
$pagina = 'produtos';
require 'includes/header.php';
?>

<h1>Produtos e estoque</h1>
<p class="subtitulo">Cadastre produtos e serviços e acompanhe a quantidade disponível.</p>

<div class="grade grade-form">

    <!-- Formulário -->
    <div class="cartao">
        <h2><?= $editando ? 'Editar produto' : 'Novo produto' ?></h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">

            <label>Nome</label>
            <input type="text" name="nome" required value="<?= e($editando['nome'] ?? '') ?>">

            <label>Categoria</label>
            <input type="text" name="categoria" list="lista-categorias" required value="<?= e($editando['categoria'] ?? '') ?>">
            <datalist id="lista-categorias">
                <?php foreach ($categorias as $c): ?><option value="<?= e($c) ?>"><?php endforeach; ?>
            </datalist>

            <div class="linha-form">
                <div>
                    <label>Tipo</label>
                    <select name="tipo" id="tipo" onchange="trocarTipo()">
                        <option value="produto">Produto</option>
                        <option value="servico" <?= ($editando['tipo'] ?? '') === 'servico' ? 'selected' : '' ?>>Serviço</option>
                    </select>
                </div>
                <div>
                    <label>Preço (R$)</label>
                    <input type="text" name="preco" required placeholder="0,00"
                           value="<?= isset($editando['preco']) ? number_format($editando['preco'], 2, ',', '') : '' ?>">
                </div>
            </div>

            <div class="linha-form" id="campos-estoque">
                <div>
                    <label>Estoque</label>
                    <input type="number" name="estoque" min="0" value="<?= $editando['estoque'] ?? 0 ?>">
                </div>
                <div>
                    <label>Estoque mínimo</label>
                    <input type="number" name="estoque_minimo" min="0" value="<?= $editando['estoque_minimo'] ?? 5 ?>">
                </div>
            </div>
            <p class="dica" id="dica-servico" style="display:none">Serviços (impressão, encadernação...) não usam estoque.</p>


            <div class="acoes">
                <button class="botao botao-destaque"><?= $editando ? 'Salvar alterações' : 'Cadastrar' ?></button>
                <?php if ($editando): ?>
                    <a class="botao botao-claro" href="produtos.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Lista -->
    <div class="cartao">
        <form method="get" class="filtros">
            <div style="flex:1">
                <label>Buscar</label>
                <input type="text" name="busca" value="<?= e($busca) ?>" placeholder="Nome ou categoria">
            </div>
            <button class="botao">Filtrar</button>
        </form>

        <div class="tabela-box">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="numero">Preço</th>
                        <th class="numero">Estoque</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($produtos as $p): ?>
                    <tr class="<?= $p['ativo'] ? '' : 'inativo' ?>">
                        <td class="celula-produto">
                            <div>
                            <strong><?= e($p['nome']) ?></strong><br>
                            <small><?= e($p['categoria']) ?><?= $p['ativo'] ? '' : ' · INATIVO' ?></small>
                            </div>
                        </td>
                        <td class="numero"><?= dinheiro($p['preco']) ?></td>
                        <td class="numero">
                            <?php if ($p['tipo'] === 'servico'): ?>
                                <span class="selo selo-servico">Serviço</span>
                            <?php elseif ($p['estoque'] == 0): ?>
                                <span class="selo selo-esgotado">Esgotado</span>
                            <?php else: ?>
                                <span class="selo selo-ok"><?= $p['estoque'] ?> un.</span>
                            <?php endif; ?>
                        </td>
                        <td class="numero">
                            <a href="?editar=<?= $p['id'] ?>">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$produtos): ?>
                    <tr><td colspan="4" class="vazio">Nenhum produto encontrado.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Esconde os campos de estoque quando o tipo é "Serviço"
function trocarTipo() {
    const servico = document.getElementById('tipo').value === 'servico';
    document.getElementById('campos-estoque').style.display = servico ? 'none' : 'flex';
    document.getElementById('dica-servico').style.display = servico ? 'block' : 'none';
}
trocarTipo();
</script>

<?php require 'includes/footer.php'; ?>
