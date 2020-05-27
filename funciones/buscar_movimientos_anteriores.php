<?php if (!isset($_SESSION)) {session_start();}

include '../funciones/conectar-base-de-datos.php';

$facturas_encontradas = array();
$datos_a_buscar = explode('#', $_POST['datos_para_el_query']);

$consulta_autocompletar = 'SELECT factura_numero, SUM(derecho) AS suma_derecho, SUM(obligacion) AS suma_obligacion FROM diario
    WHERE borrado LIKE "no"
        AND planilla LIKE "pac-%"
        AND descripcion NOT LIKE "cancelacion de facturas"
        AND id < '.$datos_a_buscar[0].'
        AND cuenta LIKE "'.$datos_a_buscar[1].'"
    GROUP BY factura_numero
    ORDER BY id DESC LIMIT 20';
$query_autocompletar = $conexion->prepare($consulta_autocompletar);
$query_autocompletar->execute();
while($rows_autocompletar = $query_autocompletar->fetch(PDO::FETCH_ASSOC))
{
    $factura_numero = $rows_autocompletar['factura_numero'];
    $factura_monto = $rows_autocompletar['suma_obligacion'] - $rows_autocompletar['suma_derecho'];
    $facturas_encontradas[$factura_numero] = $factura_monto;
}

$id_a_buscar = $datos_a_buscar[0];
echo '<table style="background-color:white;border:1px solid black;">';
    echo '<tr>';
        echo '<td>';
        echo '</td>';
        echo '<td>';
            echo '<img id="img_cerrar" src="../../imagenes/iconos/mal.jpg" width="20" height="20" style="float:right;">';
        echo '</td>';
    echo '</tr>';
    $total_facturas = 0;
    foreach ($facturas_encontradas as $factura_numero => $factura_monto)
    {
        echo '<tr>';
            echo '<td data-factura_seleccionada="'.$factura_numero.'" data-id_a_buscar="'.$id_a_buscar.'">';
                echo $factura_numero;
            echo '</td>';
            echo '<td>';
                echo '&nbsp&nbsp&nbsp';
            echo '</td>';
            echo '<td style="text-align:right;">';
                echo number_format($factura_monto);
                $total_facturas+= $factura_monto;
            echo '</td>';
        echo '</tr>';
    }
    echo '<tr style="border-top:1px solid black;">';
        echo '<td>';
            echo 'Total';
        echo '</td>';
        echo '<td>';
            echo '&nbsp&nbsp&nbsp';
        echo '</td>';
        echo '<td style="text-align:right;">';
            echo number_format($total_facturas);
        echo '</td>';
    echo '</tr>';
echo '</table>';


?>

<script>

    $('td[data-factura_seleccionada]').click(function(){
        var factura_seleccionada = $(this).data('factura_seleccionada');
        var id_a_buscar = $(this).data('id_a_buscar');
        $('input[data-id="'+id_a_buscar+'"]').val(factura_seleccionada);
        $('input[data-id="'+id_a_buscar+'"]').focus();
    });

</script>


