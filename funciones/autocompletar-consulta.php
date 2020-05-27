<?php if (!isset($_SESSION)) {session_start();}

include '../funciones/conectar-base-de-datos.php';

$datos_para_el_query_explotado = explode("#", $_POST['datos_para_el_query']);
// var_dump($datos_para_el_query_explotado);
$campo_a_buscar = array_pop($datos_para_el_query_explotado);
$tabla_a_usar = array_pop($datos_para_el_query_explotado);

$filtros_a_agregar = '';
$campo_a_buscar_datos = explode("=", $campo_a_buscar);
$campo_a_usar = $campo_a_buscar_datos[0];
$valores_a_buscar = explode(" ", $campo_a_buscar_datos[1]);
foreach ($valores_a_buscar as $pos_2 => $valor_a_buscar)
{
    $parentesis_abrir = ($campo_a_usar == 'cuenta' and $tabla_a_usar == 'cuentas' and $pos_2 == 0) ? '(' : '';
    $parentesis_cerrar = ($campo_a_usar == 'cuenta' and $tabla_a_usar == 'cuentas' and $pos_2 == (count($valores_a_buscar)-1)) ? ')' : '';
    $filtros_a_agregar.= ' AND '.$parentesis_abrir.$parentesis_abrir.$campo_a_usar.' LIKE "%'.$valor_a_buscar.'%"'.$parentesis_cerrar;
}
// var_dump($datos_para_el_query_explotado);
if($campo_a_usar == 'cuenta' and $tabla_a_usar == 'cuentas')
{
    $filtros_a_agregar_extra = '';
    foreach ($datos_para_el_query_explotado as $pos => $filtro_completo) if(!empty($filtro_completo))
    {
        $caracter_explotar = (strpos($filtro_completo, "!=") !== false) ? '!=' : '=';
        $filtro_completo_explotado = explode($caracter_explotar, $filtro_completo);
        $filtros_a_agregar_extra = ' OR '.$filtro_completo_explotado[0].' = "'.$valor_a_buscar.'"';
    }
    $filtros_a_agregar.= $filtros_a_agregar_extra.' OR ruc LIKE "%'.$valor_a_buscar.'%" OR identidad_numero LIKE "%'.$valor_a_buscar.'%"'.$parentesis_cerrar;
}

foreach ($datos_para_el_query_explotado as $pos => $filtro_completo) if(!empty($filtro_completo))
{
    // echo $filtro_completo.'<br/>';
    if(strpos($filtro_completo, "!=") !== false)
    {
        $filtro_completo_explotado = explode("!=", $filtro_completo);
        if(isset($filtro_completo_explotado[0]) and isset($filtro_completo_explotado[1])) $filtros_a_agregar.= ' AND '.$filtro_completo_explotado[0].' NOT LIKE "'.$filtro_completo_explotado[1].'"';
    }
    else
    {
        $filtro_completo_explotado = explode("=", $filtro_completo);
        if(isset($filtro_completo_explotado[0]) and isset($filtro_completo_explotado[1])) $filtros_a_agregar.= ' AND '.$filtro_completo_explotado[0].' LIKE "%'.$filtro_completo_explotado[1].'%"';
    }
}

$campo_concatenado = ($campo_a_usar == 'cuenta' and $tabla_a_usar == 'cuentas') ? 'ruc' : '';
$consulta_autocompletar = 'SELECT '.$campo_a_usar.((!empty($campo_concatenado) ? ', ' : '')).$campo_concatenado.' FROM '.$tabla_a_usar.' WHERE borrado LIKE "no"';
$consulta_autocompletar.= $filtros_a_agregar;
$consulta_autocompletar.= ' GROUP BY '.$campo_a_usar;
$consulta_autocompletar.= ' ORDER BY '.$campo_a_usar.' ASC LIMIT 25';
// echo $consulta_autocompletar;
$query_autocompletar = $conexion->prepare($consulta_autocompletar);
$query_autocompletar->execute();
while($rows_autocompletar = $query_autocompletar->fetch(PDO::FETCH_ASSOC))
{
    $respuesta[] = (!empty($campo_concatenado) ? $rows_autocompletar[$campo_concatenado].' - ' : '').$rows_autocompletar[$campo_a_usar];
}

if(isset($respuesta) and !empty($respuesta))
{
    foreach ($respuesta as $pos => $cuenta)
    {
        echo '<span>';
            echo $cuenta;
        echo '</span>';
    }
}
else
{
    echo 'No hay ninguna sugerencia';
}

?>
