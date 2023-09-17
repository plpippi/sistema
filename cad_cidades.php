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

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

if($acao == 1){
    $texto = "Editar";
    $acao_form = "update_cidade.php";
    include('conn/conn.php');
    $query = "SELECT * FROM cidade ORDER BY id_cidade ASC";
    $result = mysqli_query($link, $query);
    while($resultado = mysqli_fetch_assoc($result)){

        $id_cidade = $resultado['id_cidade'];
        $nome = $resultado['nome_cidade'];
        $id_unidade = $resultado['id_unidade'];
    }
}else{

    $acao_form = "salva_cidade.php";
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
                            <p class="text-subtitle text-muted">Cadastre ou edite uma cidade</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> Cidade</li>
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
                                    <h4 class="card-title"><?php echo $texto; ?> cidade</h4>
                                </div>

                                <div class="card-body">
                                    <form id="formCidade" method="POST" action="<?php echo $acao_form; ?>">
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Cidade</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $nome_cidade; ?>" placeholder="Informe a cidade" id="cidade" name="cidade">
                                                    <input type="hidden" id="id_cidade" name="id_cidade" value="<?php echo $id_cidade; ?>">
                                                    <div id="dCidade" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessario informar a cidade</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Unidade</h6>
                                                <div class="form-group">
                                                    <select class="form-select" id="unidade" name="unidade" style="width: 100%;">
                                                        <option value="">Selecionar</option>
                                                        <option value="11">Santa Maria</option>
                                                    </select>
                                                    <div id="dUnidade" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessario informar a unidade</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <button type="button" onclick="validar()" class="btn btn-primary"><?php echo $texto; ?> Cidade</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
        var form = document.getElementById('formCidade');

        //Pega os elementos de formulário
        var cidade = document.getElementById('cidade');
        var divCidade = document.getElementById('dCidade');

        var unidade = document.getElementById('unidade');
        var divUnidade = document.getElementById('dUnidade');

        function validar() {

            // Cria a validação
            if (cidade.value == '' || unidade.value == '') {
                if (unidade.value == '') {
                    unidade.className += " is-invalid";
                    divUnidade.style.display = "block";
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