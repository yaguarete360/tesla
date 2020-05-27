<?php if (!isset($_SESSION)) {session_start();}

echo '<table class="autoancho">';		
	echo '<tr>';
		echo '<td colspan="11">';
			echo '<b>DETALLE de '.$campo.' por '.$medida.' de '.$unidad.'s';
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td>';
			echo '<b>Orden';
		echo '</td>';
		echo '<td>';
			echo '<b>Fecha';
		echo '</td>';
		echo '<td>';
			echo '<b>'.$campo;
		echo '</td>';
		echo '<td>';
			echo '<b>Entra';
		echo '</td>';
		echo '<td>';
			echo '<b>Sale';
		echo '</td>';
		echo '<td>';
			echo '<b>Debe';
		echo '</td>';
		echo '<td>';
			echo '<b>Haber';
		echo '</td>';
		echo '<td>';
			echo '<b>Ent x '.$abreviacion;
		echo '</td>';
		echo '<td>';
			echo '<b>Sal x '.$abreviacion;
		echo '</td>';
		echo '<td>';
			echo '<b>Debe x'.$abreviacion;
		echo '</td>';
		echo '<td>';
			echo '<b>Haber x '.$abreviacion;
		echo '</td>';
	echo '</tr>';
	
	include $url.'pse-red/funciones/conectar-base-de-datos.php';
	
	$suma_1 = 'SELECT *,
	SUM(entra) AS total_entra,
	SUM(sale) AS total_sale,
	SUM(debe) AS total_debe,
	SUM(haber) AS total_haber
	FROM '.$tabla.'
	WHERE '.$fecha.'
	BETWEEN "'.$_SESSION['fecha_desde'].'" 
	AND "'.$_SESSION['fecha_hasta'].'" 
	ORDER BY '.$campo.' 
	ASC';

	$query = $conexion->prepare($suma_1);
	$query->execute();
	
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$total_entra = $rows['total_entra'];
		$total_sale = $rows['total_sale'];
		$total_debe = $rows['total_debe'];
		$total_haber = $rows['total_haber'];
		$_SESSION['total_entra'] = $_SESSION['total_entra'] + $total_entra;
		$_SESSION['total_sale'] = $_SESSION['total_sale'] + $total_sale;
		$_SESSION['total_debe'] = $_SESSION['total_debe'] + $total_debe;
		$_SESSION['total_haber'] = $_SESSION['total_haber'] + $total_haber;
	}
	
	$consulta = 'SELECT * 
	FROM '.$tabla.'
	WHERE '.$fecha.'
	BETWEEN "'.$_SESSION['fecha_desde'].'" 
	AND "'.$_SESSION['fecha_hasta'].'" 
	ORDER BY '.$fecha.' 
	ASC';

	$query = $conexion->prepare($consulta);
	$query->execute();
	
	$ind = 0;
	$cantidad_registros = 0;
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		$cantidad_registros = $cantidad_registros + 1;
		$ind ++;
		echo '<tr>';
			echo '<td>';
				echo $rows['fecha'].'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				echo $ind.'&nbsp&nbsp&nbsp';
			echo '</td>';			
			echo '<td>';
				echo $rows[$campo];
			echo '</td>';
			echo '<td class="derecha">';
				echo number_format($rows['entra'],3,',','.').'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				echo number_format($rows['sale'],3,',','.').'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				echo number_format($rows['debe'],3,',','.').'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				echo number_format($rows['haber'],3,',','.').'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				if($produccion > 0) echo number_format($rows['entra'] / $produccion,3,',','.').'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				if($produccion > 0) echo number_format($rows['sale'] / $produccion,3,',','.').'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				if($produccion > 0) echo number_format($rows['debe'] / $produccion,3,',','.').'&nbsp&nbsp&nbsp';
			echo '</td>';
			echo '<td class="derecha">';
				if($produccion > 0) echo number_format($rows['haber'] / $produccion,3,',','.').'&nbsp&nbsp&nbsp';
			echo '</td>';
		echo '</tr>';
	}
	echo '<tr>';
		echo '<td colspan="10"><b>RESUMEN de '.$campo.':</b></td>';
	echo '</tr>';					
	echo '<tr>';
		$rotulo_1_1 = 'Total entra: ';
		echo '<td colspan="10"><b>'.$rotulo_1_1.'</b></td>';
		echo '<td class="derecha"><b>'.number_format($total_entra,2,',','.').'</b></td>';
	echo '</tr>';	
	echo '<tr>';
		$rotulo_1_2 = 'Total sale: ';
		echo '<td colspan="10"><b>'.$rotulo_1_2.'</b></td>';
		echo '<td class="derecha"><b>'.number_format($total_sale,2,',','.').'</b></td>';
	echo '</tr>';	
	echo '<tr>';
		$rotulo_1_3 = 'Diferencia entre Entra y Sale: ';
		echo '<td colspan="10"><b>'.$rotulo_1_3.'</b></td>';
		echo '<td class="derecha"><b>'.number_format($total_entra - $total_sale,2,',','.').'</b></td>';
	echo '</tr>';	
	echo '<tr>';
		$rotulo_1_4 = 'Total debe: ';
		echo '<td colspan="10"><b>'.$rotulo_1_4.'</b></td>';
		echo '<td class="derecha"><b>'.number_format($total_debe,2,',','.').'</b></td>';
	echo '</tr>';	
	echo '<tr>';
		$rotulo_1_5 = 'Total haber: ';
		echo '<td colspan="10"><b>'.$rotulo_1_5.'</b></td>';
		echo '<td class="derecha"><b>'.number_format($total_haber,2,',','.').'</b></td>';
		$_SESSION['total'.ucfirst($campo)] = $total_haber;
	echo '</tr>';	
	echo '<tr>';
		$rotulo_1_6 = 'Diferencia entre Debe y Haber: ';
		echo '<td colspan="10"><b>'.$rotulo_1_6.'</b></td>';
		echo '<td class="derecha"><b>'.number_format($total_debe - $total_haber,2,',','.').'</b></td>';
	echo '</tr>';	
	echo '<tr>';
		$rotulo_1_7 = 'Haber por '.$medida.': ';
		echo '<td colspan="10"><b>'.$rotulo_1_7.'</b></td>';
		if($produccion > 0) echo '<td class="derecha"><b>'.number_format($total_haber / $produccion,2,',','.').'</b></td>';
	echo '</tr>';	
	echo '<tr>';
		$rotulo_1_8 = 'Haber por '.$unidad.': ';
		echo '<td colspan="10"><b>'.$rotulo_1_8.'</b></td>';
		if($unidades > 0) echo '<td class="derecha"><b>'.number_format($total_haber / $unidades,2,',','.').'</b></td>';
	echo '</tr>';	
	echo '<tr>';
		echo '<td class="fin-de-listado" colspan="3">Fin del listado de '.$cantidad_registros.' registros.</td>';
	echo '</tr>';
echo '</table>';

?>
