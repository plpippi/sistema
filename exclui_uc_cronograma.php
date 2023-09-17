<?php

error_reporting(0);
ini_set("display_errors", 0 );

include('conn/conn.php');

$id_turma = $_GET['id_turma'];
$id_uc = $_GET['id_uc'];
$id_curso = $_GET['id_curso'];
$query = "DELETE FROM aula WHERE id_uc_componente = $id_uc AND id_turma = $id_turma";
mysqli_query($link, $query);

header('Location: cad_cronograma.php?id_turma='.$id_turma.'&id_curso='.$id_curso);
//print_r($query);

?>