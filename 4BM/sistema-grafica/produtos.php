<?php
// =====================================================
//  CATÁLOGO INTERNO E CONTROLE DE ESTOQUE (4.2)
//  Funcionário: consulta, adiciona e edita produtos.
//  Administrador: também pode inativar / reativar.
// =====================================================
require 'includes/auth.php';
exigirLogin();

// Salva a foto enviada e devolve o nome do arquivo (ou null se não veio foto).
// $_FILES['imagem'] tem os dados do arquivo enviado pelo <input type="file">:
// nome temporário (tmp_name), tamanho (size) e se deu erro (error).
function salvarFoto($idProduto) {
    if (empty($_FILES['imagem']) || $_FILES['imagem']['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $arquivo = $_FILES['imagem'];

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Não foi possível enviar a foto.');
    }
    if ($arquivo['size'] > 3 * 1024 * 1024) {
        throw new Exception('A foto pode ter no máximo 3 MB.');
    }

    // Confere se é mesmo uma imagem (não só pela extensão)
    $permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $info = @getimagesize($arquivo['tmp_name']);
    if (!$info || !isset($permitidos[$info['mime']])) {
        throw new Exception('Envie uma foto JPG, PNG ou WEBP.');
    }

    $pasta = __DIR__ . '/assets/img/produtos/';
    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }
    // Nome único: produto_ID_horário.jpg (o nome original do arquivo é ignorado,
    // assim ninguém consegue enviar um arquivo com nome perigoso)
    $nome = 'produto_' . $idProduto . '_' . time() . '.' . $permitidos[$info['mime']];
    // Move o arquivo da pasta temporária do PHP para a pasta do sistema
    move_uploaded_file($arquivo['tmp_name'], $pasta . $nome);
    return $nome;
}

// Apaga a foto antiga da pasta quando ela é trocada ou removida
function apagarFoto($nome) {
    if ($nome && is_file(__DIR__ . '/assets/img/produtos/' . $nome)) {
        unlink(__DIR__ . '/assets/img/produtos/' . $nome);
    }
}

// ---------- Salvar (novo ou edição) ----------
// O mesmo formulário serve para cadastrar e editar:
// se vier um id, é edição (UPDATE); se não vier, é cadastro novo (INSERT).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'salvar') {
    // (int) e max(0, ...) garantem números válidos e não negativos
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

    // Foto (opcional): se mandou uma nova, troca a antiga
    try {
        $sql = $pdo->prepare('SELECT imagem FROM produtos WHERE id = ?');
        $sql->execute([$id]);
        $fotoAntiga = $sql->fetchColumn();

        if (!empty($_POST['remover_foto'])) {
            apagarFoto($fotoAntiga);
            $pdo->prepare('UPDATE produtos SET imagem = NULL WHERE id = ?')->execute([$id]);
        }

        $novaFoto = salvarFoto($id);
        if ($novaFoto) {
            apagarFoto($fotoAntiga);
            $pdo->prepare('UPDATE produtos SET imagem = ? WHERE id = ?')->execute([$novaFoto, $id]);
        }
        avisar($mensagem);
    } catch (Exception $erro) {
        avisar($mensagem . ' Mas a foto não foi salva: ' . $erro->getMessage(), 'erro');
    }
    ir('produtos.php');
}

// ---------- Inativar / reativar (só admin) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'situacao') {
    if (!ehAdmin()) {
        avisar('Somente o administrador pode inativar produtos.', 'erro');
        ir('produtos.php');
    }
    // "1 - ativo" inverte o valor: 1 vira 0 (inativa) e 0 vira 1 (reativa).
    // O produto não é apagado para não estragar o histórico de vendas (requisito 4.2).
    $sql = $pdo->prepare('UPDATE produtos SET ativo = 1 - ativo WHERE id = ?');
    $sql->execute([(int) $_POST['id']]);
    avisar('Situação do produto alterada.');
    ir('produtos.php' . (isset($_GET['inativos']) ? '?inativos=1' : ''));
}

// ---------- Produto que está sendo editado ----------
// Ao clicar em "Editar", a URL fica produtos.php?editar=ID
// e o formulário é preenchido com os dados desse produto
$editando = null;
if (!empty($_GET['editar'])) {
    $sql = $pdo->prepare('SELECT * FROM produtos WHERE id = ?');
    $sql->execute([(int) $_GET['editar']]);
    $editando = $sql->fetch();
}

// ---------- Listagem com filtros ----------
$busca = trim($_GET['busca'] ?? '');
$mostrarInativos = isset($_GET['inativos']);

// "WHERE 1=1" é sempre verdadeiro; serve só para podermos ir
// acrescentando " AND ..." conforme os filtros escolhidos
$sql = 'SELECT * FROM produtos WHERE 1=1';
$params = [];
if (!$mostrarInativos) {
    $sql .= ' AND ativo = 1';
}
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

    <!-- Formulário de cadastro / edição -->
    <div class="cartao">
        <h2><?= $editando ? 'Editar produto' : 'Novo produto' ?></h2>
        <!-- enctype="multipart/form-data" é obrigatório para enviar arquivos (a foto) -->
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">

            <label>Nome</label>
            <input type="text" name="nome" required value="<?= e($editando['nome'] ?? '') ?>">

            <label>Categoria</label>
            <!-- datalist: sugere as categorias já usadas, mas deixa digitar uma nova -->
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

            <label>Foto (opcional)</label>
            <?php if (!empty($editando['imagem'])): ?>
                <img class="foto-previa" src="assets/img/produtos/<?= e($editando['imagem']) ?>" alt="">
                <label style="display:flex;gap:6px;font-weight:400;margin-bottom:10px">
                    <input type="checkbox" name="remover_foto" value="1" style="width:auto;margin:0"> Remover foto atual
                </label>
            <?php endif; ?>
            <input type="file" name="imagem" accept="image/jpeg,image/png,image/webp">
            <p class="dica">JPG, PNG ou WEBP de até 3 MB. Aparece no catálogo público.</p>

            <div class="acoes">
                <button class="botao botao-destaque"><?= $editando ? 'Salvar alterações' : 'Cadastrar' ?></button>
                <?php if ($editando): ?>
                    <a class="botao botao-claro" href="produtos.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Lista de produtos com busca e filtro de inativos -->
    <div class="cartao">
        <form method="get" class="filtros">
            <div style="flex:1">
                <label>Buscar</label>
                <input type="text" name="busca" value="<?= e($busca) ?>" placeholder="Nome ou categoria">
            </div>
            <label style="display:flex;gap:6px;align-items:center;font-weight:400">
                <input type="checkbox" name="inativos" value="1" style="width:auto;margin:0" <?= $mostrarInativos ? 'checked' : '' ?>>
                Mostrar inativos
            </label>
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
                            <?php if ($p['imagem']): ?>
                                <img class="miniatura" src="assets/img/produtos/<?= e($p['imagem']) ?>" alt="">
                            <?php else: ?>
                                <span class="miniatura miniatura-vazia"><?= e(mb_substr($p['nome'], 0, 1)) ?></span>
                            <?php endif; ?>
                            <div>
                            <strong><?= e($p['nome']) ?></strong><br>
                            <small><?= e($p['categoria']) ?><?= $p['ativo'] ? '' : ' (inativo)' ?></small>
                            </div>
                        </td>
                        <td class="numero"><?= dinheiro($p['preco']) ?></td>
                        <td class="numero">
                            <?php // Selo colorido: serviço, esgotado (vermelho), baixo (amarelo) ou ok (verde) ?>
                            <?php if ($p['tipo'] === 'servico'): ?>
                                <span class="selo selo-servico">Serviço</span>
                            <?php elseif ($p['estoque'] == 0): ?>
                                <span class="selo selo-esgotado">Esgotado</span>
                            <?php elseif ($p['estoque'] <= $p['estoque_minimo']): ?>
                                <span class="selo selo-baixo"><?= $p['estoque'] ?> un.</span>
                            <?php else: ?>
                                <span class="selo selo-ok"><?= $p['estoque'] ?> un.</span>
                            <?php endif; ?>
                        </td>
                        <td class="numero">
                            <a href="?editar=<?= $p['id'] ?>">Editar</a>
                            <?php // Botão de inativar só aparece para o admin (e o PHP lá em cima também confere) ?>
                            <?php if (ehAdmin()): ?>
                                &nbsp;
                                <form method="post" class="inline">
                                    <input type="hidden" name="acao" value="situacao">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button class="botao-link"><?= $p['ativo'] ? 'Inativar' : 'Reativar' ?></button>
                                </form>
                            <?php endif; ?>
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
trocarTipo(); // roda uma vez ao abrir a página (para quando estiver editando um serviço)
</script>

<?php require 'includes/footer.php'; ?>
