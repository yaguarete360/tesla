<?php if (!isset($_SESSION)) {session_start();}

if(!isset($rompe_bloques)) $rompe_bloques = '';
if(!isset($estilo_del_td)) $estilo_del_td = '';
if(!isset($es_nombre_de_capitulo)) $es_nombre_de_capitulo = '';
if(!isset($ver_etiqueta)) $ver_etiqueta = '';

include "../../funciones/switch-de-estilo-prestaciones.php";

if($rompe_bloques == "arriba" or $rompe_bloques == "ambos") echo '<td colspan="2" style="height:5px;background-color:#D8A262;"></td></tr><tr>';

$variable_nombre = $campo_nombre;

if(!isset($valor)) $valor = "";

if($campo_atributo['formato'] != "oculto" and $campo_atributo['formato'] != "dato-no-aplicable" and $campo_atributo['formato'] != "sin-datos-por-defecto")
{
	echo '<td id="td_capitulo_'.$campo_nombre.'" class="td_columna1" '.$estilo_del_td.'>';
	if($es_nombre_de_capitulo == "si")
	{
		if(isset($nombre_del_capitulo) and empty($nombre_del_capitulo))
		{
			$nombre_del_campo_explotado = explode("_", $campo_nombre);
			$nombre_del_capitulo = $nombre_del_campo_explotado[0];
		}
		echo '<b>'.ucwords($nombre_del_capitulo).'</b> &nbsp &nbsp';
	}

	echo '</td>';
}

if($campo_atributo['formato'] != "oculto" and $campo_atributo['formato'] != "dato-no-aplicable" and $campo_atributo['formato'] != "sin-datos-por-defecto")
{
	echo '<td id="td_label_'.$campo_nombre.'" class="td_columna2" '.$estilo_del_td.'>';
	if($ver_etiqueta == "si")
	{
		echo '<b><label id="label_'.$campo_nombre.'" for="'.$campo_nombre.'">'.ucwords(str_replace("_", " ", $campo_nombre)).'</label></b> &nbsp &nbsp';
	}

}

if(isset($campo_atributo['selector']) and $campo_atributo['selector'] == "asistido" and $campo_atributo['formato'] != "dato-no-aplicable" and $campo_atributo['formato'] != "sin-datos-por-defecto")
{
	switch ($campo_atributo['asistente'])
	{
		case 'armar-nombre':
			include "../../funciones/armar-nombre-script.php";
		break;
		
		case 'armar-codigo':
			include "../../funciones/armar-codigo.php";
		break;
		
		case 'seleccion-de-sitio':
			include "../../funciones/armar-seleccion-de-sitio.php";
		break;

		case 'opcion-especifica': case 'opcion-segun-tipo':

			$rotulo = "";
			$sin_datos = "si";
			$blanco = "si";
			$todos = "no";
			$sentido = "ASC";
				include "../../funciones/seleccionar-archivos-especificos.php";
			echo '</td>';

		break;
		
		case 'opcion-especifica-simplificado':

			$rotulo = "";
			$blanco = "si";
				include "../../funciones/seleccionar-archivos-especificos-simplificado.php";
			echo '</td>';

		break;

		case 'hora':
				echo '<br/>';
				include "../../funciones/seleccionar-horas.php";
			echo '</td>';
		break;

		case 'calcular-edad':
				$campo_nacimiento = "nacimiento";
				$campo_defuncion = "defuncion_fecha";
				include "../../funciones/calcular-edad.php";
			echo '</td>';
		break;

		case 'boton':		
				$rotulo = "";
				include "../../funciones/seleccionar-botones.php";
			echo '</td>';
		break;

		case 'vista-tipo':
				echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$vista_tipo.'" readonly/>';
			echo '</td>';
		break;

		case 'numero-por-tipo':
				
				include '../../funciones/armar-numero-por-tipo.php';
				
				echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="numero-por-tipo" value="'.$numero_final.'"/>';
			echo '</td>'; 			
		break;

		case 'mostrar-monto-lista':
				include '../../funciones/mostrar-monto-lista.php';
				echo '<input type="text" id="'.$campo_nombre.'" name="'.$campo_nombre.'" class="datos" value="'.$valor.'" readonly/>';
			echo '</td>';
		break;

		case 'radios':
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
					include "../../funciones/armar-form-segun-modo.php";
				}
			echo '</td>';
		break;

		case 'armar-contrato':
				include "../../funciones/armar-contrato-script.php";
			echo '</td>';
		break;

		case 'elegir-feretros':
				isset($rows[$campo_nombre]) ? $valor = $rows[$campo_nombre] : $valor = "";
				if($campo_nombre == 'feretro_serie')
				{
						include "../../funciones/feretros-en-input-nuevo.php";
					echo '</td>';
				}
				else
				{
						echo '<input type="text" id="'.$campo_nombre.'" name="'.$campo_nombre.'" class="datos" value="'.$valor.'" readonly/>';
					echo '</td>';
				}
		break;

		case 'selector-urna':
				include "../../funciones/seleccionar-urna.php";
			echo '</td>';
		break;

		case 'auto_fecha':
			if($campo_atributo['formato'] != "oculto")
			{
					$auto_fecha_a_usar = date("Y-m-d G:i:s");
					echo $auto_fecha_a_usar;
					echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" value="'.$auto_fecha_a_usar.'"/>';
				echo '</td>';
			}
		break;

		case 'ultimo_usuario':
				echo $_SESSION['usuario_en_sesion'];
				echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$_SESSION['usuario_en_sesion'].'"/>';
			echo '</td>';
		break;
	}
}
else
{
	switch ($campo_atributo['formato'])
	{
		case 'fecha':
				
				$rotulo = "";
				$indice = $ind;
				if(isset($valor)) $_SESSION[$variable_nombre] = $valor;
				include "../../funciones/seleccionar-fechas.php";
			
			echo '</td>';
		break;
		
		case 'vista':
				isset($rows[$campo_nombre]) ? $valor = $rows[$campo_nombre] : $valor = "";
				echo $valor;
				echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$valor.'"/>';
			echo '</td>';
		break;

		case 'texto':
				(isset($rows[$campo_nombre]) and $rows[$campo_nombre] !== "sin datos") ? $valor = $rows[$campo_nombre] : $valor = "";
				echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$valor.'"/>';
			echo '</td>';
		break;

		case 'numero-texto':
				(isset($rows[$campo_nombre]) and $rows[$campo_nombre] !== "sin datos") ? $valor = $rows[$campo_nombre] : $valor = "";
				echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$valor.'"/>';
			echo '</td>';
		break;

		case 'numero':
				(isset($rows[$campo_nombre]) and $rows[$campo_nombre] !== "sin datos") ? $valor = $rows[$campo_nombre] : $valor = "";
				echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$valor.'"/>';
			echo '</td>';
		break;
		
		case 'oculto':
		
			if(!isset($valor)) $valor = "";
			if(empty($valor) and isset($campo_atributo['valor_por_defecto'])) $valor = $campo_atributo['valor_por_defecto'];
			echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$valor.'"/>';
		break;
		
		case 'dato-no-aplicable':

			if($campo_nombre=="mortaja_fecha" or $campo_nombre=="cementerio_hora" or $campo_nombre=="traslado_fecha" or $campo_nombre=="traslado_hora" or $campo_nombre=="traslado_tiempo" or $campo_nombre=="defuncion_hora")
			{
			echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="0000:00:00 00:00:00"/>';
			}else
			{
				echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="no aplicable"/>';
			}
			
		break;

		case 'sin-datos-por-defecto':

			if($campo_nombre=="busqueda_tiempo" or $campo_nombre=="laboratorio_tiempo" or $campo_nombre=="tramite_tiempo" or $campo_nombre=="traslado_tiempo" or $campo_nombre=="responso_hora" or $campo_nombre=="soldadura_hora" or $campo_nombre=="oculto" or $campo_nombre=="concluido" or $campo_nombre=="creado"  or $campo_nombre=="traslado_fecha" or $campo_nombre=="traslado_hora")
			{
				echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="0000:00:00 00:00:00"/>';
			}else
			{
				echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="sin datos"/>';
			}
			
		break;

		default:
				if(!isset($valor)) $valor = "";
				echo '<input type="'.$campo_atributo['formato'].'" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$valor.'"/>';
			echo '</td>';
		break;
	}
}

if($rompe_bloques == "abajo" or $rompe_bloques == "ambos") echo '</tr><tr><td colspan="2" style="height:5px;background-color:#D8A262;"></td></tr><tr>';

?>
