<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set('display_errors', 1);

    $_SESSION['url_pdf'] = '';
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
            if(isset($_SESSION['titulo_del_listado']) and !empty($_SESSION['titulo_del_listado']))
            {
                $this->Cell(0, 10, iconv("UTF-8", "ISO-8859-1", $_SESSION['titulo_del_listado']),0,0,'L',false);
                $this->Ln();
            }

            $this->SetTextColor(255,255,255);
            $this->SetFillColor(216,162,98);
            $this->SetFont('Arial','B', 8);
        }

        function Footer()
        {
            $this->SetFont('Arial','',7);
            $this->Cell(0,5,"",0,0,'C');
            $this->Ln();
            $this->Cell(0,2,"Planillas de Asociaciones de Parque Serenidad. R.U.C.: 80001620-3",0,0,'C');
            $this->Ln();
            $this->Cell(0,5,"Impreso el ".date('Y-m-d G:i:s')." por ".$_SESSION['usuario_en_sesion'],0,0,'C');
            $this->Ln();
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on", 5);
    $pdf->SetMargins(20, 10, 5);

    $pdf->SetFont('Arial','', 8);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(245,231,214);
    $pdf->SetDrawColor(216,162,98);

    $alto_filas = 5;
    $anchos['cuenta'] = 13;
    $anchos['centro'] = 13;
    $anchos['contrato_numero'] = 23;
    $anchos['contrato'] = 20;
    $anchos['cliente'] = 70;
    $anchos['cuota'] = 18;
    $anchos['monto'] = 18;
    $anchos['observacion'] = 15;
    $ancho_total = array_sum($anchos);
    $alto_pagina = 279;
    
    $ancho_margen_resumen = ($ancho_total / 7);
    $ancho_campos_resumen = ($ancho_total / 7);
    
    $cabecera_margen_internos = $ancho_total * 0.05;
    $cabecera_columnas_anchos = ($ancho_total * 0.5) - ($cabecera_margen_internos * 2);

    $meses = array('', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
    $periodo_mes = $meses[date('n', strtotime($elementos_a_imprimir['periodo']))];
    $periodo_ano = date('Y', strtotime($elementos_a_imprimir['periodo']));

    foreach ($elementos_a_imprimir['datos'] as $asociacion_nombre => $cuotas_de_la_aso)
    {
        $total_pagina = 0;
        $pagina_numero = 0;
        $pagina_numero++;
        $resumen = array();
        $pdf->AddPage('P');
        $linea_num = 0;

        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'LT', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'SEÑOR(ES):'), 'T', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'PAGINA: '.$pagina_numero), 'T', 0, 'R', false);
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'TR', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'L', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", strtoupper($asociacion_nombre)), 0, 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'ASUNCION, '.date('d').' DE '.$meses[date('n')].' DE '.date('Y')), 0, 0, 'R', false);
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'R', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'L', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'PRESENTE'), 0, 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, '', 0, 0, 'L', false);
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'R', 0, 'L', false);
        $pdf->Ln();
        $pdf->MultiCell($ancho_total, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'Solicitamos por su intermedio, a vuestra Asociación, se sirva descontar de los haberes de los funcionarios la cuota correspondiente a los contratos firmados con nuestra empreza por las compras detalladas a continuacion:'), 'L', 'L', false);
        // $pdf->Ln();
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'LB', 0, 'L', false);
        $periodo_explotado = explode('-', $elementos_a_imprimir['periodo']);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'El descuento corresponde a: '.$periodo_mes.' '.$periodo_ano), 'B', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, '', 'B', 0, 'L', false);
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'BR', 0, 'L', false);
        $pdf->Ln();
        
        foreach ($elementos_a_imprimir['campos'] as $campo_nombre)
        {
            $pdf->Cell($anchos[$campo_nombre], $alto_filas, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $campo_nombre))), 0, 0, 'L', false);
        }
        $pdf->Ln();
        $cuotas_de_la_aso_ids = array_keys($cuotas_de_la_aso);
        $ultima_cuota = end($cuotas_de_la_aso_ids);
        foreach ($cuotas_de_la_aso as $id => $campos_cuota)
        {
            $linea_num++;
            foreach ($elementos_a_imprimir['campos'] as $campo_nombre)
            {
                $campo_valor = $campos_cuota[$campo_nombre];
                switch ($campo_nombre)
                {
                    case 'monto':
                        $pdf->Cell($anchos[$campo_nombre], $alto_filas, number_format($campo_valor), 0, 0, 'R', false);
                        $total_pagina+= $campo_valor;
                    break;
                    
                    case 'centro':
                    case 'cuota':
                        $pdf->Cell($anchos[$campo_nombre], $alto_filas, iconv("UTF-8", "ISO-8859-1", strtoupper($campo_valor)), 0, 0, 'L', false);
                    break;

                    case 'cliente':
                        $pdf->Cell($anchos[$campo_nombre], $alto_filas, iconv("UTF-8", "ISO-8859-1", ucwords($campo_valor)), 0, 0, 'L', false);
                    break;

                    default:
                        $pdf->Cell($anchos[$campo_nombre], $alto_filas, iconv("UTF-8", "ISO-8859-1", $campo_valor), 0, 0, 'L', false);
                    break;
                }
            }
            if(!isset($resumen[$campos_cuota['centro']])) $resumen[$campos_cuota['centro']] = 0;
            $resumen[$campos_cuota['centro']]+= $campos_cuota['monto'];
            $pdf->Ln();
            if($linea_num == 40 or $id == $ultima_cuota)
            {
                foreach ($elementos_a_imprimir['campos'] as $campo_nombre)
                {
                    switch ($campo_nombre)
                    {
                        case 'monto':
                            $pdf->Cell($anchos[$campo_nombre], $alto_filas, 'Total de la Pagina:   '.number_format($total_pagina), 0, 0, 'R', false);
                        break;
                        
                        default:
                            $pdf->Cell($anchos[$campo_nombre], $alto_filas, '', 0, 0, 'L', false);
                        break;
                    }
                }
                $pdf->Ln();
                if($id != $ultima_cuota)
                {
                    $total_pagina = 0;
                    $pdf->AddPage('P');
                    $pagina_numero++;
                    $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'BLT', 0, 'L', false);
                    $pdf->Cell($cabecera_columnas_anchos, $alto_filas, '', 'BT', 0, 'L', false);
                    $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'PAGINA: '.$pagina_numero), 'BT', 0, 'R', false);
                    $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'BTR', 0, 'L', false);
                    $pdf->Ln();
                    $linea_num = 1;
                }
            }
        }
        // RESUMEN
        $pdf->AddPage('P');
        $pagina_numero = 1;
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'LT', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'SEÑOR(ES):'), 'T', 0, 'L', false);
        // $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'PAGINA: '.$pdf->PageNo().'/{nb}'), 'T', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'PAGINA: '.$pagina_numero), 'T', 0, 'R', false);
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'TR', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'L', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", strtoupper($asociacion_nombre)), 0, 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'ASUNCION, '.date('d').' DE '.$meses[date('n')].' DE '.date('Y')), 0, 0, 'R', false);
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'R', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'L', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'PRESENTE'), 0, 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, '', 0, 0, 'L', false);
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'R', 0, 'L', false);
        $pdf->Ln();
        $pdf->MultiCell($ancho_total, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'Solicitamos por su intermedio, a vuestra Asociación, se sirva descontar de los haberes de los funcionarios la cuota correspondiente a los contratos firmados con nuestra empreza por las compras detalladas a continuacion:'), 'LR', 'L', false);
        // $pdf->Ln();
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'LB', 0, 'L', false);
        $periodo_explotado = explode('-', $elementos_a_imprimir['periodo']);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'El descuento corresponde a: '.$periodo_mes.' '.$periodo_ano), 'B', 0, 'L', false);
        $pdf->Cell($cabecera_columnas_anchos, $alto_filas, '', 'B', 0, 'L', false);
        $pdf->Cell($cabecera_margen_internos, $alto_filas, '', 'BR', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($ancho_total, $alto_filas, 'RESUMEN GENERAL', 0, 0, 'C', false);
        $pdf->Ln();
        $pdf->Cell($ancho_margen_resumen, $alto_filas, '', 0, 0, 'L', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, 'CENTRO', 'TLB', 0, 'L', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, 'MONTO TOTAL', 'TB', 0, 'L', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, '% BONIF.', 'TB', 0, 'L', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, 'MONTO BONIF.', 'TB', 0, 'L', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, 'IMPORTE NETO.', 'TRB', 0, 'L', false);
        $pdf->Cell($ancho_margen_resumen, $alto_filas, '', 0, 0, 'L', false);
        $pdf->Ln();

        $total_monto = 0;
        $total_bonificacion = 0;
        $total_neto = 0;
        $bonificaciones_por_asociacion = $elementos_a_imprimir['bonificaciones'];
        $bonificacion_de_esta_aso = (isset($bonificaciones_por_asociacion[$asociacion_nombre])) ? $bonificaciones_por_asociacion[$asociacion_nombre] : 5;
        foreach ($resumen as $centro => $centro_monto)
        {
            $bonificacion = ($bonificacion_de_esta_aso / 100) * $centro_monto;
            $monto_neto = $centro_monto - $bonificacion;

            $pdf->Cell($ancho_margen_resumen, $alto_filas, '', 0, 0, 'L', false);
            $pdf->Cell($ancho_campos_resumen, $alto_filas, strtoupper($centro), 0, 0, 'L', false);
            $pdf->Cell($ancho_campos_resumen, $alto_filas, number_format($centro_monto), 0, 0, 'R', false);
            $total_monto+= $centro_monto;
            $pdf->Cell($ancho_campos_resumen, $alto_filas, number_format($bonificacion_de_esta_aso+0, 2), 0, 0, 'R', false);
            $pdf->Cell($ancho_campos_resumen, $alto_filas, number_format($bonificacion), 0, 0, 'R', false);
            $total_bonificacion+= $bonificacion;
            $pdf->Cell($ancho_campos_resumen, $alto_filas, number_format($monto_neto), 0, 0, 'R', false);
            $total_neto+= $monto_neto;
            $pdf->Cell($ancho_margen_resumen, $alto_filas, '', 0, 0, 'L', false);
            $pdf->Ln();
        }
        $pdf->Ln();
        $pdf->Cell($ancho_margen_resumen, $alto_filas, 'TOTAL-->', 0, 0, 'L', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, '', 'TLB', 0, 'L', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, number_format($total_monto), 'TB', 0, 'R', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, '', 'TB', 0, 'L', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, number_format($total_bonificacion), 'TB', 0, 'R', false);
        $pdf->Cell($ancho_campos_resumen, $alto_filas, number_format($total_neto), 'TRB', 0, 'R', false);
        $pdf->Cell($ancho_margen_resumen, $alto_filas, '', 0, 0, 'L', false);
        $pdf->Ln();
        $montoAUsar = $total_monto;
        include '../funciones/poner-montos-en-letras.php';
        $pdf->MultiCell($ancho_total, $alto_filas, iconv("UTF-8", "ISO-8859-1", 'Son Guaranies: '.strtoupper($montoFinalLetras)), 0, 'L', false);
    }

    
    $pdf->Output();

?>