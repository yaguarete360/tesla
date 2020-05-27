<?php if (!isset($_SESSION)) {session_start();}

	include "../funciones/conectar-base-de-datos.php";

	header("Content-Disposition: attachment; filename=".$_POST['nombre_del_archivo'].".txt");
	header("Content-type: text/plain");
	header("Content-Type: application/force-download");

	foreach ($_SESSION['elementos_a_exportar'] as $linea_numero => $linea_texto)
	{
		print $linea_texto;
		if($linea_texto != end($_SESSION['elementos_a_exportar'])) echo "\n";
	}

?>
