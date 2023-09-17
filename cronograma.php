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

$dia = date('Y-m-d');

// query antiga ativar se der erro $query = "SELECT turma.id_turma, turma.hora_inicio, turma.numero_turma, aula.id_uc_componente, aula.hora_inicio, aula.hora_termino, COUNT(*) AS qunt_aulas, MIN(aula.dia) AS menor_data, MAX(aula.dia) AS maior_data FROM turma INNER JOIN aula ON turma.id_turma = aula.id_turma GROUP BY aula.id_turma ORDER BY id_turma ASC";
$query = "SELECT turma.pesquisa, turma.id_turma, turma.nome AS turma, turma.hora_inicio, turma.numero_turma, aula.id_uc_componente, aula.hora_inicio, aula.hora_termino, COUNT(*) AS qunt_aulas, 
MIN(aula.dia) AS menor_data, MAX(aula.dia) AS maior_data FROM turma INNER JOIN aula ON turma.id_turma = aula.id_turma WHERE aula.dia >= '$dia' 
GROUP BY aula.id_turma, turma.id_turma, turma.hora_inicio, turma.numero_turma, aula.hora_inicio, aula.hora_termino ORDER BY id_turma ASC;";
$result = mysqli_query($link, $query);


$query_quant_total = "SELECT turma.pesquisa, turma.id_turma, turma.numero_turma, MIN(aula.dia) AS menor_data, MAX(aula.dia) AS maior_data, COUNT(*) AS qunt_aulas FROM turma INNER JOIN aula ON turma.id_turma = aula.id_turma 
GROUP BY aula.id_turma, turma.id_turma, turma.hora_inicio, turma.numero_turma, aula.hora_inicio, aula.hora_termino ORDER BY id_turma ASC";
$result_total = mysqli_query($link, $query_quant_total);
while ($resultado_total = mysqli_fetch_assoc($result_total)) {
    //$total_aulas[$resultado_total['numero_turma']."".$resultado_total['menor_data']."".$resultado_total['maior_data']] = $resultado_total['qunt_aulas'];
    $total_aulas[$resultado_total['id_turma']] = $resultado_total['qunt_aulas'];
}

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
                                        <th>Turma</th>
                                        <th>Data de Inicio</th>
                                        <th>Data de Término</th>
                                        <th>Aulas até Hoje</th>
                                        <th>% Concluida</th>
                                        <th>Pendencias</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($resultado = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?php echo $resultado['id_turma']; ?></td>
                                            <td><?php echo $resultado['turma']." - ".$resultado['numero_turma']; ?></td>
                                            <td><?php echo converte_data($resultado['menor_data']); ?></td>
                                            <td><?php echo converte_data($resultado['maior_data']); ?></td>
                                            <td><?php echo $resultado['qunt_aulas'] . ' aulas'; ?></td>
                                            <td>
                                                <?php 
                                                    $totalGeral = $total_aulas[$resultado['id_turma']]; 
                                                    $totalHoje = $resultado['qunt_aulas'];
                                                    $porcentagem = round(($totalHoje * 100) / $totalGeral, 0);
                                                    if ($resultado['pesquisa'] == 1){
                                                        $barra = " bg-success";
                                                    }else{
                                                        if($porcentagem >= 70){
                                                            $barra = " bg-warning";
                                                        }else{
                                                            $barra = "";
                                                        }
                                                    }
                                                ?>
                                                <div class="progress">
                                                    <div class="progress-bar<?php echo $barra; ?>" role="progressbar" style='width: <?php echo $porcentagem; ?>%;' aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo $porcentagem."%"; ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $hora_inicio = $resultado["hora_inicio"];
                                                $query_pendencias = "SELECT DISTINCT *, COUNT(*) AS total FROM aula INNER JOIN professor ON aula.id_professor = professor.id_professor WHERE '$hora_inicio' >= aula.hora_inicio AND '$hora_inicio' <= aula.hora_termino GROUP BY dia, aula.id_professor HAVING count(dia) > 1 AND COUNT(aula.id_professor) > 1 ORDER BY aula.dia ASC ";
                                                $result_pendencias = mysqli_query($link, $query_pendencias);
                                                while ($resultado_pendencias = mysqli_fetch_assoc($result_pendencias)) {
                                                    //echo $resultado_pendencias['total'];
                                                    $total = $resultado_pendencias['total'];
                                                }
                                                if ($total >= 2) {
                                                    echo "<a href='pendencias.php?hora_inicio=" . $resultado['hora_inicio'] . "' class='btn btn-danger btn-sm'>Com Pendencias</span>";
                                                } else {
                                                    echo "<span class='badge bg-success'>Sem Pendencias</span>";
                                                }
                                                //echo $total;
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Ações
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="cad_cronograma.php?acao=1&id_turma=<?php echo $resultado['id_turma']; ?>"><i class="bi bi-pencil-square"></i>
                                                        &nbsp;Editar</a>
                                                    <a class="dropdown-item" href="exclui_cronograma.php?id_turma=<?php echo $resultado['id_turma']; ?>"><i class="bi bi-trash-fill"></i>
                                                        &nbsp;Excluir</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="salva_cronograma.php?turma=<?php echo $resultado['id_turma']; ?>&nome=<?php echo $resultado['turma']." - ".$resultado['numero_turma']; ?>"><i class="bi bi-file-pdf-fill"></i>
                                                        &nbsp;Gerar Cronograma</a>
                                                    <a class="dropdown-item" href="ver_cronograma.php?turma=<?php echo $resultado['id_turma']; ?>&nome=<?php echo $resultado['numero_turma']; ?>"><i class="bi bi-eye-fill"></i>
                                                        &nbsp;Visualizar Cronograma</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="pesquisa.php?turma=<?php echo $resultado['id_turma']; ?>"><i class="bi bi-award-fill"></i>
                                                        &nbsp;Pesquisa Realizada</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>
            </div>

            <footer>
                <div class='footer clearfix mb-0 text-muted'>
                    <div class='float-start'>
                        <p>2021 Senac SM</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php echo $scripts; ?>
</body>

</html>

