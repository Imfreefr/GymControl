<?php
require_once '../vendor/autoload.php';
use Controller\AlunoController; use Model\Frequencia; use Model\Evolucao; use Model\Treino;
exigirLogin();
if(($_SESSION['usuario_tipo']??'')==='admin'){ header('Location: admin/painel_admin.php'); exit; }
$alunoCtrl=new AlunoController();
$aluno=$alunoCtrl->porUsuario((int)$_SESSION['usuario_id']);
$freq=new Frequencia(); $evo=new Evolucao(); $treinoM=new Treino();
$totalPres=0; $freqMes=0; $evolucoes=[]; $meusTreinos=[];
if($aluno){
  $totalPres=$freq->totalPresencas((int)$aluno['id']);
  $freqMes=$freq->doMes((int)$aluno['id'], date('Y-m'));
  $evolucoes=$evo->doAluno((int)$aluno['id']);
  $meusTreinos=$treinoM->doAluno((int)$aluno['id']);
}
$proximo=$meusTreinos[0]??null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../templates/css/global.css">
<link rel="shortcut icon" href="../templates/assets/img/favicon.svg">
<title>GymControl | Painel do Aluno</title>
</head>
<body style="background:#f5f5f7">
<header class="navbar-gym text-white">
<nav class="container d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
<div class="d-flex align-items-center gap-3">
<span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width:42px;height:42px"><i class="bi bi-lightning-charge-fill"></i></span>
<strong>GymControl</strong>
</div>
<div class="d-flex align-items-center gap-2">
<span class="small opacity-75"><?= e($_SESSION['usuario_nome']) ?></span>
<a href="meu_treino.php" class="btn btn-sm btn-outline-light">Meu Treino</a>
<a href="frequencia.php" class="btn btn-sm btn-outline-light">Frequência</a>
<a href="evolucao.php" class="btn btn-sm btn-outline-light">Evolução</a>
<a href="logout.php" class="btn btn-sm bgLinearGradient text-white">Sair</a>
</div>
</nav>
</header>
<main class="container py-4">
<h2 class="fw-bold">Olá, <?= e($_SESSION['usuario_nome']) ?>! 👋</h2>
<p class="text-muted">Bem-vindo ao seu painel. Acompanhe seu treino e evolução.</p>

<div class="row g-3 mb-4">
<div class="col-6 col-lg-3"><div class="stat-card p-3"><small class="text-muted">Meu Treino</small><h4 class="fw-bold mb-1"><?= count($meusTreinos) ?> treino(s)</h4><a href="meu_treino.php" class="small" style="color:#ea580c">Ver treino →</a></div></div>
<div class="col-6 col-lg-3"><div class="stat-card p-3"><small class="text-muted">Frequência</small><h4 class="fw-bold mb-1"><?= $totalPres ?> presenças</h4><span class="small text-muted"><?= $freqMes ?> no mês</span></div></div>
<div class="col-6 col-lg-3"><div class="stat-card p-3"><small class="text-muted">Evolução</small><h4 class="fw-bold mb-1"><?= count($evolucoes) ?> registros</h4><a href="evolucao.php" class="small" style="color:#ea580c">Ver histórico →</a></div></div>
<div class="col-6 col-lg-3"><div class="stat-card p-3"><small class="text-muted">Status</small><h4 class="fw-bold mb-1"><span class="badge bg-success"><?= e($aluno['status']??'ativo') ?></span></h4><span class="small text-muted"><?= e($aluno['objetivo']??'—') ?></span></div></div>
</div>

<div class="row g-3">
<div class="col-lg-8">
<div class="card card-gym border-0">
<div class="card-body">
<h5 class="fw-bold"><i class="bi bi-person-badge"></i> Meus Dados</h5>
<div class="row small">
<div class="col-6"><p class="mb-1"><strong>E-mail:</strong> <?= e($_SESSION['usuario_email']) ?></p><p class="mb-1"><strong>Telefone:</strong> <?= e($aluno['telefone']??'—') ?></p></div>
<div class="col-6"><p class="mb-1"><strong>Objetivo:</strong> <?= e($aluno['objetivo']??'—') ?></p><p class="mb-1"><strong>Professor:</strong> <?= e($aluno['professor']??'—') ?></p></div>
</div>
</div>
</div>
<?php if($proximo): ?>
<div class="card card-gym border-0 mt-3">
<div class="card-body">
<h5 class="fw-bold">Próximo Treino</h5>
<p class="mb-1 fw-semibold"><?= e($proximo['nome']) ?> <small class="text-muted">— <?= e($proximo['objetivo']??'') ?></small></p>
<p class="small text-muted mb-0">Criado em <?= e($proximo['created_at']) ?></p>
<a href="meu_treino.php" class="btn btn-sm btn-gym mt-2">Ver detalhes</a>
</div>
</div>
<?php endif; ?>
</div>
<div class="col-lg-4">
<div class="card card-gym border-0">
<div class="card-body">
<h5 class="fw-bold"><i class="bi bi-calendar-check"></i> Frequência do mês</h5>
<h2 class="fw-bold" style="color:#ea580c"><?= $freqMes ?></h2>
<p class="small text-muted">presenças em <?= date('m/Y') ?></p>
<a href="frequencia.php" class="btn btn-sm btn-outline-dark w-100">Ver histórico</a>
</div>
</div>
<?php if(!empty($evolucoes)): $ult=$evolucoes[0]; ?>
<div class="card card-gym border-0 mt-3">
<div class="card-body">
<h5 class="fw-bold"><i class="bi bi-graph-up"></i> Última evolução</h5>
<p class="mb-1"><strong><?= e($ult['peso']) ?> kg</strong> • <?= e($ult['altura']) ?> m</p>
<p class="small text-muted mb-0"><?= e($ult['observacao']??'') ?> — <?= e($ult['data']) ?></p>
<canvas id="grafPeso" height="120" class="mt-2"></canvas>
</div>
</div>
<?php endif; ?>
</div>
</div>
</main>
<?php if(!empty($evolucoes)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels=<?= json_encode(array_reverse(array_column($evolucoes,'data'))) ?>;
const pesos=<?= json_encode(array_reverse(array_column($evolucoes,'peso'))) ?>;
new Chart(document.getElementById('grafPeso'),{type:'line',data:{labels,datasets:[{data:pesos,borderColor:'#ea580c',backgroundColor:'rgba(234,88,12,.12)',tension:.3,fill:true}]},options:{plugins:{legend:{display:false}},scales:{y:{beginAtZero:false}}}});
</script>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
