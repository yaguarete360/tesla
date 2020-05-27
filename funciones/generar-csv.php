<?php if (!isset($_SESSION)) {session_start();}
	
	$contenido_del_csv = '';
	foreach ($_SESSION['elementos_a_exportar'] as $linea_numero => $linea_datos)
	{
		$contenido_del_csv.= rtrim($linea_datos, ',')."\n";
	}

	
?>
