# Sistema de Gestão para Gráfica

Projeto de PHP (SWEB) do 2°D INFONET, ETEC Vasco Antônio Venchiarutti.

**Grupo:** Otávio Giovanelli Biazzi, Pedro Henrique Miranda, Laura Cristina e Pedro Godoi

Sistema web para organizar a gráfica: catálogo público, controle de estoque, registro de vendas,
painel administrativo, gastos, cadastro de funcionários e relatório em PDF para a diretoria/APM.
As funcionalidades seguem o documento `docs/Plano_Funcionalidades.pdf`.

---

## Tecnologias

- PHP 8 (com PDO)
- MySQL / MariaDB
- HTML, CSS e JavaScript, sem frameworks
- [FPDF](http://www.fpdf.org/) para gerar o relatório em PDF (já está em `lib/fpdf`)

## Como rodar (XAMPP)

1. Copie a pasta `grafica` para `C:\xampp\htdocs\`.
2. No painel do XAMPP, ligue o **Apache** e o **MySQL**.
3. Abra `http://localhost/grafica/instalar.php` e clique em **Instalar banco de dados**
   (ou, se preferir, importe `banco/grafica.sql` pelo phpMyAdmin).
4. Se a pasta `config` não tiver o `conexao.php`, copie `conexao.exemplo.php` com o nome `conexao.php`.
5. Acesse `http://localhost/grafica`.

> O `instalar.php` só funciona no próprio computador onde o XAMPP está rodando (localhost).
> Assim, ninguém que acesse o sistema pela rede da escola consegue apagar o banco.

### Usuários de teste

| Login   | Senha     | Nível         |
|---------|-----------|---------------|
| `admin` | `admin123`| Administrador |
| `maria` | `func123` | Funcionário   |

## Funcionalidades (item do PDF → arquivo)

| Item | Funcionalidade | Arquivo |
|------|----------------|---------|
| 4.1 | Login de funcionários e níveis de acesso | `login.php`, `includes/auth.php` |
| 4.2 | Catálogo interno e controle de estoque | `produtos.php` |
| 4.3 | Catálogo público (sem login) | `catalogo.php` |
| 4.4 | Registro de vendas e pedidos | `vendas_nova.php` |
| 4.5 | Histórico de vendas | `vendas_historico.php` |
| 4.6 | Painel administrativo | `admin/painel.php` |
| 4.7 | Registro de gastos | `admin/gastos.php` |
| 4.8 | Cadastro de funcionários | `admin/funcionarios.php` |
| 4.9 | Relatórios em PDF | `admin/relatorio.php`, `admin/relatorio_pdf.php` |

## Imagens

- **Fotos dos produtos:** são enviadas pelo próprio sistema, em Produtos e estoque → Foto. Elas ficam em `assets/img/produtos/` e aparecem no catálogo público.
- **Logo e foto da escola:** coloque `logo.png` e `fachada.jpg` em `assets/img/` (veja o `LEIA-ME.txt`).
- As cores seguem a identidade da ETEC VAV: preto, vermelho e branco.

## Níveis de acesso

- **Funcionário:** faz login, consulta e edita produtos, registra vendas e vê o histórico.
- **Administrador:** tudo o que o funcionário faz, mais painel, gastos, funcionários, relatórios e inativar produtos.

## Estrutura de pastas

```
grafica/
├── admin/          páginas que só o administrador acessa
├── assets/css/     estilo do site
├── banco/          script do banco de dados (grafica.sql)
├── config/         conexão com o banco
├── docs/           PDF com o plano de funcionalidades
├── includes/       arquivos reaproveitados (login, menu, funções, cálculos)
├── lib/fpdf/       biblioteca que gera o PDF
├── catalogo.php    catálogo público
├── inicio.php      tela inicial depois do login
├── login.php / logout.php
├── produtos.php
├── vendas_nova.php
└── vendas_historico.php
```

## Segurança

- As senhas são salvas com `password_hash()` e conferidas com `password_verify()`.
- Todas as consultas usam *prepared statements*, o que protege contra SQL Injection.
- Os textos exibidos passam por `htmlspecialchars()`, o que protege contra XSS.
- O preço da venda é sempre buscado no banco, nunca no navegador.
- A venda usa uma transação: a venda, os itens e a baixa no estoque são salvos juntos, ou nada é salvo.
- Produtos e funcionários são **inativados** em vez de excluídos, para manter o histórico.
- O upload de foto confere o tipo real da imagem (JPG, PNG ou WEBP) e o tamanho máximo de 3 MB.
- O instalador do banco só pode ser usado no computador do servidor.

## Limites desta versão

Conforme o item 7 do plano, esta versão não tem pagamento on-line, envio automático de e-mail,
integração com sistemas externos nem aplicativo móvel. O relatório é baixado em PDF e o
responsável o encaminha para a APM.
