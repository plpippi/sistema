<?php

    include('conn/conn.php');

    $parecer = $_POST['parecer'];
    $aceite = $_POST['aceite'];
    $id_reserva = $_POST['id_reserva'];
    $query = "UPDATE reserva SET justificativa = '$parecer', aceite = $aceite WHERE id_reserva = $id_reserva";
    mysqli_query($link, $query);
    header('Location: reserva_equipamento.php');
    exit;

?>