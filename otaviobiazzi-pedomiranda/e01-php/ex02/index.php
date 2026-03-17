<!--
Data: 02/03/2026
Autor: Otávio Giovanelli Biazzi
Objetivo:

Exercício 2 - Conversão de Temperatura
Faça um programa que leia um caractere "F" ou "C", indicando se o valor informado está em Fahrenheit ou Celsius.
Depois, o programa deve converter para a outra unidade.
Fórmula: C = 5/9 × (F − 32)
-->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fahrenheit ou Celsius</title>
</head>
<body>
    <h1>Conversão de Temperatura</h1>
    <br>

    <form action="temperatura.php" method="post">
        <label for="temperatura">Digite a temperatura: </label>
        <input type="number" name="temperatura" id="temperatura" required>
        <br><br>

        <label>Sua temperatura está em:</label><br>
        <input type="radio" name="unidade" id="celsius" value="C" required>
        <label for="celsius">Celsius</label>
        <input type="radio" name="unidade" id="fahrenheit" value="F">
        <label for="fahrenheit">Fahrenheit</label>
        <br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>