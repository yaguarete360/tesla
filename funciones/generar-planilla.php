<?php if(!isset($_SESSION)) {session_start();}

echo '<form action="" method="post" class="nobr" id="formPrincipal">';
    echo '<table class="cargar-prestaciones">';

        echo '<tr>';
            echo '<td>';
                echo '<h3>'.strtoupper($planilla_nombre).'</h3>';
                echo '<input type="hidden" name="planilla_nombre" value="'.$planilla_nombre.'"/>';
            echo '</td>';
            echo '<td>';
                echo '<b>-</b>';
            echo '</td>';
            echo '<td>';
                if(isset($_POST['siguiente']) and !empty($_POST['planilla_ano']))
                {
                    echo '<h3>'.$_POST['planilla_ano'].'</h3>';
                }
                else
                {
                    $periodo_ano = date('Y');
                    echo '<input type="text" name="planilla_ano" value="'.$periodo_ano.'" placeholder="Planilla Año"/>';
                }
            echo '</td>';
            echo '<td>';
                echo '<b>-</b>';
            echo '</td>';
            echo '<td colspan="">';
                if(isset($_POST['siguiente']) and !empty($_POST['planilla_mes']))
                {
                    echo '<h3>'.$_POST['planilla_mes'].'</h3>';
                }
                else
                {
                    $meses_s = ",enero,febrero,marzo,abril,mayo,junio,julio,agosto,setiembre,octubre,noviembre,diciembre";
                    $meses_a = explode(",", $meses_s);
                    echo '<select name="planilla_mes"/>';
                        (isset($_POST['planilla_mes'])) ? $mes_seleccionado = $_POST['planilla_mes'] : $mes_seleccionado = date('m');
                        foreach ($meses_a as $num => $mes) if(!empty($mes)) echo '<option value="'.str_pad($num, 2, "0", STR_PAD_LEFT).'" '.(($num == $mes_seleccionado) ? 'selected': '').'>'.ucwords($mes).'</option>';
                    echo '</select>';
                }
                
            echo '</td>';
        echo '</tr>';
        if(!isset($_POST['siguiente']))
        {
            echo '<tr>';
                echo '<td colspan="5">';
                    echo '<input type="submit" name="siguiente" value="Proponer">';
                echo '</td>';
            echo '</tr>';
        }
    echo '</table>';
echo '</form>';

foreach ($montos_fijos_a as $campo => $derecho_obligacion) $clasificaciones[str_replace("_", " ", $campo)] = $derecho_obligacion;

$i_mv = 0;
$montos_variables_datos_a = explode("-", $montos_variables_datos_s);
$consulta_montos_variables = 'SELECT * 
    FROM '.$montos_variables_datos_a[0].'
    WHERE borrado LIKE "no"
    AND '.$montos_variables_datos_a[1].' LIKE "'.$montos_variables_datos_a[2].'"
    ORDER BY '.$montos_variables_datos_a[3].' ASC';
$query_mv = $conexion->prepare($consulta_montos_variables);
$query_mv->execute();
while($rows_mv = $query_mv->fetch(PDO::FETCH_ASSOC))
{
    $montos_variables_a[$i_mv] = $rows_mv[$montos_variables_datos_a[3]];
    $clasificaciones[$rows_mv[$montos_variables_datos_a[3]]] = $rows_mv[$montos_variables_datos_a[4]];
    $i_mv++;
}

if(isset($_POST['siguiente']) and !empty($_POST['planilla_ano']) and !empty($_POST['planilla_mes']))
{
    $consulta = 'SELECT * 
        FROM '.$tabla_base.'
        WHERE borrado LIKE "no" ';
    if(isset($campos_filtro)) foreach ($campos_filtro as $filtro_nombre => $filtro_valor) $consulta.= 'AND '.$filtro_nombre.' LIKE "'.$filtro_valor.'" ';
    $consulta.= 'ORDER BY '.$campo_base.'';
    $query = $conexion->prepare($consulta);
    $query->execute();

    while($rows = $query->fetch(PDO::FETCH_ASSOC))
    {
        foreach ($montos_fijos_a as $campo => $derecho_obligacion) $planilla[$rows[$campo_base]][$campo] = $rows[$campo];
    }

    echo '<form action="" method="post" class="nobr">';

        $planilla_nombre_final = $_POST['planilla_nombre']."-".$_POST['planilla_ano']."-".$_POST['planilla_mes'];
        echo '<input type="hidden" name="planilla" class="" value="'.$planilla_nombre_final.'"/>';
    
        echo '<table id="tabla_de_personas">';
            $i_personas=1;
            foreach ($planilla as $persona => $desglose)
            {
                //echo '<tr><td>&nbsp</td><tr>';
                echo '<tr>';
                    echo '<td><h4>';
                        echo $i_personas." - ".$persona;
                    echo '</h4></td>';
                echo '</tr>';

                foreach ($desglose as $tipo => $monto)
                {
                    echo '<tr>';
                        echo '<td>';
                            echo str_replace("_", " ", $tipo);
                        echo '</td>';
                        echo '<td style="text-align:right">';
                            echo number_format($monto);
                            echo '<input type="hidden" name="montos['.$persona.']['.str_replace("_", " ", $tipo).']" class="" value="'.$monto.'"/>';
                        echo '</td>';
                    echo '</tr>';
                }
                echo '<tr id="'.$persona.'" >';
                    echo '<td>';
                        echo '<select name="seleccionar_tipos_de_montos"/>';
                            foreach ($montos_variables_a as $pos => $campo)
                            {
                                echo '<option value="'.$campo.'">'.ucwords(str_replace("_", " ", $campo)).'</option>';
                            }
                        echo '</select>';
                    echo '</td>';
                    
                    echo '<td>';
                        echo '<img class="boton_persona_mas" src="../../imagenes/iconos/boton-altas.png" width="30px">';
                    echo '</td>';
                echo '</tr>';
                $i_personas++;
            }

            echo '<tr>';
                echo '<td colspan="5">';
                    echo '<input type="submit" name="generar_planilla" value="Generar">';
                echo '</td>';
            echo '</tr>';
        echo '</table>';
    echo '</form>';

}

if(isset($_POST['generar_planilla']))
{
    echo '<table id="tabla_de_personas">';
        echo '<tr>';
            echo '<th colspan="2">';
                echo "Planilla A Generar";
            echo '</th>';
            echo '<th>';
                echo $_POST['planilla'];
            echo '</th>';
        echo '</tr>';
        foreach ($_POST['montos'] as $persona => $desglose)
        {
            echo '<tr><td colspan="5"><hr></td></tr>';
            echo '<tr>';
                echo '<td colspan="3">';
                    echo '<h4>'.$persona.'</h4>';
                echo '</td>';
            echo '</tr>';

            $persona_numero = "sin numero";
            $consulta_cuenta_numero = 'SELECT * 
                FROM cuentas
                WHERE borrado LIKE "no"
                AND cuenta LIKE "'.$persona.'"
                LIMIT 1
                ';
            $query_cuenta_numero = $conexion->prepare($consulta_cuenta_numero);
            $query_cuenta_numero->execute();
            while($rows_cuenta_numero = $query->fetch(PDO::FETCH_ASSOC)) $persona_numero = $rows_cuenta_numero[$campo_numero_de_cuenta];

            foreach ($desglose as $tipo => $monto)
            {
                echo '<tr>';
                    echo '<td style="text-align:right;">&nbsp&nbsp-&nbsp&nbsp</td>';
                    echo '<td>';
                        echo $tipo;
                    echo '</td>';
                    echo '<td style="text-align:right;">';
                        echo number_format($monto);
                    echo '</td>';
                echo '</tr>';

                switch ($clasificaciones[$tipo])
                {
                    case 'derecho':
                        $entra = 1;
                        $sale = "";
                        $derecho = $monto;
                        $obligacion = "";
                    break;
                    case 'obligacion':
                        $entra = "";
                        $sale = 1;
                        $derecho = "";
                        $obligacion = $monto;
                    break;

                    default:
                        $entra = "";
                        $sale = "";
                        $derecho = "";
                        $obligacion = "";
                    break;
                }

                //no esta columna APROBADO por la insercion de 0000-00-00 00:00:00 en DATETIME // por si acaso
                $consulta_insercion = 'INSERT INTO planillas (
                    planilla,
                    fecha,
                    cuenta,
                    cuenta_numero,
                    documento_tipo,
                    documento_numero,
                    descripcion,
                    observacion,
                    aprobado_por,
                    entra,
                    sale,
                    derecho,
                    obligacion,
                    cotizacion,
                    creado,
                    borrado,
                    usuario
                    ) VALUES (
                    "'.$_POST['planilla'].'",
                    "'.date('Y-m-d').'",
                    "'.$persona.'",
                    "'.$persona_numero.'",
                    "no aplicable",
                    "no aplicable",
                    "'.$tipo.'",
                    "no aplicable",
                    "no aplicable",
                    "'.$entra.'",
                    "'.$sale.'",
                    "'.$derecho.'",
                    "'.$obligacion.'",
                    "0",
                    "'.date('Y-m-d G:i:s').'",
                    "no",
                    "'.$_SESSION['usuario_en_sesion'].'")';
                $query_insercion = $conexion->prepare($consulta_insercion);
                $query_insercion->execute();
            }
        }
    echo '</table>';
}

?>

<script type="text/javascript">

    var tabla_de_personas = document.getElementById("tabla_de_personas");

    $(".boton_persona_mas").click(function()
    {
        var esta_fila = $(this).closest("tr");
        var esta_fila_numero = esta_fila.index();
        var esta_persona_id = esta_fila.attr('id');

        var fila_nueva = tabla_de_personas.insertRow(esta_fila_numero);
        
        var celda_1 = fila_nueva.insertCell(0);
        var celda_1_valor = esta_fila.find('select').find(':selected').val();
        celda_1.innerHTML = celda_1_valor;

        var celda_2 = fila_nueva.insertCell(1);         
        var celda_2_input = '<input type="number" name="montos['+esta_persona_id+']['+celda_1_valor+']" class=""/>';
        esta_fila.prev().find('td:eq(1)').append(celda_2_input);

        var celda_3 = fila_nueva.insertCell(2);
        var celda_3_boton = '<img class="boton_persona_menos" src="../../imagenes/iconos/boton-bajas.png" width="30px">';
        esta_fila.prev().find('td:eq(2)').append(celda_3_boton);

        $(".boton_persona_menos").click(function()
        {
            var esta_fila = $(this).closest("tr");
            esta_fila.remove();
        });
    });


</script>
