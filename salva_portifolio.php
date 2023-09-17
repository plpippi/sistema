<?php

include('conn/conn.php');

$id_curso = $_GET['id_curso'];
$id_uc_componente = $_GET['id_uc_componente'];

$queryCompara = "SELECT * FROM curso_uc_componente WHERE id_curso = $id_curso AND id_uc_componente = $id_uc_componente";
$resultCompara = mysqli_query($link, $queryCompara);
$linhas = mysqli_num_rows($resultCompara);

if($linhas > 0){
    header('Location: portifolio.php?mensagem=UC/Componente já cadastrada ou vinculado para o curso&pagina=portifolio&id_curso='.$id_curso);
}else{
    $query = "INSERT INTO curso_uc_componente (id_curso, id_uc_componente) VALUES ('$id_curso', '$id_uc_componente')";
    mysqli_query($link, $query);

    header('Location: portifolio.php?pagina=portifolio&id_curso='.$id_curso);
    exit;
}