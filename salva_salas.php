<?php

error_reporting(0);
ini_set('display_errors', 0 );

include('conn/conn.php');

$nome = $_POST['nome'];
$numero = $_POST['numero'];
$capacidade = $_POST['capacidade'];
$programa = $_POST['programas'];
$obs = $_POST['obs'];

$query = "INSERT INTO local (nome, numero, capacidade,programas, obs) VALUES ('$nome', '$numero', '$capacidade', '$programa', '$obs')";
mysqli_query($link, $query);

header('Location: salas.php?pagina=estrutura');
exit;


?>