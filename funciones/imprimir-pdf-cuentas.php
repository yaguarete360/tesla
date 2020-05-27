<?php if (!isset($_SESSION)) {session_start();}

include "../librerias/free-pdf/fpdf.php";

include '../archivos/datos/'.$_POST['tabla_a_procesar'].'.php';

$_SESSION['tabla_a_procesar'] = "auxiliares_accionistas";
$_SESSION['filtro_campo'] = "accionista";
$_SESSION['filtro_desde'] = "A";
$_SESSION['filtro_hasta'] = "Z";


class PDF extends FPDF
{
  function Header()
  {
	$url = '../../';
	$this->SetFont('Arial','B',10);
	$this->Image("../imagenes/iconos/logo-cabecera.png",10,7,100,0,'PNG');
	$this->Cell(0,10,"",0,0,'C');
	$this->Ln();
	if(isset($_POST['titulo'])) $this->Cell(51,5, $_POST['titulo']);
	$this->Ln();
  }
  function Footer()
  {
	$url = '../../';
	$this->Ln();
	$this->SetFont('Arial','',7);
	$this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".$_SESSION['usuario_en_sesion'].' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	$this->Ln();
	$this->Image("../imagenes/iconos/logo-pie.png",95,$this->GetY(),20,0,'PNG');
  }
}
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 5);
$pdf->SetAutoPageBreak(10, 35);
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);

$pdf->Ln();

// $pdf->Cell(25,5, "Resonsable: ",0,0,'L');
// $pdf->Cell(45,5, ucwords($responsable),0,0,'L');
// $pdf->Cell(25,5, "- Interno: ",0,0,'L');
// $pdf->Cell(20,5, $interno,0,0,'L');

$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(85,61,36);
$pdf->Ln();

foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
{		
	if($campo_atributo['visible'] == "si")
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
		
		$pdf->Cell($campo_atributo['largo'],5, $encabezado,0,0,'L',true);
	}		
}

$pdf->SetTextColor(0,0,0);

$pdf->Cell(0,5, "",0,0,'C',true);

if(!isset($rell)) $rell = false;

$pdf->SetFillColor(249,240,226);

$pdf->Ln();

$_SESSION['cantidad_registros'] = 0;

$pdf->SetFont('Arial','',8);

include "../funciones/conectar-base-de-datos.php";

if($_POST['excluir_bajas'] == true)
{

	$listado = 'SELECT * 				
	FROM '.$_POST['tabla_a_procesar'].' 
	WHERE borrado LIKE "0000-00-00 00:00:00"
	AND baja_fecha LIKE "0000-00-00 00:00:00"
	AND '.$_POST['campo_a_procesar'].'
	BETWEEN "'.$_POST['filtro_desde'].'"
	AND "'.$_POST['filtro_hasta'].'"
	AND fecha LIKE "'.$_POST['fecha'].'%"
	ORDER BY '.$_POST['campo_a_procesar'].', fecha
	ASC
	LIMIT 2000';
}
else
{
	$listado = 'SELECT * 				
	FROM '.$_POST['tabla_a_procesar'].' 
	WHERE borrado LIKE "0000-00-00 00:00:00"
	AND '.$_POST['campo_a_procesar'].'
	BETWEEN "'.$_POST['filtro_desde'].'"
	AND "'.$_POST['filtro_hasta'].'"
	AND fecha LIKE "'.$_POST['fecha'].'%"
	ORDER BY '.$_POST['campo_a_procesar'].', fecha
	ASC
	LIMIT 2000';

}

$total_entra = 0;
$total_sale  = 0;
$total_debe  = 0;
$total_haber = 0;
$total_saldo = 0;

$query = $conexion->prepare($listado);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
	foreach($_SESSION['campos'] as $campo_nombre => $campo_atributo)
	{	
		if($campo_atributo['visible'] == "si")
		{
			if($campo_atributo['formato'] == "texto" or 
			$campo_atributo['formato'] == "fecha" or 
			$campo_atributo['formato'] == "asistido")
			{
				$texto = utf8_decode($rows[$campo_nombre]);
				if(strlen($texto) > 110)
				{
					$pdf->Cell($campo_atributo['largo'],5, ucwords(substr($texto,0,110))."...",0,0,'L',$rell);				
				}
				else
				{
					$pdf->Cell($campo_atributo['largo'],5, ucwords($texto),0,0,'L',$rell);
				}

			} 

			if($campo_atributo['formato'] == "numero-texto" or
			$campo_atributo['formato'] == "asistido-derecha" or  
			$campo_atributo['formato'] == "vista")
			{
				$pdf->Cell($campo_atributo['largo'],5, $rows[$campo_nombre],0,0,'R',$rell);
			}									

			if($campo_atributo['formato'] == "numero")
			{											
				$pdf->Cell($campo_atributo['largo'],5, number_format($rows[$campo_nombre],$campo_atributo['decimales'],',','.'),0,0,'R',$rell);
			}
		}


	}

	$total_entra = $total_entra + $rows['entra'];
	$total_sale  = $total_sale  + $rows['sale'];
	$total_debe  = $total_debe  + $rows['debe'];
	$total_haber = $total_haber + $rows['haber'];
	$total_saldo = $total_saldo + $rows['debe'] - $rows['haber'];

	$pdf->Cell(17,5, number_format($total_saldo,0,',','.'),0,0,'R',$rell);

	$pdf->Ln();

	$_SESSION['cantidad_registros']++; 		
	
	($rell == true) ? $rell = false : $rell = true;

}

$pdf->Ln();	

$pdf->Cell(10,5, "",0,0,'R',$rell);
$pdf->Cell(16,5, "Saldos: ",0,0,'R',$rell);
$pdf->Cell(44,5, "",0,0,'R',$rell);
$pdf->Cell(19,5, "",0,0,'R',$rell);
$pdf->Cell(13,5, "",0,0,'R',$rell);
$pdf->Cell(23,5, "",0,0,'R',$rell);
$pdf->Cell( 9,5, number_format($total_entra,0,',','.'),0,0,'R',$rell);
$pdf->Cell( 9,5, number_format($total_sale,0,',','.'),0,0,'R',$rell);
$pdf->Cell(17,5, number_format($total_debe,0,',','.'),0,0,'R',$rell);
$pdf->Cell(17,5, number_format($total_haber,0,',','.'),0,0,'R',$rell);
$pdf->Cell(17,5, number_format($total_saldo,0,',','.'),0,0,'R',$rell);

$pdf->Ln();	

($rell == true) ? $rell = false : $rell = true;
$pdf->SetFont('Arial','',7);
$pdf->Cell(0,5, "Fin del listado de ".$_SESSION['cantidad_registros']." registros",0,0,'L',$rell);
$pdf->Ln();
$pdf->Output();

?>
