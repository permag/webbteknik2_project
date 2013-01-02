$(function(){
		// Creating a new map
		var map = new google.maps.Map(document.getElementById("map"), {
		  center: new google.maps.LatLng(57.9, 14.6),
		  zoom: 6,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		$.getJSON('model/coordsJSON.php', function(data){
			var json = data;
		
		
			for (var i = 0, length = json.length; i < length; i++) {
				var data = json[i],
				  latLng = new google.maps.LatLng(data.lat, data.lng); 

				// Creating a marker and putting it on the map
				var marker = new google.maps.Marker({
					position: latLng,
					map: map
				});
				// Creating a closure to retain the correct data, notice how I pass the current data in the loop into the closure (marker, data)
				(function(marker, data) {
					// Attaching a click event to the current marker
					google.maps.event.addListener(marker, "click", function(e) {
						infoWindow.setContent('<a href="share/?id=' + data.alsterId + '" target="_blank"><img src="alster/' + data.alsterUrl + '" width="200" /></a>');
						infoWindow.open(map, marker);
					});

				})(marker, data);

			}
			var infoWindow = new google.maps.InfoWindow();
		});

	});
