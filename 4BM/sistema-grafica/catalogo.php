<?php
// =====================================================
//  CATÁLOGO PÚBLICO (4.3) - não precisa de login
//  Mostra só nome, categoria, preço e se está disponível.
//  NÃO mostra quantidade exata, gastos nem lucro.
// =====================================================
require 'includes/funcoes.php';

$categoriaEscolhida = $_GET['categoria'] ?? '';
$busca = trim($_GET['busca'] ?? '');

// Lista de categorias para os filtros
$categorias = $pdo->query('SELECT DISTINCT categoria FROM produtos WHERE ativo = 1 ORDER BY categoria')
                  ->fetchAll(PDO::FETCH_COLUMN);

// Busca dos produtos (só ativos)
$sql = 'SELECT nome, categoria, tipo, preco, estoque, imagem FROM produtos WHERE ativo = 1';
$params = [];
if ($categoriaEscolhida !== '') {
    $sql .= ' AND categoria = ?';
    $params[] = $categoriaEscolhida;
}
if ($busca !== '') {
    $sql .= ' AND nome LIKE ?';
    $params[] = "%$busca%";
}
$sql .= ' ORDER BY categoria, nome';
$consulta = $pdo->prepare($sql);
$consulta->execute($params);
$produtos = $consulta->fetchAll();

$titulo = 'Catálogo';
$publico = true;
require 'includes/header.php';
?>

<section class="hero <?= imagem('fachada.jpg') ? 'hero-foto' : '' ?>"
    <?php if (imagem('fachada.jpg')): ?>style="background-image: url('assets/img/fachada.jpg')"<?php endif; ?>>
    <h1>Produtos e serviços</h1>
    <p class="subtitulo">Impressões, material escolar e personalizados. Consulte o que está disponível agora.</p>

    <form method="get" style="max-width:420px;margin:0 auto 18px;display:flex;gap:8px">
        <?php if ($categoriaEscolhida): ?>
            <input type="hidden" name="categoria" value="<?= e($categoriaEscolhida) ?>">
        <?php endif; ?>
        <input type="text" name="busca" placeholder="Buscar produto..." value="<?= e($busca) ?>" style="margin:0">
        <button class="botao">Buscar</button>
    </form>
</section>

<div class="categorias">
    <a href="catalogo.php" class="<?= $categoriaEscolhida === '' ? 'ativo' : '' ?>">Todos</a>
    <?php foreach ($categorias as $cat): ?>
        <a href="?categoria=<?= urlencode($cat) ?>" class="<?= $cat === $categoriaEscolhida ? 'ativo' : '' ?>">
            <?= e($cat) ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (!$produtos): ?>
    <p class="vazio">Nenhum produto encontrado.</p>
<?php endif; ?>

<div class="vitrine">
    <?php foreach ($produtos as $p):
        // Serviço está sempre disponível; produto depende do estoque
        $disponivel = $p['tipo'] === 'servico' || $p['estoque'] > 0;
    ?>
        <div class="produto-card <?= $disponivel ? '' : 'indisponivel' ?>">
            <?php if ($p['imagem']): ?>
                <img class="produto-foto" src="assets/img/produtos/<?= e($p['imagem']) ?>" alt="<?= e($p['nome']) ?>">
            <?php else: ?>
                <div class="produto-foto produto-sem-foto"><?= e(mb_substr($p['nome'], 0, 1)) ?></div>
            <?php endif; ?>
            <span class="categoria"><?= e($p['categoria']) ?></span>
            <h3><?= e($p['nome']) ?></h3>
            <span class="preco"><?= dinheiro($p['preco']) ?></span>
            <?php if ($p['tipo'] === 'servico'): ?>
                <span class="selo selo-servico">Serviço disponível</span>
            <?php elseif ($disponivel): ?>
                <span class="selo selo-ok">Disponível</span>
            <?php else: ?>
                <span class="selo selo-esgotado">Indisponível</span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php require 'includes/footer.php'; ?>
