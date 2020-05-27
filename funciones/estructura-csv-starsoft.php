<?php if (!isset($_SESSION)) {session_start();}

	$necesita_linea_control = 'no';

	if(isset($tipo_de_listado))
	{
		switch ($tipo_de_listado)
		{
			case 'compras':
				$campos_a_generar['com_numero'] = 'asistido'; // numero de factura
				$campos_a_generar['generar'] = '0';
				$campos_a_generar['cta_iva'] = '';
				$campos_a_generar['cta_caja'] = '';
				$campos_a_generar['form_pag'] = 'asistido'; // contado/credito
				$campos_a_generar['com_imputa'] = 'asistido'; // ????
				$campos_a_generar['com_sucurs'] = '1';
				$campos_a_generar['com_centro'] = '';
				$campos_a_generar['com_provee'] = 'asistido'; // ruc
				$campos_a_generar['com_cuenta'] = 'asistido'; // ????
				$campos_a_generar['com_prvnom'] = 'asistido'; // cuenta
				$campos_a_generar['com_tipofa'] = 'Factura';
				$campos_a_generar['com_fecha'] = 'asistido'; // fecha de factura?
				$campos_a_generar['com_totfac'] = 'asistido'; // monto de la factura
				$campos_a_generar['com_exenta'] = 'asistido'; // monto exento
				$campos_a_generar['com_gravad'] = 'asistido'; // monto gravado neto
				$campos_a_generar['com_iva'] = 'asistido'; // monto iva
				$campos_a_generar['com_import'] = '0';
				$campos_a_generar['com_aux'] = '0';
				$campos_a_generar['com_con'] = '0';
				$campos_a_generar['com_cuota'] = '0';
				$campos_a_generar['com_fecven'] = 'asistido'; // fecha de factura?
				$campos_a_generar['com_ordenp'] = '0';
				$campos_a_generar['cant_dias'] = '0';
				$campos_a_generar['concepto'] = '';
				$campos_a_generar['moneda'] = '';
				$campos_a_generar['cambio'] = '';
				$campos_a_generar['valor'] = '';
				$campos_a_generar['origen'] = 'LC';
				$campos_a_generar['exen_dolar'] = '0';
				$campos_a_generar['com_disexe'] = '0';
				$campos_a_generar['com_disgra'] = '0';
				$campos_a_generar['forma_devo'] = '0';
				$campos_a_generar['retencion'] = '0';
				$campos_a_generar['porcentaje'] = '0';
				$campos_a_generar['reproceso'] = '';
				$campos_a_generar['cuenta_exe'] = '';
				$campos_a_generar['com_tipimp'] = '';
				$campos_a_generar['com_gra05'] = 'asistido'; // monto gravado 5% neto
				$campos_a_generar['com_iva05'] = 'asistido'; // iva 5%
				$campos_a_generar['com_disg05'] = '0'; 
				$campos_a_generar['cta_iva05'] = '0';
				$campos_a_generar['com_rubgra'] = '5';
				$campos_a_generar['com_rubg05'] = '0';
				$campos_a_generar['com_ctag05'] = '0';
				$campos_a_generar['com_rubexe'] = '0';
				$campos_a_generar['com_saldo'] = '0';
			break;
			
			case 'ventas':
				$campos_a_generar['ven_tipimp'] = 'I';
				$campos_a_generar['ven_gra05'] = '0';
				$campos_a_generar['ven_iva05'] = '0';
				$campos_a_generar['ven_disg05'] = '0';
				$campos_a_generar['cta_iva05'] = '0';
				$campos_a_generar['ven_rubgra'] = '0';
				$campos_a_generar['ven_rubg05'] = '0';
				$campos_a_generar['ven_disexe'] = '0';
				$campos_a_generar['ven_numero'] = 'asistido'; // factura_numero
				$campos_a_generar['ven_imputa'] = '0';
				$campos_a_generar['ven_sucurs'] = 'asistido'; // primer prefijo de factura_numero
				$campos_a_generar['generar'] = '0';
				$campos_a_generar['form_pag'] = 'CONTADO';
				$campos_a_generar['ven_centro'] = ''; // vacio
				$campos_a_generar['ven_provee'] = 'asistido'; // ruc
				$campos_a_generar['ven_cuenta'] = ''; // vacio
				$campos_a_generar['ven_prvnom'] = 'asistido'; // cuenta
				$campos_a_generar['ven_tipofa'] = 'Factura';
				$campos_a_generar['ven_fecha'] = 'asistido'; // fecha de la factura dd/mm/YYYY
				$campos_a_generar['ven_totfac'] = 'asistido'; // monto
				$campos_a_generar['ven_exenta'] = '0';
				$campos_a_generar['ven_gravad'] = 'asistido'; // monto gravado
				$campos_a_generar['ven_iva'] = 'asistido'; // iva_monto
				$campos_a_generar['ven_retenc'] = '0';
				$campos_a_generar['ven_aux'] = ''; // vacio
				$campos_a_generar['ven_ctrl'] = ''; // vacio
				$campos_a_generar['ven_con'] = '0';
				$campos_a_generar['ven_cuota'] = '0';
				$campos_a_generar['ven_fecven'] = 'asistido'; // fecha_vencimiento (fecha nomas) de la factura dd/mm/YYYY
				$campos_a_generar['cant_dias'] = '0';
				$campos_a_generar['origen'] = 'LI';
				$campos_a_generar['cambio'] = '1';
				$campos_a_generar['valor'] = '0';
				$campos_a_generar['moneda'] = '0';
				$campos_a_generar['exen_dolar'] = '0';
				$campos_a_generar['concepto'] = ''; // vacio
				$campos_a_generar['cta_iva'] = ''; // vacio
				$campos_a_generar['cta_caja'] = ''; // vacio
				$campos_a_generar['tkdesde'] = '0';
				$campos_a_generar['tkhasta'] = '0';
				$campos_a_generar['caja'] = '0';
				$campos_a_generar['ven_disgra'] = '0';
				$campos_a_generar['forma_devo'] = '0';
				$campos_a_generar['ven_cuense'] = ''; // vacio
				$campos_a_generar['anular'] = 'V';
				$campos_a_generar['reproceso'] = ''; // vacio
				$campos_a_generar['cuenta_exe'] = '0';
				$campos_a_generar['usu_ide'] = 'ORACLE';

			break;
		}
	}

?>
