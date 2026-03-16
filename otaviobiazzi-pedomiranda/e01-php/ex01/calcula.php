<!--
Data: 16/03/2026
Autor: Otávio Giovanelli Biazzi e Pedro Henrique Miranda
Objetivo: Arquivo de processamento do Exercício 1
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>

<?php
if(isset($_POST["numero"])){

    $numero = $_POST["numero"];

    if($numero >= 100 && $numero <= 200){
        echo "Está entre 100 e 200";
    }else{
        echo "Não está entre 100 e 200";
    }
}
?>

<br><br>
<a href="index.php">Voltar</a>

</body>
</html>