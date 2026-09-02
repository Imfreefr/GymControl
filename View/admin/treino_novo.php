<?php
require_once '../../vendor/autoload.php';
exigirAdmin();
use Controller\TreinoController; use Controller\AlunoController; use Model\Exercicio;
$msg='';
$alunos=(new AlunoController())->listar();
$exs=(new Exercicio())->listar();
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_validar($_POST['csrf']??null)) $msg='Token inválido.';
    else{
        $ctrl=new TreinoController();
        [$ok,$ret]=$ctrl->criar((int)($_POST['aluno_id']??0), trim($_POST['nome']??''), trim($_POST['objetivo']??''), trim($_POST['observacoes']??''), trim($_POST['professor']??''), $_POST['exercicios']??[]);
        $msg=$ret;
        if($ok){ header('Location: treinos.php'); exit; }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../templates/css/global.css">
<title>GymControl | Novo Treino</title>
</head>
<body style="background:#f5f5f7">
<div class="d-flex"><aside class="sidebar p-3" style="width:260px"><strong>GymControl</strong></aside>
<main class="flex-grow-1 p-4" style="max-width:780px">
<h3 class="fw-bold">Novo Treino</h3>
<p class="small text-muted">Aluno → Criar Treino → Selecionar Exercícios → Salvar → Aluno visualiza</p>
<?php if($msg): ?><div class="alert alert-warning py-2 small"><?= e($msg) ?></div><?php endif; ?>
<form method="POST" class="card card-gym border-0 p-4">
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Aluno *</label><select name="aluno_id" class="form-select" required><option value="">Selecione</option><?php foreach($alunos as $a): ?><option value="<?= (int)$a['id'] ?>"><?= e($a['nome']) ?> (<?= e($a['email']) ?>)</option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label">Nome *</label><input type="text" name="nome" class="form-control" placeholder="TREINO A - PEITO E TRÍCEPS" required></div>
<div class="col-md-6"><label class="form-label">Objetivo</label><input type="text" name="objetivo" class="form-control" placeholder="Hipertrofia"></div>
<div class="col-md-6"><label class="form-label">Professor</label><input type="text" name="professor" class="form-control" placeholder="Prof. Carlos"></div>
<div class="col-12"><label class="form-label">Observações</label><input type="text" name="observacoes" class="form-control"></div>
<div class="col-12">
<label class="form-label">Exercícios (selecione)</label>
<div class="border rounded-3 p-3" style="max-height:220px;overflow:auto">
<?php if(empty($exs)): ?><p class="small text-muted">Cadastre exercícios primeiro.</p>
<?php else: foreach($exs as $ex): ?>
<label class="d-flex align-items-center gap-2 small mb-1"><input type="checkbox" name="exercicios[]" value="<?= (int)$ex['id'] ?>"> <?= e($ex['nome']) ?> <span class="badge bg-secondary"><?= e($ex['grupo_muscular']) ?></span> <?= e($ex['series']) ?>×<?= e($ex['repeticoes']) ?></label>
<?php endforeach; endif; ?>
</div>
</div>
</div>
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<div class="d-flex gap-2 mt-3"><button class="btn btn-gym">Salvar</button><a href="treinos.php" class="btn btn-outline-dark">Voltar</a></div>
</form>
</main></div>
</body>
</html>
