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
$id_reserva = $_GET['id_reserva'];

if ($acao == 1) {
    $texto = "Editar";
    $acao_form = "update_reserva.php";
    include('conn/conn.php');
    $queryUpdate = "SELECT * FROM reserva WHERE id_reserva = $id_reserva ORDER BY id_reserva ASC";
    $resultUpdate = mysqli_query($link, $queryUpdate);
    while ($resultadoUpdate = mysqli_fetch_assoc($resultUpdate)) {

        $id_pessoa = $resultadoUpdate['id_pessoa'];
        $nome = $resultadoUpdate['nome'];
        $equipamento = $resultadoUpdate['equipamento'];
        $numero = $resultadoUpdate['numero'];
        $data = $resultadoUpdate['data'];
        $hora_inicio = $resultadoUpdate['hora_retirada'];
        $hora_termino = $resultadoUpdate['hora_devolucao'];
    }
} else {

    $acao_form = "salva_reserva.php";
    $texto = "Cadastrar";
}

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

$query_professor = "SELECT * FROM professor ORDER BY professor.id_professor ASC";
$result_professor = mysqli_query($link, $query_professor);

$query_equipamento = "SELECT * FROM equipamento ORDER BY equipamento.id_equipamento ASC";
$result_equipamento = mysqli_query($link, $query_equipamento);

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
                            <h3>Reserva</h3>
                            <p class="text-subtitle text-muted">Cadastre ou edite uma reserva</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="turma.php">Reserva</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> Reserva</li>
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
                                    <h4 class="card-title"><?php echo $texto; ?> reserva</h4>
                                </div>

                                <div class="card-body">
                                    <form method="POST" action="<?php echo $acao_form; ?>">
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Nome</h6>
                                                <div class="form-group">
                                                    <select class="choices form-select multiple-remove" name="professor">
                                                        <optgroup label="Cursos Disponiveis">
                                                            <?php
                                                            while ($linha_professor = mysqli_fetch_assoc($result_professor)) {
                                                                echo "<option value='" . $linha_professor['id_professor'] . "'>" . $linha_professor['nome'] . "</option>";
                                                            }
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Equipamento</h6>
                                                <div class="form-group">
                                                    <select class="choices form-select multiple-remove" name="equipamento">
                                                        <optgroup label="Cursos Disponiveis">
                                                            <?php
                                                            while ($linha_equipamento = mysqli_fetch_assoc($result_equipamento)) {
                                                                echo "<option value='" . $linha_equipamento['id_equipamento'] . "'>" . $linha_equipamento['tipo'] . " " . $linha_equipamento['numero'] . "</option>";
                                                            }
                                                            ?>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Dia</h6>
                                                <div class="form-group">
                                                    <input type="date" class="form-control" value="<?php echo $data; ?>" name="dia">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Retirada</h6>
                                                <div class="form-group">
                                                    <input type="time" class="form-control" value="<?php echo $hora_inicio; ?>" name="retirada">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-6">
                                                <h6>* Devolução</h6>
                                                <div class="form-group">
                                                    <input type="time" class="form-control" value="<?php echo $hora_termino; ?>" name="devolucao">
                                                </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <input type="hidden" name="id_reserva" value="<?php echo $id_reserva; ?>">
                                                <button type="submit" class="btn btn-primary"><?php echo $texto; ?> Reserva</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
    <?php echo $scripts; ?>
</body>

</html>