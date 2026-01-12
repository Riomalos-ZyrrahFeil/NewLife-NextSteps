@extends('layouts.app')

@section('content')
<div class="user-management-panel">
  <a href="{{ route('admin.users.index') }}" class="back-link">
    ← Back to User List
  </a>

  <h2>Edit Account: {{ $user->full_name }}</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.users.update', $user) }}" 
    class="crud-form" autocomplete="off">
    @csrf
    @method('PUT')
    
    <div class="form-group">
      <label for="first_name">First Name</label>
      <input type="text" name="first_name" id="first_name" 
        value="{{ old('first_name', $user->first_name) }}" 
        autocomplete="new-password" required>
      @error('first_name')
        <span class="error-message">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label for="last_name">Last Name</label>
      <input type="text" name="last_name" id="last_name" 
        value="{{ old('last_name', $user->last_name) }}" 
        autocomplete="new-password" required>
      @error('last_name')
        <span class="error-message">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" 
        value="{{ old('email', $user->email) }}" 
        autocomplete="new-password" required>
      @error('email')
        <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    
    @if($user->role !== 'admin')
      <div class="form-group">
        <label for="role">Role</label>
        <select name="role" id="role" required>
          @foreach ($roles as $role)
            <option value="{{ $role }}" 
              {{ old('role', $user->role) == $role ? 'selected' : '' }}>
              {{ ucfirst($role) }}
            </option>
          @endforeach
        </select>
        @error('role')
          <span class="error-message">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" required>
          @foreach ($statuses as $status)
            <option value="{{ $status }}" 
              {{ old('status', $user->status) == $status ? 'selected' : '' }}>
              {{ ucfirst($status) }}
            </option>
          @endforeach
        </select>
        @error('status')
          <span class="error-message">{{ $message }}</span>
        @enderror
      </div>
    @else
      <input type="hidden" name="role" value="admin">
      <input type="hidden" name="status" value="{{ $user->status }}">
    @endif

    <hr>
    
    <p class="password-note">
      Leave password fields blank to keep current password.
    </p>

    <div class="form-group">
      <label for="password">New Password</label>
      <input type="password" name="password" id="password" 
        autocomplete="new-password">
      @error('password')
        <span class="error-message">{{ $message }}</span>
      @enderror
    </div>
    
    <div class="form-group">
      <label for="password_confirmation">Confirm New Password</label>
      <input type="password" name="password_confirmation" 
        id="password_confirmation" autocomplete="new-password">
    </div>

    <button type="submit" class="button-primary submit-btn">
      Update Account
    </button>
  </form>
</div>
@endsection