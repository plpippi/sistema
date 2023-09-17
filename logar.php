<?php

include('conn/conn.php');
//mysqli_select_db('user') or trigger_error(mysqli_error());

session_start();

$usuario = $_POST['email'];
$senha = $_POST['senha'];

// Validação do usuário/senha digitados
$sql = "SELECT * FROM user WHERE (email = '" . $usuario . "' AND senha = '" . $senha . "') AND status = 1";
$query = mysqli_query($link, $sql);
$linhas = mysqli_num_rows($query);

if ($linhas != 1) {
    $sqlProf = "SELECT * FROM professor WHERE (email = '" . $usuario . "' AND senha = '" . $senha . "') AND status = 1";
    $queryProf = mysqli_query($link, $sqlProf);
    $linhasProf = mysqli_num_rows($queryProf);

    if ($linhasProf > 0) {
        $sql = "SELECT * FROM professor WHERE (email = '" . $usuario . "' AND senha = '" . $senha . "')";
        $resultado = mysqli_query($link, $sql);
        while ($result = mysqli_fetch_assoc($resultado)) {
            $_SESSION['id_user'] = $result['id_professor'];
            $_SESSION['nome'] = $result['nome'];
            $_SESSION['imagem'] = $result['imagem'];
            $_SESSION['acesso'] = $result['acesso'];
            $_SESSION['user'] = $usuario;
            $_SESSION['senha'] = $senha;
        }
        header("Location: professor/inicio.php?pagina=inicio");
    } else {
        $query_user = "SELECT * FROM user WHERE email = '$usuario'";
        $resultado_user = mysqli_query($link, $query_user);
        $_SESSION['user'] = $usuario;
        $_SESSION['senha'] = $senha;
        while ($linha = mysqli_fetch_assoc($resultado_user)) {
            $status = $linha['status'];
        }
        switch ($status) {
            case 0:
                // Mensagem de erro quando o usuario estiver desativado
                header("Location: index.php?erro=Usuário desativado do sistema contate o ADM.");
                break;
            case 1:
                // Mensagem de erro quando os dados são inválidos e/ou o usuário não foi encontrado
                header("Location: index.php?erro=Usuário ou senha incorretos.");
                break;
        }
    }

} else {
    // Salva os dados encontados na variável $resultado
    $sql = "SELECT * FROM user WHERE (email = '" . $usuario . "' AND senha = '" . $senha . "')";
    $resultado = mysqli_query($link, $sql);
    while ($result = mysqli_fetch_assoc($resultado)) {
        $_SESSION['nome_user'] = $result['nome'];
        $_SESSION['imagem'] = $result['imagem'];
        $_SESSION['acesso'] = $result['acesso'];
        $acesso = $result['acesso'];
    }
    $_SESSION['user'] = $usuario;
    $_SESSION['senha'] = $senha;
    switch ($acesso) {
        case 1:
            header("Location: inicio.php");
            break;
        case 2:
            header("Location: vendas/inicio.php");
            break;
        case 3:
            header("Location: adm/inicio.php");
            break;
    }
}
