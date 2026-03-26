<?php
/**
 * Data: 26/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 7 - Separar Positivos e Negativos
 * Separação de 8 números inteiros em vetores de positivos e negativos.
 */

if (isset($_POST['n']) && is_array($_POST['n'])) {
    $positivos = [];
    $negativos = [];

    foreach ($_POST['n'] as $num) {
        $n = (int)$num;
        if ($n >= 0) $positivos[] = $n;
        else $negativos[] = $n;
    }

    echo "<h3>Números Positivos (Vetor 1):</h3>";
    echo !empty($positivos) ? implode(", ", $positivos) : "Nenhum";
    
    echo "<h3>Números Negativos (Vetor 2):</h3>";
    echo !empty($negativos) ? implode(", ", $negativos) : "Nenhum";
} else {
    echo "<h1>Nenhum dado recebido.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
