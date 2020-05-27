<?php if (!isset($_SESSION)) {session_start();}

    include "../librerias/free-pdf-1-8-1/fpdf.php";

    $planilla_a_imprimir = $_SESSION['planilla_a_imprimir']; //array con todos los datos
    $tipo_de_impresion = $_SESSION['datos_extra_para_imprimir']['tipo_de_impresion'];
    $tipos_de_documentos = $_SESSION['datos_extra_para_imprimir']['tipos_de_documentos'];

    $alto_de_fila = 5;

    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(false);
    $pdf->SetMargins(15, 11, 5);
    $pdf->AddPage('P');

    $pdf->SetFont('Arial','', 9);
    $pdf->SetTextColor(0,0,0);

    $meses_s = ',Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Setiembre,Octubre,Noviembre,Diciembre';
    $meses_a = explode(',', $meses_s);

    switch ($tipo_de_impresion)
    {
        case 'estado de cuenta':
        case 'recibo interno cancelacion del sueldo a cobrar':
        case 'recibo interno cancelacion del saldo corregido':
        case 'recibo interno anticipos':
        case 'recibo interno aguinaldo':
        case 'recibo interno viaticos':
        case 'recibo interno vacaciones':
        case 'recibo interno liquidacion por renuncia':
        case 'recibo interno liquidacion por despido injustificado':
        case 'recibo interno liquidacion por despido justificado por abandono de trabajo':
        case 'recibo interno liquidacion por despido en periodo de prueba':
            $planilla_partes = explode('-', $_SESSION['datos_extra_para_imprimir']['planilla']);
            $tipo_especifico = str_replace('recibo interno ', '', $tipo_de_impresion);
            
            $ancho_total = $pdf->GetPageWidth() - 5;
            $anchos['descripcion'] = $ancho_total * 0.64;
            $anchos['obligacion'] = $ancho_total * 0.12;
            $anchos['derecho'] = $ancho_total * 0.12;
            $ancho_tabla = $anchos['descripcion'] + $anchos['obligacion'] + $anchos['derecho'];

            $cuentas = array_keys($planilla_a_imprimir);
            // // $cuentas_doble = array_merge($cuentas, $cuentas); // recibos con duplicado
            $cuentas_doble = $cuentas;
            sort($cuentas_doble);

            $i = 0;
            foreach ($cuentas_doble as $pos => $cuenta)
            {
                $movimientos = $planilla_a_imprimir[$cuenta];
                if($tipo_especifico == 'estado de cuenta' or isset($movimientos[$tipo_especifico.'-'.$tipos_de_documentos[$tipo_de_impresion]]))
                {
                    if($i % 2) $pdf->SetY(152);
                    $documento_descripcion = ($tipo_especifico == 'estado de cuenta') ? 'Estado de Cuenta' : 'Estado de Cuenta y Recibo por '.$tipo_especifico;
                    $pdf->Cell($ancho_tabla, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", $documento_descripcion), "", 0, 'R', false);
                    $pdf->Ln();
                    $este_mes = $meses_a[ltrim($planilla_partes[2], '0')];
                    $pdf->Cell($ancho_tabla, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Correspondiente Al Mes de '.$este_mes), "", 0, 'R', false);
                    $pdf->Ln();
                    $primer_dia = $planilla_partes[1].'-'.$planilla_partes[2].'-01';
                    $ultimo_dia = date('Y-m-t', strtotime($planilla_partes[1].'-'.$planilla_partes[2].'-01'));
                    $pdf->Cell($ancho_tabla, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Periodo De Pago: Del '.$primer_dia.' Al '.$ultimo_dia), "", 0, 'R', false);
                    $pdf->Ln();

                    $pdf->SetFont('Arial','', 15);
                    $pdf->Cell($ancho_tabla, $alto_de_fila+5, iconv("UTF-8", "ISO-8859-1", ucwords($cuenta)), "B", 0, 'C', false);
                    $pdf->Ln();
                    
                    $pdf->SetFont('Arial', '', 9);

                    $pdf->Cell($anchos['descripcion'], $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Descripcion'), "B", 0, 'L', false);
                    $pdf->Cell($anchos['obligacion'],$alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Obligacion'), "B", 0, 'L', false);
                    $pdf->Cell($anchos['derecho'], $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Derecho'), "B", 0, 'L', false);

                    $pdf->Ln();
                    $suma_del_funcionario = 0;

                    foreach ($movimientos as $descripcion => $monto)
                    {
                        $descripcion_explotada = explode('-', $descripcion);
                        $pdf->Cell($anchos['descripcion'], $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords($descripcion_explotada[0])), "", 0, 'L', false);
                        
                        if($descripcion_explotada[1] == 'obligacion')
                        {
                            $pdf->Cell($anchos['obligacion'], $alto_de_fila, number_format($monto), "", 0, 'R', false);
                            $pdf->Cell($anchos['derecho'], $alto_de_fila, '', "", 0, 'R', false);
                            $suma_del_funcionario += $monto;
                        }
                        else if($descripcion_explotada[1] == 'derecho')
                        {
                            $pdf->Cell($anchos['obligacion'], $alto_de_fila, '', "", 0, 'R', false);
                            $pdf->Cell($anchos['derecho'], $alto_de_fila, number_format($monto), "", 0, 'R', false);
                            $suma_del_funcionario -= $monto;
                        }
                        $pdf->Ln();
                    }
                    
                    $pdf->SetLineWidth(0.4);
                    $pdf->Cell($anchos['descripcion'], $alto_de_fila, 'Saldo A La Fecha', "T", 0, 'L', false);
                    if($suma_del_funcionario >= 0)
                    {
                        $obligacion_hacia_funcionario = number_format($suma_del_funcionario);
                        $derecho_hacia_funcionario = '';
                    }
                    else
                    {
                        $obligacion_hacia_funcionario = '';
                        $derecho_hacia_funcionario = number_format($suma_del_funcionario * -1);
                    }
                    $pdf->Cell($anchos['obligacion'], $alto_de_fila, $obligacion_hacia_funcionario, "T", 0, 'R', false);
                    $pdf->Cell($anchos['derecho'], $alto_de_fila, $derecho_hacia_funcionario, "T", 0, 'R', false);
                    $pdf->Ln();
                    $pdf->SetLineWidth(0.2);
                    $pdf->Cell($ancho_tabla, 1, '', "T", 0, 'L', false);
                    $pdf->Ln();
                    // $pdf->Cell($ancho_tabla, 1, '', "T", 0, 'L', false);
                    // $pdf->Ln();
                    
                    // $pdf->Cell($ancho_tabla, $alto_de_fila-2.5, '', "", 0, 'R', false);
                    // $pdf->Ln();
                    
                    if($tipo_especifico != 'estado de cuenta')
                    {
                        $pdf->SetFont('Arial','', 15);
                        $pdf->Cell($ancho_tabla, $alto_de_fila+2.5, 'Recibo de '.ucwords($tipo_especifico), "BT", 0, 'C', false);
                        $pdf->Ln();
                        
                        $pdf->SetFont('Arial', '', 9);
    
                        $pdf->Cell($anchos['descripcion'], $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Descripcion'), "B", 0, 'L', false);
                        $pdf->Cell($anchos['obligacion'],$alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Obligacion'), "B", 0, 'L', false);
                        $pdf->Cell($anchos['derecho'], $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Derecho'), "B", 0, 'L', false);
                        $pdf->Ln();
    
                        $pdf->SetLineWidth(0.4);
                        $pdf->Cell($anchos['descripcion'], $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords($tipo_especifico)), "B", 0, 'L', false);
                        $monto_a_recibir = $movimientos[$tipo_especifico.'-'.$tipos_de_documentos[$tipo_de_impresion]];
    
                        switch ($tipos_de_documentos[$tipo_de_impresion])
                        {
                            case 'derecho':
                                $pdf->Cell($anchos['obligacion'],$alto_de_fila, '', "B", 0, 'R', false);
                                $pdf->Cell($anchos['derecho'], $alto_de_fila, number_format($monto_a_recibir), "B", 0, 'R', false);
                            break;
    
                            case 'obligacion':
                                $pdf->Cell($anchos['obligacion'],$alto_de_fila, number_format($monto_a_recibir), "B", 0, 'R', false);
                                $pdf->Cell($anchos['derecho'], $alto_de_fila, '', "B", 0, 'R', false);
                            break;
                        }
                        $pdf->Ln();
    
                        $pdf->SetLineWidth(0.2);
                        $montoAUsar = number_format($monto_a_recibir);
                        include "../funciones/poner-montos-en-letras.php";
                        $ancho_del_guion = $pdf->GetStringWidth('-');
                        $ancho_del_texto = $pdf->GetStringWidth('Son Guaranies: '.ucwords($montoFinalLetras).'.');
                        $cantidad_de_guiones = ($ancho_tabla - $ancho_del_texto) / $ancho_del_guion;
                        $pdf->Cell(50, $alto_de_fila, 'Son Guaranies: '.ucwords($montoFinalLetras).'.'.str_repeat('-', $cantidad_de_guiones), "", 0, 'L', false);
                        $pdf->Ln();
    
                        $pdf->Cell($ancho_tabla, 5, '', "", 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Arial','', 8);
                        $pdf->Cell($ancho_total*0.4, $alto_de_fila-2, 'Fecha de Impresion: '.date('Y-m-d G:i:s'), "", 0, 'R', false);
                        $pdf->Cell($ancho_total*0.3, $alto_de_fila-2, 'Recibi Conforme:_____________________', "", 0, 'R', false);
                        $pdf->Ln();
                    }
                    else
                    {
                        $pdf->Ln();

                        $pdf->SetLineWidth(0.2);
                        $monto_a_recibir = ($suma_del_funcionario >= 0) ? $obligacion_hacia_funcionario : $derecho_hacia_funcionario;
                        $montoAUsar = $monto_a_recibir;
                        include "../funciones/poner-montos-en-letras.php";
                        $ancho_del_guion = $pdf->GetStringWidth('-');
                        $ancho_del_texto = $pdf->GetStringWidth('Son Guaranies: '.ucwords($montoFinalLetras).'.');
                        $cantidad_de_guiones = ($ancho_tabla - $ancho_del_texto) / $ancho_del_guion;
                        $pdf->Cell(50, $alto_de_fila, 'Son Guaranies: '.ucwords($montoFinalLetras).'.'.str_repeat('-', $cantidad_de_guiones), "", 0, 'L', false);
                        $pdf->Ln();
                        $pdf->SetFont('Arial','', 8);
                        $pdf->Cell(0, $alto_de_fila-2, 'Fecha de Impresion: '.date('Y-m-d G:i:s'), "", 0, 'C', false);
                        $pdf->Ln();
                        $pdf->Cell(0, $alto_de_fila, 'Impreso por: '.ucwords($_SESSION['usuario_en_sesion']), "", 0, 'C', false);
                        $pdf->Ln();
                        
                        
                    }
                    $pdf->Cell($ancho_tabla, 2, '', "B", 0, 'L', false);
                    $pdf->Ln();
                    $pdf->Cell($ancho_tabla, 1, '', "B", 0, 'L', false);
                    $pdf->Ln();
                    $pdf->SetFont('Arial','', 9);
                    if($i % 2 and $cuenta != end($cuentas_doble)) $pdf->AddPage();
                    $i++;
                }
            }
        break;
        
        case 'ministerio de trabajo':
            $pdf->SetFont('Arial','', 7);
            $planilla_partes = explode('-', $_SESSION['datos_extra_para_imprimir']['planilla']);
            $tipo_especifico = str_replace('recibo interno ', '', $tipo_de_impresion);
            
            $ancho_total = 189; // ancho dentro del borde verde
            $ancho_por_campo = $ancho_total / 12;

            $cuentas = array_keys($planilla_a_imprimir);
            $cuentas_doble = array_merge($cuentas, $cuentas);
            sort($cuentas_doble);

            $i = 0;
            foreach ($cuentas_doble as $pos => $cuenta)
            {
                $movimientos = $planilla_a_imprimir[$cuenta];
                $posicion_y_comienzo = ($i % 2) ? 170 : 30;
                $pdf->SetY($posicion_y_comienzo);
                $alineacion_izquierda = 17;
                $pdf->SetX($alineacion_izquierda);
                $alto_del_logo = 25;
                $ancho_del_logo = 66;
                $pdf->Image('../imagenes/iconos/logo ministerio de trabajo.png', null, null, $ancho_del_logo, $alto_del_logo);
                $pdf->SetY($posicion_y_comienzo);
                $pdf->SetX(90);
                $pdf->SetFont('Arial','', 17);
                $pdf->Cell(75, $alto_de_fila+5, 'Liquidacion de Salario', "", 0, 'L', false);
                $pdf->SetFont('Arial','', 20);
                $pdf->Cell(0, $alto_de_fila+5, 'L.S.', "", 0, 'L', false);
                $pdf->Ln();
                $pdf->SetY($pdf->GetY() - ($alto_de_fila*0.5));
                $pdf->SetX(100);
                $pdf->SetFont('Arial','', 6);
                $pdf->Cell(0, $alto_de_fila, '(Art. 236 de Cod. Del Trabajo)', "", 0, 'L', false);
                $pdf->Ln();
                $pdf->SetY($posicion_y_comienzo + $alto_del_logo);
                $pdf->SetFont('Arial','U', 8);
                $pdf->Cell(16, $alto_de_fila, 'Empleador:', "", 0, 'L', false);
                $pdf->SetFont('Arial','', 8);
                $pdf->Cell(90, $alto_de_fila, 'Parque Serenidad S.R.L.', "", 0, 'L', false);
                $pdf->SetFont('Arial','U', 8);
                $pdf->Cell(23, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Nº Patronal MJT:'), "", 0, 'L', false);
                $pdf->SetFont('Arial','', 8);
                $pdf->Cell(50, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", $_SESSION['datos_extra_para_imprimir']['numero_patronal_mjt'][$cuenta]), "", 0, 'L', false);
                $pdf->Ln();
                $pdf->SetFont('Arial','U', 8);
                $pdf->Cell(47, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Apellidos y Nombres del Trabajador:'), "", 0, 'L', false);
                $pdf->SetFont('Arial','', 8);
                $pdf->Cell(0, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", ucwords($cuenta)), "", 0, 'L', false);
                $pdf->Ln();
                $pdf->SetFont('Arial','U', 8);
                $primer_dia = $planilla_partes[1].'-'.$planilla_partes[2].'-01';
                $ultimo_dia = date('Y-m-t', strtotime($planilla_partes[1].'-'.$planilla_partes[2].'-01'));
                $pdf->Cell(23, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Periodo De Pago:'), "", 0, 'L', false);
                $pdf->SetFont('Arial','', 8);
                $pdf->Cell(0, $alto_de_fila, iconv("UTF-8", "ISO-8859-1", 'Del '.$primer_dia.' Al '.$ultimo_dia), "", 0, 'L', false);
                $pdf->Ln();

                $pdf->SetFont('Arial','', 7);
                $posicion_y_cabecera = $pdf->GetY();
                $campo_numero = 0;
                foreach ($movimientos as $movimiento_campo => $movimiento_valor)
                {
                    $pdf->SetY($posicion_y_cabecera);
                    $pdf->SetX($alineacion_izquierda + ($campo_numero * $ancho_por_campo));
                    $pdf->Cell($ancho_por_campo, $alto_de_fila*2, '', "BTLR", 0, 'L', false);
                    $pdf->SetY($posicion_y_cabecera);
                    $pdf->SetX($alineacion_izquierda + ($campo_numero * $ancho_por_campo));
                    $pdf->Multicell($ancho_por_campo, $alto_de_fila, str_replace('_', ' ', $movimiento_campo), "", 'L', false);
                    $campo_numero++;
                }
                
                $campo_numero = 0;
                foreach ($movimientos as $movimiento_campo => $movimiento_valor)
                {
                    $pdf->SetX($alineacion_izquierda + ($campo_numero * $ancho_por_campo));
                    $pdf->Cell($ancho_por_campo, $alto_de_fila*2, number_format($movimiento_valor), "BTLR", 0, 'R', false);
                    $campo_numero++;
                }
                $pdf->Ln();
                $pdf->SetFont('Arial','', 8);
                $quinto_del_ancho = $ancho_total / 5;
                $pdf->SetY($posicion_y_comienzo + 82);
                // $pdf->SetY(105);
                $pdf->SetX($alineacion_izquierda + $quinto_del_ancho);
                $pdf->Cell($quinto_del_ancho, $alto_de_fila, 'fecha', "T", 0, 'C', false);
                $pdf->Cell($quinto_del_ancho, $alto_de_fila, '', "", 0, 'C', false);
                $pdf->Cell($quinto_del_ancho, $alto_de_fila, 'firma', "T", 0, 'C', false);

                $pdf->SetFont('Arial','', 6);
                // $pdf->SetY(125);
                $pdf->SetY($posicion_y_comienzo + 87.5);
                $pdf->Cell(0, $alto_de_fila, 'Impreso por: '.ucwords($_SESSION['usuario_en_sesion']).' el '.date('Y-m-d').' a las '.date('G:i:s'), "", 0, 'C', false);

                if($i % 2 and $cuenta != end($cuentas_doble)) $pdf->AddPage();
                $i++;
            }
        break;
        
        default:
        break;
    }

    $pdf->Output();

?>