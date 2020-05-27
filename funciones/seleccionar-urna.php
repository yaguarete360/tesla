<?php if(!isset($_SESSION)) {session_start();}

	$urna_datos['sin datos']['status'] = "sin datos";
	$urna_datos['sin datos']['entrada_deposito'] = "sin datos";
	$urna_datos['sin datos']['compuesto'] = "sin datos";

	// $consulta_seleccion = 'SELECT *
	// FROM agrupadores
	// WHERE borrado LIKE "no"
	// AND descripcion LIKE "%urna%"
 //    ORDER BY id
	// DESC'
	// ;

	$consulta_seleccion = 'SELECT *
		FROM feretros
			WHERE borrado LIKE "no"
			AND feretro LIKE "%urna%"
			AND status LIKE "en zona 10 deposito mariscal lopez"
	    ORDER BY id
		DESC'
	;
	$query_seleccion = $conexion->prepare($consulta_seleccion);
	$query_seleccion->execute();
	while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
	{
		$urna_datos[$rows_seleccion['serie']]['status'] = $rows_seleccion['status'];
		$urna_datos[$rows_seleccion['serie']]['entrada_deposito'] = $rows_seleccion['entrada_deposito'];
		$urna_datos[$rows_seleccion['serie']]['compuesto'] = $rows_seleccion['compuesto'];
	}

	$urnas_series = array_keys($urna_datos);

			echo '<select name="'.$campo_nombre.'" id="'.$campo_nombre.'" value="'.$valor.'"/>';
				foreach ($urna_datos as $urna_serie => $urna_dato)
				{
					if(isset($valor) and $valor == $urna_serie)
					{
						echo '<option value="'.strtolower($urna_serie).'" selected>'.strtoupper($urna_serie).'</option>';
						$posicion_default = $urna_serie;
					}
					else
					{
						echo '<option value="'.strtolower($urna_serie).'">'.strtoupper($urna_serie).'</option>';
					}
				}
			echo '</select>';
        echo '</td>';
    echo '</tr>';
	if(!isset($posicion_default)) $posicion_default = $urnas_series[0];

    echo '<tr>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td class="td_columna2" '.$estilo_del_td.'>';
            echo "<b>Urna Status</b>";
			echo '<input type="text" id="urna_status" value="'.$urna_datos[$posicion_default]['status'].'" readonly>';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td class="td_columna2" '.$estilo_del_td.'>';
            echo "<b>Urna Fecha Deposito</b>";
			echo '<input type="text" id="urna_entrada_deposito" value="'.$urna_datos[$posicion_default]['entrada_deposito'].'" readonly>';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';
        echo '<td '.$estilo_del_td.'>';
        echo '</td>';
        echo '<td class="td_columna2" '.$estilo_del_td.'>';
            echo "<b>Urna Compuesto</b>";
			echo '<input type="text" id="urna_compuesto" value="'.$urna_datos[$posicion_default]['compuesto'].'" readonly>';
        echo '</td>';
    echo '</tr>';
    echo '<tr>';

?>

<script type="text/javascript">

	$(document).ready(function()
	{
		var urna_datos = '<?php echo json_encode($urna_datos); ?>';
		var urna_datos = JSON.parse(urna_datos);

		document.getElementById("urna_serie").onchange=function(){
			seleccion_de_serie = document.getElementById("urna_serie").value;
			document.getElementById("urna_status").value = urna_datos[seleccion_de_serie]['status'];
			document.getElementById("urna_entrada_deposito").value = urna_datos[seleccion_de_serie]['entrada_deposito'];
			document.getElementById("urna_compuesto").value = urna_datos[seleccion_de_serie]['compuesto'];
		};
	});

</script>
