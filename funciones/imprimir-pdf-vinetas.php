<?php if (!isset($_SESSION)) {session_start();}
$url = '../../';
include "../librerias/free-pdf/fpdf.php";

include '../funciones/agregar-clase-pdf-codigo-de-barras.php';

include '../funciones/conectar-base-de-datos.php';

$camposS = "serie,feretro,medida";
$camposA = explode(",", $camposS);

$cantidad_campos = count($camposA);

if(isset($_POST['status_a_imprimir']))
{
	$i=1;
	$consulta = 
	'SELECT *
	FROM feretros
	WHERE borrado LIKE "no"
	AND LOWER(status) = "'.strtolower($_POST['status_a_imprimir']).'"
	';
	$query = $conexion->prepare($consulta);
	$query->execute();
	while($rows = $query->fetch(PDO::FETCH_ASSOC))
	{
		foreach ($camposA as $campo)
		{
			$feretros[$i][$campo] = $rows[$campo];
		}
		$i++;
	}
}
else
{
	for ($i=1; $i-1 < $_SESSION['cantidad_a_entregar']; $i++)
	{
		$consulta = 
		'SELECT *
		FROM feretros
		WHERE borrado LIKE "no"
		AND LOWER(serie) = "'.strtolower($_POST['serie_numero_'.$i]).'"
		';
		$query = $conexion->prepare($consulta);
		$query->execute();
		while($rows = $query->fetch(PDO::FETCH_ASSOC))
		{
			foreach ($camposA as $campo)
			{
				$feretros[$i][$campo] = $rows[$campo];
			}
		}
	}
}

$pdf = new FPDF_con_barra();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak("on",20);
$pdf->SetMargins(10, 11, 10.5);
$pdf->AddPage();

$lV=90;//largo de total de cada vineta
$lE=5;//espaciado entre vinetas
$pTit = 0.65;//porcentaje del dato en el total del $lV
$porPagina = 12;//cantidad de vinetas por pagina

$pdf->Ln();
$pdf->SetFont('Arial','',10);
$contador=1;
$cantidad = count($feretros);

for ($pos=1; $pos <= $cantidad; $pos++)
{
	//$pdf->SetFillColor(216,162,98);
	//$pdf->SetTextColor(255,255,255);
	//$pdf->SetDrawColor(216,162,98);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(0.4);
	$pdf->Cell($lV*(1-$pTit),7, iconv("UTF-8", "ISO-8859-1", "Serie: "),'LTB',0,'L',false);
	$pdf->Cell($lV*$pTit,7, iconv("UTF-8", "ISO-8859-1", strtoupper($feretros[$pos]['serie'])),'RTB',0,'L',false);
	$pdf->Cell($lE,7, "",0,0,'L');
	if($pos+1 <= $cantidad) $pdf->Cell($lV*(1-$pTit),7, iconv("UTF-8", "ISO-8859-1", "Serie: "),'LTB',0,'L',false);
	if($pos+1 <= $cantidad) $pdf->Cell($lV*$pTit,7, iconv("UTF-8", "ISO-8859-1", strtoupper($feretros[$pos+1]['serie'])),'RTB',0,'L',false);
	$pdf->Ln();
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(245,231,214);
	foreach ($camposA as $campo)
	{
		if($campo != "serie")
		{
			$bA = "";//borde de abajo para ultimo campo
			// if(array_search($campo, $camposA) == $cantidad_campos-1) $bA = "B";

			$pdf->Cell($lV*(1-$pTit),7, iconv("UTF-8", "ISO-8859-1",ucwords(str_replace("_", " ", $campo)).": "),'L'.$bA,0,'L',false);
			$pdf->Cell($lV*$pTit,7, iconv("UTF-8", "ISO-8859-1",$feretros[$pos][$campo]),'R'.$bA,0,'L',false);
			$pdf->Cell($lE, 7, "",0,0,'L');
			if($pos+1 <= $cantidad) $pdf->Cell($lV*(1-$pTit),7, iconv("UTF-8", "ISO-8859-1", ucwords(str_replace("_", " ", $campo)).": "),'L'.$bA,0,'L',false);
			if($pos+1 <= $cantidad) $pdf->Cell($lV*$pTit,7, iconv("UTF-8", "ISO-8859-1", $feretros[$pos+1][$campo]),'R'.$bA,0,'L',false);
			$pdf->Ln();
		}
	}

	$pdf->Cell($lV*(1-$pTit),7, iconv("UTF-8", "ISO-8859-1","Fecha Impresion: "),'L'.$bA,0,'L',false);
	$pdf->Cell($lV*$pTit,7, date('Y-m-d G:i:s'),'R'.$bA,0,'L',false);
	$pdf->Cell($lE, 7, "",0,0,'L');
	if($pos+1 <= $cantidad) $pdf->Cell($lV*(1-$pTit),7, iconv("UTF-8", "ISO-8859-1","Fecha Impresion: "),'L'.$bA,0,'L',false);
	if($pos+1 <= $cantidad) $pdf->Cell($lV*$pTit,7, date('Y-m-d G:i:s'),'R'.$bA,0,'L',false);
	$pdf->Ln();

	// $pdf->SetFillColor(245,231,214);
	$pdf->SetFillColor(255,255,255);
	$serie_a_crear_barra = $feretros[$pos]['serie'];
	$pos_X = $pdf->GetX();
	$pos_Y = $pdf->GetY();
	$pdf->Cell($lV, 14, "     ".$serie_a_crear_barra, "LBR", 0, 'L', true);
	// $pdf->Cell($lV, 14, "X:".$pos_X."    Y:".$pos_Y."    ".$serie_a_crear_barra, "LBR", 0, 'L', true);
	$pdf->SetFillColor(0,0,0);
	$pdf->Code128($pos_X+30, $pos_Y+2, $serie_a_crear_barra, $lV-35, 10);
	
	$pdf->Cell($lE, 7, "",0,0,'L');//espacio entre vinetas

	if($pos+1 <= $cantidad)
	{
		$pdf->SetFillColor(255,255,255);
		$serie_a_crear_barra = $feretros[$pos+1]['serie'];
		$pos_X = $pdf->GetX();
		$pos_Y = $pdf->GetY();
		$pdf->Cell($lV, 14, "     ".$serie_a_crear_barra, "LBR", 0, 'L', true);
		// $pdf->Cell($lV, 14, "X:".$pos_X."    Y:".$pos_Y."    ".$serie_a_crear_barra, "LBR", 0, 'L', true);
		$pdf->SetFillColor(0,0,0);
		$pdf->Code128($pos_X+30, $pos_Y+2, $serie_a_crear_barra, $lV-35, 10);
		$pdf->Ln();
	}

	if($pos % 2)
	{
		$pos++;
		if($pos == $porPagina) $pdf->AddPage();
		$pdf->Cell(0,2, "",0,0,'L');
		$pdf->Ln();
	}
}

$pdf->Output();
?>