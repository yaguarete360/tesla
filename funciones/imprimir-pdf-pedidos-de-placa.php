<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set('display_errors', 1);

    $_SESSION['impresion_tamano_fuente'] = 8;
    $_SESSION['margen_izquierdo'] = 5;
    $_SESSION['orientacion'] = 'P';

    $_SESSION['url_pdf'] = (isset($_POST['guardar_pdf'])) ? '../' : '';
    include $_SESSION['url_pdf']."../librerias/free-pdf/fpdf.php";

    $elementos_a_imprimir = $_SESSION['elementos_a_imprimir'];

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial','B',10);

            $this->Image($_SESSION['url_pdf'].'../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
            $this->Cell(0,20,"",0,0,'C');
            $this->Ln();
            
            $this->Cell(0, 10, iconv("UTF-8", "ISO-8859-1", 'Pedido de Placa'),0,0,'L',false);
            $this->Ln();

            $this->SetTextColor(255,255,255);
            $this->SetFillColor(216,162,98);
            $this->SetFont('Arial','B', $_SESSION['impresion_tamano_fuente']);
        }

        function Footer()
        {
            $this->SetFont('Arial','',7);
            $this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".ucwords($_SESSION['usuario_en_sesion']).' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
            $this->Ln();
        }

        function colores_cabecera_1()
        {
            $this->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']+2);
            $this->SetTextColor(255,255,255); // blanco
            $this->SetFillColor(216,162,98); // color oscuro
            $this->SetDrawColor(216,162,98);
        }

        function colores_cabecera_2()
        {
            $this->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']+1);
            $this->SetTextColor(0,0,0); // negro
            $this->SetFillColor(230,195,152); // color medio
            $this->SetDrawColor(216,162,98); // color oscuro
        }
        
        function colores_cabecera_3()
        {
            $this->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
            $this->SetTextColor(0,0,0); // negro
            $this->SetFillColor(245,231,214); // color claro
            $this->SetDrawColor(216,162,98); // color oscuro
        }
        
        function colores_normales()
        {
            $this->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
            $this->SetTextColor(0,0,0); // negro
            $this->SetFillColor(255,255,255); // color blanco
            $this->SetDrawColor(216,162,98); // color oscuro
        }
    }

    $precio_por_placa = $_POST['precio_por_placa'];
    $precio_por_letra = $_POST['precio_por_letra'];

    $inhs = $_SESSION['elementos_a_imprimir']['inhs'];
    $sitios = $_SESSION['elementos_a_imprimir']['sitios'];


    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on",30);
    $pdf->SetMargins($_SESSION['margen_izquierdo'], 11, 9);

    $relleno = false;

    $pdf->SetFont('Arial','', 12);
    $pdf->SetTextColor(0,0,0); // negro
    $pdf->SetFillColor(216,162,98); // color oscuro
    $pdf->SetDrawColor(216,162,98);
    
    $ancho_total = 200;
    $separador_medio = 10;
    $mitad_ancho = ($ancho_total / 2) - $separador_medio;
    $cuarto_ancho = $mitad_ancho / 2;

    foreach ($_POST['placas_a_pedir'] as $contrato => $si)
    {
        $pdf->SetLineWidth(1);
        $inh_campos = $inhs[$contrato];
        $pdf->AddPage('P');

        $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", 'Señor:'), 0, 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", 'MINERVA.'), 0, 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", 'Att. LUIS FERNANDO CALIGARIS GAONA'), 0, 0, 'L', false);
        $pdf->Ln();
        $pdf->SetFont('Arial','U', 12);
        $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", 'Presente:'), 0, 0, 'L', false);
        $pdf->Ln();
        $pdf->SetFont('Arial','', 12);
        $pdf->MultiCell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", 'Me dirijo a Uds. con el fin de solicitar el grabado de letras en la placa correspondiente al contrato UDS N° '.strtoupper($sitios[$inh_campos['sitio']]['contrato_uds'])), 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", ''), 'TLR', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", '+'), 'LR', 0, 'C', false);
        $pdf->Ln();

        $inhs_dif = 5;
        
        $tipo_de_placa = $_POST['tipo_de_placa'][$contrato];
        $precio_por_esta_placa = ($tipo_de_placa == 'nuevo') ? $precio_por_placa : 0;

        $cantidad_de_letras_total = $_POST['cantidad_de_letras'][$contrato];
        $precio_por_estas_letras = $precio_por_letra * $cantidad_de_letras_total;
        $total_a_pagar = $precio_por_estas_letras + $precio_por_esta_placa;

        foreach ($sitios[$inh_campos['sitio']] as $sitio_inh_numero => $sitio_inh_campos) if($sitio_inh_numero != 'contrato_uds')
        {
            $inhs_dif--;
            $es_inh_valido = ($sitio_inh_campos['nombre'] != 'sin datos' and $sitio_inh_campos['nombre'] != 'no aplicable' and $sitio_inh_campos['nombre'] != '');
            $string_a_mostrar = ($es_inh_valido) ? strtoupper($sitio_inh_campos['nombre']) : '';
            $string_a_mostrar = str_replace('ñ', 'Ñ', $string_a_mostrar);
            $string_a_mostrar = str_replace('á', 'Á', $string_a_mostrar);
            $string_a_mostrar = str_replace('é', 'É', $string_a_mostrar);
            $string_a_mostrar = str_replace('í', 'Í', $string_a_mostrar);
            $string_a_mostrar = str_replace('ó', 'Ó', $string_a_mostrar);
            $string_a_mostrar = str_replace('ú', 'Ú', $string_a_mostrar);
            $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", $string_a_mostrar), 'LR', 0, 'C', false);
            $pdf->Ln();

            $nacimiento_convertido = date('d m Y', strtotime($sitio_inh_campos['nacimiento']));
            $string_a_mostrar = ($es_inh_valido) ? '* '.$nacimiento_convertido : '';

            $pdf->Cell(($mitad_ancho + $separador_medio), 7, iconv("UTF-8", "ISO-8859-1", $string_a_mostrar), 'L', 0, 'C', false);

            $defuncion_convertido = date('d m Y', strtotime($sitio_inh_campos['defuncion']));
            $string_a_mostrar = ($es_inh_valido) ? '+ '.$defuncion_convertido : '';
            $pdf->Cell(($mitad_ancho + $separador_medio), 7, iconv("UTF-8", "ISO-8859-1", $string_a_mostrar), 'R', 0, 'C', false);

            $pdf->Ln();
        }

        $sitio_explotado = explode('-', $inh_campos['sitio']);
        $sitio_area = str_pad($sitio_explotado[2]+0, 1, '0', STR_PAD_LEFT);
        $sitio_sendero = str_pad($sitio_explotado[3]+0, 2, '0', STR_PAD_LEFT);
        $sitio_numero = str_pad($sitio_explotado[4]+0, 3, '0', STR_PAD_LEFT);
        $cantidad_de_letras_sitio = strlen($sitio_area) + strlen($sitio_sendero) + strlen($sitio_numero);
        $sitio_a_mostrar = $sitio_numero.' '.$sitio_sendero.' '.$sitio_area;

        for ($i=1; $i <= $inhs_dif; $i++)
        {
            $bordes = ($i == $inhs_dif) ? 'LRB' : 'LR';
            $string_sitio = ($i == $inhs_dif) ? $sitio_a_mostrar : '';
            $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", $string_sitio.'         '), $bordes, 0, 'R', false);
            $pdf->Ln();
        }
        
        $pdf->SetLineWidth(0.3);

        $pdf->Ln();
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", '  Observaciones: '), '', 0, 'L', false);
        $pdf->Cell($separador_medio, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'C', false);
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", '  Costos: '), '', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", ''), 'TLR', 0, 'L', false);
        $pdf->Cell($separador_medio, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'L', false);
        $pdf->Cell($cuarto_ancho, 7, iconv("UTF-8", "ISO-8859-1", '  Costo de la Placa: '), 'TL', 0, 'L', false);
        $pdf->Cell($cuarto_ancho, 7, iconv("UTF-8", "ISO-8859-1", number_format($precio_por_esta_placa).'  '), 'TR', 0, 'R', false);
        $pdf->Ln();
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", '  Recibi el: '), 'LR', 0, 'L', false);
        $pdf->Cell($separador_medio, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'L', false);
        $pdf->Cell($cuarto_ancho, 7, iconv("UTF-8", "ISO-8859-1", '  Cantidad de letras: '), 'L', 0, 'L', false);
        $pdf->Cell($cuarto_ancho, 7, iconv("UTF-8", "ISO-8859-1", number_format($cantidad_de_letras_total).'  '), 'R', 0, 'R', false);
        $pdf->Ln();
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", '  Llego al parque:'), 'LR', 0, 'L', false);
        $pdf->Cell($separador_medio, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'L', false);
        $pdf->Cell($cuarto_ancho, 7, iconv("UTF-8", "ISO-8859-1", '  Costo por letra: '), 'L', 0, 'L', false);
        $pdf->Cell($cuarto_ancho, 7, iconv("UTF-8", "ISO-8859-1", number_format($precio_por_letra).'  '), 'R', 0, 'R', false);
        $pdf->Ln();
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", '  Motivo: '), 'LRB', 0, 'L', false);
        $pdf->Cell($separador_medio, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'L', false);
        $pdf->Cell($cuarto_ancho, 7, iconv("UTF-8", "ISO-8859-1", '  Total a pagar: '), 'LB', 0, 'L', false);
        $pdf->Cell($cuarto_ancho, 7, iconv("UTF-8", "ISO-8859-1", number_format($total_a_pagar).'  '), 'RB', 0, 'R', false);
        $pdf->Ln();

        $pdf->Ln();
        $pdf->Cell($ancho_total, 7, iconv("UTF-8", "ISO-8859-1", 'A la espera de lo solicitado, me despido muy atentamente.'), '', 0, 'L', false);
        $pdf->Ln();

        $pdf->Ln();
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", 'Recepcionado Por:'), 'TLRB', 0, 'L', false);
        // $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'C', false);
        $pdf->Ln();
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", ''), 'LR', 0, 'L', false);
        // $pdf->Cell($separador_medio, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'L', false);
        // $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", ''), 'LRB', 0, 'L', false);
        $pdf->Cell($separador_medio, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'L', false);
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", ucwords($_SESSION['usuario_en_sesion'])), 'T', 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", 'Fecha estimativa a recibir: '), 'TLRB', 0, 'L', false);
        $pdf->Cell($separador_medio, 7, iconv("UTF-8", "ISO-8859-1", ''), '', 0, 'C', false);
        $pdf->Cell($mitad_ancho, 7, iconv("UTF-8", "ISO-8859-1", $_SESSION['cedula_en_sesion']), '', 0, 'L', false);
        $pdf->Ln();
    }
    
    $pdf->SetTextColor(0,0,0); // negro
    $pdf->Output();

?>