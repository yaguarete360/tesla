<?php if (!isset($_SESSION)) {session_start();}

	switch ($campo_atributo['asistente']) 
	{	
	    case 'autocompletar':
			$herramientas_explotado = explode("-", $campo_atributo['herramientas']);
			$tabla_a_usar = $herramientas_explotado[0];
			if(!isset($campo_nombre)) $campo_nombre = $herramientas_explotado[1];
			$campo_a_usar = $herramientas_explotado[1];
			$herramientas_sub_explotado = isset($herramientas_explotado[2]) ? explode("#", $herramientas_explotado[2]) : array();
			$capitulo = "datos";
			$campos_filtro = array();
			foreach ($herramientas_sub_explotado as $pos => $herramientas_sub) $campos_filtro[] = $herramientas_sub;
			include '../funciones/autocompletar-base.php';
			$deshabilitar_boton_agregar = true;
		break;
		
	    case 'subir-foto':
			include "../funciones/subir-foto.php";
		break;
		
	    case 'armar-codigo-input':
			include "../funciones/armar-codigo-input.php";
		break;
		
		case 'actualizar-input-segun-select':
			include "../funciones/actualizar-input-segun-select.php";
		break;
		
    	case 'radios':
			$radios = explode("-", $campo_atributo['herramientas']);
			$radiosCantidad = count($radios);
			for ($rc = 0; $rc < $radiosCantidad; $rc++)
			{ 
				$radios_explotado = explode("=", $radios[$rc]);
				$radio_etiqueta = $radios_explotado[0];
				$radio_valor = (isset($radios_explotado[1])) ? $radios_explotado[1] : $radios_explotado[0];
				$chequeado = ($rc === 0 or $valor == $radio_valor) ? "checked" : "";
				echo ucwords($radio_etiqueta).': <input type="radio" id="'.$campo_nombre.$rc.'" name="'.$campo_nombre.'" class="datos" value="'.$radio_valor.'" '.$chequeado.'/>';
				echo '&nbsp&nbsp&nbsp';
			}
		break;
	
		case 'autonumeracion':
			include "../funciones/autonumerar.php";
		break;
		
		case 'opcion-texto':
			include "../funciones/opcion-texto.php";
		break;

		case 'numero-de-serie-automatico':
			include "../funciones/armar-numero-de-serie.php";
		break;
		
		case 'opcion-especifica':
			$rotulo = "";
			$blanco = "si";
			$todos = "no";
			$variables_oe = explode("-",$campo_atributo['herramientas']);
			$tabla_seleccion = $variables_oe[0];
			$campo_seleccion = $variables_oe[1];			
			$cantidad_oe = count($variables_oe);
			($cantidad_oe == 3) ? $grupo_seleccion = $variables_oe[2]: $grupo_seleccion = "";			
			$sentido = "ASC";
				
			include "../funciones/seleccionar-archivos-especificos.php";

		break;

		case 'con-pattern-y-length-especifico':
			$partes = explode("=", $campo_atributo['herramientas']);
			echo '<input id="'.$campo_nombre.'" type="text" name="'.$campo_nombre.'" class="datos" pattern="'.$partes[0].'" title="'.$partes[1].'" maxlength="'.$partes[2].'" value="'.$rows[$campo_nombre].'"/>';
		break;
		
		case 'boton':		
			$rotulo = "";
			include "../funciones/seleccionar-botones.php";	
		break;

		case 'armar-contrato':
				include "../funciones/armar-contrato-script.php";
			echo '</td>';
		break;
		
	 	case 'serial-por-linea':
 			echo '<select id="refrescado" class="datos">';
 				echo '<option value="1">Parque Serenidad</option>';
 				echo '<option value="2">Memorial</option>';
 			echo '</select>';
 			
 			$tabla_y_campo = explode("-", $campo_atributo['herramientas']);
 			$tabla_seleccion = $tabla_y_campo[0];
 			$campo_objetivo = $tabla_y_campo[1];
 			$campo_seleccion = $campo_nombre;
 			include "../funciones/usar-refrescar-serial-script.php";
 			echo '</td>';
 			echo '</tr>';
 			echo '<tr>';
	 			echo '<td>';
	 			echo '</td>';
	 			echo '<td>';
	 				echo '<b><label for="Autonumeracion">'.ucfirst("Autonumeracion").'</label></b>';
	 			echo '</td>';
	 			echo '<td>';
	 			if(isset($rows[$campo_nombre])) $valor_a_traer = $rows[$campo_nombre];
	 				echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="refrescado-objetivo" value="'.$valor_a_traer.'"/>';//readonly
	 			echo '</td>';
 			echo '</tr>';
	 	break;
	 	
	 	case 'armar-nombre':
			include "../funciones/armar-nombre-script.php";
		break;
		
		case 'hora':
			include "../funciones/seleccionar-horas.php";
		break;
		
		case 'ultimo_usuario':
			echo $_SESSION['usuario_en_sesion'];
			echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" value="'.$_SESSION['usuario_en_sesion'].'"/>';
		break;
		
		case 'auto_fecha':
			$auto_fecha_a_usar = date("Y-m-d G:i:s");
			echo $auto_fecha_a_usar;
			echo '<input type="hidden" name="'.$campo_nombre.'" class="datos" value="'.$auto_fecha_a_usar.'"/>';
		break;
		
	}
		
	
		
	// 	case 'serial':
	// 		echo '<td>';
	// 			if(isset($valorSerial))
	// 			{
	// 				echo '<input type="text" name="'.$campo.'" class="datos" value="'.$valorSerial.'"/><br/>';
	// 			}
	// 			else
	// 			{
	// 				echo '<input type="text" name="'.$campo.'" class="datos" value="'.$rows[$campo].'"/><br/>';	
	// 			}
	// 		echo '</td>';
	// 	break;		
		
	// 	case 'numeroDesde-corroborar':
	// 		echo '<td>';		
	// 			$tabla_y_campo = explode("-", $formatos[$f]);
	// 			$campo = $tabla_y_campo[0];
	// 			echo '<input type="text" name="'.$campo.'" class="datos" id="'.$campo.'" value=""/>';
	// 		echo '</td>';
	// 	break;
		
	// 	case 'cantidad-corroborar':
	// 		echo '<td>';
	// 			$tabla_y_campo = explode("-", $formatos[$f]);
	// 			$campo = $tabla_y_campo[0];
				
	// 			include "../funciones/corroborar-coinicidencias-script.php";
				
	// 			echo '<input type="text" name="'.$campo.'" class="datos" id="'.$campo.'" value=""/>';
	// 		echo '</td>';
	// 	break;
		
	// 	case 'numeroHasta-corroborar':
	// 	 	echo '<td>';
	// 			$tabla_y_campo = explode("-", $formatos[$f]);
	// 			$campo = $tabla_y_campo[0];
	// 			echo '<input type="text" name="'.$campo.'" class="datos" id="'.$campo.'" value=""/>';
	// 		echo '</td>';
	// 	break;
		
	// 	case 'bajasnombre-destino':
			
	// 		include "../funciones/nombre-script.php";
			
	// 		echo '<td>';
	// 			echo '<input type="text" id="bajasnombre-destino" name="'.$campo.'" class="datos" id="'.$campo.'" value="" readonly/>';
	// 		echo '</td>';
	// 	break;
		
	// 	case 'bajasnombre-nombres':
	// 		echo '<td>';
	// 			echo '<input type="text" id="bajasnombre-nombres" name="'.$campo.'" class="datos" id="'.$campo.'" value=""/>';
	// 		echo '</td>';
	// 	break;
		
	// 	case 'bajasnombre-apellidos':
	// 		echo '<td>';
	// 			echo '<input type="text" id="bajasnombre-apellidos" name="'.$campo.'" class="datos" id="'.$campo.'" value=""/>';
	// 		echo '</td>';
	// 	break;
		
	// 	case 'bajascontrato':
			
	// 		include "../funciones/bajascontrato-script.php";
			
	// 		echo '<td>';
	// 			echo '<select id="bajascontrato-linea" class="datos">';
	// 				echo '<option value="1">1</option>';
	// 				echo '<option value="2">2</option>';
	// 			echo '</select>';
	// 			echo '-';
	// 			echo '<select id="bajascontrato-centro" class="datos">';
	// 				echo '<option value="PSM">PSM</option>';
	// 				echo '<option value="PSV">PSV</option>';
	// 				echo '<option value="PSC">PSC</option>';
	// 			echo '</select>';
	// 			echo '-';
	// 			echo '<input type="number" name="bajascontrato-numero" class="datos" id="bajascontrato-numero" value=""/>';
	// 		echo '</td>';
	// 		echo '<input type="hidden" id="bajascontrato-destino" name="'.$campo.'" class="datos" value=""/>';
	// 	break;
		
	// 	case 'hora':
	// 		echo '<td>';
	// 			echo '<input type="time" name="'.$campo.'" class="datos" id="'.$campo.'" value=""/>';
	// 		echo '</td>';
	// 	break;
		
	// 	case 'auto-fecha':
	// 		echo '<td>';
	// 			echo '<input type="text" name="'.$campo.'" class="datos" id="'.$campo.'" value="'.date("Y-m-d").'" readonly/>';
	// 		echo '</td>';
	// 	break;
		
	// 	case 'serial-por-linea':
	// 		echo '<td>';
	// 			if(empty($rows[$campo]))
	// 			{
	// 			echo '<select id="refrescado" class="datos">';
	// 				echo '<option value="1">Parque Serenidad</option>';
	// 				echo '<option value="2">Memorial</option>';
	// 			echo '</select>';
	// 			$tabla_y_campo = explode("-", $opciones[$f]);
	// 			$tabla_seleccion = $tabla_y_campo[0];
	// 			$campo_objetivo = $tabla_y_campo[1];
	// 			$campo_seleccion = $campo;
	// 			include "../funciones/usar-refrescar-serial-script.php";
	// 			echo '<b><label for="Autonumeracion">'.ucfirst("Autonumeracion").'</label></b>';
	// 			echo '<input type="text" name="'.$campo.'" class="datos" id="refrescado-objetivo" value="" readonly/>';
	// 			}
	// 			else
	// 			{
					
	// 				echo '<select class="datos">';
	// 					echo '<option value="1">Parque Serenidad</option>';
	// 					echo '<option value="2">Memorial</option>';
	// 				echo '</select>';
	// 				echo '<br/>';
	// 				echo '<b><label for="Autonumeracion">'.ucfirst("Autonumeracion").'</label></b>';
	// 					echo '<input type="text" name="'.$campo.'" class="datos" id="refrescado-objetivo" value="'.$rows[$campo].'" readonly/>';
	// 			}
	// 		echo '</td>';
	// 	break;
		

		
	// 	case 'vista-refrescada':
	// 		echo '<td>';		
	// 			echo '<input type="text" name="'.$campo.'" class="datos" id="vista-refrescada-input" value="" alignment="right" readonly/>';
	// 		echo '</td>';
	// 	break;
		
	// 	case 'imagen':
	// 		echo '<td>';
	// 			echo '<input type="'.$tipos[$f].'" name="'.$campo.'" class="datos" id="'.$campo.'" value="'.$rows[$campo].'"/><br/>';	
	// 		echo '</td>';
	// 	break;	
		
	// 	case 'hora':
	// 		echo '<td>';
	// 			echo '<input type="'.$tipos[$f].'" name="'.$campo.'" class="datos" id="horaDelPicker_'.$ind.'" value="'.$rows[$campo].'"/><br/>';
	// 		echo '</td>';
	// 	break;								
		
		
	// 	case 'opcion-refrescada':
	// 		echo '<td>';
	// 			$rotulo = "";
	// 			$blanco = "si";
	// 			$todos = "no";
	// 			$tabla_y_campo = explode("-", $opciones[$f]);
	// 			$tabla_seleccion = $tabla_y_campo[0];
	// 			$campo_objetivo = $tabla_y_campo[1];
	// 			$campo_seleccion = $campos[$f];
	// 			$_SESSION['tabla_seleccion'] = $tabla_seleccion;
	// 			$sentido = "ASC";
	// 			include "../funciones/seleccionar-archivos-refrescado.php";
	// 		echo '</td>';
	// 	break;
		
		
	// 	case 'opcion':
	// 		echo '<td>'; 		
	// 			$rotulo = "";
	// 			$blanco = "si";
	// 			$todos = "no";		
	// 			$tabla_seleccion = $opciones[$f];
	// 			$campo_seleccion = $campos[$f];
	// 			$sentido = "ASC";
				
	// 			include "../funciones/seleccionar-archivos.php";
			
	// 		echo '</td>'; 			
	// 	break;
		
	// 	case 'opcion-diferente-1': case 'opcion-diferente-2';
	// 		echo '<td>';			
	// 			$rotulo = "";
	// 			$blanco = "si";
	// 			$todos = "no";
	// 			$tabla_seleccion = $opciones[$f];
	// 			$pluralidad = substr($formatos[$f], -1);
	// 			$campo_poblador = substr($tabla_seleccion, strpos($tabla_seleccion, "_") + 1);
	// 			$campo_poblador = substr($campo_poblador, 0, -($pluralidad));
	// 			$campo_seleccion = $campo_poblador;
	// 			$campo = $campo_poblador;
	// 			$sentido = "ASC";
				
	// 			include "../funciones/seleccionar-archivos.php";
			
	// 		echo '</td>';
	// 	break;
		
	// 	case 'opcion-opcion':
	// 		echo '<td>';
	// 			$rotulo = "";
	// 			$blanco = "si";
	// 			$todos = "no";
	// 			$tablas = explode("-",$opciones[$f]);
	// 			$tabla_seleccion_1 = $tablas[0];
	// 			$campo_seleccion_1 = $tablas[1];
	// 			$tabla_seleccion_2 = $tablas[2];
	// 			$campo_seleccion_2 = $tablas[3];
				
	// 			include "../funciones/seleccionar-archivos-dobles.php";
			
	// 		echo '</td>';			
	// 	break;
		
	// 	case 'boton':		
	// 		echo '<td>'; 		
	// 			$rotulo = "";
			
	// 			include "../funciones/seleccionar-botones.php";
			
	// 		echo '</td>'; 			
	// 	break;
		
	// 	case 'constante':
	// 		echo '<td id="td_'.$campo.'" class="td_columna2">';
	// 			$ValDefault = $opciones[$f];
	// 			echo '<input type="'.$tipos[$f].'" name="'.$campo.'" class="datos" id="'.$campo.'" value="'.$ValDefault.'" readonly/>';
	// 		echo '</td>'; 			
	// 	break;
		
	// 	case 'boton-creador':
	// 		echo '<td>';		
	// 			$rotulo = "";
			
	// 			include "../funciones/seleccionar-botones-creador.php";
			
	// 		echo '</td>';			
	// 	break;
				
?>
