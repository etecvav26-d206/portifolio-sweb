# Análise do GitHub — SWEB

## Organização do portfólio

Nós organizamos o portfólio de Sistemas Web por bimestres. Nesta análise, reunimos as duas entregas do escopo: a página PHP que demonstra criptografias e o projeto completo da pizzaria, que está em um repositório próprio da organização.

## Página PHP de criptografias

A página [`3BM/pesquisa-php/index.php`](pesquisa-php/index.php) foi criada para demonstrar, no navegador, a diferença entre codificação, hash e criptografia. Ela apresenta exemplos com `base64_encode()`, `md5()`, `sha1()`, `password_hash()` e `openssl_encrypt()`.

Durante a atividade, percebemos que Base64 apenas representa o texto em outro formato e pode ser revertido, enquanto hash é usado para gerar um resumo que não deve ser tratado como texto original. Já a criptografia simétrica usa uma chave para proteger e recuperar o conteúdo. A página também reforça que senhas devem usar funções próprias, como `password_hash()`, e que algoritmos antigos não são uma boa escolha para novos sistemas.

## Projeto da pizzaria e os três CRUDs

O projeto principal está no repositório [`Papa-s-Magleoni`](https://github.com/etecvav26-d206/Papa-s-Magleoni). O site possui uma página pública para o cliente, disponível em [`index.php`](https://github.com/etecvav26-d206/Papa-s-Magleoni/blob/main/index.php), e uma área administrativa conectada ao MySQL por PDO.

Os três CRUDs completos são:

- [CRUD de pizzas](https://github.com/etecvav26-d206/Papa-s-Magleoni/blob/main/gerenciar.php);
- [CRUD de categorias](https://github.com/etecvav26-d206/Papa-s-Magleoni/blob/main/categorias.php);
- [CRUD de depoimentos](https://github.com/etecvav26-d206/Papa-s-Magleoni/blob/main/depoimentos.php).

Cada cadastro permite consultar os registros e realizar as operações de criação, edição e exclusão. A conexão e as consultas usam PDO, com consultas preparadas nas operações que recebem dados do usuário. O arquivo [`database.sql`](https://github.com/etecvav26-d206/Papa-s-Magleoni/blob/main/database.sql) organiza as tabelas e os registros iniciais usados no projeto.

Também mantivemos a separação entre a parte pública do site e o painel administrativo. Assim, o cliente consegue visualizar o cardápio e as informações da pizzaria, enquanto os responsáveis podem administrar pizzas, categorias e depoimentos.

## O que aprendemos

Ao comparar a página de criptografias com o projeto da pizzaria, entendemos que SWEB envolve tanto a proteção das informações quanto a construção de funcionalidades que realmente persistem dados. A página PHP ajudou a diferenciar técnicas de segurança; os três CRUDs mostraram como PHP, PDO e MySQL se conectam em um sistema web completo.

## Referências diretas

- [Portfólio SWEB](https://github.com/etecvav26-d206/portifolio-sweb)
- [Projeto Papa's Magleoni](https://github.com/etecvav26-d206/Papa-s-Magleoni)
- [Requisitos oficiais de SWEB](https://github.com/ronildo-ferreira/ronildo-human-layer/tree/main/01-EtecVAV/2D/sweb-sistemas-web/3BIM)
