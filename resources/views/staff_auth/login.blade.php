@extends('layouts.auth')

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100" style="background: #181818;">
        <div class="w-100" style="max-width: 400px; background: #23272b; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.3); color: #fff;">
            <h2 class="mb-4 text-center">staff Login</h2>
            <form method="POST" action="{{ route('staff.login') }}">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="mt-3 text-center">
                <a href="{{ route('staff.register') }}" style="color: #9ecbff;">Don't have an account? Register</a>
            </div>
        </div>
    </div>
@endsection
