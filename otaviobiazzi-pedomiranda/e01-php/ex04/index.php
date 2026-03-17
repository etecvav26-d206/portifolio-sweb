<!--
Data: 02/03/2026
Autor: Otávio Giovanelli Biazzi
Objetivo:

Exercício 4 - Triângulo Numérico
Leia um número n e imprima n linhas no seguinte formato:
1
1 2
1 2 3
... e assim por diante
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Triângulo Numérico</title>
</head>
<body>
    <h1>Triângulo Numérico</h1>
    <form action="triangulo.php" method="post">
        <p>Digite a quantidade de linhas do seu <strong>triângulo numérico</strong></p>
        <label for="linhas">Digite o número de linhas: </label>
        <input type="number" name="linhas" id="linhas" min="1" required><br><br>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>