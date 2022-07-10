@extends('layouts.app')

@section('content')

    <h1>Streams
        @if(Auth::check())
            <a href="{{ route('streams.create') }}" class="btn btn-outline-primary" type="submit">Create</a>
        @endif
    </h1>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($streams as $stream)
        <div class="col">
            <div class="card h-100">
                @if ($stream->status == 'broadcasting')
                    <div class="text-center">
                        <h2>Online</h2>
                    </div>
                @else
                    <img src="{{ asset('/storage/' . $stream->preview) }}" class="card-img-top" alt="#">
                @endif

                <div class="card-body">
                    <h5 class="card-title"><a
                            href="{{ route('streams.show', ['stream' => $stream->id ]) }}">{{ $stream->name }}</a></h5>
                    <p class="card-text">{{ $stream->description }}</p>
                </div>
                <div class="card-footer d-flex">
                    @can('view', $stream)
                        <button class="btn btn-outline-primary copy_url" data-url="{{ $stream->rtmp_url }}">Copy URL</button>
                    @endcan
                    @can('update', $stream)
                        <a class="btn btn-outline-primary mx-2"
                           href="{{ route('streams.edit', ['stream' => $stream->id ]) }}">Edit</a>
                    @endcan
                    @can('delete', $stream)
                        <form action="{{ route('streams.destroy', ['stream' => $stream->id ]) }}" method="POST"
                              class="w-50">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row justify-content-md-center p-5">
        <div class="col-md-auto">
            {{ $streams->links('pagination::bootstrap-4') }}
        </div>
    </div>

@endsection
