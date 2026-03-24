<!--
Data: 02/03/2026
Autor: Otávio Giovanelli Biazzi e Pedro Henrique Miranda
Objetivo:

Exercício 6 - Série de Fibonacci
Leia um número n e mostre os n primeiros termos da sequência de Fibonacci.
Exemplo: n = 12 → 0 1 1 2 3 5 8 13 21 34 55 89
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Série de Fibonacci</title>
</head>
<body>
    <h1>Série de Fibonacci: </h1>
    <br>
    <form action="fibonacci.php" method="post">
        <label for="numero">Digite o número de elementos: </label>
        <input type="number" name="numero" id="numero" min="1" require>
        <br>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>
