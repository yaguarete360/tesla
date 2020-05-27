<?php
error_reporting(0);
include 'conectar-base-datos-ocean.php';
$contrato_fecha_inicio = $_POST['fecha_inicio_contrato'];
$contrato_fecha_fin = $_POST['fecha_fin_contrato'];
$query = "
SELECT DATE_FORMAT(cap1.fecha,'%d-%m-%Y') AS FECHA,(sum(cap1.sale)*-1) AS SALIDA,(select cap2.saldo 
FROM capitulos.capitulo5 as cap2 where cap2.fecha = cap1.fecha order by cap2.id desc limit 1) as SALDO 
FROM capitulos.capitulo5 as cap1 where cap1.fecha BETWEEN '$contrato_fecha_inicio' 
AND '$contrato_fecha_fin' group by cap1.fecha ORDER BY cap1.fecha asc ";
//echo $query;
$result = mysqli_query($con,$query) or die(mysqli_error());
$parent = array() ;

$saldo = array();
$contador = 0;
	while($row = mysqli_fetch_array($result))
	{
               
                        $parent[] = array(
                                "fecha"=>$row['FECHA'],
                                "salida"=>$row['SALIDA'],
                                "saldo"=>$row['SALDO']
                                
        
                       );          
                
               
	}
     echo  json_encode($parent); 
     
?>
