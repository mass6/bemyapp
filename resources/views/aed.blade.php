@extends('layout')

@section('content')

    <div id='map'></div>

@endsection

@section('scripts')
    <script>

        console.log(aeds);

        mapboxgl.accessToken = 'pk.eyJ1IjoiamNhLWFnbnRpbyIsImEiOiJjam0yaGQ5NzkwcmNqM3dvNmhoZXNoMmxxIn0.omnmyL5TeU0KqsEPmsYsCQ';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v10',
            center: [-84.5125, 39.1015],
            zoom: 12
        });

        map.on('load', function() {
            getRoute();
        });

        function getRoute() {
            var start = [-84.518641, 39.134270];
            var end = [-84.512023, 39.102779];
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
                        'line-width': 2
                    }
                });


                map.addLayer({
                    id: 'start',
                    type: 'circle',
                    source: {
                        type: 'geojson',
                        data: {
                            type: 'Feature',
                            geometry: {
                                type: 'Point',
                                coordinates: start
                            }
                        }
                    }
                });
                map.addLayer({
                    id: 'end',
                    type: 'circle',
                    source: {
                        type: 'geojson',
                        data: {
                            type: 'Feature',
                            geometry: {
                                type: 'Point',
                                coordinates: end
                            }
                        }
                    }
                });
                // this is where the JavaScript from the next step will go



            });
        }



    </script>
@endsection
