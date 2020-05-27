<?php if (!isset($_SESSION)) {session_start();}

	include '../../vistas/datos/difuntos.php';

	if(isset($_POST['grabar']))
	{
		$partes_del_servicio_a_modificar = explode("_", $_POST['servicio_a_modificar']);

		$consulta_actualizacion = 'UPDATE difuntos SET ';
		foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
		{
			if(isset($_POST[$campo_nombre]))
			{
				(empty($_POST[$campo_nombre])) ? $valor_actualizado = "sin datos" : $valor_actualizado = $_POST[$campo_nombre];
				$consulta_actualizacion.= $campo_nombre.' = "'.$valor_actualizado.'", ';
			}
		}
		$consulta_actualizacion = rtrim($consulta_actualizacion, ", ");
		$consulta_actualizacion.= ' WHERE id LIKE "'.$partes_del_servicio_a_modificar[0].'" AND codigo LIKE "'.$partes_del_servicio_a_modificar[1].'"';

		try
		{
			$query_actualizacion = $conexion->prepare($consulta_actualizacion);
	    	$query_actualizacion->execute();
			
			$mensaje_del_query = "Se ha actualizado el servicio ".$partes_del_servicio_a_modificar[1]." correctamente.";
			$color_del_mensaje = "#00ff00";
		}
		catch( PDOException $e )
		{
			$mensaje_del_query = "Ha ocurrido un problema al intenar actualizar el servicio ".$partes_del_servicio_a_modificar[1].". Contacte con el programador.";
			$color_del_mensaje = "#e62e00";

			echo $e;
			echo $consulta_actualizacion;
			echo '<br/>';
		}

		echo '<span style="font-weight:bold;color:'.$color_del_mensaje.';">';
			echo $mensaje_del_query;
		echo '</span>';
	}
	else
	{
		if(!isset($_POST['elegir_servicio']))
		{
			$campos_a_mostrar = array('fecha', 'difunto', 'tipo', 'codigo', 'modo', 'contrato_prepago', 'cooperativa_o_asociacion');

			$consulta_busqueda = 'SELECT * FROM difuntos
				WHERE borrado LIKE "no"
				AND concluido LIKE "no"
				AND derivado_desde_llamada LIKE "si"
				ORDER BY fecha ASC';
			$query_busqueda = $conexion->prepare($consulta_busqueda);
			$query_busqueda->execute();

			$estilo_del_td = 'padding: 0 10px 0 10px;';
			echo '<form action="" method="post">';
				echo '<table>';
					$estilo_del_td = 'padding: 0 10px 0 10px;';
					echo '<tr>';
						foreach ($campos_a_mostrar as $pos => $campo_nombre)
						{
							echo '<th style="'.$estilo_del_td.'">';
								echo ucwords(str_replace("_", " ", $campo_nombre));
							echo '</th>';
						}
					echo '</tr>';
					while($rows_b = $query_busqueda->fetch(PDO::FETCH_ASSOC))
					{
						echo '<tr class="fila_interna">';
							foreach ($campos_a_mostrar as $pos => $campo_nombre)
							{
								echo '<td style="'.$estilo_del_td.'">';
									echo $rows_b[$campo_nombre];
								echo '</td>';
							}
							echo '<td style="'.$estilo_del_td.'">';
								echo '<input type="radio" name="servicio_elegido" value="'.$rows_b['id'].'_'.$rows_b['codigo'].'" required>';
							echo '</td>';
						echo '</tr>';
					}
					echo '<tr>';
						echo '<td>';
							echo '<input type="submit" name="elegir_servicio" value="Siguiente">';
						echo '</td>';
					echo '</tr>';

				echo '</table>';
			echo '</form>';
		}
		else
		{
			$servicio_elegido = explode("_", $_POST['servicio_elegido']);
			$i = 0;
			$consulta_eleccion = 'SELECT * FROM difuntos
				WHERE borrado LIKE "no"
				AND id LIKE "'.$servicio_elegido[0].'"
				AND codigo LIKE "'.$servicio_elegido[1].'"';
			$query_eleccion = $conexion->prepare($consulta_eleccion);
			$query_eleccion->execute();
			
			//------------------------------AGREGAR A $campos_a_ignorar LOS CAMPOS DE FERETROS CUANDO EL DEPOSITO ESTE COMPLETO Y LIBERADO------------------------------//
			// $campos_a_ignorar = array('feretro_serie', 'feretro_modelo', 'feretro_medida', 'feretro_fecha', 'urna_serie', 'urna_status', 'urna_fecha_deposito', 'urna_compuesto', 'creado', 'modificado', 'borrado');
			$campos_a_ignorar = array('urna_serie', 'urna_status', 'urna_fecha_deposito', 'urna_compuesto', 'creado', 'modificado', 'borrado');
			$campos_modificables = array('modo', 'contrato_prepago', 'cooperativa_o_asociacion', 'direccion_calle');

			echo '<form action="" method="post" class="nobr" id="formPrincipal">';
				echo '<table class="cargar-prestaciones">';
					while($rows = $query_eleccion->fetch(PDO::FETCH_ASSOC))
					{
						$vista_tipo = $rows['tipo'];
						foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
						{
							$valor = "";
							if(!in_array($campo_nombre, $campos_a_ignorar) and !in_array($campo_nombre, $campos_a_no_incluir_en_atender) and $rows[$campo_nombre] !== "no aplicable")
							{
								if(trim($rows[$campo_nombre]) === "sin datos"
									or $rows[$campo_nombre] === "0.00"
									or $rows[$campo_nombre] === "0000-00-00"
									or $rows[$campo_nombre] === "00:00:00"
									or in_array($campo_nombre, $campos_modificables))
								{
									echo '<tr id="fila_'.$i.'">';
										$ind = $i;
										if($rows[$campo_nombre] !== "sin datos") $valor = $rows[$campo_nombre];
										include '../../funciones/generar-inputs-prestaciones.php';
									echo '</tr>';
								}
								else
								{
									echo '<tr>';
										echo '<td></td>';
										echo '<td style="padding:10px;">';
											echo '<b>'.ucwords(str_replace("_", " ", $campo_nombre)).'</b>';
											echo '&nbsp&nbsp&nbsp&nbsp';
											echo $rows[$campo_nombre];
										echo '</td>';
									echo '</tr>';
								}
								$i++;
							}
						}
						$servicio_a_modificar = $rows['id']."_".$rows['codigo'];
					}
					echo '<tr>';
						echo '<td>';
							echo '<input type="hidden" name="servicio_a_modificar" value="'.$servicio_a_modificar.'">';
							echo '<input type="submit" name="grabar" value="Siguiente">';
						echo '</td>';
					echo '</tr>';
				echo '</table>';
			echo '</form>';
		}
	}

?>
