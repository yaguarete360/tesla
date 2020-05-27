<?php if (!isset($_SESSION)) {session_start();}

include "../librerias/free-pdf/fpdf.php";

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(20, 0, 0);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(249,240,226);
$pdf->SetFont('Arial','',8);

$pdf->Image('../imagenes/iconos/logo-cabecera.png',20,4,40,0,'PNG');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(180,5,ucwords($_SESSION['titulo_pagina']).' '.$_SESSION['ano_elegido'],0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','',7);

if(!isset($rell)) $rell = false;

foreach($_SESSION['pdf_texto'] as $fila => $texto)
{
	if($_SESSION['pdf_nivel'][$fila] == "2") $pdf->Cell(10,5,"  ",0,0,'L',$rell);
	$pdf->Cell(57,5,ucwords(str_replace("_"," ",$_SESSION['pdf_nombre'][$fila])),0,0,'L',$rell);
	if($_SESSION['pdf_nivel'][$fila] == "1") $pdf->Cell(42,5,"  ",0,0,'L',$rell);
	$pdf->Cell(32,5,number_format($_SESSION['pdf_valor'][$fila],2,",","."),0,0,'R',$rell);		
	if($_SESSION['pdf_nivel'][$fila] == "2") $pdf->Cell(32,5,"  ",0,0,'L',$rell);		
	$pdf->Cell(17,5,number_format($_SESSION['pdf_porcentaje'][$fila],2,",","."),0,0,'R',$rell);		
	$pdf->Cell(32,5,$_SESSION['pdf_texto'][$fila],0,0,'L',$rell);
	$pdf->Ln();
	($rell == true) ? $rell = false : $rell = true;
}

$pdf->Output();

?>
