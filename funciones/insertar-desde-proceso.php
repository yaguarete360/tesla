<?php if (!isset($_SESSION)) {session_start();}

include '../../vistas/datos/'.$tabla_a_usar.'.php';

if(!isset($_POST['grabar']))
{
	echo '<form action="" method="post">';
		echo '<table>';
			$estilo_del_td = "";
			foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
			{
				echo '<tr><td>&nbsp</td></tr>';
				echo '<tr>';
					(isset($campo_atributo['mostrar_etiqueta'])) ? $mostrar_etiqueta = $campo_atributo['mostrar_etiqueta'] : $mostrar_etiqueta = "si";
					if($campo_atributo['formato'] == "oculto") $mostrar_etiqueta = "no";
					echo '<td></td>';

					if($campo_atributo['formato'] != "oculto" and $mostrar_etiqueta == "si")
					{
						echo '<td id="td_label_'.$campo_nombre.'"><label id="label_'.$campo_nombre.'" for="'.$campo_nombre.'">';
							echo ucwords(str_replace("_", " ", $campo_nombre));
						echo '</label></td>';
					}
					if(isset($campos_a_redefinir[$campo_nombre]))
					{
						echo '<td>';
							echo $campos_a_redefinir[$campo_nombre];
							echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" value="'.$campos_a_redefinir[$campo_nombre].'"/>';
						echo '</td>';
					}
					else
					{
						if(isset($campo_atributo['asistente']))
						{
							switch ($campo_atributo['asistente'])
							{
								case 'autonumeracion':
									$en_proceso = "si";
									echo '<td id="td_'.$campo_nombre.'">';
										include "../../funciones/autonumerar.php";
									echo '</td>';
								break;

								case 'opcion-especifica':
									echo '<td id="td_'.$campo_nombre.'">';
										$rotulo = "";
										$blanco = "si";
										$todos = "no";
										$sentido = "ASC";
										$variable_nombre = $campo_nombre;

										include "../../funciones/seleccionar-archivos-especificos.php";
									echo '</td>';
								break;
								
								case 'auto_fecha':
									$auto_fecha_a_usar = date("Y-m-d G:i:s");
									echo '<td id="td_'.$campo_nombre.'">';
										if($campo_atributo['formato'] != "oculto") echo $auto_fecha_a_usar;
										echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" value="'.$auto_fecha_a_usar.'"/>';
									echo '</td>';
								break;
								
								case 'armar-nombre':
									$listado_tipo = "altas";
									echo '<td id="td_'.$campo_nombre.'">';
										include "../../funciones/armar-nombre-script.php";
									echo '</td>';
								break;
								case 'armar-contrato':
									echo '<td id="td_'.$campo_nombre.'">';
										include "../../funciones/armar-contrato-script.php";
									echo '</td>';
								break;

								case 'ultimo_usuario':
									echo '<td id="td_'.$campo_nombre.'">';
										if($campo_atributo['formato'] != "oculto") echo ucwords($_SESSION['usuario_en_sesion']);
										echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" value="'.$_SESSION['usuario_en_sesion'].'"/>';
									echo '</td>';
								break;

								case 'radios':
									echo '<td id="td_'.$campo_nombre.'">';
										$radios = explode("-", $campo_atributo['herramientas']);
										$radios_a_armar = array();
										
										foreach ($radios as $radio)
										{
											$radios_separado = explode("=", $radio);
											$radios_a_armar[$radio]['etiqueta'] = $radios_separado[0];
											(isset($radios_separado[1])) ? $radios_a_armar[$radio]['valor'] = $radios_separado[1]: $radios_a_armar[$radio]['valor'] = $radios_separado[0];
										}

										$radio_contador = 0;
										foreach ($radios_a_armar as $radio)
										{
											if($valor == $radio['valor'] or $radio_contador == 0)
											{
												echo ucwords($radio['etiqueta']).': <input type="radio" id="'.$campo_nombre."-".$radio['etiqueta'].'" name="'.$campo_nombre.'" class="datos" value="'.$radio['valor'].'" checked/>';
											}
											else
											{
												echo '&nbsp&nbsp&nbsp';
												echo ucwords($radio['etiqueta']).': <input type="radio" id="'.$campo_nombre."-".$radio['etiqueta'].'" name="'.$campo_nombre.'" class="datos" value="'.$radio['valor'].'"/>';
											}
											$radio_contador++;
										}
										
										if($campo_nombre === "modo")
										{
											include "../../funciones/armar-form-segun-modo-desde-proceso.php";
										}
									echo '</td>';
								break;

								default:
									echo '<td id="td_'.$campo_nombre.'">';
										echo 'No existe su asistente.';
									echo '</td>';
								break;
							}
						}
						else
						{
							switch ($campo_atributo['formato'])
							{
								case 'oculto': case 'vista':
									echo '<td>';
										echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value=""/>';
									echo '</td>';
								break;

								case 'texto':
									echo '<td>';
										echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value=""/>';
									echo '</td>';
								break;

								case 'numero':
									echo '<td>';
										echo '<input type="number" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value=""/>';
									echo '</td>';
								break;

								case 'texto-caja':
									echo '<td>';
										echo '<textarea name="'.$campo_nombre.'" id="'.$campo_nombre.'"></textarea>';
									echo '</td>';
								break;
								
								default:
									echo '<td>';
										echo "No existe su formato. ".$campo_atributo['formato'];
									echo '</td>';
								break;
							}
						}
					}
				// 	echo '</td>';
				echo '</tr>';
			}

			echo '<tr>';
				echo '<td></td>';
				echo '<td>';
					echo '<input type="submit" name="grabar" value="Grabar">';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	echo '</form>';
}

if(isset($_POST['grabar']))
{
	$consulta = 'INSERT INTO '.$tabla_a_usar.' (';
	foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo) $consulta.= $campo_nombre.', ';
	$consulta = rtrim($consulta, ', ');
	$consulta.= ') VALUES (';
	foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
	{
		if(isset($campo_atributo['asistente']) and $campo_atributo['asistente'] == "autonumeracion")
		{
			$consulta.= "'";
			$en_proceso = "si";
			include "../../funciones/autonumerar.php";
			$consulta = rtrim($consulta, "'");
		}
		else
		{
			if(isset($_POST[$campo_nombre]))
			{
				if($campo_nombre == "cooperativa_o_asociacion")
				{
					(!empty($_POST[$campo_nombre])) ? $consulta.= "'".$_POST[$campo_nombre]."', ": $consulta.= "'no aplicable', ";
				}
				elseif($campo_nombre == "borrado" or $campo_nombre == "derivado")
				{
					$consulta.= "'no', ";
				}
				else
				{
					(!empty($_POST[$campo_nombre])) ? $consulta.= "'".$_POST[$campo_nombre]."', ": $consulta.= "'sin datos', ";
				}
			}
			else
			{
				$consulta.= "'', ";
			}
		}
	}
	$consulta = rtrim($consulta, ', ');
	$consulta.= ')';
	try
	{
		$query = $conexion->prepare($consulta);
	    $query->execute();
    	
    	if(isset($numero_a_usar))
    	{
    		$agarrar_el_elemento_numero = 1;
    		$campo_singular_a = array_slice($_SESSION['campos'], $agarrar_el_elemento_numero, $agarrar_el_elemento_numero, true);
    		$campo_singular_a_1 = array_keys($campo_singular_a);
    		$campo_singular_s = $campo_singular_a_1[0];

	    	$mensaje_de_carga = "<b>No se ha podido guardar el/la ".$campo_singular_s.".</b>";
	    	$consulta_corroborar_carga = 'SELECT * FROM '.$tabla_a_usar.' WHERE borrado LIKE "no" AND '.$campo_singular_s.' LIKE "'.$numero_a_usar.'"';
	    	$query_cc = $conexion->prepare($consulta_corroborar_carga);
		    $query_cc->execute();
	    	while($rows_cc = $query_cc->fetch(PDO::FETCH_ASSOC)) echo "<b>Se ha guardado el/la ".$campo_singular_s." ".$numero_a_usar."</b>";
    	}
    	else
    	{
    		echo "<b>Verificar si se ha guardado la informacion en la tabla ".$tabla_a_usar.".</b>";
    	}
	}
	catch (Exception $e)
	{
		echo "<b>Ha ocurrido un error.</b> ".$e;
	}
}

?>