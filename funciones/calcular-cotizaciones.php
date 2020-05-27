<?php if (!isset($_SESSION)) {session_start();}
include "../funciones/conectar-base-de-datos.php";

$tc2 = $_POST['tc'];
$variable=$_POST['variable'];
$variable=str_replace("-", " ", $variable);
$combo=$_POST['combo'];
$seleccionado = $_POST['seleccionado'];

if($seleccionado=='true')
	{

		$consulta = 'SELECT *                                    
	        FROM estadisticas
	        WHERE borrado LIKE "no"
	        AND estadistica LIKE "%'.$variable.'%"
	        ORDER BY ano DESC LIMIT '.$combo.'';

			$query_resultados = $conexion->prepare($consulta);
	        $query_resultados->execute();
	       
	        while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
	        {

	        	$ope2 = "SELECT * from estadisticas where tipo like '%cotizaciones%' and ano =".$rows_resultados['ano']." group by ano";
				
				$query_resultados_tc = $conexion->prepare($ope2);
	        	$query_resultados_tc->execute();

				 while($rows_resultados_tc = $query_resultados_tc->fetch(PDO::FETCH_ASSOC))
	        	 {

					$tc = $rows_resultados_tc['cantidad'];
				 }

				 $valor = ($rows_resultados['cantidad'])/$tc;

	          $json[] = array("year"     => $rows_resultados['ano'] ,
	                          "cantidad" => round(($valor-0),0));
	        }
	}else
	{

		$consulta = 'SELECT *                                    
	        FROM estadisticas
	        WHERE borrado LIKE "no"
	        AND estadistica LIKE "%'.$variable.'%"
	        ORDER BY ano DESC LIMIT '.$combo.'';

			$query_resultados = $conexion->prepare($consulta);
	        $query_resultados->execute();
	       
	        while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
	        {
	          $json[] = array("year"     => $rows_resultados['ano'] ,
	                          "cantidad" => round($rows_resultados['cantidad']-0,0));
	        }

	}


$json_string = json_encode($json);
echo $json_string;

?>