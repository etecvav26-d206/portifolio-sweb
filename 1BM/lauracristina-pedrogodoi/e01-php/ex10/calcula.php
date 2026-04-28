<?php
/**
 * Data: 26/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 10 - Ano Bissexto
 * Verificação se um ano informado é bissexto seguindo as regras astronômicas.
 */

if (isset($_POST['ano'])) {
    $ano = (int)$_POST['ano'];

    if (($ano % 400 == 0) || (($ano % 4 == 0) && ($ano % 100 != 0))) {
        echo "<h1>O ano $ano é BISSEXTO!</h1>";
    } else {
        echo "<h1>O ano $ano NÃO é bissexto.</h1>";
    }
} else {
    echo "<h1>Ano não informado.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
