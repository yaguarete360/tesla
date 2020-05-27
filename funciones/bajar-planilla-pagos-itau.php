<?php if (!isset($_SESSION)) {session_start();}

	// FALTA: completar las cuentas Itau de cada funcionario (ICTCRE) y ver las referencias (INRREF) !!!

	header("Content-Disposition: attachment; filename=".$_POST['nombre_del_archivo'].".txt");
	header("Content-type: text/plain");
	header("Content-Type: application/force-download");

	$estructuras['ITIREG']['tipo'] = 'alfa';
	$estructuras['ITIREG']['largo'] = 1;
	$estructuras['ITIREG']['decimales'] = 0;
	$estructuras['ITIREG']['valor'] = 'D'; // D=registro de detalle, C=registro de control
	$estructuras['ITIREG']['control'] = 'C'; // D=registro de detalle, C=registro de control
	
	$estructuras['ITITRA']['tipo'] = 'alfa';
	$estructuras['ITITRA']['largo'] = 2;
	$estructuras['ITITRA']['decimales'] = 0;
	$estructuras['ITITRA']['valor'] = '01'; // 01= pago de salarios, 02=pago proveedores, 03=cobro de factura cuotas, 09=debitos comandados
	$estructuras['ITITRA']['control'] = '01'; // 01= pago de salarios, 02=pago proveedores, 03=cobro de factura cuotas, 09=debitos comandados
	
	$estructuras['ICDSRV']['tipo'] = 'numerico';
	$estructuras['ICDSRV']['largo'] = 3;
	$estructuras['ICDSRV']['decimales'] = 0;
	$estructuras['ICDSRV']['valor'] = '126'; // codigo de cliente parque
	$estructuras['ICDSRV']['control'] = '126'; // codigo de cliente parque
	
	$estructuras['ICTDEB']['tipo'] = 'numerico';
	$estructuras['ICTDEB']['largo'] = 10;
	$estructuras['ICTDEB']['decimales'] = 0;
	$estructuras['ICTDEB']['valor'] = '0000432038'; // cuenta de parque
	$estructuras['ICTDEB']['control'] = '0000432038'; // cuenta de parque
	
	$estructuras['IBCOCR']['tipo'] = 'numerico';
	$estructuras['IBCOCR']['largo'] = 3;
	$estructuras['IBCOCR']['decimales'] = 0;
	$estructuras['IBCOCR']['valor'] = '17'; // banco itau
	$estructuras['IBCOCR']['control'] = '17'; // banco itau
	
	$estructuras['ICTCRE']['tipo'] = 'numerico';
	$estructuras['ICTCRE']['largo'] = 10;
	$estructuras['ICTCRE']['decimales'] = 0;
	// $estructuras['ICTCRE']['valor'] = '';// traer de organigrama // cuenta itau del funcionario
	$estructuras['ICTCRE']['control'] = '';// traer de organigrama // cuenta itau del funcionario
	
	$estructuras['ITCRDB']['tipo'] = 'alfa';
	$estructuras['ITCRDB']['largo'] = 1;
	$estructuras['ITCRDB']['decimales'] = 0;
	$estructuras['ITCRDB']['valor'] = 'C'; // D=debito, C=credito, H=cheque, F=Cobro de factura/cuota
	$estructuras['ITCRDB']['control'] = 'D'; // D=debito, C=credito, H=cheque, F=Cobro de factura/cuota
	
	$estructuras['IORDEN']['tipo'] = 'alfa';
	$estructuras['IORDEN']['largo'] = 50;
	$estructuras['IORDEN']['decimales'] = 0;
	// $estructuras['IORDEN']['valor'] = '';// funcionario nombre
	$estructuras['IORDEN']['control'] = 'PARQUE SERENIDAD';// funcionario nombre
	
	$estructuras['IMONED']['tipo'] = 'numerico';
	$estructuras['IMONED']['largo'] = 1;
	$estructuras['IMONED']['decimales'] = 0;
	$estructuras['IMONED']['valor'] = '0'; // 0=guaranies, 1= dolares
	$estructuras['IMONED']['control'] = ''; // 0=guaranies, 1= dolares
	
	$estructuras['IMTOTR']['tipo'] = 'numerico';
	$estructuras['IMTOTR']['largo'] = 15;
	$estructuras['IMTOTR']['decimales'] = 2;
	// $estructuras['IMTOTR']['valor'] = ''; // monto a debitar
	
	$estructuras['IMTOT2']['tipo'] = 'numerico';
	$estructuras['IMTOT2']['largo'] = 15;
	$estructuras['IMTOT2']['decimales'] = 2;
	$estructuras['IMTOT2']['valor'] = '0';
	$estructuras['IMTOT2']['control'] = '';
	
	$estructuras['INRODO']['tipo'] = 'alfa';
	$estructuras['INRODO']['largo'] = 12;
	$estructuras['INRODO']['decimales'] = 0;
	$estructuras['INRODO']['valor'] = ' '; // numero de documento del funcionario en los txt anteriores esta vacio -- traer de organigrama??
	$estructuras['INRODO']['control'] = ''; // numero de documento del funcionario en los txt anteriores esta vacio -- traer de organigrama??
	
	$estructuras['ITIFAC']['tipo'] = 'numerico';
	$estructuras['ITIFAC']['largo'] = 1;
	$estructuras['ITIFAC']['decimales'] = 0;
	$estructuras['ITIFAC']['valor'] = '0';
	$estructuras['ITIFAC']['control'] = '';
	
	$estructuras['INRFAC']['tipo'] = 'alfa';
	$estructuras['INRFAC']['largo'] = 20;
	$estructuras['INRFAC']['decimales'] = 0;
	$estructuras['INRFAC']['valor'] = ' ';
	$estructuras['INRFAC']['control'] = '';
	
	$estructuras['INRCUO']['tipo'] = 'numerico';
	$estructuras['INRCUO']['largo'] = 3;
	$estructuras['INRCUO']['decimales'] = 0;
	$estructuras['INRCUO']['valor'] = '0';
	$estructuras['INRCUO']['control'] = '';
	
	$estructuras['IFCHCR']['tipo'] = 'numerico';
	$estructuras['IFCHCR']['largo'] = 8;
	$estructuras['IFCHCR']['decimales'] = 0;
	$estructuras['IFCHCR']['valor'] = date('Ymd'); 
	$estructuras['IFCHCR']['control'] = date('Ymd'); 
	
	$estructuras['IFCHC2']['tipo'] = 'numerico';
	$estructuras['IFCHC2']['largo'] = 8;
	$estructuras['IFCHC2']['decimales'] = 0;
	$estructuras['IFCHC2']['valor'] = '0';
	$estructuras['IFCHC2']['control'] = '0';
	
	$estructuras['ICEPTO']['tipo'] = 'alfa';
	$estructuras['ICEPTO']['largo'] = 50;
	$estructuras['ICEPTO']['decimales'] = 0;
	$estructuras['ICEPTO']['valor'] = $_POST['concepto_del_debito']; // ver demas conceptos. de otros TXT. ej: viaticos, salarios, vacaciones, etc.
	$estructuras['ICEPTO']['control'] = ''; // ver demas conceptos. de otros TXT. ej: viaticos, salarios, vacaciones, etc.
	
	$estructuras['INRREF']['tipo'] = 'alfa';
	$estructuras['INRREF']['largo'] = 15;
	$estructuras['INRREF']['decimales'] = 0;
	$estructuras['INRREF']['valor'] = $_POST['referencia_del_debito']; // ver que le pongo de referencia operacion empresa
	$estructuras['INRREF']['control'] = ''; // ver que le pongo de referencia operacion empresa
	
	$estructuras['IFECCA']['tipo'] = 'numerico';
	$estructuras['IFECCA']['largo'] = 8;
	$estructuras['IFECCA']['decimales'] = 0;
	$estructuras['IFECCA']['valor'] = date('Ymd');
	$estructuras['IFECCA']['control'] = date('Ymd');
	
	$estructuras['IHORCA']['tipo'] = 'numerico';
	$estructuras['IHORCA']['largo'] = 6;
	$estructuras['IHORCA']['decimales'] = 0;
	$estructuras['IHORCA']['valor'] = date('His');
	$estructuras['IHORCA']['control'] = date('His');
	
	$estructuras['IUSUCA']['tipo'] = 'alfa';
	$estructuras['IUSUCA']['largo'] = 10;
	$estructuras['IUSUCA']['decimales'] = 0;
	$estructuras['IUSUCA']['valor'] = 'ADMINISTRA'; // usuario de carga
	$estructuras['IUSUCA']['control'] = 'ADMINISTRA'; // usuario de carga

	$monto_total = 0;
	foreach ($_SESSION['elementos_a_exportar'] as $cuenta => $monto)
	{
		$linea_texto = "";
		foreach ($estructuras as $campo_nombre => $estructura)
		{
			switch ($estructura['tipo'])
			{
				case 'alfa':
					$caracter_pad = ' ';
					$direccion_pad = STR_PAD_RIGHT;
				break;
				case 'numerico':
					$caracter_pad = '0';
					$direccion_pad = STR_PAD_LEFT;
				break;
			}

			if(!isset($estructura['valor']))
			{
				switch ($campo_nombre)
				{
					case 'ICTCRE':
						//$linea_texto.= str_pad('#######', $estructura['largo'], $caracter_pad, $direccion_pad);
						$linea_texto.= str_pad($_SESSION['datos_extra_para_exportar'][$cuenta]['cuenta_itau'], $estructura['largo'], $caracter_pad, $direccion_pad);
						// $_SESSION['datos_extra_para_exportar'][$cuenta]['cuenta_itau']
					break;

					case 'IORDEN':
						$cuenta_limpia = str_replace('ñ', 'n', $cuenta);
						$linea_texto.= str_pad(strtoupper($cuenta_limpia), $estructura['largo'], $caracter_pad, $direccion_pad);
					break;

					case 'IMTOTR':
						$monto_limpio = number_format($monto);
						$monto_limpio = str_replace(',', '', $monto_limpio);
						$monto_limpio.= '00';

						$linea_texto.= str_pad($monto_limpio, $estructura['largo'], $caracter_pad, $direccion_pad);
					break;

					// case 'INRODO':
					// 	$linea_texto.= str_pad($_SESSION['datos_extra_para_exportar'][$cuenta]['documento_numero'], $estructura['largo'], $caracter_pad, $direccion_pad);
					// break;
				}
			}
			else
			{
				$linea_texto.= str_pad($estructura['valor'], $estructura['largo'], $caracter_pad, $direccion_pad);
			}
		}

		print $linea_texto;
		echo "\n";
		$monto_total += $monto;
	}

	$linea_control = '';
	foreach ($estructuras as $campo_nombre => $estructura)
	{
		switch ($estructura['tipo'])
		{
			case 'alfa':
				$caracter_pad = ' ';
				$direccion_pad = STR_PAD_RIGHT;
			break;
			case 'numerico':
				$caracter_pad = '0';
				$direccion_pad = STR_PAD_LEFT;
			break;
		}

		$valor_a_usar = ($campo_nombre == 'IMTOTR') ? $monto_total.'00' : $estructura['control'];
		$linea_control.= str_pad($valor_a_usar, $estructura['largo'], $caracter_pad, $direccion_pad);
	}
	print $linea_control;
?>
