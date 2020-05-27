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

$e = 0;

$serie_post = $_POST['serie_a_revisar'];

$consulta = 'SELECT serie, status
FROM operaciones_feretros
WHERE borrado LIKE "0000-00-00 00:00:00"
AND serie LIKE "'.$serie_post.'%"
ORDER BY serie';

$query = $conexion->prepare($consulta);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{	
	$series_existentes[$e] = trim(strtoupper($rows['serie']));
	$e++;
}

for($i = 1; $i < 1000; $i++) 
{
	$serie_generada = $_POST['serie_a_revisar'].str_pad($i,3,"0",STR_PAD_LEFT);
	$series[$i] = trim($serie_generada);
}

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(245,231,214);

if(!isset($rell)) $rell = false;

foreach($series as $serie) 
{
	if(!in_array($serie, $series_existentes))
	{
		$pdf->Ln();
		$pdf->Cell(30,5, iconv("UTF-8", "ISO-8859-1", $serie),0,0,'L',$rell);
		$pdf->Cell(30,5, " No existe.",0,0,'L',$rell);
		($rell == true) ? $rell = false : $rell = true;							
	}
}
$pdf->Ln();

$pdf->Output();

?>
