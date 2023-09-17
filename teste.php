<?php

    /*
    session_start();

    for($i = 1; $i <= 10; $i++){
        $exemplo[] = $i;
    }

    print_r($exemplo);

    $_SESSION['mensagem'] = $exemplo;

    echo "<a href='testedois.php'>Acessar</a>";
    */
/*
    function teste(){
        $teste[] = "Pablo";
        $teste[] = "Leandro";
        $teste[] = "Pippi";
        return $teste;
    }
    
    $nome = teste();
    
    $tamanho = count($nome);
    //echo $tamanho;
    for($i = 0; $i <= $tamanho; $i++){
        echo $nome[$i]."<br>";
    }

    echo date('d/m/Y', strtotime('+5 days', strtotime('14-07-2022')));
*/

$dias = $_POST['campo'];
print_r($dias);

?>