<?php if (!isset($_SESSION)) {session_start();}

$url = "../";

if(isset($_SESSION['alias_en_sesion']) and !empty($_SESSION['alias_en_sesion']))
{
	include '../funciones/listar-vinculos.php';
}
else
{
	
	include '../funciones/mostrar-cabecera.php';

	echo '<div class="top-header"';
		echo 'style="background-image: url('.$url.'imagenes/iconos/cabecera.jpg)">';
		echo '<div class="container">';
			echo '<h1>Pagina No Encontrada</h1>';
		echo '</div>';
	echo '</div>';
	echo '<div class="container">';
		echo '<section class="interna">';
			echo '<div class="row">';
				echo '<div class="col-sm-12">';
					echo '<span style="color:#e60000;font-weight:900;">La seccion deseada no se encuentra disponible o no existe.</span>';
					echo '<br/>';
					echo '<span style="color:#e60000;font-weight:900;">Favor contactar con el administrador del sistema.</span>';
					echo '<br/>';
				echo '</div>';
			echo '</div>';
		echo '</section>';
	echo '</div>';

	include '../funciones/mostrar-pie.php';
	
	echo '</body>';
	echo '</html>';
	
	exit;
}

?>
