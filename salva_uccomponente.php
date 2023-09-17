<?php

include('conn/conn.php');

$nome = $_POST['nome'];
$ch = $_POST['ch'];
$descricao = $_POST['descricao'];


$query = "INSERT INTO uc_componente (nome, descricao, carga_horaria ) VALUES ('$nome', '$descricao', '$ch')";
mysqli_query($link, $query);

header('Location: uccomponente.php?pagina=portifolio');
exit;

?>