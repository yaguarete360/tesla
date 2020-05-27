<?php if (!isset($_SESSION)) {session_start();}

$url = "../../../";

$esta_pagina = basename(__FILE__);
$titulo_pagina = str_replace("-"," ",$esta_pagina);
$titulo_pagina = str_replace(".php","",$titulo_pagina);
$titulo_pagina = explode(" ",$titulo_pagina);
$agrupador = explode("_", $tabla_asistente);
$titulo_pagina = $titulo_pagina[0]." ".$titulo_pagina[1]." por ".$agrupador[1];

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
				echo '<form class="rangos" method="post" action="">';			

					include $url.'pse-red/funciones/seleccionar-fechas-rango.php';

					echo '<table>';
						$valor = $_SESSION[$varnom];
						$tabla_seleccion = $tabla_asistente;
						$campo_seleccion = $campo_asistente;
						$campo = $campo_seleccion;
						$sentido = "ASC";
						$valor = "";
						echo '<tr> ';
							echo '<td>';
									
								$rotulo = $campo." Desde:";
								$varnom = "filtro_desde";									
								include $url.'pse-red/funciones/seleccionar-archivos.php';
							
							echo '</td>';
							echo '<td>';

								$rotulo = $campo." Hasta:";
								$varnom = "filtro_hasta";							
								include $url.'pse-red/funciones/seleccionar-archivos.php';
							
							echo '</td>';
						echo '</tr>';
						echo '<tr> ';
							echo '<td>';
								echo '<input type="submit" name="listar" value="Listar">';
							echo '</td>';
						echo '</tr>';
					echo '</table>';
				echo '</form>';
			
			$fechaActual = date('Y-m-d');
			$titulo = basename(__FILE__,'.php').' al '.$fechaActual;
			$subtitulo = "";

			if(isset($_POST['fecha_desde']))
			{
				$cantidad_registros = 0;
				$unidades = 0;
				$produccion = 0;
				echo '<table>';
					echo '<th><div id="encabezados">Feretro</div></th>';
					echo '<th><div id="encabezados">Cantidad</div></th>';
					echo '<tr>';
						echo '<td colspan="5">';
							$subtitulo_0 = 'Produccion de '.$unidad_produccion.'s con '.$campo_fecha_principal.' entre el '.$_POST['fecha_desde'].' y el '.$_POST['fecha_hasta'];
							echo '<b>'.$subtitulo_0;
						echo '</td>';
					echo '</tr>';
					
					include $url.'pse-red/funciones/conectar-base-de-datos.php';
					
					$consulta = 'SELECT *, 
					COUNT('.$campo_principal.') AS unidades,
					SUM('.$campo_unidad_produccion.') AS produccion
					FROM '.$tabla_principal.'
					WHERE '.$campo_fecha_principal.'
					BETWEEN "'.$_POST['fecha_desde'].'" 
					AND "'.$_POST['fecha_hasta'].'" 
					GROUP BY '.$campo_principal.' ASC';

					$query = $conexion->prepare($consulta);
					$query->execute();
					
					while($rows = $query->fetch(PDO::FETCH_ASSOC))
					{
						$cantidad_registros = $cantidad_registros + 1;
						$unidades = $unidades + $rows['unidades'];
						$produccion = $produccion + $rows['produccion'];

						echo '<tr>';
							echo '<td>';
								echo $rows['feretro'];

							echo '</td>';
							echo '<td class="derecha">';
								echo $rows['unidades'];
								
							echo '</td>';
						echo '</tr>';
					}
					echo '<tr>';
						echo '<td><b>RESUMEN:</b></td>';
					echo '</tr>';
					echo '<tr>';
						$rotulo_0_1 = 'Unidades totales de '.$unidad_produccion.'s : ';
						echo '<td><b>'.$rotulo_0_1.'</b></td>';
						echo '<td class="derecha"><b>'.number_format($unidades,0,',','.').'</b></td>';
					echo '</tr>';	
					echo '<tr>';
						$rotulo_0_2 = ucfirst($campo_unidad_produccion).' total en '.$medida_produccion.': ';
						echo '<td><b>'.$rotulo_0_2.'</b></td>';
						echo '<td class="derecha"><b>'.number_format($produccion,2,',','.').'</b></td>';
					echo '</tr>';	
					echo '<tr>';
						$rotulo_0_3 = 'Promedio de '.$campo_unidad_produccion.' de la unidad de  '.$unidad_produccion.': ';
						echo '<td><b>'.$rotulo_0_3.'</b></td>';
						echo '<td class="derecha"><b>'.number_format($produccion / $unidades,2,',','.').'</b></td>';
					echo '</tr>';	
				echo '</table">';
				echo '<table>';
					echo '<tr>';
						$subtitulo_1 = ucfirst($campo_asistente);
						echo '<td><b>'.$subtitulo_1.'</b></td>';
						echo '<td><b>Entra</td>';
						echo '<td><b>Sale</td>';
						echo '<td><b>Debe</td>';
						echo '<td><b>Haber</td>';
						echo '<td><b>Ent.x '.$unidad_produccion.'</td>';
						echo '<td><b>Sal.x '.$unidad_produccion.'</td>';
						echo '<td><b>Debe.x '.$unidad_produccion.'</td>';
						echo '<td><b>Haber.x '.$unidad_produccion.'</td>';
					echo '</tr>';					
					
					$consulta = 'SELECT '.$campo_a_procesar.', 	
					SUM(entra) AS total_entra, 
					SUM(sale) AS total_sale,
					SUM(debe) AS total_debe,
					SUM(haber) AS total_haber 
					FROM '.$tabla_a_procesar.' 
					WHERE '.$campo_fecha_a_procesar.' 
					BETWEEN "'.$_POST['fecha_desde'].'" 
					AND "'.$_POST['fecha_hasta'].'"
					AND '.$campo_a_procesar.' 
					BETWEEN "'.$_POST['filtro_desde'].'" 
					AND "'.$_POST['filtro_hasta'].'" 
					GROUP BY '.$campo_a_procesar.' 
					ASC';

					$query = $conexion->prepare($consulta);
					$query->execute();
					
					$cantidad_registros = 0;
					$total_entra = 0;
					$total_sale = 0;
					$total_debe = 0;
					$total_haber = 0;

					while($fila = $query->fetch(PDO::FETCH_ASSOC))
					{
						$cantidad_registros = $cantidad_registros + 1;
						echo '<tr>';
							echo '<td>'.$fila[$campo_asistente].'</td>';
							echo '<td class="derecha">'.number_format($fila['total_entra'],3,',','.').'</td>';
							echo '<td class="derecha">'.number_format($fila['total_sale'],3,',','.').'</td>';
							echo '<td class="derecha">'.number_format($fila['total_debe'],2,',','.').'</td>';
							echo '<td class="derecha">'.number_format($fila['total_haber'],2,',','.').'</td>';	
							$total_entra = $total_entra + $fila['total_entra'];
							$total_sale = $total_sale + $fila['total_sale'];
							$total_debe = $total_debe + $fila['total_debe'];
							$total_haber = $total_haber + $fila['total_haber'];
							if($unidades > 0)
							{
								echo '<td class="derecha">'.number_format(($fila['total_entra'] / $unidades) ,3,',','.').'</td>';
								echo '<td class="derecha">'.number_format(($fila['total_sale'] / $unidades),3,',','.').'</td>';
								echo '<td class="derecha">'.number_format(($fila['total_debe'] / $unidades),2,',','.').'</td>';
								echo '<td class="derecha">'.number_format(($fila['total_haber'] / $unidades),2,',','.').'</td>';
							}
							else
							{
								echo '<td class="derecha">'.number_format(0,3,',','.').'</td>';
								echo '<td class="derecha">'.number_format(0,3,',','.').'</td>';
								echo '<td class="derecha">'.number_format(0,2,',','.').'</td>';
								echo '<td class="derecha">'.number_format(0,2,',','.').'</td>';							
							}
						echo '</tr>';
					}
					echo '<tr>';
					echo '</tr>';
					echo '<tr>';
						$rotulo_1_1 = 'Total entra: ';
						echo '<td colspan="8"><b>'.$rotulo_1_1.'</b></td>';
						echo '<td class="derecha"><b>'.number_format($total_entra,2,',','.').'</b></td>';
					echo '</tr>';	
					echo '<tr>';
						$rotulo_1_2 = 'Total sale: ';
						echo '<td colspan="8"><b>'.$rotulo_1_2.'</b></td>';
						echo '<td class="derecha"><b>'.number_format($total_sale,2,',','.').'</b></td>';
					echo '</tr>';	
					echo '<tr>';
						$rotulo_1_3 = 'Diferencia entre Entra y Sale: ';
						echo '<td colspan="8"><b>'.$rotulo_1_3.'</b></td>';
						echo '<td class="derecha"><b>'.number_format($total_entra - $total_sale,2,',','.').'</b></td>';
					echo '</tr>';	
					echo '<tr>';
						$rotulo_1_4 = 'Total debe: ';
						echo '<td colspan="8"><b>'.$rotulo_1_4.'</b></td>';
						echo '<td class="derecha"><b>'.number_format($total_debe,2,',','.').'</b></td>';
					echo '</tr>';	
					echo '<tr>';
						$rotulo_1_5 = 'Total haber: ';
						echo '<td colspan="8"><b>'.$rotulo_1_5.'</b></td>';
						echo '<td class="derecha"><b>'.number_format($total_haber,2,',','.').'</b></td>';
					echo '</tr>';	
					echo '<tr>';
						$rotulo_1_6 = 'Diferencia entre Debe y Haber: ';
						echo '<td colspan="8"><b>'.$rotulo_1_6.'</b></td>';
						echo '<td class="derecha"><b>'.number_format($total_debe - $total_haber,2,',','.').'</b></td>';
					echo '</tr>';	
					echo '<tr>';
						$rotulo_1_7 = 'Haber por '.$campo_unidad_produccion.' y/o por '.$medida_produccion.': ';
						echo '<td colspan="8"><b>'.$rotulo_1_7.'</b></td>';
						
						if($produccion > 0)
						{
							echo '<td class="derecha"><b>'.number_format($total_haber / $produccion,2,',','.').'</b></td>';
						}
						else
						{
							echo '<td class="derecha"><b>'.number_format(0,2,',','.').'</b></td>';
						} 
					
					echo '</tr>';					
					echo '<tr>';
						$rotulo_1_8 = 'Haber por unidad y/o por '.$unidad_produccion.': ';
						echo '<td colspan="8"><b>'.$rotulo_1_8.'</b></td>';
					
						if($unidades > 0)
						{
							echo '<td class="derecha"><b>'.number_format($total_haber / $unidades,2,',','.').'</b></td>';
						}
						else
						{
							echo '<td class="derecha"><b>'.number_format(0,2,',','.').'</b></td>';
						} 
					
					echo '</tr>';	
					echo '<tr>';
						echo '<td class="fin-de-listado" colspan="3">Fin del listado de '.$cantidad_registros.' registros.</td>';
					echo '</tr>';
				echo '</table>';
			}
			if(isset($_POST['listar']))
			{
				echo '<form action="'.$url.'pse-red/funciones/imprimir-pdf-consumo-output.php" method="post">';
					
					include $url."pse-red/funciones/imprimir-pdf-consumo-input.php";
					
					echo '<br/>';
					echo '<input type="submit" name="listar" value="Imprimir en PDF">';
					echo '<br/>';
					echo '<br/>';
				echo '</form>';
			}
			
				echo '</div>';
			echo '</div>';
		echo '</section>';
	echo '</div>';
	
	include $url."pse-red/funciones/mostrar-pie.php";
	
	echo '</body>';
echo '</html>';

?>
