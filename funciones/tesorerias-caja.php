<?php if (!isset($_SESSION)) {session_start();}

ini_set('max_execution_time', 1200);
ini_set("memory_limit","5G");

$esta_vista = basename(__FILE__);
$subtitulo = basename(__FILE__,'.php');
$titulo_pagina = str_replace("-"," ",$esta_vista);
$titulo_pagina = str_replace(".php","",$titulo_pagina);
$esta_pagina = $titulo_pagina;
$_SESSION['titulo_pagina'] = $titulo_pagina;

$capitulo = "reportes";

$url = "../../";
include '../../funciones/mostrar-cabecera.php';

echo '<div class="top-header"';
	echo 'style="background-image: url(../../imagenes/iconos/cabecera.jpg)">';
	echo '<div class="container">';
		echo '<h1>'.$titulo_pagina.'</h1>';
	echo '</div>';
echo '</div>';
echo '<div class="container">';
	echo '<section class="interna">';
		echo '<div class="row">';
			echo '<div class="col-sm-12">';

				echo '<form method="post" action="">';
					$fecha_elegida = isset($_POST['fecha_elegida']) ? $_POST['fecha_elegida'] : date('Y-m-d');
					echo '<input type="date" name="fecha_elegida" value="'.$fecha_elegida.'">';
					echo '<input type="submit" name="elegir_fecha" value="Elegir Fecha">';
				echo '</form>';

				if(isset($_POST['elegir_fecha']) and !empty($_POST['fecha_elegida']))
				{
					echo '<br/>';
					
					$fecha_elegida_sin_guiones = date('Ymd', strtotime($fecha_elegida));

					$nombre_del_archivo = 'PSE-Cajas-'.$fecha_elegida_sin_guiones.'.pdf';
					$ruta_del_archivo = '../../vistas/sintesis/cajas/';
					if(file_exists($ruta_del_archivo.$nombre_del_archivo))
					{
						echo '<b>La caja de este dia ya fue generada.</b><br/>';
						echo '<a href="../../funciones/mostrar-menu-contenido.php?solapa=sintesis&categoria=cajas">';
							echo 'Ir a Sintesis-Cajas';
						echo '</a>';
					}
					else
					{
						echo "Caja del Dia ".$fecha_elegida;

						$consulta_cuentas_bancarias = 'SELECT id, descripcion, dato_1 FROM agrupadores
							WHERE borrado LIKE "no"
								AND agrupador LIKE "cuentas bancarias"
							ORDER BY dato_1 ASC, descripcion ASC';
						$query_cuentas_bancarias = $conexion->prepare($consulta_cuentas_bancarias);
						$query_cuentas_bancarias->execute();
						while($rows_cuentas_bancarias = $query_cuentas_bancarias->fetch(PDO::FETCH_ASSOC))
						{
							$cuenta_bancaria_numero = $rows_cuentas_bancarias['descripcion'];
							$cuenta_bancaria_banco = $rows_cuentas_bancarias['dato_1'];
							$cuenta_bancaria_compuesta = $cuenta_bancaria_banco.'-'.$cuenta_bancaria_numero;
							$cuentas_bancarias[$cuenta_bancaria_compuesta]['depositos'] = array();

							// $consulta_saldos_anteriores = 'SELECT * FROM tesorerias
							// 	WHERE borrado LIKE "no"
							// ORDER BY descripcion ASC';
							// $query_saldos_anteriores = $conexion->prepare($consulta_saldos_anteriores);
							// $query_saldos_anteriores->execute();
							// while($rows_saldos_anteriores = $query_saldos_anteriores->fetch(PDO::FETCH_ASSOC))
							// {
								$cuentas_bancarias[$cuenta_bancaria_compuesta]['saldo_anterior'] = 15000000; // VER DE DONDE SACAR
								// $cuentas_bancarias[$cuenta_bancaria_compuesta]['saldo_anterior']['derecho'] = 0; // VER DE DONDE SACAR
								// $cuentas_bancarias[$cuenta_bancaria_compuesta]['saldo_anterior']['obligacion'] = 0; // VER DE DONDE SACAR
							// }

						}
						
						$campos_depositos = array('descripcion', 'documento_numero', 'derecho', 'obligacion'); //
						$consulta_depositos = 'SELECT id, cuenta_bancaria_banco, cuenta_bancaria_numero, descripcion, documento_numero, derecho, obligacion FROM diario_migracion
							WHERE borrado LIKE "no"
								AND planilla LIKE "dep-%"
								AND descripcion LIKE "%deposito%"
								AND fecha = "'.$fecha_elegida.'"
							ORDER BY id ASC';
						$query_depositos = $conexion->prepare($consulta_depositos);
						$query_depositos->execute();
						while($rows_depositos = $query_depositos->fetch(PDO::FETCH_ASSOC))
						{
							$deposito_id = $rows_depositos['id'];
							$cuenta_bancaria_compuesta = $rows_depositos['cuenta_bancaria_banco'].'-'.$rows_depositos['cuenta_bancaria_numero'];
							foreach ($campos_depositos as $campo_depositos)
							{
								$cuentas_bancarias[$cuenta_bancaria_compuesta]['depositos'][$deposito_id][$campo_depositos] = $rows_depositos[$campo_depositos];
							}
						}

						$consulta_formas_de_pagos_a_proveedores = 'SELECT * FROM agrupadores
							WHERE borrado LIKE "no"
								AND agrupador LIKE "formas de pago a proveedores"
							ORDER BY descripcion ASC';
						$query_formas_de_pagos_a_proveedores = $conexion->prepare($consulta_formas_de_pagos_a_proveedores);
						$query_formas_de_pagos_a_proveedores->execute();
						while($rows_f_d_p_a_p = $query_formas_de_pagos_a_proveedores->fetch(PDO::FETCH_ASSOC))
						{
							$formas_de_pagos_a_proveedores[$rows_f_d_p_a_p['descripcion']]['cuenta_banco'] = $rows_f_d_p_a_p['dato_5'];
							$formas_de_pagos_a_proveedores[$rows_f_d_p_a_p['descripcion']]['cuenta_numero'] = $rows_f_d_p_a_p['dato_2'];
						}

						$ultimos_numeros_por_forma_de_pago = array();
						$campos_pagos = array('cuenta', 'documento_tipo', 'documento_numero', 'derecho', 'obligacion');
						$consulta_pagos = 'SELECT id, cuenta, documento_tipo, documento_numero, derecho, obligacion FROM diario
							WHERE borrado LIKE "no"
								AND planilla LIKE "pac-%"
								AND descripcion LIKE "cancelacion de facturas"
								AND efectuado_fecha = "'.$fecha_elegida.'"
							ORDER BY id ASC';
						$query_pagos = $conexion->prepare($consulta_pagos);
						$query_pagos->execute();
						while($rows_pagos = $query_pagos->fetch(PDO::FETCH_ASSOC))
						{
							$pago_id = $rows_pagos['id'];
							$forma_de_pago_tipo = $rows_pagos['documento_tipo'];
								
							if(isset($formas_de_pagos_a_proveedores[$forma_de_pago_tipo]))
							{
								$banco_que_sale = $formas_de_pagos_a_proveedores[$forma_de_pago_tipo]['cuenta_banco'];
								$cuenta_que_sale = $formas_de_pagos_a_proveedores[$forma_de_pago_tipo]['cuenta_numero'];
								$cuenta_bancaria_compuesta = $banco_que_sale.'-'.$cuenta_que_sale;
							}
							else
							{
								$cuenta_bancaria_compuesta = 'sin datos';
							}

							$documento_numero_a_usar = strtolower($rows_pagos['documento_numero']);
							$es_pep = (strpos($documento_numero_a_usar, 'pep') !== false);
							$documento_numero_a_usar = ltrim($documento_numero_a_usar, 'pep');
							$documento_numero_a_usar = trim($documento_numero_a_usar);
							$documento_numero_a_usar = ltrim($documento_numero_a_usar, '0');
							if($es_pep) $documento_numero_a_usar = 'PEP '.str_pad($documento_numero_a_usar, 7, '0', STR_PAD_LEFT);
							if(!isset($cuentas_bancarias[$cuenta_bancaria_compuesta]['pagos_resumen'][$forma_de_pago_tipo]))
							{
								$cuentas_bancarias[$cuenta_bancaria_compuesta]['pagos_resumen'][$forma_de_pago_tipo]['descripcion'] = $documento_numero_a_usar;
								$cuentas_bancarias[$cuenta_bancaria_compuesta]['pagos_resumen'][$forma_de_pago_tipo]['monto'] = 0;
							}
							$ultimos_numeros_por_forma_de_pago[$cuenta_bancaria_compuesta][$forma_de_pago_tipo] = $documento_numero_a_usar;
							$cuentas_bancarias[$cuenta_bancaria_compuesta]['pagos_resumen'][$forma_de_pago_tipo]['monto']+= $rows_pagos['derecho'];

							foreach ($campos_pagos as $campo_pagos)
							{
								$cuentas_bancarias[$cuenta_bancaria_compuesta]['pagos_individuales'][$pago_id][$campo_pagos] = $rows_pagos[$campo_pagos];
							}
						}

						foreach ($ultimos_numeros_por_forma_de_pago as $cuenta_bancaria_compuesta => $formas_de_pago_tipo)
						{
							foreach ($formas_de_pago_tipo as $forma_de_pago_tipo => $ultimo_numero)
							{
								if($cuentas_bancarias[$cuenta_bancaria_compuesta]['pagos_resumen'][$forma_de_pago_tipo]['descripcion'] != $ultimo_numero)
								{
									$cuentas_bancarias[$cuenta_bancaria_compuesta]['pagos_resumen'][$forma_de_pago_tipo]['descripcion'].= '-'.$ultimo_numero;
								}
							}
						}

						echo '<table class="tabla_caja">';
							echo '<tr>';
								foreach ($campos_depositos as $campo_depositos)
								{
									echo '<td>';
										echo '<b>';
											echo ucwords(str_replace('_', ' ', $campo_depositos));
										echo '</b>';
									echo '</td>';
								}
								echo '<td>';
									echo '<b>Saldo</b>';
								echo '</td>';
							echo '</tr>';
							$saldos_consolidados['derecho'] = 0;
							$saldos_consolidados['obligacion'] = 0;
							$saldos_consolidados['saldo'] = 0;
							foreach ($cuentas_bancarias as $cuenta_bancaria_compuesta => $cuenta_bancaria_datos)
							{
								$cuenta_bancaria_explotada = explode('-', $cuenta_bancaria_compuesta);
								$cuenta_bancaria_banco = $cuenta_bancaria_explotada[0];
								$cuenta_bancaria_numero = $cuenta_bancaria_explotada[1];
								echo '<tr>';
									echo '<td colspan="'.(count($campos_depositos)+1).'">';
										echo '<b>';
											echo ucwords($cuenta_bancaria_banco).' - '.$cuenta_bancaria_numero;
										echo '</b>';
									echo '</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<td colspan="'.(count($campos_depositos)-2).'">';
										echo 'Saldo Anterior';
									echo '</td>';
									echo '<td style="text-align:right;">';
										echo '0';
									echo '</td>';
									echo '<td style="text-align:right;">';
										echo '0';
									echo '</td>';
									echo '<td style="text-align:right;">';
										echo number_format($cuenta_bancaria_datos['saldo_anterior']);
									echo '</td>';
								echo '</tr>';
								$saldo_a_la_fecha['derecho'] = 0;
								$saldo_a_la_fecha['obligacion'] = 0;
								$saldo_a_la_fecha['saldo'] = $cuenta_bancaria_datos['saldo_anterior'];
								if(isset($cuenta_bancaria_datos['depositos']))
								{
									foreach ($cuenta_bancaria_datos['depositos'] as $deposito_id => $deposito_datos)
									{
										echo '<tr>';
											foreach ($deposito_datos as $deposito_campo => $deposito_valor)
											{
												switch ($deposito_campo)
												{
													case 'derecho':
													case 'obligacion':
													case 'saldo':
														echo '<td style="text-align:right;">';
															echo number_format($deposito_valor);
														echo '</td>';
													break;
													
													default:
														echo '<td>';
															echo $deposito_valor;
														echo '</td>';
													break;
												}
											}
											$saldo_a_la_fecha['derecho']+= $deposito_datos['derecho'];
											$saldo_a_la_fecha['obligacion']+= $deposito_datos['obligacion'];
											$saldo_a_la_fecha['saldo']+= $deposito_datos['derecho'] - $deposito_datos['obligacion'];
											echo '<td style="text-align:right;">';
												echo number_format($saldo_a_la_fecha['saldo']);
											echo '</td>';
										echo '</tr>';
									}
								}

								if(isset($cuenta_bancaria_datos['pagos_resumen']))
								{
									foreach ($cuenta_bancaria_datos['pagos_resumen'] as $forma_de_pago_tipo => $forma_de_pago_datos)
									{
										echo '<tr>';
											echo '<td>';
												echo $forma_de_pago_tipo;
											echo '</td>';
											foreach ($forma_de_pago_datos as $forma_de_pago_campo => $forma_de_pago_valor)
											{
												switch ($forma_de_pago_campo)
												{
													case 'derecho':
													case 'obligacion':
													case 'saldo':
														echo '<td style="text-align:right;">';
															echo number_format($forma_de_pago_valor);
														echo '</td>';
													break;
													case 'monto':
														echo '<td>';
														echo '</td>';
														echo '<td style="text-align:right;">';
															echo number_format($forma_de_pago_valor);
														echo '</td>';
													break;
													
													default:
														echo '<td>';
															echo $forma_de_pago_valor;
														echo '</td>';
													break;
												}
											}
											$saldo_a_la_fecha['derecho']+= 0;
											$saldo_a_la_fecha['obligacion']+= $forma_de_pago_datos['monto'];
											$saldo_a_la_fecha['saldo']-= $forma_de_pago_datos['monto'];
											echo '<td style="text-align:right;">';
												echo number_format($saldo_a_la_fecha['saldo']);
											echo '</td>';
										echo '</tr>';
									}
								}
								echo '<tr>';
									echo '<td colspan="'.(count($campos_depositos)-2).'">';
										echo 'Saldo A La Fecha';
									echo '</td>';
									echo '<td style="text-align:right;">';
										echo number_format($saldo_a_la_fecha['derecho']);
										$saldos_consolidados['derecho']+= $saldo_a_la_fecha['derecho'];
									echo '</td>';
									echo '<td style="text-align:right;">';
										echo number_format($saldo_a_la_fecha['obligacion']);
										$saldos_consolidados['obligacion']+= $saldo_a_la_fecha['obligacion'];
									echo '</td>';
									echo '<td style="text-align:right;">';
										echo number_format($saldo_a_la_fecha['saldo']);
										$saldos_consolidados['saldo']+= $saldo_a_la_fecha['saldo'];
									echo '</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<td colspan="'.(count($campos_depositos)+1).'">';
										echo '&nbsp&nbsp&nbsp';
									echo '</td>';
								echo '</tr>';
							}
							echo '<tr>';
								echo '<td colspan="'.(count($campos_depositos)-2).'">';
									echo 'Saldo Consolidado';
								echo '</td>';
								echo '<td style="text-align:right;">';
									echo number_format($saldos_consolidados['derecho']);
								echo '</td>';
								echo '<td style="text-align:right;">';
									echo number_format($saldos_consolidados['obligacion']);
								echo '</td>';
								echo '<td style="text-align:right;">';
									echo number_format($saldos_consolidados['saldo']);
								echo '</td>';
							echo '</tr>';
						echo '</table>';

						foreach ($cuentas_bancarias as $cuenta_bancaria_compuesta => $cuenta_bancaria_datos)
						{
							echo '<br/>';
							echo '<br/>';
							echo 'NUEVA PAGINA';
							echo '<table class="tabla_caja">';
								echo '<tr>';
									echo '<td colspan="20">';
										echo '<b>Pagos Del Dia '.$fecha_elegida.'</b>';
									echo '</td>';
								echo '</tr>';
								$cuenta_bancaria_explotada = explode('-', $cuenta_bancaria_compuesta);
								$cuenta_bancaria_banco = $cuenta_bancaria_explotada[0];
								$cuenta_bancaria_numero = $cuenta_bancaria_explotada[1];

								echo '<tr>';
									echo '<td colspan="20">';
										echo '<b>Pagos De la Cuenta '.$cuenta_bancaria_compuesta.'</b>';
									echo '</td>';
								echo '</tr>';
								$total_pagado_desde_esta_cuenta = 0;
								if(isset($cuenta_bancaria_datos['pagos_individuales']))
								{
									foreach ($cuenta_bancaria_datos['pagos_individuales'] as $pago_id => $pago_datos)
									{
										echo '<tr>';
											foreach ($pago_datos as $pago_campo => $pago_valor)
											{
												switch ($pago_campo)
												{
													case 'derecho':
														echo '<td style="text-align:right;">';
															echo number_format($pago_valor);
														echo '</td>';
													break;
													
													case 'obligacion':
														// echo '<td>';
														// echo '</td>';
													break;
													
													default:
														echo '<td>';
															echo ucwords($pago_valor);
														echo '</td>';
													break;
												}
											}
										$total_pagado_desde_esta_cuenta+= $pago_datos['derecho'];
										echo '</tr>';
									}
									echo '<tr>';
										echo '<td colspan="1">';
											echo 'Cantidad de Pagos';
										echo '</td>';
										echo '<td style="text-align:right;">';
											echo count($cuenta_bancaria_datos['pagos_individuales']);
										echo '</td>';
										echo '<td style="text-align:right;">';
											echo 'Total';
										echo '</td>';
										echo '<td style="text-align:right;">';
											echo number_format($total_pagado_desde_esta_cuenta);
										echo '</td>';
									echo '</tr>';
								}
							echo '</table>';
						}
					}
				}

		echo '</div>';
	echo '</div>';
echo '</section>';
echo '</div>';

include '../../funciones/mostrar-pie.php';

echo '</body>';
echo '</html>';

?>
