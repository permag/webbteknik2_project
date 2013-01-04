$(function(){
	
	var lat = 22;
	var lng = 15;
	var zoom = 2;

	if (typeof(Storage) !== 'undefined'){
		if (localStorage.mapCenter) {
			var latLngArray = localStorage.mapCenter.split(',');
			lat = latLngArray[0].substring(1);
			lng = latLngArray[1].substring(0, latLngArray[1].length-1);
		}
		if (localStorage.mapZoom) {
			zoom = parseInt(localStorage.mapZoom);
		}
	}
	// create a new map
	var map = new google.maps.Map(document.getElementById("map"), {
	  center: new google.maps.LatLng(lat, lng),
	  zoom: zoom,
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	$.getJSON('model/coordsJSON.php', function(data){
		var json = data;
	
		for (var i = 0, length = json.length; i < length; i++) {
			var data = json[i],
			  latLng = new google.maps.LatLng(data.lat, data.lng); 

			// create a marker and put it on the map
			var marker = new google.maps.Marker({
				position: latLng,
				map: map
			});
			// create a closure to retain the correct data
			(function(marker, data) {
				// attach a click event to the current marker
				google.maps.event.addListener(marker, "mouseover", function(e) {
					infoWindow.setContent('<a href="share/?id='+ data.alsterId +'"><img src="alster/' + data.alsterUrl + '" width="200" /></a>');
					infoWindow.open(map, marker);
				});

			})(marker, data);

		}
		var infoWindow = new google.maps.InfoWindow();

	});


	$(window).bind('beforeunload', function(){
		if (typeof(Storage) !== 'undefined'){
			localStorage.mapCenter = map.getCenter();
			localStorage.mapZoom = map.getZoom();
		}
  	}); 

});