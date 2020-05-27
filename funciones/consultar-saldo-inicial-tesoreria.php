<?php if (!isset($_SESSION)) {session_start();}

include "../../funciones/conectar-base-de-datos.php";

$consulta_cantidad_datos = 'SELECT clasificacion 
						   FROM tesorerias';
$query_resultados_cantidad_datos = $conexion->prepare($consulta_cantidad_datos);
$query_resultados_cantidad_datos->execute();
$cantidad_datos = $query_resultados_cantidad_datos->fetchAll();

if(isset($_SESSION['fecha'])){
    $hoy=$_SESSION['fecha'];
}else{
	$hoy=date('Y-m-d');
}

if($clasificacion=="cobradores internos en efectivo")
{
 $zona = 1;
}

if($clasificacion=="cobradores internos en cheques diferidos")
{
 $zona = 2;
}

if($clasificacion=="cobradores internos en tarjetas")
{
 $zona = 3;
}

if($clasificacion=="cobradores externos")
{
 $zona = 4;
}

if($clasificacion=="bancos")
{
 $zona = 5;
}

if($clasificacion=="facturacion")
{
 $zona = 6;
}

if($clasificacion=="cuentas por cobrar")
{
 $zona = 7;
}

$anho=date('Y');
$consulta_saldo_inicial = 'SELECT clasificacion 
						   FROM tesorerias 
						   WHERE cobrador_nombre LIKE "%'.$descripcion_saldo_inicial.'%" 
						   AND clasificacion LIKE "%'.$clasificacion.'%"
						   AND fecha="'.$hoy.'"
						   AND borrado LIKE "no"';
$query_resultados = $conexion->prepare($consulta_saldo_inicial);
$query_resultados->execute();
$saldo_inicial = $query_resultados->fetchAll();
$cantidad = count($saldo_inicial);

if($cantidad<=0)
{
	$ultimo_saldo = 'SELECT fecha,clasificacion,cobrador_nombre,saldo_posterior 
						   FROM tesorerias 
						   WHERE cobrador_nombre LIKE "%'.$descripcion_saldo_final.'%" 
						   AND clasificacion LIKE "%'.$clasificacion.'%" 
						   AND borrado LIKE "no"
						   ORDER BY fecha desc LIMIT 1';
	$query_resultados_final = $conexion->prepare($ultimo_saldo);
    $query_resultados_final->execute();
    $saldo_final = $query_resultados_final->fetchAll();
    $cantidad_saldo_final = count($saldo_final);					   
    
    if($cantidad_saldo_final<=0)
	{
		$saldo_final=0;
	}else
	{
		for ($int=0; $int<$cantidad_saldo_final;$int++) {
			$saldo_final = $saldo_final[$int]['saldo_posterior'];
		}
	}
    
	if($cantidad_teso<=0)
	{	
		$cantidad_teso = $cantidad_teso+1;
	}
	
	$tesoreria=$anho.'-'.str_pad($cantidad_teso, 7,"0",STR_PAD_LEFT);
	$sql = "INSERT INTO tesorerias (id, tesoreria, fecha, cobrador_nombre, saldo_posterior, entra, sale, saldo_anterior, clasificacion, zona, posicion, usuario, creado, modificado, borrado) VALUES (0, '".$tesoreria."', '".$hoy."','".$descripcion_saldo_inicial."', '0.00', '0.00', 0,".$saldo_final.", '".$clasificacion."', ".$zona.", '1', '".$_SESSION['usuario_en_sesion']."', '".$hoy."', '".$hoy."', 'no');";
	$stmt = $conexion->prepare($sql);
    $stmt->execute(); 	

    $tesoreria=$anho.'-'.str_pad(($cantidad_teso+1), 7,"0",STR_PAD_LEFT);
	$sql = "INSERT INTO tesorerias (id, tesoreria, fecha, cobrador_nombre, saldo_posterior, entra, sale, saldo_anterior, clasificacion, zona, posicion, usuario, creado, modificado, borrado) VALUES (0, '".$tesoreria."', '".$hoy."','".$descripcion_saldo_final."', '0.00', '0.00', 0,0, '".$clasificacion."', ".$zona.", '3', '".$_SESSION['usuario_en_sesion']."', '".$hoy."', '".$hoy."', 'no');";
	$stmt = $conexion->prepare($sql);
    $stmt->execute(); 	


}


?>