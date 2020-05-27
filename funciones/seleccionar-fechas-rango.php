<?php if (!isset($_SESSION)) {session_start();}

$rotulo = "&nbsp &nbsp Desde &nbsp &nbsp";
$indice = 1;
$variable_nombre = "fecha_desde";
include $url.'funciones/seleccionar-fechas.php';
$rotulo = "&nbsp &nbsp Hasta &nbsp &nbsp";
$indice = 2;
$variable_nombre = "fecha_hasta";
include $url.'funciones/seleccionar-fechas.php';

?>
