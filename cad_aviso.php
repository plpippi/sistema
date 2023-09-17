<?php

// Inicio sessão
error_reporting(0);
session_start();

if(!isset($_SESSION['user'])){
    header('Location: index.html');
    exit;
}
$user = $_SESSION['user'];
$nome_user = $_SESSION['nome_user'];
$imagem_user = $_SESSION['imagem'];
$acesso_user = $_SESSION['acesso'];
// Fim sessão

$acao = $_GET['acao'];
$id_aviso = $_GET['id_aviso'];

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

if($acao == 1){
    $texto = "Editar";
    $acao_form = "update_aviso.php";
    include('conn/conn.php');
    $query = "SELECT * FROM avisos WHERE id_aviso = $id_aviso ORDER BY id_aviso ASC";
    $result = mysqli_query($link, $query);
    while($resultado = mysqli_fetch_assoc($result)){

        $id_aviso = $resultado['id_aviso'];
        $data = $resultado['data'];
        $aviso = $resultado['aviso'];
        $inicio = $resultado['inicio'];
        $fim = $resultado['fim'];
    
    }
}else{

    $acao_form = "salva_aviso.php";
    $texto = "Cadastrar";
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
                            <h3>Senac</h3>
                            <p class="text-subtitle text-muted">Cadastre ou edite um aviso</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="salas.php">Extrutura</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> Aviso</li>
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
                                    <h4 class="card-title"><?php echo $texto; ?> Aviso</h4>
                                </div>

                                <div class="card-body">
                                    <form id="formFeriado" method="POST" action="<?php echo $acao_form; ?>">
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Aviso</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $aviso; ?>" placeholder="Informe o aviso" id="aviso" name="aviso">
                                                    <div id="dAviso" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessário informar um aviso</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Dia</h6>
                                                <div class="form-group">
                                                    <input type="date" class="form-control" value="<?php echo $data; ?>" placeholder="Informe a data" id="dia" name="dia">
                                                    <div id="dDia" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessário informar um dia</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>Inicio</h6>
                                                <div class="form-group">
                                                    <input type="time" class="form-control" value="<?php echo $inicio; ?>" placeholder="Informe o inicio" id="inicio" name="inicio">
                                                    <div id="dInicio" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessário informar um inicio</div>
                                                </div>
                                                <input type="hidden" value="<?php echo $id_dia_n_letivo; ?>" name="id_dia_n_letivo">
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>Fim</h6>
                                                <div class="form-group">
                                                    <input type="time" class="form-control" value="<?php echo $inicio; ?>" placeholder="Informe o fim" id="fim" name="fim">
                                                    <div id="dFim" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessário informar um fim</div>
                                                </div>
                                                <input type="hidden" value="<?php echo $id_aviso; ?>" name="id_aviso">
                                            </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <button type="button" onclick="validar()" class="btn btn-primary"><?php echo $texto; ?> Aviso</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section><br><br>
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
    <script type="text/javascript">

        //Pega o elemento form
        var form = document.getElementById('formFeriado');

        //Pega os elementos de formulário
        var aviso = document.getElementById('aviso');
        var divAviso = document.getElementById('dAviso');

        var dia = document.getElementById('dia');
        var divDia = document.getElementById('dDia');

        var inicio = document.getElementById('inicio');
        var divInicio = document.getElementById('dInicio');

        var fim = document.getElementById('fim');
        var divFim = document.getElementById('dFim');

        function validar() {

            // Cria a validação
            if (dia.value == '' || aviso.value == '' || fim.value == '' || inicio.value == '') {
                if (dia.value == '') {
                    dia.className += " is-invalid";
                    divDia.style.display = "block";
                }
                if (aviso.value == '') {
                    aviso.className += " is-invalid";
                    divAviso.style.display = "block";
                }
                if (inicio.value == '') {
                    inicio.className += " is-invalid";
                    divInicio.style.display = "block";
                }
                if (fim.value == '') {
                    fim.className += " is-invalid";
                    divFim.style.display = "block";
                }
            } else {
                form.submit();
            }

        }


    </script>
</body>

</html>