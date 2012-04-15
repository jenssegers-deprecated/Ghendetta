$(document).ready(function()
{

	// init Mapbox

	if($('#map').length)
	{
		var mapbox = new Mapbox();
		mapbox.init('map');

		// toggle map markers

		$('.legend dt, .legend dd').bind('click', function()
		{
			layerClass = $(this).attr('class');
			if(layerClass.length)
			{
				mapbox.toggleLayer(mapbox.layers[layerClass]);
			}
		});

	}

	// iOS click functionality: stay in Full Screen mode.

	$(window).bind('click',handleClick);

	function handleClick(e)
	{
	    var target = $(e.target).closest('a');
	    if( target && target.attr('href') )
		{
	        e.preventDefault();
	        window.location = target.attr('href');
	    }
	}

	// toggle legend

	$('.js-toggle-legend').bind('click', function()
	{
	  	$('.legend-holder').fadeToggle('fast');
	});

});