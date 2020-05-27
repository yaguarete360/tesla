<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set("display_errors", 1);
    include '../librerias/free-pdf-1-8-1/fpdf.php';

    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("off",0);
    $pdf->SetMargins(0, 0, 0);
    
    
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(245,231,214);
    $pdf->SetDrawColor(216,162,98);

    $ancho_de_guion = $pdf->GetStringWidth('-');
    
    foreach ($_SESSION['campos_a_imprimir'] as $contrato_numero => $paginas_del_contrato)
    {
        foreach ($paginas_del_contrato as $pagina => $campos_a_imprimir)
        {
            $pdf->AddPage($_SESSION['orientacion_certificado'], $_SESSION['dimensiones_certificado']);
            $pdf->SetFont('Arial', '', 20);
            foreach ($campos_a_imprimir as $campo_nombre => $campo_datos)
            {
                $pdf->SetY($campo_datos['y']);
                $pdf->SetX($campo_datos['x']);
                switch ($campo_nombre)
                {
                    case 'titular':
                        $titular = str_replace('ñ', 'Ñ', $campo_datos['valor']);
                        $tamano_fuente_inicial = 23;
                        $pdf->SetFont('Arial', '', $tamano_fuente_inicial);
                        $largo_del_valor = $pdf->GetStringWidth(strtoupper($titular));
                        if($largo_del_valor > $campo_datos['ancho'])
                        {
                            for ($i=$tamano_fuente_inicial; $i > 0; $i--)
                            { 
                                $pdf->SetFont('Arial', '', $i);
                                $largo_del_valor = $pdf->GetStringWidth(strtoupper($titular));
                                if($largo_del_valor > $campo_datos['ancho']) break;
                            }
                        }
                        $pdf->Cell($campo_datos['ancho'], $campo_datos['alto'], strtoupper(iconv("UTF-8", "ISO-8859-1", $titular)), 0, 0, 'C', false);

                    break;
                    
                    case 'contrato':
                        $tamano_fuente = ($pagina == 0) ? 15 : 9;
                        $pdf->SetFont('Arial', '', $tamano_fuente);
                        $pdf->Cell($campo_datos['ancho'], $campo_datos['alto'], strtoupper($campo_datos['valor']), 0, 0, 'C', false);
                    break;

                    default:
                        $pdf->SetFont('Arial', '', 20);
                        $pdf->Cell($campo_datos['ancho'], $campo_datos['alto'], $campo_datos['valor'], 0, 0, 'C', false);
                    break;
                }
            }
        }
    }

    $pdf->Output();

?>
