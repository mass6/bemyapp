mapboxgl.accessToken = 'pk.eyJ1IjoiamNhLWFnbnRpbyIsImEiOiJjam0yaGQ5NzkwcmNqM3dvNmhoZXNoMmxxIn0.omnmyL5TeU0KqsEPmsYsCQ';
var personLocation = [incidentLocation.longitude,incidentLocation.latitude]; // [12.567114,55.665983];
var aedLocationClosest =  [parseFloat(aedClosest.longitude),parseFloat(aedClosest.latitude)];

var map = new mapboxgl.Map({
    style: 'mapbox://styles/mapbox/light-v9',
    center: personLocation,
    zoom: 16,
    pitch: 25,
    bearing: 30,
    container: 'map'});

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

    let features = [];
    for (let i = 0; i < aedLocations.length;i++){
        //console.log(aedLocations[i].latitude);
        let longitude = parseFloat(aedLocations[i].longitude);
        let latitude = parseFloat(aedLocations[i].latitude);
        //console.log([longitude,latitude]);
        let location = [longitude , latitude ];
        features.push(turf.point(location));
//        console.log(features);
    }
//    console.log(aedClosest);
    //console.log(features);
    //console.log("Features");
    var marker = document.createElement('div');
    var aed = turf.featureCollection(features);
    //let testLocation = [12.562114,55.665983];
    //var aed = turf.featureCollection([turf.point(aedLocation,{name: 'Location A'}),turf.point(testLocation,{name: 'Location B'})]);
    marker.classList = 'aed';


    // Create a new marker
    personkMarker = new mapboxgl.Marker(marker)
        .setLngLat(personLocation)
        .addTo(map);

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
    var start = aedLocationClosest;
    var end = personLocation;
    var directionsRequest = 'https://api.mapbox.com/directions/v5/mapbox/walking/' + start[0] + ',' + start[1] + ';' + end[0] + ',' + end[1] + '?geometries=geojson&access_token=' + mapboxgl.accessToken;
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
