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

        <section class="intro">
            <p>Este laboratório mostra como o PHP transforma um mesmo texto usando técnicas diferentes. Algumas funções criam um resumo irreversível, outras apenas codificam dados e a criptografia simétrica permite proteger e recuperar uma informação com uma chave.</p>
            <p>Digite uma frase abaixo para acompanhar os resultados em tempo real.</p>
        </section>
        <p class="nota">Base64 não é criptografia; MD5 e SHA1 são funções antigas; para senhas, o PHP recomenda password_hash(); e o AES usa uma chave para cifrar o texto.</p>
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
                    <tr><td><code>sha1()</code></td><td>Hash SHA1</td><td>Hash de 160 bits (40 hex).</td><td><code><?php echo sha1($texto); ?></code></td></tr>
                    <tr><td><code>hash('sha256')</code></td><td>Hash SHA256</td><td>Hash de 256 bits, mais seguro que MD5/SHA1.</td><td><code><?php echo hash('sha256', $texto); ?></code></td></tr>
                    <tr><td><code>password_hash()</code></td><td>Senha Segura</td><td>Hash bcrypt recomendado para senhas.</td><td><code><?php echo password_hash($texto, PASSWORD_DEFAULT); ?></code></td></tr>
                    <tr><td><code>base64_encode()</code></td><td>Base64</td><td>Codifica em Base64 (nao e criptografia).</td><td><code><?php echo base64_encode($texto); ?></code></td></tr>
                    <tr><td><code>openssl_encrypt()</code></td><td>AES-256-CBC</td><td>Criptografia simetrica reversivel com chave.</td><td><code><?php echo openssl_encrypt($texto, "AES-256-CBC", $chave, 0, $iv); ?></code></td></tr>
                </tbody>
            </table>
        </section>
    </main>
    <footer><p>Produzido por: Otavio Giovanelli Biazzi, Pedro Henrique Miranda, Laura Cristina Cruz e Pedro Henrique Dalle Molle Godoi</p></footer>
</body>
</html>
