<?php if (!isset($_SESSION)) {session_start();}
include "../funciones/conectar-base-de-datos.php";

$combo=$_POST['combo'];
$desde=$_POST['desde'];
$hasta=$_POST['hasta'];

$vendedor_desde=$_POST['proddesde'];
$vendedor_hasta=$_POST['prodhasta'];


$seleccionado = $_POST['seleccionado'];


        $int = 1;
        $aux_enero = 0;
       

        $enero=array();
       


         $consulta_tc = 'SELECT cotizacion,fecha FROM resultados WHERE year(fecha)='.$combo;

          $query_resultados_tc = $conexion->prepare($consulta_tc);
          $query_resultados_tc->execute();

            while($rows_resultados_tc = $query_resultados_tc->fetch(PDO::FETCH_ASSOC))
           {
                $tc = (int)$rows_resultados_tc['cotizacion'];
           }            

if($seleccionado=='true')
	{


		$consulta = 'SELECT sum(precio) as sum_cemen, producto as producto,vendedor as vendedor FROM ventas WHERE (fecha >="'.$desde.'" and fecha <="'.$hasta.'") GROUP BY producto ORDER BY sum_cemen desc';

			    $query_resultados = $conexion->prepare($consulta);
	        $query_resultados->execute();


	        while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
           {
              
               $valor = ($rows_resultados['sum_cemen'] - 0);

          
               if($valor<=$vendedor_desde && $valor>=$vendedor_hasta){

                   $prod = ($rows_resultados['sum_cemen']-0);
                   $json[] = array("year"=>$rows_resultados['producto'],
                        "producto" => round(($prod/$tc),0) );

               }
             

	        }

         
	}else
	{

	   $consulta = 'SELECT sum(precio) as sum_cemen, producto as producto,vendedor as vendedor FROM ventas WHERE (fecha >="'.$desde.'" and fecha <="'.$hasta.'") GROUP BY producto ORDER BY sum_cemen desc';

          $query_resultados = $conexion->prepare($consulta);
          $query_resultados->execute();


          while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
           {
              
               $valor = ($rows_resultados['sum_cemen'] - 0);

          
               if($valor<=$vendedor_desde && $valor>=$vendedor_hasta){

                   $json[] = array("year"=>$rows_resultados['producto'],
                        "producto" => round($rows_resultados['sum_cemen']-0,0));

               }
             

          }


  }

     
	
$json_string = json_encode($json);
echo $json_string;

?>