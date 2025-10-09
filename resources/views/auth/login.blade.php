@extends('layouts.auth')

@section('content')
<style>
    body {
        background: #e6f0fa;
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-box {
        background: #fff;
        padding: 40px 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 420px;
    }

    .login-logo {
        text-align: center;
        margin-bottom: 16px;
    }

    .login-logo img {
        max-width: 160px;
    }

    .login-box h4 {
        text-align: center;
        font-weight: 600;
        margin-bottom: 24px;
        color: #333;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 6px;
        color: #333;
    }

    .form-control,
    .form-select {
        margin-bottom: 16px;
        border-radius: 6px;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        font-size: 0.875rem;
        color: #dc3545;
    }

    .form-check-label {
        font-size: 0.95rem;
        color: #333;
    }

    .login-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .btn-primary {
        margin-top: 8px;
        background-color: #007bff;
        border: none;
        border-radius: 6px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .text-decoration-none {
        font-size: 0.95rem;
        color: #007bff;
    }

    .alert {
        margin-bottom: 20px;
        border-radius: 6px;
    }
</style>

<div class="login-wrapper">
    <div class="login-box">
        <div class="login-logo">
            <img src="{{ asset('images/LOGO.jpg') }}" alt="MGS Logo">
        </div>

        <!-- Session-based error message (e.g., invalid credentials) -->
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h4>Login to your account</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    value="{{ old('email') }}"
                    autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password">
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me and Forgot Password -->
            <div class="login-actions">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Keep me logged in</label>
                </div>
                <a href="#" class="text-decoration-none">Forgot Password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">Log in</button>
        </form>
    </div>
</div>
@endsection