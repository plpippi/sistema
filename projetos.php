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
$query = "SELECT * FROM turma WHERE turma.tipo_turma = 2 ORDER BY id_turma ASC";
$result = mysqli_query($link, $query);

$dias_semana = [
    '0' => 'D',
    '1' => 'S',
    '2' => 'T',
    '3' => 'Q',
    '4' => 'Q',
    '5' => 'S',
    '6' => 'S',
];
$nome_dia_semana = [
    '0' => 'Domingo',
    '1' => 'Segunda',
    '2' => 'Terça',
    '3' => 'Quarta',
    '4' => 'Quinta',
    '5' => 'Sexta',
    '6' => 'Sabado',
]

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Cronograma Senac</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="css/notifica.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="#">Senac</a><br>
                            <section>
                                <div class="imagem"><a href="<?php echo $linkNoti; ?>"><img src="imagens/users/<?php echo $imagem_user; ?>" style="width: 50px; height: 50px; border-radius: 50px;">&nbsp;&nbsp;<span style="font-size: 18px;"><?php echo $nome_user; ?></span></div>
                                <div class="notificacao" style="<?php echo $style; ?>"><?php echo $totalNoti; ?></div>
                            </section><br>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
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
                            <h3>Senac</h3>
                            <p class="text-subtitle text-muted">Lista de Projetos</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Projetos</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <a href="cad_projeto.php" class="btn btn-primary">Cadastrar Projeto</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cor</th>
                                        <th>Nome</th>
                                        <th>Numero da Turma</th>
                                        <th>Carga Horária</th>
                                        <th>CH Diária</th>
                                        <th>Dias de Aulas</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($resultado = mysqli_fetch_assoc($result)) { ?>
                                        <?php
                                        if ($resultado['status'] == 1) {
                                            echo "<tr>";
                                        } else {
                                            echo "<tr style='color: #DCDCDC'>";
                                        } ?>
                                        <td><?php echo $resultado['id_turma']; ?></td>
                                        <td><span class='badge' style="background-color: <?php echo $resultado['cor']; ?> !important;">C</span></td>
                                        <td><?php echo $resultado['nome']; ?></td>
                                        <td><?php echo $resultado['numero_turma']; ?></td>
                                        <td><?php echo $resultado['carga_horaria']; ?></td>
                                        <td><?php echo $resultado['horas_diaria']; ?></td>
                                        <td>
                                            <?php
                                            $dias = explode(',', $resultado['dias_semana']);
                                            $total = count($dias);
                                            for ($i = 0; $i < sizeof($dias); $i++) {
                                                echo "<span class='badge bg-secondary' title='".$nome_dia_semana[$dias[$i]]."'>" . $dias_semana[$dias[$i]] . "</span>&nbsp;";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($resultado['status'] == 1) {
                                                echo "<span class='badge bg-success'>Ativa</span>";
                                            } else {
                                                echo "<span class='badge bg-danger'>Desativada</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Ações
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="cad_projeto.php?acao=1&id_turma=<?php echo $resultado['id_turma']; ?>"><i class="bi bi-pencil-square"></i> &nbsp;Editar</a>
                                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#default<?php echo $resultado['id_turma']; ?>" href="#"><i class="bi bi-trash-fill"></i> &nbsp;Excluir</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="cad_cronograma_projeto.php?id_turma=<?php echo $resultado['id_turma']; ?>&curso=<?php echo $resultado['curso']; ?>"><i class="bi bi-file-plus-fill"></i> &nbsp;Cadastrar Cronograma</a>
                                                <a class="dropdown-item" href="calendario_turma.php?id_turma=<?php echo $resultado['id_turma']; ?>"><i class="bi bi-calendar-fill"></i> &nbsp;Visualizar Cronograma</a>
                                                <div class="dropdown-divider"></div>
                                                <?php
                                                if ($resultado['status'] == 1) {
                                                    echo "<a class='dropdown-item' href='desativa.php?tabela=turma&id_tabela=id_turma&id_item=" . $resultado['id_turma'] . "&local=turma.php'><i class='bi bi-lock-fill'></i> &nbsp;Desativar</a>";
                                                } else if ($resultado['status'] == 0) {
                                                    echo "<a class='dropdown-item' href='ativa.php?tabela=turma&id_tabela=id_turma&id_item=" . $resultado['id_turma'] . "&local=turma.php'><i class='bi bi-lock-fill'></i> &nbsp;Ativar</a>";
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        </tr>
                                        <!-- Modais -->
                                        <div class="modal fade text-left" id="default<?php echo $resultado['id_turma']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel1">Deseja realmente excluir a turma?</h5>
                                                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                                                            <i data-feather="x"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h6>Ao excluir a turma você excluirá todo o cronograma associado a essa turma.</h6>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn" data-bs-dismiss="modal">
                                                            <i class="bx bx-x d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Cancelar</span>
                                                        </button>
                                                        <a class="btn btn-primary" href="excluir_turma.php?id_turma=<?php echo $resultado['id_turma']; ?>"><i class="bi bi-trash-fill"></i> &nbsp;Excluir</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section><br><br><br><br>
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




    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendors/simple-datatables/simple-datatables.js"></script>
    <script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    <script src="assets/js/main.js"></script>
</body>

</html>