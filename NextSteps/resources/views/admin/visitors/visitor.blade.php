@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/visitors.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="{{ asset('js/visitors.js') }}" defer></script>
@endsection

@section('content')
<div class="tracker-container">
  <div class="tracker-header">
    <h2>Assigned Volunteer</h2>
  </div>

  <div class="action-bar">
    <div class="search-container">
      <form action="{{ route('admin.visitors.index') }}" method="GET">
        <input type="text" name="search" value="{{ request('search') }}"
          placeholder="Search by name, phone..." class="search-input"
          onchange="this.form.submit()">
      </form>
    </div>

    <form action="{{ route('admin.visitors.import') }}" method="POST"
      enctype="multipart/form-data" id="importForm">
      @csrf
      <input type="file" name="file" id="excelFile" style="display: none;"
        onchange="document.getElementById('importForm').submit();">
      <button type="button" class="btn-export"
        onclick="document.getElementById('excelFile').click();">
        Import Excel
      </button>
    </form>
  </div>

  <div class="user-card">
    <div class="table-controls">
      <span class="table-title">Visitor Table</span>
    </div>

    <table class="custom-table">
      <thead>
        <tr>
          <th>NAME</th>
          <th>CONTACT</th>
          <th>FIRST VISIT</th>
          <th>FOLLOW-UP</th>
          <th>STATUS</th>
          <th>ASSIGNED VOLUNTEER</th>
        </tr>
      </thead>
      <tbody>
        @foreach($visitors as $visitor)
          <tr>
            <td>
              <div class="name-cell">
                <span class="avatar">
                  {{ strtoupper(substr($visitor->first_name, 0, 1)) }}
                  {{ strtoupper(substr($visitor->last_name, 0, 1)) }}
                </span>
                <div class="visitor-name">
                  {{ $visitor->first_name }} {{ $visitor->last_name }}
                </div>
              </div>
            </td>
            <td>{{ $visitor->contact_number ?? 'N/A' }}</td>
            <td>
              {{ \Carbon\Carbon::parse($visitor->first_visit_date)
                  ->format('M j, Y') }}<br>
              <small class="visit-day">
                {{ \Carbon\Carbon::parse($visitor->first_visit_date)
                    ->format('l') }}
              </small>
            </td>
            <td>
              Day 5<br>
              <a href="#" class="action-link">View Tracker</a>
            </td>
            <td>
              @php
                $rawStatus = $visitor->messageStatus->status ?? 'not texted';
                $status = strtolower($rawStatus);
                $class = match($status) {
                  'responded' => 'status-responded',
                  'cant contact' => 'status-cant-contact',
                  'connected' => 'status-connected',
                  'texted' => 'status-texted',
                  default => 'status-not-texted'
                };
              @endphp
              <span class="status-pill {{ $class }}">
                {{ ucwords($rawStatus) }}
              </span>
            </td>
            <td>
              <div class="volunteer-info">
                @if($visitor->v_fname)
                  {{ $visitor->v_fname }} {{ $visitor->v_lname }}
                @else
                  <span class="visitor-subtext">Unassigned</span>
                @endif
              </div>
              <a href="javascript:void(0)" class="action-link"
                onclick="openAssignModal(
                  {{ $visitor->visitor_id }}, 
                  '{{ addslashes($visitor->first_name . ' ' . $visitor->last_name) }}',
                  {{ $visitor->v_fname ? 'true' : 'null' }}
                )">
                {{ $visitor->v_fname ? 'Change' : 'Assign Volunteer' }}
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="pagination-container">
      {{ $visitors->links() }}
    </div>
  </div>
</div>

{{-- Assignment Modal --}}
<div id="assignModal" class="modal-overlay" style="display:none;">
  <div class="modal-content assignment-modal">
    <div class="modal-header">
      <h3>Manage Assignment</h3>
      <p id="modalVisitorName" class="visitor-subtext"></p>
    </div>
    
    <input type="hidden" id="modalVisitorId">
    
    <div class="search-constrain-wrapper">
      <div class="modal-search-wrapper">
        <input type="text" id="volunteerSearch" 
          placeholder="Type to search volunteers..." 
          class="search-input" onkeyup="searchVolunteers()">
        <div id="searchLoader" class="loader-inner" style="display:none;"></div>
      </div>

      <div class="results-container">
        <ul id="volunteerList" class="volunteer-results"></ul>
      </div>
    </div>

    <div class="modal-actions">
      <button id="btnUnassign" onclick="assignTo(null)" 
        class="btn-unassign" style="display: none;">
        Remove Current Assignment
      </button>
      <button onclick="closeAssignModal()" class="btn-close-modal">
        Cancel
      </button>
    </div>
  </div>
</div>
@endsection