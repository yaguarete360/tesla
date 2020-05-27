<?php if (!isset($_SESSION)) {session_start();}

include "../librerias/free-pdf/fpdf.php";

class PDF extends FPDF
{
	function Header()
	{
		$this->SetFont('Arial','B',10);
		$this->Image('../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
		$this->Cell(0,30,"",0,0,'C');
		$this->Ln();
	}

	function Footer()
	{
		$this->SetFont('Arial','',7);
		$this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".$_SESSION['usuario_en_sesion'].' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		$this->Ln();
		$this->Image('../imagenes/iconos/logo-pie.png',95,$this->GetY(),20,0,'PNG');
	}
}


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak("on",30);
$pdf->SetMargins(10, 11, 10.5);
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0,0,0);
$pdf->SetDrawColor(216,162,98);

include '../funciones/conectar-base-de-datos.php';

$cantidad_status = 0;
$cantidad_total = 0;
$status_en_curso = "";
$cambia_status = "";

$consulta = 'SELECT *
FROM operaciones_feretros
WHERE borrado LIKE "0000-00-00 00:00:00"
ORDER BY status, serie';

$query = $conexion->prepare($consulta);
$query->execute();

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(245,231,214);

if(!isset($rell)) $rell = false;

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{		
	$status_en_curso = $rows['status'];
	
	if($status_en_curso != $cambia_status and $cantidad_total > 0)
	{								
		$pdf->Ln();
		$pdf->Cell(0,1,"","B",0,'L',$rell);
		$pdf->Ln();
		$pdf->Cell(60,5, iconv("UTF-8", "ISO-8859-1", "Total en ".ucfirst($cambia_status)),0,0,'L',$rell);
		$pdf->Cell(30,5, number_format($cantidad_status,0,",","."),0,0,'R',$rell);
		$pdf->Cell(40,5, iconv("UTF-8", "ISO-8859-1", "  de un total de  ".number_format($cantidad_total,0,",",".")),0,0,'R',$rell);
		$pdf->Cell(60,5,"",0,0,'L',$rell);
		$pdf->Ln();
		$pdf->Cell(0,1,"","B",0,'L',$rell);
		$pdf->Ln();
		$pdf->Ln();
		$cantidad_status = 0;
	} 

	$cantidad_status++;
	$cantidad_total++;
	$cambia_status = $rows['status'];

	$pdf->Ln();
	$pdf->Cell(20,5, iconv("UTF-8", "ISO-8859-1", strtoupper($rows['serie'])),0,0,'L',$rell);
	$pdf->Cell(45,5, iconv("UTF-8", "ISO-8859-1", ucwords($rows['feretro'])),0,0,'L',$rell);
	$pdf->Cell(20,5, iconv("UTF-8", "ISO-8859-1", $rows['medida']),0,0,'L',$rell);
	$pdf->Cell(65,5, iconv("UTF-8", "ISO-8859-1", ucwords($rows['status'])),0,0,'L',$rell);
	$pdf->Cell(40,5,"",0,0,'L',$rell);
	($rell == true) ? $rell = false : $rell = true;
}

$pdf->Output();

?>
