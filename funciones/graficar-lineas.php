
<style>

    /*body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
  background-color: #ffffff;
  overflow: hidden;
  margin: 0;
}*/

    #chartdiv {
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
    if(!$usar_minimo_base_cero) $minimo = 0;
    // var_dump($usar_minimo_base_cero);
    // echo '<br/>';
    // echo 'minimo = '.number_format($minimo);
    // echo '<br/>';

    if(!isset($contador_graficos_todos)) $contador_graficos_todos = 0;
    $contador_graficos_todos++;

    if(!isset($contador_graficos['antes'])) $contador_graficos['antes'] = 0;
    if(!isset($contador_graficos['despues'])) $contador_graficos['despues'] = 0;

    $grafico_impresion_tipo = '';
    if(isset($grafico_impresion_antes) and $grafico_impresion_antes)
    {
        $grafico_impresion_tipo = 'antes';
        $contador_graficos['antes']++;
        echo '<input type="hidden" name="graficos_a_imprimir_antes['.$contador_graficos['antes'].']" id="graficos_a_imprimir_antes_'.$contador_graficos['antes'].'" value="">';
    }
    elseif((isset($grafico_impresion_despues) and $grafico_impresion_despues) or (!isset($grafico_impresion_antes) and !isset($grafico_impresion_despues)))
    {
        $grafico_impresion_tipo = 'despues';
        $contador_graficos['despues']++;
        echo '<input type="hidden" name="graficos_a_imprimir_despues['.$contador_graficos['despues'].']" id="graficos_a_imprimir_despues_'.$contador_graficos['despues'].'" value="">';
    }

    echo '<div id="chartdiv_'.$contador_graficos_todos.'"  data-antes_despues="'.$grafico_impresion_tipo.'" data-grafico_numero="'.$contador_graficos[$grafico_impresion_tipo].'" class="chartdiv" style="min-height:500px;height:auto;max-height:1500px;"></div>';
    echo '<script src="../../librerias/amcharts4/core.js"></script>';
    echo '<script src="../../librerias/amcharts4/charts.js"></script>';
    echo '<script src="../../librerias/amcharts4/themes/animated.js"></script>';

?>

<script>
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    var chart = am4core.create("chartdiv_"+'<?php echo $contador_graficos_todos; ?>', am4charts.XYChart);

    // Add data
    var datos_a_graficar_procesado = '<?php echo json_encode($datos_a_graficar_procesado); ?>';
    var datos_a_graficar_procesado = JSON.parse(datos_a_graficar_procesado);

    var series_procesado = '<?php echo json_encode($series_procesado); ?>';
    var series_procesado = JSON.parse(series_procesado);

    var contador_graficos = '<?php echo json_encode($contador_graficos); ?>';
    var contador_graficos = JSON.parse(contador_graficos);

    console.log(contador_graficos);

    chart.data = datos_a_graficar_procesado;

    chart.padding(30, 30, 10, 30);
    // chart.legend = new am4charts.Legend();

    chart.colors.step = ('<?php echo $graficar_lineas_de_tendencia; ?>') ? 1 : 2;

    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    // categoryAxis.dataFields.category = "year";
    categoryAxis.dataFields.category = "category";
    categoryAxis.renderer.minGridDistance = 50;
    categoryAxis.renderer.grid.template.location = 0.5;
    categoryAxis.interactionsEnabled = false;
    categoryAxis.startLocation = 0.5;
    categoryAxis.endLocation = 0.5;

    // Create value axis
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.baseValue = 0;
    valueAxis.min = '<?php echo $minimo; ?>';

    // Create series
    for (i = 0; i < series_procesado.length; i++) { 
        var esta_serie = series_procesado[i];

        // var series1 = chart.series.push(new am4charts.ColumnSeries());
        // series1.columns.template.width = am4core.percent(80);
        // series1.columns.template.tooltipText = "{name}: {valueY.value}";
        // series1.name = esta_serie;
        // series1.dataFields.categoryX = "category";
        // series1.dataFields.valueY = esta_serie;
        // series1.stacked = '<?php echo $chequeado_grafico; ?>';
        var series = chart.series.push(new am4charts.LineSeries());
        series.name = esta_serie;
        // series.dataFields.valueY = "value";
        series.dataFields.valueY = esta_serie;
        series.dataFields.categoryX = "category";
        series.strokeWidth = 2;
        series.tensionX = 0.77;

        if('<?php echo $graficar_lineas_de_tendencia; ?>')
        {
            // var series = chart.series.push(new am4charts.LineSeries());
            // series.name = esta_serie+" (tendencia)";
            // series.dataFields.categoryX = "category";
            // series.dataFields.valueY = esta_serie+"_tendencia";
            // series.strokeWidth = 2;
            // series.tooltipText = "{name}: {valueY.value}"; // (valueY.change)

            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = esta_serie+"_tendencia";
            series.dataFields.categoryX = "category";
            series.strokeWidth = 2;
            // series.tensionX = 1;
        }
    }

    // bullet is added because we add tooltip to a bullet for it to change color
    var bullet = series.bullets.push(new am4charts.Bullet());
    bullet.tooltipText = "{valueY}";

    bullet.adapter.add("fill", function(fill, target){
        if(target.dataItem.valueY < 0){
            return am4core.color("#FF0000");
        }
        return fill;
    })
    var range = valueAxis.createSeriesRange(series);
    range.value = 0;
    range.endValue = -1000;
    range.contents.stroke = am4core.color("#FF0000");
    range.contents.fill = range.contents.stroke;

    // Add scrollbar
    var scrollbarX = new am4charts.XYChartScrollbar();
    scrollbarX.series.push(series);
    chart.scrollbarX = scrollbarX;

    chart.cursor = new am4charts.XYCursor();

    // chart.exporting.getImage("png").then(function(imgData) {
    //     // var graficar_antes_o_despues = ('<?php echo $grafico_impresion_antes; ?>') ? 'antes' : 'despues';
    //     var graficar_antes_o_despues = $(this).data('antes_despues');
    //     var graficar_antes_o_despues_numero = $(this).data('grafico_numero');
    //     console.log(graficar_antes_o_despues);
    //     $('#graficos_a_imprimir_'+graficar_antes_o_despues+'_'+graficar_antes_o_despues_numero).val(imgData);
    // });

    // $('#chartdiv_'+'<?php echo $contador_graficos_todos; ?>').on('click', function()
    // {
    //     // var graficar_antes_o_despues = ('<?php echo $grafico_impresion_antes; ?>') ? 'antes' : 'despues';
    //     var graficar_antes_o_despues = $(this).data('antes_despues');
    //     var graficar_antes_o_despues_numero = $(this).data('grafico_numero');
    //     console.log(graficar_antes_o_despues);
    //     chart.exporting.getImage("png").then(function(imgData) {
    //         $('#graficos_a_imprimir_'+graficar_antes_o_despues+'_'+graficar_antes_o_despues_numero).val(imgData);
    //     });
    // });

</script>

