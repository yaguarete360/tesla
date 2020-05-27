<?php if (!isset($_SESSION)) {session_start();}

	// >|8062523|0036052019|5896571031518422|G|090519|000066300|0000007618|PSM000761800000000000000000000|<

	$necesita_linea_control = 'no';

	$estructuras['codigo_comercio']['tipo'] = 'alfa';
	$estructuras['codigo_comercio']['largo'] = 7;
	$estructuras['codigo_comercio']['decimales'] = 0;
	// $estructuras['codigo_comercio']['valor'] = '8062523';
	
	$estructuras['numero_resumen']['tipo'] = 'numerico';
	$estructuras['numero_resumen']['largo'] = 10;
	$estructuras['numero_resumen']['decimales'] = 0;
	// $estructuras['numero_resumen']['valor'] = '';

	$estructuras['numero_tarjeta']['tipo'] = 'numerico';
	$estructuras['numero_tarjeta']['largo'] = 16;
	$estructuras['numero_tarjeta']['decimales'] = 0;
	// $estructuras['numero_tarjeta']['valor'] = '';
	
	$estructuras['moneda']['tipo'] = 'numerico';
	$estructuras['moneda']['largo'] = 1;
	$estructuras['moneda']['decimales'] = 0;
	// $estructuras['moneda']['valor'] = 'G';
	
	$estructuras['fecha_transaccion']['tipo'] = 'numerico';
	$estructuras['fecha_transaccion']['largo'] = 6;
	$estructuras['fecha_transaccion']['decimales'] = 0;
	// $estructuras['fecha_transaccion']['valor'] = '';
	
	$estructuras['importe']['tipo'] = 'numerico';
	$estructuras['importe']['largo'] = 9;
	$estructuras['importe']['decimales'] = 0;
	// $estructuras['importe']['valor'] = '';
	
	$estructuras['cupon']['tipo'] = 'numerico';
	$estructuras['cupon']['largo'] = 10;
	$estructuras['cupon']['decimales'] = 0;
	// $estructuras['cupon']['valor'] = '';

	$estructuras['opcional']['tipo'] = 'alfa';
	$estructuras['opcional']['largo'] = 30;
	$estructuras['opcional']['decimales'] = 0;
	// $estructuras['opcional']['valor'] = '';

?>
