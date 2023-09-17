<?php

include('conn/conn.php');

$id_uc_c = $_POST['uc'];
$id_turma = $_POST['id_turma'];
$id_curso = $_POST['curso'];
$id_local = $_POST['local'];
$id_professor = $_POST['professor'];
$dias_semana = $_POST['campo'];

// Query para retornar a carga horaria da UC
$query_ch_uc = "SELECT * FROM uc_componente WHERE id_uc_componente = $id_uc_c";
$result_ch_uc = mysqli_query($link, $query_ch_uc);
while($resultado_ch_uc = mysqli_fetch_assoc($result_ch_uc)){
    $ch_uc = $resultado_ch_uc['carga_horaria'];
}

// Query para montar a data de inicio de férias do docente
$query_ferias = "SELECT ferias_inicio, ferias_fim FROM professor WHERE id_professor = $id_professor";
$result_ferias = mysqli_query($link, $query_ferias);
while($resultado_ferias = mysqli_fetch_assoc($result_ferias)){
    $ferias_inicio = $resultado_ferias['ferias_inicio'];
    $ferias_fim = $resultado_ferias['ferias_fim'];
}

// Query para montar a data de inicio do recesso da turma
$query_recesso = "SELECT recesso_inicio, recesso_fim FROM turma WHERE id_turma = $id_turma";
$result_recesso = mysqli_query($link, $query_recesso);
while($resultado_recesso = mysqli_fetch_assoc($result_recesso)){
    $recesso_inicio = $resultado_recesso['recesso_inicio'];
    $recesso_fim = $resultado_recesso['recesso_fim'];
}

// Query para retornar informações da turma
$query_turma = "SELECT * FROM turma WHERE id_turma = $id_turma";
$result_turma = mysqli_query($link, $query_turma);
while($resultado_turma = mysqli_fetch_assoc($result_turma)){
    $data_inicio_bd = $resultado_turma['data_inicio'];
    $ch_diaria = $resultado_turma['horas_diaria'];
    //$dias_semana = explode(',', $resultado_turma['dias_semana']);
    $hora_inicio = $resultado_turma['hora_inicio'];
    $hora_termino = $resultado_turma['hora_termino'];
    $cidade = $resultado_turma['id_cidade'];
    $tipo_turma = $resultado_turma['tipo_turma'];
}

// Query para montar a lista de feriados e dias não letivos
$query_feriados = "SELECT * FROM dias_n_letivos WHERE id_cidade = 0 OR id_cidade = $cidade";
$resultado_feriados = mysqli_query($link, $query_feriados);
while($exibe = mysqli_fetch_assoc($resultado_feriados)){
    $feriados[] = $exibe['dia'] ;
}

// Calcula a quantidade de dias das ferias
$diferenca_ferias = strtotime($ferias_fim) - strtotime($ferias_inicio);
$quant_ferias = floor($diferenca_ferias / (60 * 60 * 24));

// Inicializa o primeiro dia de ferias
$dia_ferias = $ferias_inicio;

// Monta a lista de ferias docente
for($f = 1; $f <= $quant_ferias; $f++){
    $ferias[] = $dia_ferias;
    $dia_ferias = date('Y-m-d', strtotime($dia_ferias. ' + 1 days '));
}

// Calcula a carga horaria da uc e compara com o total geral do curso
$ch_total = $ch_uc;
if($tipo_turma == 1){
    $dias_aulas = ceil($ch_total / $ch_diaria);
}else if ($tipo_turma == 2){
    $dataInicio = $_POST['data_inicio'];
    $dataFim = $_POST['data_fim'];

    $diferenca = strtotime($dataFim) - strtotime($dataInicio);
    $dias_aulas = floor($diferenca / (60 * 60 * 24)) + 1;
}



if($_POST['data_inicio'] == ''){
    $data_inicio = $data_inicio_bd;
}else{
    $data_inicio = $_POST['data_inicio'];
}
echo $dias_aulas."<br><br><br>";
$i = 1;
$data_inicio = implode("-",array_reverse(explode("-",$data_inicio)));



while($i <= $dias_aulas){
    $data = implode("-",array_reverse(explode("-",$data_inicio))); //converte as datas para o padrão br
    $dia_semana = date('w', strtotime($data)); // retorna o dia da semana
    if($data != in_array($data, $feriados) && $data != in_array($data, $ferias) && $dia_semana == in_array($dia_semana, $dias_semana) && $dia_semana != 0){
        $query = "INSERT INTO aula (id_professor, id_turma, id_cidade, id_local, id_curso, id_uc_componente, dia, hora_inicio, hora_termino) VALUES ('$id_professor', '$id_turma', '$cidade', '$id_local', '$id_curso', '$id_uc_c', '$data', '$hora_inicio', '$hora_termino')";
        mysqli_query($link, $query);
        $i++;
    }
    $data_inicio = date('d-m-Y', strtotime($data_inicio. ' + 1 days '));
}
$local = $_GET['local'];
header('Location: '.$local.'.php?id_turma='.$id_turma.'&id_curso='.$id_curso);
exit;

?>
