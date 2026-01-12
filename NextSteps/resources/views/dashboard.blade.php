@extends('layouts.app')

@section('content')
<div class="user-management-panel">
    <h2>Welcome to the Dashboard</h2>
    <p>You are logged in as <strong>{{ auth()->user()->name }}</strong>
            ({{ ucfirst(auth()->user()->role) }}).</p>
    
    <div class="dashboard-stats">
            </div>
    </div>
@endsection