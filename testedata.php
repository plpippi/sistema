<?php

include('conn/conn.php');
$query_ferias = "SELECT ferias_inicio, ferias_fim FROM professor WHERE id_professor = 1";
$result_ferias = mysqli_query($link, $query_ferias);
while($resultado_ferias = mysqli_fetch_assoc($result_ferias)){
    $ferias_inicio = $resultado_ferias['ferias_inicio'];
    $ferias_fim = $resultado_ferias['ferias_fim'];
}

//echo $ferias_inicio;
//echo '<br>';
//echo $ferias_fim;

$diferenca = strtotime($ferias_fim) - strtotime($ferias_inicio);
$dias = floor($diferenca / (60 * 60 * 24));

//echo $dias;

$dia_ferias = $ferias_inicio;

echo $dia_ferias;

for($i = 1; $i <= $dias; $i++){
    
    $ferias[] = $dia_ferias;
    $dia_ferias = date('d-m-Y', strtotime($dia_ferias. ' + 1 days '));
    //echo $dia_ferias.'<br>';
}

// Query para montar a lista de feriados e dias não letivos
$query_feriados = "SELECT * FROM dias_n_letivos";
$resultado_feriados = mysqli_query($link, $query_feriados);
while($exibe = mysqli_fetch_assoc($resultado_feriados)){
    $feriados[] = $exibe['dia'] ;
}

print_r($feriados);

?>