<?php if (!isset($_SESSION)) {session_start();}
include "../funciones/conectar-base-de-datos.php";

$combo=$_POST['combo'];
$desde=$_POST['desde'];
$hasta=$_POST['hasta'];

$vendedor_desde=$_POST['proddesde'];
$vendedor_hasta=$_POST['prodhasta'];


$seleccionado = $_POST['seleccionado'];


$int = 1;



$consulta_tc = 'SELECT cotizacion,fecha FROM resultados WHERE year(fecha)='.$combo;

$query_resultados_tc = $conexion->prepare($consulta_tc);
$query_resultados_tc->execute();

while($rows_resultados_tc = $query_resultados_tc->fetch(PDO::FETCH_ASSOC))
{
    $tc = (int)$rows_resultados_tc['cotizacion'];
}

if($seleccionado=='true')
{


    $consulta = 'SELECT id,fecha,month(fecha) as mes,year(fecha) as anho,sum(total) as precio,facturador 
                 FROM facturacion 
                 WHERE year(fecha)='.$combo.' and (month(fecha) >='.$desde.' and 
                 month(fecha) <='.$hasta.')  
                 GROUP BY mes ORDER BY mes,precio';

    $query_resultados = $conexion->prepare($consulta);
    $query_resultados->execute();


    while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC)) {


        if ($rows_resultados['mes'] == 1) {
            $mes = "Enero";
        }
        if ($rows_resultados['mes'] == 2) {
            $mes = "Febrero";
        }
        if ($rows_resultados['mes'] == 3) {
            $mes = "Marzo";
        }
        if ($rows_resultados['mes'] == 4) {
            $mes = "Abril";
        }
        if ($rows_resultados['mes'] == 5) {
            $mes = "Mayo";
        }
        if ($rows_resultados['mes'] == 6) {
            $mes = "Junio";
        }
        if ($rows_resultados['mes'] == 7) {
            $mes = "Julio";
        }
        if ($rows_resultados['mes'] == 8) {
            $mes = "Agosto";
        }
        if ($rows_resultados['mes'] == 9) {
            $mes = "Septiembre";
        }
        if ($rows_resultados['octubre'] == 10) {
            $mes = "Octubre";
        }
        if ($rows_resultados['octubre'] == 11) {
            $mes = "Noviembre";
        }
        if ($rows_resultados['octubre'] == 12) {
            $mes = "Diciembre";
        }
        $precio = $rows_resultados['precio']/$tc;
        $json[] = array("year" => $mes,
            "monto" => (round($precio - 0, 0)));

    }

}else
{

    $consulta = 'SELECT id,fecha,month(fecha) as mes,year(fecha) as anho,sum(total) as precio,facturador 
                 FROM facturacion 
                 WHERE year(fecha)='.$combo.' and (month(fecha) >='.$desde.' and 
                 month(fecha) <='.$hasta.')  
                 GROUP BY mes ORDER BY mes,precio';

    $query_resultados = $conexion->prepare($consulta);
    $query_resultados->execute();


    while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC)) {


        if ($rows_resultados['mes'] == 1) {
            $mes = "Enero";
        }
        if ($rows_resultados['mes'] == 2) {
            $mes = "Febrero";
        }
        if ($rows_resultados['mes'] == 3) {
            $mes = "Marzo";
        }
        if ($rows_resultados['mes'] == 4) {
            $mes = "Abril";
        }
        if ($rows_resultados['mes'] == 5) {
            $mes = "Mayo";
        }
        if ($rows_resultados['mes'] == 6) {
            $mes = "Junio";
        }
        if ($rows_resultados['mes'] == 7) {
            $mes = "Julio";
        }
        if ($rows_resultados['mes'] == 8) {
            $mes = "Agosto";
        }
        if ($rows_resultados['mes'] == 9) {
            $mes = "Septiembre";
        }
        if ($rows_resultados['octubre'] == 10) {
            $mes = "Octubre";
        }
        if ($rows_resultados['octubre'] == 11) {
            $mes = "Noviembre";
        }
        if ($rows_resultados['octubre'] == 12) {
            $mes = "Diciembre";
        }
        $json[] = array("year" => $mes,
            "monto" => round($rows_resultados['precio'] - 0, 0));

    }

}


$json_string = json_encode($json);
echo $json_string;

?>