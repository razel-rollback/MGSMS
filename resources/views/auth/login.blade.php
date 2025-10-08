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
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
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
        justify-content: center;
        color: #333;
    }
    .form-control,
    .form-select {
        margin-bottom: 16px;
        border-radius: 6px;
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
</style>

<div class="login-wrapper">
    <div class="login-box">
        <div class="login-logo">
            <img src="{{ asset('images/LOGO.jpg') }}" alt="MGS Logo">
        </div>

        <h4>Login to your account</h4>

        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="role" class="form-label">Select Role</label>
            <select name="role" class="form-select" required>
                <option value="" disabled selected>Choose your role</option>
                <option value="production">Production Staff</option>
                <option value="inventory">Inventory Staff</option>
                <option value="manager">Manager</option>
            </select>

            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required autofocus>

            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>

            <div class="login-actions">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">Keep me logged in</label>
                </div>
                <a href="#" class="text-decoration-none">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100">Log in</button>
        </form>
    </div>
</div>
@endsection