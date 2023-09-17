<?php

// Inicio sessão
session_start();

error_reporting(0);
ini_set("display_errors", 0);

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
include('util.php');
include('notifica_reserva.php');
include('menu.php');

$query = "SELECT reserva.justificativa AS parecer, reserva.id_reserva AS id, reserva.aceite AS status, reserva.data AS data, reserva.hora_retirada AS retirada, reserva.hora_devolucao AS devolucao, 
professor.nome AS nome, equipamento.tipo AS tipo, equipamento.numero as numero FROM reserva 
INNER JOIN professor ON reserva.id_pessoa = professor.id_professor 
INNER JOIN equipamento ON reserva.id_equipamento = equipamento.id_equipamento ORDER BY id_reserva ASC";
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
                            <p class="text-subtitle text-muted">Lista de Reservas</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Reserva de Equipamentos</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <a href="cad_reserva.php" class="btn btn-primary">Reservar</a>
                            <a href="listar_reserva.php" class="btn btn-secondary">Ver Reservas</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Dia</th>
                                        <th>Retirada</th>
                                        <th>Devolução</th>
                                        <th>Parecer</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($resultado = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?php echo $resultado['id']; ?></td>
                                        <td><?php echo $resultado['nome']; ?></td>
                                        <td><?php echo converte_data($resultado['data']); ?></td>
                                        <td><?php echo $resultado['retirada']; ?></td>
                                        <td><?php echo $resultado['devolucao']; ?></td>
                                        <td><?php echo $resultado['parecer']; ?></td>
                                        <td>
                                            <?php
                                                    if($resultado['status'] == 0){
                                                        echo "<span class='badge bg-secondary'>Em análise</span>";
                                                    }else if($resultado['status'] == 1){
                                                        echo "<span class='badge bg-success'>Aceito</span>";
                                                    }else{
                                                        echo "<span class='badge bg-danger'>Não aceito</span>";
                                                    }
                                                ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Ações
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="cad_reserva.php?acao=1&id_reserva=<?php echo $resultado['id']; ?>"><i
                                                        class="bi bi-pencil-square"></i> &nbsp;Editar</a>
                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#default<?php echo $resultado['id']; ?>" href="#"><i
                                                        class="bi bi-trash-fill"></i> &nbsp;Excluir</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#parecer<?php echo $resultado['id']; ?>" href="#"><i
                                                        class="bi bi-pencil-square"></i> &nbsp;Parecer</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Inicio Modail Excluir -->
                                    <div class="modal fade text-left" id="default<?php echo $resultado['id']; ?>"
                                        tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel1">Deseja realmente excluir
                                                        a reserva?</h5>
                                                    <button type="button" class="close rounded-pill"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6>Quem certeza que deseja realmente excluir a reserva
                                                        <?php echo $resultado['tipo']; ?> para
                                                        <?php echo $resultado['nome']; ?>.</h6>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn" data-bs-dismiss="modal">
                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                        <span class="d-none d-sm-block">Cancelar</span>
                                                    </button>
                                                    <a class="btn btn-primary"
                                                        href="excluir.php?id_exclusao=<?php echo $resultado['id']; ?>&id_tabela=id_reserva&nome_tabela=reserva&local=reserva_equipamento.php"><i
                                                            class="bi bi-trash-fill"></i> &nbsp;Excluir</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fim Modal Excluir -->

                                    <!-- Inicio Modal Parecer -->

                                    <div class="modal fade text-left" id="parecer<?php echo $resultado['id']; ?>"
                                        tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel1">Informe o parecer
                                                        para
                                                        a reserva?</h5>
                                                    <button type="button" class="close rounded-pill"
                                                        data-bs-dismiss="modal" aria-label="Close">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="form" method="POST" action="parecer.php">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Status</label>
                                                            <select class="form-control" name="aceite">
                                                                <option value="1">Aceito a Reserva</option>
                                                                <option value="2">Não Aceito a Reserva</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleFormControlTextarea1">Parecer</label>
                                                            <textarea class="form-control" name="parecer"></textarea>
                                                            <input type="hidden" name="id_reserva" value="<?php echo $resultado['id']; ?>"
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn" data-bs-dismiss="modal">
                                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                                <span class="d-none d-sm-block">Cancelar</span>
                                                            </button>
                                                            <button type="submit" class="btn btn-primary"><i
                                                                    class="bi bi-chat-right-quote-fill"></i>
                                                                &nbsp;Enviar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fim Modal Parecer -->

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>
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