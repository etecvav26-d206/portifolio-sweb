<?php
require_once 'config/conexao.php';
require_once 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id ASC");
$produtos = $stmt->fetchAll();
?>

<main>
    <section class="page-header">
        <div>
            <h1 class="page-title">Gerenciamento de Estoque</h1>
            <p class="page-subtitle">Lista visual de produtos cadastrados na farmacia.</p>
        </div>

        <a class="btn btn-primary" href="cadastro.php">
            <span aria-hidden="true">+</span>
            Novo Produto
        </a>
    </section>

    <section class="data-grid" aria-label="Produtos em cards">
        <?php foreach ($produtos as $produto): ?>
        <article class="product-card">
            <div class="product-header">
                <div>
                    <h2 class="product-title"><?= htmlspecialchars($produto['nome']) ?></h2>
                    <p class="product-manufacturer"><?= htmlspecialchars($produto['fabricante']) ?></p>
                </div>
                <span class="product-id">#<?= str_pad($produto['id'], 3, '0', STR_PAD_LEFT) ?></span>
            </div>

            <div class="product-info">
                <div>
                    <p class="info-label">Preco Unitario</p>
                    <p class="price-tag">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                </div>
                <div>
                    <p class="info-label">Estoque</p>
                    <span class="stock-badge"><?= $produto['estoque'] ?> unid.</span>
                </div>
            </div>

            <div class="product-actions">
                <a class="btn btn-outline" href="editar.php?id=<?= $produto['id'] ?>">Editar</a>
                <a class="btn btn-danger" href="excluir.php?id=<?= $produto['id'] ?>">Excluir</a>
            </div>
        </article>
        <?php endforeach; ?>
    </section>

    <section class="desktop-table-wrapper" aria-label="Produtos em tabela">
        <table class="desktop-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Produto</th>
                    <th>Fabricante</th>
                    <th>Preco</th>
                    <th>Estoque</th>
                    <th class="text-right">Acoes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="product-id">#001</span></td>
                    <td>Dipirona Monoidratada</td>
                    <td>Medley</td>
                    <td><span class="price-tag">R$ 12,50</span></td>
                    <td><span class="stock-badge">145 unid.</span></td>
                    <td class="text-right">
                        <a class="btn btn-outline btn-sm" href="editar.php">Editar</a>
                        <a class="btn btn-danger btn-sm" href="excluir.php">Excluir</a>
                    </td>
                </tr>
                <tr>
                    <td><span class="product-id">#002</span></td>
                    <td>Amoxicilina 500mg</td>
                    <td>Neo Quimica</td>
                    <td><span class="price-tag">R$ 25,90</span></td>
                    <td><span class="stock-badge low">5 unid.</span></td>
                    <td class="text-right">
                        <a class="btn btn-outline btn-sm" href="editar.php">Editar</a>
                        <a class="btn btn-danger btn-sm" href="excluir.php">Excluir</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
