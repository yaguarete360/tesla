<?php if (!isset($_SESSION)) {session_start();}


 include 'conectar-base-de-datos.php';
 $conexion->exec('USE tablas_vigentes');

 $fecha = $_POST['fecha'];
 $uno = $_POST['cotizacion_segun_pdf'];
 $dos = $_POST['saldo_u_d_segun_pdf'];
 $tres = $_POST['diferencia_entre_cotiz_'];
 $cuatro = $_POST['diferencia_gs__entre_dias'];
 $cinco = $_POST['diferencia_entre_saldo_usd'];

 $guardo ="no";
 $insertar = "INSERT INTO
 			  `cotizacion_tesoreria`
 			  (`id`,
 			  `fecha`,
 			  `cotizacion_segun_pdf`,
 			  `saldo_u_d_segun_pdf`,
        `diferencia_entre_cotiz_`,
        `diferencia_gs__entre_dias`,
        `diferencia_entre_saldo_usd`)
 			  VALUES
 			  (NULL,
 			  '".$fecha."',
 			  ".$uno.",
 			  ".$dos.",
         ".$tres.",
         ".$cuatro.",
         ".$cinco.")";


$query = $conexion->prepare($insertar);
$query->execute();
$guardo ="si";

$resultado = array();
$head['resultado'] = $guardo;
array_push($resultado, $head);

 echo  json_encode($resultado);


?>
