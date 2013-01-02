$(function() {
	$("#searchPhotoTagButton").click(function(e){
		getPhotos();
		drop();
	});
	$('#searchQuoteTagButton').click(function(e){
		getQuotes();
	});
	$('#alsterQuoteFrame').click(function(e){
		editQuote();
		e.stopPropagation();
	});
	$('#finalize').click(function(e){
		finalize();
	});
});

var getPhotos = function() {
	var searchPhotoTag = $("#searchPhotoTag");
	var searchPhotosArea = $("#searchPhotosArea");

	$.ajax({
	    url: "model/getPhotos.php",
	    data: { tag: searchPhotoTag.val() },
	    type: "GET",
	    cache: true,
	    success: function(data) {
	    	searchPhotosArea.html(data);
	    	drag();
	    },
	    error: function() {
	    	searchPhotosArea.html('Error.');
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
		hoverClass: "activeArea",
		drop: function(ev, ui) {
			var droppedItem = $(ui.draggable).clone();


			if (droppedItem.hasClass("photoSrc")) {
				$(this).empty(); // if templatePhoto already contains div/img, replace with new.

				var fileName = droppedItem.attr("src"); // http://farm9.static.flickr.com/8223/8329899699_4fa389e7a1_t.jpg
				var newFileName = fileName.replace("_t", ""); // http://farm9.static.flickr.com/8223/8329899699_4fa389e7a1.jpg


				$.ajax({
					url: 'model/copyImage.php',
					type: 'GET',
					data: { imageUrl: newFileName },
					success: function(data){
						newFileName = './temp_image/'+ data;

						var photo = $("<div id='droppedPhoto' class='droppedPhotoExists'></div>");
						dropTo.append(photo);
						var droppedPhoto = $('#droppedPhoto');

						// new image element. image is appended once img file is finisedhttp://farm9.static.flickr.com/8220/8333449533_80b48fc18e.jpg loading.
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

							// resizable after img is loaded
							img.resizable({
								aspectRatio: true,
								autoHide: true,
								handles: "all"
							});

							// make draggable in place
							droppedPhoto.draggable({
								cursor: "move"
							});
						});


					}
				});


			}
		}
	});
};

var getQuotes = function() {
	var searchQuoteTag = $('#searchQuoteTag');
	
	$.ajax({
	    url: "model/getQuotes.php",
	    type: "GET",
	    dataType: 'JSON',
	    cache: true,
	    data: { source: searchQuoteTag.val() },
	    success: function(data) {
	    	var quote = '';
	    	$.each(data, function(key, val){
	    		if (val == null) {
	    			quote += '<div>No quotes found.</div>';
	    			return false;
	    		}
	    		quote += '<div class="quotesList">' + val.quote + '</div>';
	    	});
	    	
	    	$('#searchQuoteTagArea').html(quote);

	    	$('.quotesList').click(function(e){
	    		$('#alsterQuoteFrame').html($(this).html());
	    	});
	    },
	    error: function() {
	    }
	});
};

var editQuote = function() {
	var quoteFrame = $('#alsterQuoteFrame');
	var quote = quoteFrame.html();

	quoteFrame.hide();
	quoteFrame.parent().append('<textarea id="editQuoteTextarea">'+ quote +'</textarea><button id="saveQuoteEdit">Save</button>');

	$('#saveQuoteEdit').click(function(e){
		quoteFrame.html($('#editQuoteTextarea').val()).show();
		$('#editQuoteTextarea, #saveQuoteEdit').remove();
	});
};


var finalize = function() {

	// turn off onbeforeunload page warning
	// remove selected class

	// remove resize handles
	$(".mainMenu").find(".ui-resizable-handle").remove();

	// remove all img but alster img
	$('img').not('.ui-resizable').remove();
	
	$('#alster').html2canvas({ onrendered: function(canvas) {

        $.ajax({
          url: 'model/canvas2png.php',
          dataType: 'json',
          type: 'POST',
          data: {
            img: canvas.toDataURL()
          },
          success: function(data){
          	// data contains id to inserted DB row (json_encoded)
          	var id_DB = data;
          	alert(id_DB);

          }//,
          // error: function(msg){
          //   console.log(msg);
          // }
        });
    }});

};
