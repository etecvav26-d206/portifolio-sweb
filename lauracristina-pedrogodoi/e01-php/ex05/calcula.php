<?php
/**
 * Data: 23/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 5 - Somatório de Fatoriais
 * Cálculo da soma dos fatoriais de 5 números inteiros fornecidos.
 */

function fatorial($num) {
    if ($num <= 1) return 1;
    $f = 1;
    for ($i = 2; $i <= $num; $i++) $f *= $i;
    return $f;
}

if (isset($_POST['n']) && is_array($_POST['n'])) {
    $soma = 0;
    echo "<h3>Resultados:</h3><ul>";
    foreach ($_POST['n'] as $n) {
        $fat = fatorial((int)$n);
        $soma += $fat;
        echo "<li>Fatorial de $n = $fat</li>";
    }
    echo "</ul><h3>Soma total dos fatoriais: $soma</h3>";
} else {
    echo "<h1>Nenhum dado recebido.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
