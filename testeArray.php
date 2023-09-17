<?php

/*
Autor: Pablo Leandro Pippi
Data: 21/10/2022
Analise e Desenvolvimento de Sistemas Senac
*/

function imc($imc){
    $matrizImc = array(
        array('id'=> "Magreza" , 'inicio' => 0.00, 'fim' => 18.5),
        array('id'=> "Saudável" , 'inicio' => 18.51, 'fim' => 24.9),
        array('id'=> "Sobrepeso" , 'inicio' => 25.0, 'fim' => 29.9),
        array('id'=> "Obesidade Grau I" , 'inicio' => 30.0, 'fim' => 34.9),
        array('id'=> "Obesidade Grau II" , 'inicio' => 35.0, 'fim' => 39.9)
        
    );

    foreach($matrizImc as $intervalo) {

        if( ($imc >=  $intervalo['inicio']) && ($imc <= $intervalo['fim']) ){
            return "Atenção, seu IMC é ".$imc." e você está classificado
            com ".$intervalo['id'];
        }else if($imc > 39.9){
            return "Atenção, seu IMC é ".$imc." e você está classificado
            com Obesidade Grau III";
        }          
    }
}

echo imc(42.22);

?>