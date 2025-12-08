@extends('layouts.app')

@section('content')

<div class="user-management-panel">
    <a href="{{ route('admin.users.index') }}" class="back-link">← Back to User List</a>

    <h2>Edit Account: {{ $user->name }}</h2>

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

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="crud-form">
        @csrf
        @method('PUT') {{-- CRUCIAL: Spoof the PUT request for update --}}
        
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
            @error('name')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            @error('email')<span class="error-message">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" required>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
            @error('role')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" required>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" {{ old('status', $user->status) == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('status')<span class="error-message">{{ $message }}</span>@enderror
        </div>

        <hr>
        
        <p class="password-note">Leave password fields blank to keep current password.</p>

        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" name="password" id="password">
            @error('password')<span class="error-message">{{ $message }}</span>@enderror
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
        </div>

        <button type="submit" class="button-primary submit-btn">Update Account</button>
    </form>
</div>

@endsection