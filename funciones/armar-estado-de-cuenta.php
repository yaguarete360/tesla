<?php if (!isset($_SESSION)) {session_start();}
    
    $elementos_a_imprimir = array();
    echo '<form method="post" action="">';
        echo '<table>';
            echo '<tr>';
                echo '<td>';
                    echo 'Cuenta:&nbsp&nbsp';
                echo '</td>';
                echo '<td colspan="10">';
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
                    $valor = (isset($_POST['fecha_desde'])) ? $_POST['fecha_desde'] : date('Y-m-d');
                    echo '<input type="date" name="fecha_desde" value="'.$valor.'" style="width:100%">';
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td>';
                    echo 'Fecha Hasta:&nbsp&nbsp';
                echo '</td>';
                echo '<td>';
                    $valor = (isset($_POST['fecha_hasta'])) ? $_POST['fecha_hasta'] : date('Y-m-d');
                    echo '<input type="date" name="fecha_hasta" value="'.$valor.'" style="width:100%">';
                echo '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td colspan="2">';
                    echo '<input type="submit" class="submit_aprobar" name="buscar_proveedor" value="Buscar Cliente">&nbsp&nbsp';
                echo '</td>';
            echo '</tr>';
        echo '</table>';
        echo '<br/>';

        if(isset($_POST['buscar_proveedor']) and !empty($_POST['cuenta_a_buscar']))
        {
            $consulta_cuentas_control = 'SELECT count(id) AS contador_control, cuenta FROM cuentas
                WHERE borrado LIKE "no"
                    AND '.$tipo_de_cuenta.' > 0
                    AND cuenta LIKE "%'.$_POST['cuenta_a_buscar'].'%"
                GROUP BY cuenta
                ORDER BY cuenta ASC';
            $query_cuentas_control = $conexion->prepare($consulta_cuentas_control);
            $query_cuentas_control->execute();
            while($rows_cuentas_control = $query_cuentas_control->fetch(PDO::FETCH_ASSOC))
            {
                $cuentas_controles[$rows_cuentas_control['cuenta']] = ($rows_cuentas_control['contador_control'] > 1) ? $rows_cuentas_control['contador_control'] : 'ok';
            }

            $campos_contratos = array(
                'estado',
                'sitio_emprendimiento',
                'sitio_linea',
                'sitio_area',
                'sitio_sendero',
                'sitio_numero',
                'cuota_monto',
                'monto_diferido'
            );

            $contratos = array();
            $consulta_contratos = 'SELECT contratos.*, SUM(cuota_monto) AS cuota_monto FROM contratos
                WHERE borrado = "no"
                    AND cuenta = "'.$_POST['cuenta_a_buscar'].'"
                GROUP BY contrato
                ORDER BY contrato ASC';
            $query_contratos = $conexion->prepare($consulta_contratos);
            $query_contratos->execute();
            while($rows_contratos = $query_contratos->fetch(PDO::FETCH_ASSOC))
            {
                foreach ($campos_contratos as $campo_nombre)
                {
                    $contratos[$rows_contratos['contrato']][$campo_nombre] = $rows_contratos[$campo_nombre];
                }
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
                'fecha',
                'descripcion',
                'documento_tipo',
                'documento_numero',
                'factura_numero',
                'derecho',
                'obligacion',
                'moneda_extranjera_nombre',
                'moneda_extranjera_monto'
            );

            $filtro_por_tipo_de_cuenta = ($tipo_de_cuenta != 'todos') ? 'AND '.$tipo_de_cuenta.' > 0': '';
            $consulta_cuentas = 'SELECT id, cuenta, '.implode(', ', $campos_de_cuentas).' FROM cuentas
                WHERE borrado LIKE "no"
                    '.$filtro_por_tipo_de_cuenta.'
                    AND cuenta LIKE "%'.$_POST['cuenta_a_buscar'].'%"
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
                        if($contador_campos % 2 != 0) echo '</tr>';

                        echo '<tr>';
                            echo '<th>';
                                echo 'Contratos';
                            echo '</th>';
                            foreach ($campos_contratos as $campo_nombre)
                            {
                                echo '<th>';
                                    echo ucwords(str_replace('_', ' ', $campo_nombre)).': ';
                                echo '</th>';
                            }
                        echo '</tr>';
                        foreach ($contratos as $contrato => $contrato_campos)
                        {
                            echo '<tr>';
                                echo '<td>';
                                    echo '<b>';
                                        echo $contrato;
                                    echo '</b>';
                                echo '</td>';
                                foreach ($contrato_campos as $campo_nombre => $campo_valor)
                                {
                                    switch ($campo_nombre)
                                    {
                                        case 'cuota_monto':
                                        case 'monto_diferido':
                                            echo '<td style="text-align:right;">';
                                                echo number_format($campo_valor);
                                            echo '</td>';
                                        break;
                                        
                                        default:
                                            echo '<td>';
                                                echo $campo_valor;
                                            echo '</td>';
                                        break;
                                    }
                                }
                            echo '</tr>';
                        }

                        $saldos_anteriores[$cuenta]['derecho'] = 0;
                        $saldos_anteriores[$cuenta]['obligacion'] = 0;
                        $consulta_diario_saldo_anterior = 'SELECT sum(derecho) AS derecho_anterior, sum(obligacion) AS obligacion_anterior FROM diario
                            WHERE borrado LIKE "no"
                                AND cuenta LIKE "'.$cuenta.'"
                                AND fecha BETWEEN "0000-00-00" AND "'.date('Y-m-d', strtotime($_POST['fecha_desde'].' -1 day')).'"
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
                                AND fecha BETWEEN "'.$_POST['fecha_desde'].'" AND "'.$_POST['fecha_hasta'].'"
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
                                    $campo_diario = strtolower(str_replace('_', ' ', $campo_diario));
                                    $campo_diario = str_replace('moneda extranjera', 'mon. ext.', $campo_diario);
                                    $campo_diario = str_replace('documento', 'doc.', $campo_diario);
                                    echo '<th>'.ucwords($campo_diario).'</th>';
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
                                            case 'moneda_extranjera_monto':
                                                echo '<td style="text-align:right;">';
                                                    $decimales = ($dato_campo == 'moneda_extranjera_monto') ? 2 : 0;
                                                    echo number_format($dato_valor, $decimales);
                                                    if(isset($saldos_finales[$cuenta][$dato_campo])) $saldos_finales[$cuenta][$dato_campo]+= $dato_valor;
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
                                    echo 'Sumas A La Fecha '.$_POST['fecha_hasta'].': ';
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
                        $cuentas_a_imprimir[$cuenta] = $cuenta_datos;
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
            $elementos_a_imprimir['cuentas'] = $cuentas_a_imprimir;
            $elementos_a_imprimir['contratos'] = $contratos;
            $elementos_a_imprimir['saldos_anteriores'] = $saldos_anteriores;
            $elementos_a_imprimir['movimientos'] = $movimientos;
        }
    echo '</form>';
    if(isset($_POST['buscar_proveedor']) and !empty($_POST['cuenta_a_buscar']) and !empty($elementos_a_imprimir))
    {
        echo '<form method="post" action="../../funciones/imprimir-pdf-estado-de-cuenta.php">';
            $_SESSION['elementos_a_imprimir'] = $elementos_a_imprimir;
            // var_dump($elementos_a_imprimir);
            echo '<input type="hidden" name="titulo_del_listado" value="Estado de cuenta de '.ucwords($_POST['cuenta_a_buscar']).' entre las fechas '.$_POST['fecha_desde'].' y '.$_POST['fecha_hasta'].'">';
            echo '<input type="submit" name="imprimir" value="Imprimir">';
        echo '</form>';
    }

?>
