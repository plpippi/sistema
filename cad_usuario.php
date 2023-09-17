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

include('conn/conn.php');
include('notifica_reserva.php');
include('menu.php');

$acao = $_GET['acao'];
$id_user = $_GET['id_usuario'];

if ($acao == 1) {
    $texto = "Editar";
    $acao_form = "update_user.php";
    include('conn/conn.php');
    $query = "SELECT * FROM user WHERE id_user = $id_user ORDER BY id_professor ASC";
    $result = mysqli_query($link, $query);
    while ($resultado = mysqli_fetch_assoc($result)) {

        $id_usuario = $resultado['id_user'];
        $nome = $resultado['nome'];
        $email = $resultado['email'];
        $acesso = $resultado['acesso'];
        $imagem = $resultado['imagem'];
    }
} else {

    $acao_form = "salva_user.php";
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
                            <h3>Usuário</h3>
                            <p class="text-subtitle text-muted">Cadastre ou edite um usuário</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="usuario.php">Usuários</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $texto; ?> Usuário</li>
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
                                    <form method="POST" action="<?php echo $acao_form; ?>" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Nome</h6>
                                                <div class="form-group">
                                                    <input type="text" value="<?php echo $nome; ?>" class="form-control" placeholder="Informe o nome" name="nome">
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>* Email</h6>
                                                <div class="form-group">
                                                    <input type="text" value="<?php echo $email; ?>" class="form-control" placeholder="Informe um email" name="email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <h6>Acesso</h6>
                                                <div class="form-group">
                                                    <select class="choices form-select multiple-remove" name="acesso">
                                                        <optgroup label="Cursos Disponiveis">
                                                            <option value="1">Pedagógico</option>
                                                            <option value="2">Direção</option>
                                                            <option value="3">Vendas</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mb-4">
                                                <h6>Foto</h6>
                                                <div class="mb-3">
                                                    <input value="<?php echo $imagem; ?>" class="form-control" type="file" name="foto">
                                                </div>
                                            </div><input type="hidden" value="<?php echo $id_usuario; ?>" name="id_usuario">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 mb-4">
                                                <button type="submit" class="btn btn-primary"><?php echo $texto; ?> Usuário</button>&nbsp;&nbsp;<button type="reset" class="btn btn-secondary">Limpar Formulário</button>
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