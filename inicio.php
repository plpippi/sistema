<?php

// Inicio sessão
session_start();

error_reporting(0);
ini_set("display_errors", 0);
setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['user'])) {
    header('Location: index.html');
    exit;
}
$user = $_SESSION['user'];
$nome_user = $_SESSION['nome_user'];
$imagem_user = $_SESSION['imagem'];
$acesso_user = $_SESSION['acesso'];
// Fim sessão

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

$ano = date('Y');
$query = "SELECT *, COUNT(*) AS total, MONTH(aula.dia) AS mes FROM aula WHERE YEAR(aula.dia) = $ano GROUP BY MONTH(dia)";
$result = mysqli_query($link, $query);
while ($resultado = mysqli_fetch_assoc($result)) {
    $array_mes[] = $resultado['mes'];
    $array_total[] = $resultado['total'];
}

$queryTotalProfessor = "SELECT COUNT(*) AS total FROM professor";
$resultTotalProfessor = mysqli_query($link, $queryTotalProfessor);
while ($resultadoTotalProfessor = mysqli_fetch_assoc($resultTotalProfessor)) {
    $totalProfessor = $resultadoTotalProfessor['total'];
}

$mes = date('m');
$queryTotalAulas = "SELECT COUNT(*) AS total FROM aula WHERE MONTH(aula.dia) <= $mes";
$resultTotalAulas = mysqli_query($link, $queryTotalAulas);
while ($resultadoTotalAulas = mysqli_fetch_assoc($resultTotalAulas)) {
    $totalAulas = $resultadoTotalAulas['total'];
}

$queryMostraTurma = "SELECT * FROM `turma` ORDER BY id_turma DESC LIMIT 20";
$resultMostraTurma = mysqli_query($link, $queryMostraTurma);

$diaAtual = date('Y-m-d');;
$queryTotalTurmas = "SELECT COUNT(*) AS total FROM `turma` WHERE turma.data_inicio <= '$diaAtual' AND status = 1";
$resultTotalTurmas = mysqli_query($link, $queryTotalTurmas);
while ($resultadoTotalTurmas = mysqli_fetch_assoc($resultTotalTurmas)) {
    $totalTurmas = $resultadoTotalTurmas['total'];
}

$queryChart = "SELECT COUNT(*) AS total, MONTH(dia) AS mes FROM aula WHERE YEAR(aula.dia) = $ano GROUP BY MONTH(aula.dia)";
$resultChart = mysqli_query($link, $queryChart);

$queryChartValor = "SELECT COUNT(*) AS total, MONTH(dia) AS mes FROM aula WHERE YEAR(aula.dia) = $ano GROUP BY MONTH(aula.dia)";
$resultChartValor = mysqli_query($link, $queryChart);

$query_turma_abertas = "SELECT COUNT(MONTH(turma.data_inicio)) AS quantidade, MONTH(turma.data_inicio) AS mes FROM turma WHERE YEAR(turma.data_inicio) = $ano GROUP BY MONTH(turma.data_inicio)";
$result_turmas_abertas = mysqli_query($link, $query_turma_abertas);

$query_turma_abertas_valor = "SELECT COUNT(MONTH(turma.data_inicio)) AS quantidade, MONTH(turma.data_inicio) AS mes FROM turma WHERE YEAR(turma.data_inicio) = $ano GROUP BY MONTH(turma.data_inicio)";
$result_turmas_abertas_valor = mysqli_query($link, $query_turma_abertas_valor);

$query_local_disponivel = "SELECT * FROM local WHERE local.id_local NOT IN (SELECT aula.id_local FROM aula WHERE aula.dia = '$diaAtual') AND local.id_cidade = 2
 ORDER BY local.id_local ASC";
$result_local_disponivel = mysqli_query($link, $query_local_disponivel);

$meses = array(
    1 => "Janeiro",
    2 => "Fevereiro",
    3 => "Março",
    4 => "Abril",
    5 => "Maio",
    6 => "Junho",
    7 => "Julho",
    8 => "Agosto",
    9 => "Setembro",
    10 => "Outubro",
    11 => "Novembro",
    12 => "Dezembro",
);

?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <?php echo $cabecalho; ?>
</head>

<body>
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
                <h3>DashBoard Gerencial</h3>
            </div>

            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-9">
                        <div class="row">
                            <div class="col-6 col-lg-4 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon red">
                                                    <i class="iconly-boldPaper-Plus"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Aulas Ministradas até</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $meses[ltrim($mes, '0')] . ": " . $totalAulas; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-4 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon blue">
                                                    <i class="iconly-boldProfile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Professores Cadastrados</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $totalProfessor; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-4 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon green">
                                                    <i class="iconly-boldTime-Circle"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Turmas em andamento</h6>
                                                <h6 class="font-extrabold mb-0"><?php echo $totalTurmas; ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12 col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Aulas Ministradas</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <h5 class="mb-0 ms-3"><?php echo $ano; ?></h5><br>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <!-- Start Canvas -->
                                                <canvas id="grafico" height="65px"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Turmas Abertas</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <h5 class="mb-0 ms-3"><?php echo $ano; ?></h5><br>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <!-- Start Canvas -->
                                                <canvas id="graficoA" height="45px"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-body py-4 px-5">
                                <div class="d-flex align-items-center">
                                    <div class="ms-3 name" style="height: 380px; overflow-y: scroll;">
                                        <h5 class="font-bold">Relação de Turmas</h5>
                                        <table class="table table-striped">
                                        <?php while ($resultadoMostraTurma = mysqli_fetch_assoc($resultMostraTurma)) { ?>
                                        <tr><td><a href="cad_cronograma.php?id_turma=<?php echo $resultadoMostraTurma['id_turma']; ?>&curso=<?php echo $resultadoMostraTurma['curso'] ?>"><i class="bi bi-caret-right-fill"></i>&nbsp;&nbsp;<?php echo $resultadoMostraTurma['nome']." - ".$resultadoMostraTurma['numero_turma']; ?></a></td></tr>
                                        <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
			</div>
                        <div class="card">
                            <div class="card-body py-4 px-5">
                                <div class="d-flex align-items-center">
                                    <div class="ms-3 name" style="height: 300px; overflow-y:scroll;">
                                        <h5 class="font-bold">Salas Disponiveis Hoje</h5>
                                        <table class="table table-striped">
                                        <?php while ($resultadoSalas = mysqli_fetch_assoc($result_local_disponivel)) { ?>
                                        <tr><td><?php echo $resultadoSalas['numero']; ?> - <?php echo $resultadoSalas['nome'] ?></td></tr>
                                        <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section><br><br><br><br>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>
        var ctx = document.getElementById('grafico').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php while ($linhaChart = mysqli_fetch_assoc($resultChart)) { ?> '<?php echo $meses[$linhaChart['mes']]; ?>',
                    <?php } ?>
                ],
                datasets: [{
                    label: 'Aulas Ministradas',
                    data: [
                        <?php while ($linhaChartValor = mysqli_fetch_assoc($resultChartValor)) { ?>
                            <?php echo $linhaChartValor['total']; ?>,
                        <?php } ?>
                    ],
                    backgroundColor: [
                        'rgba(67, 94, 190, 1)',
                    ],
                    borderColor: [
                        'rgba(67, 94, 190, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
            }
        });
        
        var ctxa = document.getElementById('graficoA').getContext('2d');
        var myChart = new Chart(ctxa, {
            type: 'bar',
            data: {
                labels: [
                    <?php while ($linhaTurmasAbertas = mysqli_fetch_assoc($result_turmas_abertas)) { ?> '<?php echo $meses[$linhaTurmasAbertas['mes']]; ?>',
                    <?php } ?>
                ],
                datasets: [{
                    label: 'Turmas Abertas',
                    data: [
                        <?php while ($linhaTurmasAbertasValor = mysqli_fetch_assoc($result_turmas_abertas_valor)) { ?>
                            <?php echo $linhaTurmasAbertasValor['quantidade']; ?>,
                        <?php } ?>
                    ],
                    backgroundColor: [
                        'rgba(233, 108, 108, 1)',
                    ],
                    borderColor: [
                        'rgba(233, 108, 108, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
            }
        });
    </script>

</body>

</html>
