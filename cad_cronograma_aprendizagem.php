<?php

// Inicio sessão
session_start();

error_reporting(0);
ini_set("display_errors", 0);

if (!isset($_SESSION['user'])) {
    header('Location: index.html');
    exit;
}
$user = $_SESSION['user'];
$nome_user = $_SESSION['nome_user'];
$imagem_user = $_SESSION['imagem'];
$acesso_user = $_SESSION['acesso'];
// Fim sessão

include('util.php');
include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

//Variaveis para receber ID do Professor, uc, local
$id_turma = $_GET['id_turma'];
$id_curso = $_GET['id_curso'];
$id_professor = $_GET['id_professor'];
$id_uc = $_GET['id_uc'];
$id_local = $_GET['id_local'];
$anoFeriado = $_GET['ano'];

$ano_recebe = $_GET['ano'];
$mes_recebe = $_GET['mes'];
if($ano_recebe == "" AND $mes_recebe == ""){
    $ano_recebe = date('Y');
    $mes_recebe = date('m');
}
$mes = $_GET['mes'];
$ano = $_GET['ano'];

$query = "SELECT * FROM curso ORDER BY id_curso ASC";
$result = mysqli_query($link, $query);

$query_professor = "SELECT * FROM professor WHERE status = 1 ORDER BY id_professor ASC";
$result_professor = mysqli_query($link, $query_professor);

$query_uc_com = "SELECT uc_componente.* FROM uc_componente INNER JOIN curso_uc_componente ON uc_componente.id_uc_componente = curso_uc_componente.id_uc_componente WHERE status = 1 AND curso_uc_componente.id_curso = $id_curso ORDER BY id_uc_componente ASC";
$result_uc_com = mysqli_query($link, $query_uc_com);

$query_local = "SELECT * FROM local WHERE status = 1 ORDER BY id_local ASC";
$result_local = mysqli_query($link, $query_local);

$query_turma = "SELECT * FROM turma WHERE id_turma =  '" . $id_turma . "' ORDER BY id_turma ASC";
$result_turma = mysqli_query($link, $query_turma);

while ($resultado_turma = mysqli_fetch_assoc($result_turma)) {
    $nome_turma = $resultado_turma['nome'];
    $chd_turma = $resultado_turma['horas_diaria'];
    $numero_turma = $resultado_turma['numero_turma'];
    $local = $resultado_turma['id_cidade'];
    $ch_diaria = $resultado_turma['horas_diaria'];
    $hora_inicio = $resultado_turma['hora_inicio'];
    $hora_termino = $resultado_turma['hora_termino'];
}

$queryFeriados = "SELECT *, DAY(dias_n_letivos.dia) AS dias, MONTH(dias_n_letivos.dia) AS mes FROM dias_n_letivos WHERE (id_cidade = 0 OR id_cidade = $local) AND YEAR(dias_n_letivos.dia) = '$anoFeriado'";
$resultFeriados = mysqli_query($link, $queryFeriados);

//Obter nome de professor, uc, local
$queryNomeProfessor = "SELECT * FROM professor WHERE id_professor = $id_professor";
$resultNomeProfessor = mysqli_query($link, $queryNomeProfessor);
while($resultadoNomeProfessor = mysqli_fetch_assoc($resultNomeProfessor)){
    $nomeProfessor = $resultadoNomeProfessor['nome'];
}

$queryNomeUc = "SELECT * FROM uc_componente WHERE id_uc_componente = $id_uc";
$resultNomeUc = mysqli_query($link, $queryNomeUc);
while($resultadoNomeUc = mysqli_fetch_assoc($resultNomeUc)){
    $nomeUc = $resultadoNomeUc['nome'];
}

$queryNomeLocal = "SELECT * FROM local WHERE id_local = $id_local";
$resultNomeLocal = mysqli_query($link, $queryNomeLocal);
while($resultadoNomeLocal = mysqli_fetch_assoc($resultNomeLocal)){
    $nomeLocal = $resultadoNomeLocal['nome'];
    $numeroLocal = $resultadoNomeLocal['numero'];
}

$query_aulas = "SELECT * FROM aula WHERE id_turma = $id_turma ORDER BY dia ASC";
$result_aulas = mysqli_query($link, $query_aulas);

$query_lista_uc_c = "SELECT uc_componente.id_uc_componente, uc_componente.carga_horaria, uc_componente.nome, MAX(aula.dia) AS data_termino, MIN(aula.dia) AS data_inicio FROM aula 
NNER JOIN uc_componente ON aula.id_uc_componente = uc_componente.id_uc_componente WHERE `id_turma` = '" . $id_turma . "' GROUP BY aula.id_uc_componente ORDER BY data_termino ASC";
$result_lista_uc_c = mysqli_query($link, $query_lista_uc_c);


// Query para pendencias
$queryFormatacao = "SELECT aula.id_professor, aula.dia, DAY(aula.dia) AS dia_aula, MONTH(aula.dia) AS mes, COUNT(*) FROM aula 
WHERE (aula.hora_inicio BETWEEN '$hora_inicio' AND '$hora_termino') AND YEAR(aula.dia) = '$ano_recebe' GROUP BY aula.id_professor, aula.dia HAVING COUNT(*) > 1;";
$resultFormatacao = mysqli_query($link, $queryFormatacao);

// Query aulas
$query_calendario_aulas = "SELECT aula.id_turma, aula.id_local, aula.id_professor, aula.id_uc_componente, aula.id_aula, DAY(aula.dia) AS dia_aula, MONTH(aula.dia) AS mes, professor.nome AS nome, uc_componente.nome AS uc, local.numero AS sala FROM aula 
INNER JOIN professor ON aula.id_professor = professor.id_professor INNER JOIN uc_componente ON aula.id_uc_componente = uc_componente.id_uc_componente 
INNER JOIN local ON aula.id_local = local.id_local WHERE YEAR(aula.dia) = '$ano_recebe' AND MONTH(aula.dia) = '$mes_recebe' AND id_turma = '$id_turma'";
$result_calendario_aulas = mysqli_query($link, $query_calendario_aulas);

$query_dados = "SELECT aula.id_turma, COUNT(*) AS quantidade, curso.id_curso, SUM(HOUR(aula.hora_termino) - HOUR(aula.hora_inicio)) AS horas_cadastradas, curso.nome AS nome_curso, curso.carga_horaria AS ch_curso FROM `aula` 
INNER JOIN curso ON aula.id_curso = curso.id_curso WHERE curso.id_curso = $id_curso AND id_turma = $id_turma";
$result_dados = mysqli_query($link, $query_dados);
while($resultado_dados = mysqli_fetch_assoc($result_dados)){
    $horas_cadastradas = $resultado_dados['horas_cadastradas'];
    $nome_curso = $resultado_dados['nome_curso'];
    $horas_totais_curso = $resultado_dados['ch_curso'];
    $encontros_cadastrados = $resultado_dados['quantidade'];
}

$horas_a_cadastrar_curso = $horas_totais_curso - $horas_cadastradas;
$total_encontros = $horas_totais_curso / $ch_diaria;
$encontros_cadastrar = $total_encontros - $encontros_cadastrados;

$query_dados_uc = "SELECT aula.id_turma, aula.id_curso, COUNT(*) AS quantidade, uc_componente.id_uc_componente, SUM(HOUR(aula.hora_termino) - HOUR(aula.hora_inicio)) AS horas_cadastradas, 
uc_componente.nome AS nome_uc, uc_componente.carga_horaria AS ch_uc_componente FROM `aula` 
INNER JOIN uc_componente ON aula.id_uc_componente = uc_componente.id_uc_componente WHERE uc_componente.id_uc_componente = $id_uc AND id_curso = $id_curso AND id_turma = $id_turma";
$result_dados_uc = mysqli_query($link, $query_dados_uc);
while($resultado_dados_uc = mysqli_fetch_assoc($result_dados_uc)){
    $horas_cadastradas_uc = $resultado_dados_uc['horas_cadastradas'];
    $nome_uc = $resultado_dados_uc['nome_curso'];
    $horas_totais_uc = $resultado_dados_uc['ch_uc_componente'];
    $encontros_cadastrados_uc = $resultado_dados_uc['quantidade'];
    $nome_uc_componente = $resultado_dados_uc['nome_uc'];
}

$horas_a_cadastrar_uc = $horas_totais_uc - $horas_cadastradas_uc;
$total_encontros_uc = $horas_totais_uc / $ch_diaria;
$encontros_cadastrar_uc = $total_encontros_uc - $encontros_cadastrados_uc;


function dia_pascoa($a){
	//fabioissamu@yahoo.com Fabio Issamu Oshiro
	//retorna a páscoa
    if ($a<1900){$a+=1900;}
	$c = floor($a/100);
	$n = $a - (19*floor($a/19));
	$k = floor(($c - 17)/25);
	$i = $c - $c/4 - floor(($c-$k)/3) +(19*$n) + 15;
	$i = $i - (30*floor($i/30));
	$i = $i - (floor($i/28)*(1-floor($i/28))*floor(29/($i+1))*floor((21-$n)/11));
	$j = $a + floor($a/4) + $i + 2 -$c + floor($c/4);
	$j = $j - (7* floor($j/7));
	$l = $i - $j;
	$m = 3 + floor(($l+40)/44);
	$d = $l + 28 - (31*floor($m/4));
	$retorno=mktime(0, 0, 0, $m, $d-1, $a);
	return $retorno;
}

function calendario($result_calendario_aulas, $resultFormatacao, $resultFeriados, $id_turma, $id_curso, $id_professor, $id_uc, $id_local, $mes, $ano){

	//Variável de retorno do código em HTML
	$retorno="";

	//Primeira linha do calendário
	$arr_dias=Array("Dom","Seg","Ter","Qua","Qui","Sex","Sáb");

	//Deseja iniciar pelo sábado?
	$ini_sabado=false;

    //Stilo
    while($resultadoFormatacao = mysqli_fetch_assoc($resultFormatacao)){
        $stilo[$resultadoFormatacao['dia_aula']."-".$resultadoFormatacao['mes']] = 2;
    }

    //Calendário de aulas
    while($resultado_calendario_aulas = mysqli_fetch_assoc($result_calendario_aulas)){
        if($stilo[$resultado_calendario_aulas['dia_aula']."-".$resultado_calendario_aulas['mes']] == 2){
            $feriados[$resultado_calendario_aulas['dia_aula']."-".$resultado_calendario_aulas['mes']] = "<p style='color: red'>&nbsp;&nbsp;<a href='excluir_aula.php?scroll=750&id_aula=".$resultado_calendario_aulas['id_aula']."&id_turma=".$id_turma."&id_curso=".$id_curso."&id_professor=".$resultado_calendario_aulas['id_professor']."&id_uc=".$resultado_calendario_aulas['id_uc_componente']."&id_local=".$resultado_calendario_aulas['id_local']."&mes=".$mes."&ano=".$ano."&local=cad_cronograma_aprendizagem'><i class='bi bi-trash-fill'></i></a> <br>".$resultado_calendario_aulas['nome']."<br>".$resultado_calendario_aulas['uc']."<br>".$resultado_calendario_aulas['sala']."</p>";    
        }else{
            $feriados[$resultado_calendario_aulas['dia_aula']."-".$resultado_calendario_aulas['mes']] = "<p>&nbsp;&nbsp;<a href='excluir_aula.php?scroll=750&id_aula=".$resultado_calendario_aulas['id_aula']."&id_turma=".$id_turma."&id_curso=".$id_curso."&id_professor=".$resultado_calendario_aulas['id_professor']."&id_uc=".$resultado_calendario_aulas['id_uc_componente']."&id_local=".$resultado_calendario_aulas['id_local']."&mes=".$mes."&ano=".$ano."&local=cad_cronograma_aprendizagem'><i class='bi bi-trash-fill'></i></a> <br>".$resultado_calendario_aulas['nome']."<br>".$resultado_calendario_aulas['uc']."<br>".$resultado_calendario_aulas['sala']."</p>";
        }
    }

	//Feriados comuns
    while($resultado_feriados = mysqli_fetch_assoc($resultFeriados)){
        $feriados[$resultado_feriados['dias']."-".$resultado_feriados['mes']] = "<br>".$resultado_feriados['descricao'];
    }

	//mes e ano do calendario a ser montado
	If($_GET['mes'] and $_GET['ano'])
	{
	   $mes = $_GET['mes'];
	  
	   $ano = $_GET['ano'];
	}
	Else
	{
	   $mes = date("m");
	   $ano = date("Y");
	}

	//Feriados com data mutante
	/*$pascoa=dia_pascoa($ano);
	$feriados[date("j-n", $pascoa)]="Páscoa";
	$feriados[date("j-n", $pascoa-86400*2)]="Paixão";
	$feriados[date("j-n", $pascoa-86400*46)]="Cinzas";
	$feriados[date("j-n", $pascoa-86400*47)]="Carnaval";
	$feriados[date("j-n", $pascoa+86400*60)]="Corpus Christi";*/

	$cont_mes = 1; 
	if ($ini_sabado){
		$dia_semana = converte_dia(date("w", mktime(0, 0, 0, $mes, 1, $ano))); //dia da semana do primeiro dia do mes
	}else{
		//Comum
		$dia_semana = date("w", mktime(0, 0, 0, $mes, 1, $ano)); 
	}
	$t_mes = date("t", mktime(0, 0, 0, $mes, 1, $ano)); //no. total de dias no mes

	//dados do mes passado
	$dia_semana_ant = ((date("d", mktime(0, 0, 0, $mes, 0, $ano))+1)-$dia_semana); 
	$mes_ant = date("m", mktime(0, 0, 0, $mes, 0, $ano));
	$ano_ant = date("Y", mktime(0, 0, 0, $mes, 0, $ano));

	//dados do mes seguinte
	$dia_semana_post = 1;
	$mes_post = date("m", mktime(0, 0, 0, $mes, $t_mes+1, $ano));  
	$ano_post = date("Y", mktime(0, 0, 0, $mes, $t_mes+1, $ano));  

	$retorno.="<center>";

	//titulo do calendario
	$retorno.= "<font style=\"font-family:verdana,arial,serif;font-size:16\"><b>Calend&#225;rio: ".converte_mes($mes)."/".$ano."</b></font><br>";

	//montagem do calendario
	$retorno.= "<table whidth='100%'><tr><td>&nbsp;</td><td>";

	$retorno.= "<table border=1 width=1100 cellpadding=5 cellspacing=5 style='border-collapse: collapse; color:#000000' id=AutoNumber1 bordercolor=#333333>";
	//primeira linha do calendario
	$retorno.= "<tr height='80px' bgcolor=#555555 face=verdana,arial,serif>";
	for($i=0;$i<7;$i++){
		if ($i==0 || $i==6){
			//é domingo ou sábado
			$retorno.= "<td height='80px' bgcolor=#435ebe><font color=#EEEEEE face=verdana,arial,serif>$arr_dias[$i]</font></td>";
		}else{
			$retorno.= "<td height='80px'><font color=#EEEEEE face=verdana,arial,serif>$arr_dias[$i]</font></td>";
		}
	}
	$cont_cor = 0;
	While ($t_mes >= $cont_mes)
	{
	   $cont_semana = 0;
	   $retorno.= "<tr>";
	   If ($dia_semana == 7)
	   {
		  $dia_semana = 0;
	   }
	   If(($cont_cor%2)!=0) //alterna cor das linhas
	   {
		  $cor = "#F0F0F0";
	   }
	   Else
	   {
		  $cor = "#F8F8F8";
	   }
	   
	   While ($dia_semana < 7)
	   {
		  If ($cont_mes <= $t_mes)
		  {
			 If ($dia_semana == $cont_semana) //celulas de dias do mes
			 {
				$retorno.= "<td valign=top bgcolor=".$cor." width=110 height=150>";
				$retorno.= "<font face=verdana,arial,serif size=2><b><button type='button' class='btn btn-primary' onclick=document.getElementById('dia').value='".$ano."-".$mes."-".$cont_mes."';document.getElementById('formCronograma').submit();>".$cont_mes."</button></b><input type='hidden' value='".$cont_mes."' id='' name=''>";

				/************************************************************/
				/******** Conteudo do calendario, se tiver, aqui!!!! ********/ 
				/************************************************************/
				$nome_feriado=$feriados[$cont_mes."-".((int)$mes)];
				if ($nome_feriado!=""){
					$retorno.= $nome_feriado;
				}
				$retorno.= "</br></font></td>";
				$cont_mes++;
				$dia_semana++;
				$cont_semana++;
			 }
			 Else //celulas vazias no inicio (mes anterior)
			 {
				$retorno.= "<td valign=top bgcolor=".$cor.">";
				$retorno.= "<font color=#AAAAAA face=verdana,arial,serif size=2>".$dia_semana_ant."</font>";
				$retorno.= "</td>";
				$cont_semana++;    
				$dia_semana_ant++;
			 }
		  }
		  Else
		  {
				While ($cont_semana < 7) //celulas vazias no fim (mes posterior)
				{
					$retorno.= "<td valign=top bgcolor=".$cor.">";
					$retorno.= "<font color=#AAAAAA face=verdana,arial,serif size=2>".$dia_semana_post."</font>";
					$retorno.= "</td>";
					$cont_semana++;    
					$dia_semana_post++;
				}
		 break 2;   
		  }
	   }
	   $retorno.= "</tr>";
	   $cont_cor++;
	}

	$retorno.= "</table>";

	$retorno.= "</td></tr></table>";


	$retorno.= "<br>";

	//links para mes anterior e mes posterior
	$retorno.= "<table width=100%><tr><td align='center'>";
	$retorno.= "<a href=".$_SERVER['PHP_SELF']."?scroll=750&mes=".$mes_ant."&ano=".$ano_ant."&id_turma=".$id_turma."&id_curso=".$id_curso."&id_professor=".$id_professor."&id_uc=".$id_uc."&id_local=".$id_local." class=estilo1><button type='button' class='btn btn-primary'>".converte_mes($mes_ant)."/".$ano_ant."</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$retorno.= "<a href=".$_SERVER['PHP_SELF']."?scroll=750&mes=".$mes_post."&ano=".$ano_post."&id_turma=".$id_turma."&id_curso=".$id_curso."&id_professor=".$id_professor."&id_uc=".$id_uc."&id_local=".$id_local." class=estilo1><button type='button' class='btn btn-primary'>".converte_mes($mes_post)."/".$ano_post."</button></a>";
	$retorno.= "</td></tr><tr><td>&nbsp;</td></tr></table>";

	//formulario para escolha de uma data
	$retorno.= "<form method=get action=".$_SERVER['PHP_SELF'].">";
	$retorno.= "<font style=\"font-family:verdana,arial,serif;font-size:12\">M&#234;s: </font><select name=mes>";
	$retorno.= "<option></option>";

	For($cont=1;$cont<=12;$cont++)
	{
	   $retorno.= "<option value=".$cont.">".converte_mes($cont)."</option>";
	}
	$retorno.= "</select>";

	$retorno.= "<font style=\"font-family:verdana,arial,serif;font-size:12\">&nbsp;&nbsp;Ano: </font><select name=ano>";
	$retorno.= "<option></option>";

	For($cont=date("Y")-5;$cont<=date("Y")+5;$cont++)
	{
	   $retorno.= "<option value=".$cont.">".$cont."</option>";
	}
	$retorno.= "</select>";

	//Variaveis login na pagina apolo
	$retorno.= "<input type=hidden name=usuario value='".$_GET['usuario']."' />";
	$retorno.= "<input type=hidden name=senha value='".$_GET['senha']."' />";
    $retorno.= "<input type=hidden name=id_turma value='".$id_turma."' />";
    $retorno.= "<input type=hidden name=id_curso value='".$id_curso."' />";
    $retorno.= "<input type=hidden name=id_professor value='".$id_professor."' />";
    $retorno.= "<input type=hidden name=id_uc value='".$id_uc."' />";
    $retorno.= "<input type=hidden name=id_local value='".$id_local."' />";

	$retorno.= "&nbsp;&nbsp;<button type=submit class='btn btn-primary'>Ir</button>";
	$retorno.= "</form>";

	$retorno.= "</center>";
	return $retorno;
}

Function converte_dia($dia_semana) //funcao para comecar a montar o calendario pela quarta-feira
{
   If($dia_semana == 0)
   {
      $dia_semana = 1;
   }
   ElseIf ($dia_semana == 1)
   {
      $dia_semana = 2;
   }
   ElseIf ($dia_semana == 2)
   {
      $dia_semana = 3;
   }
   ElseIf ($dia_semana == 3)
   {
      $dia_semana = 4;
   }
   ElseIf ($dia_semana == 4)
   {
      $dia_semana = 5;
   }
   ElseIf ($dia_semana == 5)
   {
      $dia_semana = 6;
   }
   ElseIf ($dia_semana == 6)
   {
      $dia_semana = 0;
   }

   return $dia_semana; 

}

Function converte_mes($mes)
{
         If($mes == 1)
         {
          $mes = "Janeiro";
         }
         ElseIf($mes == 2)
         {
          $mes = "Fevereiro";
         }
         ElseIf($mes == 3)
         {
          $mes = "Março";
         }
         ElseIf($mes == 4)
         {
          $mes = "Abril";
         }
         ElseIf($mes == 5)
         {
          $mes = "Maio";
         }
         ElseIf($mes == 6)
         {
          $mes = "Junho";
         }
         ElseIf($mes == 7)
         {
          $mes = "Julho";
         }
         ElseIf($mes == 8)
         {
          $mes = "Agosto";
         }
         ElseIf($mes == 9)
         {
          $mes = "Setembro";
         }
         ElseIf($mes == 10)
         {
          $mes = "Outubro";
         }
         ElseIf($mes == 11)
         {
          $mes = "Novembro";
         }
         ElseIf($mes == 12)
         {
          $mes = "Dezembro";
         }
         return $mes;
}

//Scroll 750
$scroll = $_GET['scroll'];
if($scroll == ""){
    $scroll == "0";
}

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <?php echo $cabecalho; ?>
</head>

<body onload="window.scrollTo('0', '<?php echo $scroll ?>')">
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <?php echo $corpo; ?>
                <?php echo $menu; ?>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Cronograma</h3>
                            <p class="text-subtitle text-muted">Cadastre um Cronograma</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Cronograma</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Inicio Formulário -->
                <form method="POST" action="gera_cronograma_aprendizagem.php?scroll=750&local=cad_cronograma_aprendizagem" id="formCronograma">
                    <section id="input-with-icons">
                        <div class="row match-height">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Cadastrar Cronograma Aprendizagem</h4>
                                        
                                    </div>

                                    <div class="card-body">
                                        <form method="POST" action="sala_cad.php">
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <h6>Turma</h6>
                                                    <div class="form-group">
                                                        <h4>
                                                            <?php
                                                            echo $nome_turma . " - " . $numero_turma;
                                                            ?>
                                                            <input type="hidden" value="<?php echo $id_turma; ?>" name="id_turma">
                                                            <input type="hidden" value="<?php echo $id_curso; ?>" name="id_curso">
                                                            <input type="hidden" value="<?php echo $mes; ?>" name="mes">
                                                            <input type="hidden" value="<?php echo $ano; ?>" name="ano">
                                                            <input type="hidden" value="cad_cronograma_aprendizagem" name="local">
                                                            <input type="hidden" value="" name="dia" id="dia">
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* Professor</h6>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" name="professor" id="professor">
                                                            <optgroup label="Professor">
                                                                <option value="<?php echo $id_professor; ?>"><?php echo $nomeProfessor; ?></option>
                                                                <?php
                                                                while ($resultado_professor = mysqli_fetch_assoc($result_professor)) {
                                                                    echo "<option value=" . $resultado_professor['id_professor'] . ">" . $resultado_professor['nome'] . "</option>";
                                                                }
                                                                ?>

                                                            </optgroup>
                                                        </select>
                                                        <div id="dProfessor" class="invalid-feedback" style="display: none;">
                                                            <i class="bx bx-radio-circle"></i>
                                                            Necessário selecionar um professor
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* UC/Componente</h6>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" name="uc" id="uc">
                                                            <optgroup label="UCs/Componentes">
                                                                <option value="<?php echo $id_uc; ?>"><?php echo $nomeUc; ?></option>
                                                                <?php
                                                                while ($resultado_uc_com = mysqli_fetch_assoc($result_uc_com)) {
                                                                    echo "<option value=" . $resultado_uc_com['id_uc_componente'] . ">" . $resultado_uc_com['nome'] . " - <span>" . $resultado_uc_com['carga_horaria'] . "</span>hs</option>";
                                                                }
                                                                ?>
                                                            </optgroup>
                                                        </select>
                                                        <div id="dUc" class="invalid-feedback" style="display: none;">
                                                            <i class="bx bx-radio-circle"></i>
                                                            Necessário selecionar uma UC
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* Local</h6>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" name="id_local" id="id_local">
                                                            <optgroup label="UCs/Componentes">
                                                                <option value="<?php echo $id_local; ?>"><?php echo $nomeLocal." - ".$numeroLocal; ?></option>
                                                                <?php
                                                                while ($resultado_local = mysqli_fetch_assoc($result_local)) {
                                                                    echo "<option value=" . $resultado_local['id_local'] . ">" . $resultado_local['nome'] . " - " . $resultado_local['numero'] . "</option>";
                                                                }
                                                                ?>
                                                            </optgroup>
                                                        </select>
                                                        <div id="dLocal" class="invalid-feedback" style="display: none;">
                                                            <i class="bx bx-radio-circle"></i>
                                                            Necessário selecionar um local
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <b>Unidade Curricular:</b> <?php echo $nome_uc_componente; ?><br>
                                                    <b>Horas Totais:</b> <?php echo $horas_totais_uc; ?><br>
                                                    <b>Horas Cadastradas:</b> <?php echo $horas_cadastradas_uc; ?><br>
                                                    <b>Horas a Cadastrar:</b> <?php echo $horas_a_cadastrar_uc; ?><br>
                                                    <b>Total de Encontros:</b> <?php echo $total_encontros_uc; ?><br>
                                                    <b>Encontros Cadastrados:</b> <?php echo $encontros_cadastrados_uc; ?><br>
                                                    <b>Encontros a Cadastrar:</b> <?php echo $encontros_cadastrar_uc; ?><br>
                                                </div>
                                                <div class="col-sm-6 mb-4">
                                                    <b>Curso:</b> <?php echo $nome_curso; ?><br>
                                                    <b>Horas Totais:</b> <?php echo $horas_totais_curso; ?><br>
                                                    <b>Horas Cadastradas:</b> <?php echo $horas_cadastradas ?><br>
                                                    <b>Horas a Cadastrar:</b> <?php echo $horas_a_cadastrar_curso ?><br>
                                                    <b>Total de Encontros:</b> <?php echo $total_encontros ?><br>
                                                    <b>Encontros Cadastrados:</b> <?php echo $encontros_cadastrados; ?><br>
                                                    <b>Encontros a Cadastrar:</b> <?php echo $encontros_cadastrar ?><br>
                                                </div>
                                            </div>                                            
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    * Campos necessários
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
                <!-- Formulário -->
            <div class="card">
                <div class="card-body">
                    <?php
                        //gera calendario
                        echo calendario($result_calendario_aulas, $resultFormatacao, $resultFeriados, $id_turma, $id_curso, $id_professor, $id_uc, $id_local, $mes, $ano);
                    ?>
                </div>
            </div>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2021 Senac SM</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php echo $scripts; ?>

    <script>
        //Pega o elemento form
        var form = document.getElementById('formCronograma');

        //Pega os elementos de formulário
        var professor = document.getElementById('professor');
        var uc = document.getElementById('uc');
        var dataInicio = document.getElementById('data_inicio');
        var local = document.getElementById('local');
        var ch = document.getElementById('ch_diaria');

        //Pega os elementos das div
        var dProfessor = document.getElementById('dProfessor');
        var dUc = document.getElementById('dUc');
        var dDataInicio = document.getElementById('dDataInicio');
        var dLocal = document.getElementById('dLocal');
        var dCh = document.getElementById('dCh');

        function validar() {

            // Cria a validação
            if (professor.value == '0' || uc.value == '0' || dataInicio.value == '' || local.value == '0' || ch.value == '') {
                if(professor == '0'){
                    //professor.className += " is-invalid";
                    dProfessor.style.display = "block";
                }

                if(uc == '0'){
                    //uc.className += " is-invalid";
                    dUc.style.display = "block";
                }

                if(dataInicio == ''){
                    //dataInicio.className += " is-invalid";
                    dDataInicio.style.display = "block";
                }

                if(local == '0'){
                    //local.className += " is-invalid";
                    dLocal.style.display = "block";
                }

                if(ch == ''){
                    //ch.className += " is-invalid";
                    dCh.style.display = "block";
                }

            } else {

                form.submit();
            }

        }
    </script>
</body>

</html>
