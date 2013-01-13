var home = {

	alsterStart: 0,
	alsterTake: 5,

	myStuff: function() {
		var myStuff = $('#myStuff');
		myStuff.append('<div id="alsterLoading">Loading...</div>');

		$.ajax({
			url: 'model/getAlster.php',
			type: 'GET',
			dataType: 'JSON',
			data: { start: home.alsterStart, take: home.alsterTake },
			success: function(data) {
				$('#alsterLoading').remove();
				home.alsterStart = home.alsterStart + home.alsterTake;
				$.each(data, function(key, val) {
					var elHeight = myStuff.height() + 20;
					myStuff.append('<a style="position:absolute;top:'+elHeight+'px" href="share/?id='+ val.alsterId +'" id="myAlster'+ key +'" class="myAlster"><img src="alster/'+ val.alsterUrl +'" width="100" /></a>');
					myStuff.masonry('reload');
				});
				window.setTimeout(function(){
					$("html, body").animate({ scrollTop: $(document).height() }, "slow");
					myStuff.masonry('reload');
				},555);
				window.setTimeout(function(){
					myStuff.masonry('reload');
				},3333);
				if ($('#showMoreButton').length == 0) {
					$('#showMore').append('<button id="showMoreButton">Show more</button>');
				
					$('#showMoreButton').click(function() {
						home.myStuff();
					});
				}

			},
			error: function(error) {
				myStuff.append('Error loading more alsters.')
			}
		});
	},

	deleteAlster: function() {
		if ($('#trash').css('display') == 'none') {
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