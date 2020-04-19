
<!DOCTYPE html>
<html>
<head>
	
	<title>Layers Control Tutorial - Leaflet</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>

	<style>
		html, body {
			height: 100%;
			margin: 0;
		}
		#map {
			width: 600px;
			height: 400px;
		}
	</style>

	
</head>
<body>

<div id='map'></div>

<script>

	var mbAttr = '',
		mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

	var satellite   = L.tileLayer(mbUrl, {id: 'mapbox/satellite-v9', attribution: mbAttr}),
		streets  = L.tileLayer(mbUrl, {id: 'mapbox/streets-v11', attribution: mbAttr});

	var map = L.map('map', {
		zoomControl: false,
		center: [39.73, -104.99],
		zoom: 10,
		layers: [satellite]
	});

	var baseLayers = {
		"Satellite": satellite,
		"Streets": streets
	};

	var overlays = {};

	L.control.layers(baseLayers, overlays, {position: 'bottomleft'}).addTo(map);
	
	L.marker([39.61, -105.02]).bindPopup('This is Littleton, CO.').addTo(map),
	L.marker([39.74, -104.99]).bindPopup('This is Denver, CO.').addTo(map),
	L.marker([39.73, -104.8]).bindPopup('This is Aurora, CO.').addTo(map),
	L.marker([39.77, -105.23]).bindPopup('This is Golden, CO.').addTo(map);
</script>



</body>
</html>
