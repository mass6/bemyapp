<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <title>Delivery App</title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src='https://npmcdn.com/@turf/turf/turf.min.js'></script>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.47.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.47.0/mapbox-gl.css' rel='stylesheet' />
    <style>

        body {
            margin: 0;
            padding: 0;
        }

        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
        }
    </style>
</head>
<body>
<div id='map' class='contain'></div>
<script>
    var truckLocation = [-83.093, 42.376];
    var warehouseLocation = [-83.083, 42.363];
    var lastQueryTime = 0;
    var lastAtRestaurant = 0;
    var keepTrack = [];
    var currentSchedule = [];
    var currentRoute = null;
    var pointHopper = {};
    var pause = true;
    var speedFactor = 50;

    // Add your access token
    mapboxgl.accessToken = 'pk.eyJ1IjoiamNhLWFnbnRpbyIsImEiOiJjam0yaGQ5NzkwcmNqM3dvNmhoZXNoMmxxIn0.omnmyL5TeU0KqsEPmsYsCQ';

    // Initialize a map
    var map = new mapboxgl.Map({
        container: 'map', // container id
        style: 'mapbox://styles/mapbox/light-v9', // stylesheet location
        center: truckLocation, // starting position
        zoom: 12 // starting zoom
    });
</script>
</body>
</html>
