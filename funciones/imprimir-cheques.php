<?php if (!isset($_SESSION)) {session_start();}
    
    include '../librerias/free-pdf/fpdf.php';

    $elementos_a_imprimir = $_SESSION['elementos_a_imprimir'];

    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on",0);
    $pdf->SetMargins(0, 0, 0);
    $pdf->AddPage('L', array(151, 246));

    $pdf->SetFont('Arial', '', 8);
    
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(245,231,214);
    $pdf->SetDrawColor(216,162,98);

    $alto_de_cada_cheque = 76;
    $ancho_de_cada_cheque = 246;
    $ancho_de_guion = $pdf->GetStringWidth('-');
    
    $campos_a_imprimir['talon_fecha']['y'] = 14;
    $campos_a_imprimir['talon_fecha']['x'] = 27;
    $campos_a_imprimir['talon_fecha']['ancho'] = 22;

    $campos_a_imprimir['talon_orden']['y'] = 19;
    $campos_a_imprimir['talon_orden']['x'] = 27;
    $campos_a_imprimir['talon_orden']['ancho'] = 22;

    $campos_a_imprimir['talon_concepto_1']['y'] = 26;
    $campos_a_imprimir['talon_concepto_1']['x'] = 32;
    $campos_a_imprimir['talon_concepto_1']['ancho'] = 22;

    $campos_a_imprimir['talon_concepto_2']['y'] = 33;
    $campos_a_imprimir['talon_concepto_2']['x'] = 18;
    $campos_a_imprimir['talon_concepto_2']['ancho'] = 35;

    $campos_a_imprimir['talon_monto']['y'] = 60;
    $campos_a_imprimir['talon_monto']['x'] = 34;
    $campos_a_imprimir['talon_monto']['ancho'] = 20;

    $campos_a_imprimir['monto']['y'] = 4;
    $campos_a_imprimir['monto']['x'] = 186;
    $campos_a_imprimir['monto']['ancho'] = 40;

    $campos_a_imprimir['fecha_dia']['y'] = 17;
    $campos_a_imprimir['fecha_dia']['x'] = 146;
    $campos_a_imprimir['fecha_dia']['ancho'] = 8;

    $campos_a_imprimir['fecha_mes']['y'] = 17;
    $campos_a_imprimir['fecha_mes']['x'] = 156;
    $campos_a_imprimir['fecha_mes']['ancho'] = 8;

    $campos_a_imprimir['fecha_anho']['y'] = 17;
    $campos_a_imprimir['fecha_anho']['x'] = 175;
    $campos_a_imprimir['fecha_anho']['ancho'] = 5;

    $campos_a_imprimir['orden']['y'] = 26;
    $campos_a_imprimir['orden']['x'] = 93;
    $campos_a_imprimir['orden']['ancho'] = 133;

    $campos_a_imprimir['monto_letras_1']['y'] = 32;
    $campos_a_imprimir['monto_letras_1']['x'] = 93;
    $campos_a_imprimir['monto_letras_1']['ancho'] = 133;

    $campos_a_imprimir['monto_letras_2']['y'] = 38;
    $campos_a_imprimir['monto_letras_2']['x'] = 61;
    $campos_a_imprimir['monto_letras_2']['ancho'] = 83;

    $i = 0;
    $cheques_por_pagina = 2;
    foreach ($elementos_a_imprimir as $numero_de_orden => $cheque_campos)
    {
        if($i == $cheques_por_pagina)
        {
            $i = 0;
            $pdf->AddPage('L', array(151, 246));
        }
        $alto_desde_arriba = $i * $alto_de_cada_cheque;
        $pdf->SetY($alto_desde_arriba);
        foreach ($campos_a_imprimir as $campo_nombre => $campo_datos)
        {
            switch ($campo_nombre)
            {
                case 'talon_fecha':
                    $dato_a_mostrar = $cheque_campos['efectuado_fecha'];
                break;
                
                case 'fecha_dia':
                    $dato_a_mostrar = date('d', strtotime($cheque_campos['efectuado_fecha']));
                break;

                case 'fecha_mes':
                    $dato_a_mostrar = date('m', strtotime($cheque_campos['efectuado_fecha']));
                break;

                case 'fecha_anho':
                    $dato_a_mostrar = substr(date('Y', strtotime($cheque_campos['efectuado_fecha'])), 2);
                break;

                case 'orden':
                    $dato_a_mostrar = strtoupper($cheque_campos['cuenta_bancaria_titular']);
                break;
                
                case 'talon_orden':
                    // en font size 8 aprox 1.3mm por caracter lowercase / 2.05mm uppercase?
                    $caracteres = floor($campo_datos['ancho'] / 1.8);
                    $dato_a_mostrar = substr(strtoupper($cheque_campos['cuenta_bancaria_titular']), 0, $caracteres);
                break;

                case 'talon_monto':
                    $dato_a_mostrar = number_format($cheque_campos['derecho']);
                break;

                case 'talon_concepto_1':
                    $dato_a_mostrar = 'Pago Proveedor';
                break;

                case 'talon_concepto_2':
                    $dato_a_mostrar = '';
                break;
                
                case 'monto':

                    $ancho_monto = $pdf->GetStringWidth(number_format($cheque_campos['derecho']));
                    $cantidad_guiones = floor(($campo_datos['ancho'] - $ancho_monto) / $ancho_de_guion);
                    $dato_a_mostrar = number_format($cheque_campos['derecho']).str_repeat('-', $cantidad_guiones);
                break;

                case 'monto_letras_1':
                    $monto_en_letras_1 = '';
                    $monto_en_letras_2 = '';
                    $montoFinalLetras = '';
                    $ancho_hasta_aca = 0;
                    $monto_a_usar = $cheque_campos['derecho'];
                    include './poner-montos-en-letras-2.php';
                    
                    $largo_del_string = $pdf->GetStringWidth(strtoupper($string_final));
                    $ancho_maximo_del_string = $campo_datos['ancho'] - 5;
                    if($largo_del_string > $ancho_maximo_del_string)
                    {
                        $monto_en_letras_explotado = explode(' ', $string_final);
                        foreach ($monto_en_letras_explotado as $monto_en_letra)
                        {
                            $ancho_hasta_aca+= $pdf->GetStringWidth(strtoupper($monto_en_letra).' ');
                            if($ancho_hasta_aca > $ancho_maximo_del_string)
                            {
                                $monto_en_letras_2.= strtoupper($monto_en_letra).' ';
                            }
                            else
                            {
                                $monto_en_letras_1.= strtoupper($monto_en_letra).' ';
                            }
                        }
                        $dato_a_mostrar = trim($monto_en_letras_1);
                    }
                    else
                    {
                        $cantidad_guiones = floor(($campo_datos['ancho'] - $largo_del_string) / $ancho_de_guion);
                        $dato_a_mostrar = strtoupper($string_final).str_repeat('-', $cantidad_guiones);
                    }
                    
                break;

                case 'monto_letras_2':
                    $ancho_segunda_linea = $pdf->GetStringWidth($monto_en_letras_2);
                    $cantidad_guiones = floor(($campo_datos['ancho'] - $ancho_segunda_linea) / $ancho_de_guion);
                    $dato_a_mostrar = trim($monto_en_letras_2).str_repeat('-', $cantidad_guiones);
                break;
            }
            $pdf->SetY($alto_desde_arriba + $campo_datos['y']);
            $pdf->SetX($campo_datos['x']);
            $pdf->Cell($campo_datos['ancho'], 6, iconv("UTF-8", "ISO-8859-1", $dato_a_mostrar), 0, 0, 'L', false);
        }
        $i++;
    }

    $pdf->Output();

?>
