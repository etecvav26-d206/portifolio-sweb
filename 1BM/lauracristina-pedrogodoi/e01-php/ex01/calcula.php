<?php
/**
 * Data: 23/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 1 - Intervalo entre 100 e 200
 * Processamento e verificação do número informado.
 */

if (isset($_POST['numero'])) {
    $numero = (float)$_POST['numero'];
    if ($numero >= 100 && $numero <= 200) {
        echo "<h1>O número $numero está no intervalo entre 100 e 200.</h1>";
    } else {
        echo "<h1>O número $numero NÃO está no intervalo entre 100 e 200.</h1>";
    }
} else {
    echo "<h1>Nenhum valor recebido.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
