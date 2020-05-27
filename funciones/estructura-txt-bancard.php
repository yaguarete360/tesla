<?php if (!isset($_SESSION)) {session_start();}

	// |1CMS0001814    |        39000000|      |201905176324 |P.SERENIDAD 01/01                       |20190517| 05281|

	$necesita_linea_control = 'no';

	$estructuras['NroCon']['tipo'] = 'alfa';
	$estructuras['NroCon']['largo'] = 15;
	$estructuras['NroCon']['decimales'] = 0;
	// $estructuras['NroCon']['valor'] = '';
	
	$estructuras['importe']['tipo'] = 'numerico';
	$estructuras['importe']['largo'] = 16;
	$estructuras['importe']['decimales'] = 2;
	// $estructuras['importe']['valor'] = '';

	$estructuras['CodAut']['tipo'] = 'alfa';
	$estructuras['CodAut']['largo'] = 6;
	$estructuras['CodAut']['decimales'] = 0;
	// $estructuras['CodAut']['valor'] = '';
	
	$estructuras['NroFactura']['tipo'] = 'alfa';
	$estructuras['NroFactura']['largo'] = 13;
	$estructuras['NroFactura']['decimales'] = 0;
	// $estructuras['NroFactura']['valor'] = '';
	
	$estructuras['DesTransac']['tipo'] = 'alfa';
	$estructuras['DesTransac']['largo'] = 40;
	$estructuras['DesTransac']['decimales'] = 0;
	// $estructuras['DesTransac']['valor'] = '';
	
	$estructuras['FecTran']['tipo'] = 'numerico';
	$estructuras['FecTran']['largo'] = 8;
	$estructuras['FecTran']['decimales'] = 0;
	// $estructuras['FecTran']['valor'] = '';
	
	$estructuras['NroLote']['tipo'] = 'numerico';
	$estructuras['NroLote']['largo'] = 6;
	$estructuras['NroLote']['decimales'] = 0;
	// $estructuras['NroLote']['valor'] = '';

?>
