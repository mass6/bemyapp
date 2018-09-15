<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <title></title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='https://npmcdn.com/@turf/turf/turf.min.js'></script>
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.css' rel='stylesheet' />
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
            width: 100%;
        }
    </style>
</head>
<body>
<div id='map'></div>
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiamNhLWFnbnRpbyIsImEiOiJjam0yaGQ5NzkwcmNqM3dvNmhoZXNoMmxxIn0.omnmyL5TeU0KqsEPmsYsCQ';
    var personLocation = [12.567114,55.665983];
    var aedLocation = [12.576425,55.673904];
    var aedLocations = [12.567114,55.665983], [12.567114,55.665983], [12.567114,55.665983]);

    var aedlocations = [
        {
            'lang' : 2.567114,
            'lat' :55.665983
        }


    ]

    var map = new mapboxgl.Map({
        style: 'mapbox://styles/mapbox/light-v9',
        center: [12.567114,55.665983],
        zoom: 15.5,
        pitch: 45,
        bearing: -17.6,
        container: 'map'    });

    map.on('load', function() {
        // Insert the layer beneath any symbol layer.
        var layers = map.getStyle().layers;
        // Add aed symbol
        var marker = document.createElement('div');
        var aed = turf.featureCollection([turf.point(aedLocation)]);
        marker.classList = 'aed';

        var labelLayerId;
        for (var i = 0; i < layers.length; i++) {
            if (layers[i].type === 'symbol' && layers[i].layout['text-field']) {
                labelLayerId = layers[i].id;
                break;
            }
        }
        // Create a new marker
        personkMarker = new mapboxgl.Marker(marker)
            .setLngLat(personLocation)
            .addTo(map)
        for (var i = 0;i < aedLocations.length; i++){
            console.log(aedLocations)
        }
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


        map.addLayer({
            'id': '3d-buildings',
            'source': 'composite',
            'source-layer': 'building',
            'filter': ['==', 'extrude', 'true'],
            'type': 'fill-extrusion',
            'minzoom': 15,
            'paint': {
                'fill-extrusion-color': '#aaa',

                // use an 'interpolate' expression to add a smooth transition effect to the
                // buildings as the user zooms in
                'fill-extrusion-height': [
                    "interpolate", ["linear"], ["zoom"],
                    15, 0,
                    15.05, ["get", "height"]
                ],
                'fill-extrusion-base': [
                    "interpolate", ["linear"], ["zoom"],
                    15, 0,
                    15.05, ["get", "min_height"]
                ],
                'fill-extrusion-opacity': .6
            }
        }, labelLayerId);
        getRoute();
    });

    function getRoute() {
         var start = aedLocation;
         var end = personLocation;
        var directionsRequest = 'https://api.mapbox.com/directions/v5/mapbox/cycling/' + start[0] + ',' + start[1] + ';' + end[0] + ',' + end[1] + '?geometries=geojson&access_token=' + mapboxgl.accessToken;
         $.ajax({
             method: 'GET',
             url: directionsRequest,
         }).done(function(data) {
             var route = data.routes[0].geometry;
             map.addLayer({
                 id: 'route',
                 type: 'line',
                 source: {
                     type: 'geojson',
                     data: {
                         type: 'Feature',
                         geometry: route
                     }
                 },
                 paint: {
                     'line-width': 4,
                     'line-color': '#2527c4'
                 }
             });
             // this is where the code from the next step will go
         });
     }
</script>
</body>
</html>
