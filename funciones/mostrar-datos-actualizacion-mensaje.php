<?php if (!isset($_SESSION)) {session_start();}

$esta_vista = basename(__FILE__,'.php');

$url = "../";
include "../funciones/mostrar-cabecera.php";
$mensaje = @$_GET['mensaje'];
$mensaje = str_replace("-", " ", $mensaje);
echo '<link rel="stylesheet" href="'.$url.'css/toastr.css">';
echo '<link rel="stylesheet" href="'.$url.'css/sweetalert.css">';

echo '<script src="'.$url.'librerias/js/sweetalert.js" type="text/javascript"></script>';
echo '<script src="'.$url.'librerias/js/toastr.min.js" type="text/javascript"></script>';

echo '<div class="top-header"';
	echo 'style="background-image: url(../imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-sm-12">';
			echo $mensaje;
			echo '<form action="../index.php">';
    			echo '<input type="submit" value="Volver" />';
			echo '</form>';
				
		echo '</div>';
	echo '</section>';
echo '</div>';

include "../funciones/mostrar-pie.php";

echo '</body>';
echo '</html>';

?>

<script type="text/javascript">

	//
</script>
