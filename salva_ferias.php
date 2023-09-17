<?php

include('conn/conn.php');

$id_professor = $_POST['id_professor'];
$inicio = $_POST['data_inicio'];
$fim = $_POST['data_fim'];


$query = "UPDATE professor SET ferias_inicio = '$inicio', ferias_fim = '$fim' WHERE id_professor = $id_professor";
mysqli_query($link, $query);

//print_r($query);
header('Location: professor.php');
exit;

?>