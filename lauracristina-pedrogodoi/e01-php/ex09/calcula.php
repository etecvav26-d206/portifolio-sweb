<?php
/**
 * Data: 26/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 9 - Média Aritmética com Função
 * Função que calcula a média aritmética de um array de números reais.
 */

function media($v) {
    if (empty($v)) return 0;
    return array_sum($v) / count($v);
}

if (isset($_POST['numeros'])) {
    $input = $_POST['numeros'];
    // Tratamento dos dados: explode, remove espaços e converte para float
    $vetor = array_map('floatval', explode(',', str_replace(' ', '', $input)));
    
    $resultado = media($vetor);
    echo "<h1>A média aritmética é: " . number_format($resultado, 2) . "</h1>";
} else {
    echo "<h1>Número não informado.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
