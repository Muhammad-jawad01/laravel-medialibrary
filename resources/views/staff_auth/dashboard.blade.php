@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Student Dashboard</h2>
        <p>Welcome, {{ auth('staff')->user()->name }}!</p>
        <form method="POST" action="{{ route('staff.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
@endsection
