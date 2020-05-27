<?php if (!isset($_SESSION)) {session_start();}				

$esta_vista = basename(__FILE__);

$tabla_a_procesar = $_GET['tabla_a_procesar'];

$url = "../";
include "../funciones/mostrar-cabecera.php";

include '../vistas/datos/'.$tabla_a_procesar.'.php';

include "../funciones/conectar-base-de-datos.php";

echo '<div class="top-header"';
	echo 'style="background-image: url('.$url.'imagenes/iconos/cabecera.jpg)">';	
	echo '<div class="container">';

		$titulo = str_replace("_"," ",$tabla_a_procesar);

		echo '<h1>LISTADO DE '.$titulo.'</h1>';
		
		echo '<br/>';
	echo '</div>';
echo '</div>';
echo '<body>';
	echo '<div class="container">';
		echo '<section class="interna">';

			if(!isset($_POST['tabla_a_procesar']))
			{
				echo '<b>Favor seleccione el campo por el cual desea ordenar la tabla '.$tabla_a_procesar.'.</b>';
				echo '<br/>';
				echo '<form method="post">';
					echo 'Campo: ';
						echo '<select name="campo_a_procesar">';
							foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
							{
								echo '<option value="'.$campo_nombre.'">'.$campo_nombre;						
							}
						echo '</select>';
					echo '<input type="hidden" name="tabla_a_procesar" value="'.$tabla_a_procesar.'">';
					echo '<input type="submit" name="elegir_campo" value="Elegir campo">';				
				echo '</form>';
			}

			if(isset($_POST['elegir_campo']))
			{
				echo '<b>Favor escriba algo aproximado a lo que busca para poder seleccionar el rango de datos o deje en blanco y le retornara hasta los ultimos 2000 registros.</b>';
				echo '<br/>';				
				echo '<form method="post">';
					echo 'Siglas aproximadas: ';
					echo '<input type="text" name="aproximacion">';
					echo '<input type="hidden" name="tabla_a_procesar" value="'.$_POST['tabla_a_procesar'].'"/>';
					echo '<input type="hidden" name="campo_a_procesar" value="'.$_POST['campo_a_procesar'].'"/>';
					echo '<input type="submit" name="elegir_aproximacion" value="Elegir aproximacion"/>';				
				echo '</form>';
			}

			if(isset($_POST['elegir_aproximacion']))
			{
				echo '<b>Favor seleccione el rango desde/hasta que desea listar.</b>';
				echo '<br/>';												
				echo '<form method="post">';
					echo 'Desde: ';
					$consulta_desde = 'SELECT * 				
					FROM '.$_POST['tabla_a_procesar'].' 
					WHERE borrado = "no"
					AND '.$_POST['campo_a_procesar'].'
					LIKE "%'.$_POST['aproximacion'].'%"
					GROUP BY '.$_POST['campo_a_procesar'].'
					ORDER BY '.$_POST['campo_a_procesar'].'
					ASC
					LIMIT 2000';

					$query = $conexion->prepare($consulta_desde);
					$query->execute();

					echo '<select name="filtro_desde">';							
						while($rows = $query->fetch(PDO::FETCH_ASSOC))
						{
							echo '<option value="'.$rows[$_POST['campo_a_procesar']].'">'.$rows[$_POST['campo_a_procesar']];
						}						
					echo '</select>';				

					echo 'Hasta: ';

					$consulta_hasta = 'SELECT * 				
					FROM '.$_POST['tabla_a_procesar'].' 
					WHERE borrado = "no"
					AND '.$_POST['campo_a_procesar'].'
					LIKE "%'.$_POST['aproximacion'].'%"
					GROUP BY '.$_POST['campo_a_procesar'].'
					ORDER BY '.$_POST['campo_a_procesar'].'
					DESC
					LIMIT 2000';

					$query = $conexion->prepare($consulta_hasta);
					$query->execute();

					echo '<select name="filtro_hasta">';
						while($rows = $query->fetch(PDO::FETCH_ASSOC))
						{
							echo '<option value="'.$rows[$_POST['campo_a_procesar']].'">'.$rows[$_POST['campo_a_procesar']];
						}						
					echo '</select>';
					echo '&nbsp&nbsp&nbsp';
					echo 'Excluir bajas: ';
					echo '<input type="checkbox" name="excluir_bajas" checked="checked">';
					echo '&nbsp&nbsp&nbsp';
					echo '<input type="hidden" name="tabla_a_procesar" value="'.$_POST['tabla_a_procesar'].'">';
					echo '<input type="hidden" name="campo_a_procesar" value="'.$_POST['campo_a_procesar'].'">';
					echo '<input type="submit" name="elegir_rango" value="Elegir rango">';				
				echo '</form>';
			}

			if(isset($_POST['elegir_rango']))
			{
					echo '<b>Responsable: '.$responsable.' - Contacto: '.$interno.'</b>';

					if($_POST['excluir_bajas'] == true)
					{
					
						$consulta_listado = 'SELECT * 				
						FROM '.$_POST['tabla_a_procesar'].' 
						WHERE borrado LIKE "no"
						AND '.$_POST['campo_a_procesar'].'
						BETWEEN "'.$_POST['filtro_desde'].'"
						AND "'.$_POST['filtro_hasta'].'"
						ORDER BY '.$_POST['campo_a_procesar'].'
						ASC
						LIMIT 10000';
					
					}
					else
					{
						$consulta_listado = 'SELECT * 				
						FROM '.$_POST['tabla_a_procesar'].' 
						AND '.$_POST['campo_a_procesar'].'
						BETWEEN "'.$_POST['filtro_desde'].'"
						AND "'.$_POST['filtro_hasta'].'"
						ORDER BY '.$_POST['campo_a_procesar'].'
						ASC
						LIMIT 10000';

					}

					$query = $conexion->prepare($consulta_listado);
					$query->execute();

					echo '<table>';
						$fila = 0;

						foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
						{
							if($campo_atributo['visible'] === "si")
							{									
								$campos_abreviados = explode("_",$campo_nombre);

								if(isset($campos_abreviados[1]))
								{
									$elementos_a_imprimir[$fila][$campo_nombre] = "";
									foreach($campos_abreviados as $campo_abreviado) $elementos_a_imprimir[$fila][$campo_nombre].= ucwords(substr($campo_abreviado,0,3)).".";
								}
								else
								{
									$elementos_a_imprimir[$fila][$campo_nombre] = $campo_nombre;
								}

								if($campo_atributo['formato'] != "oculto") echo '<td class="encabezados">'.$elementos_a_imprimir[$fila][$campo_nombre].'</td>';
							}
						}
						$fila++;

						echo '<td class="encabezados">';
							echo "";
						echo '</td>';
						
						while($rows = $query->fetch(PDO::FETCH_ASSOC))
						{
							echo '<tr>';
								foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
								{
									if($campo_atributo['visible'] == "si")
									{
										$dato_a_usar = "";
										
										if($campo_atributo['formato'] == "texto" or 
										$campo_atributo['formato'] == "fecha" or 
										$campo_atributo['formato'] == "asistido" or
										$campo_atributo['formato'] == "vista-izquierda")
										{											
											echo '<td class="izquierda">';
												$dato_a_usar = (strlen($rows[$campo_nombre]) > 20) ? substr($rows[$campo_nombre], 0, 20)."..." : $rows[$campo_nombre];
												echo ucwords($dato_a_usar);
											echo '</td>';
										} 

										if($campo_atributo['formato'] == "numero-texto" or
										$campo_atributo['formato'] == "asistido-derecha" or  
										$campo_atributo['formato'] == "vista" or
										$campo_atributo['formato'] == "vista-derecha")
										{
											echo '<td class="derecha">';
												$dato_a_usar = (strlen($rows[$campo_nombre]) > 20) ? substr($rows[$campo_nombre], 0, 20)."..." : $rows[$campo_nombre];
												echo ucwords($dato_a_usar);
											echo '</td>';
										}									

										if($campo_atributo['formato'] == "numero")
										{											
											echo '<td class="derecha">';
												echo number_format($rows[$campo_nombre], $campo_atributo['decimales'],",",".");
												$dato_a_usar = $rows[$campo_nombre];
											echo '</td>';
										}

										$elementos_a_imprimir[$fila][$campo_nombre] = $dato_a_usar;
									}
									
								}
							echo '</tr>';
							$fila++;
						}						
					echo '</table>';
					echo '<br/>';
					echo 'Obs: Este reporte imprime solo hasta 10.000 registros, si quiere ver mas buesque seleccione el rango.';
					echo '<br/>';
					echo '<br/>';

					$_SESSION['elementos_a_imprimir'] = $elementos_a_imprimir;
					echo '<form action="../funciones/imprimir-pdf-listados.php" method="post">';
						foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
						{
							if($campo_atributo['visible'] == "si" and isset($campo_atributo['suma']))
							{
								switch ($campo_atributo['suma'])
								{
									case 'simple':
	    								echo '<input type="hidden" name="columnas_a_sumar[]" value="'.$campo_nombre.'">';
									break;

								// 	case 'con_acumulado':
	    			// 					echo '<input type="hidden" name="mostrar_acumulados[]" value="'.$campo_nombre.'">';
								// 	break;
									
									default:
									break;
								}
							}
						}
	    				echo '<input type="submit" name="imprimir" value="Imprimir">';
	    			echo '</form>';
			}
		echo '</section>';
	echo '</div>';

	include $url.'funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>
