<?php if (!isset($_SESSION)) {session_start();}

$unidos = array('dato_1','dato_2','dato_3','dato_4','dato_5','dato_6','dato_7','dato_8','dato_9');

$valor = "";

foreach($unidos as $unido)
{
	if(isset($unido) and !empty($rows_origen[$campo_atributo[$unido]]))
	{
	 	$valor.= trim(strtolower($rows_origen[$campo_atributo[$unido]])).$campo_atributo['separador'];
	 	$valor = str_replace("'", "`", $valor);
	 	$valor = str_replace('"', '`', $valor);
	}
}

if(empty($valor)) $valor = "sin mas";

?>
