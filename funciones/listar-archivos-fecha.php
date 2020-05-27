<?php if (!isset($_SESSION)) {session_start();}
	
$cantidad_registros = 0;
	
echo '<b>'.$_SESSION['titulo_pagina'].'</b>';

$_SESSION['fecha_desde'] = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_SESSION['fecha_desde'];
$_SESSION['fecha_hasta'] = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_SESSION['fecha_hasta'];

if(isset($_POST['listar']))
{

	include "../../funciones/conectar-base-de-datos.php";
	
	$consulta = 'SELECT * 
	FROM '.$_SESSION['tabla'].'
	WHERE borrado LIKE "0000-00-00 00:00:00"
	AND fecha BETWEEN "'.$_SESSION['fecha_desde'].'" 
	AND "'.$_SESSION['fecha_hasta'].'" 
	ORDER BY fecha '.$_SESSION['sentido'].'';
	
	$query = $conexion->prepare($consulta);
	$query->execute();

	echo '<table>';

		foreach($_SESSION['campos'] as $campo_vuelta => $campo_nombre)
		{

			$campos_abreviados = explode("_",$campo_nombre);

			if(isset($campos_abreviados[1]))
			{
				$encabezado = "";
				foreach($campos_abreviados as $campo_abreviado) $encabezado.= ucwords(substr($campo_abreviado,0,3)).".";
			}
			else
			{
				$encabezado = ucwords($campo_nombre);
			}

			echo '<th class="encabezados">';
				echo ucfirst($encabezado);
			echo '</th>';
		}

		while($rows = $query->fetch(PDO::FETCH_ASSOC))
		{
			echo '<tr>';
				foreach($_SESSION['campos'] as $campo_vuelta => $campo_nombre)
				{					
					if($campo_nombre == "costo")
					{
						echo '<td style="text-align:right; padding-left: 5px;">';
							echo number_format($rows[$campo_nombre],2,",",".");
						echo '</td>';
					}
					else 
					{
						echo '<td style="text-align:left; padding-left: 20px;">';
							echo $rows[$campo_nombre];
						echo '</td>';
					}
				}

				$cantidad_registros++;
			
			echo '</tr>';
		}
	echo '</table>';

	echo '<span class="fin-de-listado" colspan="3">Fin del listado de '.$cantidad_registros.' registros.</span>';

	echo "<br/>";
	echo '<form action="../../funciones/listar-archivos-fecha-pdf.php" method="post">';
		echo '<input type="submit" name="imprimir" value="Imprimir PDF"/>';
	echo '</form>';
}

?>
