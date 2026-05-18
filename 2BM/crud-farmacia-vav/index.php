<?php require_once 'includes/header.php'; ?>

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
        <article class="product-card">
            <div class="product-header">
                <div>
                    <h2 class="product-title">Dipirona Monoidratada</h2>
                    <p class="product-manufacturer">Medley</p>
                </div>
                <span class="product-id">#001</span>
            </div>

            <div class="product-info">
                <div>
                    <p class="info-label">Preco Unitario</p>
                    <p class="price-tag">R$ 12,50</p>
                </div>
                <div>
                    <p class="info-label">Estoque</p>
                    <span class="stock-badge">145 unid.</span>
                </div>
            </div>

            <div class="product-actions">
                <a class="btn btn-outline" href="editar.php">Editar</a>
                <a class="btn btn-danger" href="excluir.php">Excluir</a>
            </div>
        </article>

        <article class="product-card">
            <div class="product-header">
                <div>
                    <h2 class="product-title">Amoxicilina 500mg</h2>
                    <p class="product-manufacturer">Neo Quimica</p>
                </div>
                <span class="product-id">#002</span>
            </div>

            <div class="product-info">
                <div>
                    <p class="info-label">Preco Unitario</p>
                    <p class="price-tag">R$ 25,90</p>
                </div>
                <div>
                    <p class="info-label">Estoque</p>
                    <span class="stock-badge">5 unid.</span>
                </div>
            </div>

            <div class="product-actions">
                <a class="btn btn-outline" href="editar.php">Editar</a>
                <a class="btn btn-danger" href="excluir.php">Excluir</a>
            </div>
        </article>
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
                    <td><span class="stock-badge">5 unid.</span></td>
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
