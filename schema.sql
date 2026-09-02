-- =========================================================
-- BANCO DE DADOS
-- =========================================================

CREATE DATABASE IF NOT EXISTS academia
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE academia;


-- =========================================================
-- TABELA: USERS
-- =========================================================

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


-- =========================================================
-- TABELA: ALUNOS
-- =========================================================

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


-- =========================================================
-- TABELA: EXERCICIOS
-- =========================================================

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


-- =========================================================
-- TABELA: TREINOS
-- =========================================================

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


-- =========================================================
-- TABELA: TREINO_EXERCICIOS
-- =========================================================

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


-- =========================================================
-- TABELA: FREQUENCIAS
-- =========================================================

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


-- =========================================================
-- TABELA: EVOLUCOES
-- =========================================================

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
