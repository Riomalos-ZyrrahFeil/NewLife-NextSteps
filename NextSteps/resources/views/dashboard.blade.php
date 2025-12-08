@extends('layouts.app')

@section('content')

<div class="user-management-panel">
    <h2>Welcome, Volunteer!</h2>
    <p>This is your private dashboard. You do not have access to user management.</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="button-primary">Logout</button>
    </form>
</div>

@endsection