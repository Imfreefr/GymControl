# GymControl - Sistema de Gerenciamento de Academia

Sistema simples de academia em Laravel 13 com POO/MVC, MySQL (SQLite dev + MySQL prod), Eloquent/PDO prepared statements, CSRF, hash e validação backend.

## Funcionalidades
- Usuários (administrador/aluno), alunos, exercícios, treinos com exercícios, frequência e evolução
- Perfis: administrador vê tudo; aluno vê apenas seus dados (treino, frequência, evolução)
- Cadastro/login/logout com hash, POST cadastro real, GET listagem com busca, middleware perfil e abort 403
- Paineis: `/painel-admin` (dashboard) e `/painel-aluno` (meus treinos/frequência/evolução)

## Tecnologias
Laravel 13, PHP 8.3, Eloquent, SQLite (dev) / MySQL (prod), Bootstrap 5 + Bootstrap Icons via CDN, PHPUnit

## Instalação
```bash
composer install
copy .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed
php artisan serve
```

## Banco
Dev padrão `DB_CONNECTION=sqlite` com `database/database.sqlite`.
Prod MySQL: edite `.env` descomentando:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gymcontrol
DB_USERNAME=root
DB_PASSWORD=
```
Crie o banco `gymcontrol` e rode `php artisan migrate --force`.

## Usuários de teste (seeder)
- admin@academia.com / password (administrador)
- aluno@academia.com / password (aluno, vinculado a 1 aluno)

## Rotas principais
`/` (landing), `/login`, `/cadastro`, `/painel-aluno`, `/painel-aluno/meu-treino`, `/painel-admin`, `/alunos` CRUD, `/exercicios` (busca `?busca=`), `/treinos`, `/frequencias`, `/evolucoes`, `POST /logout`

## Testes
```bash
php artisan test
```
16 testes cobrindo cadastro POST, validações, login/logout, listagem/busca GET e isolamento aluno.
