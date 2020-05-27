<?php if (!isset($_SESSION)) {session_start();}
include_once('../librerias/class/tcpdf/tcpdf.php');
include_once('../librerias/class/PHPJasperXML.inc.php');
include_once ('../librerias/class/setting.php');
include "../funciones/conectar-base-de-datos.php";

$PHPJasperXML = new PHPJasperXML();

$servicio = @$_POST['servicio'];
$medida = str_replace("-", ".",@$_POST['medida']);
$feretro = @$_POST['feretro'];
$descripcion_1 = @$_POST['descripcion_1'];
$descripcion_2 = @$_POST['descripcion_2'];
$serie_1=@$_POST['serie_1'];
$posicion_1 = @$_POST['posicion_1'];
$serie_2=@$_POST['serie_2'];
$posicion_2 = @$_POST['posicion_2'];
$serie_3=@$_POST['serie_3'];
$posicion_3 = @$_POST['posicion_3'];
$serie_4=@$_POST['serie_4'];
$posicion_4 = @$_POST['posicion_4'];
$serie_5=@$_POST['serie_5'];
$posicion_5 = @$_POST['posicion_5'];

$usuario = $_SESSION['usuario_en_sesion'];
$hoy = date("d/m/Y H:i:s");
$hora = date("H:i:s");
$zona="en zona 10";

$PHPJasperXML->arrayParameter=array("usuario"=>$usuario,"fecha"=>$hoy,"modelo"=>$feretro,"medida"=>$medida,"fechaimpresion"=>$hoy,"servicio"=>$servicio,"descripcion_1"=>$descripcion_1,"descripcion_2"=>$descripcion_2,"serie_1"=>strtoupper($serie_1),"posicion_1"=>strtoupper($posicion_1),"serie_2"=>strtoupper($serie_2),"posicion_2"=>strtoupper($posicion_2),"serie_3"=>strtoupper($serie_3),"posicion_3"=>strtoupper($posicion_3),"serie_4"=>strtoupper($serie_4),"posicion_4"=>strtoupper($posicion_4),"posicion_5"=>strtoupper($posicion_5),"serie_5"=>strtoupper($serie_5));

$PHPJasperXML->load_xml_file("../librerias/informes/feretros-elegir_para_un_servicio.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);

if(ob_get_length() > 0 ) {
     ob_end_clean();
}

$PHPJasperXML->outpage("I");


?>