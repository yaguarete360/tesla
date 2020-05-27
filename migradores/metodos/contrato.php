<?php if (!isset($_SESSION)) {session_start();}

$valor = (int)$rows_origen[$campo_atributo['linea']].'-';
$valor.= strtolower($rows_origen[$campo_atributo['centro']]).'-';
$valor.= str_pad((int)$rows_origen[$campo_atributo['numero']],6,"0",STR_PAD_LEFT).'/';
if(strpos($rows_origen[$campo_atributo['numero']],".") > 0)
{
	$decimales = explode(".", $rows_origen[$campo_atributo['numero']]);
	$extension = $decimales[1];
	$valor.= $decimales[1];
}
else
{
	$valor.= '00';
}

?>
