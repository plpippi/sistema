<?php

include('conn/conn.php');

$cidade = $_POST['cidade'];
$dia = $_POST['dia'];
$tipo = $_POST['tipo'];
$descricao = $_POST['descricao'];


$query = "INSERT INTO dias_n_letivos (id_cidade, dia, tipo, descricao) VALUES ('$cidade', '$dia', '$tipo', '$descricao')";
mysqli_query($link, $query);

header('Location: feriados.php?pagina=estrutura');
exit;

?>