@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Student Dashboard</h2>
        <p>Welcome, {{ auth('student')->user()->name }}!</p>
        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
@endsection
