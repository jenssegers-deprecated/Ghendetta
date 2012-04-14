var Mapbox = function() {

	var map;
	var url = 'http://api.tiles.mapbox.com/v3/mapbox.mapbox-streets.jsonp';

	// map layers
	var markerGroup = new L.LayerGroup();
	var specialGroup = new L.LayerGroup();
	var polygonGroup = new L.LayerGroup();

	/**
	 * Add region polygons to the map and bind popups
	 */
	var addPolygons = function(regions) {
		var region, coords, points, polygon, clan, html, max;
		var centerLon = 0, centerLat = 0,totalCoords = 0;

		for (i in regions) {
			region = regions[i];
			coords = region.coords;

			// create polygon points
			points = new Array();
			for (j in coords) {
				centerLon += parseFloat(coords[j].lon);
				centerLat += parseFloat(coords[j].lat);
				totalCoords++;

				points.push(new L.LatLng(coords[j].lon, coords[j].lat));
			}

			// create polygon
			polygon = new L.Polygon(points, {
				color: '#333333',
				opacity: 0.8,
				weight: 2,
				fillColor: '#' + ((region.clans[region.leader] && region.clans[region.leader].color) ? region.clans[region.leader].color : '666666'),
				fillOpacity: 0.35
			});

			// add to layer
			polygonGroup.addLayer(polygon);

			// only show popup when occupied
			if(region.clans[region.leader]) {

				html = '<h1>' + region.name + '</h1><img src="' + region.clans[region.leader].icon + '" /><ul class="statistics">';
				max = region.clans[region.leader].possession;
				for (j in region.clans) {
					clan = region.clans[j];
					html += '<li class="' + clan.name + '"><span style="height:' + (clan.possession / max) * 100 + '%">' + clan.name + ' ' + clan.posession + '%</span></li>';
				}
				html += '</ul>';

				// bind popup
				polygon.bindPopup(html);
			}
		}

		// set map center
		map.setView(new L.LatLng(centerLon / totalCoords, centerLat / totalCoords), 12);
	}

	/**
	 * Add battle markers to the map
	 */
	var addMarkers = function(fights) {
		var fight;

		var fightIcon = L.Icon.extend({
		    iconUrl: static_url + 'img/ico_battle.svg',
		    iconSize: new L.Point(32, 44),
		    shadowUrl: null,
		    iconAnchor: new L.Point(21, 36),
		});

		for (i in fights) {
			fight = fights[i];

			// add to layer
			markerGroup.addLayer(new L.Marker(new L.LatLng(fight.lat, fight.lon), {icon: new fightIcon()}));
		}
	}

	/**
	 * Add venue specials to the map
	 */
	var addSpecials = function(venues) {
		var venue, marker;

		for (i in venues) {
			venue = venues[i];

			venueIcon = L.Icon.extend({
			    iconUrl: static_url + 'img/ico_event.svg',
			    iconSize: new L.Point(32, 44),
			    shadowUrl: null,
			    iconAnchor: new L.Point(16, 16),
			});

			marker = new L.Marker(new L.LatLng(venue.lat, venue.lon), {icon: new venueIcon()});

			// bind popup
			marker.bindPopup('<h2>' + venue.name + '</h2>');

			// add to layer
			specialGroup.addLayer(marker);
		}
	}

	/**
	 * Add layer controls to the map
	 */
	var addControls = function() {

	}

	var init = function(element) {

		// create map
		map = new L.Map(element, {
			minZoom: 10,
			zoom: 12
		});

		wax.tilejson(url, function(tilejson) {
			map.addLayer(new wax.leaf.connector(tilejson));

			// get regions
			$.getJSON(site_url + 'api/regions.json', {}, function(data) {
				if (data) {
					addPolygons(data);
				}
			});

			// get user checkins
			$.getJSON(site_url + 'api/venues.json', {}, function(data) {
				if (data) {
					addSpecials(data);
				}
			});

			// get user checkins
			$.getJSON(site_url + 'api/user/battles.json', {}, function(data) {
				if (data) {
					addMarkers(data);
				}
			});

			map.addLayer(polygonGroup);
			//map.addLayer(specialGroup);
			map.addLayer(markerGroup);

			// add layer controls
			var overlays = {
			    "Battles" : markerGroup,
			    "Specials" : specialGroup
			};
			
			map.addControl(new L.Control.Layers(null, overlays));
		});
	}

	return {
		map : map,
		init : init
	}
}


$(document).ready(function() {

	var mapbox = new Mapbox();
	mapbox.init('map');

});