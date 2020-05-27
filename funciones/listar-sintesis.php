<?php if (!isset($_SESSION)) {session_start();}		

$esta_vista = basename(__FILE__,".php");

$url = "../../";
include '../../funciones/mostrar-cabecera.php';

$capitulo = "sintesis";

echo '<div class="top-header"';
	echo 'style="background-image: url('.$url.'imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo_pagina.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-sm-12">';
				if(isset($orden) and $orden == "des")
				{
					$documentosInternos = scandir('../../archivos/'.$capitulo.'/'.$tipoDoc, SCANDIR_SORT_DESCENDING);
				} 
				else
				{
					$documentosInternos = scandir('../../archivos/'.$capitulo.'/'.$tipoDoc, SCANDIR_SORT_ASCENDING);
				}
				foreach($documentosInternos as $doc=>$documento)
				{
				    if($documento != '.' and 
				       $documento != '..' and 
				       $documento != '.DS_Store' and
				       $documento != '.htaccess' and
				       $documento != 'error_log'
				    )
				    {					
						echo '<span class="menu-item">';
						echo '<a href="../../archivos/'.$capitulo.'/'.$tipoDoc.'/'.$documento.'">';
							echo ucwords($documento);
						echo '</a>';
						echo '</span>';						
						echo '<br/>';
				    }
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';

include $url.'funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>
