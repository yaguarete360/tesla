<?php
include 'conectar-base-de-datos.php';
$precio = $_POST['precio'];
$producto_varchar = $_POST['producto_varchar'];
$edad = $_POST['edad'];
$linea = $_POST['linea'];
$producto = $_POST['producto'];
$producto = explode("-",$producto);

$anho = date("Y");

$tipo_producto = explode("-",$producto_varchar);
$tipo = null;
if($tipo_producto[0]=="psm"){
    $tipo = $tipo_producto[1];
}else{
    if(count($tipo_producto)>1){
        $tipo = $tipo_producto[0]."-".$tipo_producto[1];
    }else{
        $tipo = $tipo_producto[0];
    }

}

if($linea=="psm"){
    $linea_numero = 2;
}else{
    $linea_numero = 1;
}


if($linea==1){
    $linea = 'pse';
}

if($linea==2){
    $linea = 'mem';
}

/*****************BUSCAR PRECIO MINIMO*************/
$consulta_precios = "SELECT * 
                 FROM agrupadores 
                 WHERE agrupador LIKE '%".$precio."%' AND 
                 descripcion LIKE '%".$tipo."%' AND 
                 dato_1 = ".$anho." AND
                 dato_3 = 'minimo'";

//zecho $consulta_precios;die();
$query_resultados = $conexion->prepare($consulta_precios);
$query_resultados->execute();
$resultado = $query_resultados->fetchAll();
$precio_minimo = 0;
foreach ($resultado as $value){
    $precio_minimo = str_replace(".","",$value['dato_2']);
}
/***************************************************/

$precio = "precios en la web ".$linea." ".$producto[0]."";

if(count($producto)<=0){
    $des = $producto;
}
if(count($producto)==1){
    $des = $producto[0];
}

if(count($producto)==2){
    $des = $producto[1];
}

/*************BUSCAR PRECIO POR EDAD****************/
$consulta_precios = 'SELECT RIGHT(agrupador, 3) as centro,descripcion as plan,dato_1 as anho, 
                     dato_2 as precio,
					 dato_3,dato_4,left(dato_3,2) as desde,
					 RIGHT(dato_3,2) as hasta,dato_4 as meses 
					 FROM `agrupadores` 
					 WHERE agrupador like "%'.$precio.'%" AND 
					 dato_1 ="'.$anho.'" AND 
					 upper(RIGHT(agrupador, 3)) like upper("'.$producto[0].'") AND
                     descripcion like "'.$des.'" AND
					 '.$edad.' between LEFT(dato_3,2) AND  
					 RIGHT(dato_3,2) 
					 ORDER BY `agrupadores`.`modificado` DESC';
//echo $consulta_precios;die();
$query_resultados = $conexion->prepare($consulta_precios);
$query_resultados->execute();
$resultado = $query_resultados->fetchAll();
$precio = 0;
foreach ($resultado as $value){
    $precio = str_replace(".","",$value['precio']);
}

if($precio<=0){
    $precio = str_replace(".","",$precio_minimo);
}
/****************************************************/

$parent = array("precio_minimo"=>$precio_minimo,"precio"=>$precio);
echo  json_encode($parent);

?>