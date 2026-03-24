<!--
Data: 02/03/2026
Autor: Otávio Giovanelli Biazzi e Pedro Henrique Miranda
Objetivo:

Exercício 10 - Ano Bissexto
Leia um ano e informe se ele é bissexto.
Um ano é bissexto se: é múltiplo de 400 ou é múltiplo de 4 e não é múltiplo de 100.
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ano Bissexto</title>
</head>
<body>
    <h1>Ano Bissexto</h1>
    <form action="bissexto.php" method="post">
        <label for="ano">Digite o ano: </label>
        <input type="number" name="ano" id="ano" require>
        <input type="submit" value="Enviar">
    </form>
</body>
</html>
