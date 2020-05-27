<?php if (!isset($_SESSION)) {session_start();}

$url = "../";

include $url.'funciones/conectar-base-de-datos.php';

$client  = @$_SERVER['HTTP_CLIENT_IP'];
$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
$remote  = $_SERVER['REMOTE_ADDR'];
if(filter_var($client, FILTER_VALIDATE_IP))
{
    $direccion_ip = $client;
}
elseif(filter_var($forward, FILTER_VALIDATE_IP))
{
    $direccion_ip = $forward;
}
else
{
    $direccion_ip = $remote;
}

$consulta_seleccion = 'SELECT *
FROM visitas
WHERE borrado LIKE "no"
AND visita LIKE "'.date("Y").'%"
ORDER BY visita
DESC
LIMIT 1';

$query_seleccion = $conexion->prepare($consulta_seleccion);
$query_seleccion->execute();

while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
{
	$ultimo_numero = $rows_seleccion['visita'];
}

if(!isset($ultimo_numero))
{		
	$numero = 0;
} 		
else
{		
	$numero_partes = explode("-", $ultimo_numero);
	$numero = $numero_partes[1];
	$numero++;
}

$numero_a_usar = date("Y")."-".str_pad($numero, 7, "0", STR_PAD_LEFT);

$consulta = 'INSERT INTO visitas(
id, 
visita, 
fecha,
tipo, 
direccion_ip,
alias, 
usuario, 
creado,
borrado) 
VALUES (
0,
"'.$numero_a_usar.'", 
"'.date('Y-m-d').'", 
"logout",			 
"'.$direccion_ip.'",
"'.$_SESSION['alias_en_sesion'].'", 
"'.$_SESSION['usuario_en_sesion'].'", 
"'.date('Y-m-d h:i:s').'",
"no");';

$q=$conexion->prepare($consulta);
$q->execute(array());

session_destroy();
header('Location:../index.php');

?> 
