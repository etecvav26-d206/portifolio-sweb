<!--
Data: 23/03/2026
Autor: Pedro Godoi e Laura Cristina Cruz
Objetivo:

Exercício 3 - Calculadora Aritmética
Faça um programa que leia dois números e um operador ("+", "-", "*" ou "/").
O programa deve mostrar o resultado da operação.
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exercício 3</title>
</head>
<body>
    <h1>Calculadora Aritmética</h1>
    <form action="calcula.php" method="POST">
        <input type="number" step="0.1" name="n1" placeholder="Número 1" required>
        <select name="operador">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
        </select>
        <input type="number" step="0.1" name="n2" placeholder="Número 2" required>
        <button type="submit">Calcular</button>
    </form>
</body>
</html>
