<?php if (!isset($_SESSION)) {session_start();}

echo '<table>';
	echo '<form class="rangos" method="post" action="">';
		echo '<tr>';
			echo '<td colspan="4">';
				echo "Busque por nombre o fecha";
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<th>';
				echo "Nombre o Apellido:";
				echo "&nbsp&nbsp&nbsp";
			echo '</th>';
			echo '<td>';
				echo '<input type="text" name="nombre_buscado">';
				echo "&nbsp&nbsp&nbsp";
			echo '</td>';
			echo '<th>';
				echo "Fecha:";
				echo "&nbsp&nbsp&nbsp";
			echo '</th>';
			echo '<td>';

				$variable_nombre = "fecha_buscada";
				$rotulo = "";
				$indice = 0;				
				include '../../funciones/seleccionar-fechas.php';
				
				echo "&nbsp&nbsp&nbsp";
			echo '</td>';
			echo '<td>';
				echo '<input type="submit" name="codigo_buscado" value="Buscar Codigo">';
			echo '</td>';
		echo '</tr>';
	echo '</form>';
echo '</table>';

$consulta = "";

if(isset($_POST['codigo_buscado']))
{
	if(!empty($_POST['nombre_buscado']) and !empty($_POST['fecha_buscada']))
	{		
		$consulta = 'SELECT *
		FROM difuntos 
		WHERE borrado = "no"
		AND fecha = "'.$_POST['fecha_buscada'].'"
		AND (LOWER(titular) LIKE LOWER("%'.$_POST['nombre_buscado'].'%") OR LOWER(difunto) LIKE LOWER("%'.$_POST['nombre_buscado'].'%"))
		ORDER BY id
		DESC
		LIMIT 25';

	}
	elseif(empty($_POST['nombre_buscado']) and !empty($_POST['fecha_buscada']))
	{		
		$consulta = 'SELECT *
		FROM difuntos 
		WHERE borrado = "no"
		AND fecha = "'.$_POST['fecha_buscada'].'"
		ORDER BY id
		DESC
		LIMIT 25';

	}
	elseif(!empty($_POST['nombre_buscado']) and empty($_POST['fecha_buscada']))
	{		
		$consulta = 'SELECT *
		FROM difuntos 
		WHERE borrado = "no"
		AND (LOWER(titular) LIKE LOWER("%'.$_POST['nombre_buscado'].'%") OR LOWER(difunto) LIKE LOWER("%'.$_POST['nombre_buscado'].'%"))
		ORDER BY id
		DESC
		LIMIT 25';

	}
	try
	{
		$query = $conexion->prepare($consulta);
		$query->execute();
		
		echo '<table id="tabla_buscable">';
			echo '<tr>';
				echo '<th>';
					echo "Fecha";
				echo '</th>';
				echo '<th>';
					echo "Difunto";
				echo '</th>';
				echo '<th>';
					echo "Titular";
				echo '</th>';
				echo '<th>';
					echo "Codigo";
				echo '</th>';
			echo '</tr>';
			
			while($rows = $query->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr>';
					echo '<td>';
						echo $rows['fecha'];
						echo "&nbsp&nbsp&nbsp";
					echo '</td>';
					echo '<td>';
						echo $rows['difunto'];
						echo "&nbsp&nbsp&nbsp";
					echo '</td>';
					echo '<td>';
						echo $rows['titular'];
						echo "&nbsp&nbsp&nbsp";
					echo '</td>';
					echo '<td>';
						echo $rows['codigo'];
						echo "&nbsp&nbsp&nbsp";
					echo '</td>';
				echo '</tr>';
			}
		
		echo '<table>';
		
	}
	catch(PDOException $e)
	{
		"Fallo";
	}
}

?>
