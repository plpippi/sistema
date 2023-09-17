<?php

include('conn/conn.php');

$tipo = $_POST['tipo'];
$nome = $_POST['nome'];
$numero = $_POST['numero'];
$cidade = $_POST['cidade'];
$data_inicio = $_POST['data_inicio'];
$url_retorno = $_POST['url'];

$ch = $_POST['ch'];
$hora_inicio = $_POST['hora_inicio'];
$hora_termino = $_POST['hora_termino'];
$horas_diaria = $_POST['horas_diaria'];
$dia = implode(',', $_POST['campo']);
$curso = $_POST['curso'];
$cor = $_POST['cor'];

$query = "INSERT INTO turma (tipo_turma, nome, numero_turma, id_cidade, data_inicio, carga_horaria, hora_inicio, hora_termino, horas_diaria, dias_semana, curso, cor) VALUES ('$tipo', '$nome', '$numero', '$cidade', '$data_inicio', '$ch', '$hora_inicio', '$hora_termino', '$horas_diaria', '$dia', '$curso', '$cor')";
mysqli_query($link, $query);

header("Location: ".$url_retorno.".php?pagina=turma");
exit;

?>