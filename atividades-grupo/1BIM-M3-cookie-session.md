# Atividade — Cookies e Sessions no PHP

## Informações do grupo

| Nome | GitHub | Exercício |
|------|--------|-----------|
| Nome do Aluno 1 | @usuario1 | Exercício 1 |
| Otávio Giovanelli Biazzi | @OtavioBiazzi | Exercício 2 |
| Pedro Henrique Miranda | @M1randaPHM | Exercício 3 |
| Nome do Aluno 4 | @usuario4 | Exercício 4 |

---

## Exercício 1 — Pergunta conceitual

**Responsável:** Nome do Aluno 1

**Pergunta:** Explique a diferença entre cookies e sessions no PHP.

**Resposta:**

(escreva aqui sua resposta entre 5 e 10 linhas)

---

## Exercício 2 — Pergunta de aplicação

**Responsável:** Nome do Aluno 2

**Pergunta:** Como cookies e sessions poderiam ser utilizados em uma loja virtual?

**Resposta:**

(escreva aqui sua resposta entre 5 e 10 linhas)

---

## Exercício 3 — Pergunta de investigação

**Responsável:** Pedro Henrique Miranda

**Pergunta:** Descreva o que aconteceu em cada etapa do teste com o cookie.

**Resposta:**

Na primeira execução, ao acessar a página pela primeira vez, o navegador exibe a mensagem "Cookie ainda não disponível.". Isso ocorre porque, embora a função setcookie tenha sido executada, o cookie ainda não foi enviado de volta pelo navegador para o servidor nesta requisição.

Ao atualizar a página a mensagem muda para "Valor do cookie: 1". Como o navegador já havia recebido e salvo o cookie na etapa anterior, ao atualizar a página ele envia esse cookie junto com a requisição HTTP. Assim, o PHP consegue lê-lo através da variável $_COOKIE.
Ao abrir as ferramentas de desenvolvedor (na aba Application > Cookies), é possível visualizar o cookie armazenado. Confirma-se que o seu Nome é contador, o Valor é 1, e é possível notar também que sua Data de Expiração está definida para 1 hora no futuro (devido ao time()+3600).

Ao apagar o cookie manualmente e atualizar a página, o ciclo se reinicia. Como o navegador não tem mais o cookie salvo para enviar ao servidor, a variável $_COOKIE["contador"] volta a ficar vazia, exibindo novamente a mensagem "Cookie ainda não disponível.".

Por que o cookie não aparece imediatamente na primeira execução?

O cookie não aparece na primeira execução devido à forma como a comunicação HTTP funciona.
A variável superglobal $_COOKIE do PHP é preenchida apenas com os cookies que chegam na Requisição HTTP (ou seja, o que o navegador enviou para o servidor).

Quando usamos a função setcookie(), o PHP não cria o cookie no servidor; ele apenas adiciona uma instrução (um cabeçalho Set-Cookie) na Resposta HTTP. Portanto, na primeira execução, o PHP manda o navegador salvar o cookie, mas a variável $_COOKIE continuará vazia até que uma nova requisição seja feita e o navegador finalmente envie o cookie recém-salvo de volta para o servidor.

---

## Exercício 4 — Pergunta de reflexão

**Responsável:** Nome do Aluno 4

**Pergunta:** Por que sessions são geralmente preferidas para autenticação?

**Resposta:**

(escreva aqui sua resposta entre 5 e 10 linhas)
