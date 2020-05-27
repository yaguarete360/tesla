<?php if (!isset($_SESSION)) {session_start();}

include_once('../librerias/class/tcpdf/tcpdf.php');
include_once('../librerias/class/PHPJasperXML.inc.php');
include_once ('../librerias/class/setting.php');


$PHPJasperXML = new PHPJasperXML();
$uuid = @$_GET['uuid'];
$query = "SELECT CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido) AS nombre,cedula as documento,uuid as id FROM actualizaciones WHERE uuid='".$uuid."'";

$PHPJasperXML->arrayParameter=array("parameter1"=>1,"titulo"=>"mostrarcupon","query"=>$query);

$PHPJasperXML->load_xml_file("../librerias/class/report1.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
if(ob_get_length() > 0 ) {
     ob_end_clean();
}
$PHPJasperXML->outpage("I");


?>
