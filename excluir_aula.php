<?php

error_reporting(0);
ini_set("display_errors", 0 );
include('conn/conn.php');

$id_aula = $_GET['id_aula'];
$id_turma = $_GET['id_turma'];
$id_local = $_GET['id_local'];
$id_curso = $_GET['id_curso'];
$id_professor = $_GET['id_professor'];
$id_uc = $_GET['id_uc'];
$mes = $_GET['mes'];
$ano = $_GET['ano'];
$local = $_GET['local'];
$scroll = $_GET['scroll'];


$query = "DELETE FROM aula WHERE id_aula = $id_aula";
mysqli_query($link, $query);

//print_r($query);


header('Location: '.$local.'.php?scroll=750&id_turma='.$id_turma.'&id_curso='.$id_curso.'&id_professor='.$id_professor.'&id_uc='.$id_uc.'&id_local='.$id_local.'&mes='.$mes.'&ano='.$ano);
exit;

?>
