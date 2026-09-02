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

## Banco de Dados Exemplo
-- BANCO DE DADOS
CREATE DATABASE IF NOT EXISTS academia
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE academia;

-- TABELA: USERS
CREATE TABLE IF NOT EXISTS users (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    nome       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL UNIQUE,
    senha      VARCHAR(255) NOT NULL,
    tipo       VARCHAR(20) NOT NULL DEFAULT 'aluno',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_users_tipo
        CHECK (tipo IN ('admin', 'aluno'))
);

-- TABELA: ALUNOS
CREATE TABLE IF NOT EXISTS alunos (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    user_id         INT NOT NULL UNIQUE,
    telefone        VARCHAR(30),
    data_nascimento DATE,
    objetivo        TEXT,
    status          VARCHAR(50) DEFAULT 'ativo',
    professor       VARCHAR(255),
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_alunos_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- TABELA: EXERCICIOS
CREATE TABLE IF NOT EXISTS exercicios (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    nome           VARCHAR(255) NOT NULL,
    grupo_muscular VARCHAR(100) NOT NULL,
    descricao      TEXT,
    series         INT DEFAULT 3,
    repeticoes     VARCHAR(50) DEFAULT '12',
    carga          VARCHAR(50) DEFAULT '-',
    descanso       VARCHAR(50) DEFAULT '60s',
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- TABELA: TREINOS
CREATE TABLE IF NOT EXISTS treinos (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    aluno_id    INT NOT NULL,
    nome        VARCHAR(255) NOT NULL,
    objetivo    TEXT,
    observacoes TEXT,
    professor   VARCHAR(255),
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_treinos_aluno
        FOREIGN KEY (aluno_id)
        REFERENCES alunos(id)
        ON DELETE CASCADE
);

-- TABELA: TREINO_EXERCICIOS
CREATE TABLE IF NOT EXISTS treino_exercicios (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    treino_id    INT NOT NULL,
    exercicio_id INT NOT NULL,
    series       INT DEFAULT 3,
    repeticoes   VARCHAR(50) DEFAULT '12',
    carga        VARCHAR(50) DEFAULT '-',
    descanso     VARCHAR(50) DEFAULT '60s',

    CONSTRAINT fk_treino_exercicios_treino
        FOREIGN KEY (treino_id)
        REFERENCES treinos(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_treino_exercicios_exercicio
        FOREIGN KEY (exercicio_id)
        REFERENCES exercicios(id)
        ON DELETE CASCADE
);

-- TABELA: FREQUENCIAS
CREATE TABLE IF NOT EXISTS frequencias (
    id       INT PRIMARY KEY AUTO_INCREMENT,
    aluno_id INT NOT NULL,
    data     DATE NOT NULL,
    presente TINYINT(1) DEFAULT 1,

    CONSTRAINT fk_frequencias_aluno
        FOREIGN KEY (aluno_id)
        REFERENCES alunos(id)
        ON DELETE CASCADE,

    CONSTRAINT uq_frequencias_aluno_data
        UNIQUE (aluno_id, data)
);

-- TABELA: EVOLUCOES
CREATE TABLE IF NOT EXISTS evolucoes (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    aluno_id   INT NOT NULL,
    peso       DECIMAL(6,2),
    altura     DECIMAL(5,2),
    observacao TEXT,
    data       DATE DEFAULT (CURRENT_DATE),

    CONSTRAINT fk_evolucoes_aluno
        FOREIGN KEY (aluno_id)
        REFERENCES alunos(id)
        ON DELETE CASCADE
);

-- SEED: usuarios padrao (senha: Admin123! / Aluno123!)
INSERT IGNORE INTO users (id, nome, email, senha, tipo) VALUES
(1, 'Admin GymControl', 'admin@gymcontrol.com', '$argon2id$v=19$m=131072,t=4,p=2$Mm1xaC5lS1dSU1l1L0pvaw$E1qbb6WBaHYxMkIf7WjbSoKxpzAu1+Hf5nVVlDNVkz4', 'admin'),
(2, 'Aluno Teste', 'aluno@teste.com', '$argon2id$v=19$m=131072,t=4,p=2$ZGxhZE9RamZ2QlR5S1RPbw$NoCH/Y/4AzfcffCLIN1qZMwvpyUPK0Ni4r9f6NyPG7E', 'aluno');

INSERT IGNORE INTO alunos (id, user_id, telefone, data_nascimento, objetivo, status, professor) VALUES
(1, 2, '(11) 99999-0000', '2000-05-15', 'Hipertrofia', 'ativo', 'Prof. Carlos');
