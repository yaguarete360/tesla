<?php if (!isset($_SESSION)) {session_start();}
	
	// |3300290|0033052019|4259361408002760|G|090519|000250500|0000005615|PSM|0005615|00000000000000000000|

	$necesita_linea_control = 'no';

	$estructuras['codigo_comercio']['tipo'] = 'alfa';
	$estructuras['codigo_comercio']['largo'] = 7;
	$estructuras['codigo_comercio']['decimales'] = 0;
	// $estructuras['codigo_comercio']['valor'] = '';
	
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
	// $estructuras['moneda']['valor'] = '';

	$estructuras['transaccion_fecha']['tipo'] = 'numerico';
	$estructuras['transaccion_fecha']['largo'] = 6;
	$estructuras['transaccion_fecha']['decimales'] = 0;
	// $estructuras['transaccion_fecha']['valor'] = '';

	$estructuras['importe']['tipo'] = 'numerico';
	$estructuras['importe']['largo'] = 9;
	$estructuras['importe']['decimales'] = 0;
	// $estructuras['importe']['valor'] = '';

	$estructuras['numero_cupon']['tipo'] = 'numerico';
	$estructuras['numero_cupon']['largo'] = 10;
	$estructuras['numero_cupon']['decimales'] = 0;
	// $estructuras['numero_cupon']['valor'] = '';

	$estructuras['documento_tipo']['tipo'] = 'numerico';
	$estructuras['documento_tipo']['largo'] = 3;
	$estructuras['documento_tipo']['decimales'] = 0;
	// $estructuras['descripcion']['valor'] = '';

	$estructuras['documento_numero']['tipo'] = 'numerico';
	$estructuras['documento_numero']['largo'] = 7;
	$estructuras['documento_numero']['decimales'] = 0;
	// $estructuras['descripcion']['valor'] = '';

	$estructuras['relleno']['tipo'] = 'numerico';
	$estructuras['relleno']['largo'] = 20;
	$estructuras['relleno']['decimales'] = 0;
	// $estructuras['descripcion']['valor'] = '';

?>
