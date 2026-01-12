<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-circle">NL</div>
    <span>New Life PH</span>
  </div>
  
  <nav class="sidebar-nav">
    @if(auth()->user()->role === 'admin')
      <a href="{{ route('dashboard') }}" 
        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        Dashboard
      </a>
      
      {{-- UPDATED: Points to admin.visitors.index --}}
      <a href="{{ route('admin.visitors.index') }}" 
        class="{{ request()->routeIs('admin.visitors.*') ? 'active' : '' }}">
        Assigned Volunteer
      </a>
      
      <a href="{{ route('admin.users.index') }}" 
        class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        User Roles & Access
      </a>
      
      <a href="#" 
        class="{{ request()->routeIs('notifications') ? 'active' : '' }}">
        Notifications
      </a>
      
      <a href="#" 
        class="{{ request()->routeIs('settings') ? 'active' : '' }}">
        Settings
      </a>
    @else
      <a href="{{ route('dashboard') }}" 
        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        Dashboard
      </a>
      
      {{-- Placeholder for volunteer specific guest list --}}
      <a href="#" 
        class="{{ request()->routeIs('assigned.guests') ? 'active' : '' }}">
        Assigned Guest List
      </a>
      
      <a href="#" 
        class="{{ request()->routeIs('notifications') ? 'active' : '' }}">
        Notifications
      </a>
    @endif
  </nav>

  <div class="sidebar-footer">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout-btn">Logout</button>
    </form>
  </div>
</aside>