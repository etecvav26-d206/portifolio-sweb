<?php
/**
 * Data: 26/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 8 - Soma de 0 até N com Função
 * Função que calcula a soma acumulada de todos os números de 0 até N.
 */

function soma($n) {
    if ($n < 0) return 0;
    $total = 0;
    for ($i = 0; $i <= $n; $i++) {
        $total += $i;
    }
    return $total;
}

if (isset($_POST['n'])) {
    $n = (int)$_POST['n'];
    $resultado = soma($n);
    echo "<h1>A soma de 0 até $n é: $resultado</h1>";
} else {
    echo "<h1>Número não enviado.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
