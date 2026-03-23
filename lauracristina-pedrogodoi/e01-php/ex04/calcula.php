<?php
/**
 * Data: 23/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 4 - Triângulo Numérico
 * Geração de triângulo numérico crescente baseado no valor de N.
 */

if (isset($_POST['n'])) {
    $n = (int)$_POST['n'];
    echo "<h3>Triângulo Numérico (n=$n):</h3><code>";
    for ($i = 1; $i <= $n; $i++) {
        for ($j = 1; $j <= $i; $j++) {
            echo $j . " ";
        }
        echo "<br>";
    }
    echo "</code>";
} else {
    echo "<h1>Nenhum dado recebido.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
