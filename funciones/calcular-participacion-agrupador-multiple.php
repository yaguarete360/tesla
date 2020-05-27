<?php if (!isset($_SESSION)) {session_start();}

$url = "../../../";

$esta_vista = basename(__FILE__);
$titulo_pagina = str_replace("-"," ",$esta_vista);
$titulo_pagina = str_replace(".php","",$titulo_pagina);
$titulo_pagina = explode(" ",$titulo_pagina);
$titulo_pagina = $titulo_pagina[0]." ".$titulo_pagina[1]." por ".$agrupador;

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
				$rangodias = 1;
				
				include $url.'pse-red/funciones/generar-persistencia.php';
				
				echo '<form class="rangos" method="post" action="">';
				
					include $url.'pse-red/funciones/seleccionar-fechas-rango.php';
				
					echo '<input type="submit" name="listar" value="Listar"/>';
				echo '</form>';
				
				
				$sumadores = explode(",", $sumadores);
				$z = 0;
				foreach ($sumadores as $sumador)
				{
					$sumadorL = trim($sumador);
					$sumadores[$z] = $sumadorL;
					$z++;
				}
				
				$titulo = basename(__FILE__,'.php').': '.$subtitulo.' al '.$fecha_actual;
				
				include $url.'pse-red/funciones/conectar-base-de-datos.php';
				
				if(isset($_POST['listar']))
				{
					$contador_total = 'SELECT *,
					SUM('.$sumadores[0].') + SUM('.$sumadores[1].') + SUM('.$sumadores[2].') AS sumador_total,
					COUNT('.$contador.') AS contador_total
					FROM '.$tabla_a_procesar.'
					WHERE borrado = "0000-00-00"
					AND fecha
					BETWEEN "'.$_SESSION['fecha_desde'].'" 
					AND "'.$_SESSION['fecha_hasta'].'"';

					$queryTotales = $conexion->prepare($contador_total);
					$queryTotales->execute();	
					
					while($rows = $queryTotales->fetch(PDO::FETCH_ASSOC))
					{
						$contador_total = $rows['contador_total'];
						$sumador_total = $rows['sumador_total'];
					}
					
					$consulta = 'SELECT *,
					SUM('.$sumadores[0].') + SUM('.$sumadores[1].') + SUM('.$sumadores[2].') AS sumador_parcial,
					COUNT('.$contador.') AS contador_parcial
					FROM '.$tabla_a_procesar.'
					WHERE borrado = "0000-00-00"
					AND fecha
					BETWEEN "'.$_SESSION['fecha_desde'].'" 
					AND "'.$_SESSION['fecha_hasta'].'" 
					GROUP BY '.$agrupador.'
					ORDER BY sumador_parcial
					DESC';

					$query = $conexion->prepare($consulta);
					$query->execute();
					
					echo '<table class="autoancho">';					
						echo '<th><div class="encabezados">';
							echo ucfirst($agrupador);
						echo '</div></th>';
						echo '<th><div class="encabezados">';
							if($contador != $agrupador) echo $contador;
						echo '</div></th>';
						if(isset($calculo))
						{							
							echo '<th><div class="encabezados">';
								 echo $calculo;
							echo '</div></th>';
						}					
						echo '<th><div class="encabezados">';
							echo $sumador;
						echo '</div></th>';
						echo '<th><div class="encabezados">';
							echo ' % ';
						echo '</div></th>';
						$ind = 0;
						$porcentage_total = 0;
						while($rows = $query->fetch(PDO::FETCH_ASSOC))
						{
							echo '<tr>';
								echo '<td>';
									echo $rows[$agrupador];
									if(isset($calculo)) echo $calculo;
								echo '</td>';
								echo '<td class="derecha">';
									if($contador != $agrupador) echo number_format($rows['contador_parcial'],0,',','.');
								echo '</td>';
								if(isset($calculo))
								{							
									echo '<td class="derecha">';
										 echo $dato_calculo[$ind];
									echo '</td>';
								}
								echo '<td class="derecha">';
									echo number_format($rows['sumador_parcial'],0,',','.');	
								echo '</td>';
								echo '<td class="derecha">';
									$porcentage = $sumador_total > 0 ? $rows['sumador_parcial'] / $sumador_total * 100 : 0;
									echo number_format($porcentage,1,',','.');	
									$porcentage_total = $porcentage_total + $porcentage; 
								echo '</td>';
									$legen[$ind] = $rows[$agrupador];
									$valor[$ind] = $rows['sumador_parcial'];
								echo '</td>';
							echo '</tr>';
							$ind ++;
						}
						echo '<tr>';
							echo '<td>';
								echo '<b>TOTAL</b>';	
							echo '</td>';
							echo '<td class="derecha">';
								if($contador != $agrupador) echo '<b>'.number_format($contador_total,0,',','.').'</b>';	
							echo '</td>';
							echo '<td class="derecha">';
								echo '<b>'.number_format($sumador_total,0,',','.').'</b>';	
							echo '</td>';
							echo '<td class="derecha" colspan="2">';
								echo '<b>'.number_format($porcentage_total,0,',','.').'</b>';	
							echo '</td>';
						echo '</tr>';			
						echo '<tr>';			
							echo '<td>';
								$_SESSION['tortaTitulo'] = 'Participacion por '.strtoupper($agrupador).' desde el '.
								$_SESSION['fecha_desde'].' hasta el '.$_SESSION['fecha_hasta'].'.';
								$_SESSION['valor_torta_0'] = isset($valor[0])? $valor[0] : 0;
								$_SESSION['valor_torta_1'] = isset($valor[1])? $valor[1] : 0;
								$_SESSION['valor_torta_2'] = isset($valor[2])? $valor[2] : 0;
								$_SESSION['valor_torta_3'] = isset($valor[3])? $valor[3] : 0;
								$_SESSION['valor_torta_4'] = isset($valor[4])? $valor[4] : 0;
								$_SESSION['valor_torta_5'] = isset($valor[5])? $valor[4] : 0;
								$_SESSION['valor_torta_6'] = isset($valor[6])? $valor[6] : 0;
								$_SESSION['valor_torta_7'] = $sumador_total - ($_SESSION['valor_torta_0'] + 
																			 $_SESSION['valor_torta_1'] + 
																			 $_SESSION['valor_torta_2'] + 
																			 $_SESSION['valor_torta_3'] + 
																			 $_SESSION['valor_torta_4'] + 
																			 $_SESSION['valor_torta_5'] + 
																			 $_SESSION['valor_torta_6']
																			);
								$_SESSION['legen_torta_0'] = isset($legen[0])? $legen[0].' = '.number_format($_SESSION['valor_torta_0'],0,',','.') : "";
								$_SESSION['legen_torta_1'] = isset($legen[1])? $legen[1].' = '.number_format($_SESSION['valor_torta_1'],0,',','.') : "";
								$_SESSION['legen_torta_2'] = isset($legen[2])? $legen[2].' = '.number_format($_SESSION['valor_torta_2'],0,',','.') : "";
								$_SESSION['legen_torta_3'] = isset($legen[3])? $legen[3].' = '.number_format($_SESSION['valor_torta_3'],0,',','.') : "";
								$_SESSION['legen_torta_4'] = isset($legen[4])? $legen[4].' = '.number_format($_SESSION['valor_torta_4'],0,',','.') : "";
								$_SESSION['legen_torta_5'] = isset($legen[5])? $legen[5].' = '.number_format($_SESSION['valor_torta_5'],0,',','.') : "";
								$_SESSION['legen_torta_6'] = isset($legen[6])? $legen[6].' = '.number_format($_SESSION['valor_torta_6'],0,',','.') : "";
								$_SESSION['legen_torta_7'] = 'Otros = '.number_format($_SESSION['valor_torta_7'],0,',','.');

								echo 'Graficar participacion por &nbsp'.strtoupper($agrupador).':&nbsp&nbsp';
							echo '</td>';
							echo '<td colspan="2">';
								echo '<form method="post" action="'.$url.'pse-red/funciones/graficar-torta-3d.php">';
									echo '<input type="submit" name="graficar_2" value="Torta"/>'.'&nbsp&nbsp';
								echo '</form>';
							echo '</td>';							
							echo '<td colspan="2">';
								echo '<form method="post" action="'.$url.'pse-red/funciones/graficar-columnas.php">';
									echo '<input type="submit" name="graficar_3" value="Columnas"/>';
								echo '</form>';
							echo '</td>';						
						echo '</tr>';			
					echo '</table>';			
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';
echo '</div>';

include $url.'pse-red/funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>
