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
$sql = "(SELECT 
            id as id,
            fecha as fecha,
            producto as producto,
            precio as precio,
            producto as contrato,
            producto as contrato_nro,
            cliente as field2,
            precio as psi,
            documento_numero as psc
            FROM ventas
            WHERE borrado LIKE 'no'
            AND fecha LIKE '".$fecha."%' ORDER BY producto asc) 
            UNION 
            (SELECT 
            id as id,
            fecha as fecha,
            producto as producto,
            monto_lista as precio,
            codigo as contrato,
            codigo as contrato_nro,
            difunto as field2,
            monto_lista as psi,
            id as psc
            FROM difuntos
            WHERE borrado LIKE 'no'
            AND fecha LIKE '".$fecha."' ORDER BY contrato_nro asc)";
//echo $sql;die();
$query_resultados = $conexion->prepare($sql);
$result = $query_resultados->execute();
$parameter1 = "";
$parameter2 = "";
$parameter3 = "";
$parameter4 = "";
$parameter5 = "";
$parameter6 = "";
$parameter7 = "";
$parameter8 = "";

$query_borrar = $conexion->prepare("DELETE FROM tmp_ventas 
WHERE usuario='".$_SESSION['usuario_en_sesion']."'");
$query_borrar->execute();

foreach ($query_resultados as $value) {
    $parameter4 = $value['id'];
    $parameter1= $value['id'];
    $parameter3= number_format($value['id']);
    $parameter6= $value['id'];
    $parameter5= $value['id'];
    $parameter7= $value['id'];

    $sql_insert = "INSERT INTO `tmp_ventas` 
    (`id`, `fecha`, `producto`, `documento`, `monto`, `usuario`,`contrato`,`titular`) 
    VALUES (NULL, '".$value['fecha']."', '".$value['producto']."', '".$value['psc']."',
     '".$value['psi']."', '".$_SESSION['usuario_en_sesion']."','".$value['contrato_nro']."',
     '".$value['field2']."')";
    //echo $sql_insert;die();
    $query_insert = $conexion->prepare($sql_insert);
    $query_insert->execute();
}
$parameter8= "Pag.";
$sql = "SELECT 
        contrato as contrato_nro,
        contrato as contrato,
        documento as psc,
        monto as psi,
        titular as field2
        FROM tmp_ventas 
        WHERE usuario = '".$_SESSION['usuario_en_sesion']."' ORDER BY contrato";
/*************************************************************************/

/*************CONFIGURACION PAR QUE OCURRA LA MAGIA*******/
$PHPJasperXML = new PHPJasperXML();
    // "parameter2"=>date("Y-m-d"),
$PHPJasperXML->arrayParameter=array("query"=>$sql,
    "parameter1"=>$parameter1,
    "parameter2"=>$fecha,
    "parameter3"=>$parameter3,
    "parameter4"=>$parameter4,
    "parameter5"=>$parameter5,
    "parameter6"=>$parameter6,
    "parameter7"=>$parameter7,
    "parameter8"=>$parameter8);
$PHPJasperXML->load_xml_file("ventas_de_dia.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
ob_end_clean();
$PHPJasperXML->outpage("I");
/*********************************************************/

?>