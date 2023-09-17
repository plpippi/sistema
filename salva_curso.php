<?php

include('conn/conn.php');

$nome = $_POST['nome'];
$ch = $_POST['ch'];
$descricao = $_POST['descricao'];
$uc = $_POST['uc'];

$numero_uc = count($uc);


$query = "INSERT INTO curso (nome, carga_horaria, descricao) VALUES ('$nome', '$ch', '$descricao')";
mysqli_query($link, $query);

$queryIdCurso = "SELECT MAX(id_curso) AS id_curso FROM curso";
$resultId = mysqli_query($link, $queryIdCurso);
while ($resultadoId = mysqli_fetch_assoc($resultId)){
    $idCurso = $resultadoId['id_curso'];
}

for ($i=0; $i < $numero_uc; $i++){
    $id_uc = $uc[$i];
    $queryUc = "INSERT INTO curso_uc_componente (id_curso, id_uc_componente) VALUES ('$idCurso', '$id_uc')";
    mysqli_query($link, $queryUc);
}

header('Location: cursos.php?pagina=portifolio');
exit;

?>