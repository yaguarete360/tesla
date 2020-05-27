<?php if (!isset($_SESSION)) {session_start();}

    // 002-001-0180137
    
    echo '<a href="../../vistas/procesos/facturas-paso_3_recibir_facturas_de_cobradores.php">';
        echo 'Cargar Nuevamente la Pagina';
    echo '</a>';
    echo '<br/>';
                
    echo '<table id="tabla_de_busqueda_de_contratos">';
        echo '<tr>';
            echo '<td>';
                echo 'Buscar Contratos:';
            echo '</td>';
            echo '<td>';
                echo '<input type="text" id="buscar_contratos_texto" value="">';
            echo '</td>';
            echo '<td>';
                echo '<input type="submit" id="buscar_contratos" value="Buscar">';
            echo '</td>';
        echo '</tr>';
    echo '</table>';

    $_SESSION['factura_a_imprimir'] = '';
    $campos_datos = array('formulario_numero', 'estado', 'partida', 'formato_de_impresion', 'talonario_numero', 'sucursal');
    
    $estilo_del_td = 'padding:5px;';
    switch ($elegir_factura_forma)
    {
        case 'manual':
            echo '<form method="post" action="">';
                echo '<table>';
                    echo '<tr>';
                        echo '<td style="'.$estilo_del_td.'">';
                            echo "Cobrador:";
                        echo '</td>';
                        echo '<td style="'.$estilo_del_td.'">';
                            $cobradores = array();
                                // AND sucursal NOT IN ("", "no aplicable", "sin datos")
                            $consulta_cobradores = 'SELECT organigrama FROM organigrama 
                            WHERE borrado = "no" 
                                AND finalizacion LIKE "0000-00-00%" 
                                AND (puede_cobrar LIKE "%si%"
                                    OR puede_vender = "si")
                            ORDER BY organigrama ASC';
                            $query_cobradores = $conexion->prepare($consulta_cobradores);
                            $query_cobradores->execute();
                            while($rows_cobradores = $query_cobradores->fetch(PDO::FETCH_ASSOC)) $cobradores[] = $rows_cobradores['organigrama'];

                            echo '<select name="cobrador_seleccionado">';
                                foreach ($cobradores as $pos => $cobrador)
                                {
                                    $seleccionado = (isset($_POST['cobrador_seleccionado']) and $cobrador == $_POST['cobrador_seleccionado']) ? "selected" : "";
                                    echo '<option value="'.$cobrador.'" '.$seleccionado.'>'.$cobrador.'</option>';
                                }
                            echo '</select>';
                        echo '</td>';
                        if(isset($_POST['elegir_cobrador']) or isset($_POST['elegir_factura']))
                        {
                            $facturas = array();
                            $consulta_facturas = 'SELECT formulario_numero FROM formularios
                                WHERE borrado = "no"
                                    AND formulario = "factura"
                                    AND custodia_2 = "'.$_POST['cobrador_seleccionado'].'"
                                    AND custodia_3_entrega_fecha = "0000-00-00 00:00:00"
                                    AND custodia_3 = "no aplicable"
                                    AND fecha_de_uso LIKE "0000-00-00%"
                                    AND monto = 0
                                    ORDER BY formulario_numero ASC';
                            $query_facturas = $conexion->prepare($consulta_facturas);
                            $query_facturas->execute();
                            while($rows_facturas = $query_facturas->fetch(PDO::FETCH_ASSOC)) $facturas[] = $rows_facturas['formulario_numero'];
                            echo '<td style="'.$estilo_del_td.'">';
                                echo '<select name="factura_seleccionada">';
                                    foreach ($facturas as $pos => $factura)
                                    {
                                        $seleccionado = (isset($_POST['factura_seleccionada']) and $factura == $_POST['factura_seleccionada']) ? "selected" : "";
                                        echo '<option value="'.$factura.'" '.$seleccionado.'>'.$factura.'</option>';
                                    }
                                echo '</select>';
                            echo '</td>';
                        }

                        echo '<td style="'.$estilo_del_td.'" colspan="2">';
                        
                            if(isset($_POST['elegir_cobrador']) or isset($_POST['elegir_factura']))
                            {
                                echo '<input type="submit" name="elegir_factura" value="Elegir Factura">';
                            }
                            else
                            {
                                echo '<input type="submit" name="elegir_cobrador" value="Elegir Cobrador">';
                            }

                        echo '</td>';
                    echo '</tr>';
                echo '</table>';
            echo '</form>';
            $factura_es_imprimible = false;
            if(isset($_POST['elegir_factura']))
            {
                $factura_seleccionada = $_POST['factura_seleccionada'];
                $cobrador_seleccionado = $_POST['cobrador_seleccionado'];
            }
            // $_SESSION['forma_de_pago_del_proceso'] = 'cobrador_a_domicilio';
            $_SESSION['forma_de_pago_del_proceso'] = $forma_de_cobro;
        break;
        
        case 'automatico':
        default:
            $factura_es_imprimible = true;
            $consulta_factura_a_usar = 'SELECT formulario_numero FROM formularios 
                WHERE borrado = "no"
                    AND formulario = "factura"
                    AND estado = "activo"
                    AND custodia_3_entrega_fecha = "0000-00-00 00:00:00"
                    AND custodia_3 = "no aplicable"
                    AND fecha_de_uso = "0000-00-00"
                    AND aprobado = "0000-00-00 00:00:00"
                    AND custodia_2 = "'.$_SESSION['usuario_en_sesion'].'"
                    AND monto = 0
                    ORDER BY formulario_numero ASC LIMIT 1';
            $query_factura_a_usar = $conexion->prepare($consulta_factura_a_usar);
            $query_factura_a_usar->execute();
            while($rows_factura_a_usar = $query_factura_a_usar->fetch(PDO::FETCH_ASSOC))
            {
                $factura_seleccionada = $rows_factura_a_usar['formulario_numero'];
                $cobrador_seleccionado = $_SESSION['usuario_en_sesion'];
            }
            // $_SESSION['forma_de_pago_del_proceso'] = 'en_ventanilla';
            $_SESSION['forma_de_pago_del_proceso'] = $forma_de_cobro;
        break;
    }

    $factura_seleccionada_esta_ok = false;
    if(isset($cobrador_seleccionado) and isset($factura_seleccionada))
    {
        $consulta_facturas = 'SELECT '.implode(', ', $campos_datos).' FROM formularios
            WHERE borrado LIKE "no"
                AND formulario LIKE "factura"
                AND custodia_3_entrega_fecha LIKE "0000-00-00 00:00:00"
                AND custodia_3 LIKE "no aplicable"
                AND fecha_de_uso LIKE "0000-00-00%"
                AND custodia_2 LIKE "'.$cobrador_seleccionado.'"
                AND formulario_numero LIKE "'.$factura_seleccionada.'"
                ORDER BY formulario_numero ASC
                LIMIT 1';
        $query_facturas = $conexion->prepare($consulta_facturas);
        $query_facturas->execute();
        while($rows_facturas = $query_facturas->fetch(PDO::FETCH_ASSOC))
        {
            foreach ($campos_datos as $pos => $campo_nombre) $factura_datos[$campo_nombre] = $rows_facturas[$campo_nombre];
            if($factura_datos['estado'] == 'activo') $factura_seleccionada_esta_ok = true;
        }
    }

    if($factura_seleccionada_esta_ok and !isset($_POST['cobrar_factura']))
    {
        echo '<form method="post" id="form_cuotas" action="">';
            echo '<table id="tabla_principal" class="tabla_de_cobranzas">';
                echo '<input type="hidden" name="factura_a_actualizar" value="'.$factura_seleccionada.'">';
                echo '<input type="hidden" name="cobrador_a_controlar" value="'.$cobrador_seleccionado.'">';
                echo '<tr>';
                    foreach ($factura_datos as $campo_nombre => $campo_valor) echo '<td style="'.$estilo_del_td.'"><h5>'.str_replace("_", " ", $campo_nombre).'</h5></td>';
                echo '</tr>';
                echo '<tr>';
                    foreach ($factura_datos as $campo_nombre => $campo_valor) echo '<td style="'.$estilo_del_td.'">'.$campo_valor.'</td>';
                echo '</tr>';
                echo '<tr><td colspan="20"><hr></td></tr>';
                foreach ($campos_a_cargar as $pos => $campo_nombre)
                {
                    $variable_nombre = $campo_nombre;
                    $data_del_tr = '';
                    if(strpos($variable_nombre, 'forma_de_pago') !== false)
                    {
                        $esta_forma_de_pago = 'forma_de_pago_'.explode('_', $variable_nombre)[3];
                        $data_del_tr = (strpos($variable_nombre, 'tipo') === false) ? 'data-mostrar_por_forma_de_pago="'.$esta_forma_de_pago.'"' : '';
                    }
                    
                    echo '<tr '.$data_del_tr.'>';
                        echo '<td style="'.$estilo_del_td.'">';
                            switch ($campo_nombre)
                            {
                                case 'custodia_3':
                                    echo '<h5>Cajero</h5>';
                                break;

                                default:
                                    $campo_nombre_mostrar = str_replace("instrumento", "inst.", $campo_nombre);
                                    echo '<h5>'.str_replace("_", " ", $campo_nombre_mostrar).'</h5>';
                                break;
                            }
                        echo '</td>';
                        echo '<td id="td_'.$campo_nombre.'" style="'.$estilo_del_td.'" colspan="5">';
                            $i = 0;
                            switch ($campo_nombre)
                            {
                                case 'forma_de_cobro':
                                    echo ucwords($forma_de_cobro);
                                    echo '<input type="hidden" name="'.$variable_nombre.'" value="'.$forma_de_cobro.'">';
                                break;

                                case 'custodia_3':
                                case 'custodia_3_entrega_fecha':
                                case 'custodia_3_entrega_funcionario':
                                    if($campo_nombre == 'custodia_3') echo ucwords($_SESSION['usuario_en_sesion']);
                                break;

                                case 'fecha_de_uso':
                                    if($elegir_factura_forma == 'automatico')
                                    {
                                        echo date('Y-m-d');
                                        echo '<input type="hidden" name="fecha_de_uso" value="'.date('Y-m-d').'">';
                                    }
                                    else
                                    {
                                        $rotulo = "";
                                        $indice = $i;
                                        $requerido = "required";
                                        include '../../funciones/seleccionar-fechas.php';
                                    }
                                break;

                                case 'forma_de_pago_1_vencimiento':
                                case 'forma_de_pago_2_vencimiento':
                                case 'forma_de_pago_3_vencimiento':
                                    echo '<input type="date" name="'.$variable_nombre.'" value="">';
                                break;

                                case 'factura_tipo':
                                    echo 'Contado: <input type="radio" name="'.$variable_nombre.'" value="contado"><br/>';
                                    echo 'Credito: <input type="radio" name="'.$variable_nombre.'" value="credito">';
                                break;
                                case 'factura_vencimiento':
                                    echo '<input type="number" name="'.$variable_nombre.'" value=""> (Dias)';
                                break;

                                case 'razon_social':
                                    $campo_atributo['herramientas'] = 'cuentas-cuenta-cliente!=0';
                                    $requerido = 'required';
                                    include '../../funciones/autocompletar-base.php';
                                break;
                                case 'razon_social_cuenta_numero':
                                    $id_a_detectar_loading = "razon_social_busqueda";
                                    $id_a_detectar = "razon_social_sugerencias";
                                    $id_a_actualizar = $variable_nombre;
                                    $span_a_actualizar = $variable_nombre."_span";
                                    
                                    $campo_atributo['herramientas'] = 'cliente-cuentas-cuenta';

                                    include '../../funciones/autocompletar-desde-otro-input-base.php';
                                break;
                                case 'ruc':
                                    $id_a_detectar_loading = "razon_social_busqueda";
                                    $id_a_detectar = "razon_social_sugerencias";
                                    $id_a_actualizar = $variable_nombre;
                                    $span_a_actualizar = $variable_nombre."_span";
                                    
                                    $campo_atributo['herramientas'] = 'ruc-cuentas-cuenta';

                                    include '../../funciones/autocompletar-desde-otro-input-base.php';
                                break;

                                case 'cuenta':
                                    $campo_atributo['herramientas'] = 'cuentas-cuenta-cliente!=0';
                                    $requerido = 'required';
                                    $con_href = array(true,'../reportes/clientes-estado_de_cuenta.php', 'con_rango_de_fecha-1 year');
                                    include '../../funciones/autocompletar-base.php';
                                break;
                                case 'cuenta_numero':
                                    $id_a_detectar_loading = "cuenta_busqueda";
                                    $id_a_detectar = "cuenta_sugerencias";
                                    $id_a_actualizar = $variable_nombre;
                                    $span_a_actualizar = $variable_nombre."_span";
                                    
                                    $campo_atributo['herramientas'] = 'cliente-cuentas-cuenta';

                                    include '../../funciones/autocompletar-desde-otro-input-base.php';
                                break;
                                case 'cuenta_documento_tipo':
                                    $id_a_detectar_loading = "cuenta_busqueda";
                                    $id_a_detectar = "cuenta_sugerencias";
                                    $id_a_actualizar = $variable_nombre;
                                    $span_a_actualizar = $variable_nombre."_span";
                                    
                                    $campo_atributo['herramientas'] = 'identidad_tipo-cuentas-cuenta';

                                    include '../../funciones/autocompletar-desde-otro-input-base.php';
    
                                break;
                                case 'cuenta_documento_numero':
                                    $id_a_detectar_loading = "cuenta_busqueda";
                                    $id_a_detectar = "cuenta_sugerencias";
                                    $id_a_actualizar = $variable_nombre;
                                    $span_a_actualizar = $variable_nombre."_span";
                                    
                                    $campo_atributo['herramientas'] = 'identidad_numero-cuentas-cuenta';

                                    include '../../funciones/autocompletar-desde-otro-input-base.php';
                                break;

                                case 'contratos':
                                    $id_a_detectar_loading = "cuenta_busqueda";
                                    $id_a_detectar = "cuenta_sugerencias";
                                    // $id_a_detectar = "cuenta_numero";
                                    // $span_a_actualizar = $variable_nombre."_span";
                                    $span_a_actualizar = "td_".$variable_nombre;

                                    include '../../funciones/autocompletar-lineas-de-contratos-base.php';
                                    echo '</td>';
                                    echo '<td>';
                                        echo 'Calcular Mora Al: <input type="date" name="fecha_de_calculo_de_mora" value="">';
                                break;

                                case 'concepto':
                                    if($elegir_factura_forma == 'manual')
                                    {
                                        echo '<textarea name="'.$variable_nombre.'" rows="3" cols="30" maxlength="240">';
                                        echo '</textarea>';
                                    }
                                break;

                                case 'iva_monto': case 'monto': case 'vuelto':
                                    $clase_a_detectar = "botones_a_cobrar";
                                    $sumas_a_realizar[$variable_nombre] = "";
                                    echo '<input type="hidden" id="'.$variable_nombre.'" name="'.$variable_nombre.'" value="" required>';
                                    echo '<span id="'.$variable_nombre.'-span"></span>';
                                    if($variable_nombre == "monto")
                                    {
                                        include '../../funciones/calcular-factura.php';
                                    }
                                break;
                            
                                default:
                                    echo '<input type="text" name="'.$variable_nombre.'" value="">';
                                break;
                            }
                            $i++;
                        echo '</td>';
                    echo '</tr>';
                }
                echo '<tr>';
                    echo '<td>';
                        echo '<input type="submit" id="boton_cobrar_factura" name="cobrar_factura" value="Cobrar Factura" style="border-color:green;color:green;background-color:#adebad;" class="submits_sin_enter">';
                    echo '</td>';
                echo '</tr>';
            echo '</table>';
        echo '</form>';
    }
    elseif($elegir_factura_forma == 'automatico' or ($elegir_factura_forma == 'manual' and (isset($_POST['elegir_factura']) or isset($_POST['elegir_cobrador']))))
    {
        if(isset($factura_datos['estado']) and $factura_datos['estado'] != "activo")
        {
            echo 'La factura seleccionada se encuentra en Estado '.$factura_datos['estado'].'. Revisar con un operador o programador.';
            echo '<br/>';
        }

        if((($elegir_factura_forma == 'automatico' and !isset($factura_seleccionada) and !isset($cobrador_seleccionado)) or ($elegir_factura_forma == 'manual' and empty($factura_seleccionada) and isset($_POST['elegir_factura']))))
        {
            echo '<b>'.ucwords((isset($cobrador_seleccionado) ? $cobrador_seleccionado : $_SESSION['usuario_en_sesion'])).': </b>no tiene facturas a su nombre. Comuniquese con el Departamento de Contabilidad.';
            echo '<br/>';
        }
    }

    if(isset($_POST['cobrar_factura']))
    {
        $campos_diario_s = 'diario,planilla,fecha,cuenta,cuenta_numero,cuenta_documento_tipo,cuenta_documento_numero,linea,contrato,forma_de_cobro,documento_tipo,documento_numero,descripcion,observacion,aprobado_por,cantidad,cuota,cuota_vencimiento,efectuado_fecha,efectuado_por,factura_numero,iva_porcentaje,iva_monto,entra,sale,derecho,obligacion,creado,borrado,usuario';
        $campos_diario = explode(",", $campos_diario_s);

        // $monto_factura_total = str_replace(',', '', $_POST['total_factura'])+0;
        $monto_factura_total = str_replace(',', '', $_POST['monto'])+0;
        if(!isset($pago_real_total)) $pago_real_total = $monto_factura_total;

        $consulta_estructura_c = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME LIKE "contratos" AND TABLE_SCHEMA LIKE "'.$_SESSION['dbNombre'].'"';
        $query_estructura_c = $conexion->prepare($consulta_estructura_c);
        $query_estructura_c->execute();
        while($rows_estructura_c = $query_estructura_c->fetch(PDO::FETCH_ASSOC)) $campos_contratos[$rows_estructura_c['COLUMN_NAME']] = $rows_estructura_c['DATA_TYPE'];

        $consulta_insercion_contrato_var_base = 'INSERT INTO contratos (';
        foreach ($campos_contratos as $campo_nombre => $campo_tipo) $consulta_insercion_contrato_var_base.= $campo_nombre.', ';
        $consulta_insercion_contrato_var_base = rtrim($consulta_insercion_contrato_var_base, ', ').') VALUES (';

        $consulta_insercion_diario_base = 'INSERT INTO diario ('.$campos_diario_s.') VALUES ';
        foreach ($_POST['a_cobrar'] as $contrato_compuesto => $cuotas)
        {
            $contrato_partes = explode("-", $contrato_compuesto);

            if($contrato_compuesto == '1-var-0000000')
            {
                $ultimo_contrato_var = explode('-', $contrato_compuesto)[2];
                $consulta_ultimo_contrato_var = 'SELECT contrato_numero FROM contratos WHERE borrado = "no" AND contrato LIKE "%var%" ORDER BY contrato_numero DESC LIMIT 1';
                $query_ultimo_contrato_var = $conexion->prepare($consulta_ultimo_contrato_var);
                $query_ultimo_contrato_var->execute();
                while($rows_u_c_v = $query_ultimo_contrato_var->fetch(PDO::FETCH_ASSOC)) $ultimo_contrato_var = $rows_u_c_v['contrato_numero'];
                $contrato_compuesto = '1-var-'.str_pad($ultimo_contrato_var+1, 7, '0', STR_PAD_LEFT);
                $contrato_partes = explode("-", $contrato_compuesto);
                
                $consulta_insercion_contrato_var = $consulta_insercion_contrato_var_base;
                foreach ($campos_contratos as $campo_nombre => $campo_tipo)
                {
                    switch ($campo_nombre)
                    {
                        case 'id':
                            $dato_contrato = 'no aplicable';
                        break;
                        case 'fecha':
                        case 'creado':
                            $dato_contrato = date('Y-m-d G:i:s');
                        break;
                        case 'cuenta':
                        case 'cuenta_numero':
                        case 'cuenta_documento_tipo':
                        case 'cuenta_documento_numero':
                            $dato_contrato = $_POST[$campo_nombre];
                        break;
                        case 'contrato':
                            $dato_contrato = $contrato_compuesto;
                        break;
                        case 'contrato_linea':
                            $dato_contrato = $contrato_partes[0];
                        break;
                        case 'contrato_centro':
                            $dato_contrato = $contrato_partes[1];
                        break;
                        case 'contrato_numero':
                            $dato_contrato = $contrato_partes[2];
                        break;
                        case 'estado':
                            $dato_contrato = 'vigente';
                        break;
                        case 'usuario':
                            $dato_contrato = $_SESSION['usuario_en_sesion'];
                        break;
                        
                        default:
                            switch ($campo_tipo)
                            {
                                case 'int':
                                case 'decimal':
                                case 'date':
                                case 'datetime':
                                case 'timestamp':
                                    $dato_contrato = '0';
                                break;
                                
                                default:
                                    $dato_contrato = 'no aplicable';
                                break;
                            }
                        break;
                    }
                    $consulta_insercion_contrato_var.= '"'.strtolower($dato_contrato).'", ';
                }
                $consulta_insercion_contrato_var = rtrim($consulta_insercion_contrato_var, ', ').')';
                $query_insercion_contrato_var = $conexion->prepare($consulta_insercion_contrato_var);
                $query_insercion_contrato_var->execute();
            }

            foreach ($cuotas as $cuota_datos => $monto)
            {
                $cuota_datos_partes = explode("_", $cuota_datos);

                if(isset($_POST['a_cobrar_moras'][$contrato_compuesto][$cuota_datos])) $monto_mora = str_replace(',', '', $_POST['a_cobrar_moras'][$contrato_compuesto][$cuota_datos]) + 0;
                $tiene_mora = (isset($_POST['a_cobrar_moras'][$contrato_compuesto][$cuota_datos]) and $monto_mora > 0);
                $es_contrato_var = (strpos($contrato_compuesto, '-var-') !== false);

                $monto = str_replace(',', '', $monto)+0;

                $vueltas_doble_generacion = ($tiene_mora ? 3 : 1);
                if($es_contrato_var) $vueltas_doble_generacion = 2;

                for ($i_d_g = 1; $i_d_g <= $vueltas_doble_generacion; $i_d_g++)
                {
                    // vuelta 1 = cobranza de la cuota (obligacion)
                    // vuelta 2 = mora de la cuota / producto VAR (generacion del derecho)
                    // vuelta 3 = cobranza de la mora de la cuota (obligacion)
                    $es_cobranza = ($i_d_g == 1 or $i_d_g == 3) ? 'cobranza de la ' : '';
                    $es_mora = (($i_d_g == 3 or $i_d_g == 2) and !$es_contrato_var) ? 'mora de la ' : '';
                    if(!empty($es_mora)) $monto = $monto_mora;

                    $descripcion = $es_cobranza.$es_mora.'cuota '.$cuota_datos_partes[0];
                    if($es_contrato_var) $descripcion = rtrim($es_cobranza, ' la ').' '.$cuota_datos_partes[2];
                    // $monto_a_guardar_entra = (empty($es_cobranza)) ? count($cuotas) : 0;
                    $monto_a_guardar_entra = (empty($es_cobranza)) ? 1 : 0;
                    $monto_a_guardar_derecho = (empty($es_cobranza)) ? $monto : 0;
                    // $monto_a_guardar_sale = (!empty($es_cobranza)) ? count($cuotas) : 0;
                    $monto_a_guardar_sale = (!empty($es_cobranza)) ? 1 : 0;
                    $monto_a_guardar_obligacion = (!empty($es_cobranza)) ? $monto : 0;
                    // echo '<br/>X-> '.$monto.' => '.$descripcion.' der='.$monto_a_guardar_derecho.' obl='.$monto_a_guardar_obligacion.'<br/>';

                    $iva_base = isset($_POST[$contrato_compuesto.'-iva']) ? $_POST[$contrato_compuesto.'-iva'] : 10;
                    $iva_porcentaje = (100 / $iva_base) + 1;
                    $iva_monto = round($monto_a_guardar_obligacion / $iva_porcentaje);

                    if(!isset($monto_iva_real)) $monto_iva_real = 0;
                    $monto_iva_real+= $iva_monto;

                    if(!isset($monto_cobrado_real)) $monto_cobrado_real = 0;
                    $monto_cobrado_real+= $monto_a_guardar_obligacion;

                    $consulta_insercion_diario = $consulta_insercion_diario_base.'(';
                    foreach ($campos_diario as $pos => $campo_a_insertar)
                    {
                        switch ($campo_a_insertar)
                        {
                            case 'diario':
                                $ultimo_numero_diario = date('Y')."-0000000";
                                $consulta_und = 'SELECT diario FROM diario WHERE borrado LIKE "no" AND diario LIKE "'.date('Y').'%" ORDER BY diario DESC LIMIT 1';
                                $query_und = $conexion->prepare($consulta_und);
                                $query_und->execute();
                                while($rows_und = $query_und->fetch(PDO::FETCH_ASSOC)) $ultimo_numero_diario = $rows_und['diario'];
                                $ultimo_numero_diario_explotado = explode("-", $ultimo_numero_diario);
                                $ultimo_numero_diario = $ultimo_numero_diario_explotado[0]."-".str_pad($ultimo_numero_diario_explotado[1]+1, 7, "0", STR_PAD_LEFT);
                                $consulta_insercion_diario.= '"'.$ultimo_numero_diario.'",';
                            break;
                            
                            case 'planilla':
                                $consulta_insercion_diario.= '"cob-'.date('Y-m').'",';
                            break;

                            case 'fecha':
                            case 'efectuado_fecha':
                                // $fecha_de_uso = (($elegir_factura_forma == 'automatico') ? date('Y-m-d') : $_POST['fecha_de_uso']);
                                $fecha_de_uso = ((!isset($_POST['fecha_de_uso'])) ? date('Y-m-d') : $_POST['fecha_de_uso']);
                                $consulta_insercion_diario.= '"'.$fecha_de_uso.'",';
                            break;
                            
                            case 'linea':
                                $consulta_insercion_diario.= '"'.$contrato_partes[0].'",';
                            break;
                            case 'contrato':
                                $consulta_insercion_diario.= '"'.$contrato_compuesto.'",';
                            break;
                            case 'forma_de_cobro':
                                $consulta_insercion_diario.= '"'.$forma_de_cobro.'",';
                            break;
                            case 'documento_tipo':
                                $consulta_insercion_diario.= '"contrato '.strtolower($contrato_partes[1]).'",';
                            break;
                            case 'documento_numero':
                                $consulta_insercion_diario.= '"'.str_pad($contrato_partes[2], 7, "0", STR_PAD_LEFT).'",';
                            break;
                            case 'descripcion':
                                $consulta_insercion_diario.= '"'.$descripcion.'",';
                            break;
                            case 'observacion':
                                $concepto = (isset($_POST['concepto']) and !empty($_POST['concepto'])) ? $_POST['concepto'] : 'sin datos';
                                $consulta_insercion_diario.= '"'.$concepto.'",';
                            break;
                            case 'aprobado_por':
                                $consulta_insercion_diario.= '"no aplicable",';
                            break;
                            case 'cantidad':
                                $consulta_insercion_diario.= '"1",';
                            break;
                            case 'cuota':
                                if($monto_a_guardar_derecho == 0) $cuotas_cobradas[$contrato_compuesto][] = $cuota_datos_partes[0].'_'.$cuota_datos_partes[1];
                                $consulta_insercion_diario.= '"'.$cuota_datos_partes[0].'",';
                            break;
                            case 'cuota_vencimiento':
                                $consulta_insercion_diario.= '"'.$cuota_datos_partes[1].'",';
                            break;
                            case 'efectuado_por':
                                $consulta_insercion_diario.= '"'.$_POST['cobrador_a_controlar'].'",';
                            break;
                            case 'factura_numero':
                                $consulta_insercion_diario.= '"'.$_POST['factura_a_actualizar'].'",';
                            break;

                            case 'iva_porcentaje':
                                $consulta_insercion_diario.= '"'.($iva_porcentaje - 1).'",';
                            break;
                            case 'iva_monto':
                                $consulta_insercion_diario.= '"'.$iva_monto.'",';
                            break;

                            case 'entra':
                                $consulta_insercion_diario.= '"'.$monto_a_guardar_entra.'",';
                            break;
                            case 'derecho':
                                $consulta_insercion_diario.= '"'.$monto_a_guardar_derecho.'",';
                            break;
                            case 'sale':
                                $consulta_insercion_diario.= '"'.$monto_a_guardar_sale.'",';
                            break;
                            case 'obligacion':
                                $consulta_insercion_diario.= '"'.$monto_a_guardar_obligacion.'",';
                            break;

                            case 'creado':
                                $consulta_insercion_diario.= '"'.date('Y-m-d G:i:s').'",';
                            break;
                            case 'borrado':
                                $consulta_insercion_diario.= '"no",';
                            break;
                            case 'usuario':
                                $consulta_insercion_diario.= '"'.$_SESSION['usuario_en_sesion'].'",';
                            break;

                            default:
                                $consulta_insercion_diario.= '"'.strtolower($_POST[$campo_a_insertar]).'",';
                            break;
                        }
                    }
                    $consulta_insercion_diario = rtrim($consulta_insercion_diario, ',').')';
                    // echo $consulta_insercion_diario;
                    // echo '<br/>';
                    $query_insercion_diario = $conexion->prepare($consulta_insercion_diario);
                    $query_insercion_diario->execute();
                }
            }
        }

        $se_cobro_correctamente = false;
        $filtros_contratos_cobrados = 'AND (';
        $cantidad_de_cuotas_cobradas = 0;
        foreach ($cuotas_cobradas as $contrato_cobrado => $cuotas_a_filtrar)
        {
            $filtros_contratos_cobrados.= '(contrato = "'.$contrato_cobrado.'" AND (';
            foreach ($cuotas_a_filtrar as $cuota_pos => $cuota_datos)
            {
                if(strpos($contrato_cobrado, '-var-'))
                {
                    $cuota_datos_explotado = explode('_', $cuota_datos);
                    if(isset($cuota_datos_explotado[2])) unset($cuota_datos_explotado[2]);
                    $cuota_datos = implode('_', $cuota_datos_explotado);
                }
                $cuota_datos_remplazado = str_replace('_', '" AND cuota_vencimiento = "', $cuota_datos);
                $filtros_contratos_cobrados.= '(cuota = "'.$cuota_datos_remplazado.'") OR ';
            }
            $filtros_contratos_cobrados = rtrim($filtros_contratos_cobrados, ' OR ').')) OR ';
            $cantidad_de_cuotas_cobradas+= count($cuotas_a_filtrar);
        }
        $filtros_contratos_cobrados = rtrim($filtros_contratos_cobrados, ' OR ').')';

        $contratos_cobrados = array_keys($cuotas_cobradas);
        $cuotas_cobradas_encontradas = 0;
        $consulta_control_cobranza = 'SELECT COUNT(id) as cuotas_cobradas_encontradas FROM diario
            WHERE borrado = "no"
                AND descripcion LIKE "cobranza de %"
                '.$filtros_contratos_cobrados.'
                AND factura_numero = "'.$_POST['factura_a_actualizar'].'"
                AND efectuado_fecha = "'.$fecha_de_uso.'"
                AND cuenta = "'.$_POST['cuenta'].'"
                AND creado LIKE "'.date('Y-m-d').'%"
                AND usuario = "'.$_SESSION['usuario_en_sesion'].'"
            ORDER BY id DESC
            LIMIT '.$cantidad_de_cuotas_cobradas;
        $query_control_cobranza = $conexion->prepare($consulta_control_cobranza);
        $query_control_cobranza->execute();
        while($rows_c_c = $query_control_cobranza->fetch(PDO::FETCH_ASSOC)) $cuotas_cobradas_encontradas = $rows_c_c['cuotas_cobradas_encontradas'];
        if($cuotas_cobradas_encontradas == $cantidad_de_cuotas_cobradas) $se_cobro_correctamente = true;
        // $se_cobro_correctamente = true;

        if($se_cobro_correctamente)
        {
            if(!isset($elegir_factura_forma)) $elegir_factura_forma = 'automatico';
                // SET efectuado_fecha = "'.date('Y-m-d G:i:s').'",
            $consulta_actualizar_cuotas_cobradas = 'UPDATE diario
                SET efectuado_fecha = "'.(($elegir_factura_forma == 'automatico') ? date('Y-m-d') : $_POST['fecha_de_uso']).'",
                    efectuado_por = "'.$_SESSION['usuario_en_sesion'].'"
                WHERE borrado = "no"
                    AND descripcion NOT LIKE "cobranza de la%"
                    '.$filtros_contratos_cobrados.'
                    AND cuenta = "'.$_POST['cuenta'].'"';
            $query_actualizar_cuotas_cobradas = $conexion->prepare($consulta_actualizar_cuotas_cobradas);
            $query_actualizar_cuotas_cobradas->execute();
            // echo $consulta_actualizar_cuotas_cobradas.'<br/>';

            $campos_facturas_s = 'custodia_3,custodia_3_entrega_fecha,custodia_3_entrega_funcionario,fecha_de_uso,factura_tipo,factura_vencimiento,cuenta,cuenta_numero,ruc,concepto,aprobado_por,iva_monto,monto';
            $campos_facturas = explode(",", $campos_facturas_s);

            $consulta_actualizacion_facturas = 'UPDATE formularios SET ';
            foreach ($campos_facturas as $pos => $campo_a_actualizar)
            {
                switch ($campo_a_actualizar)
                {
                    case 'fecha_de_uso':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.(($elegir_factura_forma == 'automatico') ? date('Y-m-d') : $_POST[$campo_a_actualizar]).'",';
                    break;
                    case 'factura_tipo':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.$_POST['factura_tipo'].'",';
                    break;
                    case 'factura_vencimiento':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.$_POST['factura_vencimiento'].'",';
                    break;
                    case 'cuenta':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.$_POST['razon_social'].'",';
                    break;
                    case 'cuenta_numero':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.$_POST['razon_social_cuenta_numero'].'",';
                    break;
                    case 'custodia_3':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.$_SESSION['usuario_en_sesion'].'",';
                    break;
                    case 'custodia_3_entrega_fecha':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.date('Y-m-d G:i:s').'",';
                    break;
                    case 'custodia_3_entrega_funcionario':
                        $custodia_3_por_defecto = ($elegir_factura_forma == 'manual') ? 'custodia_2' : '"'.$_SESSION['usuario_en_sesion'].'"';
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = '.$custodia_3_por_defecto.',';
                    break;
                    case 'concepto': case 'aprobado_por':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "no aplicable",';
                    break;
                    case 'iva_monto':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.$monto_iva_real.'",';
                    break;
                    case 'monto':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.$monto_cobrado_real.'",';
                    break;

                    case 'usuario':
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.$_SESSION['usuario_en_sesion'].'",';
                    break;
                    default:
                        $dato_a_guardar = (isset($_POST[$campo_a_actualizar])) ? $_POST[$campo_a_actualizar] : 'sin datos';
                        $consulta_actualizacion_facturas.= $campo_a_actualizar.' = "'.$dato_a_guardar.'",';
                    break;
                }
            }
            $consulta_actualizacion_facturas = rtrim($consulta_actualizacion_facturas, ',').' WHERE borrado = "no" and formulario = "factura" AND formulario_numero = "'.$_POST['factura_a_actualizar'].'" = custodia_2 = "'.$_POST['cobrador_a_controlar'].'"';
            // echo $consulta_actualizacion_facturas.'<br/>';
            $query_actualizacion_facturas = $conexion->prepare($consulta_actualizacion_facturas);
            $query_actualizacion_facturas->execute();

            $_SESSION['factura_a_imprimir'] = $_POST['factura_a_actualizar'];
            echo '<b>Factura '.$_POST['factura_a_actualizar'].' se ha cargado con exito!.</b>';
            echo '<br/>';
            echo '<br/>';
            echo '<a href="../procesos/facturas-aplicar_descuentos.php">Aplicar descuentos a la factura '.$_POST['factura_a_actualizar'].'</a>';
            echo '<br/>';
            echo '<br/>';
            echo '<a href="../procesos/cajas-paso_2_cobrar_factura.php"><b>Seguir cobrando en el PASO 2</b></a>';
        }
    }

?>

<script type="text/javascript">
    
    $('#form_cuotas').keypress(function(e){
        if ( e.which == 13 ) return false;
     }); 

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

    $('input[data-formato_numero="si"]').keyup(function(){
        var valor_con_comas = addCommas($(this).val().replace(/,/g, ''));
        $(this).val(valor_con_comas);
    });

    $('[data-mostrar_por_forma_de_pago]').slideUp();
    $('[data-forma_de_pago]').change(function(){
        var esta_forma_de_pago = $(this).attr('data-forma_de_pago');
        var este_valor = $(this).val();
        if(este_valor == 'no hay')
        {
            $('[data-mostrar_por_forma_de_pago="'+esta_forma_de_pago+'"]').slideUp();
        }
        else
        {
            if(este_valor == 'efectivo')
            {
                $('[data-mostrar_por_forma_de_pago="'+esta_forma_de_pago+'"]').slideUp();
                $('[data-mostrar_por_forma_de_pago="'+esta_forma_de_pago+'"]').find('[data-formato_numero="si"]').closest('tr').slideDown();
            }
            else
            {
                $('[data-mostrar_por_forma_de_pago="'+esta_forma_de_pago+'"]').slideDown();
            }
        }
    });

    boton_estilo_verde = 'border-color:green;color:green;background-color:#adebad;';
    boton_estilo_gris = 'border-color:#666666;color:#666666;background-color:#d9d9d9';
    
    // $('#boton_cobrar_factura').prop('disabled', true);
    // $('#boton_cobrar_factura').prop('style', boton_estilo_gris);
    // $('#es_pago_parcial').prop('disabled', true);

    $('#buscar_contratos').on('click', function(){
        texto_a_buscar = $('#buscar_contratos_texto').val();
        if(texto_a_buscar.length > 3)
        {
            var capitulo = '<?php echo $capitulo; ?>';
            nivel_de_url = (capitulo == 'procesos' || capitulo == 'reportes') ? "../../" : "../";

            $('#tabla_de_busqueda_de_contratos').append('<tr class="filas_borrables"><td colspan="3"><img class="barra_loading" src="'+nivel_de_url+'imagenes/iconos/loading.gif"></td></tr>');

            datos_para_autocompletar = 'datos_para_el_query='+texto_a_buscar;
            $.ajax({
                type: "POST",
                url: nivel_de_url+"funciones/autocompletar-busqueda-de-contratos-consulta.php",
                data: datos_para_autocompletar,
                success: function(data) {
                    $('#tabla_de_busqueda_de_contratos').find('.filas_borrables').remove();
                    $('#tabla_de_busqueda_de_contratos').append(data);
                },
                error: function(data) {
                    $('#tabla_de_busqueda_de_contratos').append('<tr class="filas_borrables"><td>Error: '+datos_para_autocompletar+'</td></tr>');
                },
            });
        }
        else
        {
            $('#tabla_de_busqueda_de_contratos').find('.filas_borrables').remove();
        }
    })

</script>
