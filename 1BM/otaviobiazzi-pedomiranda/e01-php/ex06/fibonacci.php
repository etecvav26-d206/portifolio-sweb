<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
</head>
<body>
    <h1>A sequência é a seguinte: </h1>
    <br>
    <?php
    $tamanho = $_POST["numero"];
    $array = [0, 1];
    
    $i = 2;
    echo "<p>Os primeiros $tamanho números são: </p>";
    echo "<p> $array[0] $array[1]";
    while($i != $tamanho) {
        echo " ";
        $resultado = $array[0] + $array[1];
        echo $resultado;
        $array[0] = $array[1];
        $array[1] = $resultado;
        $i++;
    }
    echo "</p>";;

    ?>
</body>
</html>