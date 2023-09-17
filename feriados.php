<?php

// Inicio sessão
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

include('conn/conn.php');
include('util.php');
include('notifica_reserva.php');
include('menu.php');

$query = "SELECT dias_n_letivos.*, cidade.nome_cidade AS cidade FROM dias_n_letivos INNER JOIN cidade ON dias_n_letivos.id_cidade = cidade.id_cidade ORDER BY dia ASC";
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
                            <p class="text-subtitle text-muted">Lista de feriados ou dias não letivos</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Feriados | Dias não letivos</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <a href="cad_feriados.php" class="btn btn-primary">Cadastrar Dia</a> 
                            <a href="calendario_feriados.php" class="btn btn-secondary"><i class='bi bi-calendar-day-fill'></i> Visualizar Calendário</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Data</th>
                                        <th>Tipo</th>
                                        <th>Descrição</th>
                                        <th>Cidade</th>
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
                                        <td><?php  echo $resultado['id_dia_n_letivo']; ?></td>
                                        <td><?php  echo converte_data($resultado['dia']); ?></td>
                                        <td><?php  echo $resultado['tipo']; ?></td>
                                        <td><?php  echo $resultado['descricao']; ?></td>
                                        <td><?php  echo $resultado['cidade']; ?></td>
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
                                                <a class="dropdown-item" href="cad_feriados.php?acao=1&id_dia_n_letivo=<?php echo $resultado['id_dia_n_letivo'];?>"><i class="bi bi-pencil-square"></i> &nbsp;Editar</a>
                                                <a class="dropdown-item" href="excluir.php?id_exclusao=<?php echo $resultado['id_dia_n_letivo']; ?>&nome_tabela=dias_n_letivos&id_tabela=id_dia_n_letivo&local=feriados.php"><i class="bi bi-trash-fill"></i> &nbsp;Excluir</a>
                                                <div class="dropdown-divider"></div>
                                                <?php
                                                    if($resultado['status'] == 1){
                                                        echo "<a class='dropdown-item' href='desativa.php?tabela=dias_n_letivos&id_tabela=id_dia_n_letivo&id_item=".$resultado['id_dia_n_letivo']."&local=feriados.php'><i class='bi bi-lock-fill'></i> &nbsp;Desativar</a>";
                                                    }else if ($resultado['status'] == 0){
                                                        echo "<a class='dropdown-item' href='ativa.php?tabela=dias_n_letivos&id_tabela=id_dia_n_letivo&id_item=".$resultado['id_dia_n_letivo']."&local=feriados.php'><i class='bi bi-lock-fill'></i> &nbsp;Ativar</a>";
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
</body>

</html>