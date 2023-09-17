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
$id_professor = $_GET['id_professor'];

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');
if ($acao == 1) {
    $texto = "Editar";
    $acao_form = "update_professor.php";
    include('conn/conn.php');
    $query = "SELECT * FROM professor WHERE id_professor = $id_professor ORDER BY id_professor ASC";
    $result = mysqli_query($link, $query);
    while ($resultado = mysqli_fetch_assoc($result)) {

        $id_professor = $resultado['id_professor'];
        $nome_professor = $resultado['nome'];
        $email_professor = $resultado['email'];
        $telefone_professor = $resultado['telefone'];
        $formacao_professor = $resultado['formacao'];
        $imagem_professor = $resultado['imagem'];
    }
} else {

    $acao_form = "salva_professor.php";
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
                            <h3>Professor</h3>
                            <p class="text-subtitle text-muted">Cadastre ou edite um professor</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="professor.php">Professor</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> Professor</li>
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
                                    <h4 class="card-title"><?php echo $texto; ?> professor</h4>
                                </div>

                                <div class="card-body">
                                    <form method="POST" action="<?php echo $acao_form; ?>" enctype="multipart/form-data" id="formProfessor">
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Nome</h6>
                                                <div class="form-group">
                                                    <input type="text" value="<?php echo $nome_professor; ?>" class="form-control" placeholder="Informe o nome" name="nome" id="iNome">
                                                    <div id="dNome" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessário informar um nome</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Email</h6>
                                                <div class="form-group">
                                                    <input type="text" value="<?php echo $email_professor; ?>" class="form-control" placeholder="Informe um email" name="email" id="iEmail">
                                                    <div id="dEmail" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessário informar um email</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>Telefone</h6>
                                                <div class="form-group">
                                                    <input type="text" value="<?php echo $telefone_professor; ?>" class="form-control" placeholder="Informe um telefone" name="telefone" id="iTelefone">
                                                    <div id="dTelefone" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessário informar um telefone</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>Formação</h6>
                                                <div class="form-group">
                                                    <input type="text" value="<?php echo $formacao_professor; ?>" class="form-control" placeholder="Informe a formação principal" name="formacao" id="iFormacao">
                                                    <div id="dFormacao" class="invalid-feedback" style="display: none;"><i class="bx bx-radio-circle"></i>Necessário informar a formação</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>Foto</h6>
                                                <div class="mb-3">
                                                    <input value="<?php echo $imagem_professor; ?>" class="form-control" type="file" name="foto">
                                                </div>
                                            </div><input type="hidden" value="<?php echo $id_professor; ?>" name="id_professor">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <button type="button" onclick="validar()" class="btn btn-primary"><?php echo $texto; ?> Professor</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
    <script type="text/javascript">

        //Pega o elemento form
        var form = document.getElementById('formProfessor');

        //Pega os elementos de formulário
        var nome = document.getElementById('iNome');
        var divNome = document.getElementById('dNome');

        var email = document.getElementById('iEmail');
        var divEmail = document.getElementById('dEmail');

        var telefone = document.getElementById('iTelefone');
        var divTelefone = document.getElementById('dTelefone');

        var formacao = document.getElementById('iFormacao');
        var divFormacao = document.getElementById('dFormacao');

        function validar() {

            // Cria a validação
            if (nome.value == '' || email.value == '' || telefone.value == '' || formacao.value == '') {
                if (nome.value == '') {
                    nome.className += " is-invalid";
                    divNome.style.display = "block";
                }
                if (email.value == '') {
                    email.className += " is-invalid";
                    divEmail.style.display = "block";
                }
                if (telefone.value == '') {
                    telefone.className += " is-invalid";
                    divTelefone.style.display = "block";
                }
                if (formacao.value == '') {
                    formacao.className += " is-invalid";
                    divFormacao.style.display = "block";
                }
            } else {
                form.submit();
            }

        }


    </script>
</body>

</html>