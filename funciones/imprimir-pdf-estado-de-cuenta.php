<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set('display_errors', 1);

    $_SESSION['impresion_tamano_fuente'] = 8;
    $_SESSION['margen_izquierdo'] = 20;
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
    }

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

    $anchos_campos['fecha'] = 20;
    $anchos_campos['descripcion'] = 40;
    $anchos_campos['documento_tipo'] = 40;
    $anchos_campos['documento_numero'] = 20;
    $anchos_campos['factura_numero'] = 20;
    $anchos_campos_montos['derecho'] = 20;
    $anchos_campos_montos['obligacion'] = 20;

    $cantidad_de_elementos_a_imprimir = count($elementos_a_imprimir) - 1;
    foreach ($elementos_a_imprimir['cuentas'] as $cuenta => $cuenta_datos)
    {
        $pdf->SetFillColor(216,162,98); // color oscuro
        $pdf->SetTextColor(255,255,255); // blanco

        $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']+2);
        $pdf->Cell(0, 10, iconv("UTF-8", "ISO-8859-1", '     '.ucwords($cuenta)), 0, 0, 'L', true);
        $pdf->Ln();

        $pdf->SetTextColor(0,0,0); // negro
        $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
        $contador_campos = 0;
        $ancho_datos_campos = 30;
        $ancho_datos_valores = 80;
        foreach ($cuenta_datos as $dato_campo => $dato_valor)
        {
            $pdf->Cell($ancho_datos_campos, 7, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $dato_campo)).': '), 0, 0, 'L');
            $pdf->Cell($ancho_datos_valores, 7, iconv("UTF-8", "ISO-8859-1", $dato_valor), 0, 0, 'L');
            $contador_campos++;
            if($contador_campos % 2 == 0) $pdf->Ln();
        }

        if(isset($elementos_a_imprimir['contratos']) and !empty($elementos_a_imprimir['contratos']))
        {
            if($contador_campos % 2 != 0) $pdf->Ln();
            $ancho_campos_contratos = 20;
            $pdf->SetTextColor(255,255,255); // blanco
        	$pdf->SetFillColor(216,162,98); // color oscuro
            $pdf->Cell($ancho_campos_contratos, 7, iconv("UTF-8", "ISO-8859-1", 'Contratos'), 0, 0, 'L', true);

            foreach ($elementos_a_imprimir['contratos'][array_keys($elementos_a_imprimir['contratos'])[0]] as $campo_nombre => $campo_valor)
            {
                $pdf->Cell($ancho_campos_contratos, 7, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $campo_nombre))), 0, 0, 'L', true);
            }
            $pdf->Ln();
            $pdf->SetTextColor(0,0,0); // negro
            foreach ($elementos_a_imprimir['contratos'] as $contrato => $contrato_campos)
            {
                $pdf->Cell($ancho_campos_contratos, 7, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace('_', ' ', $contrato))), 0, 0, 'L', false);
                foreach ($contrato_campos as $campo_nombre => $campo_valor)
                {
                    switch ($campo_nombre)
                    {
                        case 'cuota_monto':
                        case 'monto_diferido':
                            $pdf->Cell($ancho_campos_contratos, 7, number_format($campo_valor), 0, 0, 'R', false);
                        break;
                        
                        default:
                            $pdf->Cell($ancho_campos_contratos, 7, iconv("UTF-8", "ISO-8859-1", $campo_valor), 0, 0, 'L', false);
                        break;
                    }
                }
                $pdf->Ln();
            }
            
            $pdf->Ln();
        }


        $pdf->SetTextColor(255,255,255); // blanco
        $pdf->Ln();
        $pdf->Cell(array_sum($anchos_campos), 7, iconv("UTF-8", "ISO-8859-1", 'Movimientos Anteriores'), 0, 0, 'L', true);
        $pdf->Cell($anchos_campos_montos['derecho'], 7, number_format($elementos_a_imprimir['saldos_anteriores'][$cuenta]['derecho']), 0, 0, 'R', true);
        $pdf->Cell($anchos_campos_montos['obligacion'], 7, number_format($elementos_a_imprimir['saldos_anteriores'][$cuenta]['obligacion']), 0, 0, 'R', true);
        $pdf->Ln();

        $pdf->SetFillColor(245,231,214); // color claro
    	$pdf->SetTextColor(0,0,0); // negro
        $relleno = false;
        foreach ($elementos_a_imprimir['movimientos'][$cuenta] as $movimiento_id => $movimiento_datos)
        {
        	foreach ($movimiento_datos as $dato_campo => $dato_valor)
        	{
				// mostrar campos
        		switch ($dato_campo)
        		{
        			case 'derecho':
        			case 'obligacion':
        				$pdf->Cell($anchos_campos_montos[$dato_campo], 7, number_format($dato_valor), 0, 0, 'R', $relleno);
        				if(!isset($saldos_finales[$cuenta][$dato_campo])) $saldos_finales[$cuenta][$dato_campo] = 0;
        				$saldos_finales[$cuenta][$dato_campo]+= $dato_valor;
    				break;
        			
        			default:
        				$este_ancho = (isset($anchos_campos[$dato_campo])) ? $anchos_campos[$dato_campo] : 20;
        				$pdf->Cell($este_ancho, 7, iconv("UTF-8", "ISO-8859-1", $dato_valor), 0, 0, 'L', $relleno);
    				break;
        		}
        	}
        	$relleno = ($relleno) ? false : true;
        	$pdf->Ln();
        }

        $pdf->SetFillColor(216,162,98); // color oscuro
    	$pdf->SetTextColor(255,255,255); // blanco

        $pdf->Cell(array_sum($anchos_campos), 7, iconv("UTF-8", "ISO-8859-1", 'Movimientos Anteriores'), 0, 0, 'L', true);
        $pdf->Cell($anchos_campos_montos['derecho'], 7, number_format($saldos_finales[$cuenta]['derecho']), 0, 0, 'R', true);
        $pdf->Cell($anchos_campos_montos['obligacion'], 7, number_format($saldos_finales[$cuenta]['obligacion']), 0, 0, 'R', true);
        $pdf->Ln();

        $saldo_final = $saldos_finales[$cuenta]['derecho'] - $saldos_finales[$cuenta]['obligacion'];
        $saldo_final_derecho = ($saldo_final > 0) ? $saldo_final : 0;
        $saldo_final_obligacion = ($saldo_final > 0) ? 0 : abs($saldo_final);
        $pdf->Cell(array_sum($anchos_campos), 7, iconv("UTF-8", "ISO-8859-1", 'Saldo'), 0, 0, 'L', true);
        $pdf->Cell($anchos_campos_montos['derecho'], 7, number_format($saldo_final_derecho), 0, 0, 'R', true);
        $pdf->Cell($anchos_campos_montos['obligacion'], 7, number_format($saldo_final_obligacion), 0, 0, 'R', true);
        $pdf->Ln();
    }
    $pdf->SetTextColor(0,0,0); // negro
    $pdf->Output();

?>