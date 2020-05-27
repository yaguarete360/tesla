<?php if (!isset($_SESSION)) {session_start();}
include "../funciones/conectar-base-de-datos.php";

$combo=$_POST['combo'];
$seleccionado = $_POST['seleccionado'];

if($seleccionado=='true')
	{

		$consulta = 'SELECT *                                    
                FROM resultados
                WHERE borrado LIKE "no"
                ORDER BY resultado DESC LIMIT '.$combo.'';

			$query_resultados = $conexion->prepare($consulta);
	        $query_resultados->execute();
	       
	        while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
           {
           	  $pasivo = ($rows_resultados['pasivo'])/$rows_resultados['cotizacion'];
           	  $patrimonio = ($rows_resultados['activo']-$rows_resultados['pasivo'])/$rows_resultados['cotizacion'];	
           	  $venta = ($rows_resultados['venta'])/$rows_resultados['cotizacion'];
           	  $utilidad = ($rows_resultados['venta']-$rows_resultados['costo'])/$rows_resultados['cotizacion'];
           	  $rinde = ($rows_resultados['rinde'])/$rows_resultados['cotizacion'];
           	  $efectivo = ($rows_resultados['ingreso']-$rows_resultados['egreso'])/$rows_resultados['cotizacion'];

	          $json[] = array("year"=>$rows_resultados['resultado'] ,
                        "pasivo" => round($pasivo-0,0),
                        "patrimonio" => round($patrimonio-0,0),
                        "venta" => round($venta-0,0),
                        "utilidad"=>round($utilidad-0,0),
                        "rinde"=>round($rinde-0,0),
                        "efectivo"=>round($efectivo-0,0));
	        }
	}else
	{

		$consulta = 'SELECT *                                    
                FROM resultados
                WHERE borrado LIKE "no"
                ORDER BY resultado DESC LIMIT '.$combo.'';

			$query_resultados = $conexion->prepare($consulta);
	        $query_resultados->execute();
	       
	        while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
           {

	          $json[] = array("year"=>$rows_resultados['resultado'] ,
                        "pasivo" => round($rows_resultados['pasivo']-0,0),
                        "patrimonio" => round($rows_resultados['activo']-$rows_resultados['pasivo'],0),
                        "venta" => round($rows_resultados['venta']-0,0),
                        "utilidad"=>round($rows_resultados['venta']-$rows_resultados['costo'],0),
                        "rinde"=>round($rows_resultados['rinde']-0,0),
                        "efectivo"=>round($rows_resultados['ingreso']-$rows_resultados['egreso'],0));
	       }
	}

$json_string = json_encode($json);
echo $json_string;

?>