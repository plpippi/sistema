<?php

error_reporting(0);
ini_set("display_errors", 0 );

include('conn/conn.php');

$id_turma = $_GET['id_turma'];
$query = "DELETE FROM aula WHERE id_turma = $id_turma";
mysqli_query($link, $query);

header('Location: cronograma.php');
//print_r($query);

?>