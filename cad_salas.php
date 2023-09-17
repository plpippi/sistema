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
$id_local = $_GET['id_local'];

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

if ($acao == 1) {
    $texto = "Editar";
    $acao_form = "update_sala.php";
    include('conn/conn.php');
    $query = "SELECT * FROM local WHERE id_local = $id_local ORDER BY id_local ASC";
    $result = mysqli_query($link, $query);
    while ($resultado = mysqli_fetch_assoc($result)) {

        $id_local = $resultado['id_local'];
        $nome_local = $resultado['nome'];
        $numero_local = $resultado['numero'];
        $capacidade = $resultado['capacidade'];
        $softwares = $resultado['programas'];
        $obs = $resultado['obs'];
    }
} else {

    $acao_form = "salva_sala.php";
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
                            <p class="text-subtitle text-muted">Cadastre ou edite um local</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="salas.php">Salas</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> Local</li>
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
                                    <h4 class="card-title"><?php echo $texto; ?> Local</h4>
                                </div>

                                <div class="card-body">
                                    <form id="formSalas" method="POST" action="<?php echo $acao_form; ?>">
                                        <div class="row">
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Nome</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $nome_local; ?>" placeholder="Informe o nome do local" id="nome" name="nome">
                                                    <div id="dNome" class="invalid-feedback" style="display: none;">
                                                        <i class="bx bx-radio-circle"></i>
                                                        Necessário informar um local
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 mb-4">
                                                <h6>* Número</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $numero_local; ?>" placeholder="Informe o número do local" id="numero" name="numero">
                                                    <div id="dNumero" class="invalid-feedback" style="display: none;">
                                                        <i class="bx bx-radio-circle"></i>
                                                        Necessário informar um número
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 mb-2">
                                                <h6>Visualizar no Painel</h6>
                                                <div class="form-group">
                                                    <select class="form-select" id="visualiza" name="visualiza">
                                                        <option value="1">Sim</option>
                                                        <option value="0">Não</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 mb-2">
                                                <h6>Capacidade</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $capacidade; ?>" placeholder="Informe a capacidade do local" id="capacidade" name="capacidade">
                                                    <div id="dCapacidade" class="invalid-feedback" style="display: none;">
                                                        <i class="bx bx-radio-circle"></i>
                                                        Necessário informar a capacidade
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>Programas Instalados</h6>
                                                <div class="form-group">
                                                    <textarea rows="5" class="form-control" placeholder="Informe os programas instalados" id="programas" name="programas"><?php echo $softwares; ?>
Windows;
Pacote Office;
Leitor PDF;
                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>OBS</h6>
                                                <div class="form-group">
                                                    <textarea rows="5" class="form-control" placeholder="Informe uma observação" id="obs" name="obs"><?php echo $obs; ?> </textarea>
                                                </div>
                                                <input type="hidden" name="id_local" value="<?php echo $id_local; ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                * Campos necessários<br>
                                                <button type="button" onclick="validar()" class="btn btn-primary"><?php echo $texto; ?> Salas</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section><br>
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
        var form = document.getElementById('formSalas');

        //Pega os elementos de formulário
        var nome = document.getElementById('nome');
        var divNome = document.getElementById('dNome');

        var numero = document.getElementById('numero');
        var divNumero = document.getElementById('dNumero');

        function validar() {

            // Cria a validação
            if (nome.value == '' || numero.value == '') {
                if (nome.value == '') {
                    nome.className += " is-invalid";
                    divNome.style.display = "block";
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