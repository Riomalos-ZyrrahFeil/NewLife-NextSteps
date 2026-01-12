@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/tracking.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="tracker-container">
  <div class="tracker-header">
    <h2>Guest Tracker</h2>
  </div>

  <div class="search-header-box">
    <div class="search-container">
      <form action="{{ route('admin.guest_tracker.index') }}" method="GET">
        <input type="text" name="search" value="{{ request('search') }}"
          placeholder="Search by name, phone..." class="search-input"
          onchange="this.form.submit()">
      </form>
    </div>
  </div>

  <div class="user-card">
    <table class="custom-table">
      <thead>
        <tr>
          <th>NAME</th>
          <th>CONTACT NUMBER</th>
          <th class="text-center">DAY 1</th>
          <th class="text-center">DAY 5</th>
          <th class="text-center">DAY 10</th>
          <th class="text-center">DAY 30</th>
          <th class="text-center">ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        @foreach($visitors as $visitor)
          <tr>
            <td>
              <div class="visitor-info-cell">
                <div class="avatar-circle">
                  {{ strtoupper(substr($visitor->first_name, 0, 1)) }}{{ strtoupper(substr($visitor->last_name, 0, 1)) }}
                </div>
                <div class="visitor-name">
                  {{ $visitor->first_name }} {{ $visitor->last_name }}
                </div>
              </div>
            </td>
            <td>
              <div class="contact-number">
                {{ $visitor->contact_number ?? 'N/A' }}
              </div>
            </td>

            @for ($i = 0; $i < 4; $i++)
              <td class="text-center">
                @php
                  $rawStatus = $visitor->messageStatus->status ?? 'Not Texted';
                  $statusClass = str_replace(' ', '-', strtolower($rawStatus));
                @endphp
                <span class="status-pill status-{{ $statusClass }}">
                  {{ ucwords($rawStatus) }}
                </span>
              </td>
            @endfor

            <td class="text-center">
              <button type="button" class="btn-view-script" 
                onclick="openScriptModal('{{ $visitor->first_name }}')">
                View Script
              </button>
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

@endsection