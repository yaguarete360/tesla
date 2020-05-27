<?php if (!isset($_SESSION)) {session_start();}

echo '<hr/>Armando los origenes<hr/>';

include "../migradores/funciones/conectar-pse.php";

$destino = 'SELECT origen 
FROM  '.$tabla_de_destino.'
ORDER BY id
ASC';
$query_destino = $conexion_pse->prepare($destino);
$query_destino->execute();

$ind = 0;

echo "<br/>";

while($rows_destino = $query_destino->fetch(PDO::FETCH_ASSOC))
{			
	$control_destino[$ind] = trim(strtolower($rows_destino['origen']));	
	echo $control_destino[$ind].'<br/>';
	$vacio = "";	
	$ind++;

}

if(!isset($vacio)) echo "<br/>La tabla de destino esta vacia.<br/>";
	
?>