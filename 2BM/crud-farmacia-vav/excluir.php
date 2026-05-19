<?php
require_once 'config/conexao.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch();

if (!$produto) {
    header("Location: index.php");
    exit;
}

require_once 'includes/header.php';
?>

<main>
    <section class="page-header">
        <div>
            <h1 class="page-title">Excluir Produto</h1>
            <p class="page-subtitle">Tela de confirmacao para remocao do estoque.</p>
        </div>

        <a class="btn btn-outline" href="index.php">Voltar</a>
    </section>

    <section class="confirm-card">
        <div>
            <p class="warning-text">Atencao: esta acao removera o produto selecionado.</p>
            <p>Produto: <strong><?= htmlspecialchars($produto['nome']) ?></strong></p>
            <p>Fabricante: <?= htmlspecialchars($produto['fabricante']) ?></p>
            <p>Estoque atual: <?= $produto['estoque'] ?> unidades</p>
        </div>

        <form action="excluir.php?id=<?= $produto['id'] ?>" method="post" class="form-actions">
            <a class="btn btn-outline" href="index.php">Cancelar</a>
            <button class="btn btn-danger" type="submit">Confirmar Exclusao</button>
        </form>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
