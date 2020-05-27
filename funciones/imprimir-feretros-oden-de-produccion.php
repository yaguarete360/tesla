<?php if (!isset($_SESSION)) {session_start();}

include_once('../librerias/class/tcpdf/tcpdf.php');
include_once('../librerias/class/PHPJasperXML.inc.php');
include_once ('../librerias/class/setting.php');


$PHPJasperXML = new PHPJasperXML();
$feretros_a_producir = @$_POST['feretros_a_producir'];
$usuario = $_SESSION['usuario_en_sesion'];
$hora = date("H:i:s");
$hoy = date("d/m/Y H:i:s");
$zona="en zona 10";
$query = 'SELECT dato_1 as posicion,dato_2 as feretro,dato_3 as medida,dato_4 as status,dato_7 as id FROM tmp WHERE dato_5 LIKE "orden" AND dato_6 LIKE "'.$usuario.'" ';

$PHPJasperXML->arrayParameter=array("usuario"=>$usuario,"fecha"=>$hoy,"fechaimpresion"=>$hora,"query"=>$query,"feretros_a_producir"=>$feretros_a_producir);

$PHPJasperXML->load_xml_file("../librerias/informes/feretros_orden_de_produccion.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);

if(ob_get_length() > 0 ) {
     ob_end_clean();
}
$PHPJasperXML->outpage("I");


?>