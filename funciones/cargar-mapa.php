<!DOCTYPE html>
<html>
	<head>
		<script src="http://maps.googleapis.com/maps/api/js"></script>

		 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbFGWhi3F7i0cdL0E2GsOTKl7CPMkb4fc&callback=initMap"
     type="text/javascript"></script>
  		
		<script>
			 (function() {
			 	
			 	var x = "<?php echo $_GET['x'];?>";
			 	var y = "<?php echo $_GET['y'];?>";
				var init = "<?php echo $_GET['init'];?>";

				var myCenter_2 = [];
				var mapProp_2 = [];
				var map_2 = [];
				var marker_2 = []; 

				myCenter_2[1] = init;
				mapProp_2[1] = init;
				map_2[1] = init;
				marker_2[1] = init;

			    console.log(myCenter_2[1]);


				function initialize() 
				{
					 myCenter_2[1] = new google.maps.LatLng(x,y);
					 mapProp_2[1] = 
					{
					  center:myCenter_2[1],
					  zoom:16,
					  mapTypeId:google.maps.MapTypeId.ROADMAP
					};
					var divmapa = "googleMap_"+init;

					 map_2[1] = new google.maps.Map(document.getElementById(divmapa),mapProp_2[1]);
					 marker_2[1] = new google.maps.Marker(
					{
				  		position:myCenter_2[1],
				  	});
					marker_2[1].setMap(map_2[1]);
				}
				    google.maps.event.addDomListener(window, 'load', initialize);



				})();
				
		</script>
	</head>
	<body>
		<div id="googleMap_<?php echo $_GET['init'];?>" style="width:100%;height:251px;"></div>
	</body>
</html>