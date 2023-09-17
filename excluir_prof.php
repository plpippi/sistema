<?php

error_reporting(0);
ini_set("display_errors", 0 );

$id_exclusao = $_GET['id_exclusao'];
include('conn/conn.php');

$query = "DELETE FROM $nome_tabela WHERE $id_tabela = $id_exclusao";
mysqli_query($link, $query);

$query_aula = "DELETE FROM aula WHERE id_professor = $id_exclusao";
mysqli_query($link, $query_aula);

header('Location: professor.php');
//print_r($query);

//echo $nome_tabela;
//echo $id_exclusao;
//echo $id_tabela;


?>