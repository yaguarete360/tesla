<?php if(!isset($_SESSION)) {session_start();}

if(isset($campo_atributo['herramientas']))
{
	$variables_oe = explode("-",$campo_atributo['herramientas']);
	$tabla_seleccion = $variables_oe[0];
	$campo_seleccion = $variables_oe[1];			
	$cantidad_oe = count($variables_oe);
	($cantidad_oe == 3) ? $grupo_seleccion = $variables_oe[2]: $grupo_seleccion = "";
}

if($rotulo != "") echo '<b><label for="'.$variable_nombre.'">'.ucfirst($rotulo).'</label></b>';

$nombre  = 0;
$ancho   = 1;
$mostrar = 2;
$formato = 3;
$decimal = 4;
$asistente = 5;
$filtro  = 6;

if(!isset($sentido)) $sentido = "ASC";

if(isset($filtro_ano_mes))
{
	$_SESSION['ano'] = $ano;
	if(isset($mes))
	{		
		
		$_SESSION['mes'] = $mes;
		$consulta_seleccion = 'SELECT * 
		FROM '.$tabla_seleccion.' 
		WHERE borrado LIKE "no" 
		AND fecha LIKE "'.$ano.'-'.$mes.'%" 
		ORDER BY '.$campo_seleccion.' '.
		$sentido;

	}
	else
	{
		
		$consulta_seleccion = 'SELECT * 
		FROM '.$tabla_seleccion.' 
		WHERE borrado LIKE "no" 
		AND fecha LIKE "'.$ano.'%" 
		ORDER BY '.$campo_seleccion.' '.
		$sentido;

	}
}
else
{

	if(isset($campo_atributo['asistente']) and $campo_atributo['asistente'] == "opcion-segun-tipo")
	{
		switch (strtolower($vista_tipo))
		{
			case 'cremacion':
				$centroCodigo = "sdc";
			break;
			case 'sepelio':
				$centroCodigo = "sds";
			break;
			case 'exhumacion':
				$centroCodigo = "exh";
			break;
			case 'exunilateral':
				$centroCodigo = "exu";
			break;
			case 'inhumacion':
				$centroCodigo = "inh";
			break;
			case 'traslado':
				$centroCodigo = "sdt";
			break;

			default:
				$centroCodigo = "";
			break;
		}

		$consulta_seleccion = 'SELECT * 
		FROM '.$tabla_seleccion.' 
		WHERE borrado LIKE "no" 
		AND descripcion LIKE "'.$centroCodigo.'%"
		GROUP BY '.$campo_seleccion.'
		ORDER BY '.$campo_seleccion.'
		'.$sentido;

	}
	else
	{
		if(isset($grupo_seleccion) and !empty($grupo_seleccion))
		{
			
			$consulta_seleccion = 'SELECT * 
			FROM '.$tabla_seleccion.' 
			WHERE borrado LIKE "no" 
			AND tipo = "'.$grupo_seleccion.'"
			GROUP BY '.$campo_seleccion.' 
			ORDER BY '.$campo_seleccion.' 
			'.$sentido;

		}
		else
		{		
	     	if(count($variables_oe) > 3)
			{
			  if(count($variables_oe) == 4)
				{
					$campo_seleccion = $variables_oe[1];
					$consulta_seleccion = 'SELECT '.$variables_oe[1].','.$variables_oe[2].', borrado
					FROM '.$variables_oe[0].' 
					WHERE borrado LIKE "no" 
					AND '.$variables_oe[2].' LIKE "'.$variables_oe[3].'"
					GROUP BY '.$campo_seleccion.' 
					ORDER BY '.$campo_seleccion.' 
					'.$sentido;

				}elseif(count($variables_oe) == 5){

				  $filter = $variables_oe[4];

					$campo_seleccion = $variables_oe[1];
					$consulta_seleccion = 'SELECT '.$variables_oe[1].','.$variables_oe[2].', borrado
					FROM '.$variables_oe[0].' 
					WHERE borrado LIKE "no" 
					AND '.$variables_oe[2].' LIKE "'.$variables_oe[3].'"
					AND '.$variables_oe[1].' LIKE "%'.$filter.'%" GROUP BY '.$campo_seleccion.'
					ORDER BY '.$campo_seleccion.' 
					'.$sentido;
				}
			}
			else
			{
			    if($tabla_seleccion == "cuentas")
			    {
			        $grupo_seleccion = $campo_seleccion;
			        $campo_seleccion = "cuenta";
			        
			        $consulta_seleccion = 'SELECT * 
        				FROM '.$tabla_seleccion.' 
        				WHERE borrado LIKE "no" 
        				AND '.$grupo_seleccion.' NOT LIKE "0"
        				GROUP BY '.$campo_seleccion.' 
        				ORDER BY '.$campo_seleccion.' 
        				'.$sentido;
			    }
			    else
			    {
    				$consulta_seleccion = 'SELECT * 
        				FROM '.$tabla_seleccion.' 
        				WHERE borrado LIKE "no"
        				GROUP BY '.$campo_seleccion.' 
        				ORDER BY '.$campo_seleccion.' 
        				'.$sentido;
			    }
			}
		}
	}
}

$query_seleccion = $conexion->prepare($consulta_seleccion);
$query_seleccion->execute();

echo '<select name="'.$variable_nombre.'" id="'.$variable_nombre.'" value="'.$valor.'"/>';	

	if($sin_datos === "si") echo '<option value="sin datos">Sin Datos</option>';
	if($blanco === "si") echo '<option value=""></option>';		
	if($todos === "si") echo '<option value="todos">Todos</option>';
	if(isset($opcion_no_se_uso) and $opcion_no_se_uso === "si") echo '<option value="no se uso">No Se Uso</option>';
	
	while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
	{
		if($rows_seleccion[$campo_seleccion] === $valor)
		{
			if(!empty($rows_seleccion[$campo_seleccion]) or trim($rows_seleccion[$campo_seleccion]) != "")
			{
				echo '<option value="'.$rows_seleccion[$campo_seleccion].'"selected>'.ucwords($rows_seleccion[$campo_seleccion]).'</option>';
			}
		}
		else
		{
			if(!empty($rows_seleccion[$campo_seleccion]) or trim($rows_seleccion[$campo_seleccion]) != "")
			{
				echo '<option value="'.$rows_seleccion[$campo_seleccion].'">'.ucwords($rows_seleccion[$campo_seleccion]).'</option>';
			}
		}
	}	
echo '</select>';

?>
