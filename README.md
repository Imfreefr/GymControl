# GymControl — Sistema de Gerenciamento de Academia

Projeto acadêmico simples, funcional e bem organizado. Inspirado na organização simples do **FitCalc.zip** (Config / Controller / Model / View / templates).

## Funcionalidades
- Cadastro / Login / Logout (sessão, hash Argon2id)
- Painel do Aluno (dados, treino, frequência, evolução com gráfico)
- Meu Treino (vários exercícios por treino)
- Exercícios (POST para cadastrar, GET para listar com busca)
- Admin: Alunos (CRUD), Exercícios (CRUD), Treinos, Frequência
- Frequência e Evolução (peso/altura/observação)
- Controle de acesso: aluno só vê seus dados

## Arquitetura
- **MVC** + **POO** + **PDO** (prepared statements)
- Estrutura: `Config/`, `Controller/`, `Model/`, `View/`, `templates/`, `database/`, `storage/`
- Suporte a **SQLite** (padrão, sem configurar MySQL) e **MySQL**

## Tecnologias
PHP 8.3+, PDO, SQLite/MySQL, Bootstrap 5, Chart.js, Toastify

## Instalação

### Opção 1 — SQLite (recomendado para teste rápido)
```bash
composer install
php -S localhost:8000
# Abra http://localhost:8000 — o banco é criado automaticamente
```

### Opção 2 — MySQL
1. Crie o banco `gymcontrol`
2. Configure `.env` (copie de `.env.example`) ou defina variáveis `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`
3. Importe `database/schema.sql`

## Acesso demo
- **Admin:** `admin@gymcontrol.com` / `Admin123!`
- **Aluno:** `aluno@teste.com` / `Aluno123!`

## Segurança
- Senha com `password_hash` (Argon2id)
- Prepared statements (anti SQL Injection)
- `htmlspecialchars` (anti XSS)
- CSRF token nos formulários POST
- Validação de entrada, controle de acesso por tipo

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
