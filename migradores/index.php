<?php if (!isset($_SESSION)) {session_start();}

echo "<h1>PROGRAMA DE MIGRACION Y BIFURCACION DE DATOS AXIS 1.0</h1><hr/>";

include '../migradores/funciones/1-elegir-parametros.php';

if(isset($_GET['declaracion_de_la_tabla']) and !empty($_GET['declaracion_de_la_tabla']))
{
	$desde_el_registro       = $_GET['desde_el_registro'];
	$hasta_el_registro       = $_GET['hasta_el_registro'];
	$declaracion_de_la_tabla = $_GET['declaracion_de_la_tabla'];

	echo $declaracion_de_la_tabla.'<br/>';

	include '../migradores/tablas/'.$declaracion_de_la_tabla;

	include '../migradores/funciones/2-armar-control.php';
			
	include '../migradores/funciones/3-armar-insercion.php';

	include '../migradores/funciones/4-insertar.php';

}
		
?>
