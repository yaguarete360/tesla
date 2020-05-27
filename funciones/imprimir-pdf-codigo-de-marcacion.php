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
$pdf->SetFont('Arial', '', 12);
$pdf->Ln();

if(!isset($_SESSION['organigrama_seleccionado'])) $_SESSION['organigrama_seleccionado'] = '';
if(isset($_POST['organigrama_seleccionado'])) $_SESSION['organigrama_seleccionado'] = $_POST['organigrama_seleccionado'];
$consulta_organigrama = 'SELECT organigrama, codigo_marcacion FROM organigrama
    WHERE borrado LIKE "no"
        AND codigo_marcacion NOT LIKE "sin datos"
        AND codigo_marcacion NOT LIKE "no aplicable"
        AND codigo_marcacion NOT LIKE ""
        AND (finalizacion LIKE "0000-00-00%"
            OR finalizacion >= CURRENT_DATE)
		AND organigrama LIKE "'.$_SESSION['organigrama_seleccionado'].'"
    ORDER BY organigrama ASC LIMIT 1';
$query_organigrama = $conexion->prepare($consulta_organigrama);
$query_organigrama->execute();
while($rows_organigrama = $query_organigrama->fetch(PDO::FETCH_ASSOC)) $codigo_a_imprimir = $rows_organigrama['codigo_marcacion'];

$pos_y = 10; // v
$pos_x = 10; // ->

$ancho_de_la_tarjeta = 86;
$alto_de_la_tarjeta = 54;

$ancho_del_codigo = ($ancho_de_la_tarjeta * 0.80);
$alto_del_codigo = ($alto_de_la_tarjeta * 0.25);

$pos_y_codigo = $pos_y + (($ancho_de_la_tarjeta - $ancho_del_codigo) / 2);
$pos_x_codigo = $pos_x + $alto_de_la_tarjeta - $alto_del_codigo - ($alto_de_la_tarjeta * 0.01);

$pos_y_logo = $pos_y + $ancho_de_la_tarjeta * 0.01;
$pos_x_logo = $pos_x + $alto_de_la_tarjeta * 0.01;

$pos_y_texto = $pos_x + $alto_de_la_tarjeta * 0.55;

$pdf->SetXY($pos_x, $pos_y);
$pdf->Cell($ancho_de_la_tarjeta, $alto_de_la_tarjeta, ucwords($_SESSION['organigrama_seleccionado']), 1, 0, 'C', 0);

$pdf->SetXY($pos_x, $pos_y_texto);
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell($ancho_de_la_tarjeta, 3, 'La marcacion de entrada/salida debe ser hecha por la persona indicada en la tarjeta. Queda prohibido prestar, entregar, pedir, duplicar, fotocopiar u otras actividades que permitan realizar la marcacion de un funcionario por otro.', 0, 'C');

$pdf->Image('../imagenes/iconos/logo-pdf.png', $pos_x_logo, $pos_y_logo, '', $alto_de_la_tarjeta * 0.45);

$pdf->Code128($pos_y_codigo, $pos_x_codigo, $codigo_a_imprimir, $ancho_del_codigo, $alto_del_codigo);

$pdf->Output();

?>
