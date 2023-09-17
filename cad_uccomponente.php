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
$id_uc_componente = $_GET['id_uc_componente'];

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

if ($acao == 1) {
    $texto = "Editar";
    $acao_form = "update_uccomponente.php";
    include('conn/conn.php');
    $query = "SELECT * FROM uc_componente WHERE id_uc_componente = $id_uc_componente ORDER BY id_uc_componente ASC";
    $result = mysqli_query($link, $query);
    while ($resultado = mysqli_fetch_assoc($result)) {

        $id_uccomponente = $resultado['id_uc_componente'];
        $nome = $resultado['nome'];
        $ch = $resultado['carga_horaria'];
        $descricao = $resultado['descricao'];
    }
} else {

    $acao_form = "salva_uccomponente.php";
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
                            <h3>UC | Componente</h3>
                            <p class="text-subtitle text-muted"><?php echo $texto; ?> ou edite uma UC | Componente</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="uccomponente.php">UC | Componente</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> UC | Componente</li>
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
                                    <h4 class="card-title"><?php echo $texto; ?> uc/componente</h4>
                                </div>

                                <div class="card-body">
                                    <form id="formuc" method="POST" action="<?php echo $acao_form; ?>">
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Nome</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $nome; ?>" placeholder="Informe o nome da UC/Componente" id="nome" name="nome">
                                                    <div id="dNome" class="invalid-feedback" style="display: none;">
                                                        <i class="bx bx-radio-circle"></i>
                                                        Necessário informar um nome de curso
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Carga Horaria</h6>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo $ch; ?>" placeholder="Informe a carga horária da UC/Componente" id="ch" name="ch">
                                                </div>
                                                <div id="dCh" class="invalid-feedback" style="display: none;">
                                                        <i class="bx bx-radio-circle"></i>
                                                        Necessário informar a carga horária
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 mb-4">
                                                <h6>Descrição</h6>
                                                <div class="form-group">
                                                    <textarea rows="6" class="form-control" placeholder="Informe a descrição" name="descricao"><?php echo $descricao; ?></textarea>
                                                </div><input type="hidden" name="id_uc_componente" value="<?php echo $id_uccomponente; ?>" </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 mb-4">
                                                    <button type="button" onclick="validar()" class="btn btn-primary"><?php echo $texto; ?> UC/Componente</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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
        var form = document.getElementById('formuc');

        //Pega os elementos de formulário
        var nome = document.getElementById('nome');
        var divNome = document.getElementById('dNome');

        var ch = document.getElementById('ch');
        var divCh = document.getElementById('dCh');

        function validar() {

            // Cria a validação
            if (nome.value == '' || ch.value == '') {
                if (nome.value == '') {
                    nome.className += " is-invalid";
                    divNome.style.display = "block";
                }
                if (ch.value == '') {
                    ch.className += " is-invalid";
                    divCh.style.display = "block";
                }
            } else {
                form.submit();
            }

        }
    </script>

</body>

</html>