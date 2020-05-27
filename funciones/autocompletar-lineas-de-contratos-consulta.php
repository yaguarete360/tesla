<?php if (!isset($_SESSION)) {session_start();}

include '../funciones/conectar-base-de-datos.php';

$cuenta_a_buscar = $_POST['datos_para_el_query'];
$contrato_var = '1-var-0000000';
$items_varios = array();
$cuotas_a_mover_al_comienzo = array();

$efectuados_por_a_evitar = array();
// $consulta_efectuado_por_a_evitar = 'SELECT descripcion FROM agrupadores WHERE borrado = "no" AND agrupador IN ("procesadoras debitos automaticos")';
// $query_efectuado_por_a_evitar = $conexion->prepare($consulta_efectuado_por_a_evitar);
// $query_efectuado_por_a_evitar->execute();
// while($rows_efectuado_por_a_evitar = $query_efectuado_por_a_evitar->fetch(PDO::FETCH_ASSOC))
// {
// 	$efectuados_por_a_evitar[] = $rows_efectuado_por_a_evitar['descripcion'];
// }

// $consulta_efectuado_por_a_evitar = 'SELECT cuenta FROM cuentas WHERE borrado = "no" AND asociacion > 0';
// $query_efectuado_por_a_evitar = $conexion->prepare($consulta_efectuado_por_a_evitar);
// $query_efectuado_por_a_evitar->execute();
// while($rows_efectuado_por_a_evitar = $query_efectuado_por_a_evitar->fetch(PDO::FETCH_ASSOC))
// {
// 	$efectuados_por_a_evitar[] = $rows_efectuado_por_a_evitar['cuenta'];
// }

// $consulta_descripciones_posibles = 'SELECT descripcion FROM agrupadores WHERE borrado = "no" AND agrupador IN ("descripcion de cuotas especiales", "items varios cobrables")';
// $query_descripciones_posibles = $conexion->prepare($consulta_descripciones_posibles);
// $query_descripciones_posibles->execute();
// while($rows_descripciones_posibles = $query_descripciones_posibles->fetch(PDO::FETCH_ASSOC))
// {
// 	$descripciones_posibles_en_diario[] = $rows_descripciones_posibles['descripcion'];
// }

	    // AND documento_tipo LIKE "contrato%"
    	// AND (descripcion LIKE "cuota%"
    	// 	OR descripcion IN ("'.implode('", "', $descripciones_posibles_en_diario).'")
    	// 	)
$consulta_autocompletar = 'SELECT id, contrato, descripcion, cuota, obligacion, derecho, cuota_vencimiento, efectuado_fecha, observacion FROM diario
    WHERE borrado = "no"
    	AND derecho > 0
    	AND contrato NOT IN ("sin datos", "no aplicable", "")
	    AND efectuado_fecha = "0000-00-00"
	    AND descripcion NOT LIKE "comision%"
	    AND descripcion NOT LIKE "descuento%"
	    AND descripcion NOT LIKE "%mora%"
	    AND cuenta = "'.$cuenta_a_buscar.'"
    ORDER BY contrato, cuota_vencimiento, cuota, id ASC';
$query_autocompletar = $conexion->prepare($consulta_autocompletar);
$query_autocompletar->execute();

if($_SESSION['alias_en_sesion'] == 'admin') echo $consulta_autocompletar;

while($rows_autocompletar = $query_autocompletar->fetch(PDO::FETCH_ASSOC))
{
	$id = $rows_autocompletar['id'];
	$contrato = $rows_autocompletar['contrato'];
	$esta_cuota = $rows_autocompletar['cuota'].'_'.$rows_autocompletar['cuota_vencimiento'];
	$descripcion = $rows_autocompletar['descripcion'];
	$efectuado_fecha = $rows_autocompletar['efectuado_fecha'];
	$tipo_de_cuota = (is_numeric(explode(' ', $esta_cuota)[0]));

	// if($contrato == '1-cms-0012422' or $contrato == '1-uds-0012422') echo $contrato.' - '.$esta_cuota.'<br/>';

    if(strpos($descripcion, 'mora') !== false)
    {
		if(!isset($contratos_moras[$contrato][$esta_cuota]['derecho'])) $contratos_moras[$contrato][$esta_cuota]['derecho'] = 0;
		if(!isset($contratos_moras[$contrato][$esta_cuota]['obligacion'])) $contratos_moras[$contrato][$esta_cuota]['obligacion'] = 0;

    	$contratos_moras[$contrato][$esta_cuota]['efectuado_fecha'] = $efectuado_fecha;
		$contratos_moras[$contrato][$esta_cuota]['derecho']+= $rows_autocompletar['derecho'];
		$contratos_moras[$contrato][$esta_cuota]['obligacion']+= $rows_autocompletar['obligacion'];
    }
    else
    {
    	if(strpos($contrato, '-var-') !== false)
    	{
    		$contrato_var = $contrato;
    	}
    	else
    	{
    		$pagos_de_la_cuota = 0;
    		$efectuado_fecha_del_ultimo_pago = '0000-00-00';
    		$efectuado_por_del_ultimo_pago = '';

		    	// AND efectuado_fecha != "0000-00-00"
			    // AND documento_tipo LIKE "contrato%"
			    // AND descripcion NOT LIKE "comision%"
			    // AND descripcion NOT LIKE "descuento%"
			    // AND descripcion NOT LIKE "cobranza de la mora%"
			   
			   //  	AND ((efectuado_por NOT IN ("'.implode('", "', $efectuados_por_a_evitar).'")
			   //  			AND efectuado_fecha = "0000-00-00")
						// OR
			   //  		)
		    	// AND descripcion NOT LIKE "%mora%"

		    // AND cuenta = "'.$cuenta_a_buscar.'"
    		$consulta_pagos_de_la_cuota = 'SELECT SUM(obligacion) AS suma_obligacion, efectuado_fecha, efectuado_por FROM diario
			    WHERE borrado = "no"
			    	AND descripcion LIKE "cobranza de%"
			    	AND descripcion NOT LIKE "cobranza de la mora%"
			    	AND obligacion > 0
				    AND contrato = "'.$contrato.'"
				    AND cuota = "'.(explode('_', $esta_cuota)[0]).'"
				    AND cuota_vencimiento = "'.(explode('_', $esta_cuota)[1]).'"
			    ORDER BY efectuado_fecha ASC';
		    if($_SESSION['alias_en_sesion'] == 'admin') echo '<br/>'.$consulta_pagos_de_la_cuota.'<br/>';
			$query_pagos_de_la_cuota = $conexion->prepare($consulta_pagos_de_la_cuota);
			$query_pagos_de_la_cuota->execute();
			while($rows_pagos_de_la_cuota = $query_pagos_de_la_cuota->fetch(PDO::FETCH_ASSOC))
			{
				$pagos_de_la_cuota = $rows_pagos_de_la_cuota['suma_obligacion'];
				$efectuado_fecha_del_ultimo_pago = $rows_pagos_de_la_cuota['efectuado_fecha'];
				$efectuado_por_del_ultimo_pago = $rows_pagos_de_la_cuota['efectuado_por'];
			}

		    if(!isset($primera_cuota[$contrato])) $primera_cuota[$contrato] = $esta_cuota;
		    if(!isset($vencimientos_para_detalle[$contrato]['inicial'])) $vencimientos_para_detalle[$contrato]['inicial'] = $rows_autocompletar['cuota_vencimiento'];
		    $vencimientos_para_detalle[$contrato]['final'] = $rows_autocompletar['cuota_vencimiento'];

			if(!isset($contratos[$contrato][$esta_cuota]['derecho'])) $contratos[$contrato][$esta_cuota]['derecho'] = 0;
			if(!isset($contratos[$contrato][$esta_cuota]['obligacion'])) $contratos[$contrato][$esta_cuota]['obligacion'] = 0;

	    	$contratos[$contrato][$esta_cuota]['id'] = $id;
	    	// $contratos[$contrato][$esta_cuota]['efectuado_fecha'] = $efectuado_fecha;
	    	$contratos[$contrato][$esta_cuota]['efectuado_fecha'] = $efectuado_fecha_del_ultimo_pago;
			$contratos[$contrato][$esta_cuota]['derecho']+= $rows_autocompletar['derecho'];
			// $contratos[$contrato][$esta_cuota]['obligacion']+= $rows_autocompletar['obligacion'];
			$contratos[$contrato][$esta_cuota]['obligacion']+= $pagos_de_la_cuota;
			$a_pagar_de_la_cuota = $contratos[$contrato][$esta_cuota]['derecho'] - $contratos[$contrato][$esta_cuota]['obligacion'];

			$contratos[$contrato][$esta_cuota]['observacion'] = ($rows_autocompletar['observacion'] != 'sin datos' and $rows_autocompletar['observacion'] != 'no aplicabe') ? $rows_autocompletar['observacion'] : '';
			
			// DEBUGGER
			// echo $contrato.' -> '.$primera_cuota[$contrato].' vs '.$esta_cuota.' => '.$contratos[$contrato][$esta_cuota]['derecho'].' - '.$contratos[$contrato][$esta_cuota]['obligacion'].' = '.$a_pagar_de_la_cuota.' count='.count($contratos[$contrato]);
			// echo '<br/>';
			// $booltest = ($primera_cuota[$contrato] == $esta_cuota and $a_pagar_de_la_cuota == 0 and count($contratos[$contrato]) <= 1);
			// var_dump($booltest);
			// echo '<br/>';

			if($primera_cuota[$contrato] == $esta_cuota and $a_pagar_de_la_cuota == 0 and count($contratos[$contrato]) <= 1)
			{
				if($contratos[$contrato][$esta_cuota]['efectuado_fecha'] == '0000-00-00')
				{
					$contratos[$contrato][$esta_cuota]['observacion'].= '* VERIFICAR PENDIENTE '.$efectuado_por_del_ultimo_pago;
				}
				else
				{
					unset($contratos[$contrato][$esta_cuota]);
					unset($primera_cuota[$contrato]);
				}
			}
			if(!$tipo_de_cuota) $cuotas_a_mover_al_comienzo[$contrato][$esta_cuota] = $contratos[$contrato][$esta_cuota];
    	}
    }
}

 // ----------------------------------------------------------------------------------------------------

$i_items_varios = 0;
$datos_para_items_varios = array('descripcion' => 'nombre', 'dato_1' => 'monto');
$consulta_items_varios = 'SELECT * FROM agrupadores
    WHERE borrado = "no"
    AND agrupador = "items varios cobrables"
    ORDER BY descripcion ASC';
$query_items_varios = $conexion->prepare($consulta_items_varios);
$query_items_varios->execute();
while($rows_items_varios = $query_items_varios->fetch(PDO::FETCH_ASSOC))
{
	foreach ($datos_para_items_varios as $campo_origen => $campo_dato) $items_varios[$i_items_varios][$campo_dato] = $rows_items_varios[$campo_origen];
	$i_items_varios++;
}

echo '<table style="float:left; width:45%; margin:5px; border: 1px solid #D8A262; border-collapse:collapse; background-color:#faf3ea; white-space:nowrap;">';
	echo '<tr>';
		echo '<td colspan="5" style="text-align:center;border-bottom: 1px solid #D8A262;">';
			echo '<h5>'.$contrato_var.'</h5>';
			echo '<input type="hidden" class="'.$contrato_var.'-iva" name="'.$contrato_var.'-iva" value="10">';
			echo '<a href="../reportes/contratos-ficha.php?cu='.str_replace(',', '_', $cuenta_a_buscar).'&co='.$contrato_var.'" target="_blank">';
				echo '<img src="../../imagenes/iconos/boton-ver-documentos.png" alt="Ver Estado Ficha de Contrato" width="20" height="20" style="margin-top:-15px;">';
			echo '</a>';
		echo '</td>';
	echo '</tr>';
	if(!isset($estilo_del_td_cuotas)) $estilo_del_td_cuotas = '';
	foreach ($items_varios as $item_varios_datos)
	{
		echo '<tr>';
			echo '<td style="'.$estilo_del_td_cuotas.'">';
				echo $item_varios_datos['nombre'];
			echo '</td>';
			echo '<td style="'.$estilo_del_td_cuotas.'text-align:right;">';
				echo number_format($item_varios_datos['monto']);
			echo '</td>';
			echo '<td style="'.$estilo_del_td_cuotas.'text-align:right;">';
				echo '<input type="checkbox" class="botones_a_cobrar '.$contrato_var.'" name="a_cobrar['.$contrato_var.'][01 de 01_'.date('Y-m-d').'_'.$item_varios_datos['nombre'].']" value="'.$item_varios_datos['monto'].'">';
			echo '</td>';
		echo '<tr>';
	}
echo '</table>';

// ----------------------------------------------------------------------------------------------------

if(isset($contratos) and !empty($contratos))
{
	foreach ($cuotas_a_mover_al_comienzo as $contrato => $cuota_a_mover)
	{
		foreach ($cuota_a_mover as $esta_cuota => $cuota_array_a_mover)
		{
			$contratos[$contrato] = array($esta_cuota => $cuota_array_a_mover) + $contratos[$contrato];
		}
	}

	$iva_de_este_contrato = 10;
	$forma_de_pago = '';

	// $i_items_varios = 0;
	// $datos_para_items_varios = array('descripcion' => 'nombre', 'dato_1' => 'monto');
	// $consulta_items_varios = 'SELECT * FROM agrupadores
	//     WHERE borrado = "no"
	//     AND agrupador = "items varios cobrables"
	//     ORDER BY descripcion ASC';
	// $query_items_varios = $conexion->prepare($consulta_items_varios);
	// $query_items_varios->execute();
	// while($rows_items_varios = $query_items_varios->fetch(PDO::FETCH_ASSOC))
	// {
	// 	foreach ($datos_para_items_varios as $campo_origen => $campo_dato) $items_varios[$i_items_varios][$campo_dato] = $rows_items_varios[$campo_origen];
	// 	$i_items_varios++;
	// }

	// echo '<table style="float:left; width:45%; margin:5px; border: 1px solid #D8A262; border-collapse:collapse; background-color:#faf3ea; white-space:nowrap;">';
	// 	echo '<tr>';
	// 		echo '<td colspan="5" style="text-align:center;border-bottom: 1px solid #D8A262;">';
 //    			echo '<h5>'.$contrato_var.'</h5>';
 //    			echo '<input type="hidden" class="'.$contrato_var.'-iva" name="'.$contrato_var.'-iva" value="10">';
	// 			echo '<a href="../reportes/contratos-ficha.php?cu='.str_replace(',', '_', $cuenta_a_buscar).'&co='.$contrato_var.'" target="_blank">';
	// 				echo '<img src="../../imagenes/iconos/boton-ver-documentos.png" alt="Ver Estado Ficha de Contrato" width="20" height="20" style="margin-top:-15px;">';
	// 			echo '</a>';
	// 		echo '</td>';
	// 	echo '</tr>';
	// 	if(!isset($estilo_del_td_cuotas)) $estilo_del_td_cuotas = '';
	// 	foreach ($items_varios as $item_varios_datos)
	// 	{
	// 		echo '<tr>';
	// 			echo '<td style="'.$estilo_del_td_cuotas.'">';
	// 				echo $item_varios_datos['nombre'];
	// 			echo '</td>';
	// 			echo '<td style="'.$estilo_del_td_cuotas.'text-align:right;">';
	// 				echo number_format($item_varios_datos['monto']);
	// 			echo '</td>';
	// 			echo '<td style="'.$estilo_del_td_cuotas.'text-align:right;">';
	// 				echo '<input type="checkbox" class="botones_a_cobrar '.$contrato_var.'" name="a_cobrar['.$contrato_var.'][01 de 01_'.date('Y-m-d').'_'.$item_varios_datos['nombre'].']" value="'.$item_varios_datos['monto'].'">';
	// 			echo '</td>';
	// 		echo '<tr>';
	// 	}
	// echo '</table>';
	
	foreach ($contratos as $contrato => $cuotas)
	{
		$contrato_explotado = explode("-", $contrato);
		$consulta_venta_producto = 'SELECT producto, forma_de_pago FROM contratos
		    WHERE borrado = "no"
		    	AND contrato = "'.$contrato.'"
		    LIMIT 1';
		$query_venta_producto = $conexion->prepare($consulta_venta_producto);
		$query_venta_producto->execute();
		while($rows_venta_producto = $query_venta_producto->fetch(PDO::FETCH_ASSOC))
		{
			$forma_de_pago = $rows_venta_producto['forma_de_pago'];
			$consulta_producto_iva = 'SELECT dato_6 FROM agrupadores WHERE borrado = "no" AND agrupador = "productos" AND descripcion = "'.$rows_venta_producto['producto'].'" LIMIT 1';
			$query_producto_iva = $conexion->prepare($consulta_producto_iva);
			$query_producto_iva->execute();
			while($rows_producto_iva = $query_producto_iva->fetch(PDO::FETCH_ASSOC)) $iva_de_este_contrato = $rows_producto_iva['dato_6'];
		}

		$estilo_del_td_cuotas = "padding-right:5px;padding-left:5px;";
		echo '<table style="float:left; width:45%; margin:5px; border: 1px solid #D8A262; border-collapse:collapse; background-color:#faf3ea; white-space:nowrap;">';
			echo '<tr>';
				echo '<td colspan="5" style="text-align:center;border-bottom: 1px solid #D8A262;">';
	    			echo '<h5>'.$contrato.'</h5>';
	    			echo '<input type="hidden" class="'.$contrato.'-iva" name="'.$contrato.'-iva" value="'.$iva_de_este_contrato.'">';
					echo '<a href="../reportes/contratos-ficha.php?cu='.str_replace(',', '_', $cuenta_a_buscar).'&co='.$contrato.'" target="_blank">';
						echo '<img src="../../imagenes/iconos/boton-ver-documentos.png" alt="Ver Estado Ficha de Contrato" width="20" height="20" style="margin-top:-15px;">';
					echo '</a>';
				echo '</td>';
			echo '</tr>';
			$deshabilitado = '';
			foreach ($cuotas as $cuota => $datos)
			{
				$cuota_explotada = explode('_', $cuota);
				$cuota = $cuota_explotada[0];
				$datos['cuota_vencimiento'] = $cuota_explotada[1];
				$tiene_mora = "no";
				$monto_mora = 0;
				echo '<tr>';
					// echo '<td style="'.$estilo_del_td_cuotas.'text-align:right;">';
		   //  			echo $datos['id'];
					// echo '</td>';
					echo '<td style="'.$estilo_del_td_cuotas.'text-align:right;">';
		    			echo $cuota;
					echo '</td>';
					echo '<td style="'.$estilo_del_td_cuotas.'text-align:right;">';
	    				$a_pagar_de_la_cuota = $datos['derecho'] - $datos['obligacion'];
		    			echo number_format(($a_pagar_de_la_cuota == 0) ? $datos['derecho'] : $a_pagar_de_la_cuota);
					echo '</td>';
					echo '<td style="'.$estilo_del_td_cuotas.'">';
						if($a_pagar_de_la_cuota > 0)
		    			{
		    				echo '<input type="checkbox" class="botones_a_cobrar '.str_replace(" ", "_", $contrato).'" name="a_cobrar['.$contrato.']['.$cuota.'_'.$datos['cuota_vencimiento'].']" value="'.$a_pagar_de_la_cuota.'" '.$deshabilitado.'>';
		    				$deshabilitado = 'disabled';
		    			}
					echo '</td>';
					echo '<td style="'.$estilo_del_td_cuotas.'">';
						if($datos['efectuado_fecha'] != 0 and $a_pagar_de_la_cuota == 0)
		    			{
		    				echo "Cobrado: ".$datos['efectuado_fecha'];
		    			}
		    			else
		    			{
		    				echo "Vencimiento: ".$datos['cuota_vencimiento'];
		    				
		    				if($a_pagar_de_la_cuota > 0 and $a_pagar_de_la_cuota != $datos['derecho'])
		    				{
			    				echo '<br/>';
			    				echo "Cobrado Parcialmente el: ".$datos['efectuado_fecha'];
		    				}
		    				if(strtotime($datos['cuota_vencimiento']) < strtotime(date('Y-m-d'))) $tiene_mora = "si";
		    			}
					echo '</td>';
					echo '<td>';
			 			echo $datos['observacion'];
					echo '</td>';
				echo '</tr>';
				$centro_del_contrato = $contrato_explotado[1];
				$mora_parcial = 0;
				if(isset($contratos_moras[$contrato][$cuota])) $mora_parcial = $contratos_moras[$contrato][$cuota]['derecho'] - $contratos_moras[$contrato][$cuota]['obligacion'];

				if($tiene_mora == "si" or $mora_parcial > 0)
				{
					$verificar_tipo_de_cuota = (is_numeric(explode(' ', $cuota)[0]));
					if($verificar_tipo_de_cuota)
					{
						if($tiene_mora == "si")
						{
							include '../funciones/calcular-mora.php';
						}
						else
						{
							$monto_mora = $mora_parcial;
						}
						$tipo_input_mora = 'hidden';

						if($monto_mora > 0 or (isset($mora_de_la_cuota) and $mora_de_la_cuota == 'perdida de vigencia'))
						{
							echo '<tr>';
								echo '<td style="'.$estilo_del_td_cuotas.'text-align:right;">';
									echo $cuota;
								echo '</td>';
								echo '<td style="'.$estilo_del_td_cuotas.'text-align:right;">';
									echo '<i>';
										echo number_format($monto_mora);
									echo '</i>';
								echo '</td>';
								echo '<td style="'.$estilo_del_td_cuotas.'">';
									$nombre_de_la_mora = 'a_cobrar_moras['.$contrato.']['.$cuota.'_'.$datos['cuota_vencimiento'].']';
									echo '<input type="'.$tipo_input_mora.'" name="'.$nombre_de_la_mora.'" value="'.$monto_mora.'" >';
								echo '</td>';
								echo '<td style="'.$estilo_del_td_cuotas.'">';
									$mensaje_mora = (isset($mora_de_la_cuota) and $mora_de_la_cuota == 'perdida de vigencia') ? $mora_de_la_cuota : 'Mora';
									echo "<i>* ".$mensaje_mora."</i>";
									if($mora_parcial > 0) echo '<br/><i>Cobrada Parcialmente el: '.$datos['efectuado_fecha'].'</i>';
								echo '</td>';
							echo '</tr>';
						}
					}
				}
			}
		echo '</table>';
		// $contador_de_contratos++;
	}
}
else
{
    echo 'No se encontraron contratos bajo esta cuenta';
}

?>
