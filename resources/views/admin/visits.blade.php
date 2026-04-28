<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visit Schedules | Admin</title>
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
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px;">✓ {{ session('success') }}</div>
    @endif

    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden;">
      <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
        <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">All Scheduled Visits</h2>
      </div>
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
            @forelse($visits as $visit)
              <tr style="border-bottom: 1px solid #e8dcc8; transition: background 0.2s;" onmouseover="this.style.background='#f5edd8'" onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 20px;">
                  <div style="font-size: 15px; color: #2c1a0e; font-weight: 600;">{{ $visit->user->name }}</div>
                  <div style="font-size: 12px; color: #8a6a40;">{{ $visit->user->email }}</div>
                </td>
                <td style="padding: 14px 20px;">
                  <div style="font-size: 14px; color: #2c1a0e;">{{ $visit->booking->event_type }}</div>
                  <div style="font-size: 11px; color: #c9a84c; font-weight: 700;">Event: {{ $visit->booking->event_date->format('M d, Y') }}</div>
                </td>
                <td style="padding: 14px 20px;">
                  <div style="font-size: 14px; color: #2c1a0e; font-weight: 600;">{{ $visit->visit_date->format('M d, Y') }}</div>
                  <div style="font-size: 11px; color: #8a6a40;">{{ $visit->visit_date->format('h:i A') }}</div>
                </td>
                <td style="padding: 14px 20px;">
                  <span class="visit-status v-{{ $visit->status }}">
                    {{ $visit->status }}
                  </span>
                </td>
                <td style="padding: 14px 20px;">
                  <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @if($visit->status === 'pending')
                      <form method="POST" action="{{ route('admin.visits.confirm', $visit->id) }}">
                        @csrf
                        <button type="submit" style="background: transparent; border: 1px solid #28a745; color: #28a745; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#28a745'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#28a745';">Confirm</button>
                      </form>
                    @endif
                    
                    @if($visit->status !== 'completed')
                      <button type="button" 
                        class="open-visit-modal-btn"
                        style="background: transparent; border: 1px solid #3498db; color: #3498db; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" 
                        data-id="{{ $visit->id }}"
                        data-current="{{ $visit->visit_date->format('Y-m-d\TH:i') }}"
                        data-event-date="{{ $visit->booking->event_date->format('Y-m-d') }}"
                        onmouseover="this.style.background='#3498db'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#3498db';">
                        Reschedule
                      </button>
                      
                      <form method="POST" action="{{ route('admin.visits.complete', $visit->id) }}">
                        @csrf
                        <button type="submit" style="background: transparent; border: 1px solid #8a6a40; color: #8a6a40; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#8a6a40'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#8a6a40';">Complete</button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" style="text-align: center; padding: 60px; color: #8a6a40; font-size: 16px;">No visits scheduled.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
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

  <div id="visit-data" class="hidden" data-base-url="{{ url('admin/visits') }}"></div>

  <script>
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
      document.getElementById('visitForm').action = visitBaseUrl + '/' + id + '/reschedule';
      document.getElementById('visit_date_input').value = current;
      document.getElementById('visit_date_input').max = eventDate + 'T23:59';
      document.getElementById('visitModal').classList.add('open');
    }
  </script>
</body>
</html>
