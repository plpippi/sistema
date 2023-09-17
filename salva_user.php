<?php

include('conn/conn.php');

$nome = $_POST['nome'];
$email = $_POST['email'];
$acesso = $_POST['acesso'];
$foto = $_FILES['foto'];

if($_FILES['foto']['size'] == 0)
{
    $new_name = "padrao.jpg";
}else{    
   $ext = strtolower(substr($_FILES['foto']['name'],-4)); //Pegando extensão do arquivo
   $new_name = date("Y.m.d-H.i.s") . $ext; //Definindo um novo nome para o arquivo
   $dir = 'imagens/users/'; //Diretório para uploads 
   move_uploaded_file($_FILES['foto']['tmp_name'], $dir.$new_name); //Fazer upload do arquivo
   echo("Imagen enviada com sucesso!");
}

$query = "INSERT INTO user (nome, email, acesso, imagem) VALUES ('$nome', '$email', '$acesso', '$new_name')";
mysqli_query($link, $query);

//print_r($query);
header('Location: usuario.php?pagina=usuario');
exit;

?>