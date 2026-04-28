<!--
Data: 16/03/2026
Autor: Otávio Giovanelli Biazzi e Pedro Henrique Miranda
Objetivo: Arquivo de cálculo do Exercício 5
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>
    <?php
    function fatorial($n){
        $resultado = 1;
        $i = 1;
        while($i <= $n){
            $resultado = $resultado * $i;
            $i++;
        }
        return $resultado;
    }

    if(isset($_POST["n1"])){
        $numero1 = $_POST["n1"];
        $numero2 = $_POST["n2"];
        $numero3 = $_POST["n3"];
        $numero4 = $_POST["n4"];
        $numero5 = $_POST["n5"];

        $resultado =
            fatorial($numero1) +
            fatorial($numero2) +
            fatorial($numero3) +
            fatorial($numero4) +
            fatorial($numero5);

        echo "<h2>Resultado: $resultado</h2>";
    }
    ?>
    <br><a href="index.php">Voltar</a>
</body>
</html>