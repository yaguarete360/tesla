<!DOCTYPE html>
<html>
	<head>
		<script src="http://maps.googleapis.com/maps/api/js"></script>
		<script>
			function initialize() 
			{
				var myCenter_1 = new google.maps.LatLng(-25.381408333333333,-57.59881388888889);
				var mapProp_1 = 
				{
				  center:myCenter_1,
				  zoom:16,
				  mapTypeId:google.maps.MapTypeId.ROADMAP
				};
				var map_1 = new google.maps.Map(document.getElementById("googleMap_1"),mapProp_1);
				var marker_1 = new google.maps.Marker(
				{
			  		position:myCenter_1,
			  	});
				marker_1.setMap(map_1);
			}
			google.maps.event.addDomListener(window, 'load', initialize);
		</script>
	</head>
	<body>
		<div id="googleMap_1" style="width:100%;height:251px;"></div>
	</body>
</html>