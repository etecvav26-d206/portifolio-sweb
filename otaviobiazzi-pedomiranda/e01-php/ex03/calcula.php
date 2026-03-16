<!--
Data: 16/03/2026
Autor: Otávio Giovanelli Biazzi e Pedro Henrique Miranda
Objetivo: Arquivo de cálculo do Exercício 3
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>
    <?php 
    if (isset($_POST["num1"], $_POST["num2"], $_POST["operacao"])){

        $num1 = $_POST["num1"];
        $num2 = $_POST["num2"];
        $operacao = $_POST["operacao"];

        if($operacao == "+"){
            $resultado = $num1 + $num2;
        }
        elseif($operacao == "-"){
            $resultado = $num1 - $num2;
        }
        elseif($operacao == "/"){
            $resultado = $num1 / $num2;
        }
        elseif($operacao == "*"){
            $resultado = $num1 * $num2;
        }

        if (isset($resultado)){
            echo $resultado;
        }
    }
    ?>
    <br><br><a href="index.php">Voltar</a>
</body>
</html>