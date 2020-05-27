<?php if(!isset($_SESSION)) {session_start();}
	
	//query viejo - filtra por fechas FUNCIONA
	$consulta_seleccion = 'SELECT serie, feretro, medida, entrada_zona_10
	FROM feretros
	WHERE borrado LIKE "no"
	AND entrada_zona_10 NOT LIKE "0000-00-00 00:00:00"
	AND servicio_fecha LIKE "0000-00-00 00:00:00"
	ORDER BY id
	DESC'
	;
	
	//query nuevo - filtra por status en_zona_10 OR otras (exposiciones) PROBAR
	$consulta_seleccion = 'SELECT serie, feretro, medida, entrada_zona_10
	FROM feretros
	WHERE borrado LIKE "no"
	AND (status LIKE "en_zona_10%"
	OR status LIKE "en_zona_11%"
	OR status LIKE "en_zona_12%")
	ORDER BY id
	DESC'
	;
	
	//query a usar mientras no se actualice la tabla operaciones_feretros se necesita filtrar si esta en deposito
	//$consulta_seleccion = 'SELECT serie, feretro, medida, entrada_zona_10
	//FROM operaciones_feretros
	//WHERE borrado LIKE "0000-00-00 00:00:00"
	//ORDER BY id
	//DESC'
	//;
	
	$query_seleccion = $conexion->prepare($consulta_seleccion);
	$query_seleccion->execute();
	while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
	{
		$feretros[strtolower($rows_seleccion['serie'])]['feretro'] = $rows_seleccion['feretro'];
		$feretros[strtolower($rows_seleccion['serie'])]['medida'] = $rows_seleccion['medida'];
		$feretros[strtolower($rows_seleccion['serie'])]['fecha'] = $rows_seleccion['entrada_zona_10'];
	}

	echo '<input type="text" id="'.$campo_nombre.'" name="'.$campo_nombre.'" class="datos" pattern="([A-Za-z]{3}[0-9]{3})|(sin datos)|(no se uso)" value="'.$valor.'" maxlength="6" title="Formato: AAA001"/>';
	
	echo '<br/>';
	echo 'No Se Uso: <input type="checkbox" id="feretro_no_se_uso" value="no se uso">';

?>
<script type="text/javascript">
$(document).ready(function()
{
	var feretros = '<?php echo json_encode($feretros); ?>';
	var feretros = JSON.parse(feretros);

	$("#feretro_no_se_uso").on('click', function()
	{
		if($(this).is(':checked'))
		{
			$("#feretro_serie").val('no se uso');
			$("#feretro_modelo").val('no aplicable');
			$("#feretro_medida").val('no aplicable');
			$("#feretro_fecha").val('no aplicable');
		}
		else
		{
			$("#feretro_serie").val('sin datos');
			$("#feretro_modelo").val('sin datos');
			$("#feretro_medida").val('sin datos');
			$("#feretro_fecha").val('sin datos');
		}
	});

	$("#feretro_serie").change(function()
	{
		esta_serie = this.value.toLowerCase();
		if(esta_serie)
		{
			if(feretros[esta_serie])
			{
				$("#feretro_modelo").val(feretros[esta_serie]['feretro']);
				$("#feretro_medida").val(feretros[esta_serie]['medida']);
				$("#feretro_fecha").val(feretros[esta_serie]['fecha']);
			}
			else
			{
				alert('No se encuentra el feretro '+esta_serie);
			}
		}
		else
		{
			$("#feretro_serie").val('sin datos');
			$("#feretro_modelo").val('sin datos');
			$("#feretro_medida").val('sin datos');
			$("#feretro_fecha").val('sin datos');
		}
	});
});
</script>