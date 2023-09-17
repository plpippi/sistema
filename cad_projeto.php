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

$acao = $_GET['acao'];
$id_turma = $_GET['id_turma'];

if ($acao == 1) {
    $texto = "Editar";
    $acao_form = "update_turma.php";
    include('conn/conn.php');
    $queryUpdate = "SELECT * FROM turma WHERE id_turma = $id_turma ORDER BY id_turma ASC";
    $resultUpdate = mysqli_query($link, $queryUpdate);
    while ($resultadoUpdate = mysqli_fetch_assoc($resultUpdate)) {

        $nome_turma = $resultadoUpdate['nome'];
        $numero_turma = $resultadoUpdate['numero_turma'];
        $data_inicio = $resultadoUpdate['data_inicio'];
        $carga_horaria = $resultadoUpdate['carga_horaria'];
        $hora_inicio = $resultadoUpdate['hora_inicio'];
        $hora_termino = $resultadoUpdate['hora_termino'];
        $horas_diaria = $resultadoUpdate['horas_diaria'];
        $dias_semana = explode(',', $resultadoUpdate['dias_semana']);
        $curso = $resultadoUpdate['curso'];
    }
} else {

    $acao_form = "salva_turma.php";
    $texto = "Cadastrar";
}

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

$id_curso = $_POST['id_curso'];
$query = "SELECT * FROM curso WHERE status = 1 ORDER BY id_curso ASC";
$result = mysqli_query($link, $query);

$queryCidade = "SELECT * FROM cidade ORDER BY nome_cidade ASC";
$resultCidade = mysqli_query($link, $queryCidade);

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
                            <h3>Turma</h3>
                            <p class="text-subtitle text-muted">Cadastre ou edite uma turma</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="turma.php">Projeto</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> Turma</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Inicio Formulário -->
                <section id="input-with-icons">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><?php echo $texto; ?> projeto</h4>
                                </div>

                                <div class="card-body">
                                    <form method="POST" action="<?php echo $acao_form; ?>">
                                        <div class="row">
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Nome</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $nome_turma; ?>" placeholder=" Informe o nome da turma" name="nome">
                                                    <input type="hidden" value="2" name="tipo">
                                                    <input type="hidden" value="projetos" name="url">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Número</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $numero_turma; ?>" placeholder=" Informe um número" name="numero">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Cidade</h6>
                                                <div class="form-group">
                                                    <select class="form-select" id="cidade" name="cidade" style="width: 100%;">
                                                        <option value="0">Qualquer Cidade</option>
                                                        <?php
                                                            while($resultadoCidade = mysqli_fetch_assoc($resultCidade)){
                                                        ?>
                                                                <option value="<?php echo $resultadoCidade['id_cidade']; ?>"><?php echo $resultadoCidade['nome_cidade']; ?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>
                                                    <div id="dCidade" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessario informar a cidade</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Data de Inicio</h6>
                                                <div class="form-group">
                                                    <input type="date" class="form-control" value="<?php echo $data_inicio; ?>" name=" data_inicio">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>* CH Total</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $carga_horaria; ?>" placeholder=" Informe a carga horaria total" name="ch">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Hora de Inicio</h6>
                                                <div class="form-group">
                                                    <input type="time" class="form-control" value="<?php echo $hora_inicio; ?>" name=" hora_inicio">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Hora de Término</h6>
                                                <div class="form-group">
                                                    <input type="time" class="form-control" value="<?php echo $hora_termino; ?>" name=" hora_termino">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-4">
                                                <h6>* CH Diária</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $horas_diaria; ?>" placeholder=" Informe a carga horaria diária" name="horas_diaria">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Dia(s) da Semana com Aula</h6>
                                                <div class="form-group">
                                                    <input type="checkbox" name="campo[]" value="0" class="form-check-input">&nbsp;D&nbsp;&nbsp;<input type="checkbox" name="campo[]" value="1" class="form-check-input">&nbsp;S&nbsp;&nbsp;<input type="checkbox" name="campo[]" value="2" class="form-check-input">&nbsp;T&nbsp;&nbsp;<input type="checkbox" name="campo[]" value="3" class="form-check-input">&nbsp;Q&nbsp;&nbsp;<input type="checkbox" name="campo[]" value="4" class="form-check-input">&nbsp;Q&nbsp;&nbsp;<input type="checkbox" name="campo[]" value="5" class="form-check-input">&nbsp;S&nbsp;&nbsp;<input type="checkbox" name="campo[]" value="6" class="form-check-input">&nbsp;S&nbsp;&nbsp;
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-4">
                                                <h6>Curso</h6>
                                                <div class="form-group">
                                                    <select class="choices form-select multiple-remove" name="curso">
                                                        <optgroup label="Cursos Disponiveis">
                                                            <?php
                                                            while ($resultado = mysqli_fetch_assoc($result)) {
                                                                echo "<option value='" . $resultado['id_curso'] . "'>" . $resultado['nome'] . "</option>";
                                                            }
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                    <input type="hidden" name="id_turma" value="<?php echo $id_turma; ?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-4">
                                                <h6>Cor da Turma</h6>
                                                <div class="form-group">
                                                    <input type="color" list="presetColors" class="form-control" name="cor" style="height: 40px !important;">
                                                    <datalist id="presetColors">
                                                        <option>#c6d8f0</option>/>
                                                        <option>#d8bfd8</option>
                                                        <option>#fff2a7</option>
                                                        <option>#ffc2bb</option>
                                                        <option>#b0e0e6 </option>
                                                        <option>#bfffec</option>
                                                        <option>#f4d5c0</option>
                                                        <option>#ff9999</option>
                                                        <option>#c4f9ff</option>
                                                        <option>#ccff99</option>
                                                        <option>#ffb394</option>
                                                        <option>#ff8ad2</option>
                                                    </datalist>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <button type="submit" class="btn btn-primary"><?php echo $texto; ?> Turma</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Formulário -->
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

    <script src="assets/js/main.js"></script>
    <!-- Include Choices JavaScript -->
    <script src="assets/vendors/choices.js/choices.min.js"></script>
</body>

</html>