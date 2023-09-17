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
$id_equipamento = $_GET['id_equipamento'];

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');
if ($acao == 1) {
    $texto = "Editar";
    $acao_form = "update_equipamento.php";
    include('conn/conn.php');
    $query = "SELECT * FROM equipamento WHERE id_equipamento = $id_equipamento ORDER BY id_equipamento ASC";
    $result = mysqli_query($link, $query);
    while ($resultado = mysqli_fetch_assoc($result)) {

        $id_equipamento = $resultado['id_equipamento'];
        $tipo = $resultado['tipo'];
        $numero_equiipamento = $resultado['numero'];
        $descricao = $resultado['descricao'];
    }
} else {

    $acao_form = "salva_equipamento.php";
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
                            <h3>Equipamento</h3>
                            <p class="text-subtitle text-muted">Cadastre ou edite um equipamento</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="equipamentos.php">Equipamento</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> Equipamento</li>
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
                                    <h4 class="card-title"><?php echo $texto; ?> equipamento</h4>
                                </div>

                                <div class="card-body">
                                    <form id="formEquipamento" method="POST" action="<?php echo $acao_form; ?>">
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Típo</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $tipo; ?>" placeholder="Informe o nome" id="tipo" name="tipo">
                                                    <div id="dTipo" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i> Necessário informar um típo</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Número</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $numero_equiipamento; ?>" placeholder="Informe um número" id="numero" name="numero">
                                                    <div id="dNumero" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i> Necessário informar um número</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 mb-4">
                                                <h6>Descrição</h6>
                                                <div class="form-group">
                                                    <textarea rows="6" class="form-control" placeholder="Informe a descrição" id="descricao" name="descricao"><?php echo $descricao; ?></textarea>
                                                </div>
                                            </div><input type="hidden" value="<?php echo $id_equipamento; ?>" name="id_equipamento">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <button type="button" onclick="validar()" class="btn btn-primary"><?php echo $texto; ?> Equipamento</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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

    <script>
        //Pega o elemento form
        var form = document.getElementById('formEquipamento');

        //Pega os elementos de formulário
        var tipo = document.getElementById('tipo');
        var divTipo = document.getElementById('dTipo');

        var numero = document.getElementById('numero');
        var divNumero = document.getElementById('dNumero');

        function validar() {

            // Cria a validação
            if (tipo.value == '') {
                if (tipo.value == '') {
                    tipo.className += " is-invalid";
                    divTipo.style.display = "block";
                }
                if (numero.value == '') {
                    numero.className += " is-invalid";
                    divNumero.style.display = "block";
                }
            } else {
                form.submit();
            }

        }
    </script>
</body>

</html>