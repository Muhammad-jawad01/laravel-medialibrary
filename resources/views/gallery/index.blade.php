@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Gallery List</h1>
        <a href="{{ route('gallery.create') }}" class="btn btn-success mb-3">Add New Gallery</a>
        @foreach ($galleries as $gallery)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $gallery->title }}</h5>
                    <p class="card-text">{{ $gallery->description }}</p>
                    <div class="row">
                        @foreach ($gallery->getMedia('images') as $media)
                            <div class="col-md-3 mb-2">
                                <img src="{{ $media->getUrl() }}" class="img-fluid rounded">
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('gallery.show', $gallery->id) }}" class="btn btn-primary mt-2">View</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
