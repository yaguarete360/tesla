<?php if (!isset($_SESSION)) {session_start();}	

$_SESSION['desde_elregistro'] = !isset($_GET['desde_el_registro']) ? 1 : $_GET['desde_el_registro'];
$_SESSION['hasta_elregistro'] = !isset($_GET['hasta_el_registro']) ? 10 : $_GET['hasta_el_registro'];
$_SESSION['declaracion_de_la_tabla'] = !isset($_GET['declaracion_de_la_tabla']) ? "" : $_GET['declaracion_de_la_tabla'];

$archivos = scandir('../migradores/tablas/');

echo '<form>';
	echo '<table>';
		echo '<tr>';
			echo '<td>Declaracion de la tabla: </td>';		
			echo '<td colspan="2">';		
				echo '<select name="declaracion_de_la_tabla" style="font-size: 20px; background: #D0EFE0"/>';
					if(isset($_SESSION['declaracion_de_la_tabla']))
					{
						echo '<option value="'.$_SESSION['declaracion_de_la_tabla'].'">'.$_SESSION['declaracion_de_la_tabla'].'</option>';
					}
					foreach ($archivos as $archivo_vuelta => $archivo_nombre)
					{
						if($archivo_nombre != "." and 
					    $archivo_nombre != ".." and 
					    $archivo_nombre != ".DS_Store" and
					    $archivo_nombre != "error_log" and					    
					    !strpos($archivo_nombre, 'pdf'))
					    {	    	
							echo '<option value="'.$archivo_nombre.'">'.$archivo_nombre.'</option>';
					    }
					}
				echo '</select>';
			echo '</td>';		
		echo '</tr>';
		echo '<tr>';
			echo '<td>Desde el id: </td><td><input type="text" name="desde_el_registro" value="'.$_SESSION['desde_elregistro'].'" style="font-size: 18px; text-align: right; background: #D0EFE0; width: 80px;"/></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Hasta el id: </td><td><input type="text" name="hasta_el_registro" value="'.$_SESSION['hasta_elregistro'].'" style="font-size: 18px; text-align: right; background: #D0EFE0; width: 80px"/></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td></td><td></td><td><input type="submit" name="tabla_rango" value="Migrar" style="font-size: 25px; color: #F8F6F3; background: #D90000"/></td>';
		echo '</tr>';
	echo '</table>';
echo '</form>';

?>