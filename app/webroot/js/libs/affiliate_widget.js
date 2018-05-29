jQuery(document).ready(function($) {
	// jcarousellite
	$(".js-jcarousellite").jCarouselLite({
		btnNext: ".next",
		btnPrev: ".prev",
		mouseWheel: true
	});
	$('body').delegate('.js-widget-target', 'click', function() {
		window.open($(this).metadata().widget_redirect,'_blank');
	});
});