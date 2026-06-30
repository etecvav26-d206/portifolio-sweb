# Pesquisa - Segurança em PHP

**Integrantes:** Laura Cristina Gonçalves da Cruz, Otávio Giovanelli Biazzi, Pedro Henrique Miranda e Pedro Henrique Dalle Molle Godoi
**Série:** 2D  
**Curso:** Técnico em Informática Para Internet  
**Tema:** Funções para criptografia, hash, codificação e proteção de dados em PHP

## 1. Segurança em aplicações Web

Segurança da informação é o conjunto de práticas usadas para proteger dados contra acesso indevido, alteração, vazamento, perda ou uso incorreto. Em aplicações web, isso significa criar sistemas que preservem a confidencialidade, a integridade e a disponibilidade das informações dos usuários.

A proteção de dados é importante porque aplicações web geralmente lidam com informações sensíveis, como nomes, e-mails, senhas, endereços, dados escolares, dados financeiros e histórico de acesso. Se esses dados forem expostos, os usuários podem sofrer prejuízos, fraudes, invasões de conta ou roubo de identidade. A Autoridade Nacional de Proteção de Dados explica que a segurança da informação envolve ações para preservar confidencialidade, integridade e disponibilidade das informações, prevenindo ameaças digitais (ANPD, 2021).

Entre os principais riscos em aplicações desenvolvidas para a Internet estão:

- vazamento de senhas;
- acesso indevido a contas;
- SQL Injection;
- Cross-Site Scripting (XSS);
- Cross-Site Request Forgery (CSRF);
- sequestro de sessão;
- exposição de arquivos de configuração;
- uso de bibliotecas e versões desatualizadas;
- falhas de validação de entrada;
- transmissão de dados sem HTTPS.

O CERT.br recomenda que usuários e administradores tenham atenção com senhas, atualizações, conexões seguras e proteção contra golpes e ataques, pois esses cuidados reduzem riscos no uso de sistemas conectados à Internet (CERT.BR, 2024).

## 2. Criptografia, Hash e Codificação

Criptografia, hash e codificação são conceitos diferentes. Confundir esses termos pode causar erros graves, principalmente no armazenamento de senhas.

| Conceito | O que é | Tem volta? | Exemplo de uso |
|---|---|---|---|
| Criptografia | Técnica que transforma uma informação em um conteúdo ilegível usando chave. | Sim, se houver a chave correta. | Proteger um dado que precisa ser lido depois. |
| Hash | Função que transforma dados em um resumo de tamanho fixo. | Não, deve ser unidirecional. | Armazenar senhas de forma segura. |
| Codificação | Transformação de dados para outro formato de representação. | Sim, sem chave secreta. | Converter dados binários para Base64. |

### Criptografia

Na criptografia, uma informação é embaralhada com uma chave. Depois, ela pode ser descriptografada com a chave correta. Isso é útil quando o sistema precisa guardar um dado protegido, mas ainda precisa recuperar o conteúdo original.

Exemplo de uso:

```text
Dados do usuario -> criptografia com chave -> texto cifrado
Texto cifrado -> descriptografia com chave -> dados do usuario
```

### Hash

O hash gera um resumo matemático de uma informação. Uma boa função de hash deve dificultar ao máximo descobrir o dado original a partir do hash. Para senhas, o PHP recomenda o uso de funções específicas como `password_hash()` e `password_verify()` (PHP DOCUMENTATION GROUP, 2026a; PHP DOCUMENTATION GROUP, 2026b).

Exemplo de uso:

```text
senha digitada -> password_hash() -> hash salvo no banco
senha no login -> password_verify() -> verdadeiro ou falso
```

### Codificação

Codificação não é proteção por si só. Ela apenas muda a forma de representar os dados. Base64, por exemplo, pode transformar dados binários em texto, mas qualquer pessoa pode decodificar esse conteúdo se tiver acesso a ele (PHP DOCUMENTATION GROUP, 2026d; PHP DOCUMENTATION GROUP, 2026e).

Exemplo de uso:

```text
imagem ou arquivo binario -> Base64 -> texto transportavel
```

## 3. Funções de Hash no PHP

O PHP possui funções para trabalhar com hashes. Algumas são próprias para senhas, e outras servem para gerar resumos de dados em situações gerais.

### password_hash()

A função `password_hash()` cria um hash seguro para senha. Ela já inclui recursos importantes, como o uso de algoritmo adequado e salt. O salt é incorporado ao hash gerado, então não é necessário criar uma coluna separada apenas para ele quando se usa essa função corretamente (PHP DOCUMENTATION GROUP, 2026a).

Exemplo:

```php
$hash = password_hash($senha, PASSWORD_DEFAULT);
```

Essa função deve ser usada no cadastro ou na troca de senha do usuário.

### password_verify()

A função `password_verify()` compara uma senha digitada com o hash salvo anteriormente. Ela retorna `true` se a senha corresponder ao hash e `false` caso contrário (PHP DOCUMENTATION GROUP, 2026b).

Exemplo:

```php
if (password_verify($senhaDigitada, $hashSalvo)) {
    echo "Login permitido";
}
```

Essa função deve ser usada no login, quando o usuário informa a senha e o sistema precisa verificar se ela está correta.

### hash()

A função `hash()` gera um hash usando algoritmos como `sha256`, `sha512` e outros. Ela pode ser útil para integridade de arquivos, assinaturas simples e comparação de resumos, mas não deve ser a escolha principal para guardar senhas de usuários (PHP DOCUMENTATION GROUP, 2026c).

Exemplo:

```php
$resumo = hash("sha256", "conteudo");
```

Para senhas, o mais adequado é usar `password_hash()` e `password_verify()`, porque essas funções foram feitas especificamente para esse caso.

### Algoritmos recomendados atualmente

Para senhas, recomenda-se usar algoritmos próprios para armazenamento de senha, como:

- `PASSWORD_DEFAULT`, para usar o padrão recomendado pelo PHP;
- `PASSWORD_BCRYPT`, opção muito usada e ainda aceita;
- `PASSWORD_ARGON2ID`, quando disponível no ambiente PHP.

Algoritmos rápidos como MD5 e SHA1 não devem ser usados para senhas. Eles são antigos e rápidos demais, o que facilita ataques de força bruta e uso de listas vazadas. A OWASP recomenda algoritmos próprios para senhas, como Argon2id, bcrypt, scrypt e PBKDF2, com parâmetros adequados de custo (OWASP, 2024a).
