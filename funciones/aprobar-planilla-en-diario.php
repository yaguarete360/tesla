<?php if(!isset($_SESSION)) {session_start();}

echo '<form action="" method="post" class="nobr" id="formPrincipal">';
    echo '<table class="cargar-prestaciones">';

        echo '<tr>';
            $consulta = 'SELECT * 
                FROM diario
                WHERE borrado LIKE "no"
                AND planilla LIKE "'.$tipo_de_planilla.'%"
                GROUP BY planilla
                ORDER BY planilla DESC
                ';
            $query = $conexion->prepare($consulta);
            $query->execute();

            echo '<td>';
                echo '<select name="planilla_seleccionada"/>';
                    while($rows = $query->fetch(PDO::FETCH_ASSOC))
                    {
                        echo '<option value="'.$rows['planilla'].'">'.strtoupper($rows['planilla']).'</option>';
                    }
                echo '</select>';
            echo '</td>';
        echo '</tr>';

        echo '<tr>';
            echo '<td colspan="5">';
                echo '<input type="submit" name="siguiente" value="Ver Planilla">';
            echo '</td>';
        echo '</tr>';

    echo '</table>';
echo '</form>';

if(isset($_POST['siguiente']))
{
    $tipos_no_cancelables_a = explode(",", $tipos_no_cancelables_s);

    $consulta = 'SELECT * 
        FROM diario
        WHERE borrado LIKE "no"
        AND planilla LIKE "'.$_POST['planilla_seleccionada'].'" ';
    if(isset($campos_filtro_si)) foreach ($campos_filtro_si as $filtro_campo => $filtro_valor) $consulta.= 'AND '.$filtro_campo.' LIKE "'.$filtro_valor.'" ';
    if(isset($campos_filtro_no)) foreach ($campos_filtro_no as $filtro_campo => $filtro_valor) $consulta.= 'AND '.$filtro_campo.' NOT LIKE "'.$filtro_valor.'" ';
    $consulta.= 'ORDER BY cuenta ASC';
    $query = $conexion->prepare($consulta);
    $query->execute();

    while($rows = $query->fetch(PDO::FETCH_ASSOC))
    {
        ($rows['derecho'] == 0) ? $campo_monto = "obligacion" : $campo_monto = "derecho";
        $planilla[$rows['cuenta']."-".$rows['cuenta_numero']][$rows['descripcion']."_".$campo_monto."_".$rows['aprobado']] = $rows[$campo_monto];
    }

    echo '<form action="" method="post">';
        echo '<table>';
            echo '<input type="hidden" name="planilla_a_actualizar" value="'.$_POST['planilla_seleccionada'].'">';

            $saldo_total_final = 0;
            foreach ($planilla as $cuenta => $datos)
            {
                $saldo_individual = 0;
                $cuenta_partes = explode("-", $cuenta);
                $persona = $cuenta_partes[0];
                $persona_numero = $cuenta_partes[1];
                echo '<tr>';
                    echo '<td>';
                        echo '<h4>';
                            echo $persona." - ".$persona_numero ;
                        echo '</h4>';
                    echo '</td>';
                echo '</tr>';

                foreach ($datos as $info => $monto)
                {
                    $info_partes = explode("_", $info);
                    $tipo = $info_partes[0];
                    $derecho_obligacion = $info_partes[1];
                    $aprobado = $info_partes[2];
                    echo '<tr>';
                        echo '<td>';
                            echo $tipo;
                        echo '</td>';
                        $estilo_bordes = "text-align:right;padding:1px 5px 1px 5px;border-right-style: solid;border-width:1px;";
                        echo '<td style="background-color:#d6f5d6;'.$estilo_bordes.'">';
                            if($derecho_obligacion == "derecho")
                            {
                                echo number_format($monto);
                                
                                // los fijos hay que ver para calcular por no aprobados

                                if($aprobado != "0000-00-00 00:00:00" or in_array($tipo, $tipos_no_cancelables_a))
                                {
                                    $saldo_individual = $saldo_individual + $monto;
                                    $saldo_total_final = $saldo_total_final + $monto;
                                }
                            }
                        echo '</td>';
                        echo '<td style="background-color:#ffcccc;'.$estilo_bordes.'">';
                            if($derecho_obligacion == "obligacion")
                            {
                                echo number_format($monto);
                                if($aprobado != "0000-00-00 00:00:00" or in_array($tipo, $tipos_no_cancelables_a))
                                {
                                    $saldo_individual = $saldo_individual - $monto;
                                    $saldo_total_final = $saldo_total_final - $monto;
                                }
                            }
                        echo '</td>';
                        
                        echo '<td>';
                            if(in_array($tipo, $tipos_no_cancelables_a))
                            {
                                if($aprobado == "0000-00-00 00:00:00")
                                {
                                    echo '<input type="hidden" name="aprobados['.$cuenta.']['.$tipo.']" value="'.$monto.'">';
                                }
                                else
                                {
                                    echo '&nbsp&nbsp&nbsp&nbsp';
                                    echo "Aprobado el: ".$aprobado;
                                }
                            }
                            else
                            {
                                echo '&nbsp&nbsp&nbsp&nbsp';
                                if($aprobado == "0000-00-00 00:00:00")
                                {
                                    echo '<input type="checkbox" id="'.str_replace(" ", "-", str_replace(",", "", $persona)).'" name="aprobados['.$cuenta.']['.$tipo.']" class="boton_aprobado" value="'.$monto.'">';
                                }
                                else
                                {
                                    echo "Aprobado el: ".$aprobado;

                                }
                            }
                        echo '</td>';
                    echo '</tr>';
                }
                echo '<tr>';
                    echo '<td style="text-align:right;">Saldo:</td>';
                    echo '<td id="saldo_individual_'.str_replace(" ", "-", str_replace(",", "", $persona)).'_derecho" style="text-align:right;background-color:#d6f5d6;border-top:solid;border-width:1px;">';
                        if($saldo_individual > 0)
                        {
                            echo number_format($saldo_individual);
                        }
                    echo '</td>';
                    echo '<td id="saldo_individual_'.str_replace(" ", "-", str_replace(",", "", $persona)).'_obligacion" style="text-align:right;background-color:#ffcccc;border-top:solid;border-width:1px;">';
                        if($saldo_individual < 0)
                        {
                            echo number_format($saldo_individual * -1);
                        }
                    echo '</td>';
                echo '</tr>';
            }
            echo '<tr>';
                echo '<td style="text-align:right;"><h4> Saldo Total A Aprobar:&nbsp&nbsp</h4></td>';
                echo '<b><td class="saldo_total" style="text-align:right;">';
                    if($saldo_individual >= 0)
                    {
                        echo number_format($saldo_total_final);
                    }
                echo '</td></b>';
                echo '<b><td class="saldo_total" style="text-align:right;">';
                    if($saldo_individual < 0)
                    {
                        echo number_format($saldo_total_final * -1);
                    }
                echo '</td></b>';
                echo '<td style="padding-left:15px;">';
                    if($saldo_individual > 0)
                    {
                        echo "De derecho para la empresa.";
                    }
                    else
                    {
                        echo "De obligacion para la empresa.";
                    }
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td>';
                    echo '<input type="submit" name="aprobar" value="Aprobar Planilla">';
                echo '</td>';
            echo '</tr>';
        echo '</table>';
    echo '</form>';
}

if(isset($_POST['aprobar']))
{
    echo '<table>';
        if(isset($_POST['aprobados']) and !empty($_POST['aprobados']))
        {
            foreach ($_POST['aprobados'] as $cuenta => $info)
            {
                $cuenta_partes = explode("-", $cuenta);
                echo '<tr>';
                    echo '<td>';
                        echo '<h4>';
                            echo $persona = $cuenta_partes[0];
                            echo '&nbsp-&nbsp';
                            echo $persona_numero = $cuenta_partes[1];
                        echo '</h4>';
                    echo '</td>';
                echo '</tr>';
                
                foreach ($info as $tipo => $monto)
                {
                    echo '<tr>';
                        echo '<td>';
                            echo $tipo;
                        echo '</td>';
                        echo '<td>&nbsp-&nbsp</td>';
                        echo '<td>';
                            echo $monto;
                        echo '</td>';
                        if(isset($observaciones[$cuenta]))
                        {
                            echo '<td>';
                                echo $observaciones[$cuenta];
                            echo '</td>';
                        }
                    echo '</tr>';

                    $consulta_aprobacion = 'UPDATE diario SET
                        aprobado = "'.date('Y-m-d G:i:s').'",
                        aprobado_por = "'.$_SESSION['usuario_en_sesion'].'"
                        WHERE borrado LIKE "no"
                        AND aprobado LIKE "0000-00-00%"
                        AND planilla LIKE "'.$_POST['planilla_a_actualizar'].'"
                        AND cuenta LIKE "'.$persona.'"
                        AND cuenta_numero LIKE "'.$persona_numero.'"
                        AND descripcion LIKE "'.$tipo.'"
                        ';
                    $query_aprobacion = $conexion->prepare($consulta_aprobacion);
                    $query_aprobacion->execute();
                }
            }
        }
        else
        {
            echo "No hay movimientos para aprobar";
        }
    echo '</table>';
}

?>

<script type="text/javascript">

    var tabla_de_personas = document.getElementById("tabla_de_personas");

    $.fn.digits = function(){ 
        return this.each(function(){ 
            $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
        })
    }

    $(".boton_aprobado").change(function()
    {
        var esta_tabla = $(this).closest('table');
        var esta_fila = $(this).closest('tr');
        
        var esta_persona = $(this).attr('id');

        var este_movimiento_derecho = esta_fila.find('td:eq(1)').html().replace(/,/g, '');
        var este_movimiento_obligacion = esta_fila.find('td:eq(2)').html().replace(/,/g, '');
        var este_movimiento_tipo = (este_movimiento_derecho) ? "derecho" : "obligacion";
        var este_movimiento_monto = (este_movimiento_derecho) ? este_movimiento_derecho : este_movimiento_obligacion;
        este_movimiento_monto = +este_movimiento_monto;

        var derecho_de_persona_celda = $('td#saldo_individual_'+esta_persona+'_derecho');
        var derecho_de_persona_saldo = derecho_de_persona_celda.html().replace(/,/g, '');

        var obligacion_de_persona_celda = $('td#saldo_individual_'+esta_persona+'_obligacion');
        var obligacion_de_persona_saldo = obligacion_de_persona_celda.html().replace(/,/g, '');

        var este_saldo_tipo = (derecho_de_persona_saldo) ? "derecho" : "obligacion";
        var este_saldo_monto = (derecho_de_persona_saldo) ?  derecho_de_persona_saldo : obligacion_de_persona_saldo;
        este_saldo_monto = +este_saldo_monto;

        var total_final_celda_derecho = esta_tabla.find('tr:eq(-2)').find('td:eq(1)');
        var total_final_monto_derecho = total_final_celda_derecho.html().replace(/,/g, '');
        var total_final_celda_obligacion = esta_tabla.find('tr:eq(-2)').find('td:eq(2)');
        var total_final_monto_obligacion = total_final_celda_obligacion.html().replace(/,/g, '');
        var total_final_celda_observacion = esta_tabla.find('tr:eq(-2)').find('td:eq(3)');
        
        var total_final_tipo = (total_final_monto_derecho) ? "derecho" : "obligacion";
        var total_final_monto = (total_final_monto_derecho) ? total_final_monto_derecho : total_final_monto_obligacion;
        total_final_monto = +total_final_monto;

        if(this.checked)
        {
            if(este_movimiento_tipo == "derecho")
            {
                var saldo_individual_final = (este_saldo_tipo == "derecho") ? este_saldo_monto + este_movimiento_monto : este_saldo_monto - este_movimiento_monto;
                var total_final_monto = (total_final_tipo == "derecho") ? total_final_monto + este_movimiento_monto : total_final_monto - este_movimiento_monto;
            }
            else
            {
                var saldo_individual_final = (este_saldo_tipo == "derecho") ? este_saldo_monto - este_movimiento_monto : este_saldo_monto + este_movimiento_monto;
                var total_final_monto = (total_final_tipo == "derecho") ? total_final_monto - este_movimiento_monto : total_final_monto + este_movimiento_monto;
            }
        }
        else
        {
            if(este_movimiento_tipo == "derecho")
            {
                var saldo_individual_final = (este_saldo_tipo == "derecho") ? este_saldo_monto - este_movimiento_monto : este_saldo_monto + este_movimiento_monto;
                var total_final_monto = (total_final_tipo == "derecho") ? total_final_monto - este_movimiento_monto : total_final_monto + este_movimiento_monto;
            }
            else
            {
                var saldo_individual_final = (este_saldo_tipo == "derecho") ? este_saldo_monto + este_movimiento_monto : este_saldo_monto - este_movimiento_monto;
                var total_final_monto = (total_final_tipo == "derecho") ? total_final_monto + este_movimiento_monto : total_final_monto - este_movimiento_monto;
            }
        }

        if(este_saldo_tipo == "derecho")
        {
            if(saldo_individual_final >= 0)
            {
                derecho_de_persona_celda.html(saldo_individual_final);
                derecho_de_persona_celda.digits();
                obligacion_de_persona_celda.html("");
            }
            else if(saldo_individual_final < 0)
            {
                saldo_individual_final = saldo_individual_final * -1;
                obligacion_de_persona_celda.html(saldo_individual_final);
                obligacion_de_persona_celda.digits();
                derecho_de_persona_celda.html("");
            }
        }
        else 
        {
            if(saldo_individual_final >= 0)
            {
                obligacion_de_persona_celda.html(saldo_individual_final);
                obligacion_de_persona_celda.digits();
                derecho_de_persona_celda.html("");
            }
            else if(saldo_individual_final < 0)
            {
                saldo_individual_final = saldo_individual_final * -1;
                derecho_de_persona_celda.html(saldo_individual_final);
                derecho_de_persona_celda.digits();
                obligacion_de_persona_celda.html("");
            }
        }

        if(total_final_tipo == "derecho")
        {
            if(total_final_monto >= 0)
            {
                total_final_celda_derecho.html(total_final_monto);
                total_final_celda_derecho.digits();
                total_final_celda_obligacion.html("");
            }
            else if(total_final_monto < 0)
            {
                total_final_monto = total_final_monto * -1;
                total_final_celda_obligacion.html(total_final_monto);
                total_final_celda_obligacion.digits();
                total_final_celda_derecho.html("");
            }
        }
        else
        {
            if(total_final_monto >= 0)
            {
                total_final_celda_obligacion.html(total_final_monto);
                total_final_celda_obligacion.digits();
                total_final_celda_derecho.html("");
            }
            else if(total_final_monto < 0)
            {
                total_final_monto = total_final_monto * -1;
                total_final_celda_derecho.html(total_final_monto);
                total_final_celda_derecho.digits();
                total_final_celda_obligacion.html("");
            }
        }

        total_final_celda_observacion.html("De "+total_final_tipo+" para la empresa.");
    });

</script>
