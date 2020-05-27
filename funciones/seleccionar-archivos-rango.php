<?php if (!isset($_SESSION)) {session_start();}

$variable_nombre1 = "filtro_campo";
$rotulo1 = "&nbsp &nbsp Filtrar por Campo: &nbsp &nbsp";
$valor = "";

if($rotulo1 != "") echo '<b><label for="'.$variable_nombre1.'">'.ucfirst($rotulo1).'</label></b>';
$x = 0;
$z = 0;

if(!isset($campos))
{
	$campos = $_SESSION['campos'];
}

$verEnGrilla = $_SESSION['ver_en_grillas'];

if(!isset($tabla_a_procesar))
{
	$tabla_a_procesar = $tablaAsistente;
}

if(isset($_POST['ano_a_usar'])) $_SESSION['ano_a_usar'] = $_POST['ano_a_usar'];
if(isset($_POST['mes_a_usar'])) $_SESSION['mes_a_usar'] = $_POST['mes_a_usar'];

$poblaciones = array();
echo '<select class="datos" id="filtro_campo" name="filtro_campo" value="'.$valor.'"/>';
	echo '<option value="todos" selected>Seleccionar un campo..</option>';
	echo '<option value="todos">Todos</option>';
	foreach($campos as $f=>$campo)
	{
		if($verEnGrilla[$f] === "si")
		{
			$campo = trim($campo);
			$campos_a_filtrar[$x] = $campo;
			
			if($campo === "fecha"
				OR $campo === "id" 
				OR $campo === "entra" 
				OR $campo === "sale" 
				OR $campo === "debe" 
				OR $campo === "haber"
				OR $campo === "precio" 
				OR $campo === "salida_km" 
				OR $campo === "entrada_km" 
				OR $campo === "salida_hora" 
				OR $campo === "entrada_hora" 
				OR $campo === "descripcion" 
				OR $campo === "litros" 
				OR $campo === "monto" 
				OR $campo === "cantidad"
				OR $campo === "abc"
				OR $campo === "uh"
				OR $campo === "monto_neto"
				OR $campo === "monto_bruto"
				OR $campo === "iva_monto"
				OR $campo === "clave"
			){
			}
			else
			{	
				echo '<option value="'.$x.'">'.ucfirst($campos_a_filtrar[$x]).'</option>';
				$y = 0;
				
				include "../../funciones/conectar-base-de-datos.php";
				if($tabla_a_procesar === "operaciones_facturas")
				{
					$consulta_poblacion = 'SELECT '.$campo.'
					FROM '.$tabla_a_procesar.'
					WHERE borrado = "0000-00-00"
					AND fecha LIKE "'.$_POST['ano_a_usar'].'-'.$_POST['mes_a_usar'].'%"
					ORDER BY "'.$campo.'"
					DESC
					';
				}
				else
				{
					$consulta_poblacion = 'SELECT '.$campo.'
					FROM '.$tabla_a_procesar.'
					WHERE borrado = "0000-00-00"
					ORDER BY "'.$campo.'"
					DESC
					';
				}
				$query_poblacion = $conexion->prepare($consulta_poblacion);
				$query_poblacion->execute();
				while($rows_poblacion = $query_poblacion->fetch(PDO::FETCH_ASSOC))
				{
					trim($rows_poblacion[$campo]);
					if(empty($rows_poblacion[$campo]) OR is_null($rows_poblacion[$campo]))
					{
						$poblaciones[$x][$y] = "";
					}
					else
					{
						$poblaciones[$x][$y] = trim($rows_poblacion[$campo]);

						if (strpos($rows_poblacion[$campo], "'") !== FALSE)
						{
							$poblaciones[$x][$y] = str_replace("'", " ", trim($rows_poblacion[$campo]));
						}
						
						if (strpos($rows_poblacion[$campo], "-") !== FALSE AND $campo === "cliente")
						{
							$poblaciones[$x][$y] = str_replace("-", "ñ", trim($rows_poblacion[$campo]));
						}
						
						if (strpos($rows_poblacion[$campo], '"') !== FALSE)
						{
							$poblaciones[$x][$y] = str_replace('"', " ", trim($rows_poblacion[$campo]));
						}

						if (strpos($rows_poblacion[$campo], "¼") !== FALSE)
						{
							$poblaciones[$x][$y] = str_replace("¼", "a.", trim($rows_poblacion[$campo]));
						}
					}
					$z++;
					$y++;
				}
				$x++;
			}
		}
	}
echo '</select>';
echo '<br/>';

$z = 0;

foreach ($poblaciones as $poblacion_nombre => $poblacion)
{
	$poblacion = array_unique($poblacion);
	array_multisort($poblacion);
	$poblaciones[$z] = $poblacion;
	$z++;
}

$poblaciones = json_encode($poblaciones, JSON_FORCE_OBJECT);
$campos_a_filtrar = json_encode($campos_a_filtrar, JSON_FORCE_OBJECT);

$rotulo = "&nbsp &nbsp Desde &nbsp &nbsp";
$variable_nombre = "filtro_desde";
echo '<b><label id="'.$variable_nombre.'" for="'.$variable_nombre.'">'.ucfirst($rotulo).'</label></b>';
echo '<select class="datos" id="filtro_campo1" name="'.$variable_nombre.'" value=""/>';
echo '</select>';

$rotulo = "&nbsp &nbsp Hasta &nbsp &nbsp";
$variable_nombre = "filtro_hasta";
echo '<b><label id="'.$variable_nombre.'" for="'.$variable_nombre.'">'.ucfirst($rotulo).'</label></b>';
echo '<select class="datos" id="filtro_campo2" name="'.$variable_nombre.'" value=""/>';
echo '</select>';
echo '<br/>';

echo '<input type="hidden" id="nuevoAsistente_campo" name="nuevoAsistente_campo" value="" readonly>';
?>

<script>		

var poblaciones = '<?php echo $poblaciones; ?>';
var poblaciones = $.parseJSON(poblaciones);

var campos_a_filtrar = '<?php echo $campos_a_filtrar; ?>';
var campos_a_filtrar = $.parseJSON(campos_a_filtrar);

var filtro_campo1 = document.getElementById("filtro_campo1");
var filtro_campo2 = document.getElementById("filtro_campo2");
var filtro_desde = document.getElementById("filtro_desde");
var filtro_hasta = document.getElementById("filtro_hasta");

$('#filtro_campo').change(function()
	{
		var campoAsistente = this.value;
		
		filtro_campo1.options.length=0;
		filtro_campo2.options.length=0;

		if(campoAsistente == 'todos')
		{
			filtro_campo1.style.display = 'none';
			filtro_campo2.style.display = 'none';
			filtro_desde.style.display = 'none';
			filtro_hasta.style.display = 'none';

			var largoTodosSi = Object.keys(poblaciones[0]).length;
			for (i=0; i < largoTodosSi; i++)
			{
				filtro_campo1.options[filtro_campo1.options.length] = new Option(poblaciones[0][i], poblaciones[0][i]);
				filtro_campo2.options[filtro_campo2.options.length] = new Option(poblaciones[0][i], poblaciones[0][i]);
			}

			filtro_campo1.selectedIndex = 0;
			filtro_campo2.selectedIndex = (largoTodosSi -1);

			document.getElementById("nuevoAsistente_campo").value = campos_a_filtrar[0];
			// document.getElementById("nuevoAsistente").submit();
		}
		else
		{
			filtro_campo1.style.display = 'initial';
			filtro_campo2.style.display = 'initial';
			filtro_desde.style.display = 'initial';
			filtro_hasta.style.display = 'initial';
			
			var largoTodosNo = Object.keys(poblaciones[campoAsistente]).length;
			for (i=0; i < largoTodosNo; i++)
			{
				filtro_campo1.options[filtro_campo1.options.length] = new Option(poblaciones[campoAsistente][i], poblaciones[campoAsistente][i]);
				filtro_campo2.options[filtro_campo2.options.length] = new Option(poblaciones[campoAsistente][i], poblaciones[campoAsistente][i]);
			}

			filtro_campo1.selectedIndex = 0;
			filtro_campo2.selectedIndex = (largoTodosNo -1);

			document.getElementById("nuevoAsistente_campo").value = campos_a_filtrar[campoAsistente];
			// document.getElementById("nuevoAsistente").submit();
		}
	});

</script>
