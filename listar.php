    <?php
    include('conn/conn.php');
    $data_inicio = "19-07-2021";
    $ch_total = 240;
    $ch_diaria = 4;
    $dias_aulas = $ch_total / $ch_diaria;
    $query = "SELECT * FROM dias_nao_letivos";
    $resultado = mysqli_query($link, $query);
    
    for($i=1; $i <= $dias_aulas; $i++){
        $data = implode("-",array_reverse(explode("-",$data_inicio)));
        $dia_semana = date('w', strtotime($data));
        if($dia_semana != 0 AND $dia_semana != 6){
            while($exibe = mysqli_fetch_assoc($resultado)){
                if($exibe['dia'] != '$data'){
                    echo $data."<br>";
                }
            }
        }
        $data_inicio = date('d-m-Y', strtotime($data_inicio. ' + 1 days '));
    }
    ?>
