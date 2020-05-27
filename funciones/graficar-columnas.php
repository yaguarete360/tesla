
<style>

    /*body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
  background-color: #ffffff;
  overflow: hidden;
  margin: 0;
}*/

    .chartdiv {
      width: 100%;
      max-height: 600px;
      height: 100vh;
    }

</style>

<?php if (!isset($_SESSION)) {session_start();}
    
    // examples/stacked-column-chart/
    $pos = 0;
    $minimo = 0;
    $maximo = 0;
    $series_procesado = array();
    $datos_linea = array();

    if(!isset($graficar_lineas_de_tendencia)) $graficar_lineas_de_tendencia = false;
    if(!isset($usar_minimo_base_cero)) $usar_minimo_base_cero = true;
    $suma_x = array();
    $suma_y = array();
    $suma_x_por_y = array();
    $suma_x_cuadrado = array();

    foreach ($datos_a_graficar as $categoria => $valores)
    {
        $datos_a_graficar_procesado[$pos]['category'] = $categoria;
        foreach ($valores as $valor_nombre => $valor)
        {
            $datos_a_graficar_procesado[$pos][$valor_nombre] = $valor;
            if(!in_array($valor_nombre, $series_procesado)) $series_procesado[] = $valor_nombre;
            if($valor < $minimo) $minimo = $valor * 1.05;
            if($valor > $maximo) $maximo = $valor * 1.05;

            if($graficar_lineas_de_tendencia)
            {
                if(!isset($suma_x[$valor_nombre]))
                {
                    $suma_x[$valor_nombre] = 0;
                    $suma_y[$valor_nombre] = 0;
                    $suma_x_por_y[$valor_nombre] = 0;
                    $suma_x_cuadrado[$valor_nombre] = 0;
                }

                $suma_x[$valor_nombre]+= ($pos+1);
                $suma_y[$valor_nombre]+= $valor;
                $suma_x_por_y[$valor_nombre]+= (($pos+1) * $valor);
                $suma_x_cuadrado[$valor_nombre]+= (($pos+1) * ($pos+1));
            }
        }
        
        $pos++;
    }

    if($graficar_lineas_de_tendencia)
    {
        $n = count($datos_a_graficar_procesado);
        if($n > 1)
        {
            $ultimo_item = $pos - 1;
            $item_del_medio = round(($pos - 1) / 2);
            foreach ($series_procesado as $serie_pos => $serie_nombre)
            {
                $pendiente = (($n * $suma_x_por_y[$serie_nombre]) - ($suma_x[$serie_nombre] * $suma_y[$serie_nombre])) / (($n * $suma_x_cuadrado[$serie_nombre]) - ($suma_x[$serie_nombre] * $suma_x[$serie_nombre]));
                $valor_eje_y = ($suma_y[$serie_nombre] - ($pendiente * $suma_x[$serie_nombre])) / $n;
                
                $datos_a_graficar_procesado[0][$serie_nombre.'_tendencia'] = $valor_eje_y;
                $datos_a_graficar_procesado[$item_del_medio][$serie_nombre.'_tendencia'] = ($pendiente * ($item_del_medio)) + $valor_eje_y;
                $datos_a_graficar_procesado[$ultimo_item][$serie_nombre.'_tendencia'] = ($pendiente * ($ultimo_item)) + $valor_eje_y;
            }
        }
    }
    // if($usar_minimo_base_cero) $minimo = 0;
    var_dump($usar_minimo_base_cero);
    echo '<br/>';
    echo 'minimo = '.number_format($minimo);
    echo '<br/>';

    if(!isset($contador_graficos_todos)) $contador_graficos_todos = 0;
    $contador_graficos_todos++;

    $grafico_impresion_tipo = '';
    if(isset($grafico_impresion_antes) and $grafico_impresion_antes)
    {
        $grafico_impresion_tipo = 'antes';
    }
    elseif((isset($grafico_impresion_despues) and $grafico_impresion_despues) or (!isset($grafico_impresion_antes) and !isset($grafico_impresion_despues)))
    {
        $grafico_impresion_tipo = 'despues';
    }
    // echo '<input type="hidden" name="graficos_a_imprimir_'.$grafico_impresion_tipo.'[]" id="graficos_a_imprimir_'.$contador_graficos_todos.'" value="">';

    if($contador_graficos_todos == 1)
    {
        echo '<div id="chartdiv" data-grafico_numero="'.$contador_graficos_todos.'" class="chartdiv" style="min-height:500px;height:auto;max-height:1500px;"></div>';
        echo '<input type="hidden" name="graficos_a_imprimir_'.$grafico_impresion_tipo.'[]" id="graficos_a_imprimir" value="">';
        echo '<script src="../../librerias/amcharts4/core.js"></script>';
        echo '<script src="../../librerias/amcharts4/charts.js"></script>';
        echo '<script src="../../librerias/amcharts4/themes/animated.js"></script>';
    }

?>

<script>
    
    
    if('<?php echo $contador_graficos_todos; ?>' == 1)
    {
        var container = am4core.create("chartdiv", am4core.Container); //create the container
        container.width = am4core.percent(100); //set dimensions and layout
        container.height = am4core.percent(100);
        container.layout = "vertical";

        console.log('cargar una vez contenedor');
        $('.chartdiv').on('click', function()
        {
            var grafico_numero = $(this).data('grafico_numero');
            
            // console.log(grafico_numero);
            // console.log('#graficos_a_imprimir_'+grafico_numero);

            // chart.exporting.getImage("png").then(function(imgData) {
            //     // $('graficos_a_imprimir').val(imgData);
            //     console.log(imgData);
            // });

            var img;
            chart.exporting.getImage( "png" ).then( ( data ) => {
              img = data;
            });
        });
    }


    am4core.useTheme(am4themes_animated);
     var chart = container.createChild(am4charts.XYChart);
     console.log('grafico columnas');

    var datos_a_graficar_procesado = '<?php echo json_encode($datos_a_graficar_procesado); ?>';
    var datos_a_graficar_procesado = JSON.parse(datos_a_graficar_procesado);

    var series_procesado = '<?php echo json_encode($series_procesado); ?>';
    var series_procesado = JSON.parse(series_procesado);

    chart.data = datos_a_graficar_procesado;
    
    // chart.padding(30, 30, 10, 30);
    chart.padding(0, 0, 0, 0);
    chart.legend = new am4charts.Legend();

    chart.colors.step = ('<?php echo $graficar_lineas_de_tendencia; ?>') ? 1 : 2;

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "category";
    categoryAxis.renderer.minGridDistance = 60;
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.interactionsEnabled = false;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.tooltip.disabled = false;
    valueAxis.renderer.grid.template.strokeOpacity = 0.05;
    valueAxis.renderer.minGridDistance = 20;
    valueAxis.interactionsEnabled = false;
    var minimo = ('<?php echo $minimo; ?>' != 0) ? '<?php echo $minimo; ?>' : 0;
    valueAxis.min = minimo;
    // console.log('minimo='+minimo);
    // valueAxis.min = 0;
    valueAxis.renderer.minWidth = 35;

    for (i = 0; i < series_procesado.length; i++) { 
        var esta_serie = series_procesado[i];

        var series1 = chart.series.push(new am4charts.ColumnSeries());
        series1.columns.template.width = am4core.percent(80);
        series1.columns.template.tooltipText = "{name}: {valueY.value}";
        series1.name = esta_serie;
        series1.dataFields.categoryX = "category";
        series1.dataFields.valueY = esta_serie;
        series1.stacked = '<?php echo $chequeado_grafico; ?>';

        if('<?php echo $graficar_lineas_de_tendencia; ?>')
        {
            var series = chart.series.push(new am4charts.LineSeries());
            series.name = esta_serie+" (tendencia)";
            series.dataFields.categoryX = "category";
            series.dataFields.valueY = esta_serie+"_tendencia";
            series.strokeWidth = 2;
            series.tooltipText = "{name}: {valueY.value}"; // (valueY.change)
        }
    }

    chart.scrollbarX = new am4core.Scrollbar();

    chart.cursor = new am4charts.XYCursor();

    // chart.exporting.getImage("png").then(function(imgData) {
    //     var grafico_numero = $(this).data('grafico_numero');
        
    //     // console.log('#graficos_a_imprimir_'+grafico_numero);

    //     $('#graficos_a_imprimir_'+grafico_numero).val(imgData);
    // });

</script>
