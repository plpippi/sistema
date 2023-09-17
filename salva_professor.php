<?php

include('conn/conn.php');

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$formacao = $_POST['formacao'];
$foto = $_FILES['foto'];


if($_FILES['foto']['size'] == 0)
{
    $new_name = "padrao.jpg";
    //echo $_FILES['foto']['size'];
}else{
    /*echo "Tem Foto";
    echo $_FILES['foto']['size'];*/
    $ext = strtolower(substr($_FILES['foto']['name'],-4)); //Pegando extensão do arquivo
    $new_name = date("Y.m.d-H.i.s") . $ext; //Definindo um novo nome para o arquivo
    $dir = 'adm/imagens/users/'; //Diretório para uploads 
    move_uploaded_file($_FILES['foto']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
    echo("Imagen enviada com sucesso!");
}

$query = "INSERT INTO professor (nome, email, telefone, formacao, imagem) VALUES ('$nome', '$email', '$telefone', '$formacao', '$new_name')";
mysqli_query($link, $query);

header('Location: professor.php?pagina=professor');
exit;


?>