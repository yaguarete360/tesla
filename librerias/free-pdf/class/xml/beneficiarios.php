<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('../tcpdf/tcpdf.php');
include_once("../PHPJasperXML.inc.php");
include_once ('../setting.php');
include_once ('../../../../funciones/conectar-base-de-datos.php');

//$_SESSION['set_cuenta'] = "cueto laschi, julio benigno";
$cuenta = $_SESSION['set_cuenta'];
$sql = "SELECT 
        CONCAT(1) as field2,
        UPPER(beneficiario) as vendedor,
        beneficiario_documento_numero as psm,
        beneficiario_nacimiento as uds,
        beneficiario_edad as psv,
        beneficiario_vigencia as psc,
        cuota_monto as psi,
        UPPER(contrato) as contrato,
        UPPER(cuenta_numero) as cuenta_numero,
        fecha as fecha,
        UPPER(producto) as producto,
        UPPER(cobrador_nombre) as cobrador_nombre,
        UPPER(vendedor) as vendedor_nombre
        FROM `contratos`  
        WHERE 
        borrado LIKE 'no' AND
        cuenta LIKE '%".$cuenta."%' AND 
        cuenta IS NOT NULL AND
        cuenta NOT LIKE '%no aplicable%' AND 
        cuenta NOT LIKE '%sin datos%' AND
        beneficiario NOT LIKE '%no aplicable%' AND 
        beneficiario NOT LIKE '%sin datos%' AND
        contrato NOT LIKE '%-var-%' AND
        estado='vigente'
        ORDER BY cuenta";

$query_resultados = $conexion->prepare($sql);
$query_resultados->execute();

$parameter1 = "";
$parameter2 = "";
$parameter3 = "";
$parameter4 = "";
$parameter5 = "";
$parameter6 = "";
$parameter7 = "";
$parameter8 = "";

foreach ($query_resultados as $value) {
    $parameter4 = $value['vendedor'];
    $parameter1= $value['contrato'];
    $parameter3= number_format($value['cuenta_numero']);
    $parameter6= $value['fecha'];
    $parameter5= $value['producto'];
    $parameter7= $value['cobrador_nombre'];
    $parameter8= $value['vendedor_nombre'];
}

//$sql = "SELECT * FROM organigrama limit 10";
/*************************************************************************/

/*************CONFIGURACION PAR QUE OCURRA LA MAGIA*******/
$PHPJasperXML = new PHPJasperXML();
$PHPJasperXML->arrayParameter=array("query"=>$sql,
    "parameter1"=>$parameter1,
    "parameter2"=>date("Y-m-d"),
    "parameter3"=>$parameter3,
    "parameter4"=>$parameter4,
    "parameter5"=>$parameter5,
    "parameter6"=>$parameter6,
    "parameter7"=>$parameter7,
    "parameter8"=>$parameter8);
$PHPJasperXML->load_xml_file("estado_de_cuenta_beneficiario.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
ob_end_clean();
$PHPJasperXML->outpage("I");
/*********************************************************/

?>