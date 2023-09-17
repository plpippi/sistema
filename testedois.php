<?php

    session_start();

    $exemplo = $_SESSION['mensagem'];

    print_r($exemplo);
    echo "<br>";
    echo $exemplo[2];
    echo"<br><br>";

    echo "<a href='teste.php'>Voltar</a>";


?>