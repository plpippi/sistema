<?php

include('conn/conn.php');
$id_pessoa = $_POST['professor'];
$id_equipamento = $_POST['equipamento'];
$data = $_POST['dia'];
$retirada = $_POST['retirada'];
$devolucao = $_POST['devolucao'];


$query = "INSERT INTO reserva (id_pessoa, id_equipamento, data, hora_retirada, hora_devolucao) VALUES ($id_pessoa, $id_equipamento, '$data', '$retirada', '$devolucao')";
mysqli_query($link, $query);

//print_r($query);

header('Location: reserva_equipamento.php?pagina=reserva');
exit;

?>