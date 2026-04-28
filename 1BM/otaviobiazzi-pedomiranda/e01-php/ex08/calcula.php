<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
</head>
<body>
    <h1>Abaixo está o seu resultado</h1>
    <?php
    function soma($n) {
        $resultado = 0;
        for($i = 0; $i <= $n; $i++){
            $resultado += $i;
        }
        return $resultado;
    }

    $numero = $_POST["quantia"];
    $r = soma($numero);
    echo "<p>A soma de 0 até $numero deu $r </p>";

    

    ?>
</body>
</html>