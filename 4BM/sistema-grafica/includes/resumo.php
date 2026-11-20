<?php
// =====================================================
//  Cálculos usados pelo PAINEL e pelos RELATÓRIOS.
//  Ficam num lugar só para os números sempre baterem.
// =====================================================

function resumoPeriodo(PDO $pdo, $inicio, $fim) {
    $r = [];

    // Vendas (ganhos)
    $sql = $pdo->prepare('SELECT COUNT(*) AS qtd, COALESCE(SUM(total),0) AS total
                          FROM vendas WHERE DATE(data_venda) BETWEEN ? AND ?');
    $sql->execute([$inicio, $fim]);
    $v = $sql->fetch();
    $r['qtd_vendas']   = (int) $v['qtd'];
    $r['total_vendas'] = (float) $v['total'];
    $r['ticket_medio'] = $r['qtd_vendas'] ? $r['total_vendas'] / $r['qtd_vendas'] : 0;

    // Gastos
    $sql = $pdo->prepare('SELECT COALESCE(SUM(valor),0) FROM gastos WHERE data_gasto BETWEEN ? AND ?');
    $sql->execute([$inicio, $fim]);
    $r['total_gastos'] = (float) $sql->fetchColumn();

    // Lucro estimado = o que entrou - o que saiu
    $r['lucro'] = $r['total_vendas'] - $r['total_gastos'];

    // Produtos mais vendidos
    $sql = $pdo->prepare('SELECT p.nome, SUM(i.quantidade) AS qtd, SUM(i.subtotal) AS valor
                          FROM itens_venda i
                          JOIN vendas v   ON v.id = i.venda_id
                          JOIN produtos p ON p.id = i.produto_id
                          WHERE DATE(v.data_venda) BETWEEN ? AND ?
                          GROUP BY p.id, p.nome
                          ORDER BY valor DESC
                          LIMIT 5');
    $sql->execute([$inicio, $fim]);
    $r['mais_vendidos'] = $sql->fetchAll();

    // Gastos por categoria
    $sql = $pdo->prepare('SELECT categoria, SUM(valor) AS total FROM gastos
                          WHERE data_gasto BETWEEN ? AND ?
                          GROUP BY categoria ORDER BY total DESC');
    $sql->execute([$inicio, $fim]);
    $r['gastos_categoria'] = $sql->fetchAll();

    // Vendas por funcionário
    $sql = $pdo->prepare('SELECT u.nome, COUNT(*) AS qtd, SUM(v.total) AS total
                          FROM vendas v JOIN usuarios u ON u.id = v.usuario_id
                          WHERE DATE(v.data_venda) BETWEEN ? AND ?
                          GROUP BY u.id, u.nome ORDER BY total DESC');
    $sql->execute([$inicio, $fim]);
    $r['por_funcionario'] = $sql->fetchAll();

    return $r;
}

function produtosEstoqueBaixo(PDO $pdo) {
    return $pdo->query("SELECT nome, estoque, estoque_minimo FROM produtos
                        WHERE ativo = 1 AND tipo = 'produto' AND estoque <= estoque_minimo
                        ORDER BY estoque")->fetchAll();
}
