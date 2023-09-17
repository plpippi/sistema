<?php

include('conn/conn.php');

$queryNoti = "SELECT * FROM reserva WHERE aceite = 0";
$resultNoti = mysqli_query($link, $queryNoti);
$linhas = mysqli_num_rows($resultNoti);
if ($linhas > 0) {
    $style = "display: block;";
    $totalNoti = $linhas;
    $linkNoti = "reserva_equipamento.php";
} else {
    $style = "display: none;";
    $linkNoti = "#";
}

?>