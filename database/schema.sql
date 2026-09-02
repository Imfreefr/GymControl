-- ============================================================
-- GymControl - Schema do Banco de Dados
-- Compatível com SQLite e MySQL (via PDO)
-- Criação idempotente com IF NOT EXISTS e chaves estrangeiras
-- ============================================================

-- ------------------------------------------------------------
-- Tabela: users
-- Armazena credenciais e perfil (admin / aluno)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    nome       TEXT    NOT NULL,
    email      TEXT    NOT NULL UNIQUE,
    senha      TEXT    NOT NULL,
    tipo       TEXT    NOT NULL DEFAULT 'aluno' CHECK (tipo IN ('admin', 'aluno')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Tabela: alunos
-- Dados complementares vinculados a users (1:1)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS alunos (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id         INTEGER NOT NULL UNIQUE,
    telefone        TEXT,
    data_nascimento DATE,
    objetivo        TEXT,
    status          TEXT    DEFAULT 'ativo',
    professor       TEXT,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Tabela: exercicios
-- Catálogo de exercícios disponíveis
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS exercicios (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    nome           TEXT    NOT NULL,
    grupo_muscular TEXT    NOT NULL,
    descricao      TEXT,
    series         INTEGER DEFAULT 3,
    repeticoes     TEXT    DEFAULT '12',
    carga          TEXT    DEFAULT '-',
    descanso       TEXT    DEFAULT '60s',
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Tabela: treinos
-- Treinos montados por aluno
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS treinos (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    aluno_id    INTEGER NOT NULL,
    nome        TEXT    NOT NULL,
    objetivo    TEXT,
    observacoes TEXT,
    professor   TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos (id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Tabela: treino_exercicios
-- Relacionamento N:N entre treinos e exercícios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS treino_exercicios (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    treino_id    INTEGER NOT NULL,
    exercicio_id INTEGER NOT NULL,
    series       INTEGER DEFAULT 3,
    repeticoes   TEXT    DEFAULT '12',
    carga        TEXT    DEFAULT '-',
    descanso     TEXT    DEFAULT '60s',
    FOREIGN KEY (treino_id)    REFERENCES treinos (id)    ON DELETE CASCADE,
    FOREIGN KEY (exercicio_id) REFERENCES exercicios (id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Tabela: frequencias
-- Registro de presenças por aluno e data
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS frequencias (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    aluno_id INTEGER NOT NULL,
    data     DATE    NOT NULL,
    presente INTEGER DEFAULT 1,
    FOREIGN KEY (aluno_id) REFERENCES alunos (id) ON DELETE CASCADE,
    UNIQUE (aluno_id, data)
);

-- ------------------------------------------------------------
-- Tabela: evolucoes
-- Histórico de peso, altura e observações por aluno
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS evolucoes (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    aluno_id   INTEGER NOT NULL,
    peso       REAL,
    altura     REAL,
    observacao TEXT,
    data       DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (aluno_id) REFERENCES alunos (id) ON DELETE CASCADE
);
