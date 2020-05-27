<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set('display_errors', 1);

    $_SESSION['impresion_tamano_fuente'] = 7;
    $_SESSION['margen_izquierdo'] = 20;
    $_SESSION['orientacion'] = 'P';

    include "../librerias/free-pdf/fpdf.php";

    $elementos_a_imprimir = $_SESSION['elementos_a_imprimir'];

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial','B',10);

            $this->Image('../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
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
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on",30);
    $pdf->SetMargins($_SESSION['margen_izquierdo'], 11, 9);
    $pdf->AddPage($_SESSION['orientacion']);

    $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
    $pdf->SetTextColor(0,0,0); // negro
    $pdf->SetFillColor(216,162,98); // color oscuro
    $pdf->SetDrawColor(216,162,98);

    $formas_de_cobranzas = $_SESSION['elementos_a_imprimir']['formas_de_cobranzas'];
    $resumen = $_SESSION['elementos_a_imprimir']['resumen'];
    $campos_a_mostrar = $_SESSION['elementos_a_imprimir']['campos_a_mostrar'];
    $campos_a_mostrar_por_forma_de_pago = $_SESSION['elementos_a_imprimir']['campos_a_mostrar_por_forma_de_pago'];
    $cobranzas_por_instrumento = $_SESSION['elementos_a_imprimir']['cobranzas_por_instrumento'];
    $cobranzas_por_boca_de_cobranza = $_SESSION['elementos_a_imprimir']['cobranzas_por_boca_de_cobranza'];

    $alto_de_fila = 6;
    $ancho_total = 185;
    $ancho_campo_resumen = $ancho_total / (count($formas_de_cobranzas)+2);

	$pdf->SetFillColor(216,162,98); // color oscuro
	$pdf->SetTextColor(255,255,255); // blanco

	$pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']+2);
    $pdf->Cell($ancho_total, $alto_de_fila*1.5, iconv("UTF-8", "ISO-8859-1", 'Resumen de Valores'), 0, 0, 'L', true);
    $pdf->Ln();

    
    $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
    $pdf->Cell($ancho_campo_resumen, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Boca de Cobranza'), 0, 0, 'L', true);
    foreach ($formas_de_cobranzas as $forma_de_cobranza => $vacio) $pdf->Cell($ancho_campo_resumen, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $forma_de_cobranza))), 0, 0, 'L', true);
    $pdf->Cell($ancho_campo_resumen, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Totales'), 0, 0, 'L', true);
    $pdf->Ln();

    $pdf->SetTextColor(0,0,0); // negro
    $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
    foreach ($resumen as $boca_de_cobranza => $valores_cobrados_en_esta_boca)
    {
        $totales_por_linea = 0;
        $pdf->Cell($ancho_campo_resumen, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords($boca_de_cobranza)), 0, 0, 'L', false);
        foreach ($formas_de_cobranzas as $forma_de_cobranza => $vacio)
        {
            $este_monto = (isset($valores_cobrados_en_esta_boca[$forma_de_cobranza])) ? $valores_cobrados_en_esta_boca[$forma_de_cobranza] : 0;
            $pdf->Cell($ancho_campo_resumen, $alto_de_fila, number_format($este_monto), 0, 0, 'R', false);
            if(!isset($totales_resumen[$forma_de_cobranza])) $totales_resumen[$forma_de_cobranza] = 0;
            $totales_resumen[$forma_de_cobranza]+= $este_monto;
            $totales_por_linea+= $este_monto;
        }
        $pdf->Cell($ancho_campo_resumen, $alto_de_fila, number_format($totales_por_linea), 0, 0, 'R', false);
        $pdf->Ln();
    }
    $total_resumen = 0;

    $pdf->Cell($ancho_campo_resumen, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Totales'), 0, 0, 'L', false);
    foreach ($formas_de_cobranzas as $forma_de_cobranza => $vacio)
    {
        $pdf->Cell($ancho_campo_resumen, $alto_de_fila, number_format($totales_resumen[$forma_de_cobranza]), 0, 0, 'R', false);
        $total_resumen+= $totales_resumen[$forma_de_cobranza];
    }
    $pdf->Cell($ancho_campo_resumen, $alto_de_fila, number_format($total_resumen), 0, 0, 'R', false);
    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFillColor(216,162,98); // color oscuro
    $pdf->SetTextColor(255,255,255); // blanco
    $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']+2);
    $pdf->Cell($ancho_total, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Listado por Instrumento'), 0, 0, 'L', true);
    $pdf->Ln();

    $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
    $ancho_campo_por_instrumento = $ancho_total / (count($campos_a_mostrar) + count($campos_a_mostrar_por_forma_de_pago));

    foreach ($campos_a_mostrar as $campo_nombre) $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $campo_nombre))), 0, 0, 'L', true);
    foreach ($campos_a_mostrar_por_forma_de_pago as $campo_nombre) $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $campo_nombre))), 0, 0, 'L', true);
    $pdf->Ln();

    $pdf->SetTextColor(0,0,0); // negro
    foreach ($cobranzas_por_instrumento as $forma_de_cobranza => $cobranzas_en_esta_forma)
    {
        $pdf->Cell($ancho_total, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $forma_de_cobranza))), 0, 0, 'L', false);
        $pdf->Ln();
        $totales = array();
        foreach ($cobranzas_en_esta_forma as $id => $datos_de_la_cobranza)
        {
            foreach ($datos_de_la_cobranza as $campo_nombre => $campo_valor)
            {
                switch ($campo_nombre)
                {
                    case 'iva_monto':
                    case 'monto':
                        $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, number_format($campo_valor), 0, 0, 'R', false);
                        if(!isset($totales[$campo_nombre])) $totales[$campo_nombre] = 0;
                        $totales[$campo_nombre]+= $campo_valor;

                        if(!isset($totales_finales[$campo_nombre])) $totales_finales[$campo_nombre] = 0;
                        $totales_finales[$campo_nombre]+= $campo_valor;
                    break;
                    
                    default:
                        $largo_del_string = $pdf->GetStringWidth($campo_valor)+3;
                        if($largo_del_string > $ancho_campo_por_instrumento)
                        {
                            $campo_valor_original = $campo_valor;
                            $campo_valor = '';
                            for ($i=0; $i < mb_strlen($campo_valor_original); $i++)
                            { 
                                $campo_valor.= $campo_valor_original[$i];
                                $largo_del_string = $pdf->GetStringWidth($campo_valor)+3;
                                if($largo_del_string > $ancho_campo_por_instrumento) 
                                {
                                    $campo_valor.= '...';
                                    break;
                                }
                            }
                        }
                        // echo $campo_valor.'<br/>';
                        $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", $campo_valor), 0, 0, 'L', false);
                        // $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, iconv("UTF-8", 'utf-8//TRANSLIT', str_replace('ñ', 'n', $campo_valor)), 0, 0, 'L', false);

                    break;
                }
            }
            $pdf->Ln();
        }

        $primer_campo = array_keys($datos_de_la_cobranza)[0];
        foreach ($datos_de_la_cobranza as $campo_nombre => $campo_valor)
        {
            switch ($campo_nombre)
            {
                case 'iva_monto':
                case 'monto':
                    $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, number_format($totales[$campo_nombre]), 0, 0, 'R', false);
                break;
                
                case $primer_campo:
                    $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Total '.ucwords($forma_de_cobranza)), 0, 0, 'L', false);
                break;
                
                default:
                    $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, '', 0, 0, 'L', false);
                break;
            }
        }
        $pdf->Ln();
    }
    $primer_campo = array_keys($datos_de_la_cobranza)[0];
    foreach ($datos_de_la_cobranza as $campo_nombre => $campo_valor)
    {
        switch ($campo_nombre)
        {
            case 'iva_monto':
            case 'monto':
                $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, number_format($totales_finales[$campo_nombre]), 0, 0, 'R', false);
            break;
            
            case $primer_campo:
                $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Total'), 0, 0, 'L', false);
            break;
            
            default:
                $pdf->Cell($ancho_campo_por_instrumento, $alto_de_fila, '', 0, 0, 'L', false);
            break;
        }
    }

    $pdf->SetFillColor(216,162,98); // color oscuro
    $pdf->SetTextColor(255,255,255); // blanco
    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']+2);
    $pdf->Cell($ancho_total, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Listado por Boca de Cobranza'), 0, 0, 'L', true);
    $pdf->Ln();

    $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
    $ancho_campo_por_boca_de_cobranza = $ancho_total / (count($campos_a_mostrar) + count($campos_a_mostrar_por_forma_de_pago) + 1);
    $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Instrumento'), 0, 0, 'L', true);
    foreach ($campos_a_mostrar as $campo_nombre) $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $campo_nombre))), 0, 0, 'L', true);
    foreach ($campos_a_mostrar_por_forma_de_pago as $campo_nombre) $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $campo_nombre))), 0, 0, 'L', true);
    $pdf->Ln();

    $pdf->SetTextColor(0,0,0); // negro
    $totales = array();
    foreach ($cobranzas_por_boca_de_cobranza as $boca_de_cobranza => $cobranzas_en_esta_boca)
    {
        $pdf->Cell($ancho_total, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $boca_de_cobranza))), 0, 0, 'L', false);
        $pdf->Ln();
        foreach ($cobranzas_en_esta_boca as $custodia_2 => $cobranzas_de_esta_custodia)
        {
            $pdf->Cell($ancho_total, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $custodia_2))), 0, 0, 'L', false);
            $pdf->Ln();
            foreach ($cobranzas_de_esta_custodia as $id => $datos_de_la_cobranza)
            {
                foreach ($datos_de_la_cobranza as $campo_nombre => $campo_valor)
                {
                    switch ($campo_nombre)
                    {
                        case 'iva_monto':
                        case 'monto':
                            $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, number_format($campo_valor), 0, 0, 'R', false);

                            if(!isset($totales[$boca_de_cobranza][$campo_nombre])) $totales[$boca_de_cobranza][$campo_nombre] = 0;
                            $totales[$boca_de_cobranza][$campo_nombre]+= $campo_valor;
                            if(!isset($totales[$custodia_2][$campo_nombre])) $totales[$custodia_2][$campo_nombre] = 0;
                            $totales[$custodia_2][$campo_nombre]+= $campo_valor;
                        break;

                        default:
                            $largo_del_string = $pdf->GetStringWidth($campo_valor)+3;
                            if($largo_del_string > $ancho_campo_por_instrumento)
                            {
                                $campo_valor_original = $campo_valor;
                                $campo_valor = '';
                                for ($i=0; $i < mb_strlen($campo_valor_original); $i++)
                                { 
                                    $campo_valor.= $campo_valor_original[$i];
                                    $largo_del_string = $pdf->GetStringWidth($campo_valor)+3;
                                    if($largo_del_string > $ancho_campo_por_instrumento) 
                                    {
                                        $campo_valor.= '...';
                                        break;
                                    }
                                }
                            }
                            // echo $campo_valor.'<br/>';
                            $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", $campo_valor), 0, 0, 'L', false);
                            // $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", str_replace('ñ', 'n', $campo_valor)), 0, 0, 'L', false);
                        break;
                    }
                }
                $pdf->Ln();
            }
            $primer_campo = array_keys($datos_de_la_cobranza)[0];
            foreach ($datos_de_la_cobranza as $campo_nombre => $campo_valor)
            {
                switch ($campo_nombre)
                {
                    case 'iva_monto':
                    case 'monto':
                        $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, number_format($totales[$custodia_2][$campo_nombre]), 0, 0, 'R', false);
                    break;
                    
                    case $primer_campo:
                        $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Total '.ucwords($custodia_2)), 0, 0, 'L', false);
                    break;

                    default:
                        $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ''), 0, 0, 'L', false);
                    break;
                }
            }
            $pdf->Ln();
        }
        $primer_campo = array_keys($datos_de_la_cobranza)[0];
        foreach ($datos_de_la_cobranza as $campo_nombre => $campo_valor)
        {
            switch ($campo_nombre)
            {
                case 'iva_monto':
                case 'monto':
                    $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, number_format($totales[$boca_de_cobranza][$campo_nombre]), 0, 0, 'R', false);
                break;
                
                case $primer_campo:
                    $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Total '.ucwords($boca_de_cobranza)), 0, 0, 'L', false);
                break;

                default:
                    $pdf->Cell($ancho_campo_por_boca_de_cobranza, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ''), 0, 0, 'L', false);
                break;
            }
        }
    }

    $pdf->Ln();
    $pdf->SetTextColor(0,0,0); // negro
    $pdf->Output();

?>
