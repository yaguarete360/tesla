<?php if (!isset($_SESSION)) {session_start();}

include "../../funciones/conectar-base-de-datos.php";

if(isset($_SESSION['fecha'])){
    $hoy=$_SESSION['fecha'];
}else{
	$hoy=date('Y-m-d');
}


$anho=date('Y');
$consulta_saldo_final = 'SELECT (SUM( (saldo_anterior+entra)-sale)) AS saldo 
						 FROM tesorerias 
						 WHERE clasificacion LIKE "%'.$clasificacion.'%" 
						 AND fecha="'.$hoy.'" 
						 AND borrado LIKE "%no%" 
						 GROUP BY clasificacion';

$query_resultados_saldo_final = $conexion->prepare($consulta_saldo_final);
$query_resultados_saldo_final->execute();
$saldo_final = $query_resultados_saldo_final->fetchAll();
$contador_saldo_final = count($saldo_final);
for($int=0;$int<$contador_saldo_final;$int++)
{
	$valor = $saldo_final[$int]['saldo'];
}

$sql = 'UPDATE tesorerias 
		SET saldo_posterior='.$valor.'
		WHERE clasificacion LIKE "%'.$clasificacion.'%" 
		AND cobrador_nombre LIKE "%'.$descripcion_saldo_final.'%" 
		AND fecha="'.$hoy.'"';

	$stmt = $conexion->prepare($sql);
    $stmt->execute(); 	
	//	echo $sql;

?>