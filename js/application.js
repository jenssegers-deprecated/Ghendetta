$(document).ready(function()
{

	// init Mapbox

	if($('#map').length)
	{
		var mapbox = new Mapbox();
		mapbox.init('map');

		// hard-coded set to true for now. @todo replace with function

		$('.battles input').attr('checked', true);
		$('.specials input').attr('checked', true);

		// legend toggle functionality

		$('.legend dt, .legend dd').bind('click', function()
		{
			layerClass = $(this).attr('class').split(/\s+/);

			if(layerClass.length)
			{
				$.each(layerClass, function(index, item)
				{
				    if (item != 'chk')
					{
						// toggle layers on and off

						mapbox.toggleLayer(mapbox.layers[item]);

						// toggle checkboxes on and off. @todo replace with function

						if(mapbox.layers[item].state === 1)
						{
							$('.' + item + ' input').attr('checked', true);
						}
						else
						{
							$('.' + item + ' input').attr('checked', false);
						}
					}
			    });
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