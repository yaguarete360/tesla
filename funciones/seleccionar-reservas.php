<?php if (!isset($_SESSION)) {session_start();}

echo '<form class="rangos" method="post" action="">';
	
	$consulta_reservas = 'SELECT 
		* 
		FROM '.$tabla_a_procesar.' 
		WHERE borrado = "0000-00-00" 
		GROUP BY reserva
		ORDER BY reserva
		ASC'
	;

	$query = $conexion->prepare($consulta_reservas);
	$query->execute();
	
	echo '<select name="filtro_desde">';
		
		if(isset($_SESSION['filtro_desde']))
		{
			echo '<option value="'.$_SESSION['filtro_desde'].'" selected>'.$_SESSION['filtro_desde'].'</option>';
		}
		
		while($rows_reservas = $query->fetch(PDO::FETCH_ASSOC))
		{
			echo '<option value="'.$rows_reservas['reserva'].'">'.$rows_reservas['reserva'].'</option>';
		}
	
	echo '</select>';
	
	$query = $conexion->prepare($consulta_reservas);
	$query->execute();
	
	echo '<select name="filtro_hasta">';
		
		if(isset($_SESSION['filtro_hasta']))
		{
			echo '<option value="'.$_SESSION['filtro_hasta'].'" selected>'.$_SESSION['filtro_hasta'].'</option>';
		}
		while($rows_reservas = $query->fetch(PDO::FETCH_ASSOC))
		{
			echo '<option value="'.$rows_reservas['reserva'].'">'.$rows_reservas['reserva'].'</option>';
		}

	echo '</select>';

	
	$url = "../../";
	include "../../funciones/seleccionar-fechas-rango.php";

	echo "  Incluir cabeceras: ";
	echo '<input type="checkbox" name="incluir_cabeceras" checked="checked">';
	echo "  ";
	
	echo '<input type="submit" name="consultar" value="Consultar"/>';
echo '</form>';

?>
