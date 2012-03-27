var Battlefield = function() {

	var map, polygons, circles;

	var drawRegions = function(regions) {
		delete polygons;
		polygons = new Array();

		var coords, polygon, region, color;
		for (i in regions) {
			region = regions[i];
			coords = region.coords;

			// put coordinates into a polygon
			polygon = new Array();
			for (j in coords) {
				polygon.push(new google.maps.LatLng(coords[j].lon,
						coords[j].lat));
			}

			color = region.clan ? region.clan.color : '333333';

			// create area
			polygons[i] = new google.maps.Polygon({
				paths : polygon,
				strokeColor : '#333333',
				strokeOpacity : 0.8,
				strokeWeight : 2,
				fillColor : '#' + color,
				fillOpacity : 0.35
			});

			// put the area layer onto the map
			polygons[i].setMap(map);
		}
	};

	var drawFights = function(checkins) {
		delete circles;
		circles = new Array();

		var checkin, dot;
		for (i in checkins) {
			checkin = checkins[i];

			// create circle
			circles[i] = new google.maps.Circle({
				strokeWeight : 0,
				fillColor : '#FF0000',
				fillOpacity : 0.8,
				map : map,
				center : new google.maps.LatLng(checkin.lat, checkin.lon),
				radius : 100
			});
		}
	};

	var init = function(element) {
		var options = {
			zoom : 12,
			disableDefaultUI : true,
			center : new google.maps.LatLng(51.067075, 3.737011),
			mapTypeId : google.maps.MapTypeId.TERRAIN
		}

		map = new google.maps.Map(document.getElementById(element), options);

		var self = this;

		// get regions
		$.getJSON(site_url + 'api/regions.json', {}, function(data) {
			if (data) {
				drawRegions(data);
			}

			// get user checkins
			$.getJSON(site_url + 'api/user/fights.json', {}, function(data) {
				if (data) {
					drawFights(data);
				}
			});
		});
	};

	return {
		map : map,
		init : init
	};

}

$(document).ready(function() {

	var battlefield = new Battlefield();
	battlefield.init('map_canvas');

});