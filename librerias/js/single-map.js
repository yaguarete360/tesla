var map;
var init_map = false;

function set_markers(mapTitle, mapAddress, lat, lng){
	var contentString = "<div id='infocontent'><div class='google_title'><strong>"+mapTitle+"</strong></div><p>"+mapAddress+"</p></div>";
  
	if(mapAddress != ''){
		var infowindow = new google.maps.InfoWindow({
			content: contentString
		});
	}
	var myLatlng = new google.maps.LatLng(lat, lng);
	var marker = new google.maps.Marker({
			position: myLatlng,
			map: map
		});
	google.maps.event.addListener(marker, 'click', function() {
	infowindow.open(map,marker);
	});
}

function initialize() {
	var myLatlng = new google.maps.LatLng(gcode.lat, gcode.lng);
	var mapOptions = {
		zoom: Number(gcode.zoom),
		center: myLatlng,
        panControl:false,
		zoomControl:false,
		mapTypeControl:false,
		scaleControl:false,
		scrollwheel: false,
		streetViewControl:false,
		mapTypeId: google.maps.MapTypeId.ROADMAP

	}
	map = new google.maps.Map(document.getElementById(gcode.mapId), mapOptions);
	var styles = [
					{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},
					{"featureType":"transit","elementType":"labels","stylers":[{"visibility":"off"}]},
					{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},
					{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},
					{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},
					{"stylers":[{"hue":"#F16244"},{"saturation":-100},{"gamma":1.2243},{"lightness":13}]},
					{"featureType":"road","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"lightness":24}]},
					{"featureType":"road","elementType":"geometry","stylers":[{"lightness":57}]}
				]
		var mapTitle=gcode.title;
		var mapAddress=gcode.address;
		var mapAdd=gcode.add;
		var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});
		var contentString = "<div id='infocontent'><div class='google_title'><strong>"+mapTitle+"</strong></div><p>"+mapAddress+"</p></div>";
      
		if(gcode.address != ''){
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
		}
		var marker = new google.maps.Marker({
				position: myLatlng,
				map: map,
				icon:gcode.marker
			});
			map.mapTypes.set('map_style', styledMap);
			map.setMapTypeId('map_style');
			google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});
		init_map = true;
}
google.maps.event.addDomListener(window, 'load', initialize);