<?php

error_reporting(0);
ini_set("display_errors", 0 );

$id_turma = $_GET['id_turma'];
include('conn/conn.php');

$query = "DELETE FROM turma WHERE id_turma = $id_turma";
mysqli_query($link, $query);

$query_aula = "DELETE FROM aula WHERE id_turma = $id_turma";
mysqli_query($link, $query_aula);

header('Location: turma.php');
exit;
//print_r($query);

//echo $nome_tabela;
//echo $id_exclusao;
//echo $id_tabela;


?>