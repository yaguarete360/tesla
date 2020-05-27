<?php if (!isset($_SESSION)) {session_start();}

$hoy = date("Y-m-d");

if(isset($_POST['fecha_desde']))
{
	$consulta_total_general = ' SELECT *,
	COUNT(*) AS total_general
	FROM operaciones_feretros
	WHERE borrado LIKE "0000-00-00"
	AND fecha
	BETWEEN "'.$_POST['fecha_desde'].'"
	AND "'.$hoy.'"';

	$query = $conexion->prepare($consulta_total_general);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$total_general = $rows['total_general'];
	}

	$consulta_total_mal_estado = ' SELECT *,
	COUNT(*) AS total_mal_estado
	FROM operaciones_feretros
	WHERE borrado LIKE "0000-00-00"
	AND fecha
	BETWEEN "'.$_POST['fecha_desde'].'"
	AND "'.$hoy.'"	
	AND status = "Dado de baja por mal estado"';

	$query = $conexion->prepare($consulta_total_mal_estado);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$total_mal_estado = $rows['total_mal_estado'];
	}

	$consulta_total_garantia = ' SELECT *,
	COUNT(*) AS total_garantia
	FROM operaciones_feretros
	WHERE borrado LIKE "0000-00-00"
	AND fecha
	BETWEEN "'.$_POST['fecha_desde'].'"
	AND "'.$hoy.'"	
	AND status = "Utilizado para garantia"';

	$query = $conexion->prepare($consulta_total_garantia);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$total_garantia = $rows['total_garantia'];
	}

	$consulta_total_utilizados = ' SELECT *,
	COUNT(*) AS total
	FROM operaciones_feretros
	WHERE borrado LIKE "0000-00-00"
	AND fecha
	BETWEEN "'.$_POST['fecha_desde'].'"
	AND "'.$hoy.'"
	AND status = "Utilizado en un servicio"';

	$query = $conexion->prepare($consulta_total_utilizados);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$total_utilizados= $rows['total'];
	}
	
	$consulta_total_crudos = ' SELECT *,
	COUNT(*) AS total_crudos
	FROM operaciones_feretros
	WHERE borrado LIKE "0000-00-00"
	AND fecha
	BETWEEN "'.$_POST['fecha_desde'].'"
	AND "'.$hoy.'"		
	AND status = "En deposito de crudos"
	OR status = "En proceso de lijado"
	OR status = "En proceso de lustre"
	OR status = "En proceso de blondas"
	OR status = "En proceso de manijas"
	OR status = "En proceso de metalicas"
	OR status = "En proceso de reparacion"
	OR status = "En transito a depositos"
	OR status = "En transito a fabrica"';

	$query = $conexion->prepare($consulta_total_crudos);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$total_crudos = $rows['total_crudos'];
	}

	$consulta_total_terminados = ' SELECT *,
	COUNT(*) AS total_terminados
	FROM operaciones_feretros
	WHERE borrado LIKE "0000-00-00"
	AND fecha
	BETWEEN "'.$_POST['fecha_desde'].'"
	AND "'.$hoy.'"		
	AND status LIKE "En deposito España"
	OR status LIKE "En deposito Mariscal Lopez"
	OR status LIKE "En exposicion%"';

	$query = $conexion->prepare($consulta_total_terminados);
	$query->execute();

	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$total_terminados = $rows['total_terminados'];
	}

	$consulta_modelos_medidas_utilizados = ' SELECT *,
	COUNT(*) AS cantidad
	FROM operaciones_feretros
	WHERE borrado LIKE "0000-00-00"
	AND fecha
	BETWEEN "'.$_POST['fecha_desde'].'"
	AND "'.$hoy.'"
	AND status = "Utilizado en un servicio"
	GROUP BY feretro, medida
	ORDER BY feretro ASC, medida ASC';

	$query = $conexion->prepare($consulta_modelos_medidas_utilizados);
	$query->execute();
	
	$cantidadDeRegistros = 0;
	$ind = 0;
	$cambia_modelo = "";
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$modelos_medidas_utilizados[$ind] = array($rows['feretro'],$rows['medida'],$rows['cantidad']);
		$ind++;
	}
	
	$consulta_modelos_medidas_crudos = ' SELECT *,
	COUNT(*) AS cantidad
	FROM operaciones_feretros
	WHERE borrado LIKE "0000-00-00"
	AND status = "En deposito de crudos"
	OR status = "En proceso de lijado"
	OR status = "En proceso de lustre"
	OR status = "En proceso de blondas"
	OR status = "En proceso de manijas"
	OR status = "En proceso de metalicas"
	OR status = "En proceso de reparacion"
	OR status = "En transito a depositos"
	OR status = "En transito a fabrica"
	GROUP BY feretro, medida
	ORDER BY feretro ASC, medida ASC';

	$query = $conexion->prepare($consulta_modelos_medidas_crudos);
	$query->execute();

	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$modelos_medidas_crudos[$ind] = array($rows['feretro'],$rows['medida'],$rows['cantidad']);
		$ind++;
	}

	$consulta_modelos_medidas_terminados = ' SELECT *,
	COUNT(*) AS cantidad
	FROM operaciones_feretros
	WHERE borrado LIKE "0000-00-00"
	AND status LIKE "En deposito España"
	OR status LIKE "En deposito Mariscal Lopez"
	OR status LIKE "En exposicion%"
	GROUP BY feretro, medida
	ORDER BY feretro ASC, medida ASC';

	$query = $conexion->prepare($consulta_modelos_medidas_terminados);
	$query->execute();

	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$modelos_medidas_terminados[$ind] = array($rows['feretro'],$rows['medida'],$rows['cantidad']);
		$ind++;
	}
}

?>
