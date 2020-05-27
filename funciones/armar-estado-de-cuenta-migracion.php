<?php if (!isset($_SESSION)) {session_start();}
    
    $viene_de_otra_pagina = (isset($_GET['cu'])) ? true : false;
    if($viene_de_otra_pagina)
    {
        $cuenta_a_buscar = str_replace('_', ',', $_GET['cu']);
        $fecha_desde = (isset($_GET['fd'])) ? $_GET['fd'] : date('Y-m-d');
        $fecha_hasta = (isset($_GET['fh'])) ? $_GET['fh'] : date('Y-m-d');
    }

    if(isset($_POST['buscar_proveedor']) and !empty($_POST['cuenta_a_buscar']))
    {
        $cuenta_a_buscar = $_POST['cuenta_a_buscar'];
        $fecha_desde = $_POST['fecha_desde'];
        $fecha_hasta = $_POST['fecha_hasta'];
        $_SESSION['set_cuenta'] = $_POST['cuenta_a_buscar'];
    }

    echo '<form method="post" action="'.strtok($_SERVER['REQUEST_URI'], '?').'">';
        echo '<table>';
            echo '<tr>';
                echo '<td>';
                    echo 'Cuenta:&nbsp&nbsp';
                echo '</td>';
                echo '<td colspan="10">';
                    $valor = isset($cuenta_a_buscar) ? $cuenta_a_buscar : '';
                    $requerido = '';
                    $campo_nombre = 'cuenta_a_buscar';
                    $campos_filtro = array();
                    $campo_atributo['herramientas'] = 'cuentas-cuenta-'.$tipo_de_cuenta.'!=0';
                    $herramientas_explotado = explode("-", $campo_atributo['herramientas']);
                    $tabla_a_usar = $herramientas_explotado[0];
                    $campo_a_usar = $herramientas_explotado[1];
                    $herramientas_sub_explotado = explode("#", $herramientas_explotado[2]);
                    foreach ($herramientas_sub_explotado as $pos => $herramientas_sub) $campos_filtro[] = $herramientas_sub;
                    include '../../funciones/autocompletar-base.php';
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td>';
                    echo 'Fecha Desde:&nbsp&nbsp';
                echo '</td>';
                echo '<td>';
                    $valor = (isset($fecha_desde)) ? $fecha_desde : date('Y-m-d');
                    echo '<input type="date" name="fecha_desde" value="'.$valor.'" style="width:100%">';
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td>';
                    echo 'Fecha Hasta:&nbsp&nbsp';
                echo '</td>';
                echo '<td>';
                    $valor = (isset($fecha_hasta)) ? $fecha_hasta : date('Y-m-d');
                    echo '<input type="date" name="fecha_hasta" value="'.$valor.'" style="width:100%">';
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td colspan="2">';
                    echo '<input type="submit" class="submit_aprobar" name="buscar_proveedor" value="Buscar '.ucwords($tipo_de_cuenta).'">&nbsp&nbsp';
                echo '</td>';
            echo '</tr>';
        echo '</table>';
        echo '<br/>';

        if((isset($_POST['buscar_proveedor']) and !empty($_POST['cuenta_a_buscar'])) or $viene_de_otra_pagina)
        {
            $consulta_cuentas_control = 'SELECT count(id) AS contador_control, cuenta FROM cuentas
                WHERE borrado LIKE "no"
                    AND '.$tipo_de_cuenta.' > 0
                    AND cuenta LIKE "%'.$cuenta_a_buscar.'%"
                GROUP BY cuenta
                ORDER BY cuenta ASC';
            $query_cuentas_control = $conexion->prepare($consulta_cuentas_control);
            $query_cuentas_control->execute();
            while($rows_cuentas_control = $query_cuentas_control->fetch(PDO::FETCH_ASSOC))
            {
                $cuentas_controles[$rows_cuentas_control['cuenta']] = ($rows_cuentas_control['contador_control'] > 1) ? $rows_cuentas_control['contador_control'] : 'ok';
            }

            $campos_de_cuentas = array(
                $tipo_de_cuenta,
                'ruc',
                'identidad_tipo',
                'identidad_numero',
                'domicilio_calle',
                'domicilio_numero',
                'domicilio_interseccion',
                'domicilio_barrio',
                'domicilio_ciudad',
                'domicilio_pais',
                'telefono',
                'celular',
                'fax',
                'email',
                'contacto'
            );

            $campos_del_diario = array(
                'id',
                'cuota_vencimiento',
                'descripcion',
                'documento_tipo',
                'documento_numero',
                'factura_numero',
                'derecho',
                'obligacion',
                'efectuado_fecha'
            );

            $filtro_por_tipo_de_cuenta = ($tipo_de_cuenta != 'todos') ? 'AND '.$tipo_de_cuenta.' > 0': '';
            $consulta_cuentas = 'SELECT id, cuenta, '.implode(', ', $campos_de_cuentas).' FROM cuentas
                WHERE borrado LIKE "no"
                    '.$filtro_por_tipo_de_cuenta.'
                    AND cuenta LIKE "%'.$cuenta_a_buscar.'%"
                ORDER BY cuenta ASC';
            $query_cuentas = $conexion->prepare($consulta_cuentas);
            $query_cuentas->execute();
            while($rows_cuentas = $query_cuentas->fetch(PDO::FETCH_ASSOC))
            {
                $cuenta = $rows_cuentas['cuenta'];
                if($cuentas_controles[$cuenta] == 'ok')
                {
                    foreach ($campos_de_cuentas as $campo_nombre)
                    {
                        $este_valor = $rows_cuentas[$campo_nombre];
                        switch ($campo_nombre)
                        {
                            case 'domicilio_calle':
                            case 'domicilio_numero':
                            case 'domicilio_interseccion':
                            case 'domicilio_barrio':
                            case 'domicilio_ciudad':
                            case 'domicilio_pais':
                                if(!isset($cuentas[$cuenta]['domicilio'])) $cuentas[$cuenta]['domicilio'] = '';
                                if($este_valor != 'sin datos')
                                {
                                    $caracter_concatenacion = ', ';
                                    if($campo_nombre == 'domicilio_numero' or $campo_nombre == 'domicilio_interseccion') $caracter_concatenacion = ' ';
                                    if($campo_nombre == 'domicilio_calle' or empty($cuentas[$cuenta]['domicilio'])) $caracter_concatenacion = '';
                                    $cuentas[$cuenta]['domicilio'].= $caracter_concatenacion.$rows_cuentas[$campo_nombre];
                                }
                            break;

                            case 'telefono':
                            case 'celular':
                            case 'fax':
                                if(!isset($cuentas[$cuenta]['telefonos'])) $cuentas[$cuenta]['telefonos'] = '';
                                if($este_valor != 'sin datos')
                                {
                                    if(!empty($cuentas[$cuenta]['telefonos'])) $cuentas[$cuenta]['telefonos'].= '/';
                                    $cuentas[$cuenta]['telefonos'].= $rows_cuentas[$campo_nombre];
                                }
                            break;
                            
                            default:
                                $cuentas[$cuenta][$campo_nombre] = $rows_cuentas[$campo_nombre];
                            break;
                        }
                    }
                }
            }

            echo '<table class="tabla_linda">';
                $movimientos = array();
                echo '<tr>';
                echo '<th colspan="20">';
                echo '<a href="../../librerias/free-pdf/class/xml/beneficiarios.php" target="_blank">Imprimir Beneficiarios</a><br><br>';
                echo '</th>';
                echo '</tr>';

                /*echo '<tr>';
                echo '<th colspan="20">';
                echo '<a href="../../librerias/free-pdf/class/xml/estado_de_cuenta.php" target="_blank">Imprimir Estado de Cuenta</a><br><br>';
                echo '</th>';
                echo '</tr>';*/

                foreach ($cuentas as $cuenta => $cuenta_datos)
                {
                    if($cuentas_controles[$cuenta] == 'ok')
                    {
                        echo '<tr>';
                            echo '<th colspan="20">';
                                echo $cuenta;
                            echo '</th>';
                        echo '</tr>';
                        $contador_campos = 0;
                        foreach ($cuenta_datos as $dato_campo => $dato_valor)
                        {
                            if($contador_campos % 2 == 0) echo '<tr>';
                                echo '<td>';
                                    echo '<b>';
                                        echo ucwords(str_replace('_', ' ', $dato_campo)).': ';
                                    echo '</b>';
                                echo '</td>';
                                echo '<td>';
                                    echo $dato_valor;
                                echo '</td>';
                                $contador_campos++;
                            if($contador_campos % 2 == 0) echo '</tr>';
                        }

                        $saldos_anteriores[$cuenta]['derecho'] = 0;
                        $saldos_anteriores[$cuenta]['obligacion'] = 0;
                        $consulta_diario_saldo_anterior = 'SELECT sum(derecho) AS derecho_anterior, sum(obligacion) AS obligacion_anterior FROM diario
                            WHERE borrado LIKE "no"
                                AND cuenta LIKE "'.$cuenta.'"
                                AND fecha BETWEEN "0000-00-00" AND "'.date('Y-m-d', strtotime($fecha_desde.' -1 day')).'"
                            ORDER BY cuenta, fecha, id ASC';
                        $query_diario_saldo_anterior = $conexion->prepare($consulta_diario_saldo_anterior);
                        $query_diario_saldo_anterior->execute();
                        while($rows_diario_saldo_anterior = $query_diario_saldo_anterior->fetch(PDO::FETCH_ASSOC)) 
                        {
                            $saldos_anteriores[$cuenta]['derecho']+= $rows_diario_saldo_anterior['derecho_anterior'];
                            $saldos_anteriores[$cuenta]['obligacion']+= $rows_diario_saldo_anterior['obligacion_anterior'];
                        }
                        $saldos_finales[$cuenta]['derecho'] = $saldos_anteriores[$cuenta]['derecho'];
                        $saldos_finales[$cuenta]['obligacion'] = $saldos_anteriores[$cuenta]['obligacion'];

                        if(!isset($movimientos[$cuenta])) $movimientos[$cuenta] = array();
                        $consulta_diario_rango = 'SELECT * FROM diario
                            WHERE borrado LIKE "no"
                                AND cuenta LIKE "'.$cuenta.'"
                                AND fecha BETWEEN "'.$fecha_desde.'" AND "'.$fecha_hasta.'"
                            ORDER BY cuenta, fecha, id ASC';
                        $query_diario_rango = $conexion->prepare($consulta_diario_rango);
                        $query_diario_rango->execute();
                        while($rows_diario_rango = $query_diario_rango->fetch(PDO::FETCH_ASSOC))
                        {
                            $id_diario = $rows_diario_rango['id'];
                            foreach ($campos_del_diario as $campo_diario)
                            {
                                $movimientos[$cuenta][$id_diario][$campo_diario] = $rows_diario_rango[$campo_diario];
                            }
                        }

                        echo '<tr><th colspan="20">Detalle</th></tr>';
                        if(empty($movimientos[$cuenta]))
                        {
                            echo '<tr><td style="background-color:white;" colspan="20">'.ucwords($cuenta).' no tiene movimientos en este periodo.</td></tr>';
                        }
                        else
                        {
                            echo '<tr>';
                                foreach ($campos_del_diario as $campo_diario)
                                {
                                    echo '<th>'.ucwords(str_replace('_', ' ', $campo_diario)).'</th>';
                                }
                            echo '</tr>';
                            
                            echo '<tr>';
                                echo '<td colspan="'.array_search('derecho', $campos_del_diario).'">';
                                    echo 'Movimientos Anteriores';
                                echo '</td>';
                                echo '<td style="text-align:right;">';
                                    echo number_format($saldos_anteriores[$cuenta]['derecho']);
                                echo '</td>';
                                echo '<td style="text-align:right;">';
                                    echo number_format($saldos_anteriores[$cuenta]['obligacion']);
                                echo '</td>';
                            echo '</tr>';
                            foreach ($movimientos[$cuenta] as $movimiento_id => $movimiento_datos)
                            {
                                echo '<tr>';
                                    foreach ($movimiento_datos as $dato_campo => $dato_valor)
                                    {
                                        switch ($dato_campo)
                                        {
                                            case 'derecho':
                                            case 'obligacion':
                                                echo '<td style="text-align:right;">';
                                                    echo number_format($dato_valor);
                                                    $saldos_finales[$cuenta][$dato_campo]+= $dato_valor;
                                                echo '</td>';
                                            break;
                                            
                                            default:
                                                echo '<td>';
                                                    echo $dato_valor;
                                                echo '</td>';
                                            break;
                                        }
                                    }
                                echo '</tr>';
                            }
                            echo '<tr>';
                                echo '<td colspan="'.array_search('derecho', $campos_del_diario).'">';
                                    echo 'Sumas A La Fecha '.$fecha_hasta.': ';
                                echo '</td>';
                                echo '<td style="text-align:right;">';
                                    echo number_format($saldos_finales[$cuenta]['derecho']);
                                echo '</td>';
                                echo '<td style="text-align:right;">';
                                    echo number_format($saldos_finales[$cuenta]['obligacion']);
                                echo '</td>';
                            echo '</tr>';
                            echo '<tr>';
                                $saldo_final = $saldos_finales[$cuenta]['derecho'] - $saldos_finales[$cuenta]['obligacion'];
                                $saldo_final_derecho = ($saldo_final >= 0) ? $saldo_final : 0;
                                $saldo_final_obligacion = ($saldo_final < 0) ? $saldo_final * -1 : 0;
                                echo '<td colspan="'.array_search('derecho', $campos_del_diario).'">';
                                    echo 'Saldo: ';
                                echo '</td>';
                                echo '<td style="text-align:right;">';
                                    echo number_format($saldo_final_derecho);
                                echo '</td>';
                                echo '<td style="text-align:right;">';
                                    echo number_format($saldo_final_obligacion);
                                echo '</td>';
                            echo '</tr>';
                        }

                        echo '<tr style="background-color:white;"><td colspan="20">&nbsp</td></tr>';
                    }
                    else
                    {
                        if(!isset($cuentas_controladas[$cuenta]))
                        {
                            echo '<i>La cuenta <b>'.$cuenta.'</b> se repite '.$cuentas_controles[$cuenta].' veces. Ver con un programador para corregir.</i><br/>';
                        }
                        $cuentas_controladas[$cuenta] = 'si';
                    }
                }
            echo '</table>';
        }
    echo '</form>';

?>
