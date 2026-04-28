<?php
/**
 * Data: 23/03/2026
 * Autor: Pedro Godoi e Laura Cristina Cruz
 * Objetivo:
 * Exercício 3 - Calculadora Aritmética
 * Realização de cálculos aritméticos entre dois números operados.
 */

if (isset($_POST['n1'], $_POST['n2'], $_POST['operador'])) {
    $n1 = (float)$_POST['n1'];
    $n2 = (float)$_POST['n2'];
    $op = $_POST['operador'];
    $res = 0;

    switch ($op) {
        case '+': $res = $n1 + $n2; break;
        case '-': $res = $n1 - $n2; break;
        case '*': $res = $n1 * $n2; break;
        case '/': 
            if ($n2 != 0) $res = $n1 / $n2; 
            else $res = "Erro: Divisão por zero";
            break;
    }
    echo "<h1>Resultado: $n1 $op $n2 = $res</h1>";
} else {
    echo "<h1>Dados insuficientes.</h1>";
}
echo '<p><a href="index.php">Voltar</a></p>';
?>
