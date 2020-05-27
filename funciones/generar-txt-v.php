<?php if (!isset($_SESSION)) {session_start();}

	$estructuras = array();
	switch ($_SESSION['switch_de_estructura_txt'])
	{
		case 'itau planilla electronica de salarios':
			$switch_de_estructura = 'itau salarios';
			$valores_fijos['ITITRA'] = '01';
			$valores_fijos['ITCRDB'] = 'C';
			$valores_fijos['ITIFAC'] = '0';
			$valores_fijos['INRFAC'] = ' ';
			include '../../funciones/estructura-txt-itau.php';
		break;

		case 'itau pago a proveedores':
			$switch_de_estructura = 'itau proveedores';
			$valores_fijos['ITITRA'] = '02';
			$valores_fijos['ITCRDB'] = 'C';
			include '../../funciones/estructura-txt-itau.php';
		break;

		case 'itau cheque administrativo':
			$switch_de_estructura = 'itau cheque administrativo';
			$valores_fijos['ITITRA'] = '02';
			$valores_fijos['ITCRDB'] = 'H';
			include '../../funciones/estructura-txt-itau.php';
		break;

		case 'planilla de ips':
			$switch_de_estructura = 'ips';
			include '../../funciones/estructura-txt-ips.php';
		break;
		
		default:
			$switch_de_estructura = '';
		break;
	}

	$monto_total = 0;
	$contenido_del_txt = '';
	foreach ($_SESSION['elementos_a_exportar'] as $cuenta => $monto)
	{
		$linea_texto = "";
		foreach ($estructuras as $campo_nombre => $estructura)
		{
			switch ($switch_de_estructura)
			{
				case 'itau':
				case 'itau salarios':
				case 'itau proveedores':
				case 'itau cheque administrativo':
					switch ($estructura['tipo'])
					{
						case 'alfa':
							$caracter_pad = ' ';
							$direccion_pad = STR_PAD_RIGHT;
							$comienzo_del_substr = 0;
						break;
						case 'numerico':
							$caracter_pad = '0';
							$direccion_pad = STR_PAD_LEFT;
							$comienzo_del_substr = (-1 * $estructura['largo']);
						break;
					}
				break;

				case 'ips':
					$direccion_pad = STR_PAD_LEFT;
					$comienzo_del_substr = 0;
					switch ($estructura['tipo'])
					{
						case 'alfa':
							$caracter_pad = ' ';
						break;
						case 'numerico':
							$caracter_pad = '0';
						break;
					}
				break;
			}

			if(!isset($estructura['valor']) and !isset($valores_fijos[$campo_nombre]))
			{
				switch ($switch_de_estructura)
				{
					case 'itau':
					case 'itau salarios':
						switch ($campo_nombre)
						{
							case 'ICTCRE': // NUMERO DE CUENTA
				                $valor_largo_controlado = substr($_SESSION['datos_extra_para_exportar'][$cuenta]['cuenta_itau'], $comienzo_del_substr, $estructura['largo']);
								$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
							break;

							case 'IORDEN': // A NOMBRE DE QUIEN LA CUENTA || HAY CASOS QUE SE DEPOSITA EN CUENTA DE OTRA PERSONA \\
								$cuenta_a_usar = (isset($_SESSION['datos_extra_para_exportar'][$cuenta]['cuenta_a_usar'])) ? $_SESSION['datos_extra_para_exportar'][$cuenta]['cuenta_a_usar'] : $cuenta;
								$cuenta_limpia = substr(str_replace('ñ', 'n', $cuenta_a_usar), 0, $estructura['largo']);
								$valor_largo_controlado = substr($cuenta_limpia, $comienzo_del_substr, $estructura['largo']);
								$linea_texto.= str_pad(strtoupper($valor_largo_controlado), $estructura['largo'], $caracter_pad, $direccion_pad);
							break;

							case 'IMTOTR':
								$monto_limpio = number_format($monto);
								$monto_limpio = str_replace(',', '', $monto_limpio);
								$monto_limpio.= '00';
								$valor_largo_controlado = substr($monto_limpio, $comienzo_del_substr, $estructura['largo']);

								$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
								$monto_total += $monto;
							break;

							case 'ITIFAC':
							case 'INRFAC':
							case 'INRODO':
								$linea_texto.= str_pad('', $estructura['largo'], $caracter_pad, $direccion_pad);
							break;
						}
					break;

					case 'itau proveedores':
					case 'itau cheque administrativo':
						// $cuenta en este caso sería el numero_de_orden. Asi permite que una misma cuenta tenga MAS DE UN movimientos en el TXT. Asi se saca UN PAGO por FACTURA.
						$cuenta_a_buscar = $_SESSION['datos_extra_para_exportar'][$cuenta]['cuenta'];
						switch ($campo_nombre)
						{
							case 'ICTCRE':
								$valor_cuenta_numero = ($switch_de_estructura == 'itau cheque administrativo') ? '0' : $_SESSION['datos_extra_para_exportar'][$cuenta]['cuenta_bancaria_numero'];
				                $valor_largo_controlado = substr($valor_cuenta_numero, $comienzo_del_substr, $estructura['largo']);
								$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
							break;

							case 'IORDEN':
								$cuenta_limpia = substr(str_replace('ñ', 'n', $_SESSION['datos_extra_para_exportar'][$cuenta]['cuenta_bancaria_titular']), 0, $estructura['largo']);
								$valor_largo_controlado = substr($cuenta_limpia, $comienzo_del_substr, $estructura['largo']);
								$linea_texto.= str_pad(strtoupper($valor_largo_controlado), $estructura['largo'], $caracter_pad, $direccion_pad);
							break;

							case 'IMTOTR':
								$monto_limpio = number_format($monto);
								$monto_limpio = str_replace(',', '', $monto_limpio);
								$monto_limpio.= '00';
								$valor_largo_controlado = substr($monto_limpio, $comienzo_del_substr, $estructura['largo']);

								$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
								$monto_total+= $monto;
							break;

							case 'ITIFAC':
								switch ($_SESSION['datos_extra_para_exportar'][$cuenta]['factura_tipo'])
								{
									case 'credito':
										$factura_tipo = '2';
									break;
									
									case 'contado':
									default:
										$factura_tipo = '1';
									break;
								}
								$linea_texto.= str_pad($factura_tipo, $estructura['largo'], $caracter_pad, $direccion_pad);
							break;
							case 'INRFAC':
								$factura_numero = $_SESSION['datos_extra_para_exportar'][$cuenta]['factura_numero'];
								$valor_largo_controlado = substr($factura_numero, $comienzo_del_substr, $estructura['largo']);
								$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
							break;

							case 'INRODO':
								$linea_texto.= str_pad($_SESSION['datos_extra_para_exportar'][$cuenta]['documento_numero'], $estructura['largo'], $caracter_pad, $direccion_pad);
							break;
						}
					break;

					case 'ips':
						switch ($campo_nombre)
						{
							case 'numero_asegurado_ips':
							case 'dias_trabajados':
				                $valor_largo_controlado = substr($_SESSION['datos_extra_para_exportar'][$cuenta][$campo_nombre], $comienzo_del_substr, $estructura['largo']);
								$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
							break;

							case 'cedula_identidad':
				                $valor_largo_controlado = substr($_SESSION['datos_extra_para_exportar'][$cuenta]['documento_numero'], $comienzo_del_substr, $estructura['largo']);
								$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
							break;

							case 'nombres':
							case 'apellidos':
								$cuenta_explotada_a_usar = ($campo_nombre == 'nombres') ? 1 : 0;
								$cuenta_explotada = explode(', ', $cuenta)[$cuenta_explotada_a_usar];
								$cuenta_limpia = str_replace('ñ', '-', $cuenta_explotada);
								$cuenta_limpia = str_replace('Ñ', '-', $cuenta_limpia);
				                $valor_largo_controlado = substr($cuenta_limpia, $comienzo_del_substr, $estructura['largo']);
								$linea_limpia = str_pad(strtoupper($valor_largo_controlado), $estructura['largo'], $caracter_pad, $direccion_pad);
				                $linea_limpia = str_replace('-', 'Ñ', $linea_limpia);
								$linea_texto.= $linea_limpia;
							break;

							// case 'salario_imponible':
							// 	$salario_imponible = ($_SESSION['datos_extra_para_exportar'][$cuenta]['salario_basico'] < $datos_extra_para_exportar['salario_minimo']) ? $datos_extra_para_exportar['salario_minimo'] : $_SESSION['datos_extra_para_exportar'][$cuenta]['salario_basico'];
				   //              $valor_largo_controlado = substr($salario_imponible + 0, $comienzo_del_substr, $estructura['largo']);
							// 	$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
							// break;

							case 'periodo_de_pago':
								$planilla_seleccionada_explotada = explode('-', $planilla_seleccionada);
								$periodo_de_pago = ltrim($planilla_seleccionada_explotada[2], '0').$planilla_seleccionada_explotada[1];
				                $valor_largo_controlado = substr($periodo_de_pago, $comienzo_del_substr, $estructura['largo']);
								$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
							break;

							case 'salario_imponible':
							case 'salario_real':
								// total cobrado imponible para el aporte obrero ?? base de calculo para aporte obrero ??
								
								$valor_largo_controlado = substr($monto, $comienzo_del_substr, $estructura['largo']);

								$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
							break;
						}
					break;
				}
			}
			else
			{
				$valor_por_defecto = (isset($estructura['valor'])) ? $estructura['valor'] : $valores_fijos[$campo_nombre];
				$valor_largo_controlado = substr($valor_por_defecto, $comienzo_del_substr, $estructura['largo']);
				$linea_texto.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
			}
		}

		$contenido_del_txt.= $linea_texto."\n";
	}

	if(isset($necesita_linea_control) and $necesita_linea_control == 'si')
	{
		$linea_control = '';
		foreach ($estructuras as $campo_nombre => $estructura)
		{
			switch ($estructura['tipo'])
			{
				case 'alfa':
					$caracter_pad = ' ';
					$direccion_pad = STR_PAD_RIGHT;
					$comienzo_del_substr = 0;
				break;
				case 'numerico':
					$caracter_pad = '0';
					$direccion_pad = STR_PAD_LEFT;
					$comienzo_del_substr = (-1 * $estructura['largo']);
				break;
			}

			$valor_a_usar = ($campo_nombre == 'IMTOTR') ? round($monto_total).'00' : $estructura['control'];
			$valor_largo_controlado = substr($valor_a_usar, $comienzo_del_substr, $estructura['largo']);
			$linea_control.= str_pad($valor_largo_controlado, $estructura['largo'], $caracter_pad, $direccion_pad);
		}
		$contenido_del_txt.= $linea_control;
	}
	
?>
