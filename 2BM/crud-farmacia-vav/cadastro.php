<?php require_once 'includes/header.php'; ?>

<main>
    <section class="page-header">
        <div>
            <h1 class="page-title">Cadastrar Produto</h1>
            <p class="page-subtitle">Formulario visual para inserir um novo item no estoque.</p>
        </div>

        <a class="btn btn-outline" href="index.php">Voltar</a>
    </section>

    <section class="form-card">
        <form action="#" method="post">
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="nome">Nome da Medicacao</label>
                    <input class="form-control" type="text" id="nome" name="nome" placeholder="Ex: Paracetamol 750mg">
                </div>

                <div class="form-group full">
                    <label class="form-label" for="fabricante">Fabricante / Laboratorio</label>
                    <input class="form-control" type="text" id="fabricante" name="fabricante" placeholder="Ex: EMS">
                </div>

                <div class="form-group">
                    <label class="form-label" for="preco">Preco (R$)</label>
                    <input class="form-control" type="number" step="0.01" min="0" id="preco" name="preco" placeholder="0.00">
                </div>

                <div class="form-group">
                    <label class="form-label" for="estoque">Qtd. em Estoque</label>
                    <input class="form-control" type="number" min="0" id="estoque" name="estoque" placeholder="0">
                </div>
            </div>

            <div class="form-actions">
                <a class="btn btn-outline" href="index.php">Cancelar</a>
                <button class="btn btn-primary" type="submit">Salvar Produto</button>
            </div>
        </form>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
