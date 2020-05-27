<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set('display_errors', 1);

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
            if(isset($_SESSION['titulo_del_listado']) and !empty($_SESSION['titulo_del_listado']))
            {
                $this->Cell(0, 10, iconv("UTF-8", "ISO-8859-1", $_SESSION['titulo_del_listado']),0,0,'L',false);
                $this->Ln();
            }

            $this->SetTextColor(255,255,255);
            $this->SetFillColor(216,162,98);
            $this->SetFont('Arial','B', 7);
        }

        function Footer()
        {
            $this->SetFont('Arial','',7);
            $this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".$_SESSION['usuario_en_sesion'].' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
            $this->Ln();
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on",30);
    $pdf->SetMargins(2, 11, 9);
    $pdf->AddPage('P');

    $relleno = false;

    $pdf->SetFont('Arial','', 7);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(245,231,214);
    $pdf->SetDrawColor(216,162,98);
    $pdf->SetLineWidth(0.2);

    
    $anchos['cuenta_numero'] = 22;
    $anchos['ruc'] = 15;
    $anchos['direccion'] = 125;
    $anchos['telefono'] = 20;
    $anchos['celular'] = 20;
    $ancho_total = array_sum($anchos);
    $anchos['contrato'] = 20;
    $anchos['cuota'] = 15;
    $anchos['cuota_vencimiento'] = 25;
    $anchos['saldo'] = 20;
    $anchos['dias_de_atraso'] = 25;
    $anchos['recargo'] = 25;
    $alto_fila = 5;

    foreach ($elementos_a_imprimir as $barrio => $resultados_del_barrio)
    {
        $pdf->SetLineWidth(0.5);
        $pdf->SetFont('Arial','B', 9);
        $pdf->Cell($ancho_total, $alto_fila * 1.2, 'Barrio: '.ucwords($barrio), "BT", 0, 'L', true);
        $pdf->Ln();
        foreach ($resultados_del_barrio as $cuenta => $campos_linea)
        {
            $pdf->SetLineWidth(0.2);
            $pdf->SetFont('Arial','B', 8);
            $pdf->Cell($ancho_total, $alto_fila * 1.1, iconv("UTF-8", "ISO-8859-1", ucwords($cuenta)), "T", 0, 'L', true);
            $pdf->Ln();
            
            foreach ($campos_linea['datos'] as $campo_nombre => $campo_dato)
            {
                $pdf->SetFont('Arial','U', 8);
                if($campo_nombre != 'observaciones')
                {
                    $pdf->Cell($anchos[$campo_nombre], $alto_fila * 1.1, ucwords(str_replace('_', ' ', $campo_nombre)), "", 0, 'L', false);
                }
            }
            $pdf->Ln();

            $pdf->SetFont('Arial','', 7);
            foreach ($campos_linea['datos'] as $campo_nombre => $campo_dato)
            {
                switch ($campo_nombre)
                {
                    case 'observaciones':
                    break;

                    case 'direccion':
                        $pdf->Cell($anchos[$campo_nombre], $alto_fila, iconv("UTF-8", "ISO-8859-1", implode('/', $campo_dato)), "", 0, 'L', false);
                    break;

                    default:
                        $pdf->Cell($anchos[$campo_nombre], $alto_fila, iconv("UTF-8", "ISO-8859-1", $campo_dato), "", 0, 'L', false);
                    break;
                }
            }
            $pdf->Ln();

            $pdf->MultiCell($ancho_total, $alto_fila, iconv("UTF-8", "ISO-8859-1", 'Observaciones: '.$campos_linea['datos']['observaciones']), 0, 'L', false);
            // $pdf->Ln();

            $pdf->SetFont('Arial','B', 8);
            $pdf->Cell($anchos['contrato'], $alto_fila, 'Contrato', "", 0, 'L', false);
            $primer_contrato = array_keys($campos_linea['contratos'])[0];
            $primera_cuota = array_keys($campos_linea['contratos'][$primer_contrato])[1];
            foreach ($campos_linea['contratos'][$primer_contrato][$primera_cuota] as $dato_nombre => $dato)
            {
                $pdf->Cell($anchos[$dato_nombre], $alto_fila, ucwords(str_replace('_', ' ', $dato_nombre)), "", 0, 'L', false);
            }
            $pdf->Ln();

            $pdf->SetFont('Arial','', 7);
            foreach ($campos_linea['contratos'] as $contrato => $cuotas)
            {
                foreach ($cuotas as $cuota_vencimiento => $cuota_datos)
                {
                    if($cuota_vencimiento != 'saldo')
                    {
                        $pdf->Cell($anchos['contrato'], $alto_fila, $contrato, "", 0, 'L', false);
                        foreach ($cuota_datos as $dato_nombre => $dato)
                        {
                            switch ($dato_nombre)
                            {
                                case 'saldo':
                                case 'dias_de_atraso':
                                    $pdf->Cell($anchos[$dato_nombre], $alto_fila, number_format($dato), "", 0, 'R', false);
                                break;
                                
                                default:
                                    $pdf->Cell($anchos[$dato_nombre], $alto_fila, iconv("UTF-8", "ISO-8859-1", $dato), "", 0, 'L', false);
                                break;
                            }
                        }
                        $pdf->Ln();
                    }
                }
            }
        }
        // $pdf->SetLineWidth(0.5);
        $pdf->Cell($ancho_total, $alto_fila, ' ', "", 0, 'L', true);
        $pdf->Ln();
    }

    $pdf->Output();

?>
