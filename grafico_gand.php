<?php

$ano = date('Y');
$nomeMes = array(
1=>'Janeiro',
2=>'Fevereiro',
3=>'Março',
4=>'Abril',
5=>'Maio',
6=>'Junho',
7=>'Julho',
8=>'Agosto',
9=>'Setembro',
10=>'Outubro',
11=>'Novembro',
12=>'Dezembro'
);

$janeiro = date('d', mktime(0, 0, 0, 2, 0, $ano));
$fevereiro = date('d', mktime(0, 0, 0, 3, 0, $ano));
$março = date('d', mktime(0, 0, 0, 4, 0, $ano));
$abril = date('d', mktime(0, 0, 0, 5, 0, $ano));
$maio = date('d', mktime(0, 0, 0, 6, 0, $ano));
$junho = date('d', mktime(0, 0, 0, 7, 0, $ano));
$julho = date('d', mktime(0, 0, 0, 8, 0, $ano));
$agosto = date('d', mktime(0, 0, 0, 9, 0, $ano));
$setembro = date('d', mktime(0, 0, 0, 10, 0, $ano));
$outubro = date('d', mktime(0, 0, 0, 11, 0, $ano));
$novembro = date('d', mktime(0, 0, 0, 12, 0, $ano));
$dezembro = date('d', mktime(0, 0, 0, 13, 0, $ano));

?>

<html>
    <head>
        <style type="text/css">
table tr > td{
background: #dfdfdf;
padding: 0.3rem 0.6rem;
border-bottom: 1px solid #ccc
}

table > thead > tr > th {
  background: darkblue;
  position: sticky;
  top: 0; /* Don't forget this, required for the stickiness */
  color: #ffffff;
  padding: 0.3rem 0.6rem;
  min-width: 6rem;
}
        </style>
    </head>
    <body>


        <table>
            <thead>
            <tr>
                <?php
                echo "<th>&nbsp;</th>";
                $contador = 2;
                for($c = 1;$c<=12;$c++){
                    echo "<th colspan=".date('d', mktime(0, 0, 0, $contador, 0, $ano)).">";
                    echo $nomeMes[$c];
                    echo "</th>";
                    $contador = $contador + 1;
                }
                ?>
            </tr>
            <tr>
                <?php
                echo "<th>&nbsp;</th>";
                $contador = 2;
                for($y = 1;$y <= 12; $y++){
                    for($i = 1;$i <= date('d', mktime(0, 0, 0, $contador, 0, $ano));$i++){
                        echo "<th>";
                        echo $i;
                        echo "</th>";
                    }
                    $contador = $contador + 1;
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
                $contador = 2;
                for($l = 1;$l <= 100; $l++){
                    echo "<tr>";
                    echo "<th>Sala ".$l."</th>";
                for($y = 1;$y <= 12; $y++){
                    for($i = 1;$i <= date('d', mktime(0, 0, 0, $contador, 0, $ano));$i++){
                        echo "<th>";
                        echo $i;
                        echo "</th>";
                    }
                    $contador = $contador + 1;
                }
                echo "</tr>";
            }
                ?>
            </tbody>
        </table>




</body>
</html>