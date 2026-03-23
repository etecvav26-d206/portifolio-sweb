<!--
Data: 23/03/2026
Autor: Pedro Godoi e Laura Cristina Cruz
Objetivo:

Exercício 2 - Conversão de Temperatura
Faça um programa que leia um caractere "F" ou "C", indicando se o valor informado está em Fahrenheit ou Celsius.
Depois, o programa deve converter para a outra unidade.
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exercício 2</title>
</head>
<body>
    <h1>Conversão de Temperatura</h1>
    <form action="calcula.php" method="POST">
        <label for="valor">Valor:</label>
        <input type="number" step="0.01" name="valor" id="valor" required>
        <br><br>
        <label>Unidade Informada:</label>
        <input type="radio" name="unidade" value="C" checked> Celsius
        <input type="radio" name="unidade" value="F"> Fahrenheit
        <br><br>
        <button type="submit">Converter</button>
    </form>
</body>
</html>
