<?php if (!isset($_SESSION)) {session_start();}

include '../funciones/conectar-base-de-datos.php';

$datos_para_el_query_explotado = explode("-", $_POST['datos_para_el_query']);
$campo_a_buscar = $datos_para_el_query_explotado[0];
$tabla_a_usar = $datos_para_el_query_explotado[1];
$campo_a_comparar = explode('=', $datos_para_el_query_explotado[2])[0];
$valor_a_comparar = (strpos($datos_para_el_query_explotado[2], 'cuenta') !== false) ? end($datos_para_el_query_explotado) : $datos_para_el_query_explotado[2];
$filtro_para_buscar = ($valor_a_comparar == $datos_para_el_query_explotado[2]) ? str_replace('=', ' LIKE "', $valor_a_comparar).'"' : $campo_a_comparar.' LIKE "'.trim($valor_a_comparar).'"';

$consulta_autocompletar = 'SELECT '.$campo_a_buscar.' FROM '.$tabla_a_usar.'
    WHERE borrado LIKE "no"
    AND '.$filtro_para_buscar.'
	LIMIT 1';
$query_autocompletar = $conexion->prepare($consulta_autocompletar);
$query_autocompletar->execute();

while($rows_autocompletar = $query_autocompletar->fetch(PDO::FETCH_ASSOC))
{
    $respuesta = $rows_autocompletar[$campo_a_buscar];
}

if(isset($respuesta) and !empty($respuesta))
{
    echo $respuesta;
}
else
{
    echo 'sin datos';
}

?>
