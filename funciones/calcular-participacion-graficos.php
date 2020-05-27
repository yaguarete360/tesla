<?php if (!isset($_SESSION)) {session_start();}				

$url = "../../";

for($ind = 0; $ind < 7; $ind++) if(isset($_SESSION['legenda_grafico_'.$ind])) $_SESSION['legenda_grafico_'.$ind] = 0;
for($ind = 0; $ind < 7; $ind++) if(isset($_SESSION['valor_grafico_'.$ind])) $_SESSION['valor_grafico_'.$ind] = 0;					
$hoy = date( "Y-m-d" );
$ayer = date( "Y-m-d", strtotime("-3 day", strtotime($hoy)));  

$_SESSION['fecha_desde'] = isset($del_dia) ? $ayer : "2015-01-01";				
$_SESSION['fecha_hasta'] = $hoy;

include $url.'funciones/conectar-base-de-datos.php';

$casos_total = ' SELECT *, 
SUM(abc) AS exequias_abc, 
SUM(uh) AS exequias_uh, 
COUNT('.$entidades.') AS casos_total
FROM operaciones_exequias
WHERE borrado = "0000-00-00"
AND fecha
BETWEEN "'.$_SESSION['fecha_desde'].'" 
AND "'.$_SESSION['fecha_hasta'].'"';

$queryTotal = $conexion->prepare($casos_total);
$queryTotal->execute();	

while($rows = $queryTotal->fetch(PDO::FETCH_ASSOC))
{
	$casos_total = $rows['casos_total'];
	$exequias_abc_total = $rows['exequias_abc'];
	$exequias_uh_total = $rows['exequias_uh'];
}

$ind = 0;

$exequias_totales = $exequias_abc_total + $exequias_uh_total;

$consulta = 'SELECT *, 
SUM(abc) AS exequias_abc, 
SUM(uh) AS exequias_uh, 
SUM(uh + abc) AS exequias,
COUNT('.$entidades.') AS casos
FROM operaciones_exequias
WHERE borrado = "0000-00-00"
AND fecha
BETWEEN "'.$_SESSION['fecha_desde'].'" 
AND "'.$_SESSION['fecha_hasta'].'" 
GROUP BY '.$entidades.'
ORDER BY '.$medidores.'
DESC';

$query = $conexion->prepare($consulta);
$query->execute();
								
while($rows = $query->fetch(PDO::FETCH_ASSOC))
{
	switch ($agrupador) 
	{
		case 'casos':
			$legenda[$ind] = substr($rows[$entidades],0,16);
			$valor[$ind] = $rows['casos'];
			$total_otros = $casos_total;
		break;		

		case 'exequias':
			$legenda[$ind] = substr($rows[$entidades],0,16);
			$valor[$ind] = $rows['exequias_uh'] + $rows['exequias_abc'];
			$total_otros = $exequias_totales;
		break;
	}
	
	$ind ++;					

}

$_SESSION['torta_titulo'] = 'Participacion por '.strtoupper($agrupador).' desde el '.
$_SESSION['fecha_desde'].' hasta el '.$_SESSION['fecha_hasta'].'.';

if($ind > 0)
{																	
	$_SESSION['valor_grafico_0'] = $valor[0];
	$_SESSION['valor_grafico_1'] = $valor[1];
	$_SESSION['valor_grafico_2'] = $valor[2];
	$_SESSION['valor_grafico_3'] = $valor[3];
	$_SESSION['valor_grafico_4'] = $valor[4];
	$_SESSION['valor_grafico_5'] = $valor[5];
	$_SESSION['valor_grafico_6'] = $valor[6];							
	$_SESSION['valor_grafico_7'] = $total_otros - (	$valor[0] + 
												$valor[1] + 
												$valor[2] + 
												$valor[3] + 
												$valor[4] + 
												$valor[5] + 
												$valor[6]
											  );
	$_SESSION['legenda_grafico_0'] = $legenda[0].' = '.number_format($_SESSION['valor_grafico_0'],0,',','.');
	$_SESSION['legenda_grafico_1'] = $legenda[1].' = '.number_format($_SESSION['valor_grafico_1'],0,',','.');
	$_SESSION['legenda_grafico_2'] = $legenda[2].' = '.number_format($_SESSION['valor_grafico_2'],0,',','.');
	$_SESSION['legenda_grafico_3'] = $legenda[3].' = '.number_format($_SESSION['valor_grafico_3'],0,',','.');
	$_SESSION['legenda_grafico_4'] = $legenda[4].' = '.number_format($_SESSION['valor_grafico_4'],0,',','.');
	$_SESSION['legenda_grafico_5'] = $legenda[5].' = '.number_format($_SESSION['valor_grafico_5'],0,',','.');
	$_SESSION['legenda_grafico_6'] = $legenda[6].' = '.number_format($_SESSION['valor_grafico_6'],0,',','.');
	$_SESSION['legenda_grafico_7'] = 'Otros = '.number_format($_SESSION['valor_grafico_7'],0,',','.');
	
	if($tipo_grafico == "columnas")
	{		
		include $url.'funciones/graficar-columnas.php';
	}
	else
	{
		include $url.'funciones/graficar-tortas.php';
	}

}
else
{
	echo '<center>';					
		echo '<h2>'.$_SESSION['titulo_pagina'].'</h2>';
		echo $_SESSION['torta_titulo'].'<br/>';
		echo '<h5>No hay datos en este periodo</h5>';
	echo '</center>';
}

?>
