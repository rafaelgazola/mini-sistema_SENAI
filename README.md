# Sistema de Gestão de Alunos

Aplicação web em PHP para cadastrar, consultar, atualizar e excluir alunos. Também possui cadastro e login de usuários para proteger as páginas da pasta `app/`.

## Sumário

1. [Sobre o projeto](#sobre-o-projeto)
2. [Tecnologias](#tecnologias)
3. [Estrutura](#estrutura)
4. [Banco de dados](#banco-de-dados)
5. [Configuração do PostgreSQL](#configuração-do-postgresql)
6. [Como executar](#como-executar)
7. [Primeiro acesso](#primeiro-acesso)
8. [Funcionalidades](#funcionalidades)
9. [Testes](#testes)
10. [Autenticação](#autenticação)
11. [Possíveis problemas](#possíveis-problemas)

## Sobre o projeto

O sistema apresenta uma página inicial pública e formulários para gestão de alunos. As páginas usam `POST` para enviar os dados ao próprio arquivo, que chama as funções de banco centralizadas em `includes/functions.php`.

O cadastro de usuário, o login e o logout ficam na pasta `login/`. Depois do login, o usuário pode acessar as operações de alunos pelo menu compartilhado.

## Tecnologias

- PHP com PDO PostgreSQL
- PostgreSQL
- HTML
- Sessões nativas do PHP

Não há CSS, JavaScript, framework, Composer ou arquivo de dependências no repositório. Também não existe uma versão mínima de PHP definida; é necessário PHP com suporte a PDO PostgreSQL.

## Estrutura

```text
mini-sistema/
├── index.php                 # Página inicial pública
├── README.md                # Esta documentação
├── app/
│   ├── create.php            # Cadastro de alunos
│   ├── select.php            # Listagem de alunos
│   ├── selectw.php           # Consulta por ID
│   ├── update.php            # Atualização de aluno
│   └── delete.php            # Exclusão de aluno
├── database/
│   └── conect.php            # Conexão PDO
├── includes/
│   ├── header.php            # Menu
│   ├── footer.php            # Rodapé
│   └── functions.php         # Funções e consultas SQL
└── login/
    ├── login.php             # Login
    ├── cadastrar.php         # Cadastro de usuário
    ├── logout.php            # Logout
    └── verifica_user.php     # Proteção de acesso
```

Os nomes são os arquivos reais do projeto: a conexão está em `conect.php` e a consulta individual em `selectw.php`.

## Banco de dados

O projeto usa PostgreSQL através de PDO. A conexão é criada em `database/conect.php` com o DSN:

```text
pgsql:host=HOST;dbname=NOME_DO_BANCO
```

A configuração atual aponta para o host `192.168.10.68`, o banco `escola` e o usuário `escola`. A senha não é publicada neste README. O DSN não informa porta; nenhuma porta diferente da padrão é configurada pelo projeto.

O código utiliza as tabelas abaixo. Os tipos não estão declarados no repositório; os tipos da tabela são sugestões para um banco local compatível.

### `usuarios`

| Campo | Tipo sugerido | Uso |
|---|---|---|
| `id` | `SERIAL` | ID gravado na sessão |
| `email` | `VARCHAR(255)` | Busca no login |
| `senha` | `VARCHAR(255)` | Comparação no login |

### `alunos`

| Campo | Tipo sugerido | Uso |
|---|---|---|
| `id` | `SERIAL` | ID das consultas e alterações |
| `nome` | `VARCHAR(255)` | Nome |
| `turma` | `VARCHAR(255)` | Turma |
| `nascimento` | `DATE` | Data de nascimento |
| `ativo` | `BOOLEAN` | Situação |
| `email` | `VARCHAR(255)` | E-mail |

Não há SQL, `UNIQUE`, `NOT NULL`, `DEFAULT` ou chave estrangeira definidos nos arquivos do projeto. Também não existe relacionamento físico entre `usuarios` e `alunos`.

### SQL para criar o banco

Execute `CREATE DATABASE` conectado a um banco administrativo. Depois conecte-se ao banco criado e execute os `CREATE TABLE`.

```sql
CREATE DATABASE escola;
```

```sql
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255),
    senha VARCHAR(255)
);

CREATE TABLE alunos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255),
    turma VARCHAR(255),
    nascimento DATE,
    ativo BOOLEAN,
    email VARCHAR(255)
);
```

Esse schema usa apenas as duas tabelas e as colunas consultadas pelo PHP. O repositório não inclui arquivo `.sql`.

## Configuração do PostgreSQL

É necessário ter o PostgreSQL instalado e o serviço ativo.

### Opção 1: pgAdmin

1. Abra o pgAdmin e conecte-se ao servidor PostgreSQL.
2. Crie o banco `escola`.
3. Abra o Query Tool desse banco.
4. Execute os comandos `CREATE TABLE` deste README.
5. Confira `usuarios` e `alunos` em `Schemas > public > Tables`; use `Refresh` se necessário.

### Opção 2: `psql`

```bash
psql -U postgres
```

No prompt do PostgreSQL:

```sql
CREATE DATABASE escola;
\c escola
```

Depois execute os comandos `CREATE TABLE` acima. Como não existe arquivo SQL no repositório, não há comando `psql -f` para este projeto.

Para verificar o banco e as tabelas:

```text
\l
\c escola
\dt
```

```sql
SELECT * FROM usuarios;
SELECT * FROM alunos;
```

## Como executar

### 1. Preparar o PHP e o banco

Coloque a pasta `mini-sistema` no computador, instale/inicie o PostgreSQL, crie o banco e as tabelas e ajuste `database/conect.php`:

```php
$host = "localhost";
$dbname = "escola";
$user = "seu_usuario";
$pass = "sua_senha";
```

O arquivo atual usa um host específico da rede do projeto. Em outra máquina, `host`, `dbname`, `user` e `pass` devem corresponder ao PostgreSQL local.

### 2. Verificar o driver do PHP

```bash
php -v
php -m
```

No Windows, para filtrar as extensões:

```bash
php -m | findstr pgsql
```

`pdo_pgsql` precisa estar habilitado. `pgsql` também costuma aparecer quando o suporte PostgreSQL está instalado. Se não aparecer, verifique as extensões PostgreSQL no `php.ini` usado pelo PHP.

### 3. Iniciar o servidor

O menu usa caminhos absolutos começando por `/mini-sistema`. Por isso, abra o terminal na pasta pai do projeto e execute:

```bash
cd pasta-pai
php -S localhost:8000
```

Com a estrutura `pasta-pai/mini-sistema/`, acesse:

```text
http://localhost:8000/mini-sistema/index.php
```

## Primeiro acesso

1. Abra `login/cadastrar.php` pelo endereço `http://localhost:8000/mini-sistema/login/cadastrar.php`.
2. Informe e-mail e senha e envie o formulário.
3. O usuário é inserido em `usuarios` e a página redireciona para `index.php`.
4. Abra `login/login.php` e informe as mesmas credenciais.
5. Acesse as páginas de alunos pelo menu.

O cadastro de usuário e `index.php` são públicos. As páginas dentro de `app/` exigem sessão autenticada.

## Funcionalidades

### Autenticação

- `login/cadastrar.php` chama `cadastra_user()` e executa `INSERT` em `usuarios`.
- `login/login.php` chama `consultar_user()`, busca o e-mail e compara a senha.
- `login/logout.php` limpa a sessão e redireciona para `index.php`.
- `login/verifica_user.php` redireciona para o login quando `$_SESSION['id']` não existe.

### CRUD de alunos

- **Create:** `app/create.php` recebe `nome`, `turma`, `email`, `nasc` e `ativo`; `cadastrar()` executa `INSERT`.
- **Read:** `app/select.php` chama `relatorio()` para `SELECT * FROM alunos`; `app/selectw.php` chama `consultar()` com um ID.
- **Update:** `app/update.php` recebe o ID e os campos do formulário; `atualizar()` executa `UPDATE ... WHERE id = :id`.
- **Delete:** `app/delete.php` recebe o ID; `apagar()` executa `DELETE FROM alunos WHERE id = :id`.

Não há filtros na listagem nem confirmação adicional antes da exclusão. Os campos de ID têm validação HTML `required`; os demais formulários não possuem validação de servidor.

## Testes

1. Cadastre um usuário e faça login.
2. Cadastre um aluno em `app/create.php`.
3. Confira a listagem em `app/select.php`.
4. Consulte o ID em `app/selectw.php`.
5. Altere o registro em `app/update.php` e consulte novamente.
6. Exclua o ID em `app/delete.php` e confirme na listagem.
7. Verifique os dados com `SELECT * FROM usuarios;` e `SELECT * FROM alunos;`.
8. Faça logout e tente abrir uma página de `app/` para verificar o redirecionamento ao login.

As funções do banco estão em `includes/functions.php`. Elas usam `prepare()`, `bindParam()` e `execute()` do PDO. Exceções `PDOException` e mensagens de sucesso são exibidas diretamente pelas funções.

## Autenticação

O login inicia uma sessão e grava o ID do usuário em `$_SESSION['id']`. Cada página de `app/` inclui `verifica_user.php`, que inicia a sessão caso necessário e exige essa variável. Sem ela, ocorre redirecionamento para `login/login.php`.

O logout limpa `$_SESSION`, executa `session_destroy()` e retorna à página inicial. As senhas são gravadas e comparadas diretamente; o código não usa `password_hash()` nem `password_verify()`.

## Possíveis problemas

- **`could not find driver`:** habilite `pdo_pgsql` no PHP e confira com `php -m`.
- **`connection refused`:** verifique se o PostgreSQL está ativo e se o host configurado está correto.
- **`password authentication failed`:** confira o usuário e a senha em `database/conect.php`.
- **`database "escola" does not exist`:** crie o banco ou ajuste `$dbname`.
- **`relation "alunos" does not exist`:** crie as tabelas com o SQL deste README.
