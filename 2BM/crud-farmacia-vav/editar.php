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
            <h1 class="page-title">Editar Produto</h1>
            <p class="page-subtitle">Formulario visual para atualizar os dados de um produto.</p>
        </div>

        <a class="btn btn-outline" href="index.php">Voltar</a>
    </section>

    <section class="form-card">
        <form action="editar.php?id=<?= $produto['id'] ?>" method="post">
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="nome">Nome da Medicacao</label>
                    <input class="form-control" type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
                </div>

                <div class="form-group full">
                    <label class="form-label" for="fabricante">Fabricante / Laboratorio</label>
                    <input class="form-control" type="text" id="fabricante" name="fabricante" value="<?= htmlspecialchars($produto['fabricante']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="preco">Preco (R$)</label>
                    <input class="form-control" type="number" step="0.01" min="0" id="preco" name="preco" value="<?= $produto['preco'] ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="estoque">Qtd. em Estoque</label>
                    <input class="form-control" type="number" min="0" id="estoque" name="estoque" value="<?= $produto['estoque'] ?>" required>
                </div>
            </div>

            <div class="form-actions">
                <a class="btn btn-outline" href="index.php">Cancelar</a>
                <button class="btn btn-primary" type="submit">Atualizar Produto</button>
            </div>
        </form>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
