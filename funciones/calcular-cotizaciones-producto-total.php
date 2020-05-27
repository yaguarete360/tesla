<?php if (!isset($_SESSION)) {session_start();}
include "../funciones/conectar-base-de-datos.php";

$combo=$_POST['combo'];
$desde=$_POST['desde'];
$hasta=$_POST['hasta'];

$producto_desde=$_POST['proddesde'];
$producto_hasta=$_POST['prodhasta'];


$seleccionado = $_POST['seleccionado'];


        $int = 1;
        $aux_enero = 0;
        $aux_febrero = 0;
        $aux_marzo = 0;
        $aux_abril = 0;
        $aux_mayo = 0;
        $aux_junio = 0;
        $aux_julio = 0;
        $aux_agosto = 0;
        $aux_septiembre = 0;
        $aux_octubre = 0;
        $aux_noviembre = 0;
        $aux_diciembre = 0;

        $enero=array();
        $febrero=array();
        $marzo=array();
        $abril=array();
        $mayo=array();
        $junio=array();
        $julio=array();
        $agosto=array();
        $septiembre=array();
        $octubre=array();
        $noviembre=array();
        $diciembre=array();


         $consulta_tc = 'SELECT cotizacion,fecha FROM resultados WHERE year(fecha)='.$combo;

          $query_resultados_tc = $conexion->prepare($consulta_tc);
          $query_resultados_tc->execute();

            while($rows_resultados_tc = $query_resultados_tc->fetch(PDO::FETCH_ASSOC))
           {
                $tc = (int)$rows_resultados_tc['cotizacion'];
           }            

if($seleccionado=='true')
	{


		$consulta = 'SELECT id,fecha,month(fecha) as mes,year(fecha) as anho,sum(precio) as precio,producto FROM ventas WHERE year(fecha)='.$combo.' and (month(fecha) >='.$desde.' and month(fecha) <='.$hasta.') and (producto BETWEEN "'.str_replace("_", " ", $producto_desde).'" and "'.str_replace("_", " ", $producto_hasta).'") GROUP BY producto,mes ORDER BY producto,mes';

			    $query_resultados = $conexion->prepare($consulta);
	        $query_resultados->execute();


	        while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
           {
           
             if($rows_resultados['mes']==1){
                if($aux_enero==0){
                   $aux_enero=1;
                  $enero['year']="Enero";
                }
                $enero[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==2){
                if($aux_febrero==0){
                   $aux_febrero=1;
                   $febrero['year']="Febrero";
                }
                $febrero[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==3){
                if($aux_marzo==0){
                   $aux_marzo=1;
                   $marzo['year']="Marzo";
                }
                $marzo[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==4){
                if($aux_abril==0){
                   $aux_abril=1;
                   $abril['year']="Abril";
                }
                $abril[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==5){
                if($aux_mayo==0){
                   $aux_mayo=1;
                   $mayo['year']="Mayo";
                }
                $mayo[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==6){
                if($aux_junio==0){
                   $aux_junio=1;
                   $junio['year']="Junio";
                }
                $junio[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==7){
                if($aux_julio==0){
                   $aux_julio=1;
                   $julio['year']="Julio";
                }
                $julio[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==8){
                if($aux_agosto==0){
                   $aux_agosto=1;
                   $agosto['year']="Agosto";
                }
                $agosto[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==9){
                if($aux_septiembre==0){
                   $aux_septiembre=1;
                   $septiembre['year']="Septiembre";
                }
                $septiembre[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==10){
                if($aux_octubre==0){
                   $aux_octubre=1;
                   $octubre['year']="Octubre";
                }
                $octubre[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==11){
                if($aux_noviembre==0){
                   $aux_noviembre=1;
                   $noviembre['year']="Noviembre";
                }
                $noviembre[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

            if($rows_resultados['mes']==12){
                if($aux_diciembre==0){
                   $aux_diciembre=1;
                   $diciembre['year']="Diciembre";
                }
                $diciembre[$rows_resultados['producto']]=round((($rows_resultados['precio'])/$tc)-0,0);
            }

	        }

           if(!empty($enero)){
       $json[] = $enero;
     } 

      if(!empty($febrero)){
       $json[] = $febrero;
      } 

       if(!empty($marzo)){
        $json[] = $marzo;
      }

       if(!empty($abril)){
        $json[] = $abril;
      } 

      if(!empty($mayo)){
        $json[] = $mayo;
      } 

       if(!empty($junio)){
        $json[] = $junio;
      } 

       if(!empty($julio)){
        $json[] = $julio;
      } 

       if(!empty($agosto)){
        $json[] = $agosto;
      } 

       if(!empty($septiembre)){
        $json[] = $septiembre;
      } 

       if(!empty($octubre)){
        $json[] = $octubre;
      } 

       if(!empty($noviembre)){
        $json[] = $noviembre;
      } 

       if(!empty($diciembre)){
        $json[] = $diciembre;
      } 
	}else
	{

		$consulta = 'SELECT id,fecha,month(fecha) as mes,year(fecha) as anho,sum(precio) as precio,producto FROM ventas WHERE year(fecha)='.$combo.' and (month(fecha) >='.$desde.' and month(fecha) <='.$hasta.') and (producto BETWEEN "'.str_replace("_", " ", $producto_desde).'" and "'.str_replace("_", " ", $producto_hasta).'") GROUP BY producto,mes ORDER BY producto,mes';

          $query_resultados = $conexion->prepare($consulta);
          $query_resultados->execute();


          while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
           {
           
             if($rows_resultados['mes']==1){
                if($aux_enero==0){
                   $aux_enero=1;
                  $enero['year']="Enero";
                }
                $enero[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==2){
                if($aux_febrero==0){
                   $aux_febrero=1;
                   $febrero['year']="Febrero";
                }
                $febrero[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==3){
                if($aux_marzo==0){
                   $aux_marzo=1;
                   $marzo['year']="Marzo";
                }
                $marzo[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==4){
                if($aux_abril==0){
                   $aux_abril=1;
                   $abril['year']="Abril";
                }
                $abril[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==5){
                if($aux_mayo==0){
                   $aux_mayo=1;
                   $mayo['year']="Mayo";
                }
                $mayo[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==6){
                if($aux_junio==0){
                   $aux_junio=1;
                   $junio['year']="Junio";
                }
                $junio[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==7){
                if($aux_julio==0){
                   $aux_julio=1;
                   $julio['year']="Julio";
                }
                $julio[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==8){
                if($aux_agosto==0){
                   $aux_agosto=1;
                   $agosto['year']="Agosto";
                }
                $agosto[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==9){
                if($aux_septiembre==0){
                   $aux_septiembre=1;
                   $septiembre['year']="Septiembre";
                }
                $septiembre[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==10){
                if($aux_octubre==0){
                   $aux_octubre=1;
                   $octubre['year']="Octubre";
                }
                $octubre[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==11){
                if($aux_noviembre==0){
                   $aux_noviembre=1;
                   $noviembre['year']="Noviembre";
                }
                $noviembre[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

            if($rows_resultados['mes']==12){
                if($aux_diciembre==0){
                   $aux_diciembre=1;
                   $diciembre['year']="Diciembre";
                }
                $diciembre[$rows_resultados['producto']]=$rows_resultados['precio'];
            }

          }

           if(!empty($enero)){
       $json[] = $enero;
     } 

      if(!empty($febrero)){
       $json[] = $febrero;
      } 

       if(!empty($marzo)){
        $json[] = $marzo;
      }

       if(!empty($abril)){
        $json[] = $abril;
      } 

      if(!empty($mayo)){
        $json[] = $mayo;
      } 

       if(!empty($junio)){
        $json[] = $junio;
      } 

       if(!empty($julio)){
        $json[] = $julio;
      } 

       if(!empty($agosto)){
        $json[] = $agosto;
      } 

       if(!empty($septiembre)){
        $json[] = $septiembre;
      } 

       if(!empty($octubre)){
        $json[] = $octubre;
      } 

       if(!empty($noviembre)){
        $json[] = $noviembre;
      } 

       if(!empty($diciembre)){
        $json[] = $diciembre;
      } 

	}

$json_string = json_encode($json);
echo $json_string;

?>