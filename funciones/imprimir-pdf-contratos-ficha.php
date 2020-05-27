<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set('display_errors', 1);

    $_SESSION['impresion_tamano_fuente'] = 7.5;
    $_SESSION['margen_izquierdo'] = 5;
    $_SESSION['orientacion'] = 'P';

    $_SESSION['url_pdf'] = (isset($_POST['guardar_pdf'])) ? '../' : '';
    include $_SESSION['url_pdf']."../librerias/free-pdf/fpdf.php";

    $elementos_a_imprimir = $_SESSION['elementos_a_imprimir'];

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial','B',10);

            $this->Image($_SESSION['url_pdf'].'../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
            $this->Cell(0,20,"",0,0,'C');
            $this->Ln();
            if(isset($_POST['titulo_del_listado']) and !empty($_POST['titulo_del_listado']))
            {
                $this->Cell(0, 10, iconv("UTF-8", "ISO-8859-1", $_POST['titulo_del_listado']),0,0,'L',false);
                $this->Ln();
            }

            $this->SetTextColor(255,255,255);
            $this->SetFillColor(216,162,98);
            $this->SetFont('Arial','B', $_SESSION['impresion_tamano_fuente']);
        }

        function Footer()
        {
            $this->SetFont('Arial','',7);
            $this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".ucwords($_SESSION['usuario_en_sesion']).' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
            $this->Ln();
        }

        function colores_cabecera_1()
        {
            $this->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']+2);
            $this->SetTextColor(255,255,255); // blanco
            $this->SetFillColor(216,162,98); // color oscuro
            $this->SetDrawColor(216,162,98);
        }

        function colores_cabecera_2()
        {
            $this->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']+1);
            $this->SetTextColor(0,0,0); // negro
            $this->SetFillColor(230,195,152); // color medio
            $this->SetDrawColor(216,162,98); // color oscuro
        }
        
        function colores_cabecera_3()
        {
            $this->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
            $this->SetTextColor(0,0,0); // negro
            $this->SetFillColor(245,231,214); // color claro
            $this->SetDrawColor(216,162,98); // color oscuro
        }
        
        function colores_normales()
        {
            $this->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
            $this->SetTextColor(0,0,0); // negro
            $this->SetFillColor(255,255,255); // color blanco
            $this->SetDrawColor(216,162,98); // color oscuro
        }
    }

    function abreviar_cabeceras($cabecera_a_abreviar)
    {
        $cabecera_a_abreviar = str_replace('beneficiario', 'benef.', $cabecera_a_abreviar);
        $cabecera_a_abreviar = str_replace('efectuado', 'efec.', $cabecera_a_abreviar);
        // $cabecera_a_abreviar = str_replace('fecha', 'fec.', $cabecera_a_abreviar);
        $cabecera_a_abreviar = str_replace('vencimiento', 'ven.', $cabecera_a_abreviar);
        $cabecera_a_abreviar = str_replace('numero', 'num.', $cabecera_a_abreviar);
        $cabecera_a_abreviar = str_replace('documento', 'doc.', $cabecera_a_abreviar);
        $cabecera_a_abreviar = str_replace('dias_de_', '', $cabecera_a_abreviar);
        return $cabecera_a_abreviar;
    }

    function acortar_listados($string_a_acortar, $largo_del_string, $largo_maximo)
    {
        if($largo_del_string > $largo_maximo)
        {
            $largo_promedio_por_caracter = round(strlen($string_a_acortar) / $largo_del_string);
            $caracteres_que_entran = floor($largo_maximo / $largo_promedio_por_caracter) - 9;
            $string_acortado = trim(substr($string_a_acortar, 0, $caracteres_que_entran)).'...';
        }
        else
        {
            $string_acortado = $string_a_acortar;
        }
        
        return $string_acortado;
    }

    $contratos = $elementos_a_imprimir['contratos'];
    $sitios = $elementos_a_imprimir['sitios'];
    $observaciones = $elementos_a_imprimir['observaciones'];
    $contratos_por_beneficiario = $elementos_a_imprimir['contratos_por_beneficiario'];
    $movimientos = $elementos_a_imprimir['movimientos'];
    $campos_por_beneficiario = $elementos_a_imprimir['campos_por_beneficiario'];
    $campos_del_diario = $elementos_a_imprimir['campos_del_diario'];
    $campos_por_sitio = $elementos_a_imprimir['campos_por_sitio'];
    $campos_por_inh = $elementos_a_imprimir['campos_por_inh'];

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on",30);
    $pdf->SetMargins($_SESSION['margen_izquierdo'], 11, 9);
    $pdf->AddPage($_SESSION['orientacion']);

    $relleno = false;

    $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
    $pdf->SetTextColor(0,0,0); // negro
    $pdf->SetFillColor(216,162,98); // color oscuro
    $pdf->SetDrawColor(216,162,98);
    
    $ancho_total = 200;
    $ancho_campos_cabecera_contratos = $ancho_total / 4;
    // $ancho_campos_detalle_contrato = $ancho_total / count($campos_por_beneficiario);
    $ancho_campos_detalle_contrato_defecto = $ancho_total / count($campos_por_beneficiario);
    $ancho_campos_detalle_contrato['beneficiario_numero'] = 5;
    $ancho_campos_detalle_contrato['beneficiario'] = 90;
    $ancho_campos_detalle_contrato['estado'] = 13;
    $ancho_campos_detalle_contrato['beneficiario_edad'] = 10;
    $ancho_campos_detalle_contrato['beneficiario_nacimiento'] = 15;
    $ancho_campos_detalle_contrato['beneficiario_defuncion'] = 15;
    $ancho_campos_detalle_contrato['beneficiario_vigencia'] = 15;
    $ancho_campos_detalle_contrato['cuota_monto'] = 17;
    $ancho_campos_detalle_contrato['obs'] = 20;

    $ancho_campos_cabecera_inh = $ancho_total / count($campos_por_sitio);
    $ancho_campos_detalle_inh = $ancho_total / count($campos_por_inh);
    // $ancho_campos_detalle_movimientos = $ancho_total / count($campos_del_diario);
    $ancho_campos_detalle_movimientos['fecha'] = 15;
    // $ancho_campos_detalle_movimientos['cuota'] = 14;
    $ancho_campos_detalle_movimientos['descripcion'] = 37;
    $ancho_campos_detalle_movimientos['cuota_vencimiento'] = 15;
    $ancho_campos_detalle_movimientos['efectuado_fecha'] = 15;
    $ancho_campos_detalle_movimientos['efectuado_por'] = 32;
    $ancho_campos_detalle_movimientos['dias_de_atraso'] = 7;
    $ancho_campos_detalle_movimientos['mora'] = 14;
    $ancho_campos_detalle_movimientos['factura_numero'] = 22;
    $ancho_campos_detalle_movimientos['recibo_numero'] = 13;
    $ancho_campos_detalle_movimientos['derecho'] = 15;
    $ancho_campos_detalle_movimientos['obligacion'] = 15;
    $ancho_campos_detalle_movimientos_defecto = $ancho_total / count($campos_del_diario);

    $es_primera_pagina = true;
    foreach ($contratos as $contrato => $contrato_datos)
    {
        if(!$es_primera_pagina) $pdf->AddPage($_SESSION['orientacion']);
        $es_primera_pagina = false;

        $pdf->colores_cabecera_1();
        $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", $contrato), 0, 0, 'L', true);
        $pdf->Ln();
        $contador_campos = 0;
        foreach ($contrato_datos as $dato_campo => $dato_valor)
        {
            $pdf->colores_cabecera_3();
            $pdf->Cell($ancho_campos_cabecera_contratos, 5, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', abreviar_cabeceras($dato_campo)))), 0, 0, 'L', true);
            $pdf->colores_normales();
            $pdf->Cell($ancho_campos_cabecera_contratos, 5, iconv("UTF-8", "ISO-8859-1", $dato_valor), 0, 0, 'L', false);
            $contador_campos++;
            if($contador_campos % 2 == 0) $pdf->Ln();
        }

        if($contrato_datos['sitio'] != '0-0-00-000-0000')
        {
            $pdf->colores_cabecera_2();
            $pdf->Ln();
            $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", '* CONTROLAR DATOS DEL SITIO'), 0, 0, 'L', true);
            $pdf->Ln();
            if(isset($sitios[$contrato]))
            {
                foreach ($sitios[$contrato] as $inh_numero => $inh_campos)
                {
                    $ancho_del_campo = ($inh_numero == 0) ? $ancho_campos_cabecera_inh : $ancho_campos_detalle_inh;
                    if($inh_numero == 0 or $inh_numero == 1)
                    {
                        $pdf->colores_cabecera_2();
                        foreach ($inh_campos as $campo_nombre => $campo_valor)
                        {
                            $campo_nombre = str_replace('beneficiario', 'benef.', $campo_nombre);
                            $pdf->Cell($ancho_del_campo, 7, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', abreviar_cabeceras($campo_nombre)))), 0, 0, 'L', true);
                        }
                        $pdf->Ln();
                    }

                    $pdf->colores_normales();
                    foreach ($inh_campos as $campo_nombre => $campo_valor)
                    {
                        $pdf->Cell($ancho_del_campo, 4, iconv("UTF-8", "ISO-8859-1", $campo_valor), 0, 0, 'L', false);
                    }
                    $pdf->Ln();
                }
            }
            else
            {
                $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", 'NO SE ENCONTRO EL SITIO '.$contrato_datos['sitio']), 0, 0, 'L', false);
                $pdf->Ln();
            }
        }

        $pdf->colores_normales();
        $pdf->Ln();
        // MULTICELL ???
        // $pdf->Cell($ancho_total, 4, iconv("UTF-8", "ISO-8859-1", 'Observaciones: '.$observaciones[$contrato]), 0, 0, 'L', true);
        $pdf->MultiCell($ancho_total, 4, iconv("UTF-8", "ISO-8859-1", 'Observaciones: '.$observaciones[$contrato]), 0 , 'J' , false);
        $pdf->Ln();

        $pdf->colores_cabecera_2();
        $pdf->Cell($ancho_total, 4, iconv("UTF-8", "ISO-8859-1", 'Beneficiarios'), 0, 0, 'L', true);
        $pdf->Ln();

        if(!empty($contratos_por_beneficiario[$contrato]) or count($contratos_por_beneficiario[$contrato]) > 0)
        {
            $pdf->colores_cabecera_3();
            foreach ($campos_por_beneficiario as $campo_por_beneficiario)
            {
                // $campo_nombre = str_replace('beneficiario', 'benef.', $campo_por_beneficiario);
                switch ($campo_por_beneficiario)
                {
                    case 'beneficiario_numero':
                        $campo_nombre = '#';
                    break;

                    case 'beneficiario_nacimiento':
                    case 'beneficiario_edad':
                    case 'beneficiario_vigencia':
                        $campo_nombre = str_replace('beneficiario_', '', $campo_por_beneficiario);
                    break;

                    case 'beneficiario_defuncion':
                        $campo_nombre = 'baja';
                    break;
                    
                    default:
                        $campo_nombre = $campo_por_beneficiario;
                    break;
                }
                // $pdf->Cell($ancho_campos_detalle_contrato, 5, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', abreviar_cabeceras($campo_por_beneficiario)))), 0, 0, 'L', true);
                $ancho_del_campo = isset($ancho_campos_detalle_contrato[$campo_por_beneficiario]) ? $ancho_campos_detalle_contrato[$campo_por_beneficiario] : $ancho_campos_detalle_contrato_defecto;
                $pdf->Cell($ancho_del_campo, 5, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $campo_nombre))), 0, 0, 'L', true);
            }
            $pdf->Ln();
            
            $pdf->colores_normales();
            foreach ($contratos_por_beneficiario[$contrato] as $beneficiario_numero => $beneficiario_datos)
            {
                foreach ($beneficiario_datos as $beneficiario_dato_nombre => $beneficiario_dato)
                {
                    $ancho_del_campo = isset($ancho_campos_detalle_contrato[$beneficiario_dato_nombre]) ? $ancho_campos_detalle_contrato[$beneficiario_dato_nombre] : $ancho_campos_detalle_contrato_defecto;
                    // echo isset($ancho_campos_detalle_contrato[$beneficiario_dato_nombre]) ? 'ancho' : '|'.$beneficiario_dato_nombre.'defecto|';
                    switch ($beneficiario_dato_nombre)
                    {
                        case 'beneficiario_numero':
                        case 'cuota_monto':
                            $alineacion = 'R';
                            $dato_a_usar = $beneficiario_dato;
                        break;

                        case 'beneficiario':
                            $alineacion = 'L';
                            $dato_a_usar = acortar_listados($beneficiario_dato, $pdf->GetStringWidth($beneficiario_dato), $ancho_del_campo);
                        break;
                        
                        default:
                            $dato_a_usar = $beneficiario_dato;
                            $alineacion = 'L';
                        break;
                    }
                    $pdf->Cell($ancho_del_campo, 4, iconv("UTF-8", "ISO-8859-1", $dato_a_usar), 0, 0, $alineacion, false);
                }
                $pdf->Ln();
            }
        }

        $pdf->colores_cabecera_2();
        $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", 'Detalle'), 0, 0, 'L', true);
        $pdf->Ln();

        $pdf->colores_cabecera_3();
        foreach ($campos_del_diario as $campo_diario)
        {
            $ancho_del_campo = isset($ancho_campos_detalle_movimientos[$campo_diario]) ? $ancho_campos_detalle_movimientos[$campo_diario] : $ancho_campos_detalle_movimientos_defecto;
            $pdf->Cell($ancho_del_campo, 5, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', abreviar_cabeceras($campo_diario)))), 0, 0, 'L', true);
        }
        $pdf->Ln();
        
        foreach ($movimientos[$contrato] as $movimiento_id => $movimiento_datos)
        {
            $pdf->colores_normales();
            $es_mora = strpos($movimiento_datos['descripcion'], 'mora') !== false;
            $es_descuento = strpos($movimiento_datos['descripcion'], 'descuento') !== false;
            $dias_de_atraso = 0;
            if(!$es_mora and !$es_descuento)
            {
                $dia_de_calculo_atraso = ($movimiento_datos['efectuado_fecha'] == '0000-00-00') ? date('Y-m-d') : $movimiento_datos['cuota_vencimiento'];
                $dias_de_atraso = round((strtotime($dia_de_calculo_atraso) - strtotime($movimiento_datos['cuota_vencimiento'])) / 86400);
            }
            $estilo_tr = '';
            if($dias_de_atraso > 0)
            {
                $pdf->SetFont('Arial','B', $_SESSION['impresion_tamano_fuente']);
                $pdf->SetTextColor(255,0,0); // blanco
            }
            foreach ($movimiento_datos as $dato_campo => $dato_valor)
            {
                $ancho_del_campo = isset($ancho_campos_detalle_movimientos[$dato_campo]) ? $ancho_campos_detalle_movimientos[$dato_campo] : $ancho_campos_detalle_movimientos_defecto;
                switch ($dato_campo)
                {
                    case 'derecho':
                    case 'obligacion':
                    case 'dias_de_atraso':
                    case 'mora':
                        $pdf->Cell($ancho_del_campo, 4, number_format($dato_valor), 0, 0, 'R', false);
                    break;

                    case 'descripcion':
                        // $dato_valor = str_replace('cobranza', 'cob.', $dato_valor);
                        // $dato_valor = str_replace('cuota', 'cu.', $dato_valor);
                        $dato_valor = str_replace('de la ', '', $dato_valor);
                        $pdf->Cell($ancho_del_campo, 4, iconv("UTF-8", "ISO-8859-1", acortar_listados($dato_valor, $pdf->GetStringWidth($dato_valor), $ancho_del_campo)), 0, 0, 'L', false);
                    break;
                    
                    default:
                        $pdf->Cell($ancho_del_campo, 4, iconv("UTF-8", "ISO-8859-1", acortar_listados($dato_valor, $pdf->GetStringWidth($dato_valor), $ancho_del_campo)), 0, 0, 'L', false);
                    break;
                }
            }
            $pdf->Ln();
            $pdf->colores_normales();
        }
        
        // $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", ' '), 'B', 0, 'L', false);
        // $pdf->Ln();
    }
    $pdf->SetTextColor(0,0,0); // negro
    $pdf->Output();

?>