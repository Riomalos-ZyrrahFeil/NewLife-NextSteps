<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'User Management') }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

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
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>