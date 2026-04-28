<!--
Data: 26/03/2026
Autor: Pedro Godoi e Laura Cristina Cruz
Objetivo:

Exercício 10 - Ano Bissexto
Leia um ano e informe se ele é bissexto.
Regra: múltiplo de 400 ou (múltiplo de 4 e não múltiplo de 100).
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Exercício 10</title>
</head>
<body>
    <h1>Verificador de Ano Bissexto</h1>
    <form action="calcula.php" method="POST">
        <label for="ano">Informe o ano:</label>
        <input type="number" name="ano" required>
        <button type="submit">Verificar</button>
    </form>
</body>
</html>
