@extends('layouts.guest') 

@section('content')

<div class="login-card">
    <div class="card-header">
        <div class="logo-circle">NL</div>
        <h1>New Life PH</h1>
        <p>GuestConnect</p>
    </div>

    <div class="card-body">
        <p class="welcome-text">Welcome Back</p>
        <p class="subtitle">Please sign in to continue</p>
        
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            
            <div class="input-group">
                <label for="email">Username/Email</label>
                <input type="text" name="email" id="email" placeholder="Enter your email" required autofocus>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="sign-in-button">
                Sign In >
            </button>
        </form>
    </div>
</div>

@endsection