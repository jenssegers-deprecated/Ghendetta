var Mapbox = function() {
	
	var map;
	
	var addLayers = function(regions) {
		var region, coords, points, polygon;
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
				
				points.push(new google.maps.LatLng(coords[j].lon, coords[j].lat));
			}
			
			// create polygon
			polygons[i] = new google.maps.Polygon({
				paths: points,
				strokeColor: '#333333',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#' + (region.leader && region.leader.color ? region.leader.color : '666666'),
				fillOpacity: (region.leader && region.leader.color ? 0.5 : 0.3)
			});
			
			// add to map
			polygons[i].setMap(map);
		}
		
		// set center
		map.setCenter(new google.maps.LatLng(centerLon / totalCoords, centerLat / totalCoords));
		map.setZoom(12);
	}
	
	var addMarkers = function(fights) {
		var fight, icon;
		var icons = new Array();
		
		for (i in fights) {
			fight = fights[i];
			
			// create icon
			icons[i] = new google.maps.Marker({
			      position: new google.maps.LatLng(fight.lat, fight.lon),
			      map: map,
			      icon: static_url + 'img/ico_battle.png'
			});
		}
	}
	
	var init = function(element) {
		
		var options = {
		  getTileUrl: function(coord, zoom) {
			  return "http://mt0.google.com/vt/lyrs=8bit,m@174000000&hl=en&src=app&z=" + zoom + "&x=" + coord.x + "&y=" + + coord.y +" ";
		  },
		  tileSize: new google.maps.Size(256, 256)
		};

		var bitMapType = new google.maps.ImageMapType(options);
		
		map = new google.maps.Map(document.getElementById(element), { disableDefaultUI: true });
		map.setMapTypeId('roadmap');
		map.overlayMapTypes.insertAt(0, bitMapType);
		
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