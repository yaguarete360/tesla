<?php if (!isset($_SESSION)) {session_start();}

	$necesita_linea_control = 'no';
	
	$estructuras['numero_patronal_1']['tipo'] = 'numerico';
	$estructuras['numero_patronal_1']['largo'] = 4;
	$estructuras['numero_patronal_1']['decimales'] = 0;
	$estructuras['numero_patronal_1']['valor'] = '4';

	$estructuras['numero_patronal_2']['tipo'] = 'numerico';
	$estructuras['numero_patronal_2']['largo'] = 2;
	$estructuras['numero_patronal_2']['decimales'] = 0;
	$estructuras['numero_patronal_2']['valor'] = '84';

	$estructuras['numero_patronal_3']['tipo'] = 'numerico';
	$estructuras['numero_patronal_3']['largo'] = 4;
	$estructuras['numero_patronal_3']['decimales'] = 0;
	$estructuras['numero_patronal_3']['valor'] = '477';

	$estructuras['numero_asegurado_ips']['tipo'] = 'alfa';
	$estructuras['numero_asegurado_ips']['largo'] = 10;
	$estructuras['numero_asegurado_ips']['decimales'] = 0;
	// $estructuras['numero_asegurado_ips']['valor'] = '';

	$estructuras['cedula_identidad']['tipo'] = 'alfa';
	$estructuras['cedula_identidad']['largo'] = 10;
	$estructuras['cedula_identidad']['decimales'] = 0;
	// $estructuras['cedula_identidad']['valor'] = '';

	$estructuras['apellidos']['tipo'] = 'alfa';
	$estructuras['apellidos']['largo'] = 30;
	$estructuras['apellidos']['decimales'] = 0;
	// $estructuras['apellidos']['valor'] = '';

	$estructuras['nombres']['tipo'] = 'alfa';
	$estructuras['nombres']['largo'] = 30;
	$estructuras['nombres']['decimales'] = 0;
	// $estructuras['nombres']['valor'] = '';

	$estructuras['categoria']['tipo'] = 'alfa';
	$estructuras['categoria']['largo'] = 1;
	$estructuras['categoria']['decimales'] = 0;
	$estructuras['categoria']['valor'] = 'E';

	$estructuras['dias_trabajados']['tipo'] = 'alfa';
	$estructuras['dias_trabajados']['largo'] = 2;
	$estructuras['dias_trabajados']['decimales'] = 0;
	// $estructuras['dias_trabajados']['valor'] = '';

	$estructuras['salario_imponible']['tipo'] = 'alfa';
	$estructuras['salario_imponible']['largo'] = 10;
	$estructuras['salario_imponible']['decimales'] = 0;
	// $estructuras['salario_imponible']['valor'] = '';

	$estructuras['periodo_de_pago']['tipo'] = 'alfa';
	$estructuras['periodo_de_pago']['largo'] = 6;
	$estructuras['periodo_de_pago']['decimales'] = 0;
	// $estructuras['periodo_de_pago']['valor'] = '';

	$estructuras['codigo_actividad']['tipo'] = 'alfa';
	$estructuras['codigo_actividad']['largo'] = 2;
	$estructuras['codigo_actividad']['decimales'] = 0;
	$estructuras['codigo_actividad']['valor'] = '0';

	$estructuras['salario_real']['tipo'] = 'alfa';
	$estructuras['salario_real']['largo'] = 10;
	$estructuras['salario_real']['decimales'] = 0;
	// $estructuras['salario_real']['valor'] = '';

?>
