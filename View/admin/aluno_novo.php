<?php
require_once '../../vendor/autoload.php';
exigirAdmin();
use Controller\AlunoController;
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_validar($_POST['csrf']??null)) $msg='Token inválido.';
    else{
        $ctrl=new AlunoController();
        [$ok,$ret]=$ctrl->cadastrarAluno(trim($_POST['nome']??''), trim($_POST['email']??''), trim($_POST['telefone']??''), $_POST['data_nascimento']?:null, trim($_POST['objetivo']??''), $_POST['status']??'ativo', trim($_POST['professor']??''), trim($_POST['senha']??'Aluno123!'));
        $msg=$ret;
        if($ok){ header('Location: alunos.php?msg='.urlencode($ret)); exit; }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../../templates/css/global.css">
<title>GymControl | Novo Aluno</title>
</head>
<body style="background:#f5f5f7">
<div class="d-flex">
<aside class="sidebar p-3" style="width:260px"><div class="d-flex align-items-center gap-2 mb-4"><span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width:38px;height:38px"><i class="bi bi-lightning-charge-fill"></i></span><strong>GymControl</strong></div><nav class="d-flex flex-column gap-1"><a href="painel_admin.php" class="p-2 text-decoration-none">Dashboard</a><a href="alunos.php" class="active p-2 text-decoration-none">Alunos</a></nav></aside>
<main class="flex-grow-1 p-4" style="max-width:720px">
<h3 class="fw-bold">Novo Aluno</h3>
<p class="small text-muted">POST → Controller → Validação → Model → PDO → MySQL/SQLite → Redirect</p>
<?php if($msg): ?><div class="alert alert-warning py-2 small"><?= e($msg) ?></div><?php endif; ?>
<form method="POST" class="card card-gym border-0 p-4">
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Nome *</label><input type="text" name="nome" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">E-mail *</label><input type="email" name="email" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Telefone</label><input type="text" name="telefone" class="form-control" placeholder="(11) 99999-0000"></div>
<div class="col-md-6"><label class="form-label">Data nascimento</label><input type="date" name="data_nascimento" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Objetivo</label><select name="objetivo" class="form-select"><option value="">Selecione</option><option>Hipertrofia</option><option>Emagrecimento</option><option>Condicionamento</option><option>Saúde</option></select></div>
<div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="ativo">Ativo</option><option value="inativo">Inativo</option></select></div>
<div class="col-md-3"><label class="form-label">Professor</label><input type="text" name="professor" class="form-control" placeholder="Prof. Carlos"></div>
<div class="col-md-6"><label class="form-label">Senha inicial</label><input type="text" name="senha" class="form-control" value="Aluno123!"><small class="text-muted">Padrão: maiúscula + minúscula + número, 8+ chars</small></div>
</div>
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<div class="d-flex gap-2 mt-3"><button class="btn btn-gym">Salvar</button><a href="alunos.php" class="btn btn-outline-dark">Voltar</a></div>
</form>
</main>
</div>
</body>
</html>
