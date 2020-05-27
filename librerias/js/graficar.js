function dibujar(data){
      
      var chartData = data;
      
            chart = new AmCharts.AmSerialChart();
            chart.dataProvider = chartData;
            chart.categoryField = formato[0];
            chart.startDuration = 1;
            chart.plotAreaBorderAlpha = 0.2;
            chart.rotate = false;

            var categoryAxis = chart.categoryAxis;
            categoryAxis.gridAlpha = 0.1;
            categoryAxis.axisAlpha = 0;
            categoryAxis.gridPosition = "start";
            
            var valueAxis = new AmCharts.ValueAxis();
            valueAxis.stackType = "regular";
            valueAxis.gridAlpha = 0.1;
            valueAxis.axisAlpha = 0;
            chart.addValueAxis(valueAxis);

            var graph = null;
            var c = 0;
         
                for(var i=1;i<formato.length;i++){
                    graph = new AmCharts.AmGraph(); 
                    graph.title = formato[i];
                    graph.labelText = "[[value]]";
                    graph.valueField = formato[i];
                    graph.type = "column";
                    graph.lineAlpha = 0;
                    graph.fillAlphas = 1;
                    graph.lineColor = colores[i];
                    graph.balloonText = "<b><span style='color:"+colores[i]+"'>[[title]]</b></span><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>";
                    graph.labelPosition = "middle";
                    chart.addGraph(graph);
                }
            
            var legend = new AmCharts.AmLegend();
            legend.position = "bottom";
            legend.borderAlpha = 0.3;
            legend.horizontalGap = 10;
            legend.switchType = "v";
            chart.addLegend(legend);

            chart.creditsPosition = "top-right";
            chart.write("chartdiv");
 }

function setDepth() {
    if (document.getElementById("rb1").checked) {
        chart.depth3D = 0;
        chart.angle = 0;
    } else {
        chart.depth3D = 20;
        chart.angle = 30;
    }
    chart.validateNow();
}


function graficar(){
  
 var arrayProperties = new Array();
 var json2 = json;
  
 $.ajax({
   type: "POST",
   url: "../../funciones/armar-json.php",
   data: { 'json':json2,'formato':formato },
   dataType: 'json',
   beforeSend: function(){

   },
   success: function(data){
     dibujar(data);
 
    }
    });  
}

$( "#objeto" ).change(function() {
   //console.log('graficar...');
   //graficar();
       
});

graficar();
