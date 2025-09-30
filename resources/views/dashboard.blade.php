@extends('layouts.app')


@section('content')
    {{-- @if (auth()->user()->hasrole()) --}}
    @if (auth()->user()->id === 1)
        <h1 class="text-dark">hello</h1>
        @include('card1')
    @endif
    @if (auth()->user()->email === 'admin@admin.com')
        <h1 class="text-dark">hello</h1>
        @include('card2')
    @endif
@endsection
