<?php if (!isset($_SESSION)) {session_start();}

include "../librerias/free-pdf/fpdf.php";

class PDF extends FPDF
{
  function Header()
  {
	$this->SetFont('Arial','B',10);
	$this->Image('../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
	$this->Cell(0,10,"",0,0,'C');
	$this->Ln();
	if(isset($_POST['titulo'])) $this->Cell(51,5, $_POST['titulo']);
	$this->Ln();
  }
  function Footer()
  {
	$this->Ln();
	$this->SetFont('Arial','',7);
	$this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".$_SESSION['usuario_en_sesion'].' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	$this->Ln();
	$this->Image('../imagenes/iconos/logo-pie.png',95,$this->GetY(),20,0,'PNG');
  }
}


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 20);
$pdf->SetAutoPageBreak(10, 35);
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->Ln();

$cantidad_registros = 0;

if(!isset($rell)) $rell = false;

$pdf->Cell(1,5,ucfirst($_SESSION['titulo_pagina']),0,0,'L');
$pdf->Ln();

foreach($_SESSION['campos'] as $campo_vuelta => $campo_nombre)
{
	$campos_abreviados = explode("_",$campo_nombre);

	if(isset($campos_abreviados[1]))
	{
		$encabezado = "";
		foreach($campos_abreviados as $campo_abreviado) $encabezado.= ucwords(substr($campo_abreviado,0,3)).".";
	}
	else
	{
		$encabezado = ucwords($campo_nombre);
	}
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFillColor(85,61,36);
	
	$pdf->Cell($_SESSION['anchos'][$campo_vuelta],5,ucfirst($encabezado),0,0,'L',true);

}
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(249,240,226);
$pdf->SetFont('Arial','',8);

include "../funciones/conectar-base-de-datos.php";

$consulta = 'SELECT * 
FROM '.$_SESSION['tabla'].'
WHERE borrado LIKE "0000-00-00 00:00:00"
AND fecha BETWEEN "'.$_SESSION['fecha_desde'].'" 
AND "'.$_SESSION['fecha_hasta'].'" 
ORDER BY fecha '.$_SESSION['sentido'].'';

$query = $conexion->prepare($consulta);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
	$pdf->Ln();
	foreach($_SESSION['campos'] as $campo_vuelta => $campo_nombre)
	{					
		if($campo_nombre == "costo")
		{
			$pdf->Cell($_SESSION['anchos'][$campo_vuelta],5,number_format($rows[$campo_nombre],2,",","."),0,0,'R',$rell);
		}
		else 
		{
			$pdf->Cell($_SESSION['anchos'][$campo_vuelta],5,$rows[$campo_nombre],0,0,'L',$rell);
		}
	}	
	$cantidad_registros++;
	($rell == true) ? $rell = false : $rell = true;
}

$pdf->Ln();
$pdf->SetFont('Arial','',7);
$pdf->Cell(0,5, "Fin del listado de ".$cantidad_registros." registros",0,0,'L');
$pdf->Ln();
$pdf->Output();

?>
