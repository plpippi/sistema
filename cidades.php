<?php

// Inicio sessão
session_start();
error_reporting(0);
ini_set("display_errors", 0);
setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');


if(!isset($_SESSION['user'])){
    header('Location: index.html');
    exit;
}
$user = $_SESSION['user'];
$nome_user = $_SESSION['nome_user'];
$imagem_user = $_SESSION['imagem'];
$acesso_user = $_SESSION['acesso'];
// Fim sessão

include('conn/conn.php');
include('util.php');
include('notifica_reserva.php');
include('menu.php');

$query = "SELECT * FROM cidade ORDER BY id_cidade ASC";
$result = mysqli_query($link, $query);

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
                            <p class="text-subtitle text-muted">Lista de Cidades</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Cadastros | Cidades</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <a href="cad_cidades.php" class="btn btn-primary">Cadastrar Cidade</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cidade</th>
                                        <th>Unidade</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while($resultado = mysqli_fetch_assoc($result)){ ?>
                                    <?php
                                        if($resultado['status'] == 1){
                                            echo "<tr>";
                                        }else if ($resultado['status'] == 0){
                                            echo "<tr style='color: #C0C0C0;'>";
                                        }
                                    ?>
                                        <td><?php  echo $resultado['id_cidade']; ?></td>
                                        <td><?php  echo $resultado['nome_cidade']; ?></td>
                                        <td><?php  echo $resultado['id_unidade']; ?></td>
                                        <td>
                                            <?php 
                                                if($resultado['status'] == 1){
                                                    echo "<span class='badge bg-success'>Ativa</span>";
                                                }else if ($resultado['status'] == 0){
                                                    echo "<span class='badge bg-danger'>Desativada</span>";
                                                }
                                            ?> 
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Ações
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="cad_cidades.php?acao=1&id_cidade=<?php echo $resultado['id_cidade'];?>"><i class="bi bi-pencil-square"></i> &nbsp;Editar</a>
                                                <a class="dropdown-item" href="excluir.php?id_exclusao=<?php echo $resultado['id_cidade']; ?>&nome_tabela=cidade&id_tabela=id_cidade&local=cidades.php"><i class="bi bi-trash-fill"></i> &nbsp;Excluir</a>
                                                <div class="dropdown-divider"></div>
                                                <?php
                                                    if($resultado['status'] == 1){
                                                        echo "<a class='dropdown-item' href='desativa.php?tabela=cidade&id_tabela=id_cidade&id_item=".$resultado['id_cidade']."&local=cidade.php'><i class='bi bi-lock-fill'></i> &nbsp;Desativar</a>";
                                                    }else if ($resultado['status'] == 0){
                                                        echo "<a class='dropdown-item' href='ativa.php?tabela=cidade&id_tabela=id_cidade&id_item=".$resultado['id_cidade']."&local=cidade.php'><i class='bi bi-lock-fill'></i> &nbsp;Ativar</a>";
                                                    }
                                                ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section><br>
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
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    <script src="assets/js/main.js"></script>
</body>

</html>