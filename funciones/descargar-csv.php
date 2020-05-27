<?php if (!isset($_SESSION)) {session_start();}
	
	$archivo_csv_nombre = $_POST['nombre_del_csv'];

	header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=$archivo_csv_nombre.csv");
    # Disable caching - HTTP 1.1
    header("Cache-Control: no-cache, no-store, must-revalidate");
    # Disable caching - HTTP 1.0
    header("Pragma: no-cache");
    # Disable caching - Proxies
    header("Expires: 0");

	$csv = '';
    foreach ($_SESSION['elementos_a_exportar'] as $fila)
    {
    	foreach ($fila as $campo_valor)
    	{
			$campo_valor = trim(preg_replace('/\s+/', ' ', $campo_valor));
			$csv.= str_replace(',', '', $campo_valor).', ';
    	}
    	$csv = rtrim($csv, ', ')."\n";
    }
    echo $csv;

?>
