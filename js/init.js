$(function() {
	$("#searchPhotoTagButton").click(function(e){
		getPhotos();
		drop();
	});
});

var getPhotos = function() {
	var searchPhotoTag = $("#searchPhotoTag");
	var searchPhotosContainer = $("#searchPhotosContainer");

	$.ajax({
	    url: "model/getPhotos.php",
	    data: { tag: searchPhotoTag.val() },
	    type: "GET",
	    cache: true,
	    success: function(data) {
	    	searchPhotosContainer.html(data);
	    	drag();
	    },
	    error: function() {
	    	searchPhotosContainer.html('Error.');
	    }
	});

};
var drag = function() {
	$('.photoSrc').draggable({
		helper: "clone",
		containment: "#wrap",
		opacity: 0.75,
		zIndex: 999,
		cursor: "move",
		revert: "invalid",
		revertDuration: 300
	});
};

var drop = function() {
	var dropTo = $('#alsterPhotoFrame');
	dropTo.droppable({
		accept: '.photoSrc',
		tolerance: "pointer", // mouse "on target"
		//hoverClass: "activeArea",
		drop: function(ev, ui) {
			var droppedItem = $(ui.draggable).clone();

			if (droppedItem.hasClass("photoSrc")) {
				$(this).find("img").remove(); // if templatePhoto already contains div/img, replace with new.

				var fileName = droppedItem.attr("src"); // http://farm9.static.flickr.com/8223/8329899699_4fa389e7a1_t.jpg
				var newFileName = fileName.replace("_t", ""); // http://farm9.static.flickr.com/8223/8329899699_4fa389e7a1.jpg
				var photo = $("<div id='droppedPhoto'></div>");
				droppedPhoto = $('#droppedPhoto');

				$('#alsterPhotoFrame').append(photo);

				var image = $('#droppedPhoto');

				// new image element. image is appended once img file is finised loading.
				var img = $("<img />").attr("src", newFileName);
				img.load(function() {
					//ajaxLoader.remove();
					droppedPhoto.append(img);

					var image = droppedPhoto.find("img");

					// adjust image to cover entire template photo frame
					if (image.width() < dropTo.width() || image.width() > dropTo.width()) {
						image.css({ width: dropTo.width() + 4 });
					} 
					if (image.height() < dropTo.height() + 4) {
						while (image.height() < dropTo.height() + 4) {
							image.css({ height: image.height() * 1.05, width: image.width() * 1.05 });
						}
					}
					// center image horizontal
					if (image.width() > dropTo.width()) {
						var widthDiff = dropTo.width() - image.width(); // negative value
						droppedPhoto.css({ left: parseInt((widthDiff) / 2) });
					}
				});

			}
		}
	});
};