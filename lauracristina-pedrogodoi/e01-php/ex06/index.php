<!--
Data: 26/03/2026
Autor: Pedro Godoi e Laura Cristina Cruz
Objetivo:

Exercício 6 - Série de Fibonacci
Leia um número n e mostre os n primeiros termos da sequência de Fibonacci.
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exercício 6</title>
</head>
<body>
    <h1>Série de Fibonacci</h1>
    <form action="calcula.php" method="POST">
        <label for="n">Quantos termos deseja ver?</label>
        <input type="number" name="n" min="1" required>
        <button type="submit">Mostrar</button>
    </form>
</body>
</html>
