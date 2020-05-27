<?php
include 'conectar-base-de-datos.php';
$contrato = $_POST['contrato'];
$contrato = str_pad($contrato,7,"0",STR_PAD_LEFT);
$tipo_documento = $_POST['tipo_documento'];
$tipo_documento = explode(" ",$tipo_documento);
$tipo_documento = $tipo_documento[1];
$linea = $_POST['linea'];
$documento = $linea."-".$tipo_documento."-".$contrato;

/*****************BUSCAR CONTRATO*************/
$consulta = "SELECT * 
                 FROM contratos 
                 WHERE contrato = '".$documento."' AND 
                 borrado ='no' GROUP BY contrato";

$query_resultados = $conexion->prepare($consulta);
$query_resultados->execute();
$resultado = $query_resultados->fetchAll();
$contrato_array = array();
$contrato_array['contrato'] = array();

$head = array();
$detail = array();
$cabezera = 0;
foreach ($resultado as $value){
    $head = array();
    $head['contrato'] = $value['contrato'];
    $head['fecha'] = $value['fecha'];
    $head['entrega_inicial'] = $value['entrega_inicial'];
    $head['monto_diferido'] = $value['monto_diferido'];
    $head['cuenta_numero'] = $value['cuenta_numero'];
    $head['cuenta'] = $value['cuenta'];
    $head['cuenta_documento_numero'] = $value['cuenta_documento_numero'];
    $head['cuenta_direccion_particular'] = $value['cuenta_direccion_particular'];
    $head['producto'] = $value['producto'];
    $head['cuotas_cantidad'] = $value['cuotas_cantidad'];
    $head['cuota_monto'] = $value['cuota_monto'];
    $head['cobrador_nombre'] = $value['cobrador_nombre'];
    $head['forma_de_pago'] = $value['forma_de_pago'];
    //$head['cuotas'] = $value['cuotas_cantidad'];
    $head['cuenta_particular_barrio'] = $value['cuenta_particular_barrio'];
    $head['cuenta_direccion_laboral'] = $value['cuenta_direccion_laboral'];
    $head['detalles'] = array();

    $consulta_detail = "SELECT * 
                 FROM contratos 
                 WHERE contrato = '".$documento."' AND 
                 borrado ='no'";

    $query_resultados_detail = $conexion->prepare($consulta_detail);
    $query_resultados_detail->execute();
    $resultado_detail = $query_resultados_detail->fetchAll();
    foreach ($resultado_detail as $value_detail){
        $detail = array();
        $detail['id'] = $value_detail['id'];
        $detail['beneficiario'] = $value_detail['beneficiario'];
        $detail['beneficiario_documento_tipo'] = $value_detail['beneficiario_documento_tipo'];
        $detail['beneficiario_documento_numero'] = $value_detail['beneficiario_documento_numero'];
        $detail['beneficiario_nacimiento'] = $value_detail['beneficiario_nacimiento'];
        $detail['beneficiario_sexo'] = $value_detail['beneficiario_sexo'];
        $detail['beneficiario_estado_civil'] = $value_detail['beneficiario_estado_civil'];
        $detail['beneficiario_edad'] = $value_detail['beneficiario_edad'];
        $detail['cuota_monto'] = $value_detail['cuota_monto'];
        array_push($head['detalles'],$detail);
    }
    array_push($contrato_array['contrato'],$head);
}

echo json_encode($contrato_array);

?>