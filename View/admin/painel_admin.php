<?php
require_once '../../vendor/autoload.php';
exigirAdmin();
use Model\Aluno; use Model\Exercicio; use Model\Treino; use Model\Connection;
$alunoM=new Aluno(); $exM=new Exercicio(); $trM=new Treino();
$totalAlunos=$alunoM->total();
$totalEx=$exM->total();
$totalTr=$trM->total();
$pdo=Connection::getInstance();
$presRecentes=$pdo->query("SELECT f.data, u.nome FROM frequencias f JOIN alunos a ON a.id=f.aluno_id JOIN users u ON u.id=a.user_id ORDER BY f.data DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../../templates/css/global.css">
<link rel="shortcut icon" href="../../templates/assets/img/favicon.svg">
<title>GymControl | Painel Admin</title>
</head>
<body style="background:#f5f5f7">
<div class="d-flex">
<aside class="sidebar p-3" style="width:260px">
<div class="d-flex align-items-center gap-2 mb-4"><span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width:38px;height:38px"><i class="bi bi-lightning-charge-fill"></i></span><strong>GymControl</strong></div>
<nav class="d-flex flex-column gap-1">
<a href="painel_admin.php" class="active p-2 text-decoration-none"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="alunos.php" class="p-2 text-decoration-none"><i class="bi bi-people"></i> Alunos</a>
<a href="exercicios.php" class="p-2 text-decoration-none"><i class="bi bi-activity"></i> Exercícios</a>
<a href="treinos.php" class="p-2 text-decoration-none"><i class="bi bi-clipboard-check"></i> Treinos</a>
<a href="frequencia_admin.php" class="p-2 text-decoration-none"><i class="bi bi-calendar-check"></i> Frequência</a>
<a href="../logout.php" class="p-2 text-decoration-none text-danger"><i class="bi bi-box-arrow-right"></i> Sair</a>
</nav>
<div class="mt-4 small opacity-75"><?= e($_SESSION['usuario_nome']) ?><br><?= e($_SESSION['usuario_email']) ?></div>
</aside>
<main class="flex-grow-1 p-4">
<h3 class="fw-bold">Painel Administrativo</h3>
<p class="text-muted small">Números vêm do banco (PDO + prepared statements).</p>
<div class="row g-3 mb-4">
<div class="col-md-4"><div class="stat-card p-3"><small class="text-muted">Total de Alunos</small><h2 class="fw-bold" style="color:#ea580c"><?= $totalAlunos ?></h2></div></div>
<div class="col-md-4"><div class="stat-card p-3"><small class="text-muted">Total de Exercícios</small><h2 class="fw-bold" style="color:#ea580c"><?= $totalEx ?></h2></div></div>
<div class="col-md-4"><div class="stat-card p-3"><small class="text-muted">Total de Treinos</small><h2 class="fw-bold" style="color:#ea580c"><?= $totalTr ?></h2></div></div>
</div>
<div class="d-flex flex-wrap gap-2 mb-4">
<a href="aluno_novo.php" class="btn btn-gym btn-sm"><i class="bi bi-person-plus"></i> Novo Aluno</a>
<a href="exercicio_novo.php" class="btn btn-dark btn-sm"><i class="bi bi-plus-lg"></i> Novo Exercício</a>
<a href="treino_novo.php" class="btn btn-outline-dark btn-sm"><i class="bi bi-clipboard-plus"></i> Novo Treino</a>
<a href="alunos.php" class="btn btn-outline-dark btn-sm">Lista de Alunos</a>
<a href="exercicios.php" class="btn btn-outline-dark btn-sm">Lista de Exercícios</a>
</div>
<div class="row g-3">
<div class="col-lg-6"><div class="card card-gym border-0"><div class="card-body"><h5 class="fw-bold">Presenças recentes</h5>
<?php if(empty($presRecentes)): ?><p class="small text-muted">Nenhuma presença registrada.</p>
<?php else: ?><ul class="list-unstyled small mb-0"><?php foreach($presRecentes as $p): ?><li class="d-flex justify-content-between border-bottom py-1"><span><?= e($p['nome']) ?></span><span class="text-muted"><?= e($p['data']) ?></span></li><?php endforeach; ?></ul><?php endif; ?>
</div></div></div>
<div class="col-lg-6"><div class="card card-gym border-0"><div class="card-body"><h5 class="fw-bold">Atalhos</h5><p class="small text-muted">Cadastro via <b>POST</b> → Controller → Validação → Model → PDO → MySQL/SQLite → Redirect.<br>Listagem via <b>GET</b> → Controller → Model → PDO → View.</p></div></div></div>
</div>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
