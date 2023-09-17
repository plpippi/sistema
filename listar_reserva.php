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

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

$query_reservas = "SELECT reserva.id_reserva AS id, reserva.data AS dia, reserva.hora_retirada AS inicio, reserva.hora_devolucao AS fim, 
professor.nome AS nome, equipamento.tipo AS tipo, equipamento.numero AS numero FROM reserva INNER JOIN 
professor ON reserva.id_pessoa = professor.id_professor INNER JOIN equipamento ON reserva.id_equipamento = 
equipamento.id_equipamento ORDER BY reserva.id_reserva ASC";
$result_reservas = mysqli_query($link, $query_reservas);

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
                    <?php while ($linha_reservas = mysqli_fetch_assoc($result_reservas)) { ?> {
                            id: '<?php echo $linha_reservas['id']; ?>',
                            title: '<?php echo  $linha_reservas['nome']." - ".$linha_reservas['tipo']." ".$linha_reservas['numero']; ?>',
                            start: '<?php echo $linha_reservas['dia']; ?>T<?php echo $linha_reservas['inicio']; ?>',
                            end: '<?php echo $linha_reservas['dia']; ?>T<?php echo $linha_reservas['fim']; ?>',
                            color: '#56AAAA',
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
                            <p class="text-subtitle text-muted">Calendario de Reservas</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Reservas</li>
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
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <?php echo $scripts; ?>
</body>

</html>