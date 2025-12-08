@extends('layouts.app')

@section('content')

<div class="user-management-panel">
    <div class="header-controls">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <input type="text" name="search" placeholder="Search by name, email, etc..." 
                   value="{{ request('search') }}">
            <button type="submit">Search</button>
        </form>

        <select name="filter_status" onchange="this.form.submit()">
            <option value="">Current Batch</option>
            <option value="active" {{ request('filter_status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('filter_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        
        <a href="{{ route('admin.users.create') }}" class="button-primary">Add Account</a>
    </div>

    <h2>User Roles & Access</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Login</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="role-tag role-{{ strtolower($user->role) }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td><span class="status-{{ strtolower($user->status) }}">{{ ucfirst($user->status) }}</span></td>
                <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'N/A' }}</td>
                <td class="actions">
                    <a href="{{ route('admin.users.edit', $user) }}">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-form">
                        @csrf
                        @method('DELETE')
                        {{-- @can('delete', $user) --}}
                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                        {{-- @endcan --}}
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection