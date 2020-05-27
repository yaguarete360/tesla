<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include 'conectar-base-de-datos.php';
$datos =  $_POST['paramName'];
$cantidad_datos = count($datos);
//$hoy = date("Y-m-d");
$hoy = "2019-06-11";
$mensaje = "no procesado.";
$cantidad = 0;
foreach ($datos as $value_dato){
    $hoy=$value_dato['vencimiento'];
    $cuenta=$value_dato['documento'];
    $consulta = 'SELECT * FROM diario WHERE id='.$value_dato['id'];
    $query_resultados = $conexion->prepare($consulta);
    $query_resultados->execute();
    $resultado = $query_resultados->fetchAll();
    $cantidad++;
    foreach ($resultado as $value){
        $descripcion_cobranza = 'Cobranza de la cuota '.$value['descripcion'];
        if($value_dato['tipo_factura']=="unica"){
            $factura = "002-001-".$value_dato['factura'];
            $numero_factura = $value_dato['factura'];
            $valor_cheque = $value_dato['monto'];
        }else{
            $valor_cheque = $value_dato['monto']/$cantidad_datos;
            if($cantidad==1){
                $numero_factura = $value_dato['factura'];
                $numero_factura = $numero_factura;
                $numero_factura = str_pad($numero_factura,7,"0",STR_PAD_LEFT);
                $factura = "002-001-".$numero_factura;
            }else{
                $numero_factura = $value_dato['factura'];
                $numero_factura = $numero_factura + ($cantidad-1);
                $numero_factura = str_pad($numero_factura,7,"0",STR_PAD_LEFT);
                $factura = "002-001-".$numero_factura;
            }

        }

        $sql_insert = "INSERT INTO diario 
        (`id`,
        `diario`,
        `planilla`,
        `fecha`,
        `numero_de_orden`,
        `cuenta`,
        `cuenta_numero`,
        `cuenta_documento_tipo`,
        `cuenta_documento_numero`,
        `linea`,
        `contrato`,
        `documento_tipo`,
        `documento_numero`,
        `descripcion`,
        `observacion`,
        `pago_individual`,
        `aprobado`,
        `aprobado_por`,
        `cantidad`,
        `cuota`,
        `cuota_vencimiento`,
        `efectuado_fecha`,
        `efectuado_por`,
        `cuenta_bancaria_titular`,
        `cuenta_bancaria_banco`,
        `cuenta_bancaria_numero`,
        `factura_tipo`,
        `factura_numero`,
        `iva_porcentaje`,
        `iva_monto`,
        `entra`,
        `sale`,
        `derecho`,
        `obligacion`,
        `cotizacion`,
        `creado`,
        `modificado`,
        `borrado`,
        `usuario`) 
        VALUES (
        NULL,
        '".$value['diario']."',
        '".$value['planilla']."',
        '".$hoy."',
        '".$value['numero_orden']."',
        '".$value['cuenta']."',
        '".$value['cuenta_numero']."',
        '".$value['cuenta_documento_tipo']."',
       '".$value['cuenta_documento_numero']."',
       '".$value['linea']."',
        '".$value['contrato']."',
       '".$value['documento_tipo']."',
       '".$value['documento_numero']."',
        '".$descripcion_cobranza."',
        'sin datos',
        'sin datos',
        '0000-00-00 00:00:00',
        'sin datos',
        '0',
        '".$value['cuota']."',
        '".$value['cuota_vencimiento']."',
        '".$hoy."',
        'sin datos',
        'sin datos',
        'sin datos',
        'sin datos',
        'sin datos',
        '".$factura."',
        '0.00',
        '0.00',
        '0',
        '1',
        '0',
        '".$value['derecho']."',
        '0.00',
        '".$hoy."',
        '".$hoy."',
        'no',
        '".ucwords($_SESSION['usuario_en_sesion'])."')";

        /*$file = fopen('parque_add.txt', "w");
        fwrite($file, $sql_insert . PHP_EOL);
        fclose($file);*/
        //echo $sql_insert;die();
        $save = $conexion->prepare($sql_insert);
        $save->execute();

        //UPDATE FORMULARIOS;
        $factura_update = '002-001-'.$numero_factura;
        $sql_update = "UPDATE formularios SET fecha_de_uso='".$hoy."',
        cuenta='".$value['cuenta']."',
        cuenta_numero='".$value['cuenta_numero']."',
        monto = '".$valor_cheque."',
        cuenta = '".$cuenta."'
        WHERE formulario_numero='".$factura_update."' AND id>0";
        /*$file = fopen('parque_update.txt', "w");
        fwrite($file, $sql_update . PHP_EOL);
        fclose($file);*/
        $update = $conexion->prepare($sql_update);
        $update->execute();

        //UPDATE DIARIO;
        $sql_update_diario = "UPDATE diario SET efectuado_fecha='".$hoy."',
        efectuado_por = '".$_SESSION['usuario_en_sesion']."',
        factura_numero = '".$factura_update."'
        WHERE id='".$value_dato['id']."' AND id>0";
        $update_diario = $conexion->prepare($sql_update_diario);
        $update_diario->execute();

        /*$file = fopen('parque_update.txt', "w");
        fwrite($file, $sql_update . PHP_EOL);
        fclose($file);*/


    }
$mensaje = "procesado.";
}
$mensaje_2 = array("mensaje"=>$mensaje);
echo json_encode($mensaje_2);
?>