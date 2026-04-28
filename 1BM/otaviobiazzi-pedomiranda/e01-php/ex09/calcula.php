<!--
Data: 16/03/2026
Autor: Otávio Giovanelli Biazzi e Pedro Henrique Miranda
Objetivo: Arquivo de cálculo do Exercício 9
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>
    <?php
    function media($v){
        if(count($v) == 0){
            return 0;
        }

        $soma = 0;

        foreach($v as $num){
            $soma += $num;
        }

        return $soma / count($v);
    }

    if(isset($_POST["n1"])){
        $numeros = [
            $_POST["n1"],
            $_POST["n2"],
            $_POST["n3"],
            $_POST["n4"],
            $_POST["n5"],
        ];

        $resultado = media($numeros);
        echo "<p>A média dos números é: $resultado</p>";
    }
    ?>
    <br><a href="index.php">Voltar</a>
</body>
</html>