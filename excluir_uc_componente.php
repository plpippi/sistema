<?php

error_reporting(0);
ini_set("display_errors", 0 );

$id_exclusao = $_GET['id_exclusao'];

include('conn/conn.php');

$query = "DELETE FROM uc_componente WHERE id_uc_componente = $id_exclusao";
mysqli_query($link, $query);

$queryCurso = "DELETE FROM curso_uc_componente WHERE id_uc_componente = $id_exclusao";
mysqli_query($link, $queryCurso);

header('Location: uccomponente.php');

?>