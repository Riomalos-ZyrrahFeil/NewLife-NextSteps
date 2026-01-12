<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Portal') }}</title>
    
    <link rel="stylesheet" href="{{ asset('css/auth-style.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud-style.css') }}">

    @auth
        @if(auth()->user()->role === 'volunteer')
            <link rel="stylesheet" href="{{ asset('css/volunteer-style.css') }}">
        @endif
    @endauth
</head>
<body class="{{ auth()->check() ? (auth()->user()->role === 'volunteer'
            ? 'volunteer-body' : '')
            : 'guest-body' }}">

    @auth
        <div class="admin-wrapper">
            @include('layouts.partials.sidebar')

            <main class="page-container">
                @include('layouts.partials.header')

                <div class="page-content-wrapper">
                    @yield('content') 
                </div>
            </main>
        </div>
    @else
        <main class="guest-centered-main">
            <div class="login-card-container">
                @yield('content')
            </div>
        </main>
    @endauth

</body>
</html>