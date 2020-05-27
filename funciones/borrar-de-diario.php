<?php if (!isset($_SESSION)) {session_start();}
	
	if(isset($_POST['seleccionar_filtro']) or isset($_POST['borrar_movimientos']))
	{
		$consulta_movimientos = 'SELECT * FROM diario WHERE borrado LIKE "no" AND efectuado_fecha LIKE "0000-00-00%" AND LOWER('.$filtro['campo'].') LIKE "'.strtolower($_POST['filtro_seleccionado']).'"';
		// AND aprobado LIKE "0000-00-00 00:00:00"
	}

	if(isset($_POST['borrar_movimientos']))
	{
		$es_valido = "no";
		if(isset($_POST['borrar']) and !empty($_POST['borrar']))
		{
			foreach ($_POST['borrar'] as $pos => $id_a_borrar)
			{
				$consulta_control_borrado = $consulta_movimientos.' AND id LIKE "'.$id_a_borrar.'"';
				if(isset($consulta_movimientos_filtros)) $consulta_control_borrado.= " ".$consulta_movimientos_filtros;
				$query_control_borrado = $conexion->prepare($consulta_control_borrado);
				$query_control_borrado->execute();
				while($rows_c_b = $query_control_borrado->fetch(PDO::FETCH_ASSOC)) $es_valido = "si";

				if($es_valido == "si")
				{
					$consulta_borrar = 'UPDATE diario SET borrado = "'.$_POST['motivo_seleccionado'].'", usuario = "'.$_SESSION['usuario_en_sesion'].'" WHERE id LIKE "'.$id_a_borrar.'"';
					// AND aprobado LIKE "0000-00-00 00:00:00"
					try
					{
						$query_borrar = $conexion->prepare($consulta_borrar);
						$query_borrar->execute();
						echo '<span style="color:green;font-weight:bold;">El movimiento con ID: '.$id_a_borrar.' se ha borrado con exito!</span>';
					}
					catch( PDOException $e )
					{
						echo '<span style="color:red;font-weight:bold;">Error en el movimiento ID: '.$id_a_borrar.'. No se pudo borrar. Contacte con un programador con la siguiente referencia:</span>';
						echo '<br/>';
						echo $e;
						echo '<br/>';
					}
					
				}
				echo '<br/>';
				echo '<br/>';
			}
		}
		else
		{
			echo "No hay nada para borrar.";
		}
	}

	$titulo = basename(__FILE__,'.php');
	
	echo '<form id="form_borrar" method="post" action="">';

		echo ucwords(str_replace("_", " ", $filtro['campo'])).": ";

		switch ($filtro['tipo'])
		{
			case 'seleccionar':
				$query_filtro = $conexion->prepare($filtro['consulta']);
				$query_filtro->execute();
				while($rows_f = $query_filtro->fetch(PDO::FETCH_ASSOC)) $filtros_seleccionables[] = $rows_f[$filtro['campo']];

				echo '<select name="filtro_seleccionado">';
					foreach ($filtros_seleccionables as $pos => $filtro_seleccionable)
					{
						$seleccionado = (isset($_POST['filtro_seleccionado']) and $filtro_seleccionable == $_POST['filtro_seleccionado']) ? "selected" : "";
						echo '<option value="'.$filtro_seleccionable.'" '.$seleccionado.'>'.$filtro_seleccionable.'</option>';
					}
				echo '</select>';
			break;

			case 'autocompletar':
				echo "Agregar metodo...";
			break;
			
			default:
				echo '<input type="text" name="filtro_seleccionado" value="'.$_POST['filtro_seleccionado'].'">';
			break;
		}

		echo '<input type="submit" name="seleccionar_filtro" value="Seleccionar">';

		if(isset($_POST['seleccionar_filtro']))
		{
			echo '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';

			$consulta_motivos = 'SELECT descripcion FROM agrupadores WHERE borrado LIKE "no" AND agrupador LIKE "motivos de borrado del sistema" AND descripcion NOT LIKE "nombres de columnas" ORDER BY descripcion ASC';
			$query_motivos = $conexion->prepare($consulta_motivos);
			$query_motivos->execute();
			while($rows_motivos = $query_motivos->fetch(PDO::FETCH_ASSOC)) $motivos_de_borrado[] = $rows_motivos['descripcion'];

			echo '<select name="motivo_seleccionado" style="border-color:red;">';
				foreach ($motivos_de_borrado as $pos => $motivo)
				{
					echo '<option value="'.$motivo.'">'.$motivo.'</option>';
				}
			echo '</select>';

			echo '<input type="submit" id="boton_borrar" name="borrar_movimientos" value="Borrar" style="border-color:red;color:red;background-color:#ffb3b3;">';

			$campos_a_mostrar_a = explode(",", $campos_a_mostrar);
			$movimientos_por_cuenta = array();

			if(isset($consulta_movimientos_filtros)) $consulta_movimientos.= " ".$consulta_movimientos_filtros;
			$query_movimientos = $conexion->prepare($consulta_movimientos);
			$query_movimientos->execute();
			while($rows_m = $query_movimientos->fetch(PDO::FETCH_ASSOC))
			{
				$cuenta = $rows_m['cuenta'];
				$id = $rows_m['id'];
				foreach ($campos_a_mostrar_a as $pos => $campo_nombre) $movimientos_por_cuenta[$cuenta][$id][$campo_nombre] = $rows_m[$campo_nombre];
			}

				echo '<table>';

					ksort($movimientos_por_cuenta);
					
					$estilo_del_td = 'padding:5px;';
					
					foreach ($movimientos_por_cuenta as $cuenta => $movimientos)
					{
						echo '<tr><td>&nbsp</td></tr>';
						echo '<tr>';
							echo '<td colspan="50">';
								echo '<h5>'.$cuenta.'</h5>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							foreach ($campos_a_mostrar_a as $pos => $campo_nombre) 
							{
								echo '<th style="'.$estilo_del_td.'">';
									echo ucwords(str_replace("_", " ", $campo_nombre));
								echo '</th>';
							}
						echo '</tr>';

						foreach ($movimientos as $id => $campos)
						{
							echo '<tr>';
								foreach ($campos_a_mostrar_a as $pos => $campo_nombre) 
								{
									switch ($campo_nombre) {
										case 'derecho': case 'obligacion':
											echo '<td style="'.$estilo_del_td.'text-align:right;">';
												echo number_format($campos[$campo_nombre]);
											echo '</td>';
										break;

										case 'documento_numero':
											echo '<td style="'.$estilo_del_td.'text-align:right;">';
												echo $campos[$campo_nombre];
											echo '</td>';
										break;
										
										default:
											echo '<td style="'.$estilo_del_td.'">';
												echo $campos[$campo_nombre];
											echo '</td>';
										break;
									}
								}

								echo '<td style="'.$estilo_del_td.'">';
									echo '<input type="checkbox" name="borrar[]" value="'.$id.'">';
								echo '</td>';
							echo '</tr>';
						}
					}

				echo '</table>';
		}
	echo '</form>';
?>

<script>
	
	$('input[type=checkbox]').change(function(){
		if($(this).is(':checked'))
		{
			$(this).closest('tr').css('color', 'red');
		}
		else
		{
			$(this).closest('tr').css('color', 'initial');
		}
	});
	
	$('#boton_borrar').click(function() {
	    var cantidad_a_borrar = 0;
	    $('input[type=checkbox]:checked').each(function() {
			cantidad_a_borrar = cantidad_a_borrar + 1;
		});
	    return confirm("Desea borrar "+cantidad_a_borrar+" movimientos?");
	});

</script>


