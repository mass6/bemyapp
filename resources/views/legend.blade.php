<div id="main" class="legend">
    <h3>Legend</h3>
    @foreach($deployment->events as $event)
        <p>{{$event->name}}: <small>{{$event->created_at->toTimeString()}}</small></p>
    @endforeach
</div>
