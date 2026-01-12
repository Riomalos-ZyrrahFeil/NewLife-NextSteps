@extends('layouts.app')

@section('content')

{{-- 1. Top Search & Filter Bar --}}
<div class="page-header-top">
  <form method="GET"
        action="{{ route('admin.users.index') }}"
        class="search-filter-form">
    <div class="search-input-wrapper">
      <input type="text" 
            name="search"
            placeholder="Search by name, email"
            value="{{ request('search') }}">
      <button type="submit"
              class="btn-search">Search</button>
    </div>

    <div class="filter-wrapper">
      <select name="filter_status" onchange="this.form.submit()">
        <option value="">Current Batch</option>
        <option value="active" {{ request('filter_status') == 'active'
            ? 'selected'
            : '' }}>Active</option>
        <option value="inactive" {{ request('filter_status') == 'inactive'
            ? 'selected'
            : '' }}>Inactive</option>
      </select>
    </div>
  </form>
</div>

<div class="user-card">
  <div class="table-header-row">
      <h3>User Roles & Access</h3>
      <a href="{{ route('admin.users.create') }}" class="btn-add">
          Add Account
      </a>
  </div>

  <table class="custom-table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr>
        <td class="font-bold">{{ $user->full_name }}</td>
        <td class="text-secondary">{{ $user->email }}</td>
        <td>
            <span class="badge badge-role badge-{{ strtolower($user->role) }}">
              {{ ucfirst($user->role) }}
            </span>
        </td>
        <td>
            <span class="badge badge-status badge-{{ strtolower($user->status) }}">
              {{ ucfirst($user->status) }}
            </span>
        </td>
        <td class="actions">
          <div class="action-row">
            <a href="{{ route('admin.users.edit', $user) }}" class="action-link edit">Edit</a>
            
            {{-- UPDATED: Reverted to direct form submission with native alert --}}
            <form action="{{ route('admin.users.destroy', $user) }}"
                  method="POST"
                  class="inline-form" 
                  onsubmit="return confirm('Are you sure you want to delete {{ $user->full_name }}? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-user-trigger">
                    Delete
                </button>
            </form>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection