<?php

// Inicio sessão
error_reporting(0);
session_start();

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

$query_feriados = "SELECT dias_n_letivos.*, cidade.nome_cidade AS cidade FROM dias_n_letivos INNER JOIN cidade ON dias_n_letivos.id_cidade = cidade.id_cidade ORDER BY dia ASC ";
$result_feriados = mysqli_query($link, $query_feriados);

$query_feriados_qualquer = "SELECT * FROM dias_n_letivos WHERE id_cidade = 0 ORDER BY dia ASC";
$result_feriados_qualquer = mysqli_query($link, $query_feriados_qualquer);

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <?php echo $cabecalho; ?>
    <!-- Full Callendar-->
    <link href='css/fullcalendar.min.css' rel='stylesheet' />
    <link href='css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
    <link href='css/personalizado.css' rel='stylesheet' />
    <script src='js/moment.min.js'></script>
    <script src='js/jquery.min.js'></script>
    <script src='js/fullcalendar.min.js'></script>
    <script src='locale/pt-br.js'></script>

    <!-- Criação do Calendario -->
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay',
                },
                height: 500,
                aspectRatio: 1.8,
                defaultDate: Date(),
                //navLinks: true, // can click day/week names to navigate views
                //editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: [

                    <?php while ($linha_feriado = mysqli_fetch_assoc($result_feriados)) { ?> {
                            id: '<?php echo $linha_feriado['id_dia_n_letivo']; ?>',
                            title: '<?php echo  $linha_feriado['cidade']." | ".$linha_feriado['descricao']; ?>',
                            start: '<?php echo $linha_feriado['dia']; ?>',
                            end: '<?php echo $linha_feriado['dia']; ?>',
                            color: '#FF5656',
                        },
                    <?php } ?>

                    <?php while ($linha_qualquer = mysqli_fetch_assoc($result_feriados_qualquer)) { ?> {
                            id: '<?php echo $linha_qualquer['id_dia_n_letivo']; ?>',
                            title: '<?php echo  $linha_qualquer['descricao']; ?>',
                            start: '<?php echo $linha_qualquer['dia']; ?>',
                            end: '<?php echo $linha_qualquer['dia']; ?>',
                            color: '#FF5656',
                        },
                    <?php } ?>
                ]
            });
        });
    </script>
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
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Senac</h3>
                            <p class="text-subtitle text-muted">Calendario da Turma <?php echo $numero_turma ?></p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Calendario</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h3>Calendario</h3>
                        </div>
                        <div class="card-body">
                            <div id='calendar' style="max-width: 100% !important;"></div>
                        </div>
                    </div>

                </section>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2021 Senac SM</p>
                        <?php
                            print_r($inicio)
                        ?>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <?php echo $scripts; ?>
</body>

</html>