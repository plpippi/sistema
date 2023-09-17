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

$id_curso = $_POST['id_curso'];
$id_curso = $_GET['id_curso'];
$query = "SELECT curso_uc_componente.id_curso_uc_componente AS id, curso.nome AS nome_curso, uc_componente.nome AS uc_componente, uc_componente.carga_horaria AS ch, uc_componente.descricao AS descricao_uc  FROM curso_uc_componente INNER JOIN curso ON curso.id_curso = curso_uc_componente.id_curso INNER JOIN uc_componente ON uc_componente.id_uc_componente = curso_uc_componente.id_uc_componente WHERE curso_uc_componente.id_curso = $id_curso ORDER BY curso_uc_componente.id_curso_uc_componente ASC";
$result = mysqli_query($link, $query);

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

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="css/notifica.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">

    <!-- Include Choices CSS -->
    <link rel="stylesheet" href="assets/vendors/choices.js/choices.min.css" />
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
                            <h3>Portifólio</h3>
                            <p class="text-subtitle text-muted">Cadastre um Portifólio</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Portifólio</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Inicio Formulário -->
                <form method="GET" action="salva_portifolio.php?id_curso=<?php echo $id_curso; ?>">
                    <section id="input-with-icons">
                        <div class="row match-height">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Cadastrar Portifólio</h4>
                                    </div>

                                    <div class="card-body">
                                        <form method="POST" action="portifólio.php?id_curso=<?php echo $id_curso; ?>">
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* Curso</h6>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" id="id_curso" name="id_curso">
                                                            <?php
                                                            if ($id_curso == '') {
                                                                $query_curso = "SELECT * FROM curso WHERE status = 1 ORDER BY id_curso ASC";
                                                                $result_curso = mysqli_query($link, $query_curso);
                                                                echo "<optgroup label='Cursos Disponiveis'>";
                                                                while ($resultado_curso = mysqli_fetch_assoc($result_curso)) {
                                                                    echo "<option value='" . $resultado_curso['id_curso'] . "'>" . $resultado_curso['nome'] . "</option>";
                                                                }
                                                                echo "</optgroup>";
                                                            } else {
                                                                $query_curso_retorno = "SELECT * FROM curso WHERE id_curso = " . $id_curso . " ORDER BY id_curso ASC";
                                                                $result_curso_retorno = mysqli_query($link, $query_curso_retorno);
                                                                echo "<optgroup label='Ultimo Curso Cadastrado'>";
                                                                while ($resultado_curso_retorno = mysqli_fetch_assoc($result_curso_retorno)) {
                                                                    echo "<option value='" . $resultado_curso_retorno['id_curso'] . "' selected>" . $resultado_curso_retorno['nome'] . "</option>";
                                                                }
                                                                echo "</optgroup>";
                                                                $query_curso = "SELECT * FROM curso WHERE status = 1 ORDER BY id_curso ASC";
                                                                $result_curso = mysqli_query($link, $query_curso);
                                                                echo "<optgroup label='Cursos Disponiveis'>";
                                                                while ($resultado_curso = mysqli_fetch_assoc($result_curso)) {
                                                                    echo "<option value='" . $resultado_curso['id_curso'] . "'>" . $resultado_curso['nome'] . "</option>";
                                                                }
                                                                echo "</optgroup>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div style="color: red;"><?php echo $_GET['mensagem']; ?></div><br>
                                                </div>
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* UC/Componente</h6>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" id="id_uc_componente" name="id_uc_componente">
                                                            <optgroup label="UCs/Componentes">
                                                                <?php
                                                                $query_uc_com = "SELECT * FROM uc_componente WHERE status = 1 ORDER BY id_uc_componente ASC";
                                                                $result_uc_com = mysqli_query($link, $query_uc_com);
                                                                while ($resultado_uc_com = mysqli_fetch_assoc($result_uc_com)) {
                                                                    echo "<option value=" . $resultado_uc_com['id_uc_componente'] . ">" . $resultado_uc_com['nome'] . "</option>";
                                                                }
                                                                ?>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <button type="submit" class="btn btn-primary">Cadastrar
                                                        Portifólio</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section><br>
                </form>
                <!-- Formulário -->
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>ID<?php //print_r($query); 
                                        ?></th>
                                <th>Curso</th>
                                <th>UC/Componente</th>
                                <th>CH</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($resultado = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $resultado['id']; ?></td>
                                    <td><?php echo $resultado['nome_curso']; ?></td>
                                    <td><?php echo $resultado['uc_componente']; ?></td>
                                    <td><?php echo $resultado['ch']; ?></td>
                                    <td><?php echo $resultado['descricao_uc']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
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
    <!-- Include Choices JavaScript -->
    <script src="assets/vendors/choices.js/choices.min.js"></script>

    <script src="assets/js/main.js"></script>
</body>

</html>