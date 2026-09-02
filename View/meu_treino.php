<?php
require_once '../vendor/autoload.php';
use Controller\AlunoController; use Controller\TreinoController;
exigirLogin();
$alunoCtrl=new AlunoController(); $treinoCtrl=new TreinoController();
$aluno=$alunoCtrl->porUsuario((int)$_SESSION['usuario_id']);
if(!$aluno){ die('Aluno não encontrado.'); }
$meusTreinos=$treinoCtrl->doAluno((int)$aluno['id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../templates/css/global.css">
<link rel="shortcut icon" href="../templates/assets/img/favicon.svg">
<title>GymControl | Meu Treino</title>
</head>
<body style="background:#f5f5f7">
<header class="navbar-gym text-white">
<nav class="container d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
<div class="d-flex align-items-center gap-2"><a href="painel_aluno.php" class="text-white text-decoration-none d-flex align-items-center gap-2"><span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width:38px;height:38px"><i class="bi bi-lightning-charge-fill"></i></span><strong>GymControl</strong></a></div>
<div class="d-flex gap-2"><a href="painel_aluno.php" class="btn btn-sm btn-outline-light">Painel</a><a href="logout.php" class="btn btn-sm bgLinearGradient text-white">Sair</a></div>
</nav>
</header>
<main class="container py-4">
<h3 class="fw-bold">Meu Treino</h3>
<p class="text-muted small">Treinos vinculados ao seu perfil. Dados vêm do banco (GET → Controller → Model → PDO → MySQL/SQLite → View).</p>
<?php if(empty($meusTreinos)): ?>
<div class="alert alert-light border">Nenhum treino cadastrado ainda. Procure a recepção.</div>
<?php else: foreach($meusTreinos as $t): $exs=$treinoCtrl->exercicios((int)$t['id']); ?>
<div class="card card-gym border-0 mb-3">
<div class="card-body">
<div class="d-flex justify-content-between flex-wrap gap-2">
<div>
<h5 class="fw-bold mb-1"><?= e($t['nome']) ?></h5>
<p class="small text-muted mb-1"><i class="bi bi-bullseye"></i> Objetivo: <?= e($t['objetivo']??'—') ?> • <i class="bi bi-person"></i> Prof: <?= e($t['professor']??'—') ?> • <?= e($t['created_at']) ?></p>
<?php if(!empty($t['observacoes'])): ?><p class="small mb-2"><em><?= e($t['observacoes']) ?></em></p><?php endif; ?>
</div>
<span class="badge bg-dark align-self-start"><?= count($exs) ?> exercícios</span>
</div>
<?php if(empty($exs)): ?><p class="small text-muted">Sem exercícios vinculados.</p>
<?php else: ?>
<div class="table-responsive">
<table class="table table-sm align-middle mb-0">
<thead><tr class="small text-muted"><th>Exercício</th><th>Grupo</th><th>Séries</th><th>Reps</th><th>Carga</th><th>Descanso</th></tr></thead>
<tbody>
<?php foreach($exs as $ex): ?>
<tr><td class="fw-semibold"><?= e($ex['nome']) ?></td><td><span class="badge bg-secondary"><?= e($ex['grupo_muscular']) ?></span></td><td><?= e($ex['series']) ?>×</td><td><?= e($ex['repeticoes']) ?></td><td><?= e($ex['carga']) ?></td><td><?= e($ex['descanso']) ?></td></tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php endif; ?>
</div>
</div>
<?php endforeach; endif; ?>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
