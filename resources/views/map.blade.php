@extends('layout')

@section('content')

    <div id='map'></div>

@endsection

@section('scripts')
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiamNhLWFnbnRpbyIsImEiOiJjam0yaGQ5NzkwcmNqM3dvNmhoZXNoMmxxIn0.omnmyL5TeU0KqsEPmsYsCQ';
        var personLocation = [12.567114,55.665983];
        var aedLocation = [12.576425,55.673904];
        //var aedLocations = [12.567114,55.665983], [12.567114,55.665983], [12.567114,55.665983]);
        console.log(aedLocations);

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
            var labelLayerId;
            for (var i = 0; i < layers.length; i++) {
                if (layers[i].type === 'symbol' && layers[i].layout['text-field']) {
                    labelLayerId = layers[i].id;
                    break;
                }
            }
            // Add aed symbol
            /*
            for (var i = 0;i < aedLocations.length; i++) {
                aedLocation = [aedLocations[i]['latitude'], aedLocations[i]['longitude']]
                */
            console.log(aedLocation)

            var marker = document.createElement('div');
            var aed = turf.featureCollection([turf.point(aedLocation)]);
            marker.classList = 'aed';
            getRoute();

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


@endsection
