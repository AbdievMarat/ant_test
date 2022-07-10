@extends('layouts.app')

@section('content')

    <h1>{{ $stream->name }}</h1>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <p class="card-text">{{ $stream->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <iframe width="560" height="315" src="http://89.22.229.228:5080/LiveApp/play.html?name={{ $stream->stream_id }}"
                frameborder="0" allowfullscreen></iframe>
    </div>

@endsection
