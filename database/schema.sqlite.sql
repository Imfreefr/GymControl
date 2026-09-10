-- GymControl - Schema SQLite (para testes)
-- Derivado de schema.sql MySQL, compatível com testes locais sem MySQL
CREATE TABLE IF NOT EXISTS users (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    nome       TEXT NOT NULL,
    email      TEXT NOT NULL UNIQUE,
    senha      TEXT NOT NULL,
    tipo       TEXT NOT NULL DEFAULT 'aluno' CHECK (tipo IN ('admin', 'aluno')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS alunos (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id         INTEGER NOT NULL UNIQUE,
    telefone        TEXT,
    data_nascimento DATE,
    objetivo        TEXT,
    status          TEXT DEFAULT 'ativo',
    professor       TEXT,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS exercicios (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    nome           TEXT NOT NULL,
    grupo_muscular TEXT NOT NULL,
    descricao      TEXT,
    series         INTEGER DEFAULT 3,
    repeticoes     TEXT DEFAULT '12',
    carga          TEXT DEFAULT '-',
    descanso       TEXT DEFAULT '60s',
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS treinos (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    aluno_id    INTEGER NOT NULL,
    nome        TEXT NOT NULL,
    objetivo    TEXT,
    observacoes TEXT,
    professor   TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS treino_exercicios (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    treino_id    INTEGER NOT NULL,
    exercicio_id INTEGER NOT NULL,
    series       INTEGER DEFAULT 3,
    repeticoes   TEXT DEFAULT '12',
    carga        TEXT DEFAULT '-',
    descanso     TEXT DEFAULT '60s',
    FOREIGN KEY (treino_id) REFERENCES treinos(id) ON DELETE CASCADE,
    FOREIGN KEY (exercicio_id) REFERENCES exercicios(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS frequencias (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    aluno_id INTEGER NOT NULL,
    data     DATE NOT NULL,
    presente INTEGER DEFAULT 1,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    UNIQUE (aluno_id, data)
);
CREATE TABLE IF NOT EXISTS evolucoes (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    aluno_id   INTEGER NOT NULL,
    peso       REAL,
    altura     REAL,
    observacao TEXT,
    data       DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
);
INSERT OR IGNORE INTO users (id, nome, email, senha, tipo) VALUES
(1, 'Admin GymControl', 'admin@gymcontrol.com', '$argon2id$v=19$m=131072,t=4,p=2$Mm1xaC5lS1dSU1l1L0pvaw$E1qbb6WBaHYxMkIf7WjbSoKxpzAu1+Hf5nVVlDNVkz4', 'admin'),
(2, 'Aluno Teste', 'aluno@teste.com', '$argon2id$v=19$m=131072,t=4,p=2$ZGxhZE9RamZ2QlR5S1RPbw$NoCH/Y/4AzfcffCLIN1qZMwvpyUPK0Ni4r9f6NyPG7E', 'aluno');
INSERT OR IGNORE INTO alunos (id, user_id, telefone, data_nascimento, objetivo, status, professor) VALUES
(1, 2, '(11) 99999-0000', '2000-05-15', 'Hipertrofia', 'ativo', 'Prof. Carlos');
