<?php if (!isset($_SESSION)) {session_start();}

$valor = trim(strtolower($rows_origen[$campo_atributo['campo']]));
if(isset($campo_atributo['vacio']) and empty($valor)) $valor = $campo_atributo['vacio'];

?>
