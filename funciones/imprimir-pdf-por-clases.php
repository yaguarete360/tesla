<?php if (!isset($_SESSION)) {session_start();}

include "../librerias/free-pdf-1-8-1/fpdf.php";

$elementos_a_imprimir = $_SESSION['elementos_a_imprimir'];


class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial','B',10);
        $this->SetTextColor(0,0,0);

        $this->Image($_SESSION['url_pdf'].'../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
        $this->Cell(0, 20, '', 0, 0, 'C');
        $this->Ln();
        $this->Cell(0, 5, $_POST['titulo_del_pdf'], 0, 0, 'L');
        // $this->Ln();

        $this->SetFillColor(216,162,98);
        $this->SetFont('Arial','B', 7);
        $this->Ln();
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
$pdf->SetMargins(10, 11, 10);
$pdf->SetAutoPageBreak('auto', 15);
$pdf->AddPage();

$pdf->SetFont('Arial','B',8);

$ancho_de_pagina = $pdf->GetPageWidth();
foreach ($elementos_a_imprimir[0] as $campo_nombre => $campo_valor)
{
	switch ($campo_nombre)
	{
		case 'fecha':
			$anchos[$campo_nombre] = $ancho_de_pagina * 0.10;
		break;

		case 'cobrador':
			$anchos[$campo_nombre] = $ancho_de_pagina * 0.40;
		break;

		case 'entra':
		case 'sale':
			$anchos[$campo_nombre] = $ancho_de_pagina * 0.10;
		break;

		case 'saldo':
			$anchos[$campo_nombre] = $ancho_de_pagina * 0.15;
		break;
	}
}

foreach ($elementos_a_imprimir as $linea => $campos)
{
	switch ($campos['clase_de_linea'])
	{
		case 'nombres_de_campos':
			$pdf->SetFont('Arial', 'B',8);
			foreach ($campos as $campo_nombre => $campo_valor)
			{
				if($campo_nombre != 'clase_de_linea')
				{
					switch ($campo_nombre)
					{
						default:
							$pdf->Cell($anchos[$campo_nombre], 7, $campo_valor,0,0,'L');
						break;
					}
				}
			}
		break;

		case 'nombre_de_capitulo':
			$pdf->Ln();
			$pdf->SetFont('Arial', 'B', 10);
			foreach ($campos as $campo_nombre => $campo_valor)
			{
				if($campo_nombre != 'clase_de_linea')
				{
					switch ($campo_nombre)
					{
						case 'cobrador':
							$pdf->Cell($anchos[$campo_nombre], 10, iconv("UTF-8", "ISO-8859-1", trim($campo_valor)),0,0,'L');
						break;
						
						default:
							$pdf->Cell($anchos[$campo_nombre], 10, '',0,0,'R');
						break;
					}
				}
			}
		break;

		case 'saldo':
			$pdf->SetFont('Arial', '', 8);
			$pdf->SetFillColor(217, 217, 217);
			foreach ($campos as $campo_nombre => $campo_valor)
			{
				if($campo_nombre != 'clase_de_linea')
				{
					switch ($campo_nombre)
					{
						case 'fecha':
						case 'cobrador':
							$pdf->Cell($anchos[$campo_nombre], 7, iconv("UTF-8", "ISO-8859-1", trim($campo_valor)),0,0,'L', true);
						break;

						case 'entra':
						case 'sale':
						case 'saldo':
							$pdf->Cell($anchos[$campo_nombre], 7, number_format($campo_valor) ,0,0,'R', true);
						break;
						
						default:
							$pdf->Cell(10, 7, iconv("UTF-8", "ISO-8859-1", trim($campo_valor)),0,0,'L', true);
						break;
					}
				}
			}
		break;
		
		case 'normal':
		default:
			$pdf->SetFont('Arial', '', 8);
			foreach ($campos as $campo_nombre => $campo_valor)
			{
				if($campo_nombre != 'clase_de_linea')
				{
					switch ($campo_nombre)
					{
						case 'fecha':
						case 'cobrador':
							$pdf->Cell($anchos[$campo_nombre], 7, iconv("UTF-8", "ISO-8859-1", trim($campo_valor)),0,0,'L');
						break;

						case 'entra':
						case 'sale':
						case 'saldo':
							$pdf->Cell($anchos[$campo_nombre], 7, number_format($campo_valor) ,0,0,'R');
						break;
						
						default:
							$pdf->Cell(10, 7, iconv("UTF-8", "ISO-8859-1", trim($campo_valor)),0,0,'L');
						break;
					}
				}
			}
		break;
	}
	$pdf->Ln();
}

$pdf->Output();

?>

