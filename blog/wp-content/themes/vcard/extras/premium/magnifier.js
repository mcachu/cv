jQuery(function($){
	if($('#magnifier').length == 0) return;
    
	var currentImage = null;
	
    // The size of the magnifier
	var magSize = $('#magnifier .image').width();
    magSize = 200;
	
	$('img.magnify').each(function(){
		var $$ = $(this);
		
		// Handle the mouse events
		$$.mouseover(function(e){
				currentImage = $$;
				$('#magnifier').show().css({
                    'top' : e.pageY - magSize/2,
                    'left' : e.pageX - magSize/2
				});
				
				$('#magnifier .image').css({
					'background-image' : 'url('+ currentImage.attr('src') +')'
				});
			});
        
        var $p = $$.parent();
        var ratio = $p.width() / Number($$.attr('width'));
        $p.css('height', Math.round(Number($$.attr('height')) * ratio));
        $$.hide();
        
        $('<img />')
            .load(function(){$$.fadeIn();})
            .attr('src', $$.attr('src'));
        
	});
    
    // Hide the images while they're loading
	
	$(document).mousemove(function(e){
		if(currentImage == null) return;
		var $$ = currentImage;
		if( e.pageX > $$.offset().left && e.pageX < $$.offset().left + $$.width() && e.pageY > $$.offset().top && e.pageY < $$.offset().top + $$.height()){
			// We are still hovering over the image
			$('#magnifier').css({
				'top' : e.clientY - magSize/2 - 4,
				'left' : e.clientX - magSize/2
			});
			
			// Get the relative position of the magnifier
			var x = (e.pageX - currentImage.offset().left) / currentImage.width() * currentImage.attr('width');
			var y = (e.pageY - currentImage.offset().top) / currentImage.height() * currentImage.attr('height');
			
			$('#magnifier .image').css('background-position', (-x + magSize/2)+'px '+(-y + magSize/2)+'px');
		}
		else{
			$('#magnifier').hide();
			
			currentImage = null;
		}
	});
})