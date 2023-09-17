<?php
    include('util.php');
    include('conn/conn.php');
    $inicio = $_GET['inicio'];
    $fim = $_GET['fim'];
    $query = "SELECT cidade.nome_cidade AS cidade, aula.hora_inicio AS inicio, aula.hora_termino AS termino, aula.id_aula AS id, aula.dia, professor.nome AS professor, turma.nome AS turma, turma.numero_turma AS numero, uc_componente.nome 
    AS ucComponente, local.nome AS sala, local.numero AS numeroSala FROM aula INNER JOIN cidade ON aula.id_cidade = cidade.id_cidade 
    INNER JOIN professor ON aula.id_professor = professor.id_professor INNER JOIN turma ON aula.id_turma = turma.id_turma 
    INNER JOIN uc_componente ON aula.id_uc_componente = uc_componente.id_uc_componente INNER JOIN local ON aula.id_local = local.id_local 
    WHERE (aula.dia >= '$inicio' AND aula.dia <= '$fim') ORDER BY aula.dia ASC"; 
    $result = mysqli_query($link, $query);

    $hora = date("H:i:s");
?>


<?php

$html = "
<!DOCTYPE html>
<html>
    <head>
        <style type='text/css'>
            .tabela{
                width: 550px;
                align-content: center;
                font-family: Arial, Helvetica, sans-serif;
            }
            .imagem{
                width: 150px;
                margin-bottom: 40px;
                margin-top: 80px;
            }
            html{
                margin: 0px;
                font-size: 12px !important;
            }
        </style>
        <link rel='stylesheet' href='css/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>
    </head>
    <body>
        <div class='container'>
            <div class='row'>
                <div class='col col-sm-12'>
                    <img class='imagem' src='data:image/png;base64,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'>
                    <br><b style='font-size:62px;'>Locação das Salas</b><br><br>
                    <table class='table table-striped' align='center'>
                    <tr>
                    <th>Professor</th>
                    <th>Turma</th>
                    <th>UC/Componente</th>
                    <th>Dia</th>
                    <th>Horário</th>
                    <th>Local</th>
    </tr>
                        <tbody>";
        while($resultado = mysqli_fetch_assoc($result)){ 
            $html .= "<tr>";
            $html .= "<td>".$resultadoConsulta['professor']."</td>";
            $html .= "<td>".$resultadoConsulta['turma']." - ".$resultadoConsulta['numero']."</td>";
            $html .= "<td>".$resultadoConsulta['ucComponente']."</td>";
            $html .= "<td>".converte_data($resultadoConsulta['dia'])."</td>";
            $html .= "<td>".$resultadoConsulta['inicio']." - ".$resultadoConsulta['termino']."</td>";
            $html .= "<td>".$resultadoConsulta['cidade']." | ".$resultadoConsulta['sala']." - ".$resultadoConsulta['numeroSala']."</td>";
            $html .= "</tr>";
        }
        $html .="               </tbody>
                    </table>
                </div>
            </div>
        </div>

    </body>
</html>";

require_once ('dompdf/autoload.inc.php');
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper("A4", "portrail");
$dompdf->render();
$dompdf->stream("Relatóirio Docente ".$hora);

//echo $html;

?>