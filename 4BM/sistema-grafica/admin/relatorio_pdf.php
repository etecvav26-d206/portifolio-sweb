<?php
// =====================================================
//  GERA O RELATÓRIO EM PDF usando a biblioteca FPDF
//  (pasta lib/fpdf - gratuita, não precisa instalar nada)
// =====================================================
require '../includes/auth.php';
require '../includes/resumo.php';
require '../lib/fpdf/fpdf.php';
exigirAdmin();

$inicio = $_GET['inicio'] ?? date('Y-m-01');
$fim    = $_GET['fim']    ?? date('Y-m-d');
if (!strtotime($inicio) || !strtotime($fim)) {
    die('Período inválido.');
}
if ($inicio > $fim) {
    [$inicio, $fim] = [$fim, $inicio];
}

$r = resumoPeriodo($pdo, $inicio, $fim);

// Lista de vendas e de gastos do período
$sql = $pdo->prepare('SELECT v.id, v.data_venda, v.total, u.nome AS funcionario
                      FROM vendas v JOIN usuarios u ON u.id = v.usuario_id
                      WHERE DATE(v.data_venda) BETWEEN ? AND ? ORDER BY v.data_venda');
$sql->execute([$inicio, $fim]);
$vendas = $sql->fetchAll();

$sql = $pdo->prepare('SELECT * FROM gastos WHERE data_gasto BETWEEN ? AND ? ORDER BY data_gasto');
$sql->execute([$inicio, $fim]);
$gastos = $sql->fetchAll();

// O FPDF não entende UTF-8, então convertemos os acentos
function t($texto) {
    return iconv('UTF-8', 'windows-1252//TRANSLIT', (string) $texto);
}

// Classe com cabeçalho e rodapé que se repetem em todas as páginas
class RelatorioPDF extends FPDF {
    public $periodo = '';

    function Header() {
        // Faixinha CMYK no topo
        $cores = [[200,16,46], [20,20,20], [200,16,46], [20,20,20]];  // vermelho e preto da ETEC
        foreach ($cores as $i => $c) {
            $this->SetFillColor($c[0], $c[1], $c[2]);
            $this->Rect(10 + $i * 47.5, 8, 47.5, 2, 'F');
        }
        // Logo da ETEC no canto direito (se o arquivo existir)
        $logo = __DIR__ . '/../assets/img/logo-etec-completo.png';
        if (file_exists($logo)) {
            $this->Image($logo, 176, 12, 24);
        }
        $this->SetY(14);
        $this->SetFont('Helvetica', 'B', 15);
        $this->SetTextColor(20, 20, 20);
        $this->Cell(0, 8, t(NOME_GRAFICA . ' - Relatório Financeiro'), 0, 1);
        $this->SetFont('Helvetica', '', 10);
        $this->SetTextColor(100);
        $this->Cell(0, 5, t($this->periodo), 0, 1);
        $this->Ln(4);
    }

    function Footer() {
        $this->SetY(-14);
        $this->SetFont('Helvetica', '', 8);
        $this->SetTextColor(130);
        $this->Cell(0, 5, t('Gerado em ' . date('d/m/Y H:i') . ' por ' . usuarioLogado()['nome']), 0, 0, 'L');
        $this->Cell(0, 5, t('Página ' . $this->PageNo() . ' de {nb}'), 0, 0, 'R');
    }

    function Titulo($texto) {
        $this->Ln(3);
        $this->SetFont('Helvetica', 'B', 12);
        $this->SetTextColor(20, 20, 20);
        $this->Cell(0, 8, t($texto), 0, 1);
    }

    // Tabela simples: $larguras soma 190mm; $alinhar = 'L' ou 'R' por coluna
    function Tabela($cabecalho, $linhas, $larguras, $alinhar, $rodape = null) {
        $this->SetFont('Helvetica', 'B', 9);
        $this->SetFillColor(20, 20, 20);
        $this->SetTextColor(255);
        foreach ($cabecalho as $i => $h) {
            $this->Cell($larguras[$i], 7, t($h), 0, 0, $alinhar[$i], true);
        }
        $this->Ln();

        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(40);
        $zebra = false;
        foreach ($linhas as $linha) {
            $this->SetFillColor(244, 244, 244);
            foreach ($linha as $i => $valor) {
                // Corta textos muito compridos para não invadir a coluna do lado
                $texto = t($valor);
                while ($this->GetStringWidth($texto) > $larguras[$i] - 2 && strlen($texto) > 3) {
                    $texto = substr($texto, 0, -4) . '...';
                }
                $this->Cell($larguras[$i], 6.5, $texto, 0, 0, $alinhar[$i], $zebra);
            }
            $this->Ln();
            $zebra = !$zebra;
        }

        if (!$linhas) {
            $this->SetTextColor(130);
            $this->Cell(array_sum($larguras), 7, t('Nenhum registro no período.'), 0, 1, 'C');
        }

        if ($rodape) {
            $this->SetFont('Helvetica', 'B', 9);
            $this->SetDrawColor(200, 16, 46);
            foreach ($rodape as $i => $valor) {
                $this->Cell($larguras[$i], 7, t($valor), 'T', 0, $alinhar[$i]);
            }
            $this->Ln();
        }
    }
}

$pdf = new RelatorioPDF();
$pdf->periodo = 'Período: ' . dataBr($inicio) . ' a ' . dataBr($fim);
$pdf->AliasNbPages();
$pdf->SetTitle(t('Relatório ' . dataBr($inicio) . ' a ' . dataBr($fim)));
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 20);
$pdf->AddPage();

// ---------- Resumo em quatro caixas ----------
$caixas = [
    ['Vendas (ganhos)', dinheiro($r['total_vendas'])],
    ['Gastos', dinheiro($r['total_gastos'])],
    ['Lucro estimado', dinheiro($r['lucro'])],
    ['Nº de vendas', (string) $r['qtd_vendas']],
];
$y = $pdf->GetY();
foreach ($caixas as $i => [$rotulo, $valor]) {
    $x = 10 + $i * 48;
    $pdf->SetFillColor(244, 244, 244);
    $pdf->Rect($x, $y, 46, 20, 'F');
    $pdf->SetXY($x + 3, $y + 3);
    $pdf->SetFont('Helvetica', '', 8);
    $pdf->SetTextColor(100);
    $pdf->Cell(40, 4, t($rotulo));
    $pdf->SetXY($x + 3, $y + 9);
    $pdf->SetFont('Helvetica', 'B', 13);
    if ($i === 2) {
        $r['lucro'] >= 0 ? $pdf->SetTextColor(31, 157, 85) : $pdf->SetTextColor(214, 69, 69);
    } else {
        $pdf->SetTextColor(20, 20, 20);
    }
    $pdf->Cell(40, 7, t($valor));
}
$pdf->SetY($y + 24);
$pdf->SetFont('Helvetica', '', 9);
$pdf->SetTextColor(100);
$pdf->MultiCell(0, 5, t('Lucro estimado = total de vendas - total de gastos registrados no sistema. Valor médio por venda: ' . dinheiro($r['ticket_medio']) . '.'));

// ---------- Tabelas ----------
$pdf->Titulo('Produtos e serviços mais vendidos');
$linhas = [];
foreach ($r['mais_vendidos'] as $p) {
    $linhas[] = [$p['nome'], $p['qtd'], dinheiro($p['valor'])];
}
$pdf->Tabela(['Produto / serviço', 'Quantidade', 'Valor'], $linhas, [120, 30, 40], ['L', 'R', 'R']);

$pdf->Titulo('Gastos do período');
$linhas = [];
foreach ($gastos as $g) {
    $linhas[] = [dataBr($g['data_gasto']), $g['descricao'], $g['categoria'], dinheiro($g['valor'])];
}
$pdf->Tabela(['Data', 'Descrição', 'Categoria', 'Valor'], $linhas, [25, 95, 40, 30], ['L', 'L', 'L', 'R'],
             ['', '', 'Total', dinheiro($r['total_gastos'])]);

$pdf->Titulo('Vendas registradas');
$linhas = [];
foreach ($vendas as $v) {
    $linhas[] = ['#' . $v['id'], dataBr($v['data_venda'], true), $v['funcionario'], dinheiro($v['total'])];
}
$pdf->Tabela(['Nº', 'Data', 'Funcionário', 'Total'], $linhas, [20, 40, 100, 30], ['L', 'L', 'L', 'R'],
             ['', '', 'Total', dinheiro($r['total_vendas'])]);

// 'I' abre no navegador (dá para salvar ou imprimir de lá)
$nomeArquivo = 'relatorio_' . $inicio . '_a_' . $fim . '.pdf';
$pdf->Output('I', $nomeArquivo);
