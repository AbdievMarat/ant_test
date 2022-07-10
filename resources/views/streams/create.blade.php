@extends('layouts.app')

@section('content')

    <form action="{{ route('streams.store') }}" method="POST" class="w-50" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input value="{{ old('name') }}" name="name" type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name">
            @error('name')
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control @if($errors->has('description')) is-invalid @endif" id="description">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="preview" class="form-label">Preview</label>
            <input name="preview" class="form-control @if($errors->has('preview')) is-invalid @endif" type="file" id="preview">
            @error('preview')
            <p class="text-danger">{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

@endsection
