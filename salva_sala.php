<?php

include('conn/conn.php');

$nome = $_POST['nome'];
$numero = $_POST['numero'];
$capacidade = $_POST['capacidade'];
$programa = $_POST['programas'];
$visualiza = $_POST['visualiza'];
$obs = $_POST['obs'];

$query = "INSERT INTO local (nome, numero, capacidade, programas, obs, visualiza) VALUES ('$nome', '$numero', '$capacidade', '$programa', '$obs', '$visualiza')";
mysqli_query($link, $query);

header('Location: salas.php?pagina=estrutura');
exit;

?>