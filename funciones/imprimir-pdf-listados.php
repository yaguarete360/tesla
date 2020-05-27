<?php if (!isset($_SESSION)) {session_start();}
    
    ini_set('display_errors', 1);

    $_SESSION['url_pdf'] = (isset($_POST['guardar_pdf'])) ? '../' : '';
    include $_SESSION['url_pdf']."../librerias/free-pdf/fpdf.php";

    $elementos_a_imprimir = $_SESSION['elementos_a_imprimir'];

    // ESTRUCTURA PARA ARRAYS A IMPRIMIR -------------------vvvvvvvvv-------------------------------------------------------------------------

    // $_SESSION['cabecera_a_imprimir'];
    // $cabecera_a_imprimir['id'] = 123123;
    // $cabecera_a_imprimir['descripcion'] = 'descripcion';
    // $cabecera_a_imprimir['cuenta'] = 'cuenta';

    // $_SESSION['elementos_a_imprimir'];
    // $elementos_a_imprimir[0]['id'] = 'id';
    // $elementos_a_imprimir[0]['descripcion'] = 'descripcion';
    // $elementos_a_imprimir[0]['cuenta'] = 'cuenta';

    // $elementos_a_imprimir[1]['id'] = 123132;
    // $elementos_a_imprimir[1]['descripcion'] = 'cancelacion';
    // $elementos_a_imprimir[1]['cuenta'] = 'jorge';

    // $elementos_a_imprimir[2]['id'] = 123133;
    // $elementos_a_imprimir[2]['descripcion'] = 'cancelacion';
    // $elementos_a_imprimir[2]['cuenta'] = 'david';
    
    // ESTRUCTURA PARA ARRAYS A IMPRIMIR -------------------^^^^^^^^-------------------------------------------------------------------------

    if(!isset($_SESSION['titulo_del_listado'])) $_SESSION['titulo_del_listado'] = '';
    if(isset($_POST['titulo_del_listado']) and !empty($_POST['titulo_del_listado'])) $_SESSION['titulo_del_listado'] = $_POST['titulo_del_listado'];

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

    $_SESSION['controlar_rellenos'] = "";
    if(isset($_POST['controlar_rellenos']) and $_POST['controlar_rellenos'] == 'si') $_SESSION['controlar_rellenos'] = $_POST['controlar_rellenos'];

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

    $_SESSION['ancho_de_celdas'] = array();
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
            if(isset($_SESSION['titulo_del_listado']) and !empty($_SESSION['titulo_del_listado']))
            {
                $this->Cell(0, 10, iconv("UTF-8", "ISO-8859-1", $_SESSION['titulo_del_listado']),0,0,'L',false);
                $this->Ln();
            }

            $this->SetTextColor(255,255,255);
            $this->SetFillColor(216,162,98);
            $this->SetFont('Arial','B', $_SESSION['impresion_tamano_fuente']);

            if($this->PageNo() != 1)
            {
                foreach ($_SESSION['elementos_a_imprimir'][0] as $columna_nombre => $columna_dato)
                {
                    if($columna_nombre != $_SESSION['grupo_separador'])
                    {
                        $this->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'], iconv("UTF-8", "ISO-8859-1", ucwords($columna_dato)),0,0,'L',true);
                    }
                }
                $this->Ln();
            }
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


    // $_SESSION['cabecera_a_imprimir']['tipo'] = 'texto';
    // $_SESSION['cabecera_a_imprimir']['datos'][] = 'SEÑORES:';
    // $_SESSION['cabecera_a_imprimir']['datos'][] = $asociacion;
    // $_SESSION['cabecera_a_imprimir']['datos'][] = 'PRESENTE';
    // $_SESSION['cabecera_a_imprimir']['datos'][] = 'Solicitamos por este medio a vuestra asociacion..';
    // $_SESSION['cabecera_a_imprimir']['datos'][] = 'Descuento Correspondiente al mes de '.$mes_a_descontar;
    
    // $_SESSION['cabecera_a_imprimir']['tipo'] = 'campos';
    // $_SESSION['cabecera_a_imprimir']['datos']['proveedor'] = 2816;
    // $_SESSION['cabecera_a_imprimir']['datos']['ruc'] = '80093342-7';
    // $_SESSION['cabecera_a_imprimir']['datos']['ruc'] = '80093342-7';
    // $_SESSION['cabecera_a_imprimir']['datos']['ruc'] = '80093342-7';
    // $_SESSION['cabecera_a_imprimir']['datos']['ruc'] = '80093342-7';
    // $_SESSION['cabecera_a_imprimir']['datos']['ruc'] = '80093342-7';
    // $_SESSION['cabecera_a_imprimir']['datos']['ruc'] = '80093342-7';
    // $_SESSION['cabecera_a_imprimir']['datos']['ruc'] = '80093342-7';

    if(isset($_POST['graficos_a_imprimir_antes']))
    {
        foreach ($_POST['graficos_a_imprimir_antes'] as $grafico_a_imprimir_num => $grafico_a_imprimir)
        {
            $encodedImg = explode(',',$grafico_a_imprimir)[1];
            $decodedImg = base64_decode($encodedImg);

            $imagen = imageCreateFromString($decodedImg);

            if(!$imagen) die('Base64 value is not a valid image');

            $imagen_ruta = '../imagenes/temporales/grafico-temporal.png';
            imagepng($imagen, $imagen_ruta, 1);
            $pdf->Ln();
            $pdf->Image($imagen_ruta, null, null, 200, 0, 'PNG');
        }
    }

    if(!isset($_SESSION['cabecera_a_imprimir'])) $_SESSION['cabecera_a_imprimir'] = array();
    if(isset($_POST['tiene_cabecera']) and isset($_SESSION['cabecera_a_imprimir']) and !empty($_SESSION['cabecera_a_imprimir']))
    {
        $cabecera_a_imprimir = $_SESSION['cabecera_a_imprimir']['datos'];
        switch ($_SESSION['cabecera_a_imprimir']['tipo'])
        {
            case 'texto':
                foreach ($cabecera_a_imprimir as $cabecera_pos => $cabecera_texto)
                {
                    $pdf->MultiCell(0, $_SESSION['alto_fila'] * 1.3, iconv("UTF-8", "ISO-8859-1", ucwords($cabecera_texto).': '), 0, 'L', false);
                }
            break;

            case 'campos':
                $ancho_de_la_pagina = ($pdf->GetPageWidth()) - ($_SESSION['margen_izquierdo'] * 2);
                $contador_cabecera = 0;
                foreach ($cabecera_a_imprimir as $cabecera_campo => $cabecera_valor)
                {
                    $bordes_cabecera = '';
                    $ancho_del_campo_nombre = $pdf->GetStringWidth($cabecera_campo) * 1.05;
                    $ancho_del_campo_valor = $pdf->GetStringWidth($cabecera_valor) * 1.05;
                    $ancho_total_del_campo = $ancho_del_campo_nombre + $ancho_del_campo_valor;
                    $posicion_final_del_cell = $pdf->GetX() + $ancho_total_del_campo;
                    if($posicion_final_del_cell > $ancho_de_la_pagina) $pdf->Ln();

                    $pdf->Cell($ancho_del_campo_nombre, $_SESSION['alto_fila'] * 1.3, iconv("UTF-8", "ISO-8859-1", ucwords($cabecera_campo).': '), $bordes_cabecera, 0, 'L', true);
                    $pdf->Cell($ancho_del_campo_valor, $_SESSION['alto_fila'] * 1.3, iconv("UTF-8", "ISO-8859-1", ucwords($cabecera_valor)), $bordes_cabecera, 0, 'L', false);
                    
                    $contador_cabecera++;
                }
                $pdf->Ln();
            break;
        }
        $pdf->Ln();
    }

    if($pdf->PageNo() == 1)
    {
        $pdf->SetTextColor(255,255,255);
        $pdf->SetFillColor(216,162,98);
        $pdf->SetFont('Arial','B', $_SESSION['impresion_tamano_fuente']);
        foreach ($_SESSION['elementos_a_imprimir'] as $fila => $columnas)
        {
            foreach ($columnas as $columna_nombre => $columna_dato)
            {
                if($columna_nombre != $_SESSION['grupo_separador'])
                {
                    $pdf->Cell($_SESSION['ancho_de_celdas'][$columna_nombre], $_SESSION['alto_fila'], iconv("UTF-8", "ISO-8859-1", ucwords($columna_dato)),0,0,'L',true);
                }
            }
            break;
        }
        $pdf->Ln();
    }

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
            if(!empty($_SESSION['controlar_rellenos']))
            {
                $relleno = $_SESSION['rellenos'][$fila];
            }

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

            if(empty($_SESSION['controlar_rellenos']))
            {
                ($relleno == false) ? $relleno = true : $relleno = false;
            }
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

    if(isset($_POST['graficos_a_imprimir_despues']))
    {
        foreach ($_POST['graficos_a_imprimir_despues'] as $grafico_a_imprimir)
        {
            $encodedImg = explode(',',$grafico_a_imprimir)[1];
            $decodedImg = base64_decode($encodedImg);

            $imagen = imageCreateFromString($decodedImg);

            if(!$imagen) die('Base64 value is not a valid image');

            $imagen_ruta = '../imagenes/temporales/grafico-temporal.png';
            imagepng($imagen, $imagen_ruta, 1);
            $pdf->Ln();
            $pdf->Image($imagen_ruta, null, null, 200, 0, 'PNG');
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