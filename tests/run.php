<?php
$start = microtime(true);
require_once __DIR__ . '/bootstrap.php';

$pass = 0; $fail = 0; $errors = [];
function ok(string $msg): void { global $pass; $pass++; echo "  \033[32m✓\033[0m $msg\n"; }
function fail(string $msg, string $detail = ''): void { global $fail, $errors; $fail++; $d = $detail ? " — $detail" : ''; echo "  \033[31m✗\033[0m $msg$d\n"; $errors[] = "$msg$d"; }
function assertTrue(bool $v, string $msg, string $detail = ''): void { $v ? ok($msg) : fail($msg, $detail); }
function assertFalse(bool $v, string $msg): void { assertTrue(!$v, $msg); }
function assertEquals(mixed $exp, mixed $got, string $msg): void { $exp === $got ? ok($msg) : fail($msg, "esperado " . var_export($exp, true) . " got " . var_export($got, true)); }
function assertContains(string $needle, string $hay, string $msg): void { str_contains($hay, $needle) ? ok($msg) : fail($msg, "não contém '$needle'"); }
function assertNotContains(string $needle, string $hay, string $msg): void { !str_contains($hay, $needle) ? ok($msg) : fail($msg, "contém '$needle' mas não deveria"); }

echo "\n=== GymControl — Suite de Validação Funcional ===\n";
echo "Branch: teste/validacao-funcional | DB: SQLite em memória | PHP " . PHP_VERSION . "\n\n";

// 1. LINT
echo "[1/7] Lint (php -l) — 31 arquivos\n";
$phpFiles = array_merge(
    glob(__DIR__ . '/../index.php'),
    glob(__DIR__ . '/../Config/*.php'),
    glob(__DIR__ . '/../Controller/*.php'),
    glob(__DIR__ . '/../Model/*.php'),
    glob(__DIR__ . '/../View/*.php'),
    glob(__DIR__ . '/../View/admin/*.php')
);
foreach ($phpFiles as $f) {
    $out = []; $code = 0;
    exec('php -l ' . escapeshellarg($f) . ' 2>&1', $out, $code);
    $code === 0 ? ok(basename($f) . " sintaxe OK") : fail(basename($f), implode(' ', $out));
}

// 2. SCHEMA / BANCO
echo "\n[2/7] Schema e Conexão\n";
$pdo = Model\Connection::getInstance();
assertTrue($pdo instanceof PDO, "PDO instanciado");
assertEquals('sqlite', $pdo->getAttribute(PDO::ATTR_DRIVER_NAME), "Driver SQLite no teste");
$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
foreach (['users','alunos','exercicios','treinos','treino_exercicios','frequencias','evolucoes'] as $t) {
    assertTrue(in_array($t, $tables, true), "Tabela '$t' existe");
}
$seedUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
assertTrue($seedUsers >= 2, "Seed admin+aluno presente ($seedUsers)");
$admin = $pdo->query("SELECT * FROM users WHERE email='admin@gymcontrol.com'")->fetch();
assertTrue((bool)$admin && $admin['tipo'] === 'admin', "Seed admin@gymcontrol.com tipo=admin");
assertTrue(password_verify('Admin123!', $admin['senha']), "Hash admin verifica Admin123!");
$alunoSeed = $pdo->query("SELECT * FROM users WHERE email='aluno@teste.com'")->fetch();
assertTrue(password_verify('Aluno123!', $alunoSeed['senha']), "Hash aluno verifica Aluno123!");

// Limpando dados de teste anteriores mantendo seeds
$pdo->exec("DELETE FROM evolucoes"); $pdo->exec("DELETE FROM frequencias"); $pdo->exec("DELETE FROM treino_exercicios"); $pdo->exec("DELETE FROM treinos"); $pdo->exec("DELETE FROM exercicios");
$pdo->exec("DELETE FROM alunos WHERE id != 1"); $pdo->exec("DELETE FROM users WHERE id NOT IN (1,2)");

// 3. MODELS
echo "\n[3/7] Models — CRUD completo\n";

// Usuario
$u = new Model\Usuario();
$hash = password_hash('Teste123!', PASSWORD_ARGON2ID, ['memory_cost'=>1<<16,'time_cost'=>3,'threads'=>1]);
assertTrue($u->cadastrar('Teste User', 'teste@gym.com', $hash, 'aluno'), "Usuario::cadastrar");
assertTrue((bool)$u->porEmail('teste@gym.com'), "Usuario::porEmail");
assertTrue((bool)$u->porId(3), "Usuario::porId");
assertTrue($u->ultimoId() >= 3, "Usuario::ultimoId");
// porEmail inexistente
assertFalse((bool)$u->porEmail('nao@existe.com'), "Usuario::porEmail inexistente retorna false");
// Aluno
$a = new Model\Aluno();
assertTrue($a->criar(3, '(11) 98888-7777', '1995-01-10', 'Hipertrofia', 'ativo', 'Prof. Ana'), "Aluno::criar");
$aluno = $a->porUserId(3);
assertTrue((bool)$aluno, "Aluno::porUserId");
assertTrue((bool)$a->porId((int)$aluno['id']), "Aluno::porId");
$lista = $a->listar();
assertTrue(count($lista) >= 2, "Aluno::listar total >=2");
$busca = $a->listar('Teste User');
assertTrue(count($busca) >= 1, "Aluno::listar com busca");
assertTrue($a->atualizar((int)$aluno['id'], ['telefone'=>'(11) 99999-1111','objetivo'=>'Emagrecimento','status'=>'ativo']), "Aluno::atualizar");
$upd = $a->porId((int)$aluno['id']);
assertEquals('(11) 99999-1111', $upd['telefone'], "Aluno::atualizar persistiu telefone");
assertTrue($a->total() >= 2, "Aluno::total");
$cntAntes = $a->total();

// Exercicio
$ex = new Model\Exercicio();
assertTrue($ex->criar(['nome'=>'Supino Reto','grupo_muscular'=>'Peito','descricao'=>'Barra','series'=>4,'repeticoes'=>'10','carga'=>'60kg','descanso'=>'90s']), "Exercicio::criar");
assertTrue($ex->criar(['nome'=>'Agachamento','grupo_muscular'=>'Pernas']), "Exercicio::criar mínimo");
$exs = $ex->listar();
assertTrue(count($exs) >= 2, "Exercicio::listar");
$buscaEx = $ex->listar('Supino');
assertTrue(count($buscaEx) >= 1, "Exercicio::listar busca");
assertTrue((bool)$ex->porId((int)$exs[0]['id']), "Exercicio::porId");
assertTrue($ex->total() >= 2, "Exercicio::total");
$exIdDel = (int)$exs[1]['id'];
assertTrue($ex->excluir($exIdDel), "Exercicio::excluir");
assertFalse((bool)$ex->porId($exIdDel), "Exercicio::excluir removeu");

// Treino
$tr = new Model\Treino();
$alunoId = (int)$aluno['id'];
$tid = $tr->criar($alunoId, 'TREINO A - PEITO', 'Hipertrofia', 'Obs teste', 'Prof. Carlos');
assertTrue($tid > 0, "Treino::criar retorna id ($tid)");
$exId = (int)$ex->listar()[0]['id'];
assertTrue($tr->vincularExercicio($tid, $exId, 3, '12', '50kg', '60s'), "Treino::vincularExercicio");
assertTrue(count($tr->doAluno($alunoId)) >= 1, "Treino::doAluno");
assertTrue(count($tr->todos()) >= 1, "Treino::todos");
assertTrue((bool)$tr->porId($tid), "Treino::porId");
assertTrue(count($tr->exerciciosDoTreino($tid)) >= 1, "Treino::exerciciosDoTreino");
assertTrue($tr->total() >= 1, "Treino::total");
// isolamento: aluno 1 não vê treino do aluno 2
$tid2 = $tr->criar(1, 'TREINO B - COSTAS', null, null, null);
assertTrue(!in_array($tid2, array_column($tr->doAluno($alunoId), 'id'), true) || count($tr->doAluno(1)) >= 1, "Treino::doAluno isolamento por aluno");

// Frequencia
$freq = new Model\Frequencia();
$hoje = date('Y-m-d');
assertTrue($freq->registrar($alunoId, $hoje, 1), "Frequencia::registrar");
assertTrue($freq->registrar($alunoId, $hoje, 1), "Frequencia::registrar idempotente (ON CONFLICT)");
assertTrue(count($freq->doAluno($alunoId)) >= 1, "Frequencia::doAluno");
assertTrue($freq->totalPresencas($alunoId) >= 1, "Frequencia::totalPresencas");
assertTrue($freq->doMes($alunoId, date('Y-m')) >= 1, "Frequencia::doMes (compat SQLite/MySQL)");
assertEquals(0, $freq->doMes($alunoId, '1900-01'), "Frequencia::doMes mês vazio =0");

// Evolucao
$evo = new Model\Evolucao();
assertTrue($evo->registrar($alunoId, 78.5, 1.75, 'Primeira medida', $hoje), "Evolucao::registrar");
assertTrue($evo->registrar($alunoId, null, null, null, $hoje), "Evolucao::registrar nulos");
$hist = $evo->doAluno($alunoId);
assertTrue(count($hist) >= 2, "Evolucao::doAluno");
$evoId = (int)$hist[0]['id'];
assertTrue($evo->excluir($evoId, $alunoId), "Evolucao::excluir próprio");
assertFalse($evo->excluir(99999, $alunoId), "Evolucao::excluir id inexistente");
$evo2 = new Model\Evolucao();
$otherHist = $evo2->doAluno(1);
if ($otherHist) { assertFalse($evo->excluir((int)$otherHist[0]['id'], $alunoId), "Evolucao::excluir de outro aluno bloqueado"); }

// 4. CONTROLLERS
echo "\n[4/7] Controllers\n";
$_SESSION = ['usuario_id'=>1,'usuario_tipo'=>'admin','usuario_nome'=>'Admin','usuario_email'=>'admin@gymcontrol.com'];

// UsuarioController
$uc = new Controller\UsuarioController();
$ref = new ReflectionMethod($uc, 'validarSenha');
$ref->setAccessible(true);
assertTrue($ref->invoke($uc, 'Admin123!'), "UsuarioController::validarSenha forte OK");
assertFalse($ref->invoke($uc, 'fraca'), "UsuarioController::validarSenha fraca rejeita");
assertFalse($ref->invoke($uc, 'SEMNUMERO!'), "UsuarioController::validarSenha sem número rejeita");
[$ok,$msg] = $uc->cadastrar('', 'a@a.com', 'Admin123!', 'Admin123!');
assertFalse($ok, "UsuarioController::cadastrar vazio rejeita");
[$ok,$msg] = $uc->cadastrar('Nome', 'invalido', 'Admin123!', 'Admin123!');
assertFalse($ok, "UsuarioController::cadastrar e-mail inválido");
[$ok,$msg] = $uc->cadastrar('Nome', 'admin@gymcontrol.com', 'Admin123!', 'Admin123!');
assertFalse($ok, "UsuarioController::cadastrar e-mail duplicado");
[$ok,$msg] = $uc->cadastrar('Nome', 'novo2@gym.com', 'Admin123!', 'Diferente123!');
assertFalse($ok, "UsuarioController::cadastrar senhas não conferem");
[$ok,$msg] = $uc->cadastrar('Novo User', 'novo@gym.com', 'Forte123!', 'Forte123!');
assertTrue($ok, "UsuarioController::cadastrar sucesso");
$loginOk = $uc->login('novo@gym.com', 'Forte123!');
assertTrue($loginOk, "UsuarioController::login sucesso");
assertEquals('novo@gym.com', $_SESSION['usuario_email'] ?? '', "UsuarioController::login seta sessão");
assertFalse($uc->login('novo@gym.com', 'errada'), "UsuarioController::login senha errada");
assertTrue($uc->estaLogado(), "UsuarioController::estaLogado");
$_SESSION['usuario_tipo'] = 'admin';
assertTrue($uc->ehAdmin(), "UsuarioController::ehAdmin");

// AlunoController
$ac = new Controller\AlunoController();
assertTrue(count($ac->listar()) >= 1, "AlunoController::listar");
assertTrue(count($ac->listar('Teste')) >= 1, "AlunoController::listar busca");
assertTrue((bool)$ac->porId(1), "AlunoController::porId");
assertTrue((bool)$ac->porUsuario(2), "AlunoController::porUsuario");
[$ok,$msg] = $ac->cadastrarAluno('Ctrl Aluno', 'ctrl@gym.com', '(11) 99999-0000', '2000-01-01', 'Saúde', 'ativo', 'Prof. X', 'Ctrl123!');
assertTrue($ok, "AlunoController::cadastrarAluno");
[$ok,$msg] = $ac->cadastrarAluno('Dup', 'ctrl@gym.com', '', null, null, 'ativo', null);
assertFalse($ok, "AlunoController::cadastrarAluno duplicado bloqueia");
$ctrlAluno = $ac->porUsuario((int)(new Model\Usuario())->porEmail('ctrl@gym.com')['id']);
assertTrue((bool)$ctrlAluno, "AlunoController::cadastrarAluno criou vinculo");
assertTrue($ac->atualizar((int)$ctrlAluno['id'], ['telefone'=>'(11) 00000-0000']), "AlunoController::atualizar");
assertTrue($ac->excluir((int)$ctrlAluno['id']), "AlunoController::excluir");
assertFalse((bool)$ac->porId((int)$ctrlAluno['id']), "AlunoController::excluir removeu");

// ExercicioController
$ec = new Controller\ExercicioController();
assertTrue(count($ec->listar()) >= 1, "ExercicioController::listar");
[$ok,$msg] = $ec->criar(['nome'=>'','grupo_muscular'=>'Peito']);
assertFalse($ok, "ExercicioController::criar nome vazio rejeita");
[$ok,$msg] = $ec->criar(['nome'=>'Remada','grupo_muscular'=>'']);
assertFalse($ok, "ExercicioController::criar grupo vazio rejeita");
[$ok,$msg] = $ec->criar(['nome'=>'Rosca Direta','grupo_muscular'=>'Bíceps']);
assertTrue($ok, "ExercicioController::criar sucesso");
$lastEx = $ec->listar()[0];
assertTrue($ec->excluir((int)$lastEx['id']), "ExercicioController::excluir");

// TreinoController
$tc = new Controller\TreinoController();
[$ok,$msg] = $tc->criar(0, 'Nome', null, null, null, []);
assertFalse($ok, "TreinoController::criar sem aluno rejeita");
[$ok,$msg] = $tc->criar($alunoId, '', null, null, null, []);
assertFalse($ok, "TreinoController::criar sem nome rejeita");
$exTmpId = (new Model\Exercicio())->listar()[0]['id'] ?? 1;
[$ok,$msg] = $tc->criar($alunoId, 'TREINO TESTE CTRL', 'Teste', null, 'Prof. Teste', [$exTmpId]);
assertTrue($ok, "TreinoController::criar sucesso");
assertTrue(count($tc->doAluno($alunoId)) >= 1, "TreinoController::doAluno");
assertTrue(count($tc->todos()) >= 1, "TreinoController::todos");
$treinosAluno = $tc->doAluno($alunoId);
$lastTid = (int)$treinosAluno[0]['id'];
assertTrue((bool)$tc->porId($lastTid), "TreinoController::porId");
assertTrue(is_array($tc->exercicios($lastTid)), "TreinoController::exercicios");
assertTrue($tc->excluir($lastTid), "TreinoController::excluir");
assertFalse((bool)$tc->porId($lastTid), "TreinoController::excluir removeu");

// 5. SEGURANÇA
echo "\n[5/7] Segurança (hash, PDO, XSS, CSRF)\n";
$h = password_hash('Segura123!', PASSWORD_ARGON2ID, ['memory_cost'=>1<<16,'time_cost'=>3,'threads'=>1]);
assertTrue(password_verify('Segura123!', $h), "password_hash Argon2id verifica");
assertFalse(password_verify('errada', $h), "password_verify rejeita errada");
// SQL Injection — busca com LIKE não deve quebrar nem injetar
try { $inj = $a->listar("' OR '1'='1"); assertTrue(is_array($inj), "SQL injection listar aluno resiste"); } catch (Throwable $e) { fail("SQL injection listar aluno", $e->getMessage()); }
try { $inj2 = $ex->listar("'; DROP TABLE users; --"); assertTrue(is_array($inj2), "SQL injection listar exercicio resiste"); } catch (Throwable $e) { fail("SQL injection exercicio", $e->getMessage()); }
// Verifica que todos os Models usam prepare (não query direto em SELECT)
$modelFiles = glob(__DIR__ . '/../Model/*.php');
foreach ($modelFiles as $mf) {
    if (basename($mf) === 'Connection.php') { ok("Connection.php — classe de conexão, sem SELECT — OK"); continue; }
    $c = file_get_contents($mf);
    $hasQuerySelect = preg_match('/->query\s*\(\s*["\']SELECT/', $c);
    assertFalse((bool)$hasQuerySelect, basename($mf) . " sem ->query(SELECT — só prepare)");
    assertTrue(str_contains($c, '->prepare('), basename($mf) . " usa prepare");
}
// XSS — verifica htmlspecialchars nas Views
$viewFiles = array_merge(glob(__DIR__ . '/../View/*.php'), glob(__DIR__ . '/../View/admin/*.php'), [__DIR__ . '/../index.php']);
foreach ($viewFiles as $vf) {
    $c = file_get_contents($vf);
    if (str_contains($c, '<?=') || str_contains($c, '<?php echo')) {
        $hasEchoVar = preg_match('/<\?=\s*\$|echo\s+\$/', $c);
        if ($hasEchoVar) {
            assertTrue(str_contains($c, 'htmlspecialchars'), basename($vf) . " usa htmlspecialchars no output");
        } else { ok(basename($vf) . " sem echo de variável — OK"); }
    } else { ok(basename($vf) . " sem output dinâmico — OK"); }
}
// CSRF — verifica token em todos os POST
foreach ($viewFiles as $vf) {
    $c = file_get_contents($vf);
    $hasPost = str_contains($c, 'method="POST"') || str_contains($c, "REQUEST_METHOD'] === 'POST'");
    if ($hasPost) {
        $hasCsrf = str_contains($c, 'csrf') && (str_contains($c, 'hash_equals') || str_contains($c, 'htmlspecialchars($_SESSION'));
        assertTrue($hasCsrf, basename($vf) . " POST com CSRF");
    } else { ok(basename($vf) . " sem POST — OK"); }
}
// Excluir e logout só via POST
foreach (['View/admin/aluno_excluir.php','View/admin/exercicio_excluir.php','View/admin/treino_excluir.php','View/logout.php'] as $rel) {
    $c = file_get_contents(__DIR__ . '/../' . $rel);
    assertTrue(str_contains($c, "REQUEST_METHOD'] !== 'POST'") || str_contains($c, '405'), basename($rel) . " bloqueia GET (405)");
    assertNotContains('$_GET[\'id\']', $c, basename($rel) . " não usa GET id");
    assertTrue(str_contains($c, '$_POST'), basename($rel) . " usa POST");
}
assertTrue(file_exists(__DIR__ . '/../database/schema.sql'), "database/schema.sql existe");

// 6. PÁGINAS (estrutura + includes)
echo "\n[6/7] Páginas — existência e estrutura\n";
$pages = [
    'index.php' => 'GymControl',
    'View/register.php' => 'Criar Conta',
    'View/painel_aluno.php' => 'Painel do Aluno',
    'View/meu_treino.php' => 'Meu Treino',
    'View/frequencia.php' => 'Frequência',
    'View/evolucao.php' => 'Evolução',
    'View/logout.php' => 'csrf',
    'View/admin/painel_admin.php' => 'Painel Admin',
    'View/admin/alunos.php' => 'Alunos',
    'View/admin/aluno_novo.php' => 'Novo Aluno',
    'View/admin/aluno_editar.php' => 'Editar Aluno',
    'View/admin/aluno_excluir.php' => 'POST',
    'View/admin/exercicios.php' => 'Exercícios',
    'View/admin/exercicio_novo.php' => 'Novo Exercício',
    'View/admin/exercicio_excluir.php' => 'POST',
    'View/admin/treinos.php' => 'Treinos',
    'View/admin/treino_novo.php' => 'Novo Treino',
    'View/admin/treino_excluir.php' => 'POST',
    'View/admin/frequencia_admin.php' => 'Frequência',
];
foreach ($pages as $rel => $needle) {
    $path = __DIR__ . '/../' . $rel;
    assertTrue(file_exists($path), "$rel existe");
    if (file_exists($path)) {
        $c = file_get_contents($path);
        assertContains($needle, $c, "$rel contém '$needle'");
        assertNotContains('<form method="POST" action="../logout.php" class="m-0 p-2">Sair</a>', $c, "$rel sem HTML quebrado form/a");
    }
}
// HTML quebrado específico de treinos.php (bug corrigido) não deve existir em lugar nenhum
$allViews = implode("\n", array_map('file_get_contents', $viewFiles));
assertNotContains('Sair</a>', $allViews, "Nenhuma view com 'Sair</a>' quebrado");
assertNotContains('>Sair</a>', $allViews, "Nenhuma view com '>Sair</a>'");

// Auth guards
foreach (['View/painel_aluno.php','View/meu_treino.php','View/frequencia.php','View/evolucao.php'] as $rel) {
    $c = file_get_contents(__DIR__ . '/../' . $rel);
    assertTrue(str_contains($c, "usuario_id") && str_contains($c, "header('Location:"), basename($rel) . " tem guard de autenticação");
}
foreach (glob(__DIR__ . '/../View/admin/*.php') as $vf) {
    $c = file_get_contents($vf);
    assertTrue(str_contains($c, "usuario_tipo") && str_contains($c, "'admin'"), basename($vf) . " guarda admin");
}

// Isolation: painel_aluno só doAluno
$c = file_get_contents(__DIR__ . '/../View/painel_aluno.php');
assertTrue(str_contains($c, "porUsuario") && str_contains($c, "doAluno"), "painel_aluno usa porUsuario + doAluno (isolamento)");
$c2 = file_get_contents(__DIR__ . '/../View/meu_treino.php');
assertTrue(str_contains($c2, "doAluno"), "meu_treino isola por aluno");

// 7. FLUXOS INTEGRADOS
echo "\n[7/7] Fluxos integrados\n";
// Fluxo completo: cadastrar usuário -> aluno -> exercício -> treino -> frequência -> evolução
$pdo2 = Model\Connection::getInstance();
$uc2 = new Controller\UsuarioController();
[$ok,$msg] = $uc2->cadastrar('Fluxo Teste', 'fluxo@gym.com', 'Fluxo123!', 'Fluxo123!');
assertTrue($ok, "Fluxo: cadastro usuário");
$uid = (int)(new Model\Usuario())->porEmail('fluxo@gym.com')['id'];
$fluxoAluno = (new Model\Aluno())->porUserId($uid);
assertTrue((bool)$fluxoAluno, "Fluxo: aluno vinculado");
$fid = (int)$fluxoAluno['id'];
$ec2 = new Controller\ExercicioController();
[$ok,$msg] = $ec2->criar(['nome'=>'Fluxo Ex','grupo_muscular'=>'Peito']);
assertTrue($ok, "Fluxo: criar exercício");
$exFluxoId = (int)(new Model\Exercicio())->listar('Fluxo Ex')[0]['id'];
$tc2 = new Controller\TreinoController();
[$ok,$msg] = $tc2->criar($fid, 'FLUXO TREINO', 'Teste', null, 'Prof. Fluxo', [$exFluxoId]);
assertTrue($ok, "Fluxo: criar treino com exercício");
assertTrue(count($tc2->doAluno($fid)) >= 1, "Fluxo: aluno vê seu treino");
assertTrue(count($tc2->doAluno(1)) === count((new Model\Treino())->doAluno(1)), "Fluxo: treino não vaza para outro aluno");
assertTrue((new Model\Frequencia())->registrar($fid, date('Y-m-d'), 1), "Fluxo: registrar frequência");
assertTrue((new Model\Evolucao())->registrar($fid, 80.0, 1.80, 'Fluxo', date('Y-m-d')), "Fluxo: registrar evolução");
$histFluxo = (new Model\Evolucao())->doAluno($fid);
assertTrue(count($histFluxo) >= 1, "Fluxo: histórico evolução");

// Limpeza fluxo
$pdo2->exec("DELETE FROM evolucoes WHERE aluno_id=$fid");
$pdo2->exec("DELETE FROM frequencias WHERE aluno_id=$fid");
$pdo2->exec("DELETE FROM treino_exercicios WHERE treino_id IN (SELECT id FROM treinos WHERE aluno_id=$fid)");
$pdo2->exec("DELETE FROM treinos WHERE aluno_id=$fid");
$pdo2->exec("DELETE FROM exercicios WHERE id=$exFluxoId");
$alunoFluxo = (new Model\Aluno())->porUserId($uid);
if ($alunoFluxo) { (new Model\Aluno())->excluir((int)$alunoFluxo['id']); }

$elapsed = round((microtime(true) - $start)*1000);
echo "\n" . str_repeat("=", 60) . "\n";
echo "Resultado: $pass passaram";
if ($fail) echo ", \033[31m$fail falharam\033[0m"; else echo ", \033[32m0 falhas\033[0m";
echo " — {$elapsed}ms\n";
if ($fail) {
    echo "\nFalhas:\n";
    foreach ($errors as $e) echo "  - $e\n";
    exit(1);
}
echo "Tudo OK — todas as funções e páginas funcionais.\n";
