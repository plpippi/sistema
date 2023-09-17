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

$id_professor = $_GET['id_professor'];

$query_aulas = "SELECT turma.numero_turma AS numero, turma.cor AS cor, aula.id_aula AS id, aula.dia AS data, aula.hora_inicio AS inicio, aula.hora_termino AS termino, professor.nome AS nome, 
uc_componente.nome as uc FROM aula INNER JOIN turma ON aula.id_turma = turma.id_turma INNER JOIN professor ON aula.id_professor = professor.id_professor 
INNER JOIN uc_componente ON aula.id_uc_componente = uc_componente.id_uc_componente WHERE aula.id_professor = $id_professor ORDER BY aula.dia ASC";
$result_aula = mysqli_query($link, $query_aulas);

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
                    
                    <?php while ($linha_aula = mysqli_fetch_assoc($result_aula)) { ?> {
                            id: '<?php echo $linha_aula['id']; ?>',
                            title: '<?php echo  $linha_aula['nome']; ?> <?php echo $linha_aula['uc']." Turma:".$linha_aula['numero']; ?>',
                            start: '<?php echo $linha_aula['data']; ?>T<?php echo $linha_aula['inicio']; ?>',
                            end: '<?php echo $linha_aula['data']; ?>T<?php echo $linha_aula['termino']; ?>',
                            color: '<?php echo $linha_aula['cor']; ?>',
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
