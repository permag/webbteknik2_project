var home = {

	alsterStart: 0,
	alsterTake: 6,
	alsterIdCount: 0,

	myStuff: function() {
		var myStuff = $('#myStuff');
		myStuff.append('<div id="alsterLoading">Loading...</div>');

		$.ajax({
			url: 'model/getAlster.php',
			type: 'GET',
			dataType: 'JSON',
			data: { start: home.alsterStart, take: home.alsterTake },
			success: function(data) {
				// if (data == null || data == '') {
				// 	myStuff.html('<p>Nothing here. Start creating!</p>');
				// 	return false;
				// }
				$('#alsterLoading').remove();
				home.alsterStart = home.alsterStart + home.alsterTake;
				var count = 0;
				var elHeight = myStuff.height();
				$.each(data, function(key, val) {
					home.alsterIdCount++;
					elHeight = myStuff.height() + 20;

					var img = $("<img />").attr("src", 'alster/' + val.alsterUrl);
					img.load(function() {
						myStuff.append('<a style="position:absolute;top:'+elHeight+'px" href="share/?id='+ val.alsterId +'" id="myAlster'+ home.alsterIdCount +'" class="myAlster"></a>');
						img.attr('width', 100);
						myStuff.find('a').last().append(img);
						myStuff.masonry('reload');
					});

					count = key;
				});
				if ($('#showMoreButton').length != 0) { // not on page load
				window.setTimeout(function(){
						$("html, body").animate({ scrollTop: $(document).height() }, "slow");
				},555);						
				}
				window.setTimeout(function(){
						myStuff.masonry('reload');
				},555);
				window.setTimeout(function(){
					myStuff.masonry('reload');
				},2222);

				if ($('#showMoreButton').length == 0 && count >= home.alsterTake - 1) {
					$('#showMore').append('<button id="showMoreButton">Show more</button>');
				
					$('#showMoreButton').click(function() {
						home.myStuff();
						if ($('#trash').css('display') != 'none') {
							home.deleteAlster(); // toggle delete box
						}
					});
				}

			},
			error: function(error) {
				myStuff.append('Error loading.')
			}
		});
	},

	deleteAlster: function() {
		if ($('#trash').css('display') == 'none') {
			var myStuff = $('#myStuff');
			myStuff.attr('style', 'position:relative; overflow:none !important; height:'+myStuff.height()+'px;'); // masonry fix to prevent overflow:hidden
			setTimeout(function(){
				myStuff.attr('style', 'position:relative; overflow:none !important; height:'+myStuff.height()+'px;'); // again
			},333);
			$('.myAlster').draggable({
				helper: 'clone',
				revert: 'invalid',
				revertDuration: 300
			});

			$('#trash').droppable({
				accept: ".myAlster",
				tolerance: "pointer", // mouse "on target"
				hoverClass: "activeArea",
				drop: function(ev, ui) {
					var droppedItem = $(ui.draggable).clone();
					var alsterId = droppedItem.attr('href');
					alsterId = alsterId.replace('share/?id=', '');
					var imgUrl = droppedItem.find('img').attr('src');
					var toRemove = droppedItem.attr('id');
					droppedItem.remove()
					var removeConfirm = confirm('Are you sure you wish to delete this?');
					if (removeConfirm) {	
						$('#'+toRemove+'').remove();
						
						$.ajax({
							url: 'model/deleteAlster.php',
							type: 'GET',
							data: { alsterId: alsterId, imgUrl: imgUrl },
							success: function(data){
								home.masonry($('#myStuff'), '.myAlster');
							}
						});
					}
				}
			});
		} else {
			$('.myAlster').draggable('destroy')
		}
		$('#trash').slideToggle('slow');
	},

	masonry: function(container, item) {
		var $container = container;
		$container.imagesLoaded(function(){
			$container.masonry({
				itemSelector : item,
				columnWidth: 109,
				isAnimated: true,
				animationOptions: {
					duration: 444,
					easing: 'swing',
					queue: false
				}
			});
		});
		$container.show()
	}
};

$(function(){
	home.myStuff();

	$('#deleteAlster').click(function(e){
		home.deleteAlster();
		e.preventDefault();
	});

	// masonry on my stuff
	home.masonry($('#myStuff'), '.myAlster');

});