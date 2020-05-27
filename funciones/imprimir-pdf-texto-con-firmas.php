<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set('display_errors', 1);
    $_SESSION['url_pdf'] = (isset($_POST['guardar_pdf'])) ? '../' : '';

    include $_SESSION['url_pdf']."../librerias/free-pdf/fpdf.php";
    include $_SESSION['url_pdf']."../funciones/conectar-base-de-datos.php";

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial','B',10);

            $this->Image($_SESSION['url_pdf'].'../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
            $this->Cell(0,20,"",0,0,'C');
            $this->Ln();
            if(isset($_POST['titulo_del_listado']) and !empty($_POST['titulo_del_listado']))
            {
                $this->Cell(0, 10, iconv("UTF-8", "ISO-8859-1", $_POST['titulo_del_listado']),0,0,'L',false);
                $this->Ln();
            }
        }

        function Footer()
        {
            $this->SetFont('Arial','',7);
            $this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".iconv("UTF-8", "ISO-8859-1", $_SESSION['usuario_en_sesion']).' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
            $this->Ln();
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on",30);
    $pdf->SetMargins(20, 11, 9);
    $pdf->AddPage('P');

    $pdf->SetFont('Arial','',9);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(245,231,214);
    $pdf->SetDrawColor(216,162,98);

    $pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", $_POST['texto_a_imprimir']), 0, 'J', false);
    $pdf->Ln();

    if(isset($_POST['firmas']))
    {
        $consulta_documentos_numeros = 'SELECT organigrama, documento_tipo, documento_numero FROM organigrama
        WHERE borrado LIKE "no"
            AND organigrama IN ("'.implode('", "', $_POST['firmas']).'")';
        $query_documentos_numeros = $conexion->prepare($consulta_documentos_numeros);
        $query_documentos_numeros->execute();
        while($rows_d_n = $query_documentos_numeros->fetch(PDO::FETCH_ASSOC))
        {
            $firmantes_datos[$rows_d_n['organigrama']]['documento_tipo'] = $rows_d_n['documento_tipo'];
            $firmantes_datos[$rows_d_n['organigrama']]['documento_numero'] = $rows_d_n['documento_numero'];
        }

        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',9);
        $largoFirmas = 70;
        $altoFila = 5;

        $pdf->Ln();
        $pdf->Ln();
        foreach ($_POST['firmas'] as $firma_num => $firma_firmante)
        {
            $pdf->Cell($largoFirmas,$altoFila, "________________________",0,0,'L');
        }
        $pdf->Ln();

        foreach ($_POST['firmas'] as $firma_num => $firma_firmante)
        {
            $pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", ucwords($firma_firmante)),0,0,'L');
        }
        $pdf->Ln();

        foreach ($_POST['firmas'] as $firma_num => $firma_firmante)
        {
            $pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", ucwords($firmantes_datos[$firma_firmante]['documento_tipo'])),0,0,'L');
        }
        $pdf->Ln();

        foreach ($_POST['firmas'] as $firma_num => $firma_firmante)
        {
            $dato_a_mostrar = (is_numeric($firmantes_datos[$firma_firmante]['documento_numero'])) ? number_format($firmantes_datos[$firma_firmante]['documento_numero']) : iconv("UTF-8", "ISO-8859-1", ucwords($firmantes_datos[$firma_firmante]['documento_numero']));
            $pdf->Cell($largoFirmas,$altoFila, $dato_a_mostrar,0,0,'L');
        }
        $pdf->Ln();
    }

    $pdf->Output();

?>