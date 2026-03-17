<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
</head>
<body>
    
    <h1>Resultado da conversão:</h1>

    <?php
    //Receber os dados do form
    $temperatura = $_POST["temperatura"];
    $unidade = $_POST["unidade"];

    if ($unidade == "C") {
        $resultado = $temperatura * 9 / 5 + 32;
        $resultado = round($resultado, 2);
        echo "<p>$temperatura °C equivale a $resultado °F</p>";
    } else {
        $resultado = 5 / 9 * ($temperatura - 32);
        $resultado = round($resultado, 2);
        echo "<p>$temperatura °F equivale a $resultado °C</p>";
    }
    ?>
</body>
</html>
