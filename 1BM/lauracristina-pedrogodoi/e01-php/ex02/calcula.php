<?php
/**
 * Data: 23/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 2 - Conversão de Temperatura
 * Conversão entre as unidades Celsius e Fahrenheit.
 */

if (isset($_POST['valor']) && isset($_POST['unidade'])) {
    $valor = (float)$_POST['valor'];
    $unidade = $_POST['unidade'];
    if ($unidade == 'C') {
        $resultado = ($valor * 9/5) + 32;
        echo "<h1>$valor °C = " . number_format($resultado, 2) . " °F</h1>";
    } else {
        $resultado = 5/9 * ($valor - 32);
        echo "<h1>$valor °F = " . number_format($resultado, 2) . " °C</h1>";
    }
} else {
    echo "<h1>Dados insuficientes.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
