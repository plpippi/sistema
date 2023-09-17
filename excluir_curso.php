<?php

error_reporting(0);
ini_set("display_errors", 0 );

$id_exclusao = $_GET['id_exclusao'];
include('conn/conn.php');

$query = "DELETE FROM curso WHERE id_curso = $id_exclusao";
mysqli_query($link, $query);

$query_uc_componete = "DELETE FROM curso_uc_componente WHERE id_curso = $id_exclusao";
mysqli_query($link, $query_uc_componete);

header('Location: cursos.php');
//print_r($query);

//echo $nome_tabela;
//echo $id_exclusao;
//echo $id_tabela;


?>