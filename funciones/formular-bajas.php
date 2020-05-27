<?php if (!isset($_SESSION)) {session_start();}				

$esta_vista = basename(__FILE__);

$tabla_a_procesar = $_GET['tabla_a_procesar'];

$url = "../";
include "../funciones/mostrar-cabecera.php";

include '../vistas/datos/'.$tabla_a_procesar.'.php';

$ind = 0;

echo '<div class="top-header"';
	echo 'style="background-image: url(../imagenes/iconos/cabecera.jpg)">';	
	echo '<div class="container">';

		$titulo = str_replace("_"," ",$tabla_a_procesar);

		echo '<h1>BAJAS DE '.$titulo.'</h1>';
		
		echo '<br/>';
	echo '</div>';
echo '</div>';
echo '<body>';
	echo '<div class="container">';
		echo '<section class="interna">';
			echo '<div class="row">';
				echo '<div class="col-sm-12">';
					
					$listado_tipo = "bajas";
					include "../funciones/formular-1-listado.php";

					include "../funciones/formular-2-ficha.php";
				
				echo '</div>';
			echo '</div>';
		echo '</section>';
	echo '</div>';
	
	include "../funciones/mostrar-pie.php";

echo '</body>';
echo '</html>';

?>
