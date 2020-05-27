<?php if (!isset($_SESSION)) {session_start();}

$consulta_insertar = 'INSERT INTO difuntos (';
	foreach($campos as $campo_nombre => $campo_atributo)
	{
		if($campo_nombre != "id")
		{
			$consulta_insertar.= $campo_nombre.', ';
		}
	}
	$consulta_insertar = rtrim($consulta_insertar, ', ').') VALUES (';
	foreach($campos as $campo_nombre => $campo_atributo) 
	{
		if($campo_nombre != "id")
		{
			if($campo_nombre == "codigo")
			{
				$consulta_insertar.= '"'.trim(strtolower($codigo_a_usar)).'",';
			}
			elseif($campo_nombre == "borrado" or $campo_nombre == "concluido")
			{
				// $consulta_insertar.= '"'.trim(strtolower(!empty($_POST[$campo_nombre]) ? trim(strtolower($_POST[$campo_nombre])) : "no")).'",';
				$consulta_insertar.= '"no",';
			}
			else
			{
				if($campo_nombre == "nacimiento" 
					or $campo_nombre == "defuncion_fecha" 
					or $campo_nombre == "defuncion_hora" 
				    or $campo_nombre == "inicio_fecha" 
				    or $campo_nombre == "inicio_hora" 
				    or $campo_nombre == "fin_fecha" 
				    or $campo_nombre == "fin_hora" 
				    or $campo_nombre == "mortaja_fecha" 
				    or $campo_nombre == "busqueda_tiempo" 
				    or $campo_nombre == "creado" 
				    or $campo_nombre == "modificado" 
				    or $campo_nombre == "cementerio_hora" 
				    or $campo_nombre == "traslado_fecha" 
				    or $campo_nombre == "traslado_hora")
				{
					// if($campo_nombre == "borrado" or $campo_nombre == "concluido")
					// {
						// $consulta_insertar.= '"'.trim(strtolower(!empty($_POST[$campo_nombre]) ? trim(strtolower($_POST[$campo_nombre])) : "no")).'",';
					// 	$consulta_insertar.= '"no",';
					// }
					// else
					// {
						$consulta_insertar.= '"'.trim(strtolower(!empty($_POST[$campo_nombre]) ? trim(strtolower($_POST[$campo_nombre])) : "0000-00-00 00:00:00")).'",';
					// }
					
				}
				else
				{
					if($campo_nombre == "monto_lista" or $campo_nombre == "monto_diferido")
					{
						$consulta_insertar.= '"'.trim(strtolower(!empty($_POST[$campo_nombre]) ? trim(strtolower($_POST[$campo_nombre])) : "0")).'",';
					}
					else
					{
						if($campo_nombre == 'difunto')
						{
							$difunto_a_guardar = str_replace(' ,', ',', preg_replace('/\s+/', ' ', $_POST[$campo_nombre]));
							$consulta_insertar.= '"'.trim(strtolower(!empty($difunto_a_guardar) ? trim(strtolower($difunto_a_guardar)) : "sin datos")).'",';
						}
						else
						{
							$consulta_insertar.= '"'.trim(strtolower(!empty($_POST[$campo_nombre]) ? trim(strtolower($_POST[$campo_nombre])) : "sin datos")).'",';
						}
					}
				}
		    }
		}
	}
	$consulta_insertar = rtrim($consulta_insertar, ',)');
	$consulta_insertar = $consulta_insertar.')';

try
{
	$query_2 = $conexion->prepare($consulta_insertar);
	$query_2->execute();
	echo '<br/>';
	echo '<div class="cargar-prestaciones-mensajesi">';
		echo "Se ha agregado con exito.";
	echo '</div>';
	echo '<form action="../../vistas/procesos/atender-imprimir.php" method="post" class="nobr">';
		echo '<table class="cargar-prestaciones-submit">';
			echo '<tr>';
				echo '<td>';
					echo '<input type="submit" name="imprimir" class="datos" value="Imprimir"/><br/>';
				echo '</td>';
				echo '<td>';
					echo '<input type="submit" name="volver" class="datos" value="Volver"/><br/>';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	echo '</form>';
	$_SESSION['prestacion_tipo'] = $_POST['tipo'];
	$_SESSION['prestacion_codigo'] = $codigo_a_usar;//$_POST['codigo']
}
catch( PDOException $e )
{
	echo '<div class="cargar-prestaciones-mensajeno">';
		echo "Ha ocurrido un error. Favor intente de nuevo.";
	echo '</div>';
	echo '<br/>';
	echo $e;

	echo '<form action="" method="post" class="nobr">';
		echo '<table class="cargar-prestaciones-submit">';
			echo '<tr>';
				echo '<td>';
					echo '<input type="submit" name="volver" class="datos" value="Volver"/><br/>';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	echo '</form>';
}

?>