<?php if (!isset($_SESSION)) {session_start();}		
		
	if(isset($listado_tipo))
	{		
		if(isset($_GET['id']) or isset($_GET['caso']) and $_GET['caso'] == "agregar")
		{ 						
			echo '<div class="contabm">';
				echo '<table class="fondoabm">';

					if($listado_tipo != "consultas") echo '<form action="" method="post" enctype="multipart/form-data">';

						if($listado_tipo == "altas")
						{
							foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
							{
								echo '<tr>';
									echo '<td>';
										$ind++;
										if(isset($_POST[$campo_nombre]) and isset($_POST['grabar'])) $rows[$campo_nombre] = $_POST[$campo_nombre];
										include "../funciones/generar-inputs.php"; 
									echo '</td>';
								echo '</tr>';
							}
						}
						else
						{
						    
							$consulta = 'SELECT * 
							FROM '.$tabla_a_procesar.' 
							WHERE borrado = "no"
							AND id = '.$_GET['id'];

							$query = $conexion->prepare($consulta);
							$query->execute();
							
							while($rows = $query->fetch(PDO::FETCH_ASSOC))
							{
								foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
								{
									switch ($listado_tipo) 
									{									
										case "modificaciones":
											echo '<tr>';
												echo '<td>';
													$ind++;
													include "../funciones/generar-inputs.php"; 
												echo '</td>';
											echo '</tr>';
										break;

										case "consultas": case "bajas":
											echo '<tr>';
												echo '<td>';
													echo '<b>'.str_replace("_"," ",ucwords($campo_nombre)).'</b>';
												echo '</td>';
												if($listado_tipo == "bajas")
												{
												    if($campo_nombre == "borrado")
												    {
												        echo '<td class="izquierda">';
												            $campo_atributo['asistente'] = "opcion-especifica";
												            if(!isset($campo_atributo['herramientas'])) $campo_atributo['herramientas'] = "agrupadores-descripcion-agrupador-motivos de borrado del sistema";
    														include "../funciones/generar-inputs-asistidos.php";
    													echo '</td>';
												    }
												    elseif($campo_nombre == "usuario")
												    {
												        echo '<td class="izquierda">';
												            $campo_atributo['asistente'] = "ultimo_usuario";
    														include "../funciones/generar-inputs-asistidos.php";
    													echo '</td>';
												    }
												    else
												    {
												        if($campo_atributo['formato'] == "texto" or 
        												   $campo_atributo['formato'] == "fecha" or 
        												   $campo_atributo['formato'] == "asistido" or
        												   $campo_atributo['formato'] == "hora" or
        												   $campo_atributo['formato'] == "oculto"
        												   )
        												{
        													echo '<td class="izquierda">';
        														echo $rows[$campo_nombre];
        													echo '</td>';									
        												} 
        												if($campo_atributo['formato'] == "numero-texto")
        												{
        													echo '<td class="derecha">';
        														echo $rows[$campo_nombre];
        													echo '</td>';
        												}									
        												if($campo_atributo['formato'] == "numero")
        												{											
        													echo '<td class="derecha">';
        														echo number_format($rows[$campo_nombre],$campo_atributo['decimales'],",",".");
        													echo '</td>';
        												}
												    }
												}
												else
												{
												    if($campo_atributo['formato'] == "texto" or 
        												   $campo_atributo['formato'] == "fecha" or 
        												   $campo_atributo['formato'] == "asistido" or
        												   $campo_atributo['formato'] == "hora" or
        												   $campo_atributo['formato'] == "oculto" or
        												   $campo_atributo['formato'] == "vista"
        												   )
        												{
        													echo '<td class="izquierda">';
        														echo $rows[$campo_nombre];
        													echo '</td>';									
        												} 
        												if($campo_atributo['formato'] == "numero-texto")
        												{
        													echo '<td class="derecha">';
        														echo $rows[$campo_nombre];
        													echo '</td>';
        												}									
        												if($campo_atributo['formato'] == "numero")
        												{											
        													echo '<td class="derecha">';
        														echo number_format($rows[$campo_nombre],$campo_atributo['decimales'],",",".");
        													echo '</td>';
        												}
												}
											echo '</tr>';
										break;
										
										default:
											# code...
										break;
									}
								}
							}
						}
						echo '<tr>';
							echo '<td colspan="3">';
								switch ($listado_tipo) 
								{
									case "altas":
										$hoy = date("Y-m-d H:i:s");
										echo '<tr>';
											echo '<td colspan="3">';

												$post = (isset($_POST['grabar'])) ? "si" : "";
												$accion = "agregar";
												$capitulo = "altas";
												$salida_a = $esta_vista;										
												include '../funciones/mostrar-boton-submit.php';									

											echo '</td>';
										echo '</tr>';
										if(isset($_POST['grabar']))
										{
										    // consulta de control
											if(isset($campos_para_control_de_repeticion))
											{
												$control_de_reinsercion = "ok";
												$consulta_control_reinsercion = 'SELECT ';
												foreach ($campos_para_control_de_repeticion as $pos => $campo_c_r)  $consulta_control_reinsercion.= $campo_c_r.', ';
												$consulta_control_reinsercion = rtrim($consulta_control_reinsercion, ', ');
												$consulta_control_reinsercion.= ' FROM '.$tabla_a_procesar.' WHERE borrado LIKE "no"';
												foreach ($campos_para_control_de_repeticion as $pos => $campo_c_r)
												{
													if(empty($_POST[$campo_c_r]))
													{
														$valor_c_r = ($_SESSION['campos'][$campo_c_r]['formato'] == "numero") ? "0" : "sin datos";
													}
													else
													{
														$valor_c_r = $_POST[$campo_c_r];
													}
													$comparador_c_r = ($_SESSION['campos'][$campo_c_r]['formato'] == "numero") ? "=" : "LIKE";
													$consulta_control_reinsercion.= ' AND '.$campo_c_r.' '.$comparador_c_r.' "'.$valor_c_r.'"';
												}
												$query_control_reinsercion = $conexion->prepare($consulta_control_reinsercion);
										       	$query_control_reinsercion->execute();
										       	while($rows_c_r = $query_control_reinsercion->fetch(PDO::FETCH_ASSOC)) $control_de_reinsercion = "detectado";
											}
											
											$consulta = "INSERT INTO `".$tabla_a_procesar."`(`";									
											foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
											{
												$consulta.= $campo_nombre.'`,`';
											} 
											$consulta = rtrim($consulta, "`");
											$consulta = rtrim($consulta, ",").") VALUES ('";
											foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
											{
												$vacio_a_controlar = trim($_POST[$campo_nombre]);
												if(empty($vacio_a_controlar))
												{
													if(isset($campo_atributo['default_en_vacio']))
													{
														$consulta.= trim(strtolower($campo_atributo['default_en_vacio']))."','";
													}
													else
													{
														if($campo_nombre == "id" or $campo_nombre == "cotizacion" or $campo_nombre == "costo")
														{
															$consulta.= "0','";
														}
														else
														{
															if($campo_nombre == "creado" or $campo_nombre == "modificado")
															{
																$consulta.= "".$hoy."','";
															}
															else
															{
																if($campo_nombre == "borrado")
																{
																	$consulta.= "no','";
																}
																else
																{ 
																	if($campo_nombre == "entrada_zona_03" or $campo_nombre == "entrada_zona_04" or $campo_nombre == "entrada_zona_05" or $campo_nombre == "entrada_zona_06" or $campo_nombre == "entrada_zona_07" or $campo_nombre == "entrada_zona_08" or $campo_nombre == "entrada_zona_09" or $campo_nombre == "entrada_zona_10" or $campo_nombre == "entrada_zona_11" or $campo_nombre == "entrada_zona_12" or $campo_nombre == "entrada_zona_13" or $campo_nombre == "entrada_zona_14" or $campo_nombre == "entrada_zona_15" or $campo_nombre == "entrada_zona_16" or $campo_nombre == "entrada_zona_96" or $campo_nombre == "entrada_zona_97" or 
																	 	 $campo_nombre == "entrada_zona_98" or $campo_nombre == "entrada_zona_99" or $campo_nombre == "servicio_fecha" or $campo_nombre == "contratacion" or $campo_nombre == "finalizacion" or $campo_nombre == "entrada_zona_02")
																	{
																		$consulta.= "0000-00-00 00:00:00','";
																	}
																	else
																	{ 		
																		$consulta.= "sin datos','";
																	}
																}
															}
														}
													}
												}
												else
												{
													switch ($campo_atributo['asistente'])
													{
														case 'autonumeracion':
															include "../funciones/autonumerar.php";
														break;
														
														default:
															$consulta.= trim(strtolower($_POST[$campo_nombre]))."','";
														break;
													}
												}

											}											
											$consulta = $consulta.")";
											$consulta = str_replace(",')",")",$consulta);

                                            if(isset($control_de_reinsercion) and $control_de_reinsercion == "detectado")
											{
												echo "Esta informacion ya existe.<br/>";
												echo "Verifique nuevamente y/o hable con un programador.<br/>";
											}
											else
											{
												try
												{
													$query = $conexion->prepare($consulta);
													$query->execute();
													echo "Insertado!";
												}
												catch (Exception $e)
												{
													echo 'ERROR: '.$e;
												}
											}
										}
									break;

									case "consultas":

										echo '<tr>';
											echo '<td colspan="3">';
												$salida_a = $esta_vista;					
												$capitulo = "consultas";
												echo '<a class="salir" href="'.$salida_a.'?tabla_a_procesar='.$tabla_a_procesar.'&capitulo='.$capitulo.'">Salir</a>';
										
											echo '</td>';
										echo '</tr>';
									
									break;									

									case "modificaciones":


										echo '<tr>';
											echo '<td colspan="3">';
										
												$post = (isset($_POST['grabar'])) ? "si" : "";
												$accion = "modificar";
												$capitulo = "modificaciones";
												$salida_a = $esta_vista;					
												include "../funciones/mostrar-boton-submit.php";
											
											echo '</td>';
										echo '</tr>';

										if(isset($_POST['grabar']))
										{

											$id = $_POST['id'];
											$consulta = 'UPDATE '.$tabla_a_procesar.' SET ';
											foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
											{
												if($campo_nombre != "id" AND $campo_nombre != "modificado" AND $campo_nombre != "borrado")
												{
													$vacio_a_controlar = trim($_POST[$campo_nombre]);
													if(empty($vacio_a_controlar))
													{
														if(isset($campo_atributo['default_en_vacio']))
														{
															$consulta.="`".$campo_nombre."` = "."'".trim(strtolower($campo_atributo['default_en_vacio']))."',";
														}
														else
														{
															$consulta.="`".$campo_nombre."` = "."'sin datos',";
														}
													}
													else
													{

														if($campo_nombre == "baja_motivo")
														{
															if(empty($_POST['baja_fecha']) or $_POST['baja_fecha'] == "0000:00:00")
															{
																$consulta.= "no aplicable','";
															}
															else
															{
																$consulta.= trim(strtolower($_POST[$campo_nombre]))."','";
															}
														}
														else
														{
															
															if($campo_nombre == "baja_motivo")
															{
																if(empty($_POST['baja_fecha']) or $_POST['baja_fecha'] == "0000:00:00")
																{
																	$consulta.="`".$campo_nombre."` = 'no aplicable',";
																}
																else
																{
																	$consulta.="`".$campo_nombre."` = "."'".trim(strtolower($_POST[$campo_nombre]))."',";
																}
															}
															else
															{
																$consulta.="`".$campo_nombre."` = "."'".trim(strtolower($_POST[$campo_nombre]))."',";
															}
														}
													}
												} 
											}
											$consulta = substr($consulta, 0, -1);
											$consulta.=" WHERE id=".$id;

											$query = $conexion->prepare($consulta);
											$query->execute();
										}
									break;

									case "bajas":

										echo '<tr>';
											echo '<td colspan="3">';

												$post = (isset($_POST['grabar'])) ? "si" : "";
												$accion = "borrar";
												$capitulo = "bajas";
												$salida_a = $esta_vista;														
												include $url.'funciones/mostrar-boton-submit.php';
											
											echo '</td>';
										echo '</tr>';
										if(isset($_POST['grabar']))
										{			
											$id = $_POST['id'];
											$controlDeStringVacio = 0;
											if(isset($_POST['grabar']))
											{
												$consulta = 'UPDATE '.$tabla_a_procesar.' SET ';
												//$consulta.= '`borrado` = "si",';
												$consulta.= '`borrado` = "'.$_POST['borrado_motivo'].'"';
												$consulta.=" WHERE id=".$id;
												
												$query = $conexion->prepare($consulta);
												$query->execute();
											
											}
										}
									break;

									default:
									break;
								}
							echo '</td>';
						echo '</tr>';
					if($listado_tipo != "consultas") echo '</form>';

				echo '</table>';
			echo '</div>';
		}
	}
	else
	{
		echo "No esta definido el tipo de listado.";
	}
?>