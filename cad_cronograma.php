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

$id_turma = $_GET['id_turma'];
$id_curso = $_GET['id_curso'];
$query = "SELECT * FROM curso ORDER BY id_curso ASC";
$result = mysqli_query($link, $query);

$query_professor = "SELECT * FROM professor WHERE status = 1 ORDER BY id_professor ASC";
$result_professor = mysqli_query($link, $query_professor);

$query_uc_com = "SELECT uc_componente.* FROM uc_componente INNER JOIN curso_uc_componente ON uc_componente.id_uc_componente = curso_uc_componente.id_uc_componente WHERE status = 1 AND curso_uc_componente.id_curso = $id_curso ORDER BY id_uc_componente ASC";
$result_uc_com = mysqli_query($link, $query_uc_com);

$query_local = "SELECT * FROM local WHERE status = 1 ORDER BY id_local ASC";
$result_local = mysqli_query($link, $query_local);

$query_turma = "SELECT * FROM turma WHERE id_turma =  '" . $id_turma . "' ORDER BY id_turma ASC";
$result_turma = mysqli_query($link, $query_turma);

while ($resultado_turma = mysqli_fetch_assoc($result_turma)) {
    $nome_turma = $resultado_turma['nome'];
    $chd_turma = $resultado_turma['horas_diaria'];
    $numero_turma = $resultado_turma['numero_turma'];
}

$query_lista_uc_c = "SELECT uc_componente.id_uc_componente, uc_componente.carga_horaria, uc_componente.nome, MAX(aula.dia) AS data_termino, MIN(aula.dia) AS data_inicio FROM aula INNER JOIN uc_componente ON aula.id_uc_componente = uc_componente.id_uc_componente WHERE `id_turma` = '" . $id_turma . "' GROUP BY aula.id_uc_componente ORDER BY data_termino ASC";
$result_lista_uc_c = mysqli_query($link, $query_lista_uc_c);

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
                            <p class="text-subtitle text-muted">Cadastre um Cronograma</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Cronograma</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Inicio Formulário -->
                <form method="POST" action="gera_cronograma.php?local=cad_cronograma" id="formCronograma">
                    <section id="input-with-icons">
                        <div class="row match-height">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Cadastrar Cronograma</h4>
                                    </div>

                                    <div class="card-body">
                                        <form method="POST" action="sala_cad.php">
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <h6>Turma</h6>
                                                    <div class="form-group">
                                                        <h4>
                                                            <?php
                                                            echo $nome_turma . " - " . $numero_turma;
                                                            ?>
                                                            <input type="hidden" value="<?php echo $id_turma; ?>" name="id_turma">
                                                            <input type="hidden" value="<?php echo $id_curso; ?>" name="curso">
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* Professor</h6>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" name="professor" id="professor">
                                                            <optgroup label="Professor">
                                                                <option value="0">Selecionar</option>
                                                                <?php
                                                                while ($resultado_professor = mysqli_fetch_assoc($result_professor)) {
                                                                    echo "<option value=" . $resultado_professor['id_professor'] . ">" . $resultado_professor['nome'] . "</option>";
                                                                }
                                                                ?>

                                                            </optgroup>
                                                        </select>
                                                        <div id="dProfessor" class="invalid-feedback" style="display: none;">
                                                            <i class="bx bx-radio-circle"></i>
                                                            Necessário selecionar um professor
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* UC/Componente</h6>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" name="uc" id="uc">
                                                            <optgroup label="UCs/Componentes">
                                                                <option value="0">Selecionar</option>
                                                                <?php
                                                                while ($resultado_uc_com = mysqli_fetch_assoc($result_uc_com)) {
                                                                    echo "<option value=" . $resultado_uc_com['id_uc_componente'] . ">" . $resultado_uc_com['nome'] . " - <span>" . $resultado_uc_com['carga_horaria'] . "</span>hs</option>";
                                                                }
                                                                ?>
                                                            </optgroup>
                                                        </select>
                                                        <div id="dUc" class="invalid-feedback" style="display: none;">
                                                            <i class="bx bx-radio-circle"></i>
                                                            Necessário selecionar uma UC
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* Data de Inicio</h6>
                                                    <div class="form-group">
                                                        <input type="date" class="form-control" name="data_inicio" id="data_inicio">
                                                        <div id="dDataInicio" class="invalid-feedback" style="display: none;">
                                                            <i class="bx bx-radio-circle"></i>
                                                            Necessário preencher uma data de inicio
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* Local</h6>
                                                    <div class="form-group">
                                                        <select class="choices form-select multiple-remove" name="local" id="local">
                                                            <optgroup label="UCs/Componentes">
                                                                <option value="0">Selecionar</option>
                                                                <?php
                                                                while ($resultado_local = mysqli_fetch_assoc($result_local)) {
                                                                    echo "<option value=" . $resultado_local['id_local'] . ">" . $resultado_local['nome'] . " - " . $resultado_local['numero'] . "</option>";
                                                                }
                                                                ?>
                                                            </optgroup>
                                                        </select>
                                                        <div id="dLocal" class="invalid-feedback" style="display: none;">
                                                            <i class="bx bx-radio-circle"></i>
                                                            Necessário selecionar um local
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-4">
                                                    <h6>* Carga Horária Diária</h6>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" value="<?php echo $chd_turma; ?>" placeholder="Informe a CH Diária" id="ch_diaria" name="ch_diaria">
                                                        <div id="dCh" class="invalid-feedback" style="display: none;">
                                                            <i class="bx bx-radio-circle"></i>
                                                            Necessário preencher uma carga horária diária
                                                        </div>
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
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    * Campos necessários<br>
                                                    <button type="button" onclick="validar()" class="btn btn-primary">Gerar Cronograma</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>UC/Componente</th>
                                <th>CH</th>
                                <th>Data Inicio</th>
                                <th>Data Término</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($resultado_lista_uc_c = mysqli_fetch_assoc($result_lista_uc_c)) { ?>
                                <tr>
                                    <td><?php echo $resultado_lista_uc_c['id_uc_componente']; ?></td>
                                    <td><?php echo $resultado_lista_uc_c['nome']; ?></td>
                                    <td><?php echo $resultado_lista_uc_c['carga_horaria'] . " hs"; ?></td>
                                    <td><?php echo converte_data($resultado_lista_uc_c['data_inicio']); ?></td>
                                    <td><?php echo converte_data($resultado_lista_uc_c['data_termino']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Ações
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#"><i class="bi bi-pencil-square"></i> &nbsp;Editar</a>
                                            <a class="dropdown-item" href="exclui_uc_cronograma.php?id_uc=<?php echo $resultado_lista_uc_c['id_uc_componente']; ?>&id_turma=<?php echo $id_turma; ?>&id_curso=<?php echo $id_curso; ?>"><i class="bi bi-trash-fill"></i> &nbsp;Excluir</a>
                                        </div>
                                    </td>
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
    <?php echo $scripts; ?>

    <script>
        //Pega o elemento form
        var form = document.getElementById('formCronograma');

        //Pega os elementos de formulário
        var professor = document.getElementById('professor');
        var uc = document.getElementById('uc');
        var dataInicio = document.getElementById('data_inicio');
        var local = document.getElementById('local');
        var ch = document.getElementById('ch_diaria');

        //Pega os elementos das div
        var dProfessor = document.getElementById('dProfessor');
        var dUc = document.getElementById('dUc');
        var dDataInicio = document.getElementById('dDataInicio');
        var dLocal = document.getElementById('dLocal');
        var dCh = document.getElementById('dCh');

        function validar() {

            // Cria a validação
            if (professor.value == '0' || uc.value == '0' || dataInicio.value == '' || local.value == '0' || ch.value == '') {
                if(professor == '0'){
                    //professor.className += " is-invalid";
                    dProfessor.style.display = "block";
                }

                if(uc == '0'){
                    //uc.className += " is-invalid";
                    dUc.style.display = "block";
                }

                if(dataInicio == ''){
                    //dataInicio.className += " is-invalid";
                    dDataInicio.style.display = "block";
                }

                if(local == '0'){
                    //local.className += " is-invalid";
                    dLocal.style.display = "block";
                }

                if(ch == ''){
                    //ch.className += " is-invalid";
                    dCh.style.display = "block";
                }

            } else {
                form.submit();
            }

        }
    </script>
</body>

</html>
