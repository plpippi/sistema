<?php

include('conn/conn.php');

$cidade = $_POST['cidade'];
$unidade = $_POST['unidade'];


$query = "INSERT INTO cidade (nome_cidade, id_unidade) VALUES ('$cidade', '$unidade')";
mysqli_query($link, $query);

//print $query;
header('Location: cidades.php?pagina=cadastro');
exit;

?>