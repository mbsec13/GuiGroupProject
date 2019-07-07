$(document).ready(function() {
	// Fade in elements with class hidden
	$('.hidden').fadeIn(1000).removeClass('hidden');

	// Animted scroll to #index-main-scroller's target on click
	$('#index-main-scroller').on('click', function(event) {
		let target = $(this.getAttribute('href'));

		if (target.length) {
			event.preventDefault();
			$('html, body').stop().animate({
				scrollTop: target.offset().top
			}, 1000);
		}
	});

	// Animated scroll to #index-intro-scroller's target on click
	$('#index-intro-scroller').on('click', function(event) {
		let target = $(this.getAttribute('href'));

		if (target.length) {
			event.preventDefault();
			$('html, body').stop().animate({
				scrollTop: target.offset().top
			}, 1000);
		}
	});

	// Animated scroll to top when "BACK TO TOP" on footer clicked
	$('#footer-totop').on('click', function(event) {
		event.preventDefault();
		$('html, body').stop().animate({
			scrollTop: 0
		}, 1000);
	});
});