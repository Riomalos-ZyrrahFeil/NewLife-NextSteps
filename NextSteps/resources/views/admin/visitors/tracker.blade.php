@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/tracking.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="tracker-container">
  <div class="tracker-header"><h2>Guest Tracker</h2></div>

  <div class="search-header-box">
    <div class="search-container">
      <form action="{{ route('admin.guest_tracker.index') }}" method="GET">
        <input type="text" name="search" value="{{ request('search') }}"
          placeholder="Search..." class="search-input"
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
          {{-- Dynamic headers mula sa database --}}
          @foreach($stages as $stage)
            <th class="text-center">{{ strtoupper($stage->stage_name) }}</th>
          @endforeach
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
                <div class="visitor-name">{{ $visitor->first_name }} {{ $visitor->last_name }}</div>
              </div>
            </td>
            <td><div class="contact-number">{{ $visitor->contact_number ?? 'N/A' }}</div></td>

            @php
              $vDate = \Carbon\Carbon::parse($visitor->first_visit_date);
              $elapsed = $vDate->diffInDays(now());
            @endphp

            @foreach($stages as $stage)
              @php
                $isDue = $elapsed >= $stage->day_offset; // Trigger base sa offset
                $stRecord = $visitor->stageStatuses
                    ->where('follow_up_stage_id', $stage->follow_up_stage_id)
                    ->first();
                $val = $stRecord->status ?? 'not texted';
                $cls = str_replace(' ', '-', strtolower($val));
              @endphp

              <td class="text-center">
                <div class="status-cell-wrapper">
                  @if($isDue)
                    <div class="status-pill status-{{ $cls }}" onclick="toggleDropdown(this)">
                      {{ ucwords($val) }}
                    </div>
                    <select class="inline-status-select" style="display:none;" 
                            onchange="updStat({{ $visitor->visitor_id }}, this, {{ $stage->follow_up_stage_id }})">
                      @foreach(['not texted','texted','responded','connected','cant contact'] as $o)
                        <option value="{{ $o }}" {{ $val == $o ? 'selected' : '' }}>{{ ucwords($o) }}</option>
                      @endforeach
                    </select>
                  @else
                    <span class="status-pill status-pending">Pending</span>
                  @endif
                </div>
              </td>
            @endforeach

            <td class="text-center">
              <button type="button" class="btn-view-script" onclick="openScriptModal('{{ $visitor->first_name }}')">
                View Script
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="pagination-container">{{ $visitors->links() }}</div>
  </div>
</div>

<script>
function toggleDropdown(p) {
  const w = p.closest('.status-cell-wrapper');
  const s = w.querySelector('.inline-status-select');
  p.style.display = 'none';
  s.style.display = 'block';
  s.focus();
}

function updStat(id, sel, stageId) {
  const stat = sel.value;
  const p = sel.closest('.status-cell-wrapper').querySelector('.status-pill');

  fetch("{{ route('admin.guest_tracker.status') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ 
        visitor_id: id, 
        status: stat, 
        stage_id: stageId
    })
  })
  .then(res => res.json())
  .then(() => {
    p.innerText = stat.charAt(0).toUpperCase() + stat.slice(1);
    p.className = `status-pill status-${stat.replace(' ', '-')}`;
    sel.style.display = 'none';
    p.style.display = 'inline-block';
  });
}
</script>
@endsection