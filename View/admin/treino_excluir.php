<?php
require_once '../../vendor/autoload.php';
exigirAdmin();
if(!csrf_validar($_GET['csrf']??null)) die('Token inválido.');
use Controller\TreinoController;
(new TreinoController())->excluir((int)($_GET['id']??0));
header('Location: treinos.php');
exit;
