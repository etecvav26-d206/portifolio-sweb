<!--
Data: 26/03/2026
Autor: Pedro Godoi e Laura Cristina Cruz
Objetivo:

Exercício 7 - Separar Positivos e Negativos
Leia 8 números inteiros e separe em dois vetores:
- um vetor com números positivos
- um vetor com números negativos
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exercício 7</title>
</head>
<body>
    <h1>Separar Positivos e Negativos</h1>
    <form action="calcula.php" method="POST">
        <?php for($i=1; $i<=8; $i++): ?>
            <input type="number" name="n[]" placeholder="Número <?php echo $i; ?>" required><br>
        <?php endfor; ?>
        <br>
        <button type="submit">Separar</button>
    </form>
</body>
</html>
