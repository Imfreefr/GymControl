# GymControl — Sistema de Gerenciamento de Academia

Projeto acadêmico desenvolvido para a atividade de PHP (server-side) do SENAI, com foco em uma aplicação web full-stack integrando Front-End, Back-End e Banco de Dados.

## Tema

Sistema de gestão de academia: cadastro e controle de alunos, treinos, exercícios, frequência e evolução física, com painéis distintos para aluno e administrador.

## Funcionalidades

- Cadastro / Login / Logout (sessão, hash Argon2id)
- Painel do Aluno (dados, treino, frequência, evolução com gráfico)
- Meu Treino (vários exercícios por treino)
- Exercícios (POST para cadastrar, GET para listar com busca)
- Admin: Alunos (CRUD), Exercícios (CRUD), Treinos, Frequência
- Frequência e Evolução (peso/altura/observação)
- Controle de acesso: aluno só vê seus próprios dados

## Requisitos Funcionais (RF)

| ID | Descrição |
|----|-----------|
| RF01 | O sistema deve permitir o cadastro de usuários (aluno e administrador) |
| RF02 | O sistema deve permitir login e logout com controle de sessão |
| RF03 | O sistema deve armazenar senhas utilizando hash seguro (Argon2id) |
| RF04 | O aluno deve visualizar um painel com seus dados, treino, frequência e evolução |
| RF05 | O aluno deve ter acesso restrito apenas aos seus próprios dados |
| RF06 | O administrador deve gerenciar (criar, listar, editar, excluir) alunos |
| RF07 | O administrador deve gerenciar (criar, listar, editar, excluir) exercícios |
| RF08 | O administrador deve gerenciar treinos, associando múltiplos exercícios a cada treino |
| RF09 | O sistema deve registrar a frequência (presença) do aluno por data |
| RF10 | O sistema deve registrar a evolução física do aluno (peso, altura, observações) |
| RF11 | O sistema deve exibir um gráfico de evolução do aluno |
| RF12 | O sistema deve permitir listagem e busca de exercícios (requisição GET) |
| RF13 | O sistema deve permitir o cadastro de exercícios (requisição POST) |

## Arquitetura

- **MVC** + **POO** + **PDO** (prepared statements)
- Estrutura: `Config/`, `Controller/`, `Model/`, `View/`, `templates/`, `database/`, `storage/`
- Suporte a **SQLite** (padrão, sem configurar MySQL) e **MySQL**

> **Nota sobre o Laravel Herd:** o Herd é utilizado apenas como ambiente/servidor local para rodar PHP, não há dependência do framework Laravel no `composer.json`. O projeto é PHP puro estruturado em MVC.

## Tecnologias

PHP 8.3+, PDO, SQLite/MySQL, Bootstrap 5, Chart.js, Toastify

## Ferramentas de apoio ao desenvolvimento

- **Git e GitHub**: versionamento e hospedagem do código-fonte
- **Jira Software**: planejamento e divisão de tarefas

## Instalação

### Opção 1 — SQLite (recomendado para teste rápido)

```
composer install
php -S localhost:8000
# Abra http://localhost:8000 — o banco é criado automaticamente
```

### Opção 2 — MySQL

1. Crie o banco `gymcontrol`
2. Configure `.env` (copie de `.env.example`) ou defina as variáveis `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`
3. Importe `database/schema.sql`

> O `.env` só é necessário caso opte pela Opção 2 (MySQL). Rodando com SQLite (padrão), nenhuma configuração adicional é exigida.

## Acesso demo

- **Admin:** `admin@gymcontrol.com` / `Admin123!`
- **Aluno:** `aluno@teste.com` / `Aluno123!`

## Segurança

- Senha com `password_hash` (Argon2id)
- Prepared statements (anti SQL Injection)
- `htmlspecialchars` (anti XSS)
- CSRF token nos formulários POST
- Validação de entrada, controle de acesso por tipo de usuário

## Estrutura

```
Config/configuration.php
Controller/UsuarioController, AlunoController, ExercicioController, TreinoController
Model/Connection, Usuario, Aluno, Exercicio, Treino, Frequencia, Evolucao
View/register.php, painel_aluno.php, meu_treino.php, frequencia.php, evolucao.php
View/admin/painel_admin.php, alunos.php, exercicios.php, treinos.php ...
templates/css/global.css, login.css, register.css
database/schema.sql
index.php (login)
```

## Fluxos exigidos

- **POST:** cadastro de usuário, exercício, treino, aluno, evolução, frequência
- **GET:** listagem de exercícios/alunos, visualização de treino/frequência/evolução

## Autores

- [Nome do integrante 1]
- [Nome do integrante 2] *(caso trabalho em dupla)*
