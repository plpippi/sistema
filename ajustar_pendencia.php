<?php

include('conn/conn.php');
include('menu.php');
$dia = $_GET['dia'];
$query = "SELECT DISTINCT * FROM aula INNER JOIN professor ON aula.id_professor = professor.id_professor WHERE aula.dia = '$dia' ORDER BY aula.dia ASC";

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
                                    <?php while ($resultado = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?php echo $resultado['id_aula']; ?></td>
                                            <td><?php echo $resultado['nome']; ?></td>
                                            <td><?php echo $resultado['id_turma']; ?></td>
                                            <td><?php echo $resultado['dia']; ?></td>
                                            <td><?php echo $resultado['hora_inicio']; ?></td>
                                            <td><?php echo $resultado['hora_termino']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opções</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="editar_pendencia.php"><i class="bi bi-pencil-square"></i> &nbsp;Editar</a>
                                                    <a class="dropdown-item" href="excluir.php?local=cronograma.php&nome_tabela=aula&id_tabela=id_aula&id_exclusao=<?php echo $resultado['id_aula']; ?>"><i class="bi bi-trash-fill"></i> &nbsp;Excluir</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#"><i class="bi bi-people-fill"></i> &nbsp;Alterar Professor</a>
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