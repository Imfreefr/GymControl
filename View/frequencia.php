<?php
require_once '../vendor/autoload.php';
use Controller\AlunoController; use Model\Frequencia;
exigirLogin();
if(($_SESSION['usuario_tipo']??'')==='admin'){ header('Location: admin/painel_admin.php'); exit; }
$alunoCtrl=new AlunoController(); $freq=new Frequencia();
$aluno=$alunoCtrl->porUsuario((int)$_SESSION['usuario_id']);
if(!$aluno) die('Aluno não encontrado.');
$historico=$freq->doAluno((int)$aluno['id']);
$total=$freq->totalPresencas((int)$aluno['id']);
$mes=$freq->doMes((int)$aluno['id'], date('Y-m'));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../templates/css/global.css">
<link rel="shortcut icon" href="../templates/assets/img/favicon.svg">
<title>GymControl | Frequência</title>
</head>
<body style="background:#f5f5f7">
<header class="navbar-gym text-white"><nav class="container d-flex justify-content-between align-items-center py-3"><a href="painel_aluno.php" class="text-white text-decoration-none d-flex align-items-center gap-2"><span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width:38px;height:38px"><i class="bi bi-lightning-charge-fill"></i></span><strong>GymControl</strong></a><div class="d-flex gap-2"><a href="painel_aluno.php" class="btn btn-sm btn-outline-light">Painel</a><a href="logout.php" class="btn btn-sm bgLinearGradient text-white">Sair</a></div></nav></header>
<main class="container py-4">
<h3 class="fw-bold">Minha Frequência</h3>
<div class="row g-3 mb-3">
<div class="col-6 col-md-4"><div class="stat-card p-3 text-center"><small class="text-muted">Total</small><h3 class="fw-bold" style="color:#ea580c"><?= $total ?></h3><small class="text-muted">presenças</small></div></div>
<div class="col-6 col-md-4"><div class="stat-card p-3 text-center"><small class="text-muted">No mês (<?= date('m/Y') ?>)</small><h3 class="fw-bold" style="color:#ea580c"><?= $mes ?></h3><small class="text-muted">presenças</small></div></div>
<div class="col-12 col-md-4"><div class="stat-card p-3 text-center"><small class="text-muted">Status</small><h5><span class="badge bg-success"><?= e($aluno['status']) ?></span></h5><small class="text-muted"><?= e($aluno['objetivo']??'—') ?></small></div></div>
</div>
<div class="card card-gym border-0">
<div class="card-body">
<h5 class="fw-bold"><i class="bi bi-calendar3"></i> Histórico</h5>
<div class="table-responsive">
<table class="table table-sm">
<thead><tr><th>Data</th><th>Presença</th></tr></thead>
<tbody>
<?php if(empty($historico)): ?><tr><td colspan="2" class="text-muted small">Nenhum registro.</td></tr>
<?php else: foreach($historico as $f): ?><tr><td><?= e($f['data']) ?></td><td><span class="badge bg-success">Presente</span></td></tr><?php endforeach; endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
