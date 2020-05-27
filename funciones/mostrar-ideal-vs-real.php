<?php if (!isset($_SESSION)) {session_start();}
include_once('../librerias/class/tcpdf/tcpdf.php');
include_once('../librerias/class/PHPJasperXML.inc.php');
include_once ('../librerias/class/setting.php');
include "../funciones/conectar-base-de-datos.php";

$PHPJasperXML = new PHPJasperXML();

$desde = @$_POST['desde'];
$hasta = @$_POST['hasta'];

$usuario = $_SESSION['usuario_en_sesion'];
$hoy = date("H:i:s");
$zona="en zona 10";
$query = 'SELECT dato_1 as pos_ideal,dato_2 as mod_ideal,dato_3 as med_ideal, dato_4 as pos_real,dato_5 as mod_real,dato_6 as med_real,dato_8 as imagen FROM 
	tmp ORDER BY id ASC';

$feretros_resultados = $conexion->prepare($query);
 $feretros_resultados->execute();
 $query_default = $feretros_resultados->fetchAll();

if(count($query_default)<=0){
	header("Location: ../vistas/reportes/feretros-comparacion_ideal_vs_real.php?desde=".$desde."&hasta=".$hasta."");
}

$PHPJasperXML->arrayParameter=array("reporte"=>5,"titulo"=>"mostrarcupon","usuario"=>$usuario,"fecha"=>$hoy,"modelo"=>$desde,"medida"=>$hasta,"fechaimpresion"=>$hoy,"query"=>$query);

$PHPJasperXML->load_xml_file("../librerias/informes/feretros_comparacion_ideal_real_horizontal.jrxml");
$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);

if(ob_get_length() > 0 ) {
     ob_end_clean();
}
$sql = "DELETE FROM tmp WHERE dato_7='".$_SESSION['usuario_en_sesion']."'";
                                                           
$stmt = $conexion->prepare($sql);
$stmt->execute();
$PHPJasperXML->outpage("I");


?>