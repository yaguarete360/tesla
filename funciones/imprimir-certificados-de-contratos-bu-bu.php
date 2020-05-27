<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set("display_errors", 1);
    include '../librerias/free-pdf-1-8-1/fpdf.php';

    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("off",0);
    $pdf->SetMargins(0, 0, 0);
    $pdf->AddPage($_SESSION['orientacion_certificado'], $_SESSION['dimensiones_certificado']);

    $pdf->SetFont('Arial', '', 20);
    
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(245,231,214);
    $pdf->SetDrawColor(216,162,98);

    $ancho_de_guion = $pdf->GetStringWidth('-');
    
    foreach ($_SESSION['campos_a_imprimir'] as $campo_nombre => $campo_datos)
    {
        $pdf->SetY($campo_datos['y']);
        $pdf->SetX($campo_datos['x']);
        switch ($campo_nombre)
        {
            case 'titular':
                $tamano_fuente_inicial = 23;
                $pdf->SetFont('Arial', '', $tamano_fuente_inicial);
                $largo_del_valor = $pdf->GetStringWidth(strtoupper($campo_datos['valor']));
                if($largo_del_valor > $campo_datos['ancho'])
                {
                    for ($i=$tamano_fuente_inicial; $i > 0; $i--)
                    { 
                        $pdf->SetFont('Arial', '', $i);
                        $largo_del_valor = $pdf->GetStringWidth(strtoupper($campo_datos['valor']));
                        if($largo_del_valor > $campo_datos['ancho']) break;
                    }
                }
                $pdf->Cell($campo_datos['ancho'], $campo_datos['alto'], strtoupper($campo_datos['valor']), 0, 0, 'C', false);
            break;
            
            case 'contrato':
                $pdf->SetFont('Arial', '', 15);
                $pdf->Cell($campo_datos['ancho'], $campo_datos['alto'], strtoupper($campo_datos['valor']), 0, 0, 'C', false);
            break;

            default:
                $pdf->SetFont('Arial', '', 20);
                $pdf->Cell($campo_datos['ancho'], $campo_datos['alto'], $campo_datos['valor'], 0, 0, 'C', false);
            break;
        }
    }

    $pdf->Output();

?>
