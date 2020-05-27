<?php if(!isset($_SESSION)) {session_start();}
    
    $planilla_seleccionada = $_POST['planilla_seleccionada'];

    $titulo_del_listado = $descripcion_a_buscar.' '.strtoupper($planilla_seleccionada);

    $ruta_de_archivos = '../../vistas/sintesis/archivos de sueldos/';
    $nombre_del_archivo = date('Ymd').'-sueldos-'.$descripcion_a_buscar.'-'.$relacion;
    $archivo_pdf = $ruta_de_archivos.$nombre_del_archivo.'.pdf';
    $archivo_txt = $ruta_de_archivos.$nombre_del_archivo.'.txt';
    
    // $descripcion_a_insertar = ($descripcion_a_buscar != 'cancelacion del sueldo a cobrar') ? 'pago de '.$descripcion_a_buscar : $descripcion_a_buscar;
    $descripcion_a_insertar = (strpos($descripcion_a_buscar, 'cancelacion') !== false) ? $descripcion_a_buscar : 'pago de '.$descripcion_a_buscar;

    $campos_a_insertar_diario['diario'] = '';
    $campos_a_insertar_diario['planilla'] = $planilla_seleccionada;
    $campos_a_insertar_diario['fecha'] = date('Y-m-d');
    $campos_a_insertar_diario['cuenta'] = '';
    $campos_a_insertar_diario['cuenta_numero'] = '';
    $campos_a_insertar_diario['cuenta_documento_tipo'] = '';
    $campos_a_insertar_diario['cuenta_documento_numero'] = '';
    $campos_a_insertar_diario['contrato'] = 'no aplicable';
    $campos_a_insertar_diario['documento_tipo'] = '';
    $campos_a_insertar_diario['documento_numero'] = '';
    $campos_a_insertar_diario['descripcion'] = $descripcion_a_insertar;
    $campos_a_insertar_diario['observacion'] = 'no aplicable';
    $campos_a_insertar_diario['aprobado'] = date('Y-m-d G:i:s');
    $campos_a_insertar_diario['aprobado_por'] = $_SESSION['usuario_en_sesion'];
    $campos_a_insertar_diario['cantidad'] = '1';
    $campos_a_insertar_diario['cuota'] = 'no aplicable';
    $campos_a_insertar_diario['efectuado_fecha'] = date('Y-m-d G:i:s');
    $campos_a_insertar_diario['efectuado_por'] = $_SESSION['usuario_en_sesion'];
    $campos_a_insertar_diario['factura_tipo'] = 'no aplicable';
    $campos_a_insertar_diario['factura_numero'] = 'no aplicable';
    $campos_a_insertar_diario['cuenta_bancaria_titular'] = '';
    $campos_a_insertar_diario['cuenta_bancaria_banco'] = '';
    $campos_a_insertar_diario['cuenta_bancaria_numero'] = '';
    $campos_a_insertar_diario['entra'] = '';
    $campos_a_insertar_diario['sale'] = '';
    $campos_a_insertar_diario['derecho'] = '';
    $campos_a_insertar_diario['obligacion'] = '';
    $campos_a_insertar_diario['creado'] = date('Y-m-d G:i:s');
    $campos_a_insertar_diario['borrado'] = 'no';
    $campos_a_insertar_diario['usuario'] = $_SESSION['usuario_en_sesion'];

    $consulta_insertar_base = 'INSERT INTO diario (';
    foreach ($campos_a_insertar_diario as $campo_nombre => $campo_valor) $consulta_insertar_base.= $campo_nombre.', ';
    $consulta_insertar_base = rtrim($consulta_insertar_base, ', ').') VALUES (';

    $consulta_organigrama = 'SELECT * 
        FROM organigrama
        WHERE borrado LIKE "no"
        ORDER BY organigrama ASC';
    $query_organigrama = $conexion->prepare($consulta_organigrama);
    $query_organigrama->execute();
    while($rows_organigrama = $query_organigrama->fetch(PDO::FETCH_ASSOC))
    {
        $organigrama[$rows_organigrama['organigrama']] = $rows_organigrama['seccion'];
        foreach ($campos_datos_extra_organigrama as $pos => $campo_datos_extra_org)
        {
            $datos_extra_organigrama[$rows_organigrama['organigrama']][$campo_datos_extra_org] = $rows_organigrama[$campo_datos_extra_org];
        }
    }

    $consulta_cuentas = 'SELECT * 
        FROM cuentas
        WHERE borrado LIKE "no"
        ORDER BY cuenta ASC';
    $query_cuentas = $conexion->prepare($consulta_cuentas);
    $query_cuentas->execute();
    while($rows_cuentas = $query_cuentas->fetch(PDO::FETCH_ASSOC))
    {
        foreach ($campos_datos_extra_cuentas as $pos => $campo_datos_extra_cue)
        {
            $datos_extra_cuentas[$rows_cuentas['cuenta']][$campo_datos_extra_cue] = $rows_cuentas[$campo_datos_extra_cue];
        }
    }

    $ya_fue_cancelado = 'no';
    $esta_para_efectuar = 'si';
    // $filtro_cancelacion = ($descripcion_a_buscar == 'cancelacion del sueldo a cobrar') ? '' : 'AND diario.efectuado_fecha LIKE "0000-00-00" AND diario.descripcion LIKE "'.$descripcion_a_buscar.'"';
    $filtro_cancelacion = (strpos($descripcion_a_buscar, 'cancelacion') !== false) ? '' : 'AND diario.efectuado_fecha LIKE "0000-00-00" AND diario.descripcion LIKE "'.$descripcion_a_buscar.'"';
    $consulta_datos = 'SELECT diario.id, diario.cuenta, diario.descripcion, diario.derecho, diario.obligacion, diario.aprobado, diario.aprobado_por, diario.efectuado_fecha, diario.efectuado_por
        FROM diario
        INNER JOIN organigrama ON organigrama.organigrama LIKE diario.cuenta
        WHERE diario.borrado LIKE "no"
        AND diario.aprobado NOT LIKE "0000-00-00 00:00:00"
        '.$filtro_cancelacion.'
        AND diario.planilla LIKE "'.$planilla_seleccionada.'"
        AND organigrama.borrado LIKE "no"
        AND organigrama.relacion LIKE "'.$relacion.'"
        ORDER BY organigrama.seccion, diario.cuenta, diario.id ASC';
        // AND organigrama.finalizacion LIKE "0000-00-00%"
    $query_datos = $conexion->prepare($consulta_datos);
    $query_datos->execute();
    while($rows_datos = $query_datos->fetch(PDO::FETCH_ASSOC))
    {
        $cuenta = $rows_datos['cuenta'];
        if(isset($_POST['finalizar_planilla']) and $rows_datos['efectuado_fecha'] == '0000-00-00' and in_array($cuenta, $_POST['cuentas_a_efectuar'])) $ids_a_efectuar[] = $rows_datos['id'];
        $seccion = $organigrama[$cuenta];
        $descripcion = $rows_datos['descripcion'];

        if(!isset($movimientos_diario[$seccion][$cuenta][$descripcion]['obligacion'])) $movimientos_diario[$seccion][$cuenta][$descripcion]['obligacion'] = 0;
        if(!isset($movimientos_diario[$seccion][$cuenta][$descripcion]['derecho'])) $movimientos_diario[$seccion][$cuenta][$descripcion]['derecho'] = 0;

        $movimientos_diario[$seccion][$cuenta][$descripcion]['obligacion'] += $rows_datos['obligacion'];
        $movimientos_diario[$seccion][$cuenta][$descripcion]['derecho'] += $rows_datos['derecho'];
        $movimientos_diario[$seccion][$cuenta][$descripcion]['aprobado'] = $rows_datos['aprobado'];
        $movimientos_diario[$seccion][$cuenta][$descripcion]['aprobado_por'] = $rows_datos['aprobado_por'];
        if($rows_datos['descripcion'] == 'cancelacion del sueldo a cobrar' and $descripcion_a_buscar != 'cancelacion del saldo corregido')
        {
            //$ya_fue_cancelado = 'si';
            //break;
        }
        // echo $rows_datos['cuenta'];
        // var_dump($datos_extra_organigrama[$rows_datos['cuenta']]['cuenta_itau']);
        // var_dump((!empty($datos_extra_organigrama[$rows_datos['cuenta']]['cuenta_itau']) and ctype_digit($datos_extra_organigrama[$rows_datos['cuenta']]['cuenta_itau'])));
        // echo '<br/>';

        if(isset($_POST['forma_de_pago']) and ($_POST['forma_de_pago'] == 'itau pago a proveedores' or $_POST['forma_de_pago'] == 'itau planilla electronica de salarios'))
        {
            if(!empty($datos_extra_organigrama[$rows_datos['cuenta']]['cuenta_itau']) and ctype_digit($datos_extra_organigrama[$rows_datos['cuenta']]['cuenta_itau']))
            {
                $datos_extra_para_exportar[$rows_datos['cuenta']]['cuenta_itau'] = $datos_extra_organigrama[$rows_datos['cuenta']]['cuenta_itau'];
            }
            else
            {
                $esta_para_efectuar = 'no';
                $razon_no_esta_para_efectuar = $rows_datos['cuenta'].' no tiene cuenta itau';
            }
        }
    }

    if($descripcion_a_buscar == 'cancelacion del sueldo a cobrar' and $rows_datos['descripcion'] == 'cancelacion del sueldo a cobrar') unset($movimientos_diario);
echo '<form action="" method="post">';
    echo '<table class="tabla_linda">';
        if(isset($movimientos_diario))
        {
            if($descripcion_a_buscar == 'cancelacion del saldo corregido')
            {
                foreach ($movimientos_diario as $seccion => $cuentas)
                {
                    $saldos_cuentas = array();
                    foreach ($cuentas as $cuenta => $descripciones)
                    {
                        foreach ($descripciones as $descripcion => $movimiento)
                        {
                            $saldos_cuentas[$cuenta] += ($movimiento['obligacion'] - $movimiento['derecho']);
                        }
                    }

                    foreach ($saldos_cuentas as $cuenta => $saldo)
                    {
                        if($saldo == 0) unset($movimientos_diario[$seccion][$cuenta]);
                    }
                    if(empty($movimientos_diario[$seccion])) unset($movimientos_diario[$seccion]);
                }
            }
            
            $i = 0;
            $fila = 0;
            $elementos_a_imprimir[$fila]['seccion'] = 'seccion';
            $elementos_a_imprimir[$fila]['cuenta'] = 'cuenta';
            $elementos_a_imprimir[$fila][$descripcion_a_insertar] = $descripcion_a_insertar;
            $elementos_a_imprimir[$fila]['aprobado'] = 'aprobado';
            
            $totales['final']['derecho'] = 0;
            $totales['final']['obligacion'] = 0;
            foreach ($movimientos_diario as $seccion => $cuentas)
            {
                $totales[$seccion]['derecho'] = 0;
                $totales[$seccion]['obligacion'] = 0;
                echo '<tr>';
                    echo '<th colspan="10">';
                        echo '<h4>'.$seccion.'</h4>';
                    echo '</th>';
                echo '</tr>';
                echo '<tr>';
                    echo '<th>';
                        echo 'Descripcion';
                    echo '</td>';
                    echo '<th>';
                        echo 'Obligacion';
                    echo '</td>';
                    echo '<th>';
                        echo 'Derecho';
                    echo '</td>';
                echo '</tr>';
                foreach ($cuentas as $cuenta => $descripciones)
                {
                    $i++;
                    $saldos_cuentas[$cuenta] = 0;
                    echo '<tr>';
                        echo '<td colspan="10" style="background-color:#ebcfad;">';
                            echo '<h6>'.$i.' - '.$cuenta.'</h6>';
                        echo '</td>';
                        echo '<td style="background-color:#ebcfad;">';
                            echo '<input type="checkbox" name="cuentas_a_efectuar[]" value="'.$cuenta.'">';
                        echo '</td>';
                    echo '</tr>';
                    foreach ($descripciones as $descripcion => $movimiento)
                    {
                        echo '<tr>';
                            echo '<td>';
                                echo $descripcion;
                            echo '</td>';
                            echo '<td style="text-align:right;">';
                                echo number_format($movimiento['obligacion']);

                            echo '</td>';
                            echo '<td style="text-align:right;">';
                                echo number_format($movimiento['derecho']);
                            echo '</td>';
                        echo '</tr>';
                        $saldos_cuentas[$cuenta] += ($movimiento['obligacion'] - $movimiento['derecho']);
                    }

                    if($saldos_cuentas[$cuenta] < 0)
                    {
                        $montos_a_mostrar['entra'] = 0;
                        $montos_a_mostrar['sale'] = 1;
                        $montos_a_mostrar['derecho'] = 0;
                        $montos_a_mostrar['obligacion'] = $saldos_cuentas[$cuenta] * -1;
                    }
                    else
                    {
                        $montos_a_mostrar['entra'] = 1;
                        $montos_a_mostrar['sale'] = 0;
                        $montos_a_mostrar['derecho'] = $saldos_cuentas[$cuenta];
                        $montos_a_mostrar['obligacion'] = 0;
                    }
                    $totales[$seccion]['derecho'] += $montos_a_mostrar['derecho'];
                    $totales[$seccion]['obligacion'] += $montos_a_mostrar['obligacion'];

                    $fila++;

                    if(isset($_POST['finalizar_planilla']) and $esta_para_efectuar == 'si' and in_array($cuenta, $_POST['cuentas_a_efectuar']))
                    {
                        $elementos_a_imprimir[$fila]['seccion'] = $seccion;
                        $elementos_a_imprimir[$fila]['cuenta'] = $cuenta;
                        $elementos_a_imprimir[$fila][$descripcion_a_insertar] = ($montos_a_mostrar['obligacion'] == 0) ? $montos_a_mostrar['derecho'] : $montos_a_mostrar['obligacion'];
                        $elementos_a_imprimir[$fila]['aprobado'] = 'Aprobado el '.date('Y-m-d G:i:s').' por '.ucwords($_SESSION['usuario_en_sesion']);
                        
                        $elementos_a_exportar[$cuenta] = ($montos_a_mostrar['derecho'] > 0) ? $montos_a_mostrar['derecho'] : $montos_a_mostrar['obligacion'];
                        
                        if($montos_a_mostrar['obligacion'] == 0 and $montos_a_mostrar['derecho'] > 0) // el saldo a pagar tiene que ser derecho
                        {
                            // $elementos_a_exportar[$cuenta] = $montos_a_mostrar['derecho'];
                            $consulta_insertar = $consulta_insertar_base;
                            foreach ($campos_a_insertar_diario as $campo_nombre => $campo_valor)
                            {
                                switch ($campo_valor)
                                {
                                    case '':
                                        switch ($campo_nombre)
                                        {
                                            case 'diario':
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
                                                $numero_a_usar = date('Y')."-".str_pad(explode("-", $ultimo_numero)[1]+1, 7, "0", STR_PAD_LEFT);
                                                $consulta_insertar.= '"'.$numero_a_usar.'", ';
                                            break;

                                            $campos_datos_extra = array('cuenta_itau', 'documento_numero', 'relacion', 'numero_asegurado_ips', 'salario_basico');

                                            case 'cuenta':
                                                $consulta_insertar.= '"'.$cuenta.'", ';
                                            break;

                                            case 'cuenta_numero':
                                                $cuenta_numero = (isset($datos_extra_cuentas[$cuenta]['funcionario'])) ? $datos_extra_cuentas[$cuenta]['funcionario'] : 'sin datos';
                                                $consulta_insertar.= '"'.$cuenta_numero.'", ';
                                            break;

                                            case 'cuenta_documento_tipo':
                                                $consulta_insertar.= '"'.$datos_extra_organigrama[$cuenta]['documento_tipo'].'", ';
                                            break;

                                            case 'cuenta_documento_numero':
                                                $consulta_insertar.= '"'.$datos_extra_organigrama[$cuenta]['documento_numero'].'", ';
                                            break;

                                            case 'documento_tipo':
                                                $consulta_insertar.= '"'.$_POST['forma_de_pago'].'", ';
                                            break;

                                            case 'documento_numero':
                                                $consulta_insertar.= '"'.$_POST['forma_de_pago_numero'].'", ';
                                            break;
                                            
                                            case 'cuenta_bancaria_titular':
                                            case 'cuenta_bancaria_banco':
                                            case 'cuenta_bancaria_numero':
                                                if(isset($datos_extra_organigrama[$cuenta]['cuenta_itau']) or isset($datos_extra_organigrama[$cuenta][$campo_nombre]))
                                                {
                                                    if($campo_nombre == 'cuenta_bancaria_titular') $datos_cuenta_bancaria_valor = $cuenta;
                                                    if($campo_nombre == 'cuenta_bancaria_banco') $datos_cuenta_bancaria_valor = 'itau';
                                                    if($campo_nombre == 'cuenta_bancaria_numero') $datos_cuenta_bancaria_valor = $datos_extra_organigrama[$cuenta]['cuenta_itau'];
                                                    $datos_cuenta_bancaria = (isset($datos_extra_organigrama[$cuenta]['cuenta_itau'])) ? $datos_cuenta_bancaria_valor : $datos_extra_organigrama[$cuenta][$campo_nombre];
                                                }
                                                else
                                                {
                                                    $datos_cuenta_bancaria = 'no aplicable';
                                                }
                                                $consulta_insertar.= '"'.$datos_cuenta_bancaria.'", ';
                                            break;

                                            case 'entra':
                                            case 'sale':
                                            case 'derecho':
                                            case 'obligacion':
                                                $consulta_insertar.= '"'.$montos_a_mostrar[$campo_nombre].'", ';
                                            break;
                                            
                                            default:
                                                $consulta_insertar.= '"sin datos", ';
                                            break;
                                        }
                                    break;
                                    
                                    default:
                                        $consulta_insertar.= '"'.$campo_valor.'", ';
                                    break;
                                }
                            }
                            $consulta_insertar = rtrim($consulta_insertar, ', ').')';
                            // echo $consulta_insertar;
                            // echo '<br/>';

                            $query_insertar = $conexion->prepare($consulta_insertar);
                            $query_insertar->execute();
                        }
                        
                        if($descripcion_a_buscar != 'cancelacion del sueldo a cobrar')
                        {
                            $consulta_actualizar_efectuado = 'UPDATE diario SET
                                documento_tipo = "'.$_POST['forma_de_pago'].'",
                                documento_numero = "'.$_POST['forma_de_pago_numero'].'",
                                efectuado_fecha = "'.date('Y-m-d').'",
                                efectuado_por = "'.$_SESSION['usuario_en_sesion'].'"
                                WHERE borrado LIKE "no"
                                    AND cuenta LIKE "'.$cuenta.'"
                                    AND descripcion LIKE "'.$descripcion_a_buscar.'"
                                    AND planilla LIKE "'.$planilla_seleccionada.'"
                                    AND efectuado_fecha LIKE "0000-00-00"
                                    AND (efectuado_por LIKE ""
                                        OR efectuado_por LIKE "sin datos"
                                        OR efectuado_por LIKE "no aplicable")';
                            echo $consulta_actualizar_efectuado;
                            echo '<br/>';
                            
                            $query_actualizar_efectuado = $conexion->prepare($consulta_actualizar_efectuado);
                            $query_actualizar_efectuado->execute();
                        }
                    }
                    
                    echo '<tr>';
                        echo '<th>';
                            echo '<b>Saldo A Cancelar: ';
                        echo '</th>';
                        echo '<th style="text-align:right;">';
                            echo number_format($montos_a_mostrar['obligacion']);
                        echo '</th>';
                        echo '<th style="text-align:right;">';
                            echo number_format($montos_a_mostrar['derecho']);
                        echo '</b></th>';
                        if(isset($_POST['finalizar_planilla']) and $esta_para_efectuar == 'si' and in_array($cuenta, $_POST['cuentas_a_efectuar']))
                        {
                            echo '<td style="color:green;">';
                                echo 'Efectuado!';
                            echo '</td>';
                        }
                        elseif(isset($_POST['finalizar_planilla']) and $esta_para_efectuar == 'no' and in_array($cuenta, $_POST['cuentas_a_efectuar']))
                        {
                            echo '<tr>';
                                echo '<td style="color:red;">';
                                    echo $razon_no_esta_para_efectuar;
                                echo '</td>';
                            echo '</tr>';
                        }
                    echo '</tr>';
                }
                
                echo '<tr>';
                    echo '<td>';
                        echo 'Total de '.ucwords($seccion).' = ';
                    echo '</td>';
                    echo '<td style="text-align:right;">';
                        if($totales[$seccion]['obligacion'] != 0) echo number_format($totales[$seccion]['obligacion']);
                        $totales['final']['obligacion'] += $totales[$seccion]['obligacion'];
                    echo '</td>';
                    echo '<td style="text-align:right;">';
                        if($totales[$seccion]['derecho'] != 0) echo number_format($totales[$seccion]['derecho']);
                        $totales['final']['derecho'] += $totales[$seccion]['derecho'];
                    echo '</td>';
                echo '</tr>';
                
                echo '<tr>';
                    echo '<td colspan="10">';
                        echo '&nbsp';
                    echo '</td>';
                echo '</tr>';
            }
            echo '<tr>';
                echo '<td>';
                    echo 'Total = ';
                echo '</td>';
                echo '<td style="text-align:right;">';
                    if($totales['final']['obligacion'] != 0) echo number_format($totales['final']['obligacion']);
                echo '</td>';
                echo '<td style="text-align:right;">';
                    if($totales['final']['derecho'] != 0) echo number_format($totales['final']['derecho']);
                echo '</td>';
            echo '</tr>';

            if(!isset($_POST['finalizar_planilla']))
            {
                // if(!file_exists($archivo_pdf) and !file_exists($archivo_txt) and $esta_para_efectuar == 'si')
                if($esta_para_efectuar == 'si')
                {
                    // if(isset($elementos_a_imprimir) and !empty($elementos_a_imprimir) and isset($elementos_a_exportar) and !empty($elementos_a_exportar))
                    // {
                        // echo '<form action="" method="post">';
                            // settings para el PDF \\
                            echo '<input type="hidden" name="titulo_del_listado" value="'.$titulo_del_listado.'">';
                            echo '<input type="hidden" name="columnas_a_sumar[]" value="'.$descripcion_a_insertar.'">';
                            echo '<input type="hidden" name="grupo_separador" value="seccion">';
                            $pdf_num = 0;
                            while ($pdf_num < 5)
                            {
                                $pdf_num++;
                                if(file_exists($archivo_pdf))
                                {
                                    $nombre_del_archivo_viejo = $nombre_del_archivo;
                                    $nombre_del_archivo = $nombre_del_archivo.'-'.$pdf_num;
                                    $archivo_pdf = str_replace($nombre_del_archivo_viejo, $nombre_del_archivo, $archivo_pdf);
                                }
                                else
                                {
                                    break;
                                }
                            }
                            echo '<input type="hidden" name="guardar_pdf" value="'.$archivo_pdf.'">';
                            
                            // settings para el TXT \\
                            echo '<input type="hidden" name="nombre_del_archivo" value="'.$nombre_del_archivo.'">';
                            echo '<input type="hidden" name="concepto_del_debito" value="'.$concepto_del_debito.'">';// ADELANTO SALARIOS
                            echo '<input type="hidden" name="referencia_del_debito" value="'.$referencia_del_debito.'">'; // 4

                            $consulta_formas_de_pago = 'SELECT descripcion
                                FROM agrupadores
                                WHERE borrado LIKE "no"
                                AND agrupador LIKE "formas de pago a funcionarios"
                                ORDER BY descripcion ASC';
                            $query_formas_de_pago = $conexion->prepare($consulta_formas_de_pago);
                            $query_formas_de_pago->execute();
                            echo '<tr>';
                                echo '<th>';
                                    echo 'Forma De Pago';
                                echo '</th>';
                                echo '<th>';
                                    echo 'Forma De Pago Numero';
                                echo '</th>';
                            echo '</tr>';

                            echo '<tr>';
                                echo '<td>';
                                    // switch ($relacion)
                                    // {
                                    //     case 'dependiente':
                                    //         echo ucwords("itau planilla electronica de salarios: ");
                                    //         echo '<input type="hidden" name="forma_de_pago" value="itau planilla electronica de salarios">';
                                    //     break;
                                        
                                    //     default:
                                            echo '<select name="forma_de_pago" required>';
                                                echo '<option value="" selected></option>';
                                                while($rows_formas_de_pago = $query_formas_de_pago->fetch(PDO::FETCH_ASSOC))
                                                {
                                                    echo '<option value="'.$rows_formas_de_pago['descripcion'].'">'.$rows_formas_de_pago['descripcion'].'</option>';
                                                }
                                            echo '</select>';
                                    //     break;
                                    // }
                                echo '</td>';
                                echo '<td>';
                                    echo '<input type="text" name="forma_de_pago_numero" value="" required>';
                                echo '</td>';

                                echo '<td>';
                                    echo '<input type="hidden" name="planilla_seleccionada" value="'.$planilla_seleccionada.'">';
                                    echo '<input type="hidden" name="seleccionar_filtro_principal" value="'.$_POST['seleccionar_filtro_principal'].'">';
                                    echo '<input type="hidden" name="relacion_seleccionada" value="'.$_POST['relacion_seleccionada'].'">';
                                    echo '<input type="submit" name="finalizar_planilla" value="Finalizar Planilla">';
                                echo '</td>';
                            echo '</tr>';
                        // echo '</form>';
                    // }
                    // else
                    // {
                    //     echo '<tr>';
                    //         echo '<td style="color:red;">';
                    //             echo 'Los datos no son validos para finalizar.';
                    //         echo '</td>';
                    //     echo '</tr>';
                    // }
                }
                else
                {
                    echo '<a href="../../vistas/reportes/funcionarios-listado_de_archivos_sueldos.php">';
                        echo 'Ir al Listado de Archivos de Sueldos';
                    echo '</a>';
                    echo '<tr>';
                        echo '<td style="color:red;">';
                            echo 'Los datos no son validos para finalizar.<br/>';
                            if(isset($razon_no_esta_para_efectuar)) echo $razon_no_esta_para_efectuar;
                        echo '</td>';
                    echo '</tr>';
                }
            }
            elseif(isset($_POST['finalizar_planilla']))
            {
                if($esta_para_efectuar == 'si' and isset($elementos_a_imprimir) and !empty($elementos_a_imprimir) and isset($elementos_a_exportar) and !empty($elementos_a_exportar))
                {
                    $consulta_actualizar_efectuado_2 = 'UPDATE diario
                        SET efectuado_fecha = "'.date('Y-m-d').'",
                            efectuado_por = "'.$_SESSION['usuario_en_sesion'].'"
                        WHERE borrado LIKE "no"
                            AND efectuado_fecha LIKE "0000-00-00"
                            AND id IN ("'.implode('", "', $ids_a_efectuar).'")';
                    $query_actualizar_efectuado_2 = $conexion->prepare($consulta_actualizar_efectuado_2);
                    $query_actualizar_efectuado_2->execute();

                    if(!isset($datos_extra_para_exportar)) $datos_extra_para_exportar = array();
                    $_SESSION['datos_extra_para_exportar'] = array();
                    $_SESSION['datos_extra_para_exportar'] = $datos_extra_para_exportar;
                    $_SESSION['elementos_a_exportar'] = array();
                    $_SESSION['elementos_a_exportar'] = $elementos_a_exportar;
                    $_SESSION['switch_de_estructura_txt'] = $_POST['forma_de_pago'];
                    $contenido_del_txt = "";
                    
                    // echo 'elementos_a_exportar';
                    // var_dump($elementos_a_exportar);
                    // echo '<br/>';
                    // echo 'elementos_a_imprimir';
                    // var_dump($elementos_a_imprimir);
                    // echo '<br/>';

                    // echo 'generar-txt<br/>';
                    include '../../funciones/generar-txt.php';
                    // var_dump($contenido_del_txt);
                    if(strpos($archivo_txt, $_POST['nombre_del_archivo']) === false)
                    {
                        $archivo_txt = str_replace($nombre_del_archivo, $_POST['nombre_del_archivo'], $archivo_txt);
                    }
                    $archivo_txt_final = fopen($archivo_txt, 'wb');
                    fwrite($archivo_txt_final, $contenido_del_txt);
                    fclose($archivo_txt_final);
                        
                    $_SESSION['elementos_a_imprimir'] = array();
                    $_SESSION['elementos_a_imprimir'] = $elementos_a_imprimir;
                    
                    // echo 'imprimir-pdf<br/>';
                    include '../../funciones/imprimir-pdf-listados.php';
                }
                else
                {
                    echo '<tr>';
                        echo '<td style="color:red;">';
                            echo 'Los datos no son validos para finalizar.<br/>';
                            if(isset($razon_no_esta_para_efectuar)) echo $razon_no_esta_para_efectuar;
                        echo '</td>';
                    echo '</tr>';
                }
            }
        }
        else
        {
            echo 'Los archivos de sueldos-'.$descripcion_a_buscar.' ya se encuentran generados.';
            echo '<br/>';
            echo '<a href="../../vistas/reportes/funcionarios-listado_de_archivos_sueldos.php">';
                echo 'Ir al Listado de Archivos de Sueldos';
            echo '</a>';
            echo '<tr>';
                echo '<td>';
                    echo 'No hay movimientos a efectuar.';
                echo '</td>';
            echo '</tr>';
        }
    echo '</table>';
echo '</form>';

?>