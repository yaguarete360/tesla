<?php if (!isset($_SESSION)) {session_start();}				

echo '<table class="autoancho">';
	echo '<th><div id="encabezados">Orden</div></th>';
	echo '<th><div id="encabezados">Entregado el</div></th>';
	echo '<th><div id="encabezados">Serie</div></th>';
	echo '<th><div id="encabezados">Modelo</div></th>';
	echo '<th><div id="encabezados">Medida</div></th>';
	echo '<th><div id="encabezados">Marcado el</div></th>';
	echo '<th><div id="encabezados">Dias</div></th>';
	echo '<th><div id="encabezados">Compuesto</div></th>';
	$date = date('Y-m-d');
	$ind = 0;
	
	if(!isset($unidades)) $unidades = 0; 
	
	$cantidad_registros = 0;
	$produccion = 0;
	$total_dias = 0;
	$sale_metro_gasto = 0;
	$haber_metro_gasto = 0;
	$total_sale_metro_gasto = 0;
	$total_haber_metro_gasto = 0;
	$sale_metro_labor = 0;
	$haber_metro_labor = 0;
	$total_sale_metro_labor = 0;
	$total_haber_metro_labor = 0;
	$sale_metro_material = 0;
	$haber_metro_material = 0;
	$total_sale_metro_material = 0;
	$total_haber_metro_material = 0;
	
	echo '<tr>';
		echo '<td colspan="8">';
			echo '<b>DETALLE de '.$unidad_produccion.'s con '.$campo_fecha_principal.' entre el '.$_SESSION['fecha_desde'].' y el '.$_SESSION['fecha_hasta'];
		echo '</td>';
	echo '</tr>';
		
	$_SESSION['fecha_desde'] = $_POST['fecha_desde'];
	$_SESSION['fecha_hasta'] = $_POST['fecha_hasta'];	
	
	include $url.'pse-red/funciones/conectar-base-de-datos.php';
	
	$consulta = 'SELECT * 	
	FROM '.$tablaPrincipal.'
	WHERE '.$campo_fecha_principal.' 
	BETWEEN "'.$_SESSION['fecha_desde'].'" 
	AND "'.$_SESSION['fecha_hasta'].'" 
	ORDER BY '.$campo_fecha_principal.' 
	ASC';

	$query = $conexion->prepare($consulta);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$cantidad_registros = $cantidad_registros + 1;
		echo '<tr>';
			
			$ind ++;
			
			echo '<td>';
				echo $rows['fecha'].'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				echo $ind.'&nbsp&nbsp&nbsp';
			echo '</td>';			
			echo '<td>';
				echo $rows['serie'].'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td>';
				echo $rows['feretro'].'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				echo $rows['medida'].'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td>';
				echo $rows['fecha'].'&nbsp&nbsp&nbsp';
			echo '</td>';	
			echo '<td class="derecha">';

				$dias = round((strtotime($rows['fecha']) - strtotime($rows['fecha'])) / 86400);
				echo number_format($dias,2,',','.');
				$total_dias = $total_dias + $dias;

			echo '</td>';	
			echo '<td>';
				echo $rows['compuesto'];
			echo '</td>';
		echo '</tr>';		
		
		$unidades = $cantidad_registros;
		$produccion = $produccion + $rows['medida'];
		$_SESSION['unidades'] = $unidades;
		$_SESSION['produccion'] = $produccion;
	
	}
	echo '<tr>';
		echo '<td><b>RESUMEN:</b></td>';
	echo '</tr>';
	echo '<tr>';
		$rotulo_0_1 = 'Unidades totales: ';
		echo '<td colspan="7"><b>'.$rotulo_0_1.'</b></td>';
		echo '<td class="derecha"><b>'.number_format($unidades,0,',','.').'&nbsp&nbsp&nbsp</b></td>';
	echo '</tr>';	
	echo '<tr>';
		$rotulo_0_2 = ucfirst($campo_unidad_produccion).' total: ';
		echo '<td colspan="7"><b>'.$rotulo_0_2.'</b></td>';
		echo '<td class="derecha"><b>'.number_format($produccion,2,',','.').'&nbsp&nbsp&nbsp</b></td>';
	echo '</tr>';	
	echo '<tr>';
		$rotulo_0_3 = 'Promedio de '.$campo_unidad_produccion.' por unidad: ';
		echo '<td colspan="7"><b>'.$rotulo_0_3.'</b></td>';
		if($unidades > 0)
		{
			echo '<td class="derecha"><b>'.number_format($produccion / $unidades,2,',','.').'&nbsp&nbsp&nbsp</b></td>';
		}
		else
		{
			echo '<td class="derecha"><b>'.number_format(0,2,',','.').'&nbsp&nbsp&nbsp</b></td>';
		}
	echo '</tr>';	
	echo '<tr>';
		$unidades = $cantidad_registros;
		echo '<td class="fin-de-listado" colspan="3">Fin del listado de '.$cantidad_registros.' registros.</td>';
	echo '</tr>';
echo '</table">';

?>
