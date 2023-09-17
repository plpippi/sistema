$query_uc = "SELECT * FROM curso_uc_componente INNER JOIN uc_componente ON curso_uc_componente.id_uc_componente = uc_componente.id_uc_componente WHERE id_curso = ".$resultado['id_curso']."";
$result_uc = mysqli_query($link, $query_uc);
$rows = mysqli_num_rows($query_uc);
if($rows == ''){
    echo "UCs não vinculadas";
}else{
    while($resultado_uc = mysqli_fetch_assoc($result_uc){
        echo $resultado_uc['id_curso'];
    }
}