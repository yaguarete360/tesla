<?php if (!isset($_SESSION)) {session_start();}

include_once('../librerias/class/tcpdf/tcpdf.php');
include_once('../librerias/class/PHPJasperXML.inc.php');
include_once ('../librerias/class/setting.php');


$PHPJasperXML = new PHPJasperXML();
$modelo = @$_POST['modelo'];
$medida = @$_POST['medida'];
$usuario = $_SESSION['usuario_en_sesion'];
$hoy = date("H:i:s");
$zona="en zona 10";
$query = 'SELECT fecha as fecha,trim(posicion_zona_10) as posicion,feretro as feretro,serie as serie,medida as medida, status as status,entrada_zona_10 as entrada FROM feretros WHERE feretro="'.$modelo.'" AND medida="'.$medida.'" AND status like "%'.$zona.'%" LIMIT 1000';

$PHPJasperXML->arrayParameter=array("reporte"=>5,"titulo"=>"mostrarcupon","usuario"=>$usuario,"fecha"=>"24/02/2019","modelo"=>$modelo,"medida"=>$medida,"fechaimpresion"=>$hoy,"query"=>$query);


$PHPJasperXML->load_xml_file("../librerias/informes/feretros_en_zona_10.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
if(ob_get_length() > 0 ) {
     ob_end_clean();
}
$PHPJasperXML->outpage("I");


?>