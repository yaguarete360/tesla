<?php if(!isset($_SESSION)) {session_start();}

$_SESSION['desde'] = !isset($_GET['desde']) ? 1 : $_GET['desde'];
$_SESSION['hasta'] = !isset($_GET['hasta']) ? 100 : $_GET['hasta'];
$_SESSION['tabla_destino'] = !isset($tabla_destino) ? "" : $tabla_destino;

echo '<form>';
	echo 'Desde el <b>ID</b> numero: <input type="text" name="desde" value="'.$_SESSION['desde'].'">';
	echo '<br/>';
	echo 'Hasta el <b>ID</b> numero: <input type="text" name="hasta" value="'.$_SESSION['hasta'].'">';
	echo '<br/>';
	echo '<input type="hidden" name="datos_tablas" value="'.$_GET['datos_tablas'].'">';
	echo '<input type="submit" name="procesar" value="Procesar">';
echo '</form>';
echo '<hr/>'; 

?>
