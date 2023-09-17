<?php

include('conn/conn.php');

$id_uc_c = $_POST['uc'];
$id_turma = $_POST['id_turma'];
$id_curso = $_POST['id_curso'];
$id_local = $_POST['id_local'];
$id_professor = $_POST['professor'];
$data = $_POST['dia'];
$mes = $_POST['mes'];
$ano = $_POST['ano'];
$local = $_POST['local'];
$scroll = $_GET['scroll'];

// Query para retornar a carga horaria da UC
$query_ch_uc = "SELECT * FROM uc_componente WHERE id_uc_componente = $id_uc_c";
$result_ch_uc = mysqli_query($link, $query_ch_uc);
while($resultado_ch_uc = mysqli_fetch_assoc($result_ch_uc)){
    $ch_uc = $resultado_ch_uc['carga_horaria'];
}

// Query para retornar a data de inicio da turma e dias da semana
$query_turma = "SELECT * FROM turma WHERE id_turma = $id_turma";
$result_turma = mysqli_query($link, $query_turma);
while($resultado_turma = mysqli_fetch_assoc($result_turma)){
    $hora_inicio =  $resultado_turma['hora_inicio'];
    $hora_termino = $resultado_turma['hora_termino'];
    $cidade = $resultado_turma['id_cidade'];
    $tipo_turma = $resultado_turma['tipo_turma'];
}


$query = "INSERT INTO aula (id_professor, id_turma, id_cidade, id_local, id_curso, id_uc_componente, dia, hora_inicio, hora_termino) VALUES ('$id_professor', '$id_turma', '$cidade', '$id_local', '$id_curso', '$id_uc_c', '$data', '$hora_inicio', '$hora_termino')";
mysqli_query($link, $query);

print_r($query);

$local = $_GET['local'];
header('Location: '.$local.'.php?scroll=750&id_turma='.$id_turma.'&id_curso='.$id_curso.'&id_professor='.$id_professor.'&id_local='.$id_local.'&id_uc='.$id_uc_c.'&mes='.$mes.'&ano='.$ano);
exit;

?>
