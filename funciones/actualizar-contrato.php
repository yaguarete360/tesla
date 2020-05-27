<?php
include 'conectar-base-de-datos.php';
$datos =  $_POST['paramName'];
$cantidad_datos = count($datos);
$mensaje = "no";
$contrato = null;
/*****************UPDATE CONTRATO*************/
foreach ($datos as $value_dato){

    //UPDATE CONTRATO TITULAR
    $contrato = $value_dato['contrato'];
    $consulta = "UPDATE contratos 
                 SET cuenta = '".$value_dato['cuenta']."',
                 cuotas_cantidad = '".$value_dato['cuotas']."',
                 fecha = '".$value_dato['fecha']."',
                 cuenta_direccion_particular = '".$value_dato['cuenta_direccion_particular']."',
                 cuenta_documento_numero = '".$value_dato['cuenta_documento_numero']."',
                 cuenta_numero = '".$value_dato['cuenta_numero']."',
                 forma_de_pago = '".$value_dato['forma_de_pago']."',
                 cobrador_nombre = '".$value_dato['cobrador']."',
                 cuenta_particular_barrio = '".$value_dato['cuenta_particular_barrio']."',
                 cuenta_direccion_laboral = '".$value_dato['cuenta_direccion_laboral']."',
                 entrega_inicial = ".$value_dato['entrega_inicial'].",
                 monto_diferido = ".$value_dato['monto_diferido'].",
                 sitio_emprendimiento = ".$value_dato['sitio_emprendimiento'].",
                 sitio_linea = ".$value_dato['sitio_linea'].",
                 sitio_area = ".$value_dato['sitio_area'].",
                 sitio_sendero = ".$value_dato['sitio_sendero'].",
                 sitio_numero = ".$value_dato['sitio_numero']."
                 WHERE contrato = '".$value_dato['contrato']."'";
    /*$file = fopen('AAAAAA.txt', "w");
    fwrite($file, $consulta . PHP_EOL);
    fclose($file);*/
    $query_resultados = $conexion->prepare($consulta);
    $query_resultados->execute();
    $mensaje = "ok";
    //UPDATE BENEFICIARIOS
    for ($int=0;$int<count($value_dato['beneficiarios']);$int++) {
        $beneficiario_id = $value_dato['beneficiarios'][$int]['id'];
        $beneficiario_nombre = $value_dato['beneficiarios'][$int]['nombre'];
        $beneficiario_documento_tipo = $value_dato['beneficiarios'][$int]['ci'];
        $beneficiario_documento_numero = $value_dato['beneficiarios'][$int]['documento'];
        $beneficiario_sexo = $value_dato['beneficiarios'][$int]['sexo'];
        $beneficiario_estado_civil = $value_dato['beneficiarios'][$int]['estado_civil'];
        $beneficiario_nacimiento = $value_dato['beneficiarios'][$int]['nacimiento'];
        $beneficiario_vigencia = $value_dato['beneficiarios'][$int]['vigencia'];
        $beneficiario_edad = $value_dato['beneficiarios'][$int]['edad'];
        $beneficiario_cuota = $value_dato['beneficiarios'][$int]['cuota'];

        $update = "UPDATE contratos 
                 SET beneficiario = '".$beneficiario_nombre."',
                 beneficiario_documento_tipo = '".$beneficiario_documento_tipo."',
                 beneficiario_documento_numero = '".$beneficiario_documento_numero."',
                 beneficiario_nacimiento = '".$beneficiario_nacimiento."',
                 beneficiario_vigencia = '".$beneficiario_vigencia."',
                 beneficiario_sexo = '".$beneficiario_sexo."',
                 beneficiario_estado_civil = '".$beneficiario_estado_civil."',
                 beneficiario_edad = '".$beneficiario_edad."',
                 cuota_monto = '".$beneficiario_cuota."'
                 WHERE id = '".$beneficiario_id."'";
        /*$file = fopen('AAAAA3.txt', "w");
        fwrite($file, $update . PHP_EOL);
        fclose($file);*/
        $query_resultados_update = $conexion->prepare($update);
        $query_resultados_update->execute();
    }

}
$contrato_array = array("mensaje"=>$mensaje,"contrato"=>$contrato);
echo json_encode($contrato_array);

?>