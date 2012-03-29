var Mapbox = function() {
	
	var map;
	var url = 'http://api.tiles.mapbox.com/v3/mapbox.mapbox-streets.jsonp';
	
	var addLayers = function(regions) {
		var region, coords, points, polygon;
		var polygons = new Array();
		
		for (i in regions) {
			region = regions[i];
			coords = region.coords;
			
			// create polygon points
			points = new Array();
			for (j in coords) {
				points.push(new L.LatLng(coords[j].lon, coords[j].lat));
			}
			
			// create polygon
			polygons[i] = new L.Polygon(points, {
				color: '#333333',
				opacity: 0.8,
				weight: 2,
				fillColor: '#' + (region.leader ? region.leader.color : '666666'),
				fillOpacity: 0.35
			});
			
			// add to map
			map.addLayer(polygons[i]);
			//polygons[i].bindPopup(region.name);
		}
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
		
		wax.tilejson(url, function(tilejson) {
			// create map
			map = new L.Map(element, {
				minZoom: 10,
				zoom: 12
			});
			map.addLayer(new wax.leaf.connector(tilejson)).setView(new L.LatLng(51.067075, 3.737011), 12);
			
			// get regions
			$.getJSON(site_url + 'api/regions.json', {}, function(data) {
				if (data) {
					addLayers(data);
				}
	
				// get user checkins
				$.getJSON(site_url + 'api/user/fights.json', {}, function(data) {
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