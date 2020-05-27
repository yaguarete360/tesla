<?php if (!isset($_SESSION)) {session_start();}


 include 'conectar-base-de-datos.php';
 $conexion->exec('USE capitulos');

 $id = $_POST['id'];

$guardo ="no";
$delete = "DELETE FROM capitulo7_temporal WHERE id=".$id;  
$query = $conexion->prepare($delete);
$query->execute();
$guardo ="si";

$resultado = array();
$head['resultado'] = $guardo;
array_push($resultado, $head);

 echo  json_encode($resultado); 


?>