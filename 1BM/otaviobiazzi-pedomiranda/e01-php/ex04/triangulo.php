<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resposta</title>
</head>
<body>
    <?php
    $linhas = $_POST["linhas"];
    echo "<h1>Abaixo está seu triângulo númerico de $linhas linhas</h1>";
    for($i = 0; $i <= $linhas; $i++){
        echo "<p>";
        for($a = 1; $a <= $i; $a++){
            echo $a;
        }
        echo "</p>";
    }
    ?>
    
</body>
</html>