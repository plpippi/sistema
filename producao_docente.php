<?php

// Inicio sessão
session_start();

error_reporting(0);
ini_set('display_errors', 0);

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

$query = "SELECT * FROM professor ORDER BY id_professor ASC";
$result = mysqli_query($link, $query);

$id_professor = $_POST['id_professor'];
$converte_ano = explode('-', $_POST['ano']);
$ano = $converte_ano[0];

$queryChart = "SELECT COUNT(*) AS total, MONTH(dia) AS mes FROM aula WHERE aula.id_professor = $id_professor AND YEAR(aula.dia) = $ano GROUP BY MONTH(aula.dia)";
$resultChart = mysqli_query($link, $queryChart);

$queryChartValor = "SELECT COUNT(*) AS total, MONTH(dia) AS mes FROM aula WHERE aula.id_professor = $id_professor AND YEAR(aula.dia) = $ano GROUP BY MONTH(aula.dia)";
$resultChartValor = mysqli_query($link, $queryChart);

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
)

?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <?php echo $cabecalho; ?>
    <!-- Charts JS -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js"></script>
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
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Relatórios</h3>
                            <p class="text-subtitle text-muted">Gere um Relatório</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Relatórios</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Inicio Formulário -->
                <form method="POST" action="producao_docente.php?pagina=relatorio">
                    <section id="input-with-icons">
                        <div class="row match-height">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Relatório de Aulas Professor</h4>
                                    </div>

                                    <div class="card-body">
                                        <form method="POST" action="horas_docente.php?pagina=relatorio" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <h6>Professor</h6>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" name="id_professor">
                                                            <optgroup label='Professores'>
                                                                <?php while ($linha = mysqli_fetch_assoc($result)) { ?>
                                                                    <option value="<?php echo $linha['id_professor']; ?>"><?php echo $linha['nome']; ?></option>
                                                                <?php } ?>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-4">
                                                    <h6>Ano</h6>
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" name="ano" style="height: 48px !important;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Consultar</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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

                <section class="section">
                    <!-- Start Canvas -->
                    <canvas id="grafico" height="65px"></canvas>
                </section>
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
        var ctx = document.getElementById('grafico').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php while ($linhaChart = mysqli_fetch_assoc($resultChart)) { ?> '<?php echo $meses[$linhaChart['mes']]; ?>',
                    <?php } ?>
                ],
                datasets: [{
                    label: 'Aulas Programasdas/Ministradas',
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
    </script>
</body>

</html>