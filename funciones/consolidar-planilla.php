<?php if(!isset($_SESSION)) {session_start();}

echo '<form action="" method="post" class="nobr" id="formPrincipal">';
    echo '<table class="cargar-prestaciones">';

        echo '<tr>';
            $consulta = 'SELECT * 
                FROM planillas
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

if(isset($_POST['siguiente']) or isset($_POST['consolidar']))
{
    $esta_planilla_esta_en_diario = "no";
    $planilla_a_usar = (isset($_POST['planilla_seleccionada']) ? $_POST['planilla_seleccionada'] : $_POST['planilla_a_actualizar']);
    $consulta = 'SELECT * 
        FROM planillas
        WHERE borrado LIKE "no"
        AND planilla LIKE "'.$planilla_a_usar.'"
        ORDER BY cuenta ASC
        ';
    $query = $conexion->prepare($consulta);
    $query->execute();

    while($rows = $query->fetch(PDO::FETCH_ASSOC))
    {
        ($rows['derecho'] == 0) ? $campo_monto = "obligacion" : $campo_monto = "derecho";
        $planilla[$rows['cuenta']."-".$rows['cuenta_numero']][$rows['descripcion']."_".$campo_monto."_".$rows['aprobado']] = $rows[$campo_monto];
    }

$consulta = 'SELECT * FROM diario WHERE borrado LIKE "no" AND planilla LIKE "'.$planilla_a_usar.'" ORDER BY cuenta ASC LIMIT 1';
$query = $conexion->prepare($consulta);
$query->execute();
while($rows = $query->fetch(PDO::FETCH_ASSOC)) $esta_planilla_esta_en_diario = "si";

    echo '<table>';
        $saldo_final = 0;
        foreach ($planilla as $cuenta => $info)
        {
            $cuenta_partes = explode("-", $cuenta);
            $cuenta_nombre = $cuenta_partes[0];
            $cuenta_numero = $cuenta_partes[1];
            $saldo_individual = 0;
            echo '<tr>';
                echo '<td><h4>';
                    echo $cuenta_nombre." - ".$cuenta_numero;
                echo '</h4></td>';
            echo '</tr>';
            foreach ($info as $descripcion => $monto)
            {
                $descripcion_partes = explode("_", $descripcion);
                ($descripcion_partes[2] == "0000-00-00 00:00:00") ? $aprobado = 'style="color:red;font-style:italic;"' : $aprobado = '';
                ($descripcion_partes[2] == "0000-00-00 00:00:00") ? $aprobado_sino = "no" : $aprobado_sino = "si";
                echo '<tr>';
                    echo '<td '.$aprobado.'>';
                        echo $descripcion_partes[0];
                    echo '</td>';
                    $entra = 0;
                    $sale = 0;
                    $derecho = 0;
                    $obligacion = 0;

                    if($descripcion_partes[1] == "derecho")
                    {
                        echo '<td '.$aprobado.' style="text-align:right;">';
                            echo number_format($monto);
                        echo '</td>';
                        echo '<td '.$aprobado.'>';
                        echo '</td>';
                        if($aprobado_sino == "si")
                        {
                            $saldo_individual = $saldo_individual + $monto;
                            $saldo_final = $saldo_final + $monto;

                            $entra = 1;
                            $derecho = abs($monto);
                        }
                    }
                    else
                    {
                        echo '<td '.$aprobado.'>';
                        echo '</td>';
                        echo '<td '.$aprobado.' style="text-align:right;">';
                            echo number_format($monto);
                        echo '</td>';
                        if($aprobado_sino == "si")
                        {
                            $saldo_individual = $saldo_individual - $monto;
                            $saldo_final = $saldo_final - $monto;

                            $sale = 1;
                            $obligacion = abs($monto);
                        }
                    }
                    
                    if(isset($_POST['consolidar']) and $esta_planilla_esta_en_diario == "no" and $aprobado_sino == "si")
                    {
                        $consulta_actualizar_planilla_subido = 'UPDATE planillas SET
                            subido_a_diario = "'.date('Y-m-d G:i:s').'"
                            WHERE borrado LIKE "no"
                            AND aprobado NOT LIKE "0000-00-00%"
                            AND planilla LIKE "'.$_POST['planilla_a_actualizar'].'"
                            AND cuenta LIKE "'.$cuenta_nombre.'"
                            AND cuenta_numero LIKE "'.$cuenta_numero.'"
                            AND descripcion LIKE "'.$descripcion_partes[0].'"
                            ';
                        $query_aps = $conexion->prepare($consulta_actualizar_planilla_subido);
                        $query_aps->execute();
                        
                        $diario_a_insertar_campos_s = "dia,cuenta,cuenta_numero,agrupador_1,agrupador_2,agrupador_3,planilla,vencimiento,descripcion,cantidad,documento_tipo,documento_numero,modificacion,observacion,entra,sale,derecho,obligacion,origen,creado,contrato,cuota,movimiento_caja,asociacion,asociacion_numero,pagare,vendedor,cobrador_1,cobrador_2,cobrador_3,borrado,usuario";
                        $diario_a_insertar_campos_a = explode(",", $diario_a_insertar_campos_s);

                        $diario_a_insertar_valores_s = date('Y-m-d')."_".$cuenta_nombre."_".$cuenta_numero."_funcionarios_".$descripcion_partes[0]."_dependiente_".$_POST['planilla_a_actualizar']."_".date('Y-m-t')."_".$descripcion_partes[0]."_1_planilla de funcionarios_".$_POST['planilla_a_actualizar']."_no aplicable_no aplicable_".$entra."_".$sale."_".$derecho."_".$obligacion."_".$_SESSION['usuario_en_sesion']."_".date('Y-m-d G:i:s')."_no aplicable_no aplicable_sin datos_no aplicable_no aplicable_no aplicable_no aplicable_no aplicable_no aplicable_no aplicable_no_".$_SESSION['usuario_en_sesion'];
                        $diario_a_insertar_valores_a = explode("_", $diario_a_insertar_valores_s);

                        $consulta_insertar_diario = 'INSERT INTO diario (';
                        foreach ($diario_a_insertar_campos_a as $pos => $campo) $consulta_insertar_diario.= $campo.', ';
                        $consulta_insertar_diario = rtrim($consulta_insertar_diario, ", ");
                        $consulta_insertar_diario.= ') VALUES (';
                        foreach ($diario_a_insertar_valores_a as $pos => $valor) $consulta_insertar_diario.= '"'.$valor.'", ';
                        $consulta_insertar_diario = rtrim($consulta_insertar_diario, ", ").')';
                        $query_ied = $conexion->prepare($consulta_insertar_diario);
                        $query_ied->execute();
                    }
                    
                    echo '<td '.$aprobado.'style="padding-left:10px;">';
                        echo ($descripcion_partes[2] == "0000-00-00 00:00:00") ? "<b>No aprobado</b>" : $descripcion_partes[2];
                    echo '</td>';
                echo '</tr>';
            }

            echo '<tr>';
                echo '<td colspan="2" style="text-align:right;"><h5>';
                    echo "Saldo:&nbsp&nbsp";
                echo '</h5></td>';
                echo '<td style="text-align:right;"><h5>';
                    echo ($saldo_individual >= 0) ? "Derecho de" : "Obligacion de";
                echo '</h5></td>';
                echo '<td style="text-align:right;"><h5>';
                    echo number_format(abs($saldo_individual));
                echo '</h5></td>';
            echo '</tr>';
        }
        echo '<tr>';
            echo '<td colspan="2" style="text-align:right;"><h4>';
                echo "Saldo Final:&nbsp&nbsp";
            echo '</h4></td>';
            echo '<td style="text-align:right;"><h4>';
                echo ($saldo_final >= 0) ? "Derecho de " : "Obligacion de";
            echo '</h4></td>';
            echo '<td style="text-align:right;"><h4>';
                echo number_format(abs($saldo_final));
            echo '</h4></td>';
        echo '</tr>';

    echo '</table>';
    echo '<form action="" method="post" class="nobr">';
        echo '<br/>';
        echo '<br/>';
        echo '<input type="hidden" name="planilla_a_actualizar" value="'.$planilla_a_usar.'">';
        if($esta_planilla_esta_en_diario == "no" and !isset($_POST['consolidar'])) echo '<input type="submit" name="consolidar" value="Consolidar en Diario">';
    echo '</form>';
}

?>
