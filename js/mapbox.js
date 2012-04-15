var Mapbox = function() {

	var map;
	var url = 'http://api.tiles.mapbox.com/v3/mapbox.mapbox-streets.jsonp';

	// map layers
	var battlesGroup = new L.LayerGroup();
	var specialGroup = new L.LayerGroup();
	var polygonGroup = new L.LayerGroup();
	
	// control
	var layerControl = new L.Control.Layers();

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

		// add polygons to map
		map.addLayer(polygonGroup);
		
		// set map center
		map.setView(new L.LatLng(centerLon / totalCoords, centerLat / totalCoords), 12);
	}

	/**
	 * Add battle markers to the map
	 */
	var addBattles = function(battles) {
		var battle;

		var battleIcon = L.Icon.extend({
		    iconUrl: static_url + 'img/ico_battle.svg',
		    iconSize: new L.Point(32, 44),
		    shadowUrl: null,
		    iconAnchor: new L.Point(21, 36),
		});

		for (i in battles) {
			battle = battles[i];

			// add to layer
			battlesGroup.addLayer(new L.Marker(new L.LatLng(battle.lat, battle.lon), {icon: new battleIcon()}));
		}
		
		// add layer to control
		layerControl.addOverlay(battlesGroup, "Battles");
		
		// add battles to map
		map.addLayer(battlesGroup);
	}

	/**
	 * Add venue specials to the map
	 */
	var addSpecials = function(venues) {
		var venue, marker, html;

		for (i in venues) {
			venue = venues[i];

			venueIcon = L.Icon.extend({
			    iconUrl: static_url + 'img/ico_arena.svg',
			    iconSize: new L.Point(32, 44),
			    shadowUrl: null,
			    iconAnchor: new L.Point(16, 16),
			});

			// create marker
			marker = new L.Marker(new L.LatLng(venue.lat, venue.lon), {icon: new venueIcon()});

			// generate html
			html = '<h1>' + venue.name + '</h1><img src="/img/ico_tower.svg" /><span class="points">+' + venue.multiplier + '</span>';

			// bind popup
			marker.bindPopup(html);

			// add to layer to group
			specialGroup.addLayer(marker);
		}
		
		// add layer to control
		layerControl.addOverlay(specialGroup, "Specials");
	}

	/**
	 * Add layer controls to the map
	 */
	var toggleLayer = function() {
		
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
					
					console.log(map.layers);
				}
			});

			// get user checkins
			$.getJSON(site_url + 'api/user/battles.json', {}, function(data) {
				if (data) {
					addBattles(data);
				}
			});

			// add layer control
			map.addControl(layerControl);
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