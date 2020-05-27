<?php if(!isset($_SESSION)) {session_start();}


$herramientas = explode("#",$campo_atributo['herramientas']);
$tabla_seleccion = $herramientas[0];
$campo_seleccion = $herramientas[1];
foreach ($herramientas as $pos => $herramienta)
{
	if($pos > 1)
	{
		$filtros_extra = explode('=', $herramienta);
		$comparacion_del_filtro = ($filtros_extra[1][0] == "!") ? "NOT LIKE" : "LIKE";
		$valor_a_comparar_del_filtro = ($filtros_extra[1][0] == "!") ? substr($filtros_extra[1], 1) : $filtros_extra[1];
		
		$filtros_del_select[] = 'AND '.$filtros_extra[0].' '.$comparacion_del_filtro.' "'.$valor_a_comparar_del_filtro.'" ';
	}
}

if($rotulo != "") echo '<b><label for="'.$variable_nombre.'">'.ucfirst($rotulo).'</label></b>';

if(!isset($sentido)) $sentido = "ASC";
if(!isset($sin_datos)) $sin_datos = "no";
if(!isset($blanco)) $blanco = "no";
if(!isset($todos)) $todos = "no";

$consulta_seleccion = 'SELECT * 
	FROM '.$tabla_seleccion.' 
	WHERE borrado LIKE "no" ';
	foreach ($filtros_del_select as $pos => $filtro_del_select) $consulta_seleccion.= $filtro_del_select;
	$consulta_seleccion.= ' GROUP BY '.$campo_seleccion.'
	ORDER BY '.$campo_seleccion.' '.$sentido;

$query_seleccion = $conexion->prepare($consulta_seleccion);
$query_seleccion->execute();

echo '<select name="'.$variable_nombre.'" id="'.$variable_nombre.'" value="'.$valor.'"/>';	

	if($sin_datos === "si") echo '<option value="sin datos">Sin Datos</option>';
	if($blanco === "si") echo '<option value=""></option>';		
	if($todos === "si") echo '<option value="todos">Todos</option>';
	
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
