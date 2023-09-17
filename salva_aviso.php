<?php

include('conn/conn.php');

$aviso = $_POST['aviso'];
$data = $_POST['dia'];
$inicio = $_POST['inicio'];
$fim = $_POST['fim'];


$query = "INSERT INTO avisos (aviso, data, inicio, fim) VALUES ('$aviso', '$data', '$inicio', '$fim')";
mysqli_query($link, $query);

//print_r($query);

header('Location: avisos.php?pagina=aviso');
exit;

?>