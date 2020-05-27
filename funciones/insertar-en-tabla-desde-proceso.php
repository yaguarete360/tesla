<?php if (!isset($_SESSION)) {session_start();}

	include '../../funciones/conectar-base-de-datos.php';
	
	include '../../vistas/datos/'.$tabla_destino.'.php';

	$estilo_del_td = 'padding:5px;';
	$llaves_de_la_session_campos = array_keys($_SESSION['campos']);
	if(isset($_POST['finalizar']))
	{

		echo '<table>';
			echo '<tr>';
				echo '<td colspan="20" style="'.$estilo_del_td.'">';
					echo "Se ha insertado lo siguiente:";
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				foreach ($_SESSION['campos'] as $campo_nombre => $valor)
				{
					if(in_array($campo_nombre, $campos_a_mostrar)) 
					{
						echo '<th style="'.$estilo_del_td.'">';
							echo ucwords(str_replace("_", " ", $campo_nombre));
						echo '</th>';
					}
				}
			echo '</tr>';
			
			$campos_del_sistema = array('id', 'usuario', 'creado', 'modificado', 'borrado');
			$consulta_insercion_1 = 'INSERT INTO '.$tabla_destino.' (';
			foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo) $consulta_insercion_1.= $campo_nombre.', ';
			$consulta_insercion_1 = rtrim($consulta_insercion_1, ", ").' ) VALUES (';

			foreach ($_SESSION['datos_a_insertar'] as $fila => $datos)
			{
			    if(isset($_POST['insertar'][$fila]))
			    {
			        $consulta_insercion_2 = "";
    				echo '<tr>';
    					foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
    					{
    						if(isset($datos[$campo_nombre]))
    						{
    							if(isset($redireccionar_campo[$campo_nombre]['asistente_en_insercion']))
    							{
    								switch ($redireccionar_campo[$campo_nombre]['asistente_en_insercion'])
    								{
    								    // ------------------------------------- ASISTENTES EN INSERCION ------------------------------------- \\
    									case 'autonumeracion':
    										$ultimo_numero = date('Y').'-0000000';
    										$consulta_autonumeracion = 'SELECT *
    											FROM '.$tabla_destino.'
    											WHERE borrado LIKE "no"
    											AND '.$campo_nombre.' LIKE "'.$redireccionar_campo[$campo_nombre]['herramientas_en_insercion'].'-%"
    											ORDER BY '.$campo_nombre.' DESC
    											LIMIT 1';
    										$query_an = $conexion->prepare($consulta_autonumeracion);
    										$query_an->execute();
    										while($rows_an = $query_an->fetch(PDO::FETCH_ASSOC)) $ultimo_numero = $rows_an[$campo_nombre];
    										$numero_a_usar = explode("-", $ultimo_numero);
    										$valor = date('Y').'-'.str_pad($numero_a_usar[1] + 1, 7, "0", STR_PAD_LEFT);
    									break;
    									
    									default:
    										$valor = "sin datos";
    									break;
    									// ------------------------------------- ASISTENTES EN INSERCION ------------------------------------- //
    								}
    							}
    							else
    							{
    								if(in_array($campo_nombre, $campos_del_sistema))
    								{
    									switch ($campo_nombre)
    									{
    										case 'id':
    										case 'modificado':
    											$valor = '';
    										break;
    
    										case 'usuario':
    											$valor = $_SESSION['usuario_en_sesion'];
    										break;
    
    										case 'creado':
    											$valor = date('Y-m-d G:i:s');
    										break;
    
    										case 'borrado':
    											$valor = 'no';
    										break;
    										
    										default:
    											$valor = 'sin datos';
    										break;
    									}
    								}
    								else
    								{
    									$valor = $datos[$campo_nombre];
    								}
    							}
    						}
    						else
    						{
    							$valor = 'sin datos';
    						}
    
    						if(in_array($campo_nombre, $campos_a_mostrar)) 
    						{
    							echo '<td style="'.$estilo_del_td.'">';
    								echo $valor;
    							echo '</td>';
    						}
    						$consulta_insercion_2.= '"'.$valor.'", ';
    					}
    					$consulta_insercion_2 = rtrim($consulta_insercion_2, ', ').')';
    					$consulta_insercion =  $consulta_insercion_1.$consulta_insercion_2;
    					$query_insercion = $conexion->prepare($consulta_insercion);
    					$query_insercion->execute();
    				echo '</tr>';
			    }
			}

			unset($_SESSION['datos_a_insertar']);
		echo '</table>';
		
		if(isset($insertar_saldos_tesoreria)) include "../../funciones/insertar-saldo-final-tesoreria.php";
		
	}
	else
	{
		echo '<table>';
			echo '<form method="post" action="">';
				echo '<tr>';
					echo '<td colspan="30">';
						echo '<input type="submit" name="vaciar_sesion_datos_a_insertar" id="boton_vaciar_sesion_datos_a_insertar" value="Volver A Empezar">';
						if(isset($_POST['vaciar_sesion_datos_a_insertar'])) unset($_SESSION['datos_a_insertar']);
					echo '</td>';
				echo '</tr>';
				if(!isset($_SESSION['datos_a_insertar'])) $_SESSION['datos_a_insertar'] = array();

				if(isset($_POST['agregar']))
				{

					$fila = count($_SESSION['datos_a_insertar']);
					foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
					{
						if(isset($redireccionar_campo[$campo_nombre]['asistente_en_post']))
						{
							switch ($redireccionar_campo[$campo_nombre]['asistente_en_post'])
							{
							    // ------------------------------------- ASISTENTES EN POST ------------------------------------- \\
								case 'traer_dato_de_otro_campo':
									$_SESSION['datos_a_insertar'][$fila][$campo_nombre] = "sin datos";
									// $herramientas_post[0] = POST a capturar
									// $herramientas_post[1] = tabla a buscar en
									// $herramientas_post[2] = campo a buscar en
									// $herramientas_post[3] = campo a traer

									$herramientas_post = explode("-", $redireccionar_campo[$campo_nombre]['herramientas_en_post']);
									$consultar_a_traer = 'SELECT '.$herramientas_post[2].', '.$herramientas_post[3].' ';
									$consultar_a_traer.= 'FROM '.$herramientas_post[1].' ';
									$consultar_a_traer.= 'WHERE borrado LIKE "no" AND '.$herramientas_post[2].' LIKE "'.$_POST[$herramientas_post[0]].'" 
									 ORDER BY '.$herramientas_post[2].' ASC LIMIT 1';
									$query_a_t = $conexion->prepare($consultar_a_traer);
									$query_a_t->execute();			
									while($rows_a_t = $query_a_t->fetch(PDO::FETCH_ASSOC)) $_SESSION['datos_a_insertar'][$fila][$campo_nombre] = $rows_a_t[$herramientas_post[3]];
								break;
								
								case 'copiar_otro_campo_del_post':
									$post_a_copiar = $redireccionar_campo[$campo_nombre]['herramientas_en_post'];
									$_SESSION['datos_a_insertar'][$fila][$campo_nombre] = "sin datos";
									$_SESSION['datos_a_insertar'][$fila][$campo_nombre] = $_POST[$post_a_copiar];
								break;

								default:
									echo "<b>Establecer el Asistente de POST -> ".$campo_nombre.'</b>';
									$_SESSION['datos_a_insertar'][$fila][$campo_nombre] = $_POST[$campo_nombre];
								break;
								// ------------------------------------- ASISTENTES EN POST ------------------------------------- //
							}
						}
						else
						{
							$_SESSION['datos_a_insertar'][$fila][$campo_nombre] = $_POST[$campo_nombre];

						}
					}

				}

				echo '<tr>';
					echo '<th id="th_fila" style="'.$estilo_del_td.'">';
						echo "Fila";
					echo '</th>';
					foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
					{
						if(in_array($campo_nombre, $campos_a_mostrar)) 
						{
							echo '<th id="th_'.$campo_nombre.'" style="'.$estilo_del_td.'">';
								echo ucwords(str_replace("_", " ", $campo_nombre));
							echo '</th>';
						}
					}
				echo '</tr>';

				$consulta_ver_cargados = 'SELECT * FROM '.$tabla_destino.' WHERE borrado LIKE "no" ';
				foreach ($filtros as $pos => $filtro)
				{
					$datos_del_filtro = explode("=", $filtro);
					$comparacion = ($datos_del_filtro[1][0] == "!") ? "NOT LIKE": "LIKE";
					$consulta_ver_cargados.= 'AND '.$datos_del_filtro[0].' '.$comparacion.' "'.$datos_del_filtro[1].'" ';
				}
				$consulta_ver_cargados.= 'ORDER BY '.$llaves_de_la_session_campos[1].' DESC LIMIT 25';
				$query_ver_cargados = $conexion->prepare($consulta_ver_cargados);
				$query_ver_cargados->execute();
				while($rows_vc = $query_ver_cargados->fetch(PDO::FETCH_ASSOC))
				{
					echo '<tr>';
						echo '<td style="'.$estilo_del_td.'color:gray;">'.$rows_vc[$llaves_de_la_session_campos[1]].'</td>';
						foreach ($campos_a_mostrar as $pos => $campo_nombre)
						{
							if(isset($redireccionar_campo[$campo_nombre]['estilo']))
							{
								switch ($redireccionar_campo[$campo_nombre]['estilo'])
								{
									case 'numero':
										echo '<td style="'.$estilo_del_td.'color:gray;text-align:right;">';
											echo number_format($rows_vc[$campo_nombre], 0);
										echo '</td>';
									break;

									case 'moneda':
										echo '<td style="'.$estilo_del_td.'color:gray;text-align:right;">';
											echo number_format($rows_vc[$campo_nombre]);
										echo '</td>';
									break;
								}
							}
							else
							{
								echo '<td style="'.$estilo_del_td.'color:gray;">';
									echo $rows_vc[$campo_nombre];
								echo '</td>';
							}
						}
					echo '</tr>';
				}


				foreach ($_SESSION['datos_a_insertar'] as $fila => $datos)
				{
					echo '<tr>';
						echo '<td style="'.$estilo_del_td.'">';
							echo $fila +1;
						echo '</td>';
						foreach ($datos as $columna => $dato)
						{
							if(in_array($columna, $campos_a_mostrar)) 
							{
								if(isset($redireccionar_campo[$columna]['estilo']))
								{
									switch ($redireccionar_campo[$columna]['estilo'])
									{
										case 'numero':
											echo '<td style="'.$estilo_del_td.'text-align:right;">';
												echo number_format($dato, 0);
											echo '</td>';
										break;

										case 'moneda':
											echo '<td style="'.$estilo_del_td.'text-align:right;">';
												echo number_format($dato);
											echo '</td>';
										break;
									}
								}
								else
								{
									echo '<td style="'.$estilo_del_td.'">';
										echo $dato;
									echo '</td>';
								}
							}
						}
						echo '<td style="'.$estilo_del_td.'">';
							echo '<input type="checkbox" name="insertar['.$fila.']" value="insertar" checked>';
						echo '</td>';
					echo '</tr>';
				}


				echo '<tr>';
					$no_es_prestacion = "si";
					$ind = 0;
					$rompe_bloques = "";
					$es_nombre_de_capitulo = "no";
					$ver_etiqueta = "no";

					echo '<td id="td_fila" style="'.$estilo_del_td.'"></td>';
					foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
					{
						if(isset($redireccionar_campo[$campo_nombre]))
						{
							if(isset($redireccionar_campo[$campo_nombre]['valor']))
							{
								if(in_array($campo_nombre, $campos_a_mostrar)) 
								{
									echo '<td id="td_'.$campo_nombre.'" style="'.$estilo_del_td.'">';
										echo $redireccionar_campo[$campo_nombre]['valor'];
									echo '</td>';
								}
								echo '<input type="hidden" name="'.$campo_nombre.'" id="'.$campo_nombre.'" value="'.$redireccionar_campo[$campo_nombre]['valor'].'">';
							}
							elseif(isset($redireccionar_campo[$campo_nombre]['asistente_en_post'])  or isset($redireccionar_campo[$campo_nombre]['asistente_en_insercion']))
							{
								if(in_array($campo_nombre, $campos_a_mostrar)) 
								{
									echo '<td id="td_'.$campo_nombre.'" style="'.$estilo_del_td.'">';
									echo '</td>';
								}
								echo '<input type="hidden" name="'.$campo_nombre.'" id="'.$campo_nombre.'" value="">';
							}
							else
							{
								if(isset($redireccionar_campo[$campo_nombre]['asistente']))
								{
									$campo_atributo['formato'] = 'oculto';
									$campo_atributo['selector'] = 'asistido';
									$campo_atributo['asistente'] = $redireccionar_campo[$campo_nombre]['asistente'];
									$campo_atributo['herramientas'] = $redireccionar_campo[$campo_nombre]['herramientas'];
								}
								
								if($campo_atributo['asistente'] != "nuevo")
								{
									$campo_atributo['formato'] = 'oculto';
									if($campo_atributo['formato'] == "oculto") echo '<td id="td_'.$campo_nombre.'" style="'.$estilo_del_td.'">';
										include '../../funciones/generar-inputs-prestaciones.php';
									if($campo_atributo['formato'] == "oculto") echo '</td>';
								}
								else
								{
									echo '<td id="td_'.$campo_nombre.'" style="'.$estilo_del_td.'">';
										$herramientas = explode("-", $campo_atributo['herramientas']);
										$atributos = (isset($herramientas[1])) ? $herramientas[1] : "";
										echo '<input type="'.$herramientas[0].'" name="'.$campo_nombre.'" id="'.$campo_nombre.'" value="" '.$atributos.'>';
									echo '</td>';
								}
							}
						}
						else
						{
							if(in_array($campo_nombre, $campos_a_mostrar)) 
							{
								echo '<td id="td_'.$campo_nombre.'" style="'.$estilo_del_td.'">';
								echo '</td>';
							}
							echo '<input type="hidden" name="'.$campo_nombre.'" id="'.$campo_nombre.'" value="no aplicable">';
						}
						$ind++;
					}
					echo '<td style="'.$estilo_del_td.'">';
						echo '<input type="submit" name="agregar" id="boton-agregar" value="Agregar">';
					echo '</td>';
				echo '</tr>';
				
				echo '<tr>';
					echo '<td colspan="20" style="'.$estilo_del_td.'">';
						echo '<input type="submit" name="finalizar" id="finalizar" value="Finalizar">';
					echo '</td>';
				echo '</tr>';

			echo '</form>';
		echo '</table>';
	}

?>

<script>
	$('input[type=checkbox]').change(function(){

		if($(this).is(":checked"))
		{
			$(this).closest('tr').find('td').css("color", "black");
		}
		else
		{
			$(this).closest('tr').find('td').css("color", "red");
		}
		
	});
</script>
