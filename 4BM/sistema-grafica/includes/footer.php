    </main><!-- fecha o <main> aberto no header.php -->
<?php // Rodapé aparece só nas páginas públicas (catálogo e login) ?>
<?php if (!empty($publico)): ?>
    <footer class="rodape-publico">
        <?= e(NOME_GRAFICA) ?> - ETEC Vasco Antônio Venchiarutti
    </footer>
<?php endif; ?>
</body>
</html>
