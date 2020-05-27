<?php if(!isset($_SESSION)) {session_start();}

	$tabla_seleccion = "operaciones_feretros";
	$campo_seleccion = $feretros1[1];

	if($campo_seleccion === "fecha") $campo_seleccion = "entrada_deposito";
	
	$x1 = 0;
	if($campo_atributo['asistente'] === "feretro-serie") $feretros_serie[$x1] = "";
	if($campo_atributo['asistente'] === "feretro-feretro") $feretros_modelos[$x1] = "";
	if($campo_atributo['asistente'] === "feretro-medida") $feretros_medidas[$x1] = "";
	if($campo_atributo['asistente'] === "feretro-fecha") $feretros_fechas[$x1] = "";
	$x1++;
	if($campo_atributo['asistente'] === "feretro-serie") $feretros_serie[$x1] = "no se uso feretro";
	if($campo_atributo['asistente'] === "feretro-feretro") $feretros_modelos[$x1] = "no aplicable";
	if($campo_atributo['asistente'] === "feretro-medida") $feretros_medidas[$x1] = "no aplicable";
	if($campo_atributo['asistente'] === "feretro-fecha") $feretros_fechas[$x1] = "";
	$x1++;
	
	$consulta_seleccion = 'SELECT serie,'.$campo_seleccion.'
	FROM '.$tabla_seleccion.' 
	WHERE borrado = "0000-00-00 00:00:00"
	AND entrada_deposito NOT LIKE "0000-00-00 00:00:00"
	AND servicio_fecha LIKE "0000-00-00 00:00:00"
	AND (servicio_codigo LIKE "no aplicable"
		OR
		servicio_codigo LIKE "sin datos")
	ORDER BY id
	DESC'
	;
	$query_seleccion = $conexion->prepare($consulta_seleccion);
	$query_seleccion->execute();
	while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
	{
		if($campo_atributo['asistente'] === "feretro-serie") $feretros_serie[$x1] = $rows_seleccion[$campo_seleccion];
		if($campo_atributo['asistente'] === "feretro-feretro") $feretros_modelos[$x1] = $rows_seleccion[$campo_seleccion];
		if($campo_atributo['asistente'] === "feretro-medida") $feretros_medidas[$x1] = $rows_seleccion[$campo_seleccion];
		if($campo_atributo['asistente'] === "feretro-fecha") $feretros_fechas[$x1] = $rows_seleccion[$campo_seleccion];
		$x1++;
	}

	if($campo_atributo['asistente'] === "feretro-serie")
	{
		echo '<select name="'.$campo_nombre.'" id="'.$campo_nombre.'" value="'.$valor.'"/>';
			foreach ($feretros_serie as $objetivo)
			{
				if(isset($rows[$campo_nombre]) and $rows[$campo_nombre] == $objetivo)
				{
					echo '<option value="'.$objetivo.'" selected>'.$objetivo.'</option>';
				}
				else
				{
					echo '<option value="'.$objetivo.'">'.$objetivo.'</option>';
				}
			}
		echo '</select>';
	}
	else
	{
		echo '<input type="text" id="'.$campo_nombre.'" name="'.$campo_nombre.'" class="datos" value="'.$valor.'" readonly/>';
	}

?>