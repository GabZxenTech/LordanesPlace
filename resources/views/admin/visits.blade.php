<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visit Schedules | Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.open { display: flex; }
    .visit-status { display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; text-transform: capitalize; }
    .v-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .v-confirmed { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .v-rescheduled { background: #e1f5fe; color: #0277bd; border: 1px solid #b3e5fc; }
    .v-completed { background: #f5f0e8; color: #8a6a40; border: 1px solid #d4c4a0; }
    .v-missed { background: #fde8e8; color: #e74c3c; border: 1px solid #f5c6cb; }

    /* Remove-visit confirmation (same look as the customer cancel dialog) */
    .rm-modal { position: fixed; inset: 0; z-index: 1100; background: rgba(26,18,8,.55); backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center; padding: 16px; }
    .rm-modal[hidden] { display: none; }
    .rm-box { position: relative; background: #fffcf5; border: 1px solid #e8dcc4; border-radius: 16px; max-width: 460px; width: 100%; padding: 30px 26px 24px; text-align: center; box-shadow: 0 30px 60px rgba(26,18,8,.28); font-family: 'Jost', sans-serif; }
    .rm-close { position: absolute; top: 14px; right: 14px; width: 34px; height: 34px; border-radius: 50%; border: 0; background: transparent; color: #8f7c62; display: grid; place-items: center; cursor: pointer; }
    .rm-close:hover { background: #f2ebdc; color: #1a1208; }
    .rm-icon { width: 54px; height: 54px; border-radius: 50%; background: #f5ead2; color: #b8860b; display: grid; place-items: center; margin: 0 auto 14px; }
    .rm-box h3 { font-family: 'Cormorant Garamond', serif; font-size: 30px; font-weight: 700; color: #1a1208; margin: 0 0 8px; }
    .rm-box > p { font-size: 14px; color: #5c4a33; line-height: 1.55; margin: 0 0 18px; }
    .rm-card { background: #f7f1e4; border: 1px solid #e8dcc4; border-radius: 12px; padding: 12px 14px; display: grid; gap: 3px; text-align: left; margin-bottom: 20px; }
    .rm-card strong { font-size: 15px; color: #1a1208; }
    .rm-card span { font-size: 13px; color: #8f7c62; }
    .rm-acts { display: flex; gap: 12px; margin: 0; }
    .rm-btn { flex: 1; font-family: 'Jost', sans-serif; font-size: 14px; font-weight: 600; padding: 12px; border-radius: 8px; cursor: pointer; background: #fffcf5; border: 1px solid #b8860b; color: #b8860b; }
    .rm-btn:hover { background: #fbf0d9; }
    .rm-btn-danger { background: #2c1a0e; border-color: #2c1a0e; color: #fff; }
    .rm-btn-danger:hover { background: #4a2f1a; }
    .rm-btn:focus-visible, .rm-close:focus-visible { outline: 2px solid #1a1208; outline-offset: 2px; }
  </style>
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">
    <div style="margin-bottom: 32px;">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Visit Schedules</h1>
      <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Manage Venue Walkthroughs</p>
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px; display: flex; align-items: center; gap: 8px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> {{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div style="background: #f8d7da; border: 1px solid #dc3545; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
        @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
      </div>
    @endif

    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden;">
      <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
        <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">All Scheduled Visits</h2>
      </div>

      {{-- Tabs, sort and search (handled by the server, 20 rows per page) --}}
      <form method="GET" action="{{ route('admin.visits.index') }}" class="lf-bar">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="lf-tabs" role="tablist" aria-label="Filter visits">
          @foreach($tabs as $tabKey => $tabLabel)
            <a href="{{ route('admin.visits.index', array_filter(['tab' => $tabKey, 'sort' => $sort !== 'asc' ? $sort : null, 'q' => $search ?: null])) }}"
               class="lf-tab" role="tab" aria-selected="{{ $tab === $tabKey ? 'true' : 'false' }}" style="text-decoration: none;">{{ $tabLabel }}<span class="lf-n">{{ $counts[$tabKey] }}</span></a>
          @endforeach
        </div>
        <div class="lf-controls">
          <label class="lf-control">Sort
            <select name="sort" onchange="this.form.submit()">
              <option value="asc" @selected($sort === 'asc')>Visit date (soonest)</option>
              <option value="desc" @selected($sort === 'desc')>Visit date (latest)</option>
            </select>
          </label>
          <input type="search" name="q" value="{{ $search }}" class="lf-search" placeholder="Search client, email, booking # or package (press Enter)" aria-label="Search visits">
        </div>
      </form>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="border-bottom: 1px solid #d4c4a0;">
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">CLIENT</th>
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">BOOKING</th>
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">VISIT DATE</th>
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">STATUS</th>
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">ACTION</th>
            </tr>
          </thead>
          <tbody>
            @forelse($visitRows as $rowVisits)
              @php
                $visit = $rowVisits->first();
                $rowBookings = $rowVisits->pluck('booking')->sortBy('event_date')->values();
              @endphp
              <tr style="border-bottom: 1px solid #e8dcc8; transition: background 0.2s;" onmouseover="this.style.background='#f5edd8'" onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 20px;">
                  <div style="font-size: 15px; color: #2c1a0e; font-weight: 600;">{{ $visit->user->name }}</div>
                  <div style="font-size: 12px; color: #8a6a40;">{{ $visit->user->email }}</div>
                </td>
                <td style="padding: 14px 20px;">
                  @if($rowBookings->count() === 1)
                    <div style="font-size: 14px; color: #2c1a0e;">{{ $visit->booking->event_type }} <span style="color: #8a6a40;">· {{ $visit->booking->package }}{{ $visit->booking->room_number ? ' ' . $visit->booking->room_number : '' }}</span></div>
                    <div style="font-size: 11px; color: #8a6a40; font-family: monospace;">{{ $visit->booking->booking_number }}</div>
                    <div style="font-size: 11px; color: #c9a84c; font-weight: 700;">Event: {{ $visit->booking->event_date->format('M d, Y') }}</div>
                  @else
                    @php
                      $first = $rowBookings->first()->event_date; $last = $rowBookings->last()->event_date;
                      $packagesLine = $rowBookings->map(fn ($b) => $b->package . ($b->room_number ? ' ' . $b->room_number : ''))->unique()->implode(', ');
                    @endphp
                    <div style="font-size: 14px; color: #2c1a0e;">{{ $rowBookings->pluck('event_type')->unique()->implode(' + ') }} <span style="color: #8a6a40;">· {{ $packagesLine }}</span></div>
                    <div style="font-size: 11px; color: #8a6a40; font-family: monospace;">{{ $rowBookings->count() }} bookings · {{ $rowBookings->first()->booking_number }} – {{ $rowBookings->last()->booking_number }}</div>
                    <div style="font-size: 11px; color: #c9a84c; font-weight: 700;">Event: {{ $first->isSameDay($last) ? $first->format('M d, Y') : $first->format('M d') . ' – ' . $last->format('M d, Y') }}</div>
                  @endif
                </td>
                <td style="padding: 14px 20px;">
                  <div style="font-size: 14px; color: #2c1a0e; font-weight: 600;">{{ $visit->visit_date->format('M d, Y') }}</div>
                  <div style="font-size: 11px; color: #8a6a40;">{{ $visit->visit_date->format('h:i A') }}</div>
                </td>
                <td style="padding: 14px 20px;">
                  <span class="visit-status v-{{ $visit->status }}">
                    {{ $visit->status }}
                  </span>
                  @if($visit->status === 'missed' && $visit->booking && !$visit->booking->hasFinalStatus())
                    {{-- The booking is cancelled if still missed one day after the visit --}}
                    <div style="font-size: 10px; color: #c0392b; margin-top: 4px; max-width: 150px; line-height: 1.3;">Booking auto-cancels {{ $visit->visit_date->copy()->addDay()->format('M d, h:i A') }} unless completed or rescheduled</div>
                  @endif
                </td>
                <td style="padding: 14px 20px;">
                  <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @if($visit->canConfirm())
                      <form method="POST" action="{{ route('admin.visits.confirm', $visit->id) }}">
                        @csrf
                        <button type="submit" style="background: transparent; border: 1px solid #28a745; color: #28a745; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#28a745'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#28a745';">Confirm</button>
                      </form>
                    @endif
                    
                    @if($visit->canReschedule())
                      <button type="button"
                        class="open-visit-modal-btn"
                        style="background: transparent; border: 1px solid #3498db; color: #3498db; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" 
                        data-id="{{ $visit->id }}"
                        data-current="{{ $visit->visit_date->format('Y-m-d\TH:i') }}"
                        data-event-date="{{ $visit->booking->event_date->format('Y-m-d') }}"
                        onmouseover="this.style.background='#3498db'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#3498db';">
                        Reschedule
                      </button>
                    @endif

                    @if($visit->canComplete())
                      <form method="POST" action="{{ route('admin.visits.complete', $visit->id) }}">
                        @csrf
                        <button type="submit" style="background: transparent; border: 1px solid #8a6a40; color: #8a6a40; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#8a6a40'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#8a6a40';">Complete</button>
                      </form>
                    @endif

                    @if($visit->canRemove())
                      <button type="button" class="remove-visit-btn"
                        data-action="{{ route('admin.visits.destroy', $visit->id) }}"
                        data-client="{{ $visit->user->name }}"
                        data-booking="{{ $rowBookings->count() > 1 ? $rowBookings->count() . ' bookings · ' . $rowBookings->first()->booking_number . ' – ' . $rowBookings->last()->booking_number : $visit->booking->booking_number . ' · ' . $visit->booking->package }}"
                        data-when="{{ $visit->visit_date->format('M d, Y · h:i A') }}"
                        style="background: transparent; border: 1px solid #e0a39b; color: #c0392b; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.background='#c0392b'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#c0392b';">Remove</button>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" style="text-align: center; padding: 60px; color: #8a6a40; font-size: 16px;">{{ $search !== '' ? 'No visits match your search.' : 'No visits in this tab.' }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @include('partials._pagination', ['paginator' => $page])
    </div>
  </main>

  {{-- VISIT RESCHEDULE MODAL --}}
  <div id="visitModal" class="modal-overlay">
    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 40px; width: 100%; max-width: 400px;">
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 24px; font-weight: 700; color: #2c1a0e; margin: 0 0 24px; text-transform: uppercase; letter-spacing: 1px;">Reschedule Visit</h2>
      <form method="POST" action="" id="visitForm">
        @csrf
        <div style="margin-bottom: 24px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">New Date & Time</label>
          <input type="datetime-local" name="visit_date" id="visit_date_input" required 
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
        </div>
        <div style="display: flex; gap: 12px;">
          <button type="submit" style="flex: 1; background: #c9a84c; color: #2c1a0e; border: none; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; transition: opacity 0.3s; font-family: 'Jost', sans-serif;">Save Date</button>
          <button type="button" style="flex: 1; border: 1px solid #d4c4a0; background: transparent; color: #8a6a40; padding: 14px; border-radius: 6px; font-size: 14px; cursor: pointer;" onclick="document.getElementById('visitModal').classList.remove('open')">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  {{-- REMOVE VISIT CONFIRMATION --}}
  <div id="removeVisitModal" class="rm-modal" role="dialog" aria-modal="true" aria-labelledby="removeVisitTitle" hidden>
    <div class="rm-box">
      <button type="button" class="rm-close" id="removeVisitClose" aria-label="Close">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
      <div class="rm-icon" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
      </div>
      <h3 id="removeVisitTitle">Remove this visit?</h3>
      <p>It will be hidden from this list. The booking, its payments and the visit record are kept in the database.</p>
      <div class="rm-card">
        <strong id="removeVisitClient"></strong>
        <span id="removeVisitBooking"></span>
        <span id="removeVisitWhen"></span>
      </div>
      <form method="POST" action="" id="removeVisitForm" class="rm-acts">
        @csrf
        @method('DELETE')
        <button type="button" class="rm-btn" id="removeVisitKeep">Keep</button>
        <button type="submit" class="rm-btn rm-btn-danger" id="removeVisitConfirm">Yes, Remove</button>
      </form>
    </div>
  </div>

  <div id="visit-data" class="hidden" data-base-url="{{ url('admin/visits') }}"></div>

  <script>
    // Remove: confirm in a styled dialog, then submit the DELETE form.
    (function () {
      const modal = document.getElementById('removeVisitModal');
      const form = document.getElementById('removeVisitForm');
      const confirmBtn = document.getElementById('removeVisitConfirm');
      const close = function () { modal.hidden = true; };

      document.querySelectorAll('.remove-visit-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          form.action = btn.dataset.action;
          document.getElementById('removeVisitClient').textContent = btn.dataset.client;
          document.getElementById('removeVisitBooking').textContent = btn.dataset.booking;
          document.getElementById('removeVisitWhen').textContent = 'Visit: ' + btn.dataset.when;
          confirmBtn.disabled = false;
          confirmBtn.textContent = 'Yes, Remove';
          modal.hidden = false;
          document.getElementById('removeVisitKeep').focus();
        });
      });
      form.addEventListener('submit', function () { confirmBtn.disabled = true; confirmBtn.textContent = 'Removing...'; });
      document.getElementById('removeVisitKeep').addEventListener('click', close);
      document.getElementById('removeVisitClose').addEventListener('click', close);
      modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) close(); });
    })();

    const visitData = document.getElementById('visit-data');
    const visitBaseUrl = visitData.getAttribute('data-base-url');

    document.querySelectorAll('.open-visit-modal-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const current = this.getAttribute('data-current');
        const eventDate = this.getAttribute('data-event-date');
        openVisitModal(id, current, eventDate);
      });
    });

    function openVisitModal(id, current, eventDate) {
      const form = document.getElementById('visitForm');
      form.action = visitBaseUrl + '/' + id + '/reschedule';
      form.setAttribute('data-event-date', eventDate);
      document.getElementById('visit_date_input').value = current;

      if (eventDate) {
        const d = new Date(eventDate);
        d.setDate(d.getDate() - 1);
        const maxDateStr = d.toISOString().split('T')[0];
        document.getElementById('visit_date_input').max = maxDateStr + 'T23:59';
      }

      document.getElementById('visitModal').classList.add('open');
    }

    document.getElementById('visitForm')?.addEventListener('submit', function(e) {
      const inputVal = document.getElementById('visit_date_input').value;
      if (!inputVal) return;
      const selectedDate = inputVal.split('T')[0];
      const eventDate = this.getAttribute('data-event-date');
      const today = new Date().toISOString().split('T')[0];

      if (selectedDate < today) {
        e.preventDefault();
        uiFieldError('#visit_date_input', 'The Site Visit date cannot be in the past.');
        return;
      }

      if (eventDate && selectedDate >= eventDate) {
        e.preventDefault();
        uiFieldError('#visit_date_input', 'The Site Visit must be scheduled before the event date.');
        return;
      }
    });
  </script>
@include('partials._list-filter')
</body>
</html>
