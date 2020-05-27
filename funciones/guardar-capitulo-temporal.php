<?php if (!isset($_SESSION)) {session_start();}


 include 'conectar-base-de-datos.php';
 $conexion->exec('USE capitulos');

 $fecha = $_POST['fecha'];
 $cobrador = $_POST['cobrador'];
 $monto = $_POST['monto'];
 

 $guardo ="no";
 $insertar = "INSERT INTO
 			  `capitulo7_temporal`
 			  (`id`,
 			  `fecha`,
 			  `cobrador_nombre`,
 			  `monto`) 
 			  VALUES 
 			  (NULL,
 			  '".$fecha."',
 			  '".$cobrador."',
 			  ".$monto.")";  


$query = $conexion->prepare($insertar);
$query->execute();
$guardo ="si";

$resultado = array();
$head['resultado'] = $guardo;
array_push($resultado, $head);

 echo  json_encode($resultado); 


?>