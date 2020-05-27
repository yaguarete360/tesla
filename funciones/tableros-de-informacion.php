<?php if (!isset($_SESSION)) {session_start();}

$url = "../../";
include "../../funciones/mostrar-cabecera.php";

echo '<meta http-equiv="refresh" content="40">';

$_SESSION['fechaDesde'] = "2014-01-01";
$_SESSION['fechaHasta'] = date("Y-m-d");

if(isset($_POST['clave'])) $_SESSION['clave'] = $_POST['clave'];

if(!isset($_SESSION['clave']) or (isset($_SESSION['clave']) and $_SESSION['clave'] != "somos"))
{
	echo '<div id="slider-interna" class="carousel slide" data-ride="carousel">';
		echo '<center>';
			echo '<form action="" method="post">';
				echo '<h1>Clave <input type="password" name="clave"/></h1>';
				echo '<input type="submit" name="ingresar" value="Ingresar"/>';
			echo '</form>';
		echo '</center>';
	echo '</div>';

	include "../../funciones/mostrar-pie.php";

}

if(isset($_SESSION['clave']) and $_SESSION['clave'] == "somos")
{
	$titulo_pagina = "SERVICIOS";
	echo '<div class="tableros-datos">';

	include "../../funciones/obtener-tableros-de-informacion.php";
	
	if(!isset($_SESSION['panel'])) $_SESSION['panel'] = 0;
	$n = 0;
	
	if(!empty($casos))
	{
		foreach($casos as $ind => $caso)
		{
			$paneles[$n] = $ind;
			$n++;
		}
		
		if($_SESSION['panel'] == $n) $_SESSION['panel'] = 0;
		if($_SESSION['panel'] >  $n) $_SESSION['panel'] = 0;

		$ind = $paneles[$_SESSION['panel']];
		$total_paneles = $n;
		
		switch ($casos[$ind]['tipo'])
		{
			case 'inhumacion':
				$tipo_panel = "entierro";
				$campos_casos = 'cementerio_destino,inicio_fecha,inicio_hora';
			break;

			case 'sepelio':
				$tipo_panel = "sepelio";
				$campos_casos = 'capilla,cementerio_destino,inicio_fecha,fin_fecha,inicio_hora,crematorio,fin_hora';
			break;

			case 'cremacion':
				$tipo_panel = "cremacion";
				$campos_casos = 'crematorio,fin_fecha,fin_hora';
			break;

			case 'traslado':
				$tipo_panel = "traslado";
				$campos_casos = 'cementerio_origen,inicio_fecha,inicio_hora,cementerio_destino,fin_fecha,fin_hora';
			break;

			default:
				$campos_casos = 'capilla,inicio_fecha,inicio_hora,cementerio_destino,crematorio,fin_fecha,fin_hora';
			break;
		}	

		$campos = explode(",", $campos_casos);

		echo '<div class="tableros-bordes">';
			echo '</br>';
			echo '</br>';
			echo '<table class="tableros-tablas">'; 
				echo '<tr>'; 
					echo '<td class="tableros-rotulos" colspan="2">';
						echo ucwords($tipo_panel);
						echo " del ";
						echo ucwords($casos[$ind]['fecha']);
					echo '</td>';
				echo '</tr>'; 			
				echo '<tr>'; 
					echo '<td class="tableros-difuntos" colspan="2">';
						echo ucwords($casos[$ind]['difunto']);
					echo '</td>'; 			
				echo '</tr>'; 				
			  	
			  	foreach($campos as $campo_vuelta => $campo_nombre)
			  	{
		  			
			  		if($casos[$ind][$campo_nombre] != "N/A" and !empty($casos[$ind][$campo_nombre]) and $casos[$ind][$campo_nombre] != "S/D")
			  		{
			  			echo '<tr>'; 			  			
				  			if($casos[$ind]['tipo'] == "inhumacion" or $casos[$ind]['tipo'] == "cremacion")
				  			{
			  					echo '<td class="tableros-rotulos">';
			  						echo ucwords(str_replace("_"," ",$campo_nombre)).': ';
					  			echo '</td>';
					  			echo '<td class="tableros-datos-internos">';
					  				echo ucwords($casos[$ind][$campo_nombre]);
					  			echo '</td>';
				  			}
				  			else
				  			{
				  				echo '<td class="tableros-rotulos">';
									echo ucwords(str_replace("_"," ",$campo_nombre)).': ';
								echo '</td>';
					  			echo '<td class="tableros-datos-internos">';
					  				echo ucwords($casos[$ind][$campo_nombre]);
					  			echo '</td>';
			  				}
					  	echo '</tr>'; 
				  	}
			  	}
			echo '</table>';
			$_SESSION['panel']++;
			echo '</br>';
			echo '</br>';
		echo '</div>';
	}

	if(!isset($_SESSION['slides_fotos'])) $_SESSION['slides_fotos'] = 1;

	echo '<div class="tableros-fotos">';
		$slide_numero = 'institucionales-'.str_pad($_SESSION['slides_fotos'], 3,"0",STR_PAD_LEFT);
		echo '<img src="../../imagenes/acceso-principal/'.$slide_numero.'.jpg" alt="'.$slide_numero.'"/>';
	echo '</div>';

	$_SESSION['slides_fotos']++;
	
	if($_SESSION['slides_fotos'] > 5) $_SESSION['slides_fotos'] = 1;

}
echo '</body>';
echo '</html>';

?>
