<!--
Data: 16/03/2026
Autor: Otávio Giovanelli Biazzi e Pedro Henrique Miranda
Objetivo:

Exercício 3 - Calculadora Aritmética
Faça um programa que leia dois números e um operador ("+", "-", "*" ou "/").
O programa deve mostrar o resultado da operação.
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
</head>
<body>
    <form method="post" action="calcula.php">
        Primeiro Numero:
        <input type="number" name="num1" required><br>
        
        Segundo Numero:
        <input type="number" name="num2" required><br>
        
        Operação (+, -, /, *)
        <input type="text" name="operacao" pattern="[+\-*/]" maxlength="1" placeholder="Digite apenas +, -, * ou /" required><br>

        <button type="submit">Calcular</button>
    </form>
</body>
</html>