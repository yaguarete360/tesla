<?php if (!isset($_SESSION)) {session_start();}

$acceso = "no";

$permiso = explode("_",$menu);
if(isset($permiso[1]))
{
	$permiso = $accion.'-'.str_replace(".php","",$permiso[0].'-'.$permiso[1]);
}
else
{
	$permiso = $accion.'-'.str_replace(".php","",$permiso[0]);
}

include "../../funciones/conectar-base-de-datos.php";

$consulta = 'SELECT * 
FROM auxiliares_permisos
WHERE alias LIKE "'.$_SESSION['alias_en_sesion'].'"
AND permiso LIKE "'.$permiso.'"
ORDER BY permiso';

$query = $conexion->prepare($consulta);
$query->execute();

while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
	$acceso = "si";
}
 
?>
