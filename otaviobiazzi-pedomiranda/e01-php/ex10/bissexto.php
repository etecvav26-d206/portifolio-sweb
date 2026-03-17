<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
</head>
<body>
    <h1>Abaixo está o resultado se seu ano é bissexto</h1>
    <?php
    function bissexto($n) {
        if($n % 400 == 0){
            return true;
        }
        elseif($n % 4 == 0 && $n % 100 != 0){
            return true;
        } 
        else{
            return false;
        }
    }
    
    $ano = $_POST["ano"];
    $resposta = bissexto($ano);

    switch($resposta){
        case true : echo "<p>O ano $ano é bissexto!!!!!!</p>"; break;
        case false: echo "<p>Infelizmente o ano $ano não é bissexto <strong>:(</strong></p>"; break;
    }
    
    ?>
</body>
</html>