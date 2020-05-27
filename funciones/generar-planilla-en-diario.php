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

$montos_fijos_a = explode(",", $montos_fijos_s);
$montos_fijos_derecho_obligacion_a = explode(",", $montos_fijos_derecho_obligacion_s);
foreach ($montos_fijos_a as $pos => $campo) $clasificaciones[str_replace("_", " ", $campo)] = $montos_fijos_derecho_obligacion_a[$pos];

$i_mv = 0;
$montos_variables_datos_a = explode("-", $montos_variables_datos_s);
$consulta_montos_variables = 'SELECT * 
    FROM '.$montos_variables_datos_a[0].'
    WHERE borrado LIKE "no"
    AND '.$montos_variables_datos_a[1].' LIKE "'.$montos_variables_datos_a[2].'"
    ORDER BY '.$montos_variables_datos_a[3].' ASC
    ';
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
        foreach ($montos_fijos_a as $pos => $campo) $planilla[$rows[$campo_base]][$campo] = $rows[$campo];
    }

    echo '<form action="" method="post" class="nobr">';

        $planilla_nombre_final = $_POST['planilla_nombre']."-".$_POST['planilla_ano']."-".$_POST['planilla_mes'];
        echo '<input type="hidden" name="planilla" class="" value="'.$planilla_nombre_final.'"/>';
    
        echo '<table id="tabla_de_personas">';
            $i_personas=1;
            foreach ($planilla as $persona => $desglose)
            {
                echo '<tr><td>&nbsp</td><tr>';
                echo '<tr>';
                    echo '<td><h4>';
                        echo $i_personas." - ".$persona;
                    echo '</h4></td>';
                echo '</tr>';

                foreach ($desglose as $tipo => $monto)
                {
                    if($monto > 0)
                    {
                        echo '<tr>';
                            echo '<td>';
                                echo str_replace("_", " ", $tipo);
                            echo '</td>';
                            echo '<td style="text-align:right;">';
                                echo number_format($monto);
                                echo '<input type="hidden" name="montos['.$persona.']['.str_replace("_", " ", $tipo).']" class="'.$persona.'" value="'.$monto.'" disabled="disabled"/>';
                            echo '</td>';
                        echo '</tr>';
                    }
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

                    echo '<td style="padding-left:5px;padding-right:5px;border: 1px solid red;">';
                        echo '<input type="checkbox" checked>';
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
            $consulta_cuenta_numero = 'SELECT '.$campo_numero_de_cuenta.', '.implode(', ', $campos_datos_cuentas).'
                FROM cuentas
                WHERE borrado LIKE "no"
                AND cuenta LIKE "'.$persona.'"
                LIMIT 1
                ';
            $query_cuenta_numero = $conexion->prepare($consulta_cuenta_numero);
            $query_cuenta_numero->execute();
            while($rows_cuenta_numero = $query_cuenta_numero->fetch(PDO::FETCH_ASSOC))
            {
                $persona_numero = $rows_cuenta_numero[$campo_numero_de_cuenta];
                foreach ($campos_datos_cuentas as $campo_dato_cuenta) $datos_cuentas[$campo_dato_cuenta] = $rows_cuenta_numero[$campo_dato_cuenta];
            }

            foreach ($desglose as $tipo => $monto)
            {
                $ultimo_numero = date('Y')."-0000000";
                $consulta_diario_numero = 'SELECT diario
                    FROM diario
                    WHERE borrado LIKE "no"
                    AND diario LIKE "'.date('Y').'-%"
                    ORDER BY diario DESC
                    LIMIT 1';
                $query_diario_numero = $conexion->prepare($consulta_diario_numero);
                $query_diario_numero->execute();
                while($rows_diario_numero = $query_diario_numero->fetch(PDO::FETCH_ASSOC)) $ultimo_numero = $rows_diario_numero['diario'];
                $ultimo_numero_partes = explode("-", $ultimo_numero);
                $numero_a_usar = $ultimo_numero_partes[0]."-".str_pad($ultimo_numero_partes[1]+1, 7, "0", STR_PAD_LEFT);
                
                if($monto != 0)
                {
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

                    $existe_este_movimiento = "no"; // Medida de seguridad por FORM RESUBMISSION //s
                    $consulta_control_resubmission = 'SELECT id
                        FROM diario
                        WHERE borrado LIKE "no"
                        AND documento_tipo LIKE "no aplicable"
                        AND planilla LIKE "'.$_POST['planilla'].'"
                        AND cuenta LIKE "'.$persona.'"
                        AND descripcion LIKE "'.$tipo.'"
                        AND derecho = "'.$derecho.'"
                        AND obligacion = "'.$obligacion.'"';
                    $query_control_resubmission = $conexion->prepare($consulta_control_resubmission);
                    $query_control_resubmission->execute();
                    while($rows_c_r = $query_control_resubmission->fetch(PDO::FETCH_ASSOC)) $existe_este_movimiento = "si";

                    echo '<tr>';
                        echo '<td style="text-align:right;">&nbsp&nbsp-&nbsp&nbsp</td>';
                        echo '<td>';
                            echo $tipo;        
                        echo '</td>';
                        echo '<td style="text-align:right;">&nbsp&nbsp-&nbsp&nbsp</td>';
                        echo '<td style="text-align:right;">';
                            echo number_format($monto);
                        echo '</td>';
                        echo '<td style="text-align:right;">&nbsp&nbsp-&nbsp&nbsp</td>';
                        
                        if($existe_este_movimiento == "no")
                        {
                            $consulta_insercion = 'INSERT INTO diario (
                                diario,
                                planilla,
                                fecha,
                                cuenta,
                                cuenta_numero,
                                cuenta_documento_tipo,
                                cuenta_documento_numero,
                                contrato,
                                documento_tipo,
                                documento_numero,
                                descripcion,
                                observacion,
                                aprobado_por,
                                cuota,
                                efectuado_por,
                                cuenta_bancaria_titular,
                                cuenta_bancaria_banco,
                                cuenta_bancaria_numero,
                                factura_tipo,
                                factura_numero,
                                entra,
                                sale,
                                derecho,
                                obligacion,
                                cotizacion,
                                creado,
                                borrado,
                                usuario
                                ) VALUES (
                                "'.$numero_a_usar.'",
                                "'.$_POST['planilla'].'",
                                "'.date('Y-m-d').'",
                                "'.$persona.'",
                                "'.$persona_numero.'",
                                "'.$datos_cuentas['identidad_tipo'].'",
                                "'.$datos_cuentas['identidad_numero'].'",
                                "no aplicable",
                                "no aplicable",
                                "no aplicable",
                                "'.$tipo.'",
                                "no aplicable",
                                "no aplicable",
                                "no aplicable",
                                "no aplicable",
                                "no aplicable",
                                "no aplicable",
                                "no aplicable",
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
                            try
                            {
                                $query_insercion = $conexion->prepare($consulta_insercion);
                                $query_insercion->execute();
                                echo '<td style="color:green;font-weight:bold;">';
                                    echo "Cargado Correctamente.";
                                echo '</td>';
                            }
                            catch( PDOException $e )
                            {
                                echo '<td style="color:green;font-weight:bold;">';
                                    echo "Ha ocurrido un error. Contacte con un programador con la suiguiente referencia:<br/>";
                                    echo $e;
                                echo '</td>';
                            }
                        }
                        else
                        {
                            echo '<td style="color:green;font-weight:bold;">';
                                echo "Este movimiento ya se habia generado.";
                            echo '</td>';
                        }
                    echo '</tr>';

                    //no esta columna APROBADO por la insercion de 0000-00-00 00:00:00 en DATETIME // por si acaso

                }
            }
        }
    echo '</table>';
}

?>

<script type="text/javascript">

    var tabla_de_personas = document.getElementById("tabla_de_personas");

    var checkboxes = $("input[type=checkbox]");
    jQuery.each( checkboxes, function() {
      
        var esta_fila = $(this).closest("tr");
        var esta_fila_numero = esta_fila.index();
        var esta_persona_id = esta_fila.attr('id');

        $('input[class="'+esta_persona_id+'"]').closest('tr').css('background-color', '#DADADA');
        esta_fila.css('background-color', '#DADADA');
        $('input[class="'+esta_persona_id+'"]').attr("disabled","disabled");


    });

    $(".boton_persona_mas").click(function()
    {
        var esta_fila = $(this).closest("tr");
        var esta_fila_numero = esta_fila.index();
        var esta_persona_id = esta_fila.attr('id');
        var esta_deshabilitado_1 = esta_fila.find('input[type="checkbox"]').is(":checked");

        var fila_nueva = tabla_de_personas.insertRow(esta_fila_numero);
        
        var celda_1 = fila_nueva.insertCell(0);
        var celda_1_valor = esta_fila.find('select').find(':selected').val();
        celda_1.innerHTML = celda_1_valor;

        var celda_2 = fila_nueva.insertCell(1);
        if(esta_deshabilitado_1)
        {
            esta_deshabilitado = 'disabled="disabled"';
            esta_fila.prev().css('background-color', '#DADADA');
        }
        else
        {
            esta_deshabilitado = "";
            esta_fila.prev().css('background-color', 'white');
        }

        var celda_2_input = '<input type="number" name="montos['+esta_persona_id+']['+celda_1_valor+']" class="'+esta_persona_id+'" '+esta_deshabilitado+' />';
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
    
    $("input[type=checkbox]").click(function()
    {
        var esta_fila = $(this).closest("tr");
        var esta_fila_numero = esta_fila.index();
        var esta_persona_id = esta_fila.attr('id');

        var esta_chequeado = this.checked;
        
        if(esta_chequeado)
        {
            $('input[class="'+esta_persona_id+'"]').closest('tr').css('background-color', '#DADADA');
            esta_fila.css('background-color', '#DADADA');
            $('input[class="'+esta_persona_id+'"]').attr("disabled","disabled");
        }
        else
        {
            $('input[class="'+esta_persona_id+'"]').closest('tr').css('background-color', 'white');
            esta_fila.css('background-color', 'white');
            $('input[class="'+esta_persona_id+'"]').attr("disabled", false);
        }
    });


</script>
