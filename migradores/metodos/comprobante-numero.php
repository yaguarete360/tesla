<?php if (!isset($_SESSION)) {session_start();}

$valor = $rows_origen[$campo_atributo['serie']];

$series_anteriores = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",);

if(in_array($valor, $series_anteriores))
{
	$valor = 'serie-'.trim(strtolower($valor)).'-'.str_pad((int)$rows_origen[$campo_atributo['numero_factura']],7,"0",STR_PAD_LEFT);
}
else
{
	$valor = "000-000-".str_pad((int)$rows_origen[$campo_atributo['numero_factura']],7,"0",STR_PAD_LEFT);
}

?>
