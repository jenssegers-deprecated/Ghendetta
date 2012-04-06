var Mapbox = function() {
	
	var map;
	var url = 'http://api.tiles.mapbox.com/v3/mapbox.mapbox-streets.jsonp';
	
	var addLayers = function(regions) {
		var region, coords, points, polygon, clan, html;
		var polygons = new Array();
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
			polygons[i] = new L.Polygon(points, {
				color: '#333333',
				opacity: 0.8,
				weight: 2,
				fillColor: '#' + ((region.clans[region.leader] && region.clans[region.leader].color) ? region.clans[region.leader].color : '666666'),
				fillOpacity: 0.35
			});
			
			// add to map
			map.addLayer(polygons[i]);
			
			html = '<h1>' + region.name + '</h1><img src="' + region.clans[region.leader].icon + '" /><ul class="statistics">';
			for (j in region.clans) {
				clan = region.clans[j];
				html += '<li class="' + clan.name + '" style="height:' + clan.possession + '%">' + clan.name + '</li>';
			}
			html += '</ul>';
			
			// bind popup
			polygons[i].bindPopup(html);
		}
		
		// set map center
		map.setView(new L.LatLng(centerLon / totalCoords, centerLat / totalCoords), 12);
	}
	
	var addMarkers = function(fights) {
		var fight, icon;
		var icons = new Array();
		
		var fightIcon = L.Icon.extend({
		    iconUrl: static_url + 'img/ico_battle.png',
		    iconSize: new L.Point(64, 40),
		    shadowUrl: null,
		    iconAnchor: new L.Point(32, 38),
		});
		
		for (i in fights) {
			fight = fights[i];
			
			// create icon
			icons[i] = new L.Marker(new L.LatLng(fight.lat, fight.lon), {icon: new fightIcon()});
			
			// add to map
			map.addLayer(icons[i]);
		}
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
					addLayers(data);
				}
	
				// get user checkins
				$.getJSON(site_url + 'api/user/battles.json', {}, function(data) {
					if (data) {
						addMarkers(data);
					}
				});
			});
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