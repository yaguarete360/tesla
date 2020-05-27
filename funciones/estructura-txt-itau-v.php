<?php if (!isset($_SESSION)) {session_start();}

	$necesita_linea_control = 'si';

	$estructuras['ITIREG']['tipo'] = 'alfa';
	$estructuras['ITIREG']['largo'] = 1;
	$estructuras['ITIREG']['decimales'] = 0;
	$estructuras['ITIREG']['valor'] = 'D'; // D=registro de detalle, C=registro de control
	$estructuras['ITIREG']['control'] = 'C'; // D=registro de detalle, C=registro de control
	
	$estructuras['ITITRA']['tipo'] = 'alfa';
	$estructuras['ITITRA']['largo'] = 2;
	$estructuras['ITITRA']['decimales'] = 0;
// $estructuras['ITITRA']['valor'] = '01'; // 01= pago de salarios, 02=pago proveedores, 03=cobro de factura cuotas, 09=debitos comandados
$estructuras['ITITRA']['valor'] = $valores_por_campo['ITITRA']; // 01= pago de salarios, 02=pago proveedores, 03=cobro de factura cuotas, 09=debitos comandados
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
	// $estructuras['ICTCRE']['valor'] = '';// cuenta itau del funcionario/proveedor
	$estructuras['ICTCRE']['control'] = '';// traer de organigrama // cuenta itau del funcionario
	
	$estructuras['ITCRDB']['tipo'] = 'alfa';
	$estructuras['ITCRDB']['largo'] = 1;
	$estructuras['ITCRDB']['decimales'] = 0;
// $estructuras['ITCRDB']['valor'] = 'C'; // D=debito, C=credito, H=cheque, F=Cobro de factura/cuota
$estructuras['ITCRDB']['valor'] = $valores_por_campo['ITCRDB']; // D=debito, C=credito, H=cheque, F=Cobro de factura/cuota
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
// $estructuras['ITIFAC']['valor'] = '0';
$estructuras['ITIFAC']['valor'] = $valores_por_campo['ITIFAC'];
	$estructuras['ITIFAC']['control'] = '';
	
	$estructuras['INRFAC']['tipo'] = 'alfa';
	$estructuras['INRFAC']['largo'] = 20;
	$estructuras['INRFAC']['decimales'] = 0;
// $estructuras['INRFAC']['valor'] = ' ';
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
	
	// ------------------------------ VERSION LARGA (258) ------------------------------ 

	// $estructuras['INRFA2']['tipo'] = 'alfa';
	// $estructuras['INRFA2']['largo'] = 100;
	// $estructuras['INRFA2']['decimales'] = 0;
	// $estructuras['INRFA2']['valor'] = '';
	// // $estructuras['INRFA2']['control'] = '';

	// $estructuras['INRDR1']['tipo'] = 'alfa';
	// $estructuras['INRDR1']['largo'] = 15;
	// $estructuras['INRDR1']['decimales'] = 0;
	// $estructuras['INRDR1']['valor'] = '';
	// // $estructuras['INRDR1']['control'] = '';

	// $estructuras['INOMR1']['tipo'] = 'alfa';
	// $estructuras['INOMR1']['largo'] = 50;
	// $estructuras['INOMR1']['decimales'] = 0;
	// $estructuras['INOMR1']['valor'] = '';
	// // $estructuras['INOMR1']['control'] = '';

	// $estructuras['INRDR2']['tipo'] = 'alfa';
	// $estructuras['INRDR2']['largo'] = 15;
	// $estructuras['INRDR2']['decimales'] = 0;
	// $estructuras['INRDR2']['valor'] = '';
	// // $estructuras['INRDR2']['control'] = '';

	// $estructuras['INOMR2']['tipo'] = 'alfa';
	// $estructuras['INOMR2']['largo'] = 50;
	// $estructuras['INOMR2']['decimales'] = 0;
	// $estructuras['INOMR2']['valor'] = '';
	// // $estructuras['INOMR2']['control'] = '';

	// $estructuras['ICOMEN']['tipo'] = 'alfa';
	// $estructuras['ICOMEN']['largo'] = 100;
	// $estructuras['ICOMEN']['decimales'] = 0;
	// $estructuras['ICOMEN']['valor'] = '';
	// // $estructuras['ICOMEN']['control'] = '';

?>
