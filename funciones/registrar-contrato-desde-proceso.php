<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include 'conectar-base-de-datos.php';
$datos =  $_POST['paramName'];
$cantidad_datos = count($datos);
$hoy = date("Y-m-d");
$mensaje = "no procesado.";
$cantidad = 0;
$factura_cobranza = "sin datos";

foreach ($datos as $value_dato){

    /************INSERTAR VENTA*************/
    if($value_dato['factura_cobranza']=="No"){
        $factura_cobranza = "sin datos";
    }else{
        $factura_cobranza = $value_dato['factura_cobranza'];
    }

    $_SESSION['precio'] = str_replace(",","",$value_dato['precio']);
    $_SESSION['id'] = $value_dato['id'];
    $_SESSION['pagare_numero'] = $value_dato['pagare_numero'];
    $_SESSION['entrega_inicial'] = str_replace(",","",$value_dato['entrega_inicial']);
    $primera_insercion = 0;
    $cuota_string_first = null;
    $cuota_descripcion_first = null;
    $cuota_vencimiento_first = null;
    $consulta = "INSERT INTO 
    ventas 
    (id, venta, fecha, cliente,producto,documento_tipo
    ,documento_numero,vendedor,solicitud,factura,precio,entrega_inicial,precio_cuota,
     cuotas,ingreso_exonerado,debito_automatico,adelanto,venta_especial,linea,sucursal,
     usuario,creado,modificado,borrado,factura_cobranza,cobrador,
     sitio_emprendimiento,
     sitio_linea,
     sitio_area,
     sitio_sendero,
     sitio_numero,
     pagare_numero) 
     VALUES (NULL, '".$value_dato['venta']."', 
     '".$value_dato['fecha']."',
     '".$value_dato['cliente']."','".$value_dato['producto']."','".$value_dato['documento_tipo']."',
     '".$value_dato['documento_numero']."','".$value_dato['vendedor']."',
     '".$value_dato['solicitud']."', '".$value_dato['factura']."', '".str_replace(",","",$value_dato['precio'])."',
     '".str_replace(",","",$value_dato['entrega_inicial'])."','".str_replace(",","",$value_dato['precio_cuota'])."','".$value_dato['cuotas']."',
     '".$value_dato['ingreso_exonerado']."','".$value_dato['debito_automatico']."','".$value_dato['adelanto']."',
     'no','".$value_dato['linea']."',
     '".$value_dato['sucursal']."', '".$_SESSION['usuario_en_sesion']."',
     '".date('Y-m-d G:i:s')."','".date('Y-m-d G:i:s')."','no','".$factura_cobranza."',
     '".$value_dato['cobrador']."',
     '".$value_dato['sitio_emprendimiento']."',
     '".$value_dato['sitio_linea']."',
     '".$value_dato['sitio_area']."',
     '".$value_dato['sitio_sendero']."',
     '".$value_dato['sitio_sitio']."',
     '".$value_dato['pagare_numero']."')";

    //$query_resultados = $conexion->prepare($consulta);
    //$query_resultados->execute();
    /************FIN INSERTAR VENTA*************/

    /************INSERTAR CONTRATO*************/
    //PREGUNTAR SI EXISTE BENEFICIARIO
    $existe = 0;
    for ($int=0;$int<count($value_dato['beneficiarios']);$int++){
        //$existe = $value_dato['beneficiarios'][$int]['nombre'];
        $existe = 1;
    }

    $sum_total = 1000;
    $documento_tipo = explode(" ",$value_dato['documento_tipo']);
    $contrato = $value_dato['linea']."-".$documento_tipo[1];
    $contrato = $value_dato['linea']."-".$documento_tipo[1]."-".str_pad($value_dato['documento_numero'],7,"0",STR_PAD_LEFT);
    $total_cuota = 1000;
    $_SESSION['contra_numero'] = $contrato;

    /*******OBTERNER DATOS DE LA CUENTA**************/
    $sql_get_cuenta = "SELECT * FROM cuentas WHERE cuenta LIKE '".$value_dato['cliente']."' ";
    $query_cuenta = $conexion->prepare($sql_get_cuenta);
    $query_cuenta->execute();
    $resultado_cuenta = $query_cuenta->fetchAll();
    $cuenta_numero = 'sin datos';
    $cuenta_documento_numero = 'sin datos';
    $cuenta_documento_tipo = 'sin datos';
    $cuenta_domicilio = 'sin datos';
    $cuenta_domicilio_numero = 'sin datos';
    $cuenta_domicilio_barrio = 'sin datos';
    $cuenta_telefono = 'sin datos';
    foreach ($resultado_cuenta as $value_cuenta){
        $cuenta_numero = $value_cuenta['cliente'];
        $cuenta_documento_tipo = $value_cuenta['documento_tipo'];
        $cuenta_documento_numero = $value_cuenta['documento_numero'];
        $cuenta_domicilio = $value_cuenta['domicilio_calle'];
        $cuenta_domicilio_numero = $value_cuenta['domicilio_numero'];
        $cuenta_domicilio_barrio = $value_cuenta['domicilio_barrio'];
        $cuenta_telefono = $value_cuenta['telefono'];
    }
    //CANTIDAD DE CUOTAS A GENERAR
    $cantidad_cuotas_a_generar = 0;
    if(date('d')>10){
        $meses = 13;
    }else{
        $meses = 12;
    }
    $meses = 12;

    if($documento_tipo[1]=="psm"){
        $cantidad_cuotas_a_generar = $meses - date('m');
    }else{
        $cantidad_cuotas_a_generar = 1;
    }

    $cantidad_cuotas_a_generar_increment = $cantidad_cuotas_a_generar;
    //SI NO EXISTE BENEFICIARIO, AGREGAR 1 CONTRATO, O SINO, AGREGAR CONTRATOS CON LOS BENEFICIARIOS

    $_SESSION['sitio_emprendimiento'] =  0 ;
    if(empty($value_dato['sitio_emprendimiento'])){
        $_SESSION['sitio_emprendimiento'] =  0 ;
    }else{
        $_SESSION['sitio_emprendimiento'] =  $value_dato['sitio_emprendimiento'] ;
    }

    $_SESSION['sitio_linea'] =  0 ;
    if(empty($value_dato['sitio_linea'])){
        $_SESSION['sitio_linea'] =  0 ;
    }else{
        $_SESSION['sitio_linea'] =  $value_dato['sitio_linea'] ;
    }

    $_SESSION['sitio_area'] =  0 ;
    if(empty($value_dato['sitio_area'])){
        $_SESSION['sitio_area'] =  0 ;
    }else{
        $_SESSION['sitio_area'] =  $value_dato['sitio_area'] ;
    }

    $_SESSION['sitio_sendero'] =  0 ;
    if(empty($value_dato['sitio_sendero'])){
        $_SESSION['sitio_sendero'] =  0 ;
    }else{
        $_SESSION['sitio_sendero'] =  $value_dato['sitio_sendero'] ;
    }

    $_SESSION['sitio_sitio'] =  0 ;
    if(empty($value_dato['sitio_sitio'])){
        $_SESSION['sitio_sitio'] =  0 ;
    }else{
        $_SESSION['sitio_sitio'] =  $value_dato['sitio_sitio'] ;
    }


     $sql_update_venta = "UPDATE ventas 
    SET sitio_emprendimiento = '".$_SESSION['sitio_emprendimiento']."',
                   sitio_linea = '".$_SESSION['sitio_linea']."',
                    sitio_area = '".$_SESSION['sitio_area']."',
                    sitio_sendero = '".$_SESSION['sitio_sendero']."',
                    sitio_numero = '".$_SESSION['sitio_sitio']."',
                    pagare_numero = '".$_SESSION['pagare_numero']."'
                    WHERE id=".$_SESSION['id'];
    $query_update = $conexion->prepare($sql_update_venta);
    $query_update->execute();

    if($existe==0){
        $precio_cuota = $value_dato['precio_cuota'];
        $sql_insert = "INSERT INTO contratos (
                   `id`,
                   `fecha`,
                   `contrato`,
                   `contrato_linea`,
                   `contrato_centro`,
                   `contrato_numero`,
                   `producto`,
                   `cuenta`,
                   `cuenta_numero`,
                   `cuenta_documento_tipo`,
                   `cuenta_documento_numero`,
                   `cuenta_sexo`,
                   `cuenta_direccion_particular`,
                   `cuenta_particular_numero`,
                   `cuenta_particular_barrio`,
                   `cuenta_particular_pais`,
                   `cuenta_direccion_laboral`,
                   `cuenta_laboral_numero`,
                   `cuenta_laboral_barrio`,
                   `cuenta_laboral_pais`,
                   `cuenta_direccion_declarada_titular`,
                   `cuenta_declarada_titular_numero`,
                   `cuenta_declarada_titular_barrio`,
                   `cuenta_declarada_titular_pais`,
                   `cuenta_telefono`,
                   `beneficiario`,
                   `beneficiario_numero`,
                   `beneficiario_documento_tipo`,
                   `beneficiario_documento_numero`,
                   `beneficiario_nacimiento`,
                   `beneficiario_sexo`,
                   `beneficiario_estado_civil`,
                   `beneficiario_defuncion`,
                   `beneficiario_edad`,
                   `contacto_direccion`,
                   `contacto_numero`,
                   `contacto_barrio`,
                   `contacto_pais`,
                   `contacto_direccion_interseccion`,
                   `contacto_direccion_codigo_postal`,
                   `contacto_direccion_referencias`,
                   `contacto_telefono`,
                   `contacto_celular`,
                   `contacto_observaciones`,
                   `monto_diferido`,
                   `entrega_inicial`,
                   `cuotas_cantidad`,
                   `cuota_monto`,
                   `pre_vigencia`,
                   `caja_factura_numero`,
                   `caja_recibo_numero`,
                   `caja_monto`,
                   `asociacion`,
                   `asociacion_numero`,
                   `vencimiento_dia`,
                   `pagare_numero`,
                   `observaciones`,
                   `plazo_modificado`,
                   `plazo_actual`,
                   `datos_supervisor_de_ventas`,
                   `datos_gerente_de_ventas`,
                   `datos_base_de_datos`,
                   `datos_gerente_administrativo`,
                   `cobrador_numero`,
                   `datos_x`,
                   `creado`,
                   `modificado`,
                   `borrado`,
                   `usuario`,
                   `origen`,
                   `forma_de_pago`,
                   `cobrador_nombre`,
                   `estado`,
                   `sitio_emprendimiento`,
                   `sitio_linea`,
                   `sitio_area`,
                   `sitio_sendero`,
                   `sitio_numero`) 
                   VALUES (NULL,
                   '".$value_dato['fecha']."',
                   '".$contrato."',
                   '".$value_dato['linea']."',
                   '".$documento_tipo[1]."',
                   '".$value_dato['documento_numero']."',
                   '".$value_dato['producto']."',
                   '".$value_dato['cliente']."',
                   '".$cuenta_numero."',
                   '".$cuenta_documento_tipo."',
                   '".$cuenta_documento_numero."',
                   'sin datos',
                   '".$cuenta_domicilio."',
                   '".$cuenta_domicilio_numero."',
                   '".$cuenta_domicilio_barrio."',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                  'sin datos',
                  'sin datos',
                  'sin datos',
                  'sin datos',
                  '".$cuenta_telefono."',
                   'beneficiario 01',
                   'bene 01 num',
                   'beneficiario 01 documento tipo',
                   'beneficiario 01 documento numero',
                   'beneficiario 01 fecha_nacimeinto',
                   'beneficiario 01 sexo',
                   'beneficiario 01 estado civil',
                   '0000-00-00',
                   'beneficiario 01 edad',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'telefono contacto',
                   'celular contacto',
                   'obs contacto',
                   '".$_SESSION['precio']."',
                   '".$_SESSION['entrega_inicial']."',
                   ".$cantidad_cuotas_a_generar.",
                   ".str_replace(",","",$precio_cuota).",
                   'sin datos',
                   '".$factura_cobranza."',
                   'sin datos',
                   '".str_replace(",","",$value_dato['entrega_inicial'])."',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   '".$value_dato['pagare_numero']."',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   '".date("Y-m-d H:s:i")."',
                   '".date("Y-m-d H:s:i")."',
                    'no',
                    '".ucwords($_SESSION['usuario_en_sesion'])."', 
                    'sin datos',
                    '".$value_dato['forma_de_pago']."',
                    '".$value_dato['cobrador']."',
                    'vigente',
                    '".$_SESSION['sitio_emprendimiento']."',
                    '".$_SESSION['sitio_linea']."',
                    '".$_SESSION['sitio_area']."',
                    '".$_SESSION['sitio_sendero']."',
                    '".$_SESSION['sitio_sitio']."')";

        $file = fopen('CONTRATO.txt', "w");
        fwrite($file, $sql_insert . PHP_EOL);
        fclose($file);
        $save = $conexion->prepare($sql_insert);
        $save->execute();

    }else{

    for ($int=0;$int<count($value_dato['beneficiarios']);$int++) {
        $beneficiario_nombre = $value_dato['beneficiarios'][$int]['nombre'];
        $beneficiario_documento_tipo = $value_dato['beneficiarios'][$int]['ci'];
        if(empty($value_dato['beneficiarios'][$int]['documento'])){
            $beneficiario_documento_numero =  0;
        }else{
            $beneficiario_documento_numero =  $value_dato['beneficiarios'][$int]['documento'];
        }
        $beneficiario_sexo = $value_dato['beneficiarios'][$int]['sexo'];
        $beneficiario_estado_civil = $value_dato['beneficiarios'][$int]['estado_civil'];
        $beneficiario_nacimiento = $value_dato['beneficiarios'][$int]['nacimiento'];
        $beneficiario_edad = $value_dato['beneficiarios'][$int]['edad'];
        if(empty($value_dato['beneficiarios'][$int]['monto'])){
            $beneficiario_monto = 0;
        }else{
            $beneficiario_monto = $value_dato['beneficiarios'][$int]['monto'];
        }

        $beneficiario_vigencia = $value_dato['beneficiarios'][$int]['vigencia'];

        $sql_insert = "INSERT INTO contratos (
                   `id`,
                   `fecha`,
                   `contrato`,
                   `contrato_linea`,
                   `contrato_centro`,
                   `contrato_numero`,
                   `producto`,
                   `cuenta`,
                   `cuenta_numero`,
                   `cuenta_documento_tipo`,
                   `cuenta_documento_numero`,
                   `cuenta_sexo`,
                   `cuenta_direccion_particular`,
                   `cuenta_particular_numero`,
                   `cuenta_particular_barrio`,
                   `cuenta_particular_pais`,
                   `cuenta_direccion_laboral`,
                   `cuenta_laboral_numero`,
                   `cuenta_laboral_barrio`,
                   `cuenta_laboral_pais`,
                   `cuenta_direccion_declarada_titular`,
                   `cuenta_declarada_titular_numero`,
                   `cuenta_declarada_titular_barrio`,
                   `cuenta_declarada_titular_pais`,
                   `cuenta_telefono`,
                   `beneficiario`,
                   `beneficiario_numero`,
                   `beneficiario_documento_tipo`,
                   `beneficiario_documento_numero`,
                   `beneficiario_nacimiento`,
                   `beneficiario_sexo`,
                   `beneficiario_estado_civil`,
                   `beneficiario_defuncion`,
                   `beneficiario_edad`,
                   `contacto_direccion`,
                   `contacto_numero`,
                   `contacto_barrio`,
                   `contacto_pais`,
                   `contacto_direccion_interseccion`,
                   `contacto_direccion_codigo_postal`,
                   `contacto_direccion_referencias`,
                   `contacto_telefono`,
                   `contacto_celular`,
                   `contacto_observaciones`,
                   `monto_diferido`,
                   `entrega_inicial`,
                   `cuotas_cantidad`,
                   `cuota_monto`,
                   `pre_vigencia`,
                   `caja_factura_numero`,
                   `caja_recibo_numero`,
                   `caja_monto`,
                   `asociacion`,
                   `asociacion_numero`,
                   `vencimiento_dia`,
                   `pagare_numero`,
                   `observaciones`,
                   `plazo_modificado`,
                   `plazo_actual`,
                   `datos_supervisor_de_ventas`,
                   `datos_gerente_de_ventas`,
                   `datos_base_de_datos`,
                   `datos_gerente_administrativo`,
                   `cobrador_numero`,
                   `datos_x`,
                   `creado`,
                   `modificado`,
                   `borrado`,
                   `usuario`,
                   `origen`,
                   `forma_de_pago`,
                   `cobrador_nombre`,
                   `estado`,
                   `sitio_emprendimiento`,
                   `sitio_linea`,
                   `sitio_area`,
                   `sitio_sendero`,
                   `sitio_numero`,
                   `beneficiario_vigencia`) 
                   VALUES (NULL,
                   '" . $value_dato['fecha'] . "',
                   '" . $contrato . "',
                   '" . $value_dato['linea'] . "',
                   '" . $documento_tipo[1] . "',
                   '" . $value_dato['documento_numero'] . "',
                   '" . $value_dato['producto'] . "',
                   '" . $value_dato['cliente'] . "',
                   '" . $cuenta_numero . "',
                   '" . $cuenta_documento_tipo . "',
                   '" . $cuenta_documento_numero . "',
                   'sin datos',
                   '" . $cuenta_domicilio . "',
                   '" . $cuenta_domicilio_numero . "',
                   '" . $cuenta_domicilio_barrio . "',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                  'sin datos',
                  'sin datos',
                  'sin datos',
                  'sin datos',
                  '" . $cuenta_telefono . "',
                   '".$beneficiario_nombre."',
                   'bene 01 num',
                   '".$beneficiario_documento_tipo."',
                   '".$beneficiario_documento_numero."',
                   '".$beneficiario_nacimiento."',
                   '".$beneficiario_sexo."',
                   '".$beneficiario_estado_civil."',
                   '0000-00-00',
                   '".$beneficiario_edad."',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'telefono contacto',
                   'celular contacto',
                   'obs contacto',
                  '".$_SESSION['precio']."',
                  '".$_SESSION['entrega_inicial']."',
                   ".$cantidad_cuotas_a_generar.",
                   " . str_replace(".", "", ".$beneficiario_monto.") . ",
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                  '".$value_dato['pagare_numero']."',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   'sin datos',
                   '" . date("Y-m-d H:s:i") . "',
                   '" . date("Y-m-d H:s:i") . "',
                    'no',
                    '" . ucwords($_SESSION['usuario_en_sesion']) . "', 
                    'sin datos',
                    '" . $value_dato['forma_de_pago'] . "',
                    '" . $value_dato['cobrador'] . "',
                    'vigente',
                    '".$_SESSION['sitio_emprendimiento']."',
                    '".$_SESSION['sitio_linea']."',
                    '".$_SESSION['sitio_area']."',
                    '".$_SESSION['sitio_sendero']."',
                    '".$_SESSION['sitio_sitio']."',
                    '".$beneficiario_vigencia."')";

            $file = fopen('CONTRATO_bene.txt', "w");
            fwrite($file, $sql_insert . PHP_EOL);
            fclose($file);
            $save = $conexion->prepare($sql_insert);
            $save->execute();
        }
    }
    /************FIN INSERTAR CONTRATO*************/

    $mensaje = "procesado.";
}
$mensaje_2 = array("mensaje"=>$mensaje);
echo json_encode($mensaje_2);
?>