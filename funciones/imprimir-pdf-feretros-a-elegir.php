<?php if (!isset($_SESSION)) {session_start();}

include "../librerias/free-pdf/fpdf.php";

if(isset($_POST['imprimir']))
{
    $elementos_a_imprimir = $_SESSION['elementos_a_imprimir'];
    $elementos_a_imprimir_especificacion = $_SESSION['elementos_a_imprimir_especificacion'];
}
    
    class PDF extends FPDF
    {
    	function Header()
    	{
    		$this->SetFont('Arial','B',10);
    		$this->Image('../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
    		$this->Cell(0,20,"",0,0,'C');
    		$this->Ln();
    		
    		$this->SetFont('Arial','B', 14);
    		$this->SetTextColor(255,255,255);
    		$this->SetFillColor(216,162,98);
    		
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
    $pdf->AddPage();
    foreach ($elementos_a_imprimir_especificacion as $value) {
        $pdf->SetFont("Arial", '', 12);
        $pdf->Cell(5);
        $pdf->SetXY(15,40);
        $pdf->Cell(30, 4, 'Codigo Servicio: '.$value['servicio']);
        $pdf->SetFont("Arial", '', 12);
        $pdf->Cell(5);
        $pdf->SetXY(15,50);
        $pdf->Cell(30, 4, 'Feretro: '.$value['feretro']);
        $pdf->SetFont("Arial", '', 12);
        $pdf->Cell(5);
        $pdf->SetXY(15,60);
        $pdf->Cell(30, 4, 'Medida: '.$value['medida']);
    }
    
    foreach ($elementos_a_imprimir as $value) {
    $pdf->SetFont("Arial", '', 13);
    $pdf->Cell(5);
    $pdf->SetXY(15,70);
    $pdf->Cell(30, 4, $value['descripcion_1']);
    $pdf->Ln();
    $pdf->SetFont("Arial", '', 10);
    $pdf->Cell(10);
    $pdf->SetXY(15,80);
    $pdf->Cell(30, 4, $value['descripcion_2']);
    $pdf->SetXY(150,80);
    $pdf->Cell(4, 4, '',1);
    $pdf->Ln();
    $pdf->SetFont("Arial", '', 10);
    $pdf->Cell(10);
    $pdf->SetXY(15,90);
    $pdf->Cell(30, 4, $value['descripcion_3']);
    $pdf->SetXY(150,90);
    $pdf->Cell(4, 4, '',1);
    $pdf->Ln();
    $pdf->SetFont("Arial", '', 10);
    $pdf->Cell(10);
    $pdf->SetXY(15,100);
    $pdf->Cell(30, 4, $value['descripcion_4']);
    $pdf->SetXY(150,100);
    $pdf->Cell(4, 4, '',1);
    $pdf->Ln();

    }
    
    
    $pdf->Output();


?>