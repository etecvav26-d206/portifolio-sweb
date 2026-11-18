<?php
// =====================================================
//  REGISTRO DE VENDAS E PEDIDOS (4.4)
//  O funcionário escolhe os itens, o sistema calcula o
//  total, salva a venda e desconta o estoque.
// =====================================================
require 'includes/auth.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids         = $_POST['produto_id'] ?? [];
    $quantidades = $_POST['quantidade'] ?? [];
    $observacao  = trim($_POST['observacao'] ?? '');

    // Junta as quantidades por produto (caso o mesmo item apareça duas vezes)
    $itens = [];
    foreach ($ids as $i => $id) {
        $id  = (int) $id;
        $qtd = (int) ($quantidades[$i] ?? 0);
        if ($id > 0 && $qtd > 0) {
            $itens[$id] = ($itens[$id] ?? 0) + $qtd;
        }
    }

    if (!$itens) {
        avisar('Adicione pelo menos um item à venda.', 'erro');
        ir('vendas_nova.php');
    }

    // Transação: ou salva TUDO (venda, itens e estoque) ou não salva nada
    $pdo->beginTransaction();
    try {
        $total = 0;
        $linhas = [];

        foreach ($itens as $id => $qtd) {
            // O preço vem do banco, não do navegador (ninguém consegue alterar o valor)
            // FOR UPDATE trava o produto até o fim da venda, evitando vender o mesmo estoque duas vezes
            $sql = $pdo->prepare('SELECT * FROM produtos WHERE id = ? AND ativo = 1 FOR UPDATE');
            $sql->execute([$id]);
            $p = $sql->fetch();

            if (!$p) {
                throw new Exception('Um dos produtos não existe mais ou foi inativado.');
            }
            if ($p['tipo'] === 'produto' && $p['estoque'] < $qtd) {
                throw new Exception("Estoque insuficiente para \"{$p['nome']}\" (disponível: {$p['estoque']}).");
            }

            $subtotal = round($p['preco'] * $qtd, 2);
            $total += $subtotal;
            $linhas[] = [$p, $qtd, $subtotal];
        }

        // Cabeçalho da venda
        $sql = $pdo->prepare('INSERT INTO vendas (usuario_id, total, observacao) VALUES (?, ?, ?)');
        $sql->execute([usuarioLogado()['id'], $total, $observacao ?: null]);
        $vendaId = $pdo->lastInsertId();

        // Itens + baixa no estoque
        $insereItem = $pdo->prepare('INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario, subtotal) VALUES (?,?,?,?,?)');
        $baixaEstoque = $pdo->prepare('UPDATE produtos SET estoque = estoque - ? WHERE id = ?');

        foreach ($linhas as [$p, $qtd, $subtotal]) {
            $insereItem->execute([$vendaId, $p['id'], $qtd, $p['preco'], $subtotal]);
            if ($p['tipo'] === 'produto') {
                $baixaEstoque->execute([$qtd, $p['id']]);
            }
        }

        $pdo->commit();
        avisar("Venda nº $vendaId registrada! Total: " . dinheiro($total));
        ir('vendas_historico.php');

    } catch (Exception $erro) {
        $pdo->rollBack();
        avisar($erro->getMessage(), 'erro');
        ir('vendas_nova.php');
    }
}

// Produtos que podem ser vendidos (ativos, e com estoque se for produto)
$produtos = $pdo->query("SELECT id, nome, categoria, tipo, preco, estoque FROM produtos
                         WHERE ativo = 1 AND (tipo = 'servico' OR estoque > 0)
                         ORDER BY categoria, nome")->fetchAll();

$titulo = 'Nova venda';
$pagina = 'nova';
require 'includes/header.php';
?>

<h1>Nova venda</h1>
<p class="subtitulo">Adicione os produtos ou serviços e confirme a venda.</p>

<div class="grade grade-2" style="grid-template-columns: 3fr 2fr">

    <div class="cartao">
        <h2>Adicionar item</h2>
        <div class="adicionar-item">
            <div>
                <label>Produto ou serviço</label>
                <select id="produto">
                    <option value="">Selecione...</option>
                    <?php
                    $categoriaAtual = null;
                    foreach ($produtos as $p):
                        if ($p['categoria'] !== $categoriaAtual):
                            if ($categoriaAtual !== null) echo '</optgroup>';
                            $categoriaAtual = $p['categoria'];
                            echo '<optgroup label="' . e($categoriaAtual) . '">';
                        endif;
                    ?>
                        <option value="<?= $p['id'] ?>">
                            <?= e($p['nome']) ?> — <?= dinheiro($p['preco']) ?>
                            <?= $p['tipo'] === 'produto' ? "({$p['estoque']} em estoque)" : '' ?>
                        </option>
                    <?php endforeach; ?>
                    <?php if ($categoriaAtual !== null) echo '</optgroup>'; ?>
                </select>
            </div>
            <div class="qtd">
                <label>Qtd.</label>
                <input type="number" id="quantidade" value="1" min="1">
            </div>
            <button type="button" class="botao" onclick="adicionarItem()">Adicionar</button>
        </div>
    </div>

    <form method="post" class="cartao" id="form-venda">
        <h2>Itens da venda</h2>
        <table>
            <thead>
                <tr><th>Item</th><th class="numero">Qtd.</th><th class="numero">Subtotal</th><th></th></tr>
            </thead>
            <tbody id="itens">
                <tr id="sem-itens"><td colspan="4" class="vazio">Nenhum item adicionado</td></tr>
            </tbody>
        </table>

        <div class="total-venda">
            <small>Total</small>
            <span id="total">R$ 0,00</span>
        </div>

        <label>Observação (opcional)</label>
        <textarea name="observacao" rows="2" placeholder="Ex.: pedido para retirar amanhã, nome do cliente..."></textarea>

        <button class="botao botao-destaque" style="width:100%">Confirmar venda</button>
    </form>
</div>

<script>
// Dados dos produtos vindos do PHP
const produtos = <?= json_encode(array_column($produtos, null, 'id'), JSON_HEX_TAG | JSON_HEX_AMP) ?>;
let carrinho = {}; // { idProduto: quantidade }

// Evita que um nome com símbolos estranhos quebre a página
function seguro(texto) {
    const div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
}

function formatar(valor) {
    return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
}

function adicionarItem() {
    const id = document.getElementById('produto').value;
    const qtd = parseInt(document.getElementById('quantidade').value);

    if (!id) { alert('Escolha um produto.'); return; }
    if (!qtd || qtd < 1) { alert('Quantidade inválida.'); return; }

    const p = produtos[id];
    const novaQtd = (carrinho[id] || 0) + qtd;

    if (p.tipo === 'produto' && novaQtd > Number(p.estoque)) {
        alert('Só temos ' + p.estoque + ' unidade(s) de "' + p.nome + '" em estoque.');
        return;
    }

    carrinho[id] = novaQtd;
    document.getElementById('quantidade').value = 1;
    desenhar();
}

function remover(id) {
    delete carrinho[id];
    desenhar();
}

// Atualiza a tabela de itens e o total na tela
function desenhar() {
    const corpo = document.getElementById('itens');
    corpo.innerHTML = '';
    let total = 0;

    for (const id in carrinho) {
        const p = produtos[id];
        const qtd = carrinho[id];
        const subtotal = Number(p.preco) * qtd;
        total += subtotal;

        const linha = document.createElement('tr');
        linha.innerHTML = `
            <td>${seguro(p.nome)}
                <input type="hidden" name="produto_id[]" value="${id}">
                <input type="hidden" name="quantidade[]" value="${qtd}">
            </td>
            <td class="numero">${qtd}</td>
            <td class="numero">${formatar(subtotal)}</td>
            <td class="numero"><button type="button" class="remover" title="Remover" onclick="remover(${id})">×</button></td>`;
        corpo.appendChild(linha);
    }

    if (Object.keys(carrinho).length === 0) {
        corpo.innerHTML = '<tr><td colspan="4" class="vazio">Nenhum item adicionado</td></tr>';
    }
    document.getElementById('total').textContent = formatar(total);
}
</script>

<?php require 'includes/footer.php'; ?>
