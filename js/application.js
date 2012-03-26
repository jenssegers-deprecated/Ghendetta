$(document).ready(function() {

	/*
	 * Use ajax to refresh a user's checkins
	 * Will do nothing when the user is not authenticated
	 */
	$.ajax({
		url : site_url + 'foursquare/refresh?ajax=1',
		cache : false
	});

});