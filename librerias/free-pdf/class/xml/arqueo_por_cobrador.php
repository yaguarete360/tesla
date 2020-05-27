<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('../tcpdf/tcpdf.php');
include_once("../PHPJasperXML.inc.php");
include_once ('../setting.php');
include_once ('../../../../funciones/conectar-base-de-datos.php');

$fecha = $_SESSION['set_fecha_venta'];
$sql = "SELECT
        id as id,
        custodia_3_entrega_funcionario as contrato_nro,
        monto as psc,
        formulario_numero as field2,
        if(forma_de_pago_1_tipo='efectivo','efectivo',forma_de_pago_1_tipo) as field3,
        if(forma_de_pago_1_tipo='efectivo','efectivo',forma_de_pago_1_entidad) as field4,
        if(forma_de_pago_1_tipo='efectivo','efectivo',forma_de_pago_1_numero) as field5
        FROM formularios
        WHERE borrado LIKE 'no'
        AND formulario LIKE 'factura'
        AND fecha_de_uso LIKE '".$fecha."'
        AND custodia_3_entrega_funcionario LIKE '".$_SESSION['set_cobrador']."'
        GROUP BY formulario_numero
        ORDER BY custodia_3_entrega_funcionario,formulario_numero";
//echo $sql;die();
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
    $parameter4 = $value['id'];
    $parameter1= $value['id'];
    $parameter3= number_format($value['id']);
    $parameter6= $value['id'];
    $parameter5= $value['id'];
    $parameter7= $value['id'];
    $parameter8= $value['id'];
}
$parameter7 = "TOTAL: ";

//$sql = "SELECT * FROM organigrama limit 10";
/*************************************************************************/

/*************CONFIGURACION PAR QUE OCURRA LA MAGIA*******/
$PHPJasperXML = new PHPJasperXML();
$PHPJasperXML->arrayParameter=array("query"=>$sql,
    "parameter1"=>$parameter1,
    "parameter2"=>$fecha,
    "parameter3"=>$parameter3,
    "parameter4"=>$parameter4,
    "parameter5"=>$parameter5,
    "parameter6"=>$parameter6,
    "parameter7"=>$parameter7,
    "parameter8"=>$parameter8);
$PHPJasperXML->load_xml_file("arque_caja_cobrador.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
ob_end_clean();
$PHPJasperXML->outpage("I");
/*********************************************************/

?>