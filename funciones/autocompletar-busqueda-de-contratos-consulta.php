<?php if (!isset($_SESSION)) {session_start();}

include '../funciones/conectar-base-de-datos.php';

$dato_a_buscar = $_POST['datos_para_el_query'];
$es_numero = is_numeric($dato_a_buscar);
$tiene_guion = (strpos($dato_a_buscar, '-') !== false);

if($es_numero or $tiene_guion)
{
    $filtro_a_usar = 'AND (cuenta_documento_numero = "'.$dato_a_buscar.'" OR contrato LIKE "%'.$dato_a_buscar.'%" OR contrato_numero = "'.$dato_a_buscar.'")';
}
else
{
    $dato_a_buscar_explotado = explode(' ', $_POST['datos_para_el_query']);
    $filtro_a_usar = 'AND cuenta LIKE "%'.implode('%" AND cuenta LIKE "%', $dato_a_buscar_explotado).'%"';
}

$i = 0;
$contratos_encontrados = array();
$consulta_autocompletar = 'SELECT cuenta, contrato, cuenta_documento_tipo, cuenta_documento_numero FROM contratos
    WHERE borrado LIKE "no"
    '.$filtro_a_usar.'
    GROUP BY contrato
    ORDER BY cuenta ASC LIMIT 5';
$query_autocompletar = $conexion->prepare($consulta_autocompletar);
$query_autocompletar->execute();
while($rows_autocompletar = $query_autocompletar->fetch(PDO::FETCH_ASSOC))
{
    $cuenta = $rows_autocompletar['cuenta'];
    $cuenta_documento_numero = $rows_autocompletar['cuenta_documento_numero'];
    $cuenta_documento_tipo = $rows_autocompletar['cuenta_documento_tipo'];

    $contratos_encontrados[$cuenta]['cuenta_documento'] = $cuenta_documento_tipo.': '.$cuenta_documento_numero;
    $contratos_encontrados[$cuenta]['contratos'][$i] = $rows_autocompletar['contrato'];
    $i++;
}

if(isset($contratos_encontrados) and !empty($contratos_encontrados))
{
    foreach ($contratos_encontrados as $cuenta => $datos_de_la_cuenta)
    {
        echo '<tr class="filas_borrables">';
            echo '<td>';
                echo $cuenta;
            echo '</td>';
            echo '<td>';
                echo $datos_de_la_cuenta['cuenta_documento'];
            echo '</td>';
        echo '</tr>';
        foreach ($datos_de_la_cuenta['contratos'] as $pos => $contrato)
        {
            echo '<tr class="filas_borrables">';
                echo '<td>';
                echo '</td>';
                echo '<td>';
                    echo $contrato;
                echo '</td>';
            echo '</tr>';
        }
    }
}
else
{
    echo '<tr class="filas_borrables">';
        echo '<td>';
            echo 'No hay ninguna sugerencia';
        echo '</td>';
    echo '</tr>';
}

?>
