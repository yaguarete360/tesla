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
                AND aprobado LIKE "0000-00-00 00:00:00"
                AND '.$campo_principal.' LIKE "'.$_POST['selector_seleccionado'].'"
                '.$filtros_principales.'
                ORDER BY cuenta ASC';
            $query_principal = $conexion->prepare($consulta_principal);
            $query_principal->execute();

            while($rows_principal = $query_principal->fetch(PDO::FETCH_ASSOC))
            {
                if(!isset($resultados[$rows_principal['cuenta']])) $movimiento = 0;
                foreach ($campos_a_capturar as $pos => $campo_a_capturar)
                {
                    $resultados[$rows_principal['cuenta']][$movimiento][$campo_a_capturar] = $rows_principal[$campo_a_capturar];
                }
                $movimiento++;
            }
            echo '<form action="" method="post">';
                echo '<table>';
                    foreach ($resultados as $cuenta => $datos)
                    {
                        echo '<tr>';
                            echo '<td colspan="5" style="color:red;">';
                                echo '<h4>'.$cuenta.'</h4>';
                            echo '</td>';
                            echo '<td>';
                                echo '<input type="checkbox" name="aprobados[]" value="'.$cuenta.'">';
                            echo '</td>';
                            echo '<td id="td_aprobado" style="color:red;">';
                                echo 'No Aprobado';
                            echo '</td>';
                        echo '</tr>';
                        foreach ($datos as $movimiento_num => $infos)
                        {
                            echo '<tr>';
                                echo '<td>';
                                    echo $movimiento_num+1;
                                echo '</td>';
                                foreach ($infos as $campo_nombre => $info)
                                {
                                    if(($campo_nombre != "derecho" and $campo_nombre != "obligacion") or ($info != 0))
                                    {
                                        $estilo_del_td = (strpos($info, ".00") !== false) ? 'text-align:right;' : '';
                                        echo '<td style="'.$estilo_del_td.'">';
                                            echo (strpos($info, ".00") !== false) ? number_format($info) : $info;
                                        echo '</td>';
                                    }
                                }
                            echo '</tr>';
                        }
                    }
                    echo '<tr>';
                        echo '<td>';
                            echo "<input type='hidden' name='consulta_a_usar' value='".$consulta_principal."''>";//comillas diferentes porque rompe el html "'""'"
                            echo '<input type="submit" name="aprobar" value="Aprobar">';
                        echo '</td>';
                    echo '</tr>';
                echo '</table>';
            echo '</form>';
        }
        else
        {
            echo '<table>';


                $query_aprobar = $conexion->prepare($_POST['consulta_a_usar']);
                $query_aprobar->execute();
                while($rows_aprobar = $query_aprobar->fetch(PDO::FETCH_ASSOC))
                {
                    echo '<tr>';
                        if(in_array($rows_aprobar['cuenta'], $_POST['aprobados']))
                        {
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
    
    $('input[type=checkbox]').change(function(){

        if($(this).is(":checked"))
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
        
    });

</script>
