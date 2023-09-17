<?php

error_reporting(0);
ini_set("display_errors", 0 );

$id_exclusao = $_GET['id_exclusao'];
$id_tabela = $_GET['id_tabela'];
$nome_tabela = $_GET['nome_tabela'];
$local = $_GET['local'];
include('conn/conn.php');

$query = "DELETE FROM $nome_tabela WHERE $id_tabela = $id_exclusao";
mysqli_query($link, $query);

header('Location: '.$local);
//print_r($query);

//echo $nome_tabela;
//echo $id_exclusao;
//echo $id_tabela;


?>