<?php if (!isset($_SESSION)) {session_start();}

include "../librerias/free-pdf/fpdf.php";

include "../funciones/conectar-base-de-datos.php";

class PDF extends FPDF
{
  
  function Header()
  {
	$url = '../../';
	$this->SetFont('Arial','B',10);
	$this->Image('../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
	$this->Cell(0,20,"",0,0,'C');
	$this->Ln();
  }

  function Footer()
  {
	$url = '../../';
	$this->SetFont('Arial','',7);
	$this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".$_SESSION['usuario_en_sesion'].' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
	$this->Ln();
	$this->Image('../imagenes/iconos/logo-pie.png',95,$this->GetY(),20,0,'PNG');
  }

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 20);
$pdf->SetAutoPageBreak(0, 35);
$pdf->AddPage();
$pdf->SetFont('Arial','B',9);

if(isset($_POST['titulo'])) $pdf->Cell(51,5, $_POST['titulo']);

$pdf->Ln();
$totalCampos = 0;
$grupos = count($_SESSION['grupos']);

for($x = 0 ; $x < $grupos ; $x++)
{
	foreach($_SESSION['grupos'][$x] as $grupo)
	{
		$pdf->Cell(2,5, "" ,0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(15,5, $grupo,0,0,'L');
		$pdf->SetFont('Arial','',9);
		$pdf->Ln();
		$campos = count($_SESSION['filas'][$x]);
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell(5,5, "" ,0,0,'L');
		$pdf->Cell($_POST['ancho_1'],5, $_POST['columna_1'] ,0,0, $_POST['centrado_1']);
		$pdf->Cell($_POST['ancho_2'],5, $_POST['columna_2'] ,0,0, $_POST['centrado_2']);
		$pdf->Cell($_POST['ancho_3'],5, $_POST['columna_3'] ,0,0, $_POST['centrado_3']);
		$pdf->Cell($_POST['ancho_4'],5, $_POST['columna_4'] ,0,0, $_POST['centrado_4']);
		$pdf->Cell($_POST['ancho_5'],5, $_POST['columna_5'] ,0,0, $_POST['centrado_5']);
		$pdf->SetFont('Arial','',9);
		$pdf->Ln();

		foreach($_SESSION['filas'][$x] as $f=>$fila)
		{
			$pdf->Cell(5,5, "" ,0,0,'L');
			$pdf->Cell($_POST['ancho_1'],5, $fila[0] ,0,0, $_POST['centrado_1']);
			$pdf->Cell($_POST['ancho_2'],5, $fila[1] ,0,0, $_POST['centrado_2']);
			$pdf->Cell($_POST['ancho_3'],5, $fila[2] ,0,0, $_POST['centrado_3']);
			$pdf->Cell($_POST['ancho_4'],5, $fila[3] ,0,0, $_POST['centrado_4']);
			$pdf->Cell($_POST['ancho_5'],5, $fila[4] ,0,0, $_POST['centrado_5']);
			$pdf->Ln();
			$totalCampos++;
		}

	}
}
$pdf->Cell(10,10, 'Fin del listado de '.$x.' grupos y '.$totalCampos.' campos.');
$pdf->Output();

?>
