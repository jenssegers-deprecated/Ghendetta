<!doctype html>
<html>
<head>
	<title>I like turtles</title>
	<meta charset="utf-8">
	
	<script src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
</head>
<body onload="initialize()">

<style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    
    #map_canvas {
      height: 100%;
    }
</style>

<div id="map_canvas"></div>

<script>
var gemeentes = <?php echo json_encode($gemeentes); ?>;

function initialize() {
    
    var center = new google.maps.LatLng(51.1083039005843,3.78923551375085);
    
    var options = {
    	zoom: 12,
    	disableDefaultUI: true,
    	center: center,
    	mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    
    var map = new google.maps.Map(document.getElementById("map_canvas"), options);

    // areas bijhouden
	var areas = new Array();

	var coords, polygon, area;
	for (i in gemeentes) {
		coords = gemeentes[i];

		// coordinaten in polygon smijten
		polygon = new Array();
		for (j in coords) {
			polygon.push(new google.maps.LatLng(coords[j][0], coords[j][1]));
		}

		// area aanmaken en aan map koppelen
		areas.push(new google.maps.Polygon({
            paths: polygon,
            /*strokeColor: "#FF0000",*/
            strokeOpacity: 0.8,
            strokeWeight: 3,
            /*fillColor: "#FF0000",*/
            fillOpacity: 0.35
		}).setMap(map));
	}
}

</script>

</body>
</html>