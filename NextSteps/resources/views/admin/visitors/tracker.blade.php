@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/tracking.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="tracker-container">
  <div class="tracker-header"><h2>Guest Tracker</h2></div>

  {{-- Search Box --}}
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
          <th>CONTACT</th>
          @foreach($stages as $stage)
            <th class="text-center">{{ strtoupper($stage->stage_name) }}</th>
          @endforeach
          <th class="text-center">ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        @foreach($visitors as $visitor)
          @php
            $vDate = \Carbon\Carbon::parse($visitor->first_visit_date);
            $elapsed = $vDate->diffInDays(now());
          @endphp
          <tr>
            <td>
              <div class="visitor-info-cell">
                <div class="avatar-circle">
                  {{ strtoupper(substr($visitor->first_name, 0, 1)) }}
                  {{ strtoupper(substr($visitor->last_name, 0, 1)) }}
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
            @foreach($stages as $stage)
              @php
                $isDue = $elapsed >= $stage->day_offset;
                $stRecord = $visitor->stageStatuses
                  ->where('follow_up_stage_id', $stage->follow_up_stage_id)
                  ->first();
                $val = $stRecord->status ?? 'not texted';
                $cls = str_replace(' ', '-', strtolower($val));
              @endphp
              <td class="text-center">
                <div class="status-cell-wrapper">
                  @if($isDue)
                    <div class="status-pill status-{{ $cls }}" 
                         onclick="toggleDropdown(this)">
                      {{ ucwords($val) }}
                    </div>
                    <select class="inline-status-select" style="display:none;" 
                            onchange="updStat({{ $visitor->visitor_id }}, 
                                      this, {{ $stage->follow_up_stage_id }})">
                      @foreach(['not texted','texted','responded',
                                'connected','cant contact'] as $o)
                        <option value="{{ $o }}" 
                          {{ $val == $o ? 'selected' : '' }}>
                          {{ ucwords($o) }}
                        </option>
                      @endforeach
                    </select>
                  @else
                    <span class="status-pill status-pending">Pending</span>
                  @endif
                </div>
              </td>
            @endforeach
            <td class="text-center">
              <button type="button" class="btn-view-script" 
                      onclick="openScriptModal('{{ $visitor->first_name }}', 
                      {{ $elapsed }}, '{{ Auth::user()->first_name }}')">
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

{{-- Message Script Modal --}}
<div id="scriptModal" class="modal-overlay" style="display:none;">
  <div class="modal-content assignment-modal" style="width: 550px;">
    <div class="modal-header">
      <h3>Message Script</h3>
      <p id="activeStageLabel" 
         style="font-size: 0.8rem; color: #6b7280; margin-top: 4px;"></p>
    </div>
    <div class="modal-body" style="margin-top: 16px;">
      <textarea id="scriptTextArea" class="search-input" 
                style="width: 100%; height: 200px; font-size: 0.9rem; 
                line-height: 1.5; background: #fff;" readonly></textarea>
    </div>
    <div class="modal-actions" 
         style="flex-direction: row; justify-content: flex-end; gap: 8px;">
      <button onclick="copyScript()" class="btn-view-script" 
              style="background-color: #4f46e5;">Copy Message</button>
      <button onclick="closeScriptModal()" class="btn-close-modal" 
              style="background:none; border:none; color:#6b7280; 
              cursor:pointer;">Close</button>
    </div>
  </div>
</div>

<script>
  // --- TEMPLATES ---
  const templates = {
    1: (n, s) => `Hi ${n}! This is ${s} from New Life Main. It was ` +
      `great having you with us yesterday! We’d love to know—what stood ` +
      `out to you the most from the service? 😊 If you’d like to get ` +
      `connected to a Life Group or enroll in our free LIFE Classes, ` +
      `just let me know! Also, if there’s anything we can pray for, ` +
      `feel free to reply. Hope to see you again soon!`,
    5: (n, s) => `Hi ${n}! We’d love to see you again this Sunday, ` +
      `[Insert Date]. Let us know if you need any information —parking, ` +
      `kids’ church, or anything else! Our cafe, 5&2 is open as early ` +
      `as 7am. :) Praying for you and excited to see you again! - ${s}`,
    10: (n, s) => `Hi! This is ${s} from New Life. We were thinking of ` +
      `you! It's been 2 weeks since you first visited us. Just wanted to ` +
      `check in—have you been able to come back to church since then? 😄 ` +
      `No worries if not, we just wanted to say hi and see how you're ` +
      `doing. If you're up for joining a Life Group or checking out Link ` +
      `Class, we can help you get started. Want us to send you some info? ` +
      `Also, if you have any prayer requests, please feel free to send ` +
      `it my way! See you soon!`,
    30: (n, s) => `Hi! This is ${s} from New Life. It’s been a month since ` +
      `your first visit—how has your journey been so far? We’d love to ` +
      `help you get more connected, whether through a Life Group, ` +
      `serving, or joining a Life Class. If you are already connected, ` +
      `can you let us know what ministry you’ve joined, class you’ve ` +
      `attended or your Life Group leader’s name? We celebrate you! :)`
  };

  // --- MODAL FUNCTIONS ---
  function openScriptModal(guestName, elapsed, senderName) {
    let offset = 1;
    let days = Math.floor(elapsed); 

    if (days >= 30) offset = 30;
    else if (days >= 10) offset = 10;
    else if (days >= 5) offset = 5;

    document.getElementById('activeStageLabel').innerText = 
      `Recommended Template: Day ${offset} Offset`;
    document.getElementById('scriptTextArea').value = 
      templates[offset](guestName, senderName);
    document.getElementById('scriptModal').style.display = 'flex';
  }

  function closeScriptModal() {
    document.getElementById('scriptModal').style.display = 'none';
  }

  function copyScript() {
    const textArea = document.getElementById('scriptTextArea');
    textArea.select();
    document.execCommand('copy');
    alert('Message copied to clipboard!');
  }

  // --- TRACKING FUNCTIONS ---
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
      body: JSON.stringify({ visitor_id: id, status: stat, stage_id: stageId })
    }).then(res => res.json()).then(() => {
      p.innerText = stat.charAt(0).toUpperCase() + stat.slice(1);
      p.className = `status-pill status-${stat.replace(' ', '-')}`;
      sel.style.display = 'none';
      p.style.display = 'inline-block';
    });
  }
</script>
@endsection