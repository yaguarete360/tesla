<?php if (!isset($_SESSION)) {session_start();}
    
    include '../../librerias/free-pdf/fpdf.php';

    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on",0);
    $pdf->SetMargins(0, 25, 0);
    $dimensiones_pagina = array(280, 160); // array(280, 236);
    $pdf->AddPage('L', $dimensiones_pagina);
    // $pdf->AddPage('P', 'Letter'); // 'Letter'

    $pdf->SetFont('Arial', '', 10);
    
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(245,231,214);
    $pdf->SetDrawColor(216,162,98);
    $borde_control = 0; // para controlar espacios de cada cell = 1 : 0;

    $alto_de_cada_factura = 140;
    $ancho_de_cada_factura = 236;
    $ancho_de_guion = $pdf->GetStringWidth('-');
    
    $campos_unicos['fecha_dia']['y'] = 40;
    $campos_unicos['fecha_dia']['x'] = 50;
    $campos_unicos['fecha_dia']['ancho'] = 6;

    $campos_unicos['fecha_mes']['y'] = 40;
    $campos_unicos['fecha_mes']['x'] = 63;
    $campos_unicos['fecha_mes']['ancho'] = 40;

    $campos_unicos['fecha_anho']['y'] = 40;
    $campos_unicos['fecha_anho']['x'] = 117;
    $campos_unicos['fecha_anho']['ancho'] = 5;

    $campos_unicos['condicion_de_venta-contado']['y'] = 40;
    $campos_unicos['condicion_de_venta-contado']['x'] = 176;
    $campos_unicos['condicion_de_venta-contado']['ancho'] = 5;

    $campos_unicos['condicion_de_venta-credito']['y'] = 40;
    $campos_unicos['condicion_de_venta-credito']['x'] = 196;
    $campos_unicos['condicion_de_venta-credito']['ancho'] = 5;

    $campos_unicos['ruc']['y'] = 50;
    $campos_unicos['ruc']['x'] = 35;
    $campos_unicos['ruc']['ancho'] = 175;

    $campos_unicos['cuenta']['y'] = 60;
    $campos_unicos['cuenta']['x'] = 57;
    $campos_unicos['cuenta']['ancho'] = 81;
    
    $campos_unicos['nota_de_remision']['y'] = 60;
    $campos_unicos['nota_de_remision']['x'] = 163;
    $campos_unicos['nota_de_remision']['ancho'] = 42;

    $campos_unicos['subtotales-exentas']['y'] = 130; 
    $campos_unicos['subtotales-exentas']['x'] = 140;
    $campos_unicos['subtotales-exentas']['ancho'] = 15;
    
    $campos_unicos['subtotales-iva_05']['y'] = 130;
    $campos_unicos['subtotales-iva_05']['x'] = 162;
    $campos_unicos['subtotales-iva_05']['ancho'] = 15;
    
    $campos_unicos['subtotales-iva_10']['y'] = 130;
    $campos_unicos['subtotales-iva_10']['x'] = 180;
    $campos_unicos['subtotales-iva_10']['ancho'] = 20;

    $campos_unicos['total_a_pagar_letras']['y'] = 139;
    $campos_unicos['total_a_pagar_letras']['x'] = 46;
    $campos_unicos['total_a_pagar_letras']['ancho'] = 135;

    $campos_unicos['total_a_pagar_monto']['y'] = 139;
    $campos_unicos['total_a_pagar_monto']['x'] = 184;
    $campos_unicos['total_a_pagar_monto']['ancho'] = 22;
    
    $campos_unicos['ivas-iva_05']['y'] = 143;
    $campos_unicos['ivas-iva_05']['x'] = 63;
    $campos_unicos['ivas-iva_05']['ancho'] = 37;

    $campos_unicos['ivas-iva_10']['y'] = 143;
    $campos_unicos['ivas-iva_10']['x'] = 112;
    $campos_unicos['ivas-iva_10']['ancho'] = 30;

    $campos_unicos['ivas-total']['y'] = 143;
    $campos_unicos['ivas-total']['x'] = 155;
    $campos_unicos['ivas-total']['ancho'] = 55;

    $campos_repetidos_y = 79;
    $campos_repetidos_x = 22; // 35
    $campos_repetidos_altura = 4;
    $campos_repetidos['cantidad'] = 15;
    $campos_repetidos['descripcion'] = 82;
    $campos_repetidos['precio_unitario'] = 18;
    $campos_repetidos['monto-exentas'] = 22;
    $campos_repetidos['monto-iva_05'] = 22;
    $campos_repetidos['monto-iva_10'] = 22;

    $pdf->SetY($campos_repetidos_y);
    // $cantidad_de_lineas_hasta_aca = 0;
    foreach ($elementos_a_imprimir['detalle'] as $i => $campos_detalle)
    {
        $pdf->SetX($campos_repetidos_x);
        foreach ($campos_repetidos as $campo_nombre => $ancho)
        {
            switch ($campo_nombre)
            {
                case 'cantidad':
                    $dato_a_mostrar = $campos_detalle['sale'];
                    $alineacion = 'C';
                break;

                case 'descripcion':
                    // $dato_a_mostrar = $campos_detalle['descripcion'];
                    $alineacion = 'L';
                    $posicion_y_original = $campos_repetidos_y;
                    $tiene_filas_multiples = false;
                    $cantidad_de_filas_multiples = 0;
                    $grupos_de_palabras = explode(' ', $campos_detalle['descripcion']);
                    $cantidad_de_grupos_descripcion = count($grupos_de_palabras);
                    $dato_a_mostrar = '';

                    for ($i=0; $i < $cantidad_de_grupos_descripcion; $i++)
                    {
                        $dato_a_mostrar.= $grupos_de_palabras[$i].' ';
                        $grupo_siguiente = (isset($grupos_de_palabras[$i+1])) ? $grupos_de_palabras[$i+1] : '';
                        $dato_a_mostrar_siguiente = $dato_a_mostrar.' '.$grupo_siguiente;
                        $largo_del_string_siguiente = $pdf->GetStringWidth(strtoupper($dato_a_mostrar_siguiente));
                        if($largo_del_string_siguiente > ($ancho - 2))
                        {
                            $pdf->Cell($ancho, $campos_repetidos_altura, $dato_a_mostrar, $borde_control, 0, $alineacion, false);
                            $pdf->Ln();
                            $posicion_x_a_poner = $campos_repetidos_x + $campos_repetidos['cantidad'];
                            // $posicion_y_a_poner = $campos_repetidos_y + (($cantidad_de_lineas_hasta_aca+1) * $campos_repetidos_altura);
                            $pdf->Cell($posicion_x_a_poner, $campos_repetidos_altura, '', $borde_control, 0, $alineacion, false);
                            $dato_a_mostrar = '';
                            $tiene_filas_multiples = true;
                            $cantidad_de_filas_multiples++;
                        }
                    }
                break;

                case 'precio_unitario':
                    $dato_a_mostrar = $campos_detalle['obligacion'];
                    $alineacion = 'R';
                break;

                case 'monto-exentas':
                    if(!isset($subtotales['exentas'])) $subtotales['exentas'] = 0;
                    if(!isset($ivas_sumas['exentas'])) $ivas_sumas['exentas'] = 0;
                    if(($campos_detalle['iva_porcentaje'] == 0))
                    {
                        $dato_a_mostrar = $campos_detalle['obligacion'];

                        $subtotales['exentas']+= $campos_detalle['obligacion'];
                        $ivas_sumas['exentas']+= $campos_detalle['iva_monto'];
                    }
                    else
                    {
                        $dato_a_mostrar = '';
                    }
                    $alineacion = 'R';
                break;

                case 'monto-iva_05':
                    // $dato_a_mostrar = ($campos_detalle['iva_porcentaje'] == 0.05) ? $campos_detalle['obligacion'] : '';
                    if(!isset($subtotales['iva_05'])) $subtotales['iva_05'] = 0;
                    if(!isset($ivas_sumas['iva_05'])) $ivas_sumas['iva_05'] = 0;
                    if(($campos_detalle['iva_porcentaje'] == 5))
                    {
                        $dato_a_mostrar = $campos_detalle['obligacion'];

                        $subtotales['iva_05']+= $campos_detalle['obligacion'];
                        $ivas_sumas['iva_05']+= $campos_detalle['iva_monto'];
                    }
                    else
                    {
                        $dato_a_mostrar = '';
                    }
                    $alineacion = 'R';
                break;

                case 'monto-iva_10':
                    // $dato_a_mostrar = ($campos_detalle['iva_porcentaje'] == 0.10) ? $campos_detalle['obligacion'] : '';
                    if(!isset($subtotales['iva_10'])) $subtotales['iva_10'] = 0;
                    if(!isset($ivas_sumas['iva_10'])) $ivas_sumas['iva_10'] = 0;
                    if(($campos_detalle['iva_porcentaje'] == 10))
                    {
                        $dato_a_mostrar = $campos_detalle['obligacion'];

                        $subtotales['iva_10']+= $campos_detalle['obligacion'];
                        $ivas_sumas['iva_10']+= $campos_detalle['iva_monto'];
                    }
                    else
                    {
                        $dato_a_mostrar = '';
                    }
                    $alineacion = 'R';
                break;
            }
            $dato_a_mostrar = is_numeric($dato_a_mostrar) ? number_format($dato_a_mostrar) : $dato_a_mostrar;
            $pdf->Cell($ancho, $campos_repetidos_altura, $dato_a_mostrar, $borde_control, 0, $alineacion, false);
        }
        $pdf->Ln();
    }

    $campos_cabecera = $elementos_a_imprimir['cabecera'];
    foreach ($campos_unicos as $campo_nombre => $campo_datos)
    {
        $pdf->SetFont('Arial', '', 10);
        $alineacion = 'L';
        switch ($campo_nombre)
        {
            case 'fecha_dia':
                $dato_a_mostrar = date('d', strtotime($campos_cabecera['fecha_de_uso']));
            break;

            case 'fecha_mes':
                $meses_en_letras_s = ',enero,febrero,marzo,abril,mayo,junio,julio,agosto,setiembre,octubre,noviembre,diciembre';
                $meses_en_letras = explode(',', $meses_en_letras_s);
                $dato_a_mostrar = $meses_en_letras[date('n', strtotime($campos_cabecera['fecha_de_uso']))];
            break;

            case 'fecha_anho':
                $dato_a_mostrar = substr(date('Y', strtotime($campos_cabecera['fecha_de_uso'])), 2);
            break;

            case 'ruc':
                $dato_a_mostrar = (strtolower($campos_cabecera['ruc']) != 'sin datos' and strtolower($campos_cabecera['ruc']) != 'no aplicable') ? $campos_cabecera['ruc'] : '-------';
            break;

            case 'cuenta':
                $dato_a_mostrar = strtoupper($campos_cabecera['cuenta']);
            break;

            case 'condicion_de_venta-contado':
            case 'condicion_de_venta-credito':
                $tipo_a_detectar = explode('-', $campo_nombre)[1];
                $condicion_de_venta = (!isset($elementos_a_imprimir['detalle'][0]['factura_tipo']) or empty($elementos_a_imprimir['detalle'][0]['factura_tipo'])) ? 'contado' : $elementos_a_imprimir['detalle'][0]['factura_tipo'];
                $dato_a_mostrar = ($condicion_de_venta == $tipo_a_detectar) ? 'X' : '';
            break;

            case 'nota_de_remision':
                $dato_a_mostrar = '-------';
            break;

            case 'subtotales-exentas':
            case 'subtotales-iva_05':
            case 'subtotales-iva_10':
                $tipo_de_iva = explode('-', $campo_nombre)[1];
                $dato_a_mostrar = ($subtotales[$tipo_de_iva] > 0) ? $subtotales[$tipo_de_iva] : '';
                $alineacion = 'R';
            break;

            case 'total_a_pagar_letras':
                $monto_en_letras_1 = '';
                $monto_en_letras_2 = '';
                $montoFinalLetras = '';
                $ancho_hasta_aca = 0;
                $monto_a_usar = $campos_cabecera['monto'];
                include '../../funciones/poner-montos-en-letras-2.php';
                
                $largo_del_string = $pdf->GetStringWidth(strtoupper($string_final));
                $ancho_maximo_del_string = $campo_datos['ancho'] - 5;
                if($largo_del_string > $ancho_maximo_del_string)
                {
                    for ($i= 9; $i >= 4; $i--)
                    { 
                        $pdf->SetFont('Arial', '', $i);
                        $largo_del_string = $pdf->GetStringWidth(strtoupper($string_final));
                        if($largo_del_string < $ancho_maximo_del_string) break;
                    }
                }
                $cantidad_guiones = floor(($campo_datos['ancho'] - $largo_del_string) / $ancho_de_guion);
                if($cantidad_guiones <= 0) $cantidad_guiones = 1;
                $dato_a_mostrar = strtoupper($string_final).str_repeat('-', $cantidad_guiones);
            break;
            
            case 'total_a_pagar_monto':
                $dato_a_mostrar = $campos_cabecera['monto'];
                $alineacion = 'R';
            break;

            case 'ivas-iva_05':
            case 'ivas-iva_10':
                $tipo_de_iva = explode('-', $campo_nombre)[1];
                $dato_a_mostrar = ($ivas_sumas[$tipo_de_iva] > 0) ? $ivas_sumas[$tipo_de_iva] : '';
            break;

            case 'ivas-total':
                $dato_a_mostrar = array_sum($ivas_sumas);
            break;

            // case 'monto':
            //     $ancho_monto = $pdf->GetStringWidth(number_format($campos_cabecera['obligacion']));
            //     $cantidad_guiones = floor(($campo_datos['ancho'] - $ancho_monto) / $ancho_de_guion);
            //     $dato_a_mostrar = number_format($campos_cabecera['derecho']).str_repeat('-', $cantidad_guiones);
            // break;
        }
        $pdf->SetY($campo_datos['y']);
        $pdf->SetX($campo_datos['x']);
        $dato_a_mostrar = is_numeric($dato_a_mostrar) ? number_format($dato_a_mostrar) : $dato_a_mostrar;
        $pdf->Cell($campo_datos['ancho'], 6, $dato_a_mostrar, $borde_control, 0, $alineacion, false);
    }

    $nombre_del_archivo = '../../vistas/sintesis/facturas/'.$_SESSION['factura_a_imprimir'].'.pdf';
    $pdf->Output($nombre_del_archivo);

?>
