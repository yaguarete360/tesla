<?php

$ano_a_procesar = 2017;

include "../funciones/conectar-base-de-datos.php";

$consulta_seleccion = 'SELECT *
    FROM visitas
    WHERE borrado LIKE "no"
    AND visita LIKE "'.$ano_a_procesar.'-%"
    ORDER BY visita
    DESC
    LIMIT 1';

$query_seleccion = $conexion->prepare($consulta_seleccion);
$query_seleccion->execute();

while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
{
	$numero = $rows_seleccion['visita'];
} 
if(!isset($numero))
{
	$numero = 1;
}
else
{
	$numero_partes = explode("-", $numero);
	$numero = (int)$numero_partes[1];	
}

$consulta = 'SELECT id,fecha,creado,modificado FROM visitas WHERE fecha LIKE "'.$ano_a_procesar.'-%" ORDER BY creado ASC';
$query = $conexion->prepare($consulta);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{	
	$numero_a_usar = $ano_a_procesar."-".str_pad($numero, 7, "0", STR_PAD_LEFT);

	// echo $numero_a_usar.' - '.$rows['id'].' - '.$rows['fecha'].' - '.$rows['creado'].'<br/>';

	$insercion = 'UPDATE `visitas` SET `visita` = "'.$numero_a_usar.'" WHERE `id` = '.$rows['id'];
	
	$query_seleccion = $conexion->prepare($insercion);
	$query_seleccion->execute();

	echo $insercion;
	echo '<br/>';

	$numero++;
}
?>