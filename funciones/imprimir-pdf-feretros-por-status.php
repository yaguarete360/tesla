<?php if (!isset($_SESSION)) {session_start();}
// $url = '../../';
include "../librerias/free-pdf/fpdf.php";
class PDF extends FPDF
{
	function Header()
	{
		$this->SetFont('Arial','B',10);
		$this->Image('../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
		$this->Cell(0,30,"",0,0,'C');
		$this->Ln();
		
		if(isset($_POST['fecha_desde']))
		{
			$this->Cell(0,5,"Entrada Entre: ".$_POST['fecha_desde']." y ".$_POST['fecha_hasta']." al Status: ".$_POST['status_seleccion'],0,0,'L');
		}
		else
		{
			$this->Cell(0,5,"Entrada: ".$_POST['fecha_seleccion']." al Status: ".$_POST['status_seleccion'],0,0,'L');
		}
			$this->Ln();
	}

	function Footer()
	{
		$this->SetFont('Arial','',7);
		$this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".$_SESSION['usuario_en_sesion'].' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
		//$this->Ln();
		//$this->Image('../imagenes/iconos/logo-pie.png',95,$this->GetY(),20,0,'PNG');
	}
}

include '../funciones/conectar-base-de-datos.php';

$entrega_doc_tip = "";
$entrega_doc_num = 0;
$recepcion_doc_tip = "";
$recepcion_doc_num = 0;

$i=0;
$consulta = 
'SELECT *
FROM organigrama
WHERE borrado LIKE "no"
';
$query = $conexion->prepare($consulta);
$query->execute();
while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
	if(strtolower($rows['organigrama']) == $_POST['entrega']) $entrega_doc_tip = $rows['documento_tipo'];
	if(strtolower($rows['organigrama']) == $_POST['entrega']) $entrega_doc_num = str_replace(".", "", $rows['documento_numero']);
	if(strtolower($rows['organigrama']) == $_POST['recepcion']) $recepcion_doc_tip = $rows['documento_tipo'];
	if(strtolower($rows['organigrama']) == $_POST['recepcion']) $recepcion_doc_num = str_replace(".", "", $rows['documento_numero']);
}

$ver_la_zona = explode(" ", strtolower($_POST['status_seleccion']));
$campo_entrada = "entrada_zona_".$ver_la_zona[2];

if(isset($_POST['fecha_desde']))
{
//	AND status = "'.$_POST['status_seleccion'].'"
	$consulta = 
	'SELECT *
	FROM feretros
	WHERE borrado = "no"
	AND '.$campo_entrada.' BETWEEN "'.$_POST['fecha_desde'].' 00:00:00" AND "'.$_POST['fecha_hasta'].' 23:59:59"
	ORDER BY '.$campo_entrada.' ASC
	';
}
else
{
	$consulta = 
	'SELECT *
	FROM feretros
	WHERE borrado = "no"
	AND '.$campo_entrada.' LIKE "'.$_POST['fecha_seleccion'].'%"
	ORDER BY '.$campo_entrada.' ASC
	';
}

$query = $conexion->prepare($consulta);
$query->execute();

$feretros = array();
while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
	$feretros[$i]['serie'] = $rows['serie'];
	$feretros[$i]['feretro'] = $rows['feretro'];
	$feretros[$i]['medida'] = $rows['medida'];
	$feretros[$i]['status'] = $rows['status'];
	$feretros[$i]['fabricante'] = $rows['fabricante'];
	$fecha_a_usar_explotada = explode(" ", $rows[$campo_entrada]);
	$feretros[$i]['entrada'] = $fecha_a_usar_explotada[0];
	$i++;
}

$iM=0;
if(strtolower($_POST['status_seleccion']) == "en zona 06 chapas")
{
	//$consultaMetalicas = 
	//	'SELECT *
	//	FROM agrupadores
	//	WHERE borrado = "no"
	//	AND agrupador LIKE "modelos de feretros"
	//	';
		
	$consultaMetalicas = 
		'SELECT *
		FROM agrupadores
		WHERE agrupador LIKE "modelos de feretros"';
		
	$queryM = $conexion->prepare($consultaMetalicas);
	$queryM->execute();
	while($rows = $queryM->fetch(PDO::FETCH_ASSOC))
	{
	    $metalicas[$rows['descripcion']] = $rows['dato_1'];
	}
}

if(strtolower($_POST['status_seleccion']) == "en zona 07 blondas")
{
	//$consultaBlondas1 = 
	//	'SELECT *
	//	FROM agrupadores
	//	WHERE borrado = "no"
	//	AND agrupador LIKE "modelos de feretros"
	//	';
		
	$consultaBlondas1 = 
		'SELECT *
		FROM agrupadores
		WHERE agrupador LIKE "modelos de feretros"';
		
	$queryB1 = $conexion->prepare($consultaBlondas1);
	$queryB1->execute();
	while($rowsB1 = $queryB1->fetch(PDO::FETCH_ASSOC))
	{
		$blondas_por_feretro[$rowsB1['descripcion']] = $rowsB1['dato_2'];
	}

	$consultaBlondas2 = 
		'SELECT *
		FROM agrupadores
		WHERE borrado = "no"
		AND agrupador LIKE "tipos de blondas"
		';
	$queryB2 = $conexion->prepare($consultaBlondas2);
	$queryB2->execute();
	while($rowsB2 = $queryB2->fetch(PDO::FETCH_ASSOC))
	{
		$tipos_de_blondas[$rowsB2['descripcion']] = $rowsB2['dato_1'];
	}
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak("on",30);
$pdf->SetMargins(10, 11, 10.5);
$pdf->AddPage();
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0,0,0);
if(!empty($feretros))
{
	$pdf->SetFillColor(216,162,98);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFont('Arial','',10);
	$aPM = 25;//ancho precio metalicas
	$aTB = 21;//ancho tipo blonda
	$aPB = 20;//ancho precio blonda
	$pdf->Cell(15,7, iconv("UTF-8", "ISO-8859-1", "Serie"),0,0,'L',true);
	$pdf->Cell(30,7, iconv("UTF-8", "ISO-8859-1", "Modelo"),0,0,'L',true);
	$pdf->Cell(12,7, iconv("UTF-8", "ISO-8859-1", "Medi."),0,0,'L',true);
	$pdf->Cell(60,7, iconv("UTF-8", "ISO-8859-1", "Status Actual"),0,0,'L',true);
	$pdf->Cell(33,7, iconv("UTF-8", "ISO-8859-1", "Fabricante"),0,0,'L',true);
	if(isset($_POST['fecha_desde'])) $pdf->Cell(20,7, iconv("UTF-8", "ISO-8859-1", "Entrada"),0,0,'L',true);
	if(strtolower($_POST['status_seleccion']) == "en zona 06 chapas") $pdf->Cell($aPM,7, iconv("UTF-8", "ISO-8859-1", "Precio Chapa"),0,0,'L',true);
	if(strtolower($_POST['status_seleccion']) == "en zona 07 blondas") $pdf->Cell($aTB,7, iconv("UTF-8", "ISO-8859-1", "Tipo Blonda"),0,0,'L',true);
	if(strtolower($_POST['status_seleccion']) == "en zona 07 blondas") $pdf->Cell($aPB,7, iconv("UTF-8", "ISO-8859-1", "Precio"),0,0,'R',true);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',10);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(245,231,214);
	if(!isset($rell)) $rell = false;
	$sumaTotal=0;
	foreach($feretros as $i => $feretro)
	{
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(15,7, iconv("UTF-8", "ISO-8859-1", strtoupper($feretro['serie'])),0,0,'L',$rell);
		$pdf->Cell(30,7, iconv("UTF-8", "ISO-8859-1", $feretro['feretro']),0,0,'L',$rell);
		$pdf->Cell(12,7, iconv("UTF-8", "ISO-8859-1", $feretro['medida']),0,0,'L',$rell);
		$pdf->Cell(60,7, iconv("UTF-8", "ISO-8859-1", $feretro['status']),0,0,'L',$rell);
		$pdf->Cell(33,7, iconv("UTF-8", "ISO-8859-1", $feretro['fabricante']),0,0,'L',$rell);
		if(isset($_POST['fecha_desde'])) $pdf->Cell(20,7, iconv("UTF-8", "ISO-8859-1", $feretro['entrada']),0,0,'L',$rell);

	//----------------------------METALICAS----------------------------------------------
		if(strtolower($_POST['status_seleccion']) == "en zona 06 chapas")
		{
			if(isset($metalicas[$feretro['feretro']]))
			{
				$precio_metalica_final = $metalicas[$feretro['feretro']];
				$sumaTotal = $sumaTotal + $precio_metalica_final;
			}
			else
			{
				$precio_metalica_final = "Falta el modelo";
			}
			
			if(ctype_digit($precio_metalica_final))
			{
				$precio_metalica_final = number_format($precio_metalica_final,0,"",".");
			}

			$pdf->Cell($aPM,7, iconv("UTF-8", "ISO-8859-1", $precio_metalica_final),0,0,'R',$rell);
//			$pdf->Cell($aPM,7, iconv("UTF-8", "ISO-8859-1", number_format($precio_metalica_final,0,"",".")),0,0,'R',$rell);
		}
	//----------------------------BLONDAS----------------------------------------------
		if(strtolower($_POST['status_seleccion']) == "en zona 07 blondas")
		{
			if(isset($blondas_por_feretro[$feretro['feretro']]))
			{
				if(isset($tipos_de_blondas[$blondas_por_feretro[$feretro['feretro']]]))
				{
					$blonda_del_feretro = $blondas_por_feretro[$feretro['feretro']];
					$precio_blonda_final = $tipos_de_blondas[$blondas_por_feretro[$feretro['feretro']]];
					$sumaTotal = $sumaTotal + $precio_blonda_final;
				}
				else
				{
					$precio_blonda_final = "Falta la blonda";
				}
			}
			else
			{
				$precio_blonda_final = "Falta el modelo";
			}

			if(ctype_digit($precio_blonda_final))
			{
				$precio_blonda_final = number_format($precio_blonda_final,0,"",".");
			}

			$pdf->Cell($aTB,7, iconv("UTF-8", "ISO-8859-1", $blonda_del_feretro),0,0,'L',$rell);
			$pdf->Cell($aPB,7, iconv("UTF-8", "ISO-8859-1", $precio_blonda_final),0,0,'R',$rell);
		}
	//--------------------------------------------------------------------------------
		($rell == true) ? $rell = false : $rell = true;
		$pdf->Ln();
		if($pdf->GetY() > 250) $pdf->AddPage();
	}
	if(strtolower($_POST['status_seleccion']) == "en zona 07 blondas" or strtolower($_POST['status_seleccion']) == "en zona 06 chapas")
	{
		$pdf->SetFillColor(216,162,98);
		$pdf->SetTextColor(255,255,255);
		$pdf->SetFont('Arial','',10);
		if(strtolower($_POST['status_seleccion']) == "en zona 07 blondas") $anchoComp = $aTB+$aPB;
		if(strtolower($_POST['status_seleccion']) == "en zona 06 chapas") $anchoComp = $aPM;
		$pdf->Cell(155+$anchoComp,7, iconv("UTF-8", "ISO-8859-1", "Total = ".number_format($sumaTotal,0,"",".")),0,0,'R',true);
	}
	else
	{
		$pdf->SetFillColor(216,162,98);
		$pdf->SetTextColor(255,255,255);
		$pdf->SetFont('Arial','',10);
		if(isset($_POST['fecha_desde']))
		{
			$pdf->Cell(175,7, iconv("UTF-8", "ISO-8859-1", "Total = ".number_format(count($feretros),0,"",".")),0,0,'R',true);
		}
		else
		{
			$pdf->Cell(150,7, iconv("UTF-8", "ISO-8859-1", "Total = ".number_format(count($feretros),0,"",".")),0,0,'R',true);
		}


	}

	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','',9);
	$largoFirmas = 70;
	$altoFila = 5;
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell($largoFirmas,$altoFila, "________________________",0,0,'L');
	$pdf->Cell($largoFirmas,$altoFila, "________________________",0,0,'L');
	$pdf->Ln();
	$pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", "Entrega"),0,0,'L');
	$pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", "Recepcion"),0,0,'L');
	$pdf->Ln();
	$pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", $_POST['entrega']),0,0,'L');
	$pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", $_POST['recepcion']),0,0,'L');
	$pdf->Ln();
	$pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", $entrega_doc_tip.": ".$entrega_doc_num),0,0,'L');//number_format(,0,"","."))
	$pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", $recepcion_doc_tip.": ".$recepcion_doc_num),0,0,'L');//number_format(,0,"","."))
	$pdf->Ln();
}
else
{
	$pdf->Cell(0,7, iconv("UTF-8", "ISO-8859-1", "No hay feretros con entrada al status seleccionado entre las fechas seleccionadas."),0,0,'L');
	$pdf->Ln();
}
$pdf->Output();
?>