@extends('layout')

@section('styles')

    <style type="text/css">
        .legend {
            background: rgba(239, 238, 238, 0.87);
            display: block;
            position: fixed;
            right: 10px;
            height: 95%;
            width: 300px;
            padding: 10px;
            font-family: sans-serif;
            border-left: 2px solid #f1f1f1;
        }
    </style>
@endsection

@section('content')
    <div id='map'></div>
    @include('legend')
@endsection

@section('scripts')
    <script src='/js/map.js'></script>
    <script src='/js/log.js'></script>
    <script src='/js/log.js'></script>
@endsection
