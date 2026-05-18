<?php
require_once 'config/conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome       = $_POST['nome'];
    $fabricante = $_POST['fabricante'];
    $preco      = $_POST['preco'];
    $estoque    = $_POST['estoque'];

    $sql = "INSERT INTO produtos (nome, fabricante, preco, estoque) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $fabricante, $preco, $estoque]);

    header("Location: index.php?msg=cadastrado");
    exit;
}

require_once 'includes/header.php';
?>

<main>
    <section class="page-header">
        <div>
            <h1 class="page-title">Cadastrar Produto</h1>
            <p class="page-subtitle">Formulario para inserir um novo item no estoque.</p>
        </div>

        <a class="btn btn-outline" href="index.php">Voltar</a>
    </section>

    <section class="form-card">
        <form action="cadastro.php" method="post">
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label" for="nome">Nome da Medicacao</label>
                    <input class="form-control" type="text" id="nome" name="nome" placeholder="Ex: Paracetamol 750mg" required>
                </div>

                <div class="form-group full">
                    <label class="form-label" for="fabricante">Fabricante / Laboratorio</label>
                    <input class="form-control" type="text" id="fabricante" name="fabricante" placeholder="Ex: EMS" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="preco">Preco (R$)</label>
                    <input class="form-control" type="number" step="0.01" min="0" id="preco" name="preco" placeholder="0.00" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="estoque">Qtd. em Estoque</label>
                    <input class="form-control" type="number" min="0" id="estoque" name="estoque" placeholder="0" required>
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
