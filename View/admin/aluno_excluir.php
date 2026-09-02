<?php
require_once '../../vendor/autoload.php';
exigirAdmin();
if(!csrf_validar($_GET['csrf']??null)) die('Token inválido.');
use Controller\AlunoController;
$ctrl=new AlunoController();
$id=(int)($_GET['id']??0);
$ctrl->excluir($id);
header('Location: alunos.php?msg='.urlencode('Aluno excluído.'));
exit;
