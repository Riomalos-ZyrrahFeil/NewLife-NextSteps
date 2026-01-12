<header class="app-header">
    <div class="header-user-info">
        <span class="welcome-text">Welcome, {{ auth()->user()->first_name }}!</span>
        <span class="role-text">
            {{ auth()->user()->role === 'admin'
                    ? 'Administrator'
                    : ucfirst(auth()->user()->role) }}
        </span>
    </div>
</header>