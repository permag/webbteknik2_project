var init = {

	quoteHistoryArray: [],
	quoteHistoryPosition: 0,
	prevQuoteTag: '',

	history: function() {
		//sessionStorage.clear()
		if (typeof(Storage) !== 'undefined') {
			if (sessionStorage.quoteHistorySession != null) {
				var history = JSON.parse(sessionStorage.quoteHistorySession);
				init.quoteHistoryArray = history;

				if (init.quoteHistoryArray.length > 0) {

				}

				init.quoteHistory($('#searchQuoteTagArea'));
			}		
		}
	},

	getPhotos: function() {
		var searchPhotoTag = $("#searchPhotoTag");
		if ($.trim(searchPhotoTag.val()) == '') {
			return false;
		}
		var searchPhotosArea = $("#searchPhotosArea");
		searchPhotosArea.html('Searching...');

		$.ajax({
		    url: "model/getPhotos.php",
		    data: { tag: searchPhotoTag.val() },
		    type: "GET",
		    cache: true,
		    success: function(data) {
		    	searchPhotosArea.html(data);
		    	init.drag();

		    	$('.photoSrc').dblclick(function(){
		    		var dropTo = $('#alsterPhotoFrame');
		    		var image = $(this).attr('src').replace('_t', '');;
		    		init.saveImage(dropTo, image);
		    	});
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

					var fileName = droppedItem.attr('src'); // http://farm9.static.flickr.com/8223/8329899699_4fa389e7a1_t.jpg
					var newFileName = fileName.replace('_t', ''); // http://farm9.static.flickr.com/8223/8329899699_4fa389e7a1.jpg

					init.saveImage(dropTo, newFileName);
				}
			}
		});
	},

	saveImage: function(dropTo, newFileName){
		dropTo.html('<div class="loadingAjaxText">Loading image...</div>');
		$.ajax({
			url: 'model/copyImage.php',
			type: 'GET',
			cache: false,
			data: { imageUrl: newFileName },
			success: function(data){
				newFileName = './temp_image/'+ data + '?rand='+ Math.floor((Math.random()*999999999)+1);;

				var photo = $("<div id='droppedPhoto' class='droppedPhotoExists'></div>");
				dropTo.html(photo);
				var droppedPhoto = $('#droppedPhoto');

				// new image element. image is appended once img file is finisedhttp://farm9.static.flickr.com/8220/8333449533_80b48fc18e.jpg loading.
				var img = $("<img />").attr("src", newFileName);
				img.load(function() {
					droppedPhoto.append(img);

					var image = droppedPhoto.find("img");

					// adjust image to cover entire photo area
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
	},

	getQuotes: function() {
		var searchQuoteTag = $('#searchQuoteTag');
		if ($.trim(searchQuoteTag.val()) == '') {
			return false;
		}
		var searchQuoteTagArea = $('#searchQuoteTagArea');
		searchQuoteTagArea.html('Searching...');
		
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
		    	
		    	searchQuoteTagArea.html(output);

				$('.quotesList').click(function(e){
					$('#alsterQuoteFrame').html($(this).html());
				});

				// add history
				if (searchQuoteTag.val() != init.prevQuoteTag) {
					init.prevQuoteTag = searchQuoteTag.val(); // for next round
					if (output !== null && output.indexOf('quotesList') !== -1) {
						init.quoteHistoryArray.push(output);
					}
				}

				// history back and forward
				init.quoteHistory(searchQuoteTagArea, 1);

				init.storeHistory();

		    },
		    error: function() {
		    	searchQuoteTagArea.html('Error.');
		    }
		});
	},

	quoteHistory: function(searchQuoteTagArea, minusOne = null) {

		init.quoteHistoryPosition = init.quoteHistoryArray.length - minusOne ;
		if (init.quoteHistoryArray.length > 1) {
			if ($('#historyBack').length > 0) {
				return false;
			}
			$('#historyButtons').html('<button id="historyBack" class="btn btn-mini"> < </button> <button id="historyForward" class="btn btn-mini"> > </button>');
			$('#historyForward').attr('disabled', 'disabled');
			if (init.quoteHistoryPosition == 0) {
				$('#historyBack').attr('disabled', 'disabled');
			}

			// Back
			$('#historyBack').click(function(){
				init.quoteHistoryPosition--;
				buttons($(this), $('#historyForward'));
			});
			// Forward
			$('#historyForward').click(function(){
				init.quoteHistoryPosition++;
				buttons($('#historyBack'), $(this));
			});

			function buttons(back, forward) {
				if (init.quoteHistoryPosition >= init.quoteHistoryArray.length - 1) {
					forward.attr('disabled', 'disabled');
				} else {
					forward.removeAttr('disabled');
				}
				if (init.quoteHistoryPosition <= 0) {
					back.attr('disabled', 'disabled');
				} else {
					back.removeAttr('disabled');
				}
				addHistoryToArea();
			}

			function addHistoryToArea(){
				var hist = init.quoteHistoryArray[ init.quoteHistoryPosition ];
				searchQuoteTagArea.html(hist);
				// add handler
				$('.quotesList').click(function(e){
					$('#alsterQuoteFrame').html($(this).html());
				});
			}
		}
	},

	storeHistory: function() {
		// store new quote data in sessionStorage
		if (typeof(Storage) !== 'undefined'){
			sessionStorage.quoteHistorySession = JSON.stringify(init.quoteHistoryArray);
		}
	},

	editQuote: function() {
		var quoteFrame = $('#alsterQuoteFrame');
		var quote = quoteFrame.html();
		quote = quote.replace(/<br\s*[\/]?>/gi, '\n');

		quoteFrame.hide();
		quoteFrame.parent().append('<textarea id="editQuoteTextarea">'+ quote +'</textarea><div id="editQuoteButtons"><button id="saveQuoteEdit" class="btn btn-primary">Save</button><button id="cancelQuoteEdit" class="btn">Cancel</button></div>');

		$('#saveQuoteEdit').click(function(e){
			var text = $('#editQuoteTextarea').val();
			text = text.replace(/\n/g, '<br />');
			quoteFrame.html(text).show();
			$('#editQuoteTextarea, #editQuoteButtons').remove();
		});
		$('#cancelQuoteEdit').click(function(e){
			quoteFrame.show();
			$('#editQuoteTextarea, #editQuoteButtons').remove();
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

	// init
	init.history();
	init.drop();

	// profile pic
	var alsterPhotoFrame = $('#alsterPhotoFrame');
	var imageSrc = alsterPhotoFrame.find('img').attr('src');
	init.saveImage(alsterPhotoFrame, imageSrc);

	// photo border edit
	$('#alsterPhotoFrameBorder').resizable({
		containment: 'parent',
		handles: 's',
		maxHeight: 500,
		minHeight: 50
	});
	// photo
	$("#searchPhotoTag").focus();
	$("#searchPhotoTagButton").click(function(e){
		init.getPhotos();
	});
	$("#searchPhotoTag").keyup(function(event){
	    if(event.keyCode == 13){ // enter key
	        $("#searchPhotoTagButton").click();
	    }
	});
	// quote
	$('#searchQuoteTagButton').click(function(e){
		init.getQuotes();
	});
	$("#searchQuoteTag").keyup(function(event){
	    if(event.keyCode == 13){ // enter key
	        $("#searchQuoteTagButton").click();
	    }
	});
	// edit quote
	$('#alsterQuoteFrame').click(function(e){
		init.editQuote();
	});
	// finalize
	$('#finalize').click(function(e){
		finalize.preMake();
	});

});
