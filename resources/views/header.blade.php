<head>
    <meta charset='utf-8' />
    <title></title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    {{--<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>--}}
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
            background: #be0110;
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
    @yield('styles')
</head>
