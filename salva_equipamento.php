<?php

include('conn/conn.php');

$tipo = $_POST['tipo'];
$numero = $_POST['numero'];
$descricao = $_POST['descricao'];


$query = "INSERT INTO equipamento (tipo, numero, descricao) VALUES ('$tipo', '$numero', '$descricao')";
mysqli_query($link, $query);

header('Location: equipamentos.php?pagina=estrutura');
exit;

?>