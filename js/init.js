var init = {

	getPhotos: function() {
		var searchPhotoTag = $("#searchPhotoTag");
		var searchPhotosArea = $("#searchPhotosArea");

		$.ajax({
		    url: "model/getPhotos.php",
		    data: { tag: searchPhotoTag.val() },
		    type: "GET",
		    cache: true,
		    success: function(data) {
		    	searchPhotosArea.html(data);
		    	init.drag();
		    },
		    error: function() {
		    	searchPhotosArea.html('Error.');
		    }
		});

	},

	drag: function() {
		$('.photoSrc').draggable({
			helper: "clone",
			opacity: 0.75,
			zIndex: 999,
			cursor: "move",
			revert: "invalid",
			revertDuration: 300
		});
	},

	drop: function() {
		var dropTo = $('#alsterPhotoFrame');
		dropTo.droppable({
			accept: '.photoSrc',
			tolerance: "pointer", // mouse "on target"
			hoverClass: "activeArea",
			drop: function(ev, ui) {
				var droppedItem = $(ui.draggable).clone();


				if (droppedItem.hasClass("photoSrc")) {
					// delete old image in photo frame
					if ($(this).find('img').length > 0) {
						var oldImage = $(this).find('img').attr('src');
						init.deleteTempImg(oldImage);
					}
					$(this).empty(); // if templatePhoto already contains div/img, replace with new.

					var fileName = droppedItem.attr("src"); // http://farm9.static.flickr.com/8223/8329899699_4fa389e7a1_t.jpg
					var newFileName = fileName.replace("_t", ""); // http://farm9.static.flickr.com/8223/8329899699_4fa389e7a1.jpg

					// copy api image to webserver
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
	},

	getQuotes: function() {
		var searchQuoteTag = $('#searchQuoteTag');
		
		$.ajax({
		    url: "http://www.stands4.com/services/v2/quotes.php?uid=2543&tokenid=Xor0DOW0C4Ag1Iay&searchtype=AUTHOR&query=" + searchQuoteTag.val(),
		    type: "GET",
		    dataType: 'XML',
		    cache: true,
		    success: function(data) {
		    	var output = '';
		    	if ($(data).find('result').length == 0){
		    		output = '<div>No quotes found.</div>';
		    	}
				$(data).find('result').each(function(){
					var quote = $(this).find('quote').text();
					var author = $(this).find('author').text();
					output += '<div class="quotesList">\"'+ quote + '\" <br /> - ' + author +'</div>';
				});
		    	
		    	$('#searchQuoteTagArea').html(output);

		    	$('.quotesList').click(function(e){
		    		$('#alsterQuoteFrame').html($(this).html());
		    	});
		    },
		    error: function() {
		    }
		});
	},

	editQuote: function() {
		var quoteFrame = $('#alsterQuoteFrame');
		var quote = quoteFrame.html();
		quote = quote.replace(/<br\s*[\/]?>/gi, '\n');

		quoteFrame.hide();
		quoteFrame.parent().append('<textarea id="editQuoteTextarea">'+ quote +'</textarea><button id="saveQuoteEdit">Save</button>');

		$('#saveQuoteEdit').click(function(e){
			var text = $('#editQuoteTextarea').val();
			text = text.replace(/\n/g, '<br />');
			quoteFrame.html(text).show();
			$('#editQuoteTextarea, #saveQuoteEdit').remove();
		});
	},

	deleteTempImg: function(imgUrl) {
		$.ajax({
			url: 'model/deleteTempImg.php',
			type: 'GET',
			data: { imgUrl: imgUrl },
			success: function(data){

			}
		});
	}

};

$(function() {
	$("#searchPhotoTagButton").click(function(e){
		init.getPhotos();
		init.drop();
	});
	$('#searchQuoteTagButton').click(function(e){
		init.getQuotes();
	});
	$('#alsterQuoteFrame').click(function(e){
		init.editQuote();
	});
	$('#finalize').click(function(e){
		finalize.preMake();
	});

});
