<!--
Data: 23/03/2026
Autor: Pedro Godoi e Laura Cristina Cruz
Objetivo:

Exercício 4 - Triângulo Numérico
Leia um número n e imprima n linhas no formato:
1
1 2
1 2 3
... até n
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exercício 4</title>
</head>
<body>
    <h1>Triângulo Numérico</h1>
    <form action="calcula.php" method="POST">
        <label for="n">Informe o valor de N:</label>
        <input type="number" name="n" min="1" required>
        <button type="submit">Gerar</button>
    </form>
</body>
</html>
