<?php if (!isset($_SESSION)) {session_start();}
$url = '../../';
include "../librerias/free-pdf/fpdf.php";

include '../funciones/agregar-clase-pdf-codigo-de-barras.php';

include '../funciones/conectar-base-de-datos.php';

$pdf = new FPDF_con_barra();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak("on", 0);
$pdf->SetMargins(10, 11, 10.5);
$pdf->AddPage();

$pdf->SetFillColor(0,0,0);
$pdf->SetFont('Arial','',7);

$pdf->Ln();

// $vertical = 280;
$vertical = 255;
$ancho = 45;
$alto = 10;
$cantidad_de_barras = 0;

if($_POST['mortaja'] != 'no hay mortaja')
{
	$cantidad_de_barras++;
}

if($_POST['serie'] != 'no hay serie')
{
	$cantidad_de_barras++;
}

if($_POST['libro'] != 'no hay libro')
{
	$cantidad_de_barras++;
}

$espacio_total = 210;
$ancho_usado_por_barras = $ancho * $cantidad_de_barras;
$espacio_libre = $espacio_total - $ancho_usado_por_barras;
$interespaciado = $espacio_libre / ($cantidad_de_barras+1);

$pos_1 = ($interespaciado * 1);
$pos_2 = ($interespaciado * 2) + ($ancho * 1);
$pos_3 = ($interespaciado * 3) + ($ancho * 2);

// $pdf->Cell(0,262.5, "",0,0,'C');
$pdf->Cell(0,237.5, "",0,0,'C');
$pdf->Ln();

$calce_izq = 10;
$ancho_de_cell = (190 - ($calce_izq * 2)) / $cantidad_de_barras;

$pdf->Cell($calce_izq, 10, "", 0, 0, 'C');

if($_POST['mortaja'] != 'no hay mortaja')
{
	$horizontal_1 = $pos_1;
	$item_1 = "mortaja";
	$pdf->Code128($horizontal_1, $vertical, $_POST['mortaja'], $ancho, $alto);
	
	$pdf->Cell($ancho_de_cell, 10, $item_1, 0, 0, 'C');
}

if($_POST['serie'] != 'no hay serie')
{
	(isset($horizontal_1)) ? $horizontal_2 = $pos_2 : $horizontal_2 = $pos_1;
	$item_2 = "serie";
	$pdf->Code128($horizontal_2, $vertical, $_POST['serie'], $ancho, $alto);

	$pdf->Cell($ancho_de_cell, 10, $item_2, 0, 0, 'C');
}

if($_POST['libro'] != 'no hay libro')
{
	if(isset($horizontal_1) and isset($horizontal_2)) $horizontal_3 = $pos_3;
	if(isset($horizontal_1) and !isset($horizontal_2)) $horizontal_3 = $pos_2;
	if(!isset($horizontal_1) and isset($horizontal_2)) $horizontal_3 = $pos_2;
	if(!isset($horizontal_1) and !isset($horizontal_2)) $horizontal_3 = $pos_1;
	$item_3 = "libro";

	$pdf->Code128($horizontal_3, $vertical, $_POST['libro'], $ancho, $alto);

	$pdf->Cell($ancho_de_cell, 10, $item_3, 0, 0, 'C');
}

$espacio_util_para_cell = 190;
$ancho_de_cell = $espacio_util_para_cell / $cantidad_de_barras;

$pdf->Output();

?>
