# Atividade — Cookies e Sessions no PHP

## Informações do grupo

| Nome | GitHub | Exercício |
|------|--------|-----------|
| Nome do Aluno 1 | @usuario1 | Exercício 1 |
| Otávio Giovanelli Biazzi | @OtavioBiazzi | Exercício 2 |
| Pedro Henrique Miranda | @M1randaPHM | Exercício 3 |
| Laura Cristina Gonçalves da Cruz| @LauraCristinaCruz | Exercício 4 |

---

## Exercício 1 — Pergunta conceitual

## Diferença entre Cookies e Sessions no PHP

No PHP, a principal diferença reside no local de armazenamento e na segurança dos dados. **Cookies** são arquivos de texto armazenados no navegador do usuário (client-side), o que os torna ideais para dados de longa duração, como preferências de tema, mas são vulneráveis a manipulações externas. Já as **Sessions** armazenam as informações no servidor (server-side), mantendo apenas um ID de referência no navegador. Isso garante maior segurança para dados sensíveis, como logins, pois o conteúdo real não fica exposto diretamente ao usuário. Enquanto cookies têm validade definida manualmente, sessões geralmente expiram ao fechar o navegador.

---

### Resumo Comparativo

| Característica | Cookie | Session |
| :--- | :--- | :--- |
| **Local de Armazenamento** | Navegador (Cliente) | Servidor |
| **Segurança** | Baixa (Editável pelo usuário) | Alta (Protegida no servidor) |
| **Capacidade de Dados** | Limitada (aprox. 4KB) | Virtualmente ilimitada |
| **Persistência** | Pode durar dias, meses ou anos | Geralmente encerra ao fechar o browser |

---

## Exercício 2 — Pergunta de aplicação

**Responsável:** Otávio Giovanelli Biazzi

**Pergunta:** Como cookies e sessions poderiam ser utilizados em uma loja virtual?

**Resposta:**

 COOKIES E SESSIONS EM UMA LOJA VIRTUAL

 Cookies

Cookies são armazenados no navegador do usuário e são pequenos dados salvos no próprio navegador.

Em uma loja virtual, eles podem ser utilizados das seguintes formas:

- Manter o usuário logado
  Quando o cliente faz login, um cookie pode guardar um identificador, para que ele não precise fazer login toda hora.

- Salvar preferências
  Idioma (português/inglês), tema (claro/escuro) e produtos visualizados recentemente.

- Carrinho de compras  
  Armazenar os itens do carrinho colocados pelo usuário, para que, mesmo que ele feche e abra o site, os produtos continuem guardados.

- Marketing  
  Saber quais produtos o usuário mais busca e, assim, mostrar anúncios personalizados depois.


 Sessions

As sessions guardam dados no servidor, e o cliente recebe apenas um ID (geralmente por meio de um cookie).

- Controle de login  
  Quando o usuário faz login, o servidor cria uma sessão e guarda dados como ID do usuário e permissões. Assim, o navegador armazena só o ID da sessão, deixando o processo mais seguro.

- Carrinho de compras 
  Os produtos ficam armazenados na sessão do servidor. Assim, mesmo que o usuário navegue entre as páginas, os itens do carrinho continuam lá.

- Processo de compra
  Guarda dados temporários, como endereço e forma de pagamento, evitando que o usuário perca informações ao mudar de página.

- Controle de acesso 
  Por exemplo, apenas usuários logados conseguem acessar a parte de “Meus pedidos”.
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

**Responsável:** Laura Cristina Gonçalves da Cruz 

**Pergunta:** Por que sessions são geralmente preferidas para autenticação?

**Resposta:**

As Sessions são preferidas para autenticação principalmente porque são muito mais seguras e confiáveis.

Quando você usa session, as informações importantes (como se o usuário está logado ou não) ficam guardadas no servidor, e não no navegador. O usuário só recebe um identificador (ID da sessão), então ele não tem acesso direto aos dados. Isso diminui bastante o risco de alguém alterar informações ou tentar “burlar” o sistema.

Além disso, fica muito mais fácil de controlar os dados. Por exemplo, você pode encerrar uma sessão a qualquer momento (por exemplo: quando o usuário faz logout), e isso invalida o acesso imediatamente.

Já usar apenas cookies para autenticação é mais arriscado, porque eles ficam no navegador e podem ser:

* alterados pelo próprio usuário;
* roubados em ataques (como interceptação de dados);
* reutilizados por outra pessoa indevidamente.

Por isso, na prática, o mais comum é usar sessions para autenticação e,por algumas vezes, cookies só como apoio (por exemplo: para “lembrar” o login), mas nunca como única forma de segurança.
