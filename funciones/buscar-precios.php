<?php
include 'conectar-base-de-datos.php';
$edad = $_POST['edad'];
$linea = $_POST['linea'];
$centro = $_POST['centro'];
$producto = $_POST['producto'];
$producto = explode("-",$producto);
$anho = date("Y");
$linea_numero = 0;

if($linea=="psm"){
	$linea_numero = 2;
}else{
	$linea_numero = 1;
}


if(strtolower($producto[1])=="pno")
{
    $producto_tipo = "platino";
}
if(strtolower($producto[1])=="cel")
{
    $producto_tipo = "celestial";
}

if(strtolower($producto[1])=="oro")
{
    $producto_tipo = "oro";
}

if(strtolower($producto[1])=="plata" || strtolower($producto[1])=="pla")
{
    $producto_tipo = "plata-1";
}

if(strtolower($producto[1])=="sla")
{
    $producto_tipo = "plan a san lorenzo";
}

if(strtolower($producto[1])=="slb")
{
    $producto_tipo = "plan b san lorenzo";
}

if(strtolower($producto[1])=="sja")
{
    $producto_tipo = "plan a sajonia";
}

if(strtolower($producto[1])=="sjb")
{
    $producto_tipo = "plan b sajonia";
}


if(strtolower($producto[1])=="hom")
{
    $producto_tipo = "homenaje";
}

if(strtolower($producto[1])=="hom")
{
    $producto_tipo = "celestial";
}

if(strtolower($producto[1])=="mr1")
{
    $producto_tipo = "mariano roque alonso a";
}

if(strtolower($producto[1])=="mr2")
{
    $producto_tipo = "mariano roque alonso b";
}

if($linea==1){
	$linea = 'pse';
}

if($linea==2){
	$linea = 'mem';
}

$precio = "precios en la web ".$linea." ".$producto[0]."";

$busqueda = $linea." ".$centro;
$anho = date("Y");
$consulta_precios = 'SELECT RIGHT(agrupador, 3) as centro,descripcion as plan,dato_1 as anho, 
                     dato_2 as precio,
					 dato_3,dato_4,left(dato_3,2) as desde,
					 RIGHT(dato_3,2) as hasta,dato_4 as meses 
					 FROM `agrupadores` 
					 WHERE agrupador like "%'.$precio.'%" AND 
					 dato_1 ="'.$anho.'" AND 
					 upper(RIGHT(agrupador, 3)) like upper("'.$producto[0].'") AND
                     descripcion like "'.$producto_tipo.'" AND
					 '.$edad.' between LEFT(dato_3,2) AND  
					 RIGHT(dato_3,2) 
					 ORDER BY `agrupadores`.`modificado` DESC';
//echo $consulta_precios;
//die();
$query_resultados = $conexion->prepare($consulta_precios);
$query_resultados->execute();
$resultado = $query_resultados->fetchAll();
$precio = 0;
foreach ($resultado as $value){
    $precio = $value['precio'];
}
$parent = array("precio"=>$precio);
echo  json_encode($parent);

?>