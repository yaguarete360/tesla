<?php if (!isset($_SESSION)) {session_start();}				

include $url.'pse-red/funciones/conectar-base-de-datos.php';

$consulta_totales = ' SELECT *,
COUNT(*) AS total
FROM '.$tabla.'
WHERE borrado LIKE "0000-00-00"
AND fecha
BETWEEN "'.$_POST['fecha_desde'].'"
AND "'.$hoy.'"';

$query = $conexion->prepare($consulta_totales);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{		
	$total = $rows['total'];
}
echo $total;

if(!isset($campo_secundario))
{	
	$consulta_parciales = ' SELECT *,
	COUNT(*) AS parcial
	FROM '.$tabla.'
	WHERE fecha
	BETWEEN "'.$_POST['fecha_desde'].'"
	AND "'.$hoy.'"
	GROUP BY '.$campo.' 
	ORDER BY '.$campo.' ASC';

}
else
{	
	$consulta_parciales = ' SELECT *,
	COUNT(*) AS parcial
	FROM '.$tabla.'
	WHERE fecha
	BETWEEN "'.$_POST['fecha_desde'].'"
	AND "'.$hoy.'"
	GROUP BY '.$campo.', '.$campo_secundario.'
	ORDER BY '.$campo.' ASC, '.$campo_secundario.' ASC';

}	

$query = $conexion->prepare($consulta_parciales);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{		
	echo '<tr>';
		echo '<td>';
			echo $rows[$campo];
		echo '</td>';
		if(isset($campo_secundario))
		{		
			echo '<td>';
				echo $rows[$campo_secundario];
			echo '</td>';
		}
		echo '<td class="derecha">';
			echo number_format($rows['parcial'],0,",",".");
		echo '</td>';
		echo '<td class="derecha">';
			echo number_format(($rows['parcial'] / $total) * 100,2,",",".");
		echo '</td>';
	echo '</tr>';
}

?>
