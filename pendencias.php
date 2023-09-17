<?php

// Inicio sessão
session_start();

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

include('util.php');
include('conn/conn.php');
include('menu.php');

$hora_inicio = $_GET['hora_inicio'];
$query = "SELECT DISTINCT * FROM aula INNER JOIN professor ON aula.id_professor = professor.id_professor 
WHERE '$hora_inicio' >= aula.hora_inicio AND '$hora_inicio' <= aula.hora_termino ORDER BY aula.dia ASC ";
$result = mysqli_query($link, $query);
// query cronograma: SELECT turma.id_turma, turma.numero_turma, aula.id_uc_componente, COUNT(*) AS qunt_aulas, MIN(aula.dia) AS menor_data, MAX(aula.dia) AS maior_data FROM turma INNER JOIN aula ON turma.id_turma = aula.id_turma


?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <?php echo $cabecalho; ?>
    <style>
        .img {
            width: 40px;
            height: 40px;
            border-radius: 50px;
        }
    </style>
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
                            <h3>Cronograma</h3>
                            <p class="text-subtitle text-muted">Lista de Turmas</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Cronogramas</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Professor</th>
                                        <th>Turma</th>
                                        <th>Dia</th>
                                        <th>Hora de Inicio</th>
                                        <th>Hora de Término</th>
                                        <th>Ajustar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data_anterior = 0;
                                    $professor_anterior = 0;
                                    while ($resultado = mysqli_fetch_assoc($result)) { ?>
                                        <?php
                                        if ($data_anterior == $resultado['dia'] and $professor_anterior == $resultado['id_professor']) {
                                            echo "<tr class='cor'>";
                                        } else {
                                            echo "<tr>";
                                        }
                                        ?>
                                        <td><?php echo $resultado['id_aula']; ?></td>
                                        <td><?php echo $resultado['nome']; ?></td>
                                        <td><?php echo $resultado['id_turma']; ?></td>
                                        <td><?php echo converte_data($resultado['dia']); ?></td>
                                        <td><?php echo $resultado['hora_inicio']; ?></td>
                                        <td><?php echo $resultado['hora_termino']; ?></td>
                                        <?php
                                        if ($data_anterior == $resultado['dia'] and $professor_anterior == $resultado['id_professor']) {
                                            echo "<td><a href='ajustar_pendencia.php?dia=" . $resultado['dia'] . "' class='btn btn-danger btn-sm'>Ajustar Pendencia</a></td>";
                                        } else {
                                            echo "<td><span class='btn btn-primary btn-sm'>Sem Pendencias</span></td>";
                                        }
                                        ?>
                                        </tr>
                                    <?php
                                        $data_anterior = $resultado['dia'];
                                        $professor_anterior = $resultado['id_professor'];
                                    }
                                    ?>
                                </tbody>
                            </table>
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