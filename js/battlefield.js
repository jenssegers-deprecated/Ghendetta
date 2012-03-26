Battlefield = {

	// database info
	regions : new Array(),
	checkins : new Array(),
	
	map : null,
	polygons : new Array(),
	dots : new Array(),
	
	options : {
		zoom : 12,
		disableDefaultUI : true,
		center : new google.maps.LatLng(51.067075, 3.737011),
		mapTypeId : google.maps.MapTypeId.TERRAIN
	},

	init : function(elementId) {
		Battlefield.map = new google.maps.Map(document.getElementById(elementId), Battlefield.options);
		
		var coords, polygon, region, color;
		for (i in Battlefield.regions) {
			region = Battlefield.regions[i];
			coords = region.coords;

			// put coordinates into a polygon
			polygon = new Array();
			for (j in coords) {
				polygon.push(new google.maps.LatLng(coords[j].lon, coords[j].lat));
			}

			// create area
			Battlefield.polygons[i] = new google.maps.Polygon({
				paths : polygon,
				strokeColor : "#333333",
				strokeOpacity : 0.8,
				strokeWeight : 2,
				fillColor : "#" + region.clan.color,
				fillOpacity : 0.35
			});

			// put the area layer onto the map
			Battlefield.polygons[i].setMap(Battlefield.map);
		}
		
		var checkin, dot;
		for(i in Battlefield.checkins) {
			checkin = Battlefield.checkins[i];
			
			//create circle
			Battlefield.dots[i] = new google.maps.Circle({
		        /*strokeColor: "#FF0000",
		        strokeOpacity: 0.8,*/
		        strokeWeight: 0,
		        fillColor: "#FF0000",
		        fillOpacity: 0.8,
		        map: Battlefield.map,
		        center: new google.maps.LatLng(checkin.lat, checkin.lon),
		        radius: 100
		    });
		}
	}
	
}