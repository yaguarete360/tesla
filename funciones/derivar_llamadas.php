<?php if (!isset($_SESSION)) {session_start();}

$fecha_de_hoy = date_create(date('Y-m-d'));
date_sub($fecha_de_hoy, date_interval_create_from_date_string("7 days"));
$fecha_anterior = date_format($fecha_de_hoy,"Y-m-d");
$fecha_de_hoy = date('Y-m-d');

$campos_a_mostrar = array('fecha', 'llamada', 'difunto', 'modo', 'contrato_prepago', 'cooperativa_o_asociacion', 'persona_que_llama', 'persona_que_llama_es_titular');

if(!isset($_POST['derivar']))
{
	$consulta_busqueda = 'SELECT * FROM llamadas
		WHERE borrado LIKE "no"
		AND motivo LIKE "primera llamada"
		AND derivado LIKE "no"
		AND fecha BETWEEN "'.$fecha_anterior.' 00:00:00" AND "'.$fecha_de_hoy.' 23:59:59"
		ORDER BY fecha ASC';
	$query_busqueda = $conexion->prepare($consulta_busqueda);
	$query_busqueda->execute();

	echo '<form id="form_derivacion" action="" method="post">';
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
						echo '<input type="radio" name="llamada_a_elegir" value="'.$rows_b['id'].'_'.$rows_b['llamada'].'" required>';
					echo '</td>';
				echo '</tr>';
			}
			echo '<tr><td>&nbsp</td></tr>';
			echo '<tr style="background-color:#58453D;">';
				echo '<td style="'.$estilo_del_td.'text-align:right;color:white;letter-spacing:1px;">';
					echo "<h4>Derivar a:</h4>";
				echo '</td>';
				echo '<td style="'.$estilo_del_td.'">';
					echo '<select id="select_de_tipo" name="tipos_de_servicios" required>';
						$tipos_de_servicios = array('', 'cremacion', 'exhumacion', 'exunilateral', 'inhumacion', 'sepelio', 'traslado');
						asort($tipos_de_servicios);
						foreach ($tipos_de_servicios as $pos => $servicio) echo '<option value="'.$servicio.'">'.ucwords($servicio).'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td style="'.$estilo_del_td.'">';
					echo '<select id="select_de_linea" name="linea_de_servicios" required>';
						echo '<option value=""></option>';
						echo '<option value="1">Parque (1)</option>';
						echo '<option value="2">Memorial (2)</option>';
					echo '</select>';
				echo '</td>';
				echo '<td>';
					echo '<input type="submit" style="width:100%;" id="boton_derivar" name="derivar" value="Derivar">';
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	echo '</form>';

}

if(isset($_POST['derivar']))
{
	$campos_a_reutilizar_1 = array('llamada', 'difunto', 'difunto_direccion', 'persona_que_llama_es_titular', 'modo', 'contrato_prepago', 'cooperativa_o_asociacion');

	$datos_llamada_elegida = explode("_", $_POST['llamada_a_elegir']);

	$consulta_llamada_elegida = 'SELECT * FROM llamadas
	WHERE borrado LIKE "no"
	AND id LIKE "'.$datos_llamada_elegida[0].'"
	AND llamada LIKE "'.$datos_llamada_elegida[1].'"
	ORDER BY fecha ASC';
	$query_llamada_elegida = $conexion->prepare($consulta_llamada_elegida);
	$query_llamada_elegida->execute();
	while($rows_l_e = $query_llamada_elegida->fetch(PDO::FETCH_ASSOC))
	{
		foreach ($campos_a_reutilizar_1 as $pos => $campo_nombre)
		{
			if(!empty($rows_l_e[$campo_nombre]))
			{
				switch ($campo_nombre)
				{
					case 'difunto_direccion':
						$campos_a_reinsertar['direccion_calle'] = $rows_l_e[$campo_nombre];
					break;
					case 'persona_que_llama_es_titular':
						if($rows_l_e[$campo_nombre] == "si") $campos_a_reinsertar['titular'] = $rows_l_e['persona_que_llama'];
					break;
					default:
						$campos_a_reinsertar[$campo_nombre] = $rows_l_e[$campo_nombre];
					break;
				}
			}
			else
			{
				$campos_a_reinsertar[$campo_nombre] = "sin datos";
			}
		}
	}

	include '../../vistas/datos/'.$tabla_destino.'.php';

	$campos_a_asignar_valores_nuevos = array(
		'fecha' => date('Y-m-d'),
		'codigo' => '',
		'linea' => '',
		'tipo' =>  $_POST['tipos_de_servicios'],
		'derivado_desde_llamada' =>  'si',
		'concluido' => 'no',
		'oculto' => 'no',
		'usuario' => $_SESSION['usuario_en_sesion'],
		'creado' => date('Y-m-d G:i:s'),
		'borrado' => 'no'
		);

	foreach ($_SESSION['campos'] as $campo_nombre => $campo_atributo)
	{
		if(isset($campos_a_reinsertar[$campo_nombre])) // EL VALOR SE TRAE DE LA LLAMADA
		{
			$insercion_final[$campo_nombre] = $campos_a_reinsertar[$campo_nombre];
		}
		elseif(isset($campos_a_asignar_valores_nuevos[$campo_nombre])) // EL VALOR SE GENERA AL DERIVAR
		{
			switch ($campo_nombre)
			{
				case 'codigo':
					switch ($_POST['tipos_de_servicios'])
					{
						case 'sepelio':
							$siglas_de_servicio = "sds";
						break;

						case 'inhumacion':
							$siglas_de_servicio = "inh";
						break;
						
						case 'traslado':
							$siglas_de_servicio = "sdt";
						break;
						
						case 'cremacion':
							$siglas_de_servicio = "sdc";
						break;
						
						case 'exhumacion':
							$siglas_de_servicio = "exh";
						break;
						
						case 'exunilateral':
							$siglas_de_servicio = "exu";
						break;
						
						default:
							echo "ERROR";
						break;
					}
					$codigo_a_buscar = $_POST['linea_de_servicios']."-".$siglas_de_servicio."-".date('Y').date('m');
					$ultimo_codigo = $codigo_a_buscar."000";
					
					$consulta_de_codigo = 'SELECT * FROM difuntos WHERE borrado LIKE "no" AND codigo LIKE "'.$codigo_a_buscar.'%" ORDER BY codigo DESC LIMIT 1';
					$query_de_codigo = $conexion->prepare($consulta_de_codigo);
    				$query_de_codigo->execute();
    				while($rows_d_c = $query_de_codigo->fetch(PDO::FETCH_ASSOC)) $ultimo_codigo = $rows_d_c['codigo'];
    				$numero_del_servicio = substr($ultimo_codigo, -3);
    				$nuevo_codigo = $codigo_a_buscar.str_pad($numero_del_servicio + 1, 3, "0", STR_PAD_LEFT);
					
					$insercion_final[$campo_nombre] = $nuevo_codigo;
				break;
				
				case 'linea':
					$insercion_final[$campo_nombre] = $_POST['linea_de_servicios'];
				break;
				
				default:
					$insercion_final[$campo_nombre] = $campos_a_asignar_valores_nuevos[$campo_nombre];
				break;
			}
		}
		elseif(isset($campo_atributo['valido_en']))
		{
			$valido_en = explode("-", strtolower($campo_atributo['valido_en']));
			if(in_array(strtolower($_POST['tipos_de_servicios']), $valido_en))
			{
				$insercion_final[$campo_nombre] = "sin datos";
			}
			else
			{
				$insercion_final[$campo_nombre] = "no aplicable";
			}
		}
		else
		{
			$insercion_final[$campo_nombre] = "sin datos";
		}
	}

	$consulta_insercion = 'INSERT INTO difuntos (';
	foreach ($insercion_final as $campo_nombre => $valor) $consulta_insercion.= $campo_nombre.', ';
	$consulta_insercion = rtrim($consulta_insercion, ', ').') VALUES (';
	foreach ($insercion_final as $campo_nombre => $valor) $consulta_insercion.= '"'.$valor.'", ';
	$consulta_insercion = rtrim($consulta_insercion, ', ').')';
	$query_insercion = $conexion->prepare($consulta_insercion);
    $query_insercion->execute();

    $consulta_actualizacion_derivado = 'UPDATE llamadas SET derivado = "si" 
    	WHERE borrado LIKE "no" 
    	AND id LIKE "'.$datos_llamada_elegida[0].'" 
    	AND llamada LIKE "'.$datos_llamada_elegida[1].'"';
	$query_actualizacion_derivado = $conexion->prepare($consulta_actualizacion_derivado);
    $query_actualizacion_derivado->execute();

}

?>

<script>

	$('input[type=radio]').click(function()
    {
    	$('.fila_interna').css('background-color', 'initial');
    	$(this).closest('tr').css('background-color', '#F7F2E8');
    });

	$('#form_derivacion').submit(function()
	{
		var tipo_seleccionado = $('#select_de_tipo').val();
		var llamada_elegida = $('input[name=llamada_a_elegir]:checked', '#form_derivacion').val()
		var llamada_elegida = llamada_elegida.substr(llamada_elegida.indexOf("_") + 1)

		var confirmado = confirm("Va a derivar la llamada seleccionada "+llamada_elegida+" a un "+tipo_seleccionado+".\n\nDesea continuar?");
		return confirmado;
	});

</script>
