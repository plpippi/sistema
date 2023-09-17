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

$id_professor = $_POST['id_professor'];
$inicio = $_POST['data_inicio'];
$fim = $_POST['data_fim'];
$queryConsulta = "SELECT aula.id_aula AS id, aula.dia, TIMEDIFF(aula.hora_termino, aula.hora_inicio) AS horas, professor.nome AS nome, turma.numero_turma AS numero FROM aula INNER JOIN professor ON aula.id_professor = professor.id_professor INNER JOIN turma ON aula.id_turma = turma.id_turma WHERE aula.id_professor = $id_professor AND (aula.dia >= '$inicio' AND aula.dia <= '$fim') ORDER BY id ASC";
$resultConsulta = mysqli_query($link, $queryConsulta);

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
                <form method="POST" action="horas_docente.php?pagina=relatorio">
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
                                        <th>Turma</th>
                                        <th>Dia</th>
                                        <th>Horas</th>
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
					$nome = $resultadoConsulta['nome'];
                                        ?>
                                        <tr>
                                            <td><?php echo $resultadoConsulta['id']; ?></td>
                                            <td><?php echo $resultadoConsulta['nome']; ?></td>
                                            <td><?php echo $resultadoConsulta['numero']; ?></td>
                                            <td><?php echo  converte_data($resultadoConsulta['dia']); ?></td>
                                            <td><?php echo intval($hora[0]) . " hs"; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="4" align="right"><b>Total:</b> </td>
                                        <td><b><?php echo $total . " hs"; ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
			    <a class="btn btn-primary" href="salva_horas_docente.php?id_professor=<?php echo $id_professor; ?>&inicio=<?php echo $inicio ?>&fim=<?php echo $fim?>&nome=<?php echo $nome; ?>"><i class="bi bi-file-pdf-fill"></i>&nbsp;Salva Relatório</a>
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
