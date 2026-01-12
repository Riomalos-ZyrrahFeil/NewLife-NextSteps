<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'User Management') }}</title>
    
    <link rel="stylesheet" href="{{ asset('css/app-style.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/partials-style/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials-style/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud-style.css') }}">
    @yield('styles')
</head>
<body>
    <div class="admin-wrapper">
        @include('layouts.partials.sidebar')

        <main class="page-container">
            @include('layouts.partials.header')

            <div class="page-content-wrapper">
                @yield('content') 
            </div>
        </main>
    </div>
    
</body>
</html>