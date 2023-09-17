<?php

include('conn/conn.php');

$tabela = $_GET['tabela'];
$id_tabela = $_GET['id_tabela'];
$id_item = $_GET['id_item'];
$local = $_GET['local'];
$query = "UPDATE $tabela SET status = 1 WHERE $id_tabela = $id_item";
mysqli_query($link, $query);

//print_r($query);

header('Location: '.$local);

?>