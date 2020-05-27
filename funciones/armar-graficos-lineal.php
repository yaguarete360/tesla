<?php if (!isset($_SESSION)) {session_start();}

$url = "../../";
include "../../funciones/mostrar-cabecera.php";
echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';
echo '<link rel="stylesheet" href="/resources/demos/style.css">';
echo '<script src="https://code.jquery.com/jquery-1.12.4.js"></script>';
echo '<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';

$desde = @$_GET['desde'];
$hasta = @$_GET['hasta'];

$rango_desde = @$_GET['rangodesde'];
$rango_hasta = @$_GET['rangohasta'];
$fecha = date("Y-m-d H:i:s");
$anho = date("Y", strtotime($fecha));
$month = date("m", strtotime($fecha));
$mes = date('m');
$year = date('Y');
$day = date("d", mktime(0,0,0, $mes+1, 0, $year));
$ultimo_dia = date('Y-m-d', mktime(0,0,0, $mes, $day, $year));
$month = date('m');
$year = date('Y');
$primer_dia = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
$primer_dia_anho = $year."-01-01";

if(empty($desde))
{
   $desde = $primer_dia_anho;  
}
if(empty($hasta))
{
   $hasta = $ultimo_dia;  
}

$_SESSION['titulo_pagina'] = $titulo; 
echo '<div class="top-header"';
    echo 'style="background-image: url(../../imagenes/iconos/cabecera.jpg)">';
    echo '<div class="container">';
        echo '<h1>'.$_SESSION['titulo_pagina'].'</h1>';
    echo '</div>';
echo '</div>';

include "../../funciones/conectar-base-de-datos.php";
 
$consulta_resultados = 'SELECT year(fecha) as anho                                                
                        FROM ventas
                        WHERE borrado LIKE "no"
                        GROUP BY anho
                        ORDER BY anho DESC';

$query_resultados = $conexion->prepare($consulta_resultados);
$query_resultados->execute();

echo '<div class="container">';
    echo '<section class="interna">';
     echo '<div class="row">';
         echo '<div class="col-sm-4">';
             echo '<div class="form-group">';
             
             echo ' <label for="desde">Desde:</label>';
              echo '<input class="datos"';
              echo 'type="text"';
              echo 'name="desde" id="desde" value="'.$desde.'"/> &nbsp&nbsp&nbsp&nbsp';
              echo '<br>';
             
               echo ' <label for="hasta">Hasta:</label>';
              echo '<input class="datos"';
              echo 'type="text"';
              echo 'name="hasta" id="hasta" value="'.$hasta.'"/> &nbsp&nbsp&nbsp&nbsp';
              echo '<br>';
             
             echo '</div>';
         echo '</div>';
   
 
         $colores = array("s/c","#35E500", "#C9E100","#DFAC00","#DD6100","#DB1800","#D500BB","#707476","#A3B3BB");

          
         if(isset($todos_los_capitulos)){

            $consulta_resultados = 'SELECT '.$campos.'
            FROM '.$tabla.'
            WHERE borrado LIKE "no" AND 
            (fecha >="'.$desde.'" AND fecha <="'.$hasta.'") AND
            zona in(1,2,3,4) AND 
            entra > 0 
            GROUP BY '.$group_by.'
            ORDER BY '.$group_by.''; 

         }else{
            $consulta_resultados = 'SELECT '.$campos.'
            FROM '.$tabla.'
            WHERE borrado LIKE "no" AND 
            (fecha >="'.$desde.'" AND fecha <="'.$hasta.'") AND
            clasificacion LIKE "'.$capitulo_tesoreria.'" AND 
            entra > 0 
            GROUP BY '.$group_by.'
            ORDER BY '.$group_by.''; 
         }
           
          $query_resultados = $conexion->prepare($consulta_resultados);
          $query_resultados->execute();
    
            $int = 1;
            $formato = array("lineColor","date","value");  
            $val = 0;
            while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
            {    
                    $json[] = array(
                                "lineColor"=> "#5687AF",
                                "date"=>$rows_resultados['fecha'],
                                "value" => round($rows_resultados['total']-0,0));
                
            }
   
     echo '</div>';
        echo '<div class="row">';
            echo '<div class="col-sm-12">';
                echo '<head>';
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
                    echo '<title>Ventas por producto por Mes</title>';
                    echo '<link rel="stylesheet" href="style.css" type="text/css">';
                    echo '<script src="../../librerias/amcharts/amcharts.js" type="text/javascript"></script>';
                    echo '<script src="../../librerias/amcharts/serial.js" type="text/javascript"></script>';
                echo '</head>';
                echo '<body>';
                    echo '<div id="chartdiv" style="width: 100%; height: 600px;"></div>';
                    echo '<div style="margin-left:40px;">';
                       // echo '<input type="radio" checked="true" name="group" id="rb1" onclick="setDepth()">2D';
                        //echo '<input type="radio" name="group" id="rb2" onclick="setDepth()">3D';
                    echo '</div>';
                    echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</section>';
echo '</div>';
$url = "../../";
include "../../funciones/mostrar-pie.php";

echo '</body>';
echo '</html>';

?>
<script type="text/javascript">var formato =<?php echo json_encode($formato);?></script>
<script type="text/javascript">var colores =<?php echo json_encode($colores);?></script>
<script type="text/javascript">var json =<?php echo json_encode($json);?></script>
<script src="../../librerias/js/graficar-lineal.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {

$("#desde").datepicker({ dateFormat: "yy-mm-dd" }).val();
$("#hasta").datepicker({ dateFormat: "yy-mm-dd" }).val();

var desde = "<?php echo $desde;?>";
var hasta = "<?php echo $hasta;?>";
var archivo_php = "<?php echo $archivo_php;?>";


var desde = "<?php echo $desde;?>";
$('#desde').val(desde);

var hasta = "<?php echo $hasta;?>";
$('#hasta').val(hasta);

$("#desde").change(function() {
        cargarForm();   
});

$("#hasta").change(function() {
         cargarForm();
});



function cargarForm(){
   var desde = $('#desde').val();
   var ano = desde.split("-");
   ano = ano[0];
   var hasta = $('#hasta').val();
         
   window.location.href = archivo_php+'?ano='+ano+'&desde='+desde+'&hasta='+hasta;
}

});

</script>

<style type="text/css">
  
  button.form-control, input.form-control, optgroup.form-control, select.form-control, textarea.form-control {
    border: 1px solid #bdc3c7;
    padding: 10px;
    outline: 0;
    height: auto;
    border-radius: 0;
    width: 200px;
}

</style>