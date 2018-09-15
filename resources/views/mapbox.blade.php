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
        .person {
            margin: -10px -10px;
            width: 20px;
            height: 20px;
            border: 2px solid #fff;
            border-radius: 50%;
            background: #3887be;
            pointer-events: none;
        }
        .aed {
            margin: -10px -10px;
            width: 20px;
            height: 20px;
            border: 2px solid #fff;
            border-radius: 50%;
            background: #3887be;
            pointer-events: none;
        }

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
    var personLocation = [-83.093, 42.376];
    var aedLocation = [-83.083, 42.363];
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
        center: personLocation, // starting position
        zoom: 12 // starting zoom
    });
        // Create a marker
        map.on('load', function() {
        var marker = document.createElement('div');
        var aed = turf.featureCollection([turf.point(aedLocation)]);
        marker.classList = 'aed';

        // Create a new marker
        personkMarker = new mapboxgl.Marker(marker)
            .setLngLat(personLocation)
            .addTo(map)
            map.addLayer({
                id: 'aed',
                type: 'circle',
                source: {
                    data: aed,
                    type: 'geojson'
                },
                paint: {
                    'circle-radius': 10,
                    'circle-color': 'green',
                    'circle-stroke-color': '#0FB75E',
                    'circle-stroke-width': 2
                }
            });
            // Create a symbol layer on top of circle layer
            map.addLayer({
                id: 'aed-symbol',
                type: 'symbol',
                source: {
                    data: aed,
                    type: 'geojson'
                },
                layout: {
                    'icon-image': 'grocery-15',
                    'icon-size': 1
                },
                paint: {
                    'text-color': '#bcbdbe'
                }
    });

    });
</script>
</body>
</html>
