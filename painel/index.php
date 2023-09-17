<?php
error_reporting(0);
session_start();

include('../conn/conn.php');
include('../util.php');

date_default_timezone_set('America/sao_paulo');

$hora = date('H:i:s', strtotime('+1 hours'));
$dia = date('Y-m-d');

//echo $hora." ".$dia;

$query = "SELECT local.visualiza, local.nome AS localn, local.numero AS numero, aula.dia AS dia, aula.hora_inicio AS inicio, aula.hora_termino AS termino, professor.nome AS professor, 
professor.imagem AS imagem, aula.id_professor AS id_professor, turma.numero_turma AS turma, uc_componente.nome as uc FROM aula 
INNER JOIN professor ON aula.id_professor = professor.id_professor INNER JOIN turma ON aula.id_turma = turma.id_turma 
INNER JOIN uc_componente ON aula.id_uc_componente = uc_componente.id_uc_componente 
INNER JOIN local ON aula.id_local = local.id_local WHERE ('$hora' >= aula.hora_inicio AND '$hora' <= aula.hora_termino) AND aula.dia = '$dia' AND local.visualiza = 1";
$result = mysqli_query($link, $query);
$resultUm = mysqli_query($link, $query);
$resultDois = mysqli_query($link, $query);

$queryAviso = "SELECT * FROM avisos WHERE data = '$dia' AND (inicio <= '$hora' AND fim >= '$hora') ORDER BY id_aviso ASC ";
$resultAviso = mysqli_query($link, $queryAviso);
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Painel de Salas Senac Santa Maria</title>

    <style>
        .logo {
            height: 80px;
        }
        .hora{
            text-align: right;
            line-height: 80px;
            font-size: 40px;
        }
        .avisos{
            font-size: 40px;
            line-height: 80px;
            background-color: #FFFFFF;
            z-index: 100;
        }
        .letreiro{
            position: absolute;
            width: 100%;
            margin: 0;
            font-size: 40px;
            line-height: 80px;
            transform: translateX(100%);
            animation: rolagem 30s linear infinite;
        }
        @keyframes rolagem {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }
    </style>

    <script>
        function recaregar() {
            setTimeout(function() {
                window.location.reload(1);
            }, 120000);
        }
    </script>

  </head>
  <body onload="recaregar()">
    <div class="container-fluid">
        <div class="row">
            <div class="col col-md-4">
                <img src="../imagens/senac_logo_2.png" class="logo">
            </div>
            <div class="col col-md-8">
                <div class="hora" id="data-hora"></div>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-2 avisos">
                Aviso(s):
            </div>
            <div class="col col-md-10">
                <span class="letreiro">
                    <?php while($linhaAviso = mysqli_fetch_assoc($resultAviso)){?>
                    <?php echo $linhaAviso['aviso'].", "; ?>
                    <?php } ?>
                </span>
            </div>
        </row>
    </div>
    <div class="container">
    <?php
            $count = 0; 
            while ($resultado = mysqli_fetch_assoc($result)) {
            if($count == 0){
                echo "<div class='row'>";
            }
            ?>
                <div class="col col-md-3 justify-content-center" style="margin-bottom: 15px;">
                    <div class="card" style="width: 14rem;">
                        <img src="../imagens/users/<?php echo $resultado['imagem']; ?>" class="card-img-top" style="height: 250px">
                        <div class="card-body">
                            <p class="card-text">
                                <b><?php echo ajusta_texto($resultado['professor']); ?></b><br>
                                Turma: <?php echo $resultado['turma']; ?><br>
                                Sala: <?php echo $resultado['numero']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php $count+=1; ?>
            <?php
            if($count == 4){
                echo "</div>";
            }
            if($count==4){
                $count = 0;
            }
            } 
            ?>
    </div>

    <script>
        // Função para formatar 1 em 01
        const zeroFill = n => {
            return ('0' + n).slice(-2);
        }

        // Cria intervalo
        const interval = setInterval(() => {
            // Pega o horário atual
            const now = new Date();

            // Formata a data conforme dd/mm/aaaa hh:ii:ss
            const dataHora = zeroFill(now.getUTCDate()) + '/' + zeroFill((now.getMonth() + 1)) + '/' + now
                .getFullYear() + ' ' + zeroFill(now.getHours()) + ':' + zeroFill(now.getMinutes()) + ':' + zeroFill(
                    now.getSeconds());

            // Exibe na tela usando a div#data-hora
            document.getElementById('data-hora').innerHTML = dataHora;
        }, 1000);
    </script>
    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
