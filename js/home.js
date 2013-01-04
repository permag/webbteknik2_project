var home = {
	
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

							}
						});
					}
				}
			});
		} else {
			$('.myAlster').draggable('destroy')
		}
		$('#trash').slideToggle('slow');
	}
};

$(function(){
	$('#deleteAlster').click(function(e){
		home.deleteAlster();
		e.preventDefault();
	});
});