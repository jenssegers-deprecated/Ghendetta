$(document).ready(function() {

	// iOS click functionality: stay in Full Screen mode.

	$(window).click(handleClick);
	function handleClick(e) {
	    var target = $(e.target).closest('a');
	    if( target ) {
	        e.preventDefault();
	        window.location = target.attr('href');
	    }
});