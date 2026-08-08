<?php
$texto = isset($_POST['texto']) && !empty($_POST['texto']) ? $_POST['texto'] : "Exemplo de Texto";
$chave = "chave_secreta_123";
$iv    = "1234567890123456";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criptografias PHP</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header><h1>Criptografia no PHP</h1></header>
    <main class="container">
        <h2>Funcoes de Criptografia</h2>

        <section class="form-section">
            <form method="POST" action="">
                <label for="texto">Texto a ser criptografado:</label>
                <div class="input-wrapper">
                    <input type="text" id="texto" name="texto" value="<?php echo htmlspecialchars($texto); ?>" placeholder="Digite qualquer texto...">
                    <button type="submit" class="btn-submit">Processar</button>
                </div>
            </form>
        </section>
        <section class="tabela">
            <table>
                <thead><tr><th>Funcao</th><th>Tipo</th><th>Descricao</th><th>Resultado</th></tr></thead>
                <tbody>
                    <tr><td><code>md5()</code></td><td>Hash MD5</td><td>Hash de 128 bits (32 hex).</td><td><code><?php echo md5($texto); ?></code></td></tr>
                    <tr><td><code>sha1()</code></td><td>Hash SHA1</td><td>Hash de 160 bits (40 hex).</td><td><code>-</code></td></tr>
                    <tr><td><code>hash('sha256')</code></td><td>Hash SHA256</td><td>Hash de 256 bits.</td><td><code>-</code></td></tr>
                    <tr><td><code>password_hash()</code></td><td>Senha Segura</td><td>Hash bcrypt para senhas.</td><td><code>-</code></td></tr>
                    <tr><td><code>base64_encode()</code></td><td>Base64</td><td>Codificacao Base64.</td><td><code>-</code></td></tr>
                    <tr><td><code>openssl_encrypt()</code></td><td>AES-256-CBC</td><td>Criptografia simetrica.</td><td><code>-</code></td></tr>
                </tbody>
            </table>
        </section>
    </main>
    <footer><p>Produzido por: Otavio Giovanelli Biazzi e Pedro Henrique Miranda</p></footer>
</body>
</html>