<!--
Data: 16/03/2026
Autor: Otávio Giovanelli Biazzi e Pedro Henrique Miranda
Objetivo: Arquivo de cálculo do Exercício 7
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>
    <?php
    if(isset($_POST["n1"])){

        $positivos = [];
        $negativos = [];

        $numeros = [
            $_POST["n1"],
            $_POST["n2"],
            $_POST["n3"],
            $_POST["n4"],
            $_POST["n5"],
            $_POST["n6"],
            $_POST["n7"],
            $_POST["n8"]
        ];

        foreach($numeros as $num){
            if($num >= 0){
                $positivos[] = $num;
            }else{
                $negativos[] = $num;
            }
        }

        echo "<h2>Números Positivos:</h2>";
        echo implode(", ", $positivos);

        echo "<h2>Números Negativos:</h2>";
        echo implode(", ", $negativos);
    }
    ?>
    <br><br><a href="index.php">Voltar</a>
</body>
</html>