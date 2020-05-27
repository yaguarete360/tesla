<?php if (!isset($_SESSION)) {session_start();}
	
	// |24900001002|6020490002858229|000033800| XX|000000000000000000000000000000|

	$necesita_linea_control = 'no';

	$estructuras['codigo_comercio']['tipo'] = 'alfa';
	$estructuras['codigo_comercio']['largo'] = 11;
	$estructuras['codigo_comercio']['decimales'] = 0;
	// $estructuras['codigo_comercio']['valor'] = '';
	
	$estructuras['numero_tarjeta']['tipo'] = 'numerico';
	$estructuras['numero_tarjeta']['largo'] = 16;
	$estructuras['numero_tarjeta']['decimales'] = 0;
	// $estructuras['numero_tarjeta']['valor'] = '';

	$estructuras['importe']['tipo'] = 'numerico';
	$estructuras['importe']['largo'] = 9;
	$estructuras['importe']['decimales'] = 0;
	// $estructuras['importe']['valor'] = '';

	// ???????????????
	// $estructuras['fecha']['tipo'] = 'numerico';
	// $estructuras['fecha']['largo'] = 8;
	// $estructuras['fecha']['decimales'] = 0;
	// // $estructuras['fecha']['valor'] = '';
	// ???????????????

	$estructuras['relleno_1']['tipo'] = 'numerico';
	$estructuras['relleno_1']['largo'] = 3;
	$estructuras['relleno_1']['decimales'] = 0;
	// $estructuras['relleno_1']['valor'] = ' XX';

	$estructuras['relleno_2']['tipo'] = 'numerico';
	$estructuras['relleno_2']['largo'] = 30;
	$estructuras['relleno_2']['decimales'] = 0;
	// $estructuras['relleno_1']['valor'] = '0';

?>
