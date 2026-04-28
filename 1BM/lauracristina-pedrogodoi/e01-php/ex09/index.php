<!--
Data: 26/03/2026
Autor: Pedro Godoi e Laura Cristina Cruz
Objetivo:

Exercício 9 - Média Aritmética com Função
Crie uma função media($v) que receba uma lista de números reais e retorne a média aritmética.
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exercício 9</title>
</head>
<body>
    <h1>Média Aritmética</h1>
    <form action="calcula.php" method="POST">
        <label for="numeros">Digite os números separados por vírgula (ex: 10, 20.5, 30):</label><br>
        <input type="text" name="numeros" placeholder="Ex: 10, 20.5, 30" required style="width: 300px;"><br><br>
        <button type="submit">Calcular Média</button>
    </form>
</body>
</html>
