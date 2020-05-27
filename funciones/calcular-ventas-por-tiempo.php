<?php if (!isset($_SESSION)) {session_start();}
$url = "../../../";

$esta_vista = basename(__FILE__);
$titulo_pagina = str_replace("-"," ",$esta_vista);
$titulo_pagina = str_replace(".php","",$titulo_pagina);

include $url.'pse-red/funciones/mostrar-cabecera.php';

echo '<div class="top-header"';
	echo 'style="background-image: url('.$url.'pse-red/imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo_pagina.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-sm-12">';
				
				for($ind = 0; $ind < 7; $ind++) if(isset($_SESSION['legen_torta_'.$ind])) $_SESSION['legen_torta_'.$ind] = 0;
				for($ind = 0; $ind < 7; $ind++) if(isset($_SESSION['valor_torta_'.$ind])) $_SESSION['valor_torta_'.$ind] = 0;	
				
				$rango_dias = 1;
				include $url.'pse-red/funciones/generar-persistencia.php';

				echo '<form class="rangos" method="post" action="">';
					echo '<input type="submit" name="graficar" value="Graficar"/>';
				echo '</form>';
				$titulo = basename(__FILE__,'.php').': '.$subtitulo.' al '.$fechaActual;

				include $url.'pse-red/funciones/conectar-base-de-datos.php';

				if(isset($_POST['graficar']))
				{
					$ano_actual = date('Y');
					$ano_anterior_1 = $ano_actual -1;
					$ano_anterior_2 = $ano_actual -2;
					$ano_anterior_3 = $ano_actual -3;
					$ano_anterior_4 = $ano_actual -4;
					$_SESSION['legen_torta_0'] = $ano_actual;
					$_SESSION['legen_torta_1'] = $ano_anterior_1;
					$_SESSION['legen_torta_2'] = $ano_anterior_2;
					$_SESSION['legen_torta_3'] = $ano_anterior_3;
					$_SESSION['legen_torta_4'] = $ano_anterior_4;
					echo '<table class="autoancho">';
					    echo '<tr>';
					        echo '<td colspan="5">';
					            echo "El siguiente grafico va a obtener los datos para los años ".$_SESSION['legen_torta_4'].", ".$_SESSION['legen_torta_3'].", ".$_SESSION['legen_torta_2'].", ".$_SESSION['legen_torta_1']." y ".$_SESSION['legen_torta_0'].".";
					        echo '</td>';
					    echo '</tr>';
					echo '</table>';
					$anos = array($ano_actual, $ano_anterior_1, $ano_anterior_2, $ano_anterior_3, $ano_anterior_4);
					$meses = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
					$ind = 0;
					
					foreach ($anos as $ano)
					{
						foreach ($meses as $mes)
						{
							$ano_mes = array();
							$ano_mes = '"'.$ano.'-'.$mes.'"';
							$ano_mes = str_replace('"', '', $ano_mes);
							
							$consulta_mes = 'SELECT
							SUM('.$sumador.') AS suma_mes
							FROM '.$tablaAProcesar.'
							WHERE borrado = "0000-00-00" 
							AND fecha LIKE "'.$ano_mes.'%"';

							$query_suma_mes = $conexion->prepare($consulta_mes);
							$query_suma_mes->execute();
							
							while($rows_suma_mes = $query_suma_mes->fetch(PDO::FETCH_ASSOC))
							{
								$_SESSION['resultado_mes'][$ind] = $rows_suma_mes['suma_mes'];
								
								if(empty($_SESSION['resultado_mes'][$ind]) or $_SESSION['resultado_mes'][$ind] === "0")
								{
									$_SESSION['resultado_mes'][$ind] = "0";
								}
								
								$ind++;
							
							}
						}						
					}
					echo '<td>';
						echo '<form method="post" action="'.$url.'pse-red/funciones/graficar-columnas-comparativas.php">';
							echo '<input type="submit" name="graficar_3" value="Columnas Comparativas"/>';
						echo '</form>';
					echo '</td>';						
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';

include $url.'pse-red/funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>
