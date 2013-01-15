$(function(){

	var lat = 22;
	var lng = 15;
	var zoom = 2;
	var mapCenterName = 'mapCenter' + tool.activeUserId;
	var mapZoomName = 'mapZoom' + tool.activeUserId;

	if (typeof(Storage) !== 'undefined'){
		if (localStorage[mapCenterName]) {
			var latLngArray = localStorage[mapCenterName].split(',');
			lat = latLngArray[0].substring(1);
			lng = latLngArray[1].substring(0, latLngArray[1].length-1);
		}
		if (localStorage[mapZoomName]) {
			zoom = parseInt(localStorage[mapZoomName]);
		}
	}
	// create a new map
	var gm = google.maps;
	var map = new gm.Map(document.getElementById("map"), {
	  center: new gm.LatLng(lat, lng),
	  zoom: zoom,
	  mapTypeId: gm.MapTypeId.ROADMAP
	});

	// spiderfier
	var oms = new OverlappingMarkerSpiderfier(map);
	var iw = new gm.InfoWindow();
	oms.addListener('click', function(marker) {
		iw.setContent(marker.desc);
		iw.open(map, marker);
	});
	oms.addListener('spiderfy', function(markers) {
		iw.close();
	});


	$.getJSON('http://localhost/Dev/webbteknik2_project/api/index.php/alsters.json', function(data){ // to use API url via VPN: 'model/getAllAlsters.php'
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
				// google.maps.event.addListener(marker, "dragend", function(e) {
				// 	marker.setIcon('img/newMarker.png'); // if draggable true, change marker icon after drop
				// });

  				oms.addMarker(marker); // add oms marker

			})(marker, data);
		}
		var infoWindow = new google.maps.InfoWindow();
	});


	$(window).bind('beforeunload', function(){
		if (typeof(Storage) !== 'undefined'){
			localStorage[mapCenterName] = map.getCenter();
			localStorage[mapZoomName] = map.getZoom();
		}
	}); 

});