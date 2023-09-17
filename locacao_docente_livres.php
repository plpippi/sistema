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
include('util.php');
include('notifica_reserva.php');
include('menu.php');

$query = "SELECT * FROM professor ORDER BY id_professor ASC";
$result = mysqli_query($link, $query);

$inicio = $_POST['data_inicio'];
$fim = $_POST['data_fim'];
$queryConsulta = "SELECT * FROM professor WHERE professor.id_professor 
NOT IN (SELECT aula.id_professor FROM aula WHERE aula.dia >= '$inicio' AND aula.dia <= '$fim') ORDER BY professor.id_professor ASC";
$resultConsulta = mysqli_query($link, $queryConsulta);


// Query para selecionar docentes que não tem aula em um periodo
// SELECT * FROM professor WHERE professor.id_professor NOT IN (SELECT aula.id_professor FROM aula WHERE aula.dia >= '2023-03-01' AND aula.dia <= '2023-03-31')

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
                <form method="POST" action="locacao_docente_livres.php?pagina=relatorio">
                    <section id="input-with-icons">
                        <div class="row match-height">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Relatório Horas Professor</h4>
                                    </div>

                                    <div class="card-body">
                                        <form method="POST" action="horas_docente.php?pagina=relatorio" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-sm-4 mb-4">
                                                    <h6>Tipo de Consulta</h6>
                                                    <div class="form-group">
                                                        <a href="locacao_docente.php" class="btn btn-secondary">Docentes Com Aulas</a>
                                                        <a href="locacao_docente_livres.php" class="btn btn-danger">Docentes Livres</a>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 mb-4">
                                                    <h6>Data de Inicio</h6>
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" name="data_inicio" style="height: 48px !important;">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 mb-4">
                                                    <h6>Data de Término</h6>
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" name="data_fim" style="height: 48px !important;">
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
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Professor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    ?>
                                    <?php while ($resultadoConsulta = mysqli_fetch_assoc($resultConsulta)) { ?>
                                        <?php
                                        $hora = explode(':', $resultadoConsulta['horas']);
                                        $total = $total + intval($hora[0]);
                                        ?>
                                        <tr>
                                            <td><?php echo $resultadoConsulta['id_professor']; ?></td>
                                            <td><?php echo $resultadoConsulta['nome']; ?></td>
                                        </tr>
                                    <?php } ?>
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