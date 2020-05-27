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
    if(isset($not_like))
    {
        $consulta_sql = 'SELECT '.$campos.'
        FROM '.$tabla.'
        WHERE borrado LIKE "no" AND 
        (fecha >="'.$desde.'" AND fecha <="'.$hasta.'")
        '.$not_like.'
        GROUP BY grupo
        ORDER BY sumatoria DESC';

    $consulta_sql_asc = 'SELECT '.$campos.'
        FROM '.$tabla.'
        WHERE borrado LIKE "no" AND 
        (fecha >="'.$desde.'" AND fecha <="'.$hasta.'")
        '.$not_like.'
        GROUP BY grupo
        ORDER BY sumatoria ASC'; 
    }else
    {
        $consulta_sql = 'SELECT '.$campos.'
        FROM '.$tabla.'
        WHERE borrado LIKE "no" AND 
        (fecha >="'.$desde.'" AND fecha <="'.$hasta.'")
        GROUP BY grupo
        ORDER BY sumatoria DESC';

    $consulta_sql_asc = 'SELECT '.$campos.'
        FROM '.$tabla.'
        WHERE borrado LIKE "no" AND 
        (fecha >="'.$desde.'" AND fecha <="'.$hasta.'")
        GROUP BY grupo
        ORDER BY sumatoria ASC'; 
    }
                              

        $query_resultados_valor = $conexion->prepare($consulta_sql);
        $query_resultados_valor->execute();
        $valor = $query_resultados_valor->fetchAll();

        $query_resultados_valor_asc = $conexion->prepare($consulta_sql_asc);
        $query_resultados_valor_asc->execute();
        $valor_asc = $query_resultados_valor_asc->fetchAll();

        $array_desde = array();
        for($int=0;$int<count($valor);$int++)
        {
            $array_desde[]=$valor[$int]['sumatoria'];
        }
        $array_desde = array_unique($array_desde);

        $array_desde_order=array();
        foreach($array_desde as $value)
        {
            $array_desde_order[]=$value;
        }    
 
        $array_hasta = array();
        for($int=0;$int<count($valor_asc);$int++)
        {
            $array_hasta[]=$valor_asc[$int]['sumatoria'];
        }
        $array_hasta = (array_unique($array_hasta));
        $array_hasta_order=array();
        foreach($array_hasta as $value)
        {
            $array_hasta_order[]=$value;
        }  
         $array_hasta_order = $array_hasta_order;

        $i=0;
        if(empty($rango_desde))
        {
          $rango_desde = $array_desde_order[0];  
        }else
        {
            $bandera="NO";
            foreach($array_desde as $value)
            {
                if($value==$rango_desde)
                {
                    $bandera="SI";
                }
            }
            if($bandera=="NO"){
                $rango_desde = $array_desde_order[0];
            }
        }
        if(empty($rango_hasta))
        {
        $rango_hasta = $array_hasta_order[0];
        }else
        {
            $bandera="NO";
            foreach($array_hasta as $value)
            {
                if($value==$rango_hasta)
                {
                    $bandera="SI";
                }
            }
            if($bandera=="NO"){
                $rango_hasta = $array_hasta_order[0];
            }
        }
     
        $ordenar_valor = array($rango_desde,$rango_hasta);
        sort($ordenar_valor);
        $rango_desde_2 = $ordenar_valor[0];
        $rango_hasta_2 = $ordenar_valor[1];

$_SESSION['titulo_pagina'] = $titulo; 
echo '<div class="top-header"';
    echo 'style="background-image: url(../../imagenes/iconos/cabecera.jpg)">';
    echo '<div class="container">';
        echo '<h1>'.$_SESSION['titulo_pagina'].'</h1>';
    echo '</div>';
echo '</div>';

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
             
               echo ' <label for="rangodesde">Desde Valor Mayor:</label>';
               echo '<select class="form-control selectpicker" data-live-search="true" id="rangodesde" name="rangodesde">';
      
               for($i=0;$i<count($array_desde_order);$i++)
                { 
                    echo "<option value=".$array_desde_order[$i].">".number_format($array_desde_order[$i])."</option>";     
                }
                 echo '</select>';

               echo ' <label for="rangohasta">Hasta Valor Menor:</label>';
               echo '<select class="form-control selectpicker" data-live-search="true" id="rangohasta" name="rangohasta">';
                for($i=0;$i<count($array_hasta_order);$i++)
                  {
                    echo "<option value=".$array_hasta_order[$i].">".number_format($array_hasta_order[$i])."</option>";    
                  }
                 echo '</select>';
             echo '</div>';
         echo '</div>';
   
         $colores = array("s/c","#35E500", "#C9E100","#DFAC00","#DD6100","#DB1800","#D500BB","#707476","#A3B3BB");

         if(isset($not_like))
         {
            $consulta_resultados = 'SELECT '.$campos.'
            FROM '.$tabla.' 
            WHERE (fecha >="'.$desde.'" and fecha <="'.$hasta.'") AND
            borrado LIKE "no"
            '.$not_like.'
            GROUP BY grupo 
            ORDER BY sumatoria desc';
         }else
         {
            $consulta_resultados = 'SELECT '.$campos.'
            FROM '.$tabla.' 
            WHERE (fecha >="'.$desde.'" and fecha <="'.$hasta.'") AND
            borrado LIKE "no"
            GROUP BY grupo 
            ORDER BY sumatoria desc';
         }
         
        
          $query_resultados = $conexion->prepare($consulta_resultados);
          $query_resultados->execute();
    
            $int = 1;
            $formato = array("year","valor");  
            $val = 0;
            while($rows_resultados = $query_resultados->fetch(PDO::FETCH_ASSOC))
            {    
                if($rows_resultados['sumatoria']>=$rango_desde_2 && $rows_resultados['sumatoria']<=$rango_hasta_2){
                    $json[] = array("year"=>$rows_resultados['grupo'],
                                "valor" => round($rows_resultados['sumatoria']-0,0));
                    }
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
                        echo '<input type="radio" checked="true" name="group" id="rb1" onclick="setDepth()">2D';
                        echo '<input type="radio" name="group" id="rb2" onclick="setDepth()">3D';
                    echo '</div>';
                    echo '</div>';
            echo '</div>';

            echo '<table class="tabla_linda">';
              echo '<tr>';
                echo '<th>';
                  echo 'Año';
                echo '</th>';
                // echo '<th>';
                //   echo 'Centro';
                // echo '</th>';
                echo '<th>';
                  echo 'Monto';
                echo '</th>';
              echo '</tr>';

              foreach ($json as $pos => $json_valor)
              {
                echo '<tr>';
                  echo '<td rowspan="'.count($json_valor).'">';
                    echo $json_valor['year'];
                  echo '</td>';
                echo '</tr>';
                  foreach ($json_valor as $centro => $monto) if($centro != 'year')
                  {
                echo '<tr>';
                    // echo '<td>';
                    //   echo $centro;
                    // echo '</td>';
                    echo '<td style="text-align:right">';
                      echo number_format($monto);
                    echo '</td>';
                echo '</tr>';
                  }
              }
            echo '</table>';
            
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
<script src="../../librerias/js/graficar45.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {

$("#desde").datepicker({ dateFormat: "yy-mm-dd" }).val();
$("#hasta").datepicker({ dateFormat: "yy-mm-dd" }).val();

var desde = "<?php echo $desde;?>";
var hasta = "<?php echo $hasta;?>";
var rango_desde = "<?php echo $rango_desde?>";
var rango_hasta = "<?php echo $rango_hasta;?>";
var archivo_php = "<?php echo $archivo_php;?>";

$('#rangodesde').val(rango_desde);
$('#rangohasta').val(rango_hasta);

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

$("#rangodesde").change(function() {
   cargarForm();
});

$("#rangohasta" ).change(function() {
         cargarForm();
 });

function cargarForm(){
   var desde = $('#desde').val();
   var ano = desde.split("-");
   ano = ano[0];
   var hasta = $('#hasta').val();
   var rango_desde = $('#rangodesde').val();
   var rango_hasta = $('#rangohasta').val();
         
   window.location.href = archivo_php+'?ano='+ano+'&desde='+desde+'&hasta='+hasta+"&rangodesde="+rango_desde+"&rangohasta="+rango_hasta;
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