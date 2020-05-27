function dibujar(data){

    var chartData = data;
    
    var chart = AmCharts.makeChart("chartdiv", {
        "type": "serial",
        "theme": "light",
        "marginRight": 40,
        "marginLeft": 40,
        "autoMarginOffset": 20,
        "mouseWheelZoomEnabled":true,
        "dataDateFormat": "YYYY-MM-DD",
        "valueAxes": [{
            "id": "v1",
            "axisAlpha": 0,
            "position": "left",
            "ignoreAxisWidth":true
        }],
        "balloon": {
            "borderThickness": 1,
            "shadowAlpha": 0
        },
        "graphs": [{
            "id": "g1",
            "balloon":{
              "drop":false,
              "adjustBorderColor":true,
              "color":"#5687AF"
            },
            "bullet": "square",
            "bulletBorderAlpha": 1,
            "bulletBorderThickness": 1,
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "fillAlphas": 0.3,
            "fillColorsField": "lineColor",
            "title": "red line",
            "lineColorField": "lineColor",
            "useLineColorForBulletBorder": false,
            "valueField": "value",
            "balloonText": "<span style='font-size:18px;color:#FFFFF;font-weight:bold'>[[value]]</span>"
        }],
      
        "chartCursor": {
            "pan": true,
            "valueLineEnabled": true,
            "categoryBalloonDateFormat": "DD/MM/YYYY",
            "valueLineBalloonEnabled": true,
            "cursorAlpha":1,
            "cursorColor":"#5687AF",
            "limitToGraph":"g1",
            "valueLineAlpha":0.2,
            "valueZoomable":true
        },
        "categoryField": "date",
        "categoryAxis": {
            "parseDates": true,
            "dashLength": 1,
            "minorGridEnabled": false
        },
        "export": {
            "enabled": true
        },
        "dataProvider": chartData
    });
    
    
    chart.addListener("rendered", zoomChart);
    
    zoomChart();
    
    function zoomChart() {
        chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
    }

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
     console.log(data);
     dibujar(data);
  
    }
    });  
  }
  
  $( "#objeto" ).change(function() {
   //console.log('graficar...');
   //graficar();
       
  });
  
  graficar();
  