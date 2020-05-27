<?php if (!isset($_SESSION)) {session_start();}

// |000369444|000432038|562601   |AQUINO OCAMPOS, MARIO RUBEN   |0000012520000|00170519|820025227
// |000000000|000432038|333      |marc hellmers davalos         |0000055555500|00180719|000777777
// |000000000|000432038|265387   |ACOSTA CAZAL, DERLIZ          |0000039000000|00180719|720002227

	$necesita_linea_control = 'no';

	$estructuras['codigo_parque']['tipo'] = 'numerico';
	$estructuras['codigo_parque']['largo'] = 9;
	$estructuras['codigo_parque']['decimales'] = 0;
	// $estructuras['codigo_parque']['valor'] = '';
	// $estructuras['codigo_parque']['control'] = '';

	$estructuras['cuenta_parque']['tipo'] = 'numerico';
	$estructuras['cuenta_parque']['largo'] = 9;
	$estructuras['cuenta_parque']['decimales'] = 0;
	// $estructuras['cuenta_parque']['valor'] = '';
	// $estructuras['cuenta_parque']['control'] = '';

	$estructuras['numero_de_cedula']['tipo'] = 'alfa';
	$estructuras['numero_de_cedula']['largo'] = 9;
	$estructuras['numero_de_cedula']['decimales'] = 0;
	// $estructuras['numero_de_cedula']['valor'] = '';
	// $estructuras['numero_de_cedula']['control'] = '';

	$estructuras['cuenta_titular']['tipo'] = 'alfa';
	$estructuras['cuenta_titular']['largo'] = 30;
	$estructuras['cuenta_titular']['decimales'] = 0;
	// $estructuras['cuenta_titular']['valor'] = '';
	// $estructuras['cuenta_titular']['control'] = '';

	$estructuras['monto']['tipo'] = 'numerico';
	$estructuras['monto']['largo'] = 13;
	$estructuras['monto']['decimales'] = 2;
	// $estructuras['monto']['valor'] = '';
	// $estructuras['monto']['control'] = '';

	$estructuras['fecha']['tipo'] = 'numerico';
	$estructuras['fecha']['largo'] = 8;
	$estructuras['fecha']['decimales'] = 0;
	// $estructuras['fecha']['valor'] = '';
	// $estructuras['fecha']['control'] = '';

	$estructuras['cuenta_bancaria']['tipo'] = 'numerico';
	$estructuras['cuenta_bancaria']['largo'] = 9;
	$estructuras['cuenta_bancaria']['decimales'] = 0;
	// $estructuras['cuenta_bancaria']['valor'] = '';
	// $estructuras['cuenta_bancaria']['control'] = '';

	
	

?>
