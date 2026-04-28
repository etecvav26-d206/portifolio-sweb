<?php
/**
 * Data: 26/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 6 - Série de Fibonacci
 * Geração e exibição dos N primeiros termos da sequência de Fibonacci.
 */

if (isset($_POST['n'])) {
    $n = (int)$_POST['n'];
    $f1 = 0; $f2 = 1;

    echo "<h3>Série de Fibonacci ($n termos):</h3>";
    for ($i = 0; $i < $n; $i++) {
        echo $f1 . ($i < $n - 1 ? ", " : "");
        $f3 = $f1 + $f2;
        $f1 = $f2;
        $f2 = $f3;
    }
} else {
    echo "<h1>Nenhum dado recebido.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
