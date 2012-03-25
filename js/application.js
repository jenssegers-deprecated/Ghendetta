$(document).ready(function() {

	/*
	 * Use ajax to refresh a user's checkins
	 * Will do nothing when the user is not authenticated
	 */
	$.ajax({
		url : '/foursquare/refresh/ajax',
		cache : false
	});

});