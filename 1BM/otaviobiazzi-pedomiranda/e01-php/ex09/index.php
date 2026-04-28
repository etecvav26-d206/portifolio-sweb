<!--
Data: 16/03/2026
Autor: Otávio Giovanelli Biazzi e Pedro Henrique Miranda
Objetivo:

Exercício 9 - Média Aritmética com Função
function media($v)
Que receba uma lista de números reais e retorne a média aritmética.
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Média Aritmética com Função</title>
</head>
<body>
    <h1>Cálculo da Média Aritmética</h1>

    <form method="post" action="calcula.php">
        Primeiro Número: 
        <input type="number" name="n1" required><br>
        Segundo Número: 
        <input type="number" name="n2" required><br>
        Terceiro Número: 
        <input type="number" name="n3" required><br>
        Quarto Número: 
        <input type="number" name="n4" required><br>
        Quinto Numéro: 
        <input type="number" name="n5" required><br>

        <button type="submit">Calcular Média</button>
    </form>
</body>
</html>