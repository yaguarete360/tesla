<?php if (!isset($_SESSION)) {session_start();}

$valor = $campo_atributo['tabla_origen'];
if(!empty($campo_atributo['campo_disyuntor'])) $valor.= '-'.trim(strtolower($campo_atributo['campo_disyuntor']));
if(!empty($campo_atributo['doble_disyuntor'])) $valor.= '-'.trim(strtolower($campo_atributo['doble_disyuntor']));
$valor.= '*'.$rows_origen[$campo_atributo['id']];

?>
