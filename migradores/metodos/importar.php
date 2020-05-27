<?php if (!isset($_SESSION)) {session_start();}

if(isset($campo_atributo['clave-tipo']) and $campo_atributo['clave-tipo'] == "numero")
{
	$valor = (int)$rows_origen[$campo_atributo['campo']];
}
else
{
	$valor = $rows_origen[$campo_atributo['campo']];
}

$consulta_foraneos = 'SELECT * 
FROM  '.$campo_atributo['tabla'].'
WHERE '.$campo_atributo['clave'].'
LIKE "'.$valor.'"';

$query_foraneos = $conexion_mig->prepare($consulta_foraneos);
$query_foraneos->execute();

while($rows_foraneos = $query_foraneos->fetch(PDO::FETCH_ASSOC))
{
	if(isset($campo_atributo['dato_1']) and $campo_atributo['dato_1'] != "")
	{
		$valor = trim(strtolower($rows_foraneos[$campo_atributo['dato_1']]));
	}
	if(isset($campo_atributo['separador']) and $campo_atributo['separador'] != "")
	{
		$valor.= $campo_atributo['separador'];
	}
	if(isset($campo_atributo['dato_2']) and $campo_atributo['dato_2'] != "")
	{	
		$valor.= trim(strtolower($rows_foraneos[$campo_atributo['dato_2']]));
	}
	$vacio = "";
}

if(!isset($vacio) or $valor == "") $valor = "sin datos";

?>
