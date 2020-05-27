<?php if(!isset($_SESSION)) {session_start();}




        if(!isset($_POST['siguiente']) and !isset($_POST['aprobar']))
        {
            echo '<form action="" method="post">';
                echo '<table>';
                    echo '<tr>';
                        echo '<td>';

                            $filtro_del_selector_explotado = explode(" ", $filtros_del_selector);
                            $campo_principal = $filtro_del_selector_explotado[1];

                            $consulta_selector = 'SELECT * 
                                FROM diario
                                WHERE borrado LIKE "no"
                                '.$filtros_del_selector.'
                                GROUP BY '.$campo_principal.'
                                ORDER BY '.$campo_principal.' DESC';
                            $query_selector = $conexion->prepare($consulta_selector);
                            $query_selector->execute();

                            echo '<select name="selector_seleccionado"/>';
                                while($rows_selector = $query_selector->fetch(PDO::FETCH_ASSOC))
                                {
                                    echo '<option value="'.$rows_selector[$campo_principal].'">'.strtoupper($rows_selector[$campo_principal]).'</option>';
                                }
                            echo '</select>';
                        echo '</td>';

                        echo '<td colspan="5">';
                            echo '<input type="submit" name="siguiente" value="Ver Planilla">';
                        echo '</td>';
                    echo '</tr>';
                echo '</table>';
            echo '</form>';
        }
        elseif(!isset($_POST['aprobar']))
        {
            echo '<h3>'.$_POST['selector_seleccionado'].'</h3>';

            $filtro_del_selector_explotado = explode(" ", $filtros_del_selector);
            $campo_principal = $filtro_del_selector_explotado[1];
            
            $consulta_principal = 'SELECT * 
                FROM diario
                WHERE borrado LIKE "no"
                ';//AND aprobado LIKE "0000-00-00 00:00:00"

            switch ($campo_principal)
            {
                case 'planilla':
                    if(isset($traer_movimientos_viejos_no_aprobados) and $traer_movimientos_viejos_no_aprobados == "si")
                    {
                        // AND '.$campo_principal.' <= "'.$_POST['selector_seleccionado'].'"
                        $consulta_principal.= '
                        '.$filtros_principales.'
                        ORDER BY cuenta, planilla, documento_numero, documento_tipo, fecha ASC';
                    }
                    else
                    {
                        $consulta_principal.= ' AND '.$campo_principal.' LIKE "'.$_POST['selector_seleccionado'].'"
                        '.$filtros_principales.'
                        ORDER BY cuenta, fecha ASC';
                    }
                break;
                
                default:
                    $consulta_principal.= ' AND '.$campo_principal.' LIKE "'.$_POST['selector_seleccionado'].'"
                        '.$filtros_principales.'
                        ORDER BY cuenta, fecha ASC';
                break;
            }

            $query_principal = $conexion->prepare($consulta_principal);
            $query_principal->execute();

            while($rows_principal = $query_principal->fetch(PDO::FETCH_ASSOC))
            {
                foreach ($campos_a_capturar as $pos => $campo_a_capturar)
                {
                    $resultados[$rows_principal['cuenta']][$rows_principal['id']][$campo_a_capturar] = $rows_principal[$campo_a_capturar];
                }
                $movimientos_aprobados[$rows_principal['id']] = $rows_principal['aprobado'];
                if(!isset($montos_aprobados[$rows_principal['cuenta']])) $montos_aprobados[$rows_principal['cuenta']] = 0;
                $montos_aprobados_campo = ($rows_principal['derecho'] != 0) ? $rows_principal['derecho'] : $rows_principal['obligacion'];
                if($rows_principal['aprobado'] != "0000-00-00 00:00:00") $montos_aprobados[$rows_principal['cuenta']] = $montos_aprobados[$rows_principal['cuenta']] + $montos_aprobados_campo;
            }
            echo '<form action="" method="post">';
                echo '<table>';
                    foreach ($resultados as $cuenta => $datos)
                    {
                        echo '<tr>';
                            echo '<td colspan="7" style="color:red;">';
                                echo '<h4>'.$cuenta.'</h4>';
                            echo '</td>';
                            echo '<td>';
                                echo '<input type="checkbox" class="esta_persona" value="'.$cuenta.'">';
                            echo '</td>';
                            echo '<td style="padding:10px;">';
                                echo 'Total Aprobado = ';
                            echo '</td>';
                            echo '<td id="total_de_'.str_replace(" ", "_", str_replace(",", "", $cuenta)).'">';
                                // echo "0";
                                echo number_format($montos_aprobados[$cuenta]);
                            echo '</td>';
                        echo '</tr>';
                        $linea_separadora = '';
                        foreach ($datos as $id => $infos)
                        {
                            if(!empty($linea_separadora) and $infos[$campo_principal] != $linea_separadora and ($infos[$campo_principal] == $_POST['selector_seleccionado'] or $linea_separadora == $_POST['selector_seleccionado']))
                            {
                                echo '<tr>';
                                    echo '<td colspan="50">';
                                        echo '<hr>';
                                    echo '</td>';
                                echo '</tr>';
                            }
                            echo '<tr>';
                                foreach ($infos as $campo_nombre => $info)
                                {
                                    if(($campo_nombre != "derecho" and $campo_nombre != "obligacion") or ($info != 0))
                                    {
                                        $estilo_del_td = (strpos($info, ".00") !== false or ctype_digit($info)) ? 'text-align:right;' : '';
                                        echo '<td style="padding-right:15px;'.$estilo_del_td.'">';
                                            echo (strpos($info, ".00") !== false or ctype_digit($info)) ? number_format($info) : $info;
                                        echo '</td>';
                                    }
                                }
                                
                                $aprobacion = (isset($movimientos_aprobados[$id]) and $movimientos_aprobados[$id] == "0000-00-00 00:00:00") ? "" : "checked";
                                $color_aprobacion = (!empty($aprobacion)) ? 'green' : 'red';
                                $mensaje_aprobacion = (!empty($aprobacion)) ? 'Aprobado el '.$movimientos_aprobados[$id] : 'No Aprobado';
                                echo '<td>';
                                    if(empty($aprobacion)) echo '<input type="checkbox" class="'.$cuenta.'" name="aprobados[]" value="'.$id.'" '.$aprobacion.'>';
                                echo '</td>';
                                echo '<td id="td_aprobado" style="color:'.$color_aprobacion.';">';
                                    echo $mensaje_aprobacion;
                                echo '</td>';
                                $linea_separadora = $infos[$campo_principal];
                            echo '</tr>';
                        }
                    }
                    echo '<tr>';
                        echo '<td>';
                            if($campo_principal == "planilla") echo '<input type="hidden" name="planilla_aprobada" value="'.$_POST['selector_seleccionado'].'">';
                            echo "<input type='hidden' name='consulta_a_usar' value='".$consulta_principal."''>";//comillas diferentes porque la variable rompe el html "'""'"
                            echo '<input type="submit" name="aprobar" value="Aprobar">';
                        echo '</td>';
                    echo '</tr>';
                echo '</table>';
            echo '</form>';
        }
        else
        {
            if(isset($_POST['planilla_aprobada'])) echo '<h3>'.$_POST['planilla_aprobada'].'</h3>';

            echo '<table>';

                $query_aprobar = $conexion->prepare($_POST['consulta_a_usar']);
                $query_aprobar->execute();
                while($rows_aprobar = $query_aprobar->fetch(PDO::FETCH_ASSOC))
                {
                    echo '<tr>';
                        if(in_array($rows_aprobar['id'], $_POST['aprobados']))
                        {
                            if(isset($_POST['planilla_aprobada']) and $rows_aprobar['planilla'] != $_POST['planilla_aprobada'])
                            {
                                $obs_antes = (trim($rows_aprobar['observacion']) == "sin datos" or trim($rows_aprobar['observacion']) == "no aplicable") ? "" : $rows_aprobar['observacion']." / ";
                                $consulta_cambiar_planilla = 'UPDATE diario SET planilla = "'.$_POST['planilla_aprobada'].'", observacion = "'.$obs_antes.'Era planilla '.$rows_aprobar['planilla'].' pero fue aprobada en la planilla '.$_POST['planilla_aprobada'].'" WHERE id LIKE "'.$rows_aprobar['id'].'"';
                                $query_cambiar_planilla = $conexion->prepare($consulta_cambiar_planilla);
                                $query_cambiar_planilla->execute();
                            }
                            
                            $consulta_aprobar_2 = 'UPDATE diario SET aprobado = "'.date('Y-m-d G:i:s').'", aprobado_por = "'.$_SESSION['usuario_en_sesion'].'" WHERE id LIKE "'.$rows_aprobar['id'].'"';
                            echo '<td style="padding:5px;">';
                                echo $rows_aprobar['cuenta'];
                            echo '</td>';
                            echo '<td style="padding:5px;">';
                                echo $rows_aprobar['descripcion'];
                            echo '</td>';
                            echo '<td style="padding:5px;text-align:right;">';
                                echo ($rows_aprobar['derecho'] != 0) ? number_format($rows_aprobar['derecho']) : number_format($rows_aprobar['obligacion']);
                            echo '</td>';
                            try
                            {
                                $query_aprobar_2 = $conexion->prepare($consulta_aprobar_2);
                                $query_aprobar_2->execute();
                                echo '<td style="padding:5px;color:green;">';
                                    echo "APROBADO";
                                echo '</td>';
                            }
                            catch( PDOException $e )
                            {
                                echo '<td style="padding:5px;color:red;">';
                                    echo "NO APROBADO";
                                echo '</td>';
                            }
                        }
                    echo '</tr>';
                }
            echo '</table>';
        }

?>

<script type="text/javascript">
    
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    $('input[type=checkbox]').change(function(){
        
        if($(this).attr('class') == "esta_persona")
        {
            var esta_cuenta = $(this).val();
            var checkboxes_a_cambiar = $('input[class="'+esta_cuenta+'"]');

            if(this.checked)
            {
                checkboxes_a_cambiar.prop('checked', true);
                checkboxes_a_cambiar.closest('td').next('td').html('Aprobado');
                checkboxes_a_cambiar.closest('td').next('td').css("color", "green");
                checkboxes_a_cambiar.closest('td').prev('td').css("color", "green");
            }
            else
            {
                checkboxes_a_cambiar.prop('checked', false);
                checkboxes_a_cambiar.closest('td').next('td').html('No Aprobado');
                checkboxes_a_cambiar.closest('td').next('td').css("color", "red");
                checkboxes_a_cambiar.closest('td').prev('td').css("color", "red");
            }
        }
        else
        {
            var esta_cuenta = $(this).attr('class');
            $('input[class="esta_persona"][value="'+esta_cuenta+'"]').prop('checked', false);
            var checkboxes_a_cambiar = $('input[class="'+esta_cuenta+'"]');
            if(this.checked)
            {
                $(this).closest('td').next('td').html('Aprobado');
                $(this).closest('td').next('td').css("color", "green");
                $(this).closest('td').prev('td').css("color", "green");
            }
            else
            {
                $(this).closest('td').next('td').html('No Aprobado');
                $(this).closest('td').next('td').css("color", "red");
                $(this).closest('td').prev('td').css("color", "red");
            }
        }

        esta_cuenta_guiones = esta_cuenta.split(' ').join('_').replace(',','');
        total_de_esta_persona = $('#total_de_'+esta_cuenta_guiones).text().replace(/,/g , "");
        total_de_esta_persona = +total_de_esta_persona;

    
        if($(this).attr('class') == "esta_persona")
        {
            este_monto = 0;
            total_de_esta_persona = 0;
            if(this.checked)
            {
                checkboxes_a_cambiar.each(function(index, value){
                    este_monto_1 = $(this).closest('td').prev('td').text().replace(/,/g , "");
                    este_monto = +este_monto_1 + este_monto;
                });
            }
        }
        else
        {
            este_monto = $(this).closest('td').prev('td').text().replace(/,/g , "");
            este_monto = +este_monto;
        }
        
        if(this.checked)
        {
            total_de_esta_persona = total_de_esta_persona + este_monto;
        }
        else
        {
            total_de_esta_persona = total_de_esta_persona - este_monto;
        }

        $('#total_de_'+esta_cuenta_guiones).html(addCommas(total_de_esta_persona));

    });

</script>

