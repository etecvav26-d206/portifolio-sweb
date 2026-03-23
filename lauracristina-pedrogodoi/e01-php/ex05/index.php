<!--
Data: 23/03/2026
Autor: Pedro Godoi e Laura Cristina Cruz
Objetivo:

Exercício 5 - Somatório de Fatoriais
Leia 5 números inteiros e mostre a soma dos fatoriais desses números.
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exercício 5</title>
</head>
<body>
    <h1>Somatório de Fatoriais</h1>
    <form action="calcula.php" method="POST">
        <?php for($i=1; $i<=5; $i++): ?>
            <input type="number" name="n[]" placeholder="Número <?php echo $i; ?>" required><br>
        <?php endfor; ?>
        <br>
        <button type="submit">Calcular Somatório</button>
    </form>
</body>
</html>
