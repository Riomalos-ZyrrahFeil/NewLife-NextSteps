@extends('layouts.app')

@section('content')

<div class="user-management-panel">
    <a href="{{ route('admin.users.index') }}" class="back-link">← Back to User List</a>

    <h2>Add New Account</h2>

    {{-- Validation Errors Display --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" class="crud-form">
        @csrf
        
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')<span class="error-message">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" required>
                <option value="">Select Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
            @error('role')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <hr>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            @error('password')<span class="error-message">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>

        <button type="submit" class="button-primary submit-btn">Create Account</button>
    </form>
</div>

@endsection