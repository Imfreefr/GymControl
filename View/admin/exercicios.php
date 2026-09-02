<?php
require_once '../../vendor/autoload.php';
exigirAdmin();
use Controller\ExercicioController;
$ctrl=new ExercicioController();
$busca=trim($_GET['busca']??'');
$lista=$ctrl->listar($busca ?: null);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../../templates/css/global.css">
<link rel="shortcut icon" href="../../templates/assets/img/favicon.svg">
<title>GymControl | Exercícios</title>
</head>
<body style="background:#f5f5f7">
<div class="d-flex">
<aside class="sidebar p-3" style="width:260px"><div class="d-flex align-items-center gap-2 mb-4"><span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width:38px;height:38px"><i class="bi bi-lightning-charge-fill"></i></span><strong>GymControl</strong></div><nav class="d-flex flex-column gap-1"><a href="painel_admin.php" class="p-2 text-decoration-none"><i class="bi bi-speedometer2"></i> Dashboard</a><a href="alunos.php" class="p-2 text-decoration-none"><i class="bi bi-people"></i> Alunos</a><a href="exercicios.php" class="active p-2 text-decoration-none"><i class="bi bi-activity"></i> Exercícios</a><a href="treinos.php" class="p-2 text-decoration-none"><i class="bi bi-clipboard-check"></i> Treinos</a><a href="../logout.php" class="p-2 text-decoration-none text-danger"><i class="bi bi-box-arrow-right"></i> Sair</a></nav></aside>
<main class="flex-grow-1 p-4">
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
<h3 class="fw-bold mb-0">Exercícios</h3>
<a href="exercicio_novo.php" class="btn btn-gym btn-sm"><i class="bi bi-plus-lg"></i> Novo Exercício</a>
</div>
<form method="GET" class="mb-3"><div class="input-group" style="max-width:360px"><input type="text" name="busca" value="<?= e($busca) ?>" class="form-control" placeholder="Buscar por nome (GET)"><button class="btn btn-dark">Buscar</button></div></form>
<div class="card card-gym border-0"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead class="table-light"><tr><th>Nome</th><th>Grupo</th><th>Séries</th><th>Reps</th><th>Carga</th><th>Descanso</th><th></th></tr></thead>
<tbody>
<?php if(empty($lista)): ?><tr><td colspan="7" class="text-center small text-muted py-3">Nenhum exercício.</td></tr>
<?php else: foreach($lista as $ex): ?>
<tr><td class="fw-semibold"><?= e($ex['nome']) ?></td><td><span class="badge bg-secondary"><?= e($ex['grupo_muscular']) ?></span></td><td><?= e($ex['series']) ?>×</td><td><?= e($ex['repeticoes']) ?></td><td><?= e($ex['carga']) ?></td><td><?= e($ex['descanso']) ?></td><td><a href="exercicio_excluir.php?id=<?= (int)$ex['id'] ?>&csrf=<?= e(csrf_token()) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir?')">Excluir</a></td></tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div></div></div>
<p class="small text-muted mt-2">GET → Controller → Model → PDO (prepared) → MySQL/SQLite → View. Busca por nome com LIKE.</p>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
