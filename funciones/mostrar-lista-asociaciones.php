<?php if (!isset($_SESSION)) {session_start();}

include_once('../librerias/class/tcpdf/tcpdf.php');
include_once('../librerias/class/PHPJasperXML.inc.php');
include_once ('../librerias/class/setting.php');


$PHPJasperXML = new PHPJasperXML();
$asociacion = @$_POST['asociacion'];

$usuario = $_SESSION['usuario_en_sesion'];
$hoy = date("H:i:s");
$fecha = date("d/m/Y H:i:s");
if($asociacion=="todos"){
	$query = 'SELECT proper(cuenta) as funcionario,documento_tipo as tipo, documento_numero as numero FROM cuentas WHERE borrado LIKE "no" AND asociacion>0 GROUP BY cuenta ORDER BY cuenta';
}else{
	$query = 'SELECT proper(cuenta) as funcionario,documento_tipo as tipo, documento_numero as numero FROM cuentas WHERE borrado LIKE "no" AND cuenta LIKE "%'.str_replace("_", " ", $asociacion).'%" AND asociacion>0 GROUP BY cuenta ORDER BY cuenta';
}

$PHPJasperXML->arrayParameter=array("usuario"=>$usuario,"fecha"=>$fecha,"fechaimpresion"=>$hoy,"query"=>$query);

$PHPJasperXML->load_xml_file("../librerias/informes/listado_asociaciones.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);

if(ob_get_length() > 0 ) {
     ob_end_clean();
}
$PHPJasperXML->outpage("I");


?>