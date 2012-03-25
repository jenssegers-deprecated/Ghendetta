var map, infoWindow;

function visualize(battlefield) {
    var center = new google.maps.LatLng(51.053498,3.730381);
    
    var options = {
    	zoom: 12,
    	disableDefaultUI: true,
    	center: center,
    	mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    
    map = new google.maps.Map(document.getElementById("map_canvas"), options);

    // store areas
	var areas = new Array();

	var coords, polygon, area;
	for (i in battlefield) {
		coords = battlefield[i].coords;
		
		// put coordinates into a polygon
		polygon = new Array();
		for (j in coords) {
			polygon.push(new google.maps.LatLng(coords[j].lon, coords[j].lat));
		}

		// check if there is a winner for this area
		if (battlefield[i].winner.color !== undefined) {
			var color = battlefield[i].winner.color;
		} else {
			var color = '666666';
		}

		// create area
		areas[i] = new google.maps.Polygon({
            paths: polygon,
            strokeColor: "#333333",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#" + color,
            fillOpacity: 0.35
		});
		
		// put the area layer onto the map
		areas[i].setMap(map);

		// todo: click listener
		//google.maps.event.addListener(areas[i], 'click', tooltip);
	}

	//infowindow = new google.maps.InfoWindow();
}

/*function tooltip(event) {
	infowindow.setContent('Hello');
	infowindow.setPosition(event.latLng);
	infowindow.open(map);
}*/