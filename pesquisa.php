<?php
include('conn/conn.php');
$id_turma = $_GET['turma'];
$query = "UPDATE turma SET pesquisa = 1 WHERE id_turma = $id_turma";
mysqli_query($link, $query);
header('Location: turma.php');
exit;
?>
