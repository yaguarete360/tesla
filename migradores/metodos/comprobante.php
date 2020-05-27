<?php if (!isset($_SESSION)) {session_start();}

if(isset($campo_atributo['tipo']))
{
	
	$valor = trim(strtolower($rows_origen[$campo_atributo['tipo']]));

	if(is_null($valor))     $valor = "debito a vencer";
	if($valor == "")        $valor = "debito a vencer";
	if($valor == "contado") $valor = "factura contado";
	if($valor == "credito") $valor = "factura credito";
	if($valor == "n.credi") $valor = "nota de credito";
	
	if($rows_origen[$campo_atributo['serie-vieja']] > 0) $valor = "factura anterior";
}
else
{
	if(isset($rows_origen[$campo_atributo['recibo']]) and $rows_origen[$campo_atributo['recibo']] > 0)
	{
		$valor = "recibo";
	}
	else
	{
		$valor = "sin datos";	
	} 

}

$caso = "";

?>
