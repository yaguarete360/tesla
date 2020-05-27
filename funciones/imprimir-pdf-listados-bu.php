<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set('display_errors', 1);

    $_SESSION['url_pdf'] = (isset($_POST['guardar_pdf'])) ? '../' : '';
    include $_SESSION['url_pdf']."../librerias/free-pdf/fpdf.php";

    $elementos_a_imprimir = $_SESSION['elementos_a_imprimir'];

    if(!isset($_SESSION['margen_izquierdo'])) $_SESSION['margen_izquierdo'] = 3;
    if(isset($_POST['margen_izquierdo']) and !empty($_POST['margen_izquierdo'])) $_SESSION['margen_izquierdo'] = $_POST['margen_izquierdo'] + 0;

    if(!isset($_SESSION['orientacion'])) $_SESSION['orientacion'] = 'P';
    if(isset($_POST['orientacion']) and !empty($_POST['orientacion'])) $_SESSION['orientacion'] = $_POST['orientacion'];

    if(!isset($_SESSION['alto_fila'])) $_SESSION['alto_fila'] = 7;
    if(isset($_POST['impresion_alto_fila']) and !empty($_POST['impresion_alto_fila'])) $_SESSION['alto_fila'] = $_POST['impresion_alto_fila'];

    if(!isset($_SESSION['impresion_tamano_fuente'])) $_SESSION['impresion_tamano_fuente'] = 8;
    if(isset($_POST['impresion_tamano_fuente']) and !empty($_POST['impresion_tamano_fuente'])) $_SESSION['impresion_tamano_fuente'] = $_POST['impresion_tamano_fuente']; 

    $_SESSION['grupo_separador'] = "";
    if(isset($_POST['grupo_separador']) and !empty($_POST['grupo_separador'])) $_SESSION['grupo_separador'] = $_POST['grupo_separador'];

    $_SESSION['columnas_a_sumar'] = "";
    $_SESSION['sumas_totales'] = "";
    if(isset($_POST['columnas_a_sumar']) and !empty($_POST['columnas_a_sumar']))
    {
        foreach ($_POST['columnas_a_sumar'] as $pos => $columna)
        {
            $_SESSION['columnas_a_sumar'][$columna] = 0;
            $_SESSION['sumas_totales'][$columna] = 0;
        }
    }

    $_SESSION['mostrar_acumulados'] = "";
    if(isset($_POST['mostrar_acumulados']) and !empty($_POST['mostrar_acumulados']))
    {
        foreach ($_POST['mostrar_acumulados'] as $pos => $columna) $_SESSION['mostrar_acumulados'][$columna] = 0;
    }

    $_SESSION['columnas_a_contar'] = "";
    if(isset($_POST['columnas_a_contar']) and !empty($_POST['columnas_a_contar']))
    {
        foreach ($_POST['columnas_a_contar'] as $pos => $columna) $_SESSION['columnas_a_contar'][$columna] = 0;
    }

    $_SESSION['suma_final_general'] = "";
    if(isset($_POST['suma_final_general']) and !empty($_POST['suma_final_general']))
    {
        $_SESSION['suma_final_general'] = 'si';
    }

    $_SESSION['ancho_de_celdas'] = "";
    $diferencia_en_tamano = 0.175; // diferencia de anchos por caracter entre tamanos de fuentes (en promedio) // 
    $variacion_de_fuente = $_SESSION['impresion_tamano_fuente'] - 8;
    foreach ($elementos_a_imprimir as $fila => $columnas)
    {
        foreach ($columnas as $columna_nombre => $columna_dato)
        {
            if($columna_nombre != $_SESSION['grupo_separador'])
            {
                // ($fila == 0) ? $por_caracter = 1.9 : $por_caracter = 1.8;// ancho por caracter en promedio // al ser la cabecera en negrita los caracteres se vuelven mas anchos //
                $por_caracter = 2;// ancho por caracter en promedio // al ser la cabecera en negrita los caracteres se vuelven mas anchos //
                // if(strlen($columna_dato) < 10) $por_caracter = $por_caracter - 0.025;
                if(strlen($columna_dato) >  9) $por_caracter = $por_caracter - 0.45;
                if(strlen($columna_dato) > 14) $por_caracter = $por_caracter - 0.01;
                if(strlen($columna_dato) > 19) $por_caracter = $por_caracter - 0.01;
                if(strlen($columna_dato) > 24) $por_caracter = $por_caracter - 0.01;
                if(strlen($columna_dato) > 29) $por_caracter = $por_caracter - 0.01;
                if(strlen($columna_dato) > 34) $por_caracter = $por_caracter - 0.01;
                if(strlen($columna_dato) > 39) $por_caracter = $por_caracter - 0.01;
                if(strlen($columna_dato) > 44) $por_caracter = $por_caracter - 0.01;
                
                // (strlen($columna_dato) < 10) ? $por_caracter = 2 : $por_caracter = 1.55;
                // if(isset($_SESSION['columnas_a_sumar'][$columna_nombre])) $por_caracter = 1.75 : $por_caracter = 1.55;
                if(isset($_SESSION['columnas_a_sumar'][$columna_nombre])) $por_caracter = $por_caracter * 1.05;
                if(!isset($_SESSION['ancho_de_celdas'][$columna_nombre])) $_SESSION['ancho_de_celdas'][$columna_nombre] = 0;

                $ancho_de_celda = (strlen($columna_dato) * ($por_caracter + ($variacion_de_fuente * $diferencia_en_tamano)));
                if($ancho_de_celda > $_SESSION['ancho_de_celdas'][$columna_nombre]) $_SESSION['ancho_de_celdas'][$columna_nombre] = $ancho_de_celda;
            }
        }
    }

    $ancho_total = 0;
    foreach ($_SESSION['ancho_de_celdas'] as $columna_nombre => $ancho) $ancho_total = $ancho_total + $ancho;

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

            $this->SetTextColor(255,255,255);
            $this->SetFillColor(216,162,98);
            $this->SetFont('Arial','B', $_SESSION['impresion_tamano_fuente']);

            foreach ($_SESSION['elementos_a_imprimir'] as $fila => $columnas)
            {
                foreach ($columnas as $columna_nombre => $columna_dato)
                {
                    if($columna_nombre != $_SESSION['grupo_separador'])
                    {
                        $this->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'], iconv("UTF-8", "ISO-8859-1", ucwords($columna_dato)),0,0,'L',true);
                    }
                }
                break;
            }
            $this->Ln();
        }

        function Footer()
        {
            $this->SetFont('Arial','',7);
            $this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".$_SESSION['usuario_en_sesion'].' Pagina '.$this->PageNo().'/{nb}',0,0,'C');
            $this->Ln();
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on",30);
    $pdf->SetMargins($_SESSION['margen_izquierdo'], 11, 9);
    $pdf->AddPage($_SESSION['orientacion']);

    $relleno = false;

    $pdf->SetFont('Arial','', $_SESSION['impresion_tamano_fuente']);
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(245,231,214);
    $pdf->SetDrawColor(216,162,98);
    if(!empty($_SESSION['grupo_separador']))
    {
        $grupo_anterior = $elementos_a_imprimir[1][$_SESSION['grupo_separador']];
        $pdf->Cell($ancho_total, $_SESSION['alto_fila'] * 1.3, iconv("UTF-8", "ISO-8859-1", strtoupper($grupo_anterior)), "BT", 0, 'L', true);
        $pdf->Ln();
        $cantidad_de_grupos = 1;
    }

    $cantidad_de_elementos_a_imprimir = count($elementos_a_imprimir) - 1;
    foreach ($elementos_a_imprimir as $fila => $columnas)
    {
        if($fila != 0)
        {
            if(!empty($_SESSION['grupo_separador']) and $grupo_anterior != $columnas[$_SESSION['grupo_separador']])
            {
                $cantidad_de_grupos++;
                if(!empty($_SESSION['columnas_a_contar']))
                {
                    foreach ($columnas as $columna_nombre => $columna_dato)
                    {
                        if($columna_nombre != $_SESSION['grupo_separador'])
                        {
                            $contador_a_imprimir = isset($_SESSION['columnas_a_contar'][$columna_nombre]) ? number_format($_SESSION['columnas_a_contar'][$columna_nombre]) : "";
                            $pdf->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'] * 1.2, $contador_a_imprimir, "BT", 0, 'R', true);

                            if(isset($_SESSION['columnas_a_contar'][$columna_nombre])) $_SESSION['columnas_a_contar'][$columna_nombre] = 0;
                        }
                    }
                    $pdf->Ln();
                }

                if(!empty($_SESSION['columnas_a_sumar']))
                {
                    foreach ($columnas as $columna_nombre => $columna_dato)
                    {
                        if($columna_nombre != $_SESSION['grupo_separador'])
                        {
                            $suma_a_imprimir = isset($_SESSION['columnas_a_sumar'][$columna_nombre]) ? number_format($_SESSION['columnas_a_sumar'][$columna_nombre]) : "";
                            $pdf->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'] * 1.2, $suma_a_imprimir, "BT", 0, 'R', true);
                            
                            if(isset($_SESSION['columnas_a_sumar'][$columna_nombre])) $_SESSION['columnas_a_sumar'][$columna_nombre] = 0;
                        }
                    }
                    $pdf->Ln();

                    if(!empty($_SESSION['mostrar_acumulados']))
                    {
                        foreach ($columnas as $columna_nombre => $columna_dato)
                        {
                            if($columna_nombre != $_SESSION['grupo_separador'])
                            {
                                $suma_acum_a_imprimir = isset($_SESSION['sumas_totales'][$columna_nombre]) ? number_format($_SESSION['sumas_totales'][$columna_nombre]) : "";
                                $pdf->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'] * 1.2, $suma_acum_a_imprimir, "BT", 0, 'R', true);
                            }
                        }
                        $pdf->Ln();
                    }
                }


                $pdf->Cell($ancho_total, $_SESSION['alto_fila'] * 1.3, iconv("UTF-8", "ISO-8859-1", strtoupper($columnas[$_SESSION['grupo_separador']])), "BT", 0, 'L', true);
                $pdf->Ln();
                $relleno = false;
            }

            foreach ($columnas as $columna_nombre => $columna_dato)
            {
                if($columna_nombre != $_SESSION['grupo_separador'])
                {
                    if(is_numeric($columna_dato))
                    {
                        $decimales = (strpos($columna_dato, '.') !== false) ? 2 : 0;
                        $dato_a_mostrar = number_format($columna_dato, $decimales);
                        $alineacion_del_dato = "R";
                    }
                    else
                    {
                        $dato_a_mostrar = iconv("UTF-8", "ISO-8859-1", ucwords($columna_dato));
                        $alineacion_del_dato = "L";
                    }
                    
                    $pdf->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'], $dato_a_mostrar, 0, 0, $alineacion_del_dato, $relleno);
                    
                    if(isset($_SESSION['columnas_a_contar'][$columna_nombre])) $_SESSION['columnas_a_contar'][$columna_nombre]++;

                    if(isset($_SESSION['columnas_a_sumar'][$columna_nombre]))
                    {
                        $_SESSION['columnas_a_sumar'][$columna_nombre] = $_SESSION['columnas_a_sumar'][$columna_nombre] + $columna_dato;
                        $_SESSION['sumas_totales'][$columna_nombre] = $_SESSION['sumas_totales'][$columna_nombre] + $columna_dato;
                    }
                    
                }
            }
            $pdf->Ln();


            if(!empty($_SESSION['grupo_separador'])) $grupo_anterior = $columnas[$_SESSION['grupo_separador']];

            ($relleno == false) ? $relleno = true : $relleno = false;
        }
        
        if($fila == $cantidad_de_elementos_a_imprimir)
        {
            if(!empty($_SESSION['columnas_a_contar']))
            {
                foreach ($columnas as $columna_nombre => $columna_dato)
                {
                    if($columna_nombre != $_SESSION['grupo_separador'])
                    {
                        $contador_a_imprimir = isset($_SESSION['columnas_a_contar'][$columna_nombre]) ? number_format($_SESSION['columnas_a_contar'][$columna_nombre]) : "";
                        $pdf->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'] * 1.2, $contador_a_imprimir, "BT", 0, 'R', true);
                    }
                }
                $pdf->Ln();
            }

            if(!empty($_SESSION['columnas_a_sumar']))
            {
                if(!empty($_SESSION['grupo_separador']))
                {
                    foreach ($columnas as $columna_nombre => $columna_dato)
                    {
                        if($columna_nombre != $_SESSION['grupo_separador'])
                        {
                            $suma_a_imprimir = isset($_SESSION['columnas_a_sumar'][$columna_nombre]) ? number_format($_SESSION['columnas_a_sumar'][$columna_nombre]) : "";
                            $pdf->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'] * 1.2, $suma_a_imprimir, "BT", 0, 'R', true);
                        }
                    }
                    $pdf->Ln();
                }

                if(!isset($cantidad_de_grupos) or $cantidad_de_grupos > 1)
                {
                    foreach ($columnas as $columna_nombre => $columna_dato)
                    {
                        if($columna_nombre != $_SESSION['grupo_separador'])
                        {
                            $suma_acum_a_imprimir = isset($_SESSION['sumas_totales'][$columna_nombre]) ? number_format($_SESSION['sumas_totales'][$columna_nombre]) : "";
                            $pdf->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'] * 1.2, $suma_acum_a_imprimir, "BT", 0, 'R', true);
                        }
                    }
                    $pdf->Ln();
                }

                if(!empty($_SESSION['suma_final_general']) and count($_SESSION['columnas_a_sumar']) > 1)
                {
                    $suma_total_general = 0;

                    if(!empty($_SESSION['grupo_separador']))
                    {
                        $columnas_copia = $columnas;
                        unset($columnas_copia[$_SESSION['grupo_separador']]);
                        $columnas_a_sacar_primero = $columnas_copia;
                    }
                    else
                    {
                        $columnas_a_sacar_primero = $columnas;
                    }

                    $columnas_keys = array_keys($columnas_a_sacar_primero);
                    $primera_columna = $columnas_keys[0];
                    $ultima_columna = end($columnas_keys);

                    foreach ($columnas as $columna_nombre => $columna_dato)
                    {
                        if($columna_nombre != $_SESSION['grupo_separador'])
                        {
                            if(isset($_SESSION['columnas_a_sumar'][$columna_nombre])) $suma_total_general+= $_SESSION['columnas_a_sumar'][$columna_nombre];
                            $suma_a_imprimir = ($columna_nombre == $ultima_columna) ? number_format($suma_total_general) : '';
                            if($columna_nombre == $primera_columna) $suma_a_imprimir = 'Total';
                            $alineacion_del_dato = ($suma_a_imprimir == 'Total') ? 'L' : 'R';
                            $pdf->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'] * 1.2, $suma_a_imprimir, "BT", 0, $alineacion_del_dato, true);
                        }
                    }
                    $pdf->Ln();
                }
            }
        }
    }

    if(isset($_POST['con_firmas']))
    {
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','',9);
        $largoFirmas = 70;
        $altoFila = 5;

        $pdf->Ln();
        $pdf->Ln();
        foreach ($_POST['con_firmas'] as $firma_num => $firma_firmante)
        {
            $pdf->Cell($largoFirmas,$altoFila, "________________________",0,0,'L');
        }
        $pdf->Ln();

        if(isset($_POST['con_firmas'][0]['titulo']))
        {
            foreach ($_POST['con_firmas'] as $firma_num => $firma_firmante)
            {
                $pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", ucwords($firma_firmante['titulo'])),0,0,'L');
            }
            $pdf->Ln();
        }

        foreach ($_POST['con_firmas'] as $firma_num => $firma_firmante)
        {
            $pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", ucwords($firma_firmante['nombre'])),0,0,'L');
        }
        $pdf->Ln();

        foreach ($_POST['con_firmas'] as $firma_num => $firma_firmante)
        {
            $pdf->Cell($largoFirmas,$altoFila, iconv("UTF-8", "ISO-8859-1", $firma_firmante['documento']),0,0,'L');
        }
        $pdf->Ln();
    }
    
    if(isset($_POST['guardar_pdf']) or isset($guardar_pdf))
    {
        $guardar_pdf_final = (isset($guardar_pdf)) ? $guardar_pdf : $_POST['guardar_pdf'];
        $pdf->Output($guardar_pdf_final);
    }
    else
    {
        $pdf->Output();
    }

?>