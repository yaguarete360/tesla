<?php if (!isset($_SESSION)) {session_start();}
    
    include '../../librerias/free-pdf/fpdf.php';

    class PDF extends FPDF
    {
        function SetDash($black=null, $white=null)
        {
            if($black!==null)
                $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
            else
                $s='[] 0 d';
            $this->_out($s);
        }

        function Header()
        {
            $this->SetFont('Arial','B',10);
            $this->SetTextColor(0,0,0); // TEXTO NEGRO
            $this->SetFillColor(255,255,255); // FONDO BLANCO
            $this->SetDrawColor(216,162,98); // LINEAS OSCURAS
            $this->SetLineWidth(0.5);

            $this->Image('../../imagenes/iconos/logo-cabecera.png',10,7,100,0,'PNG');
            $this->Cell(0,20,"",0,0,'C');
            
            $this->SetXY(112, 9);
            $this->Cell(83, 5, iconv("UTF-8", "ISO-8859-1", 'PARQUE SERENIDAD S.R.L.'),'',0,'L',false); // LTR
            $this->SetXY(112, 14);
            $nombres_de_dias = array('domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado');
            $fecha_elegida_dia = date('w', strtotime($_SESSION['fecha_elegida']));
            $this->Cell(83, 5, iconv("UTF-8", "ISO-8859-1", 'Caja Del Dia: '.$_SESSION['fecha_elegida'].'('.strtoupper($nombres_de_dias[$fecha_elegida_dia]).')'),'',0,'L',false); //LR
            $this->SetXY(112, 19);
            if(isset($_SESSION['cotizacion']['dolar eeuu'])) $this->Cell(83, 5, iconv("UTF-8", "ISO-8859-1", 'Cotizacion Dolar EEUU: '.number_format($_SESSION['cotizacion']['dolar eeuu']['cotizacion'], $_SESSION['cotizacion']['dolar eeuu']['decimales'])),'',0,'L',false); // LBR
            $this->SetY(25);
            $this->ln();

            $this->SetFont('Arial','B', 8);
            $this->SetLineWidth(0.2);
        }

        function Footer()
        {
            $this->SetXY(5, -10); // el X = margen derecho para centrar
            $this->SetTextColor(0,0,0); // TEXTO NEGRO
            $this->SetFont('Arial','',7);
            $this->Cell(0,10,"Impreso el ".date('Y-m-d G:i:s')." por ".ucwords($_SESSION['usuario_en_sesion']).' Pagina '.$this->PageNo().'/{nb}', 0, 0, 'C');
        }

        function colores_cabecera()
        {
            $this->SetTextColor(255,255,255); // TEXTO BLANCO
            $this->SetFillColor(216,162,98); // FONDO OSCURO
            $this->SetDrawColor(216,162,98); // LINEAS OSCURAS
            $_SESSION['relleno'] = true;
        }

        function colores_cabecera_clara()
        {
            $this->SetTextColor(0,0,0); // TEXTO NEGRO
            $this->SetFillColor(245,231,214); // FONDO CLARO
            $this->SetDrawColor(216,162,98); // LINEAS OSCURAS
            $_SESSION['relleno'] = true;
        }

        function colores_normales()
        {
            $this->SetTextColor(0,0,0); // TEXTO NEGRO
            $this->SetFillColor(255,255,255); // FONDO BLANCO
            $this->SetDrawColor(245,231,214); // LINEAS CLARAS
            $_SESSION['relleno'] = false;
        }
    }


    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak("on", 1);
    $pdf->SetMargins(20, 10, 5);
    // $pdf->AddPage('P', 'Letter');
    $pdf->AddPage('P', array(216, 330));

    $pdf->SetFont('Arial', '', 8);
    
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(245,231,214);
    $pdf->SetDrawColor(216,162,98);

    if(isset($_SESSION['elementos_a_imprimir']))
    {
        $nombre_del_archivo = $_SESSION['nombre_del_archivo'];
        $fecha_elegida = $_SESSION['fecha_elegida'];

        $cuentas_a_cobrar = $_SESSION['elementos_a_imprimir']['cuentas_a_cobrar'];
        $cuentas_a_pagar = $_SESSION['elementos_a_imprimir']['cuentas_a_pagar'];
        $cuentas_bancarias = $_SESSION['elementos_a_imprimir']['cuentas_bancarias'];
        $cuentas_bancarias_comisiones = $_SESSION['elementos_a_imprimir']['cuentas_bancarias_comisiones'];
        $a_depositar = $_SESSION['elementos_a_imprimir']['a_depositar'];
        $cobranzas_por_centro = $_SESSION['elementos_a_imprimir']['cobranzas_por_centro'];
        $cobranzas_por_instrumento = $_SESSION['elementos_a_imprimir']['cobranzas_por_instrumento'];
        $campos_por_factura_de_formularios = $_SESSION['elementos_a_imprimir']['campos_por_factura_de_formularios'];
        $cobranzas_por_factura = $_SESSION['elementos_a_imprimir']['cobranzas_por_factura'];
        $cobranzas_por_factura_formularios = $_SESSION['elementos_a_imprimir']['cobranzas_por_factura_formularios'];
        $campos_por_banco_por_documento_tipo_numero = $_SESSION['elementos_a_imprimir']['campos_por_banco_por_documento_tipo_numero'];
        $pagos_por_banco_por_documento_tipo_numero = $_SESSION['elementos_a_imprimir']['pagos_por_banco_por_documento_tipo_numero'];
        $procesadoras_debitos_automaticos = $_SESSION['elementos_a_imprimir']['procesadoras_debitos_automaticos'];
        $debitos_automaticos = $_SESSION['elementos_a_imprimir']['debitos_automaticos'];
    }

    $altos['XS'] = 4;
    $altos['S'] = 5;
    $altos['M'] = 6;
    $altos['ML'] = 7;
    $altos['L'] = 8;
    $altos['XL'] = 9;

    $borde = 'TB';

    $ancho_total = 180;
    $alto_filas = 4;

    // ------------------------------------------------------------------------------------------------------------------------
    $ancho_campo = $ancho_total / (count($cuentas_a_cobrar[array_keys($cuentas_a_cobrar)[0]])+1);
    // $pdf->colores_normales();
    $pdf->colores_cabecera();
    // $pdf->ln();
    $pdf->Cell($ancho_total,$alto_filas, 'Cuentas A Cobrar',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();

    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Centro',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Inicial',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Ventas',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Cobranzas',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Final',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();

    $totales = array();
    $totales['inicial'] = 0;
    $totales['ventas'] = 0;
    $totales['cobranzas'] = 0;
    $totales['final'] = 0;
    $pdf->colores_normales();
    foreach ($cuentas_a_cobrar as $centro => $valores)
    {
        $pdf->Cell($ancho_campo,$alto_filas,strtoupper($centro),0,0,'L', $_SESSION['relleno']);
        foreach ($valores as $valor_campo => $valor)
        {
            $pdf->Cell($ancho_campo,$alto_filas,number_format($valor),0,0,'R', $_SESSION['relleno']);
        }
        $totales['inicial']+= $valores['inicial'];
        $totales['ventas']+= $valores['ventas'];
        $totales['cobranzas']+= $valores['cobranzas'];
        $totales['final']+= $valores['final'];
        $pdf->ln();
    }
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas, 'Totales', 0, 0,'L', $_SESSION['relleno']);
    foreach ($totales as $columna => $valor) $pdf->Cell($ancho_campo,$alto_filas,number_format($valor),0,0,'R', $_SESSION['relleno']);
    $pdf->ln();

    // $pdf->ln();
    
    // ------------------------------------------------------------------------------------------------------------------------
    $ancho_descripcion = round($ancho_total * 0.30);
    // $ancho_campo = ($ancho_total - $ancho_descripcion) / (count($cuentas_a_pagar[array_keys($cuentas_a_pagar)[0]])+1);
    $ancho_campo = ($ancho_total - $ancho_descripcion) / (count($cuentas_a_pagar[array_keys($cuentas_a_pagar)[0]]));
    // $pdf->colores_normales();
    $pdf->colores_cabecera();
    $pdf->ln();
    $pdf->Cell($ancho_total,$alto_filas, 'Cuentas Por Pagar',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();

    $pdf->colores_cabecera();
    $pdf->Cell($ancho_descripcion,$alto_filas,'Descripcion',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Cuotas',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Cuota',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Deuda Original',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Pagos',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Saldo',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();

    $totales = array();
    $totales['cuota'] = 0;
    $totales['deuda_original'] = 0;
    $totales['pagos'] = 0;
    $totales['saldo'] = 0;
    $pdf->colores_normales();
    foreach ($cuentas_a_pagar as $centro => $valores)
    {
        $pdf->Cell($ancho_descripcion,$alto_filas,strtoupper($centro),0,0,'L', $_SESSION['relleno']);
        
        $pdf->Cell($ancho_campo,$alto_filas, $valores['cuotas'],0,0,'R', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas, number_format($valores['cuota']),0,0,'R', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas, number_format($valores['deuda_original']),0,0,'R', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas, number_format($valores['pagos']),0,0,'R', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas, number_format($valores['saldo']),0,0,'R', $_SESSION['relleno']);

        $totales['cuota']+= $valores['cuota'];
        $totales['deuda_original']+= $valores['deuda_original'];
        $totales['pagos']+= $valores['pagos'];
        $totales['saldo']+= $valores['saldo'];
        $pdf->ln();
    }
    $pdf->colores_cabecera();
    $pdf->Cell(($ancho_descripcion + $ancho_campo),$alto_filas, 'Totales', 0, 0,'L', $_SESSION['relleno']);
    foreach ($totales as $columna => $valor) $pdf->Cell($ancho_campo,$alto_filas,number_format($valor),0,0,'R', $_SESSION['relleno']);
    $pdf->ln();

    // $pdf->ln();
    
    // ------------------------------------------------------------------------------------------------------------------------


    $ancho_campo = $ancho_total / (count($cuentas_bancarias[array_keys($cuentas_bancarias)[0]])+1);
    // $pdf->colores_normales();
    $pdf->colores_cabecera();
    $pdf->ln();
    $pdf->Cell($ancho_total,$alto_filas, 'Saldos Bancarios',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Cuenta Bancaria',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Inicial',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Depositos',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Giros',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Final',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $totales['inicial'] = 0;
    $totales['depositos'] = 0;
    $totales['giros'] = 0;
    $totales['final'] = 0;
    $pdf->colores_normales();
    foreach ($cuentas_bancarias as $cuenta_bancaria => $valores)
    {
        $moneda_esta_cuenta_bancaria = isset($_SESSION['monedas_por_cuenta_bancaria'][$cuenta_bancaria]) ? $_SESSION['monedas_por_cuenta_bancaria'][$cuenta_bancaria] : 'guaranies';
        $decimales = isset($_SESSION['cotizacion'][$moneda_esta_cuenta_bancaria]['decimales']) ? $_SESSION['cotizacion'][$moneda_esta_cuenta_bancaria]['decimales'] : 0;
        $multiplicar = false;
        if(isset($_SESSION['cotizacion'][$moneda_esta_cuenta_bancaria])) $multiplicar = true;

        $pdf->Cell($ancho_campo,$alto_filas,ucwords($cuenta_bancaria),0,0,'L', $_SESSION['relleno']);
        
        $inicial = (isset($valores['inicial'])) ? $valores['inicial'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($inicial, $decimales),0,0,'R', $_SESSION['relleno']);
        if($multiplicar) $inicial = $inicial * $_SESSION['cotizacion'][$moneda_esta_cuenta_bancaria]['cotizacion'];
        
        $depositos = (isset($valores['depositos'])) ? $valores['depositos'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($depositos, $decimales),0,0,'R', $_SESSION['relleno']);
        if($multiplicar) $depositos = $depositos * $_SESSION['cotizacion'][$moneda_esta_cuenta_bancaria]['cotizacion'];
        
        $giros = (isset($valores['giros'])) ? $valores['giros'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($giros, $decimales),0,0,'R', $_SESSION['relleno']);
        if($multiplicar) $giros = $giros * $_SESSION['cotizacion'][$moneda_esta_cuenta_bancaria]['cotizacion'];
        
        $final = (isset($valores['final'])) ? $valores['final'] : 0;
        if($multiplicar) $final = $final * $_SESSION['cotizacion'][$moneda_esta_cuenta_bancaria]['cotizacion'];
        // $pdf->Cell($ancho_campo,$alto_filas,number_format($final, $decimales),0,0,'R', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas,number_format(round($final)),0,0,'R', $_SESSION['relleno']);
        
        $totales['inicial']+= $inicial;
        $totales['depositos']+= $depositos;
        $totales['giros']+= $giros;
        $totales['final']+= $final;
        $pdf->ln();
    }
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Totales Brutos',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['inicial']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['depositos']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['giros']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['final']),0,0,'R', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->colores_normales();
    foreach ($cuentas_bancarias_comisiones as $comision_tipo => $comision_monto) 
    {
        $pdf->colores_normales();
        $pdf->Cell($ancho_campo,$alto_filas, ucwords($comision_tipo),0,0,'L', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
        $pdf->SetTextColor(255,0,0); // TEXTO ROJO
        $pdf->Cell($ancho_campo,$alto_filas, '-'.number_format($comision_monto),0,0,'R', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
        $pdf->ln();
        $totales['depositos']-= $comision_monto;
        // $totales['giros']-= $comision_monto;
    }
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Totales Netos',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['depositos']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
    $pdf->ln();
    // $pdf->ln();

    // ------------------------------------------------------------------------------------------------------------------------
    $ancho_campo = $ancho_total / 6;
    // $pdf->colores_normales();
    $pdf->colores_cabecera();
    $pdf->ln();
    $pdf->Cell($ancho_total,$alto_filas, 'Valores A Depositar',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Instrumento',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Inicial',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Cobranzas',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Comisiones',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Depositos',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Final',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $totales = array();
    $totales['inicial'] = 0;
    $totales['cobranzas'] = 0;
    $totales['comisiones'] = 0;
    $totales['depositos'] = 0;
    $totales['final'] = 0;
    $pdf->colores_normales();
    foreach($a_depositar as $instrumento => $valores)
    {
        $pdf->Cell($ancho_campo,$alto_filas,ucwords($instrumento),0,0,'L', $_SESSION['relleno']);

        $inicial = (isset($valores['inicial'])) ? $valores['inicial'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($inicial),0,0,'R', $_SESSION['relleno']);
        
        $cobranzas = (isset($valores['cobranzas'])) ? $valores['cobranzas'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($cobranzas),0,0,'R', $_SESSION['relleno']);

        $comisiones = (isset($valores['comisiones'])) ? $valores['comisiones'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($comisiones),0,0,'R', $_SESSION['relleno']);

        $depositos = (isset($valores['depositos'])) ? $valores['depositos'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($depositos),0,0,'R', $_SESSION['relleno']);
        
        $final = (isset($valores['final'])) ? $valores['final'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($final),0,0,'R', $_SESSION['relleno']);

        $totales['inicial']+= $inicial;
        $totales['cobranzas']+= $cobranzas;
        $totales['comisiones']+= $comisiones;
        $totales['depositos']+= $depositos;
        $totales['final']+= $final;
        $pdf->ln();
    }
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Totales',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['inicial']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['cobranzas']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['comisiones']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['depositos']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['final']),0,0,'R', $_SESSION['relleno']);
    $pdf->ln();
    // $pdf->ln();

    $ancho_campo = $ancho_total / 8;
    // $pdf->colores_normales();
    $pdf->colores_cabecera();
    $pdf->ln();
    $pdf->Cell($ancho_total,$alto_filas, 'Debitos Automaticos',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->colores_cabecera();
    $pdf->Cell(($ancho_campo*2),$alto_filas,'Procesadora',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Total del Mes',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Cobrado Otra Forma',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Enviado',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Aprobado',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Rechazado',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Facturado',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();

    $totales['total_del_mes'] = 0;
    $totales['cobrado_otra_forma'] = 0;
    $totales['enviado'] = 0;
    $totales['aprobado'] = 0;
    $totales['rechazado'] = 0;
    $totales['facturado'] = 0;
    $pdf->colores_normales();
    foreach ($procesadoras_debitos_automaticos as $procesadora_codigo => $procesadora_nombre)
    {
        $pdf->Cell(($ancho_campo*2),$alto_filas,ucwords($procesadora_nombre).' ('.$procesadora_codigo.')',0,0,'L', $_SESSION['relleno']);

        $total_del_mes = (isset($debitos_automaticos[$procesadora_nombre]['total_del_mes'])) ? $debitos_automaticos[$procesadora_nombre]['total_del_mes'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($total_del_mes),0,0,'R', $_SESSION['relleno']);

        $cobrado_otra_forma = (isset($debitos_automaticos[$procesadora_nombre]['cobrado_otra_forma'])) ? $debitos_automaticos[$procesadora_nombre]['cobrado_otra_forma'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($cobrado_otra_forma),0,0,'R', $_SESSION['relleno']);

        $enviado = (isset($debitos_automaticos[$procesadora_nombre]['enviado'])) ? $debitos_automaticos[$procesadora_nombre]['enviado'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($enviado),0,0,'R', $_SESSION['relleno']);

        $aprobado = (isset($debitos_automaticos[$procesadora_nombre]['aprobado'])) ? $debitos_automaticos[$procesadora_nombre]['aprobado'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($aprobado),0,0,'R', $_SESSION['relleno']);

        $rechazado = (isset($debitos_automaticos[$procesadora_nombre]['rechazado'])) ? $debitos_automaticos[$procesadora_nombre]['rechazado'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($rechazado),0,0,'R', $_SESSION['relleno']);
        
        $facturado = (isset($debitos_automaticos[$procesadora_nombre]['facturado'])) ? $debitos_automaticos[$procesadora_nombre]['facturado'] : 0;
        $pdf->Cell($ancho_campo,$alto_filas,number_format($facturado),0,0,'R', $_SESSION['relleno']);

        $totales['total_del_mes']+= $total_del_mes;
        $totales['cobrado_otra_forma']+= $cobrado_otra_forma;
        $totales['enviado']+= $enviado;
        $totales['aprobado']+= $aprobado;
        $totales['rechazado']+= $rechazado;
        $totales['facturado']+= $facturado;
        $pdf->ln();
    }
    $pdf->colores_cabecera();
    $pdf->Cell(($ancho_campo*2),$alto_filas,'Totales',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['total_del_mes']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['cobrado_otra_forma']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['enviado']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['aprobado']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['rechazado']),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($totales['facturado']),0,0,'R', $_SESSION['relleno']);
    $pdf->ln();

    // ------------------------------------------------------------------------------------------------------------------------
    // $pdf->AddPage('P', 'Letter');
    $pdf->AddPage('P', array(216, 330));
    // ------------------------------------------------------------------------------------------------------------------------
    $ancho_campo = $ancho_total / 3;
    // $pdf->colores_normales();
    $pdf->colores_cabecera();
    // $pdf->ln();
    $pdf->Cell($ancho_total,$alto_filas, 'Cobranzas Por Centro',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Centro',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Monto Del Dia',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Monto Acumulado',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $total_centros_del_dia = 0;
    $total_centros_acumulado = 0;
    $pdf->colores_normales();
    foreach($cobranzas_por_centro as $centro => $montos)
    {
        $pdf->Cell($ancho_campo,$alto_filas,strtoupper($centro),0,0,'L', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas,number_format($montos['del_dia']),0,0,'R', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas,number_format($montos['acumulado']),0,0,'R', $_SESSION['relleno']);
        $pdf->ln();
        $total_centros_del_dia+= $montos['del_dia'];
        $total_centros_acumulado+= $montos['acumulado'];
    }
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Totales',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($total_centros_del_dia),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,number_format($total_centros_acumulado),0,0,'R', $_SESSION['relleno']);
    // $pdf->ln();
    $pdf->ln();

    // ------------------------------------------------------------------------------------------------------------------------
    $ancho_campo = $ancho_total / 3;
    // $pdf->colores_normales();
    $pdf->colores_cabecera();
    $pdf->ln();
    $pdf->Cell($ancho_total,$alto_filas, 'Cobranzas Por Instrumento',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Instrumento',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas,'Monto Del Dia',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $total_instrumentos_del_dia = 0;
    $total_instrumentos_acumulado = 0;
    $pdf->colores_normales();
    foreach($cobranzas_por_instrumento as $instrumento => $monto)
    {
        $pdf->Cell($ancho_campo,$alto_filas,ucwords($instrumento),0,0,'L', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas,number_format($monto),0,0,'R', $_SESSION['relleno']);
        $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
        $pdf->ln();
        $total_instrumentos_del_dia+= $monto;
        // $total_instrumentos_acumulado+= $montos['acumulado'];
    }
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Total Bruto',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas, number_format($total_instrumentos_del_dia),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->colores_normales();
    $pdf->Cell($ancho_campo,$alto_filas,'Descuentos',0,0,'L', $_SESSION['relleno']);
    $pdf->SetTextColor(255,0,0); // TEXTO ROJO
    $pdf->Cell($ancho_campo,$alto_filas, '-'.number_format($cobranzas_por_instrumento['descuentos']),0,0,'R', $_SESSION['relleno']);
    $total_instrumentos_del_dia-= $cobranzas_por_instrumento['descuentos'];
    $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campo,$alto_filas,'Total Neto/Facturacion Real',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas, number_format($total_instrumentos_del_dia),0,0,'R', $_SESSION['relleno']);
    $pdf->Cell($ancho_campo,$alto_filas, '',0,0,'R', $_SESSION['relleno']);
    // $pdf->ln();
    $pdf->ln();

    // ------------------------------------------------------------------------------------------------------------------------
    $pdf->SetAutoPageBreak("on", 10);
    $pdf->SetMargins(5, 10, 5);
    // $pdf->AddPage('P', 'Letter');
    $pdf->AddPage('P', array(216, 330));
    $pdf->SetFont('Arial', '', 6);
    // ------------------------------------------------------------------------------------------------------------------------
    $ancho_campos = array();
    $ancho_campos['factura'] = 20;
    $ancho_campos['cuenta'] = 25;
    $ancho_campos['ruc'] = 11;
    $ancho_campos['forma_de_pago_1_tipo'] = 22;
    $ancho_campos['forma_de_pago_2_tipo'] = 22;
    $ancho_campos['forma_de_pago_3_tipo'] = 22;
    $ancho_total = array_sum($ancho_campos);
    $ancho_campos['defecto'] = 11;
    $pdf->colores_cabecera();
    $pdf->Cell($ancho_campos['factura'],$alto_filas, 'Factura',0,0,'L', $_SESSION['relleno']);
    foreach ($campos_por_factura_de_formularios as $campo_nombre)
    {
        $ancho_campo = isset($ancho_campos[$campo_nombre]) ? $ancho_campos[$campo_nombre] : $ancho_campos['defecto'];
        $campo_nombre = str_replace('forma_de_pago_', 'F.P._', $campo_nombre);
        $campo_nombre = str_replace('_tipo', '', $campo_nombre);
        $campo_nombre = str_replace('_', ' ', $campo_nombre);
        $pdf->Cell($ancho_campo, $alto_filas, ucwords($campo_nombre), 0, 0, 'L', $_SESSION['relleno']);
    }

    $pdf->Cell($ancho_campos['defecto'],$alto_filas, 'Monto',0,0,'L', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->colores_normales();
    $total_cobrado_por_factura = 0;
    foreach ($cobranzas_por_factura as $factura_numero => $monto)
    {
        $pdf->Cell($ancho_campos['factura'],$alto_filas, $factura_numero,0,0,'L', $_SESSION['relleno']);
        foreach ($cobranzas_por_factura_formularios[$factura_numero] as $campo_nombre => $campo_valor)
        {
            $ancho_campo = isset($ancho_campos[$campo_nombre]) ? $ancho_campos[$campo_nombre] : $ancho_campos['defecto'];
            switch ($campo_nombre)
            {
                case 'forma_de_pago_1_monto':
                case 'forma_de_pago_2_monto':
                case 'forma_de_pago_3_monto':
                    $pdf->Cell($ancho_campo,$alto_filas,number_format($campo_valor),0,0,'R', $_SESSION['relleno']);
                break;
                
                case 'cuenta':
                case 'forma_de_pago_1_tipo':
                case 'forma_de_pago_1_numero':
                case 'forma_de_pago_2_tipo':
                case 'forma_de_pago_2_numero':
                case 'forma_de_pago_3_tipo':
                case 'forma_de_pago_3_numero':
                    $string_a_mostrar = '';
                    if($pdf->GetStringWidth($campo_valor) >= $ancho_campo)
                    {
                        for ($i=0; $i < strlen($campo_valor); $i++)
                        { 
                            $string_a_mostrar.= $campo_valor[$i];
                            if($pdf->GetStringWidth($string_a_mostrar) >= ($ancho_campo - 7))
                            {
                                $string_a_mostrar.= '...';
                                break;
                            }
                        }
                    }
                    else
                    {
                        $string_a_mostrar = $campo_valor;
                    }
                    $pdf->Cell($ancho_campo, $alto_filas, $string_a_mostrar,0,0,'L', $_SESSION['relleno']);
                break;

                default:
                    $pdf->Cell($ancho_campo, $alto_filas, $campo_valor,0,0,'L', $_SESSION['relleno']);
                break;
            }
        }
        $pdf->Cell($ancho_campos['defecto'], $alto_filas, number_format($monto), 0, 0, 'R', $_SESSION['relleno']);
        $total_cobrado_por_factura+= $monto;
        $pdf->ln();
    }
    $pdf->colores_cabecera();
    $pdf->Cell(($ancho_total - $ancho_campos['defecto']), $alto_filas,'Totales',0,0,'L', $_SESSION['relleno']);
    $pdf->Cell($ancho_campos['defecto'], $alto_filas, number_format($total_cobrado_por_factura),0,0,'R', $_SESSION['relleno']);
    $pdf->ln();
    $pdf->ln();

    // ------------------------------------------------------------------------------------------------------------------------
    $pdf->SetAutoPageBreak("on", 10);
    $pdf->SetMargins(5, 10, 5);
    $pdf->SetFont('Arial', '', 6);
    // $pdf->AddPage('P', 'Letter');
    // ------------------------------------------------------------------------------------------------------------------------

    $ancho_campos = array();
    $ancho_campos['documento_tipo'] = 30;
    $ancho_campos['documento_numero'] = 25;
    $ancho_campos['factura_numero'] = 20;
    $ancho_campos['cuenta'] = 55;
    $ancho_campos['cuenta_bancaria_titular'] = 55;
    $ancho_campos['derecho'] = 15;
    $ancho_total = array_sum($ancho_campos);
    $ancho_campos['defecto'] = 20;

    foreach ($pagos_por_banco_por_documento_tipo_numero as $banco => $pagos_de_este_banco)
    {
        // ------------------------------------------------------------------------------------------------------------------------
        // $pdf->AddPage('P', 'Letter');
        $pdf->AddPage('P', array(216, 330));
        // ------------------------------------------------------------------------------------------------------------------------
        $pdf->colores_cabecera();
        $pdf->Cell($ancho_total, $alto_filas, ucwords('Pagos del Dia '.$fecha_elegida.' de la cuenta '.ucwords($banco)), 0, 0, 'L', $_SESSION['relleno']);
        $pdf->ln();
        foreach ($campos_por_banco_por_documento_tipo_numero as $campo_nombre)
        {
            $ancho_campo = isset($ancho_campos[$campo_nombre]) ? $ancho_campos[$campo_nombre] : $ancho_campos['defecto'];
            $campo_nombre = str_replace('cuenta_bancaria', 'cta._bca.', $campo_nombre);
            $campo_nombre = str_replace('numero', 'num.', $campo_nombre);
            $campo_nombre = str_replace('_', ' ', $campo_nombre);
            $pdf->Cell($ancho_campo, $alto_filas, ucwords($campo_nombre), 0, 0, 'L', $_SESSION['relleno']);
        }
        $pdf->ln();
        $pdf->colores_normales();
        $total_pagos_este_banco = 0;
        foreach ($pagos_de_este_banco as $agrupador => $pagos_campos)
        {
            foreach ($pagos_campos as $campo_nombre => $campo_valor)
            {
                $ancho_campo = isset($ancho_campos[$campo_nombre]) ? $ancho_campos[$campo_nombre] : $ancho_campos['defecto'];
                switch ($campo_nombre)
                {
                    case 'derecho':
                        $pdf->Cell($ancho_campo,$alto_filas,number_format($campo_valor),0,0,'R', $_SESSION['relleno']);
                    break;
                    
                    default:
                        $pdf->Cell($ancho_campo, $alto_filas, $campo_valor,0,0,'L', $_SESSION['relleno']);
                    break;
                }
            }
            $total_pagos_este_banco+= $pagos_campos['derecho'];
            $pdf->ln();
        }
        $pdf->colores_cabecera();
        $pdf->Cell(($ancho_total - $ancho_campos['defecto']), $alto_filas,'Total',0,0,'L', $_SESSION['relleno']);
        $pdf->Cell($ancho_campos['defecto'], $alto_filas, number_format($total_pagos_este_banco),0,0,'R', $_SESSION['relleno']);
        $pdf->ln();
        $pdf->ln();
    }

    // // ------------------------------------------------------------------------------------------------------------------------
    // $pdf->SetAutoPageBreak("on", 1);
    // $pdf->SetMargins(20, 10, 5);
    // $pdf->AddPage('P', 'Letter');
    // $pdf->SetFont('Arial', '', 8);
    // // ------------------------------------------------------------------------------------------------------------------------

    $nombre_del_archivo = '../../vistas/sintesis/cajas/'.$nombre_del_archivo.'.pdf';
    $pdf->Output($nombre_del_archivo);

?>
