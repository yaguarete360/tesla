<?php if (!isset($_SESSION)) {session_start();}

echo '<table>';
	$tabla = "operaciones_feretros";	
	$campo = "status";
	echo '<tr><td colspan="3"><h4>Historico de feretros por status.</h4></td></tr>';
	
	$consulta_casos = ' SELECT *
	FROM '.$tabla.'
	GROUP BY '.$campo.'
	ORDER BY '.$campo.' ASC';

	$query = $conexion->prepare($consulta_casos);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{					
		$caso = $rows[$campo];
		include $url.'pse-red/funciones/calcular-casos.php';
	}

	$campo = "feretro";			
	echo '<tr><td colspan="3"><h4>Historico de feretros por modelo.</h4></td></tr>';
	$consulta_casos = ' SELECT *
	FROM '.$tabla.'
	GROUP BY '.$campo.'
	ORDER BY '.$campo.' ASC';

	$query = $conexion->prepare($consulta_casos);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{					
		$caso = $rows[$campo];
	
		include $url.'pse-red/funciones/calcular-casos.php';
	
	}
echo '</table>';

?>
