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
$id_dia_n_letivo = $_GET['id_dia_n_letivo'];

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

if($acao == 1){
    $texto = "Editar";
    $acao_form = "update_feriado.php";
    include('conn/conn.php');
    $query = "SELECT * FROM dias_n_letivos WHERE id_dia_n_letivo = $id_dia_n_letivo ORDER BY id_dia_n_letivo ASC";
    $result = mysqli_query($link, $query);
    while($resultado = mysqli_fetch_assoc($result)){

        $id_dia_n_letivo = $resultado['id_dia_n_letivo'];
        $descricao_feriado = $resultado['descricao'];
        $dia_feriado = $resultado['dia'];
        $tipo = $resultado['tipo'];
    
    }
}else{

    $acao_form = "salva_feriado.php";
    $texto = "Cadastrar";
}

$queryCidade = "SELECT * FROM cidade ORDER BY nome_cidade ASC";
$resultCidade = mysqli_query($link, $queryCidade);


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
                            <p class="text-subtitle text-muted">Cadastre ou edite um dia não letivo</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="salas.php">Extrutura</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> Dia não Letivo</li>
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
                                    <h4 class="card-title"><?php echo $texto; ?> dia não letivo</h4>
                                </div>

                                <div class="card-body">
                                    <form id="formFeriado" method="POST" action="<?php echo $acao_form; ?>">
                                        <div class="row">
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
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Data</h6>
                                                <div class="form-group">
                                                    <input type="date" class="form-control" value="<?php echo $dia_feriado; ?>" placeholder="Informe uma data" id="dia" name="dia">
                                                    <div id="dDia" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessário informar um dia</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Tipo</h6>
                                                <div class="form-group">
                                                    <select class="form-select" id="tipo" name="tipo" style="width: 100%;">
                                                        <option value="">Selecionar</option>
                                                        <option value="Dia não Letivo">Dia não Letivo</option>
                                                        <option value="Feriado">Feriado</option>
                                                    </select>
                                                    <div id="dTipo" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessario informar o típo</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 mb-4">
                                                <h6>Descrição</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $descricao_feriado; ?>" placeholder="Informe a descrição" id="descricao" name="descricao">
                                                </div>
                                            </div><input type="hidden" value="<?php echo $id_dia_n_letivo; ?>" name="id_dia_n_letivo">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <button type="button" onclick="validar()" class="btn btn-primary"><?php echo $texto; ?> Dia</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
        var dia = document.getElementById('dia');
        var divDia = document.getElementById('dDia');

        var tipo = document.getElementById('tipo');
        var divTipo = document.getElementById('dTipo');

        var cidade = document.getElementById('cidade');
        var divCidade = document.getElementById('dCidade');

        function validar() {

            // Cria a validação
            if (dia.value == '' || tipo.value == '' || cidade.value == '') {
                if (dia.value == '') {
                    dia.className += " is-invalid";
                    divDia.style.display = "block";
                }
                if (tipo.value == '') {
                    tipo.className += " is-invalid";
                    divTipo.style.display = "block";
                }
                if (cidade.value == ''){
                    cidade.className += " is-invalid";
                    divCidade.style.display = "block";
                }
            } else {
                form.submit();
            }

        }


    </script>
</body>

</html>