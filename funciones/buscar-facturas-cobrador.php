<?php
include 'conectar-base-de-datos.php';
$cobrador = $_POST['cobrador'];

/*************************BUSCAR FACTURA****************/
$consulta = "SELECT * FROM formularios 
where custodia_2 like '%".$cobrador."%' 
AND fecha_de_uso='0000-00-00'";
$query_resultados = $conexion->prepare($consulta);
$query_resultados->execute();
$resultado = $query_resultados->fetchAll();
$factura = array();
foreach ($resultado as $value){
    $factura[] = $value['formulario_numero'];
}

if(count($factura)<=0){
    $factura[] = "No Posee";
}
/****************************************************/
$parent = array("factura"=>$factura);
echo json_encode($parent);

?>