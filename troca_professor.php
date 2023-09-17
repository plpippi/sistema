<?php

include('conn/conn.php');

$id_professor = $_POST['id_professor'];
$id_novo_professor = $_POST['id_novo_professor'];
$id_turma = $_POST['id_turma'];

$query = "UPDATE aula SET id_professor = $id_novo_professor WHERE id_professor = $id_professor AND id_turma = $id_turma";
mysqli_query($link, $query);

//print_r($query);

header('Location: professor.php');
exit;

?>