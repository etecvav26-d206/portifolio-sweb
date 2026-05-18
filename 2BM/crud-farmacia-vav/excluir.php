<?php require_once 'includes/header.php'; ?>

<main>
    <section class="page-header">
        <div>
            <h1 class="page-title">Excluir Produto</h1>
            <p class="page-subtitle">Tela visual de confirmacao para remocao do estoque.</p>
        </div>

        <a class="btn btn-outline" href="index.php">Voltar</a>
    </section>

    <section class="confirm-card">
        <div>
            <p class="warning-text">Atencao: esta acao removera o produto selecionado.</p>
            <p>Produto: <strong>Dipirona Monoidratada</strong></p>
            <p>Fabricante: Medley</p>
            <p>Estoque atual: 145 unidades</p>
        </div>

        <form action="#" method="post" class="form-actions">
            <a class="btn btn-outline" href="index.php">Cancelar</a>
            <button class="btn btn-danger" type="submit">Confirmar Exclusao</button>
        </form>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
