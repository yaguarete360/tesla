<?php if (!isset($_SESSION)) {session_start();}

$url = "../../";
include "../../funciones/mostrar-cabecera.php";

include "../../funciones/obtener-tableros-de-coordinacion.php";

$casos_totales = $casos - 1;

if(!isset($_SESSION['caso'])) $_SESSION['caso'] = 0;
if($_SESSION['caso'] >= $casos_totales) $_SESSION['caso'] = 0;

echo '<div class="container">';
	echo '<section class="home-section" style="overflow: hidden; height:100%;">';
		
	  	echo '<div class="tableros-coordinacion">';
			echo '<form action="" method="post">';
				echo '<br/>';
				echo '<input type="hidden" name="caso" value="'.$_SESSION['caso']++.'"/>';
				echo '<b>Mostrando caso '.$_SESSION['caso'].' de '.$casos_totales.'</b>';
				echo '<input type="submit" name="siguiente" value="Ver caso siguiente" style="float: right;"/>';		
			echo '</form>';

			echo '<br/>';

		  	foreach($rotulos as $rotulo_nombre => $rotulo_valor)
		  	{
			  		echo ucwords($rotulo_nombre).': ';	  		
			  		echo '<b>'.ucwords($valores[$rotulo_nombre][$_SESSION['caso']]).'</b>';
			  		echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
			}
		echo '</div>';
	echo '</section>';
echo '</div>';
echo '</body>';
echo '</html>';

?>
