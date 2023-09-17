<?php

// Inicio sessão
session_start();

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

$query = "SELECT * FROM professor ORDER BY id_professor ASC";
$result = mysqli_query($link, $query);

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <?php echo $cabecalho; ?>
    <style>
        .img {
            width: 40px;
            height: 40px;
            border-radius: 50px;
        }
    </style>
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
                            <p class="text-subtitle text-muted">Lista de Professores</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Professores</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <a href="cad_professor.php" class="btn btn-primary">Cadastrar Professor</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Telefone</th>
                                        <th>Formação</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($resultado = mysqli_fetch_assoc($result)) { ?>
                                        <?php
                                        if ($resultado['status'] == 1) {
                                            echo "<tr>";
                                        } else if ($resultado['status'] == 0) {
                                            echo "<tr style='color: #C0C0C0;'>";
                                        }
                                        ?>
                                        <td><?php echo $resultado['id_professor']; ?></td>
                                        <td><img src="imagens/users/<?php echo $resultado['imagem']; ?>" class="img"></td>
                                        <td><?php echo $resultado['nome']; ?></td>
                                        <td><?php echo $resultado['email']; ?></td>
                                        <td><?php echo $resultado['telefone']; ?></td>
                                        <td><?php echo $resultado['formacao']; ?></td>
                                        <td>
                                            <?php
                                            if ($resultado['status'] == 1) {
                                                echo "<span class='badge bg-success'>Ativa</span>";
                                            } else {
                                                echo "<span class='badge bg-danger'>Desativado</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Ações
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="cad_professor.php?acao=1&id_professor=<?php echo $resultado['id_professor']; ?>"><i class="bi bi-pencil-square"></i> &nbsp;Editar</a>
                                                <a class="dropdown-item" href="excluir.php?id_exclusao=<?php echo $resultado['id_professor']; ?>&nome_tabela=professor&id_tabela=id_professor&local=professor.php"><i class="bi bi-trash-fill"></i> &nbsp;Excluir</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal<?php echo $resultado['id_professor']; ?>"><i class="bi bi-people-fill"></i> &nbsp;Substituir Professor</a>
                                                <div class="dropdown-divider"></div>
                                                <?php
                                                if ($resultado['status'] == 1) {
                                                    echo "<a class='dropdown-item' href='desativa.php?tabela=professor&id_tabela=id_professor&id_item=" . $resultado['id_professor'] . "&local=professor.php'><i class='bi bi-lock-fill'></i> &nbsp;Desativar</a>";
                                                } else if ($resultado['status'] == 0) {
                                                    echo "<a class='dropdown-item' href='ativa.php?tabela=professor&id_tabela=id_professor&id_item=" . $resultado['id_professor'] . "&local=professor.php'><i class='bi bi-lock-fill'></i> &nbsp;Ativar</a>";
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        </tr>

                                        <!-- Inicio Modal -->

                                        <div class="modal fade text-left" id="modal<?php echo $resultado['id_professor']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel33">Alterar Professor</h4>
                                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                            <i data-feather="x"></i>
                                                        </button>
                                                    </div>
                                                    <form method="POST" action="troca_professor.php" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <label>Professor</label>
                                                            <div class="form-group">
                                                                <input type="text" value="<?php echo $resultado['nome']; ?>" class="form-control" disabled><input type="hidden" name="id_professor" value="<?php echo $resultado['id_professor']; ?>">
                                                            </div>
                                                            <h6>UC/Componente</h6>
                                                            <div class="input-group mb-3">
                                                                <label class="input-group-text" for="inputGroupSelect01">Turma</label>
                                                                <select class="form-select" name="id_turma">
                                                                    <?php
                                                                    $query_turma = "SELECT aula.id_turma, turma.numero_turma FROM aula INNER JOIN turma ON aula.id_turma = turma.id_turma WHERE aula.id_professor = " . $resultado['id_professor'] . " GROUP BY aula.id_turma";
                                                                    $result_turma = mysqli_query($link, $query_turma);
                                                                    while ($resultado_turma = mysqli_fetch_assoc($result_turma)) {
                                                                        echo "<option value=" . $resultado_turma['id_turma'] . ">" . $resultado_turma['numero_turma'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>


                                                            <h6>Trocar por</h6>
                                                            <div class="input-group mb-3">
                                                                <label class="input-group-text" for="inputGroupSelect01">Professor</label>
                                                                <select class="form-select" name="id_novo_professor">
                                                                    <?php
                                                                    $query_professor = "SELECT * FROM professor ORDER BY id_professor ASC";
                                                                    $result_professor = mysqli_query($link, $query_professor);
                                                                    while ($resultado_professor = mysqli_fetch_assoc($result_professor)) {
                                                                        echo "<option value=" . $resultado_professor['id_professor'] . ">" . $resultado_professor['nome'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>


                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary ml-1">
                                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                                <span class="d-none d-sm-block">Salvar Alterações</span>
                                                            </button>
                                                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                                <span class="d-none d-sm-block">Cancelar</span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Fim Modal -->

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