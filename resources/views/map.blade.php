@extends('layout')

@section('content')

    <div id="main" style="width: 200px;height: 400px;">
        <div id='map'></div>
    </div>

@endsection

@section('scripts')
    <script src='/js/map.js'></script>
    <script src='/js/log.js'></script>
@endsection
