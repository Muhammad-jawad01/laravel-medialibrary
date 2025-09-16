@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $gallery->title }}</h1>
        <p>{{ $gallery->description }}</p>
        <div class="row">
            @foreach ($gallery->getMedia('images') as $media)
                <div class="col-md-3 mb-2">
                    <img src="{{ $media->getUrl() }}" class="img-fluid rounded">
                </div>
            @endforeach
        </div>
        <a href="{{ route('gallery.index') }}" class="btn btn-secondary mt-3">Back to Gallery</a>
    </div>
@endsection
