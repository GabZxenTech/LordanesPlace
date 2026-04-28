<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Profile | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .status-badge { display: inline-block; padding: 0.5rem 1rem; border-radius: 100px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; }
    .status-approved { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
    .status-pending { background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
    .status-rejected { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
    .payment-fully_paid { background: rgba(59,130,246,0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); }
    .payment-partially_paid { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
    .payment-unpaid { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
    .visit-confirmed { color: #a88a4c; }
    .visit-pending { color: #C9A84C; }
    
    .p-container { padding: 3rem 0; font-family: 'Jost', sans-serif; }
    .p-card { background: #fdfbf7; border: 1px solid #a88a4c20; border-radius: 12px; margin-bottom: 3rem; overflow: hidden; box-shadow: 0 4px 20px rgba(168,138,76,0.05); }
    .p-header { padding: 3rem; border-bottom: 1px solid #a88a4c10; }
    .p-title { font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 2.5rem; color: #a88a4c; }
    .p-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem; }
    .p-label { font-size: 10px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem; }
    .p-value { font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0; }
    .p-footer { background: #a88a4c05; padding: 2rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #a88a4c10; }
    
    .b-list { display: flex; flex-direction: column; gap: 2rem; }
    .b-card { background: white; border: 1px solid #a88a4c15; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.02); overflow: hidden; }
    .b-header { padding: 2rem 3rem; border-bottom: 1px solid #a88a4c08; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem; background: #fffcf8; }
    .b-title { font-family: 'Cormorant Garamond', serif; font-size: 1.75rem; font-weight: 700; color: #1a1a1a; margin: 0; }
    .b-subtitle { font-size: 12px; color: #a88a4c; font-weight: 600; margin-top: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .b-content { padding: 3rem; }
    .b-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem; }
    .b-label { font-size: 11px; font-weight: 700; color: rgba(26,26,26,0.6); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 0.75rem; }
    .b-value { font-family: 'Jost', sans-serif; font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0; }
    .b-value-alt { font-family: 'Jost', sans-serif; font-size: 18px; font-weight: 700; color: #a88a4c; margin: 0; }
    .b-action-row { margin-top: 2.5rem; display: flex; justify-content: flex-end; gap: 1rem; }
    .b-notes { margin-top: 2.5rem; padding: 1.5rem 2rem; background: #fffcf8; border-radius: 8px; font-size: 15px; color: #1a1a1a60; font-style: italic; border-left: 4px solid #a88a4c20; }
    
    .btn-receipt { border: 2px solid #B8860B; color: #B8860B; background: transparent; padding: 0.85rem 2rem; border-radius: 2px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; text-decoration: none; transition: 0.3s; display: inline-block; }
    .btn-receipt:hover { background: #B8860B; color: white; }
    .btn-reschedule { background: #a88a4c; color: white; padding: 0.85rem 2rem; border-radius: 2px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; border: none; cursor: pointer; transition: 0.3s; }
  </style>
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<section class="p-container">
  <div class="max-w-[1200px] mx-auto px-4">
    <div class="p-card">
      <div class="p-header">
        <h2 class="p-title">Profile Information</h2>
        <div class="p-grid">
          <div><p class="p-label">Username</p><p class="p-value">{{ $user->name }}</p></div>
          <div><p class="p-label">Email Address</p><p class="p-value" style="word-break: break-all;">{{ $user->email }}</p></div>
          <div><p class="p-label">Account Tier</p><p class="p-value" style="color: #a88a4c; font-weight: 700;">Loyal Client</p></div>
          <div><p class="p-label">Member Since</p><p class="p-value">{{ $user->created_at->format('M d, Y') }}</p></div>
        </div>
      </div>
      <div class="p-footer">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
          <div style="width: 48px; height: 48px; border-radius: 50%; background: #a88a4c15; display: flex; align-items: center; justify-content: center; color: #a88a4c;">✦</div>
          <div><p style="font-size: 15px; font-weight: 700; color: #1a1a1a; margin: 0;">Reservation Summary</p><p style="font-size: 13px; color: #1a1a1a; margin: 0; opacity: 0.7;">You have reservations.</p></div>
        </div>
        <a href="{{ route('booking') }}" style="background: #a88a4c; color: white; padding: 0.85rem 2rem; border-radius: 2px; text-decoration: none; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; transition: 0.3s; box-shadow: 0 4px 10px rgba(168,138,76,0.15);">+ New Reservation</a>
      </div>
    </div>

    <div class="b-list">
      @forelse($bookings as $booking)
        <div class="b-card">
          <div class="b-header">
            <div><h3 class="b-title">#LDP-{{ $booking->booking_number }}</h3><p class="b-subtitle">Reservation Confirmed on {{ $booking->created_at->format('F d, Y') }}</p></div>
            <div style="display: flex; gap: 0.75rem;">
              @php $statusClass = 'status-' . $booking->status; $paymentClass = 'payment-' . $booking->payment_status; @endphp
              <span class="status-badge {{ $statusClass }}">{{ $booking->status }}</span>
              <span class="status-badge {{ $paymentClass }}">{{ str_replace('_', ' ', $booking->payment_status) }}</span>
            </div>
          </div>
          <div class="b-content">
            <div class="b-grid" style="margin-bottom: 4rem;">
              <div><p class="b-label">The Event Package</p><p class="b-value">{{ $booking->event_type }} ({{ $booking->package }})</p></div>
              <div><p class="b-label">Date of Celebration</p><p class="b-value">{{ $booking->event_date->format('F d, Y') }}</p></div>
              <div><p class="b-label">Event Schedule</p><p class="b-value">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</p></div>
            </div>
            <div class="b-grid" style="padding-bottom: 3rem; border-bottom: 1px solid #a88a4c08;">
              <div><p class="b-label">Guest Accommodation</p><p class="b-value">{{ $booking->guest_count }} Guests Expected</p></div>
              <div><p class="b-label">Total Investment</p><p class="b-value-alt">₱{{ number_format($booking->total_amount, 2) }}</p></div>
              <div><p class="b-label">Venue Visit</p>
                @php $visit = $booking->visitSchedules->first(); @endphp
                @if($visit)
                  <p class="b-value">{{ $visit->visit_date->format('M d, Y @ h:i A') }}</p>
                  <p style="font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.25rem;" class="{{ $visit->status === 'confirmed' ? 'visit-confirmed' : 'visit-pending' }}">✦ {{ $visit->status }}</p>
                @else
                  <a href="{{ route('visit-schedule.create', ['booking' => $booking->id]) }}" style="color: #a88a4c; font-weight: 900; font-size: 13px; text-decoration: none; text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 2px solid #a88a4c30; padding-bottom: 2px;">Schedule Walkthrough &rarr;</a>
                @endif
              </div>
            </div>
            <div class="b-action-row">
              <a href="{{ route('booking.receipt', $booking->id) }}" class="btn-receipt">Receipt PDF</a>
              @if($booking->status === 'approved' && $booking->reschedule_status !== 'pending')
                <button type="button" class="btn-reschedule open-reschedule-btn"
                  data-booking-id="{{ $booking->id }}"
                  data-event-date="{{ $booking->event_date->format('F d, Y') }}"
                  data-visit-date="{{ optional($booking->visitSchedules->first())->visit_date ? $booking->visitSchedules->first()->visit_date->format('F d, Y') : 'Not scheduled' }}"
                  data-reschedule-count="{{ $booking->reschedule_count }}">Reschedule</button>
              @endif
            </div>
            @if($booking->notes)<div class="b-notes">"{{ $booking->notes }}"</div>@endif
          </div>
        </div>
      @empty
        <div style="text-align: center; padding: 8rem 2rem; background: #fdfbf7; border: 2px dashed #a88a4c20; border-radius: 12px; color: #a88a4c50;"><p style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; margin-bottom: 2rem;">No reservation records found.</p></div>
      @endforelse
    </div>
  </div>
</section>

{{-- MODALS --}}
<div id="rescheduleModal" class="fixed inset-0 z-[1000] flex items-center justify-center p-4" style="display:none; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);">
    <div style="max-width: 500px; width: 100%; background: #fdfbf7; border: 1px solid #a88a4c30; border-radius: 8px; padding: 45px; position: relative; font-family: 'Jost', sans-serif;">
        <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 700; color: #a88a4c; margin: 0 0 35px 0;">Reschedule Reservation</h3>
        <button type="button" onclick="closeRescheduleModal()" style="position: absolute; top: 20px; right: 20px; border: none; background: transparent; cursor: pointer; font-size: 24px; color: #a88a4c;">&times;</button>
        <form method="POST" action="" id="rescheduleForm">
            @csrf
            <div style="margin-bottom: 24px;"><label style="display: block; font-size: 11px; color: rgba(26,26,26,0.6); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 10px; font-weight: 700;">NEW EVENT DATE</label><input type="date" name="requested_event_date" id="reschedule_event_date" required style="width: 100%; height: 50px; border: 1px solid #d4c4a0; padding: 0 15px; border-radius: 4px; box-sizing: border-box;" /></div>
            <div style="margin-bottom: 24px;"><label style="display: block; font-size: 11px; color: rgba(26,26,26,0.6); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 10px; font-weight: 700;">NEW WALKTHROUGH DATE</label><input type="date" name="requested_visit_date" id="reschedule_visit_date" required style="width: 100%; height: 50px; border: 1px solid #d4c4a0; padding: 0 15px; border-radius: 4px; box-sizing: border-box;" /></div>
            <div style="display: flex; gap: 15px;"><button type="button" onclick="closeRescheduleModal()" style="flex: 1; padding: 15px; border: 1px solid #a88a4c; background: transparent; color: #a88a4c; font-weight: 700; cursor: pointer;">Cancel</button><button type="submit" style="flex: 1; padding: 15px; border: none; background: #a88a4c; color: white; font-weight: 700; cursor: pointer;">Review Change</button></div>
        </form>
    </div>
</div>
<div id="profile-data" class="hidden" data-reschedule-url="{{ url('booking') }}"></div>

@include('partials._footer')

<script>
  const profileData = document.getElementById('profile-data');
  const rescheduleUrlBase = profileData.getAttribute('data-reschedule-url');
  document.querySelectorAll('.open-reschedule-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-booking-id');
      document.getElementById('rescheduleForm').action = rescheduleUrlBase + '/' + id + '/reschedule';
      document.getElementById('rescheduleModal').style.display = 'flex';
    });
  });
  function closeRescheduleModal() { document.getElementById('rescheduleModal').style.display = 'none'; }
</script>
</body>
</html>
