jQuery(document).ready(function($) {	
	$('a[href$="mystem-edd-modal"]').click(function(event) {
		event.preventDefault();
		$('#mystem-edd-modal-overlay').fadeIn(400, function() {
			$('#mystem-edd-modal-window').show('slide', {
				direction: 'up'
			}, 400)
		});
		$('html, body').css('overflow', 'hidden', 'important');		
	});	
	$('#mystem-edd-modal-close').click(function() {
		$('#mystem-edd-modal-window').hide('slide', {
			direction: 'up'
			}, 400, function() {
			$('#mystem-edd-modal-overlay').fadeOut(400);
			$('html, body').css('overflow', '');			
		})
	});
	$(this).keydown(function(eventObject) {
		if (eventObject.which == 27) {
			$('#mystem-edd-modal-window').hide('slide', {
				direction: 'up'
				}, 400, function() {
				$('#mystem-edd-modal-overlay').fadeOut(400);
				$('html, body').css('overflow', '');
				$('#mystem-edd-modal-close').hide();                
			})
		}
	});
	$('#mystem-edd-modal-overclose').click(function() {
		$('#mystem-edd-modal-window').hide('slide', {
			direction: 'up'
			}, 400, function() {
			$('#mystem-edd-modal-overlay').fadeOut(400);
			$('html, body').css('overflow', '');
			$('#mystem-edd-modal-close').hide();            
		})
	});	
});