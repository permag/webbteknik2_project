var finalize = {

	allowGeo: true,
	lat: null,
	lng: null,

	preMake: function() {
		finalize.allowGeo = true;
		var dialogContentText = '';
			var finalizeDialog = $("#finalizeDialog");
			finalizeDialog.dialog({
				title: "You are about to finalize your alster...",
				zIndex: 999999,
				resizable: false,
				width: 420,
				height: 270,
				modal: true,
				buttons: {
					"Yes, go ahead!": function() {
						var that = $(this);
						var tags = $("#tags").val();
						$('.ui-dialog button').button('disable'); // disable buttons, prevent multi clicks
						$(this).html("<p>Finalizing... Please wait... <img src='img/ajax-loader.gif' /></p>");
						if (finalize.allowGeo) {
							if (navigator.geolocation){
								navigator.geolocation.getCurrentPosition(getPosition);
								setTimeout(function() {
									alert('Could not find your geolocation. \n\nPlease finalize again with geolocation turned OFF.')
									that.dialog("close");
								},19999);
							}

						} else {
							finalize.make();
						}
					},
					"No, not yet.": function() {
						$(this).dialog("close");
					}
				}

			});
			finalizeDialog.html('<p>Your alster is about to get finalized.</p><br /><p>Are you sure you want to proceed?</p><br /><p class="icon-map-marker"><input type="button" id="allowGeolocation" value="Geolocation ON" /></p>');

			$('#allowGeolocation').click(function(){
				if (finalize.allowGeo == true) {
					finalize.allowGeo = false;
					$(this).val('Geolocation OFF');
				} else {
					finalize.allowGeo = true;
					$(this).val('Geolocation ON');
				}
			});

			function getPosition(position){
				finalize.lat = position.coords.latitude;
				finalize.lng = position.coords.longitude;
				finalize.make();
			}
	},

	make: function() {

		// remove resize handles
		$(".mainMenu").find(".ui-resizable-handle").remove();

		// remove all img but alster img
		$('img').not('.ui-resizable').remove();


		var alster = $('#alster');
		var imgSrc = alster.find('img').attr('src');
		
		alster.html2canvas({ onrendered: function(canvas) {

	        $.ajax({
	          url: 'model/canvas2png.php',
	          dataType: 'json',
	          type: 'POST',
	          data: {
	            img: canvas.toDataURL(),
	            lat: finalize.lat,
	            lng: finalize.lng,
	            tempImg: imgSrc
	          },
	          success: function(data){
	          	// data contains id to inserted DB row (json_encoded)
	          	var id_DB = data;
	          	window.location = 'share/?id=' + id_DB;

	          },
	          error: function(msg){
	            console.log(msg);
	          }
	        });
	    }});
	}

};