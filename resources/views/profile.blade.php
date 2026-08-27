<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Profile | LorDane's Place</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<section class="py-12 md:py-24 bg-off-white min-h-screen" style="font-family: 'Jost', sans-serif;">
  <div class="max-w-[1200px] mx-auto px-4">

    {{-- PROFILE INFORMATION SECTION - BRANDED --}}
    <div style="background: #FAF8F3; border: 1px solid #B8860B20; border-radius: 12px; margin-bottom: 3rem; overflow: hidden; shadow: 0 4px 20px rgba(184,134,11,0.05);">
      <div style="padding: 3rem; border-bottom: 1px solid #B8860B10;">
        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 2.5rem; color: #B8860B;">Profile Information</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem;">
          <div>
            <p style="font-size: 10px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Username</p>
            <p style="font-size: 18px; font-weight: 600; color: #1A1208;">{{ $user->name }}</p>
          </div>
          <div>
            <p style="font-size: 10px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Email Address</p>
            <p style="font-size: 18px; font-weight: 600; color: #1A1208; word-break: break-all;">{{ $user->email }}</p>
          </div>
          <div>
            <p style="font-size: 10px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Account Tier</p>
            <p style="font-size: 18px; font-weight: 700; color: #B8860B;">Loyal Client</p>
          </div>
          <div>
            <p style="font-size: 10px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Member Since</p>
            <p style="font-size: 18px; font-weight: 600; color: #1A1208;">{{ $user->created_at->format('M d, Y') }}</p>
          </div>
        </div>
      </div>

      <div style="background: #B8860B05; padding: 2rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #B8860B10;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
          <div style="width: 48px; height: 48px; border-radius: 50%; background: #B8860B15; display: flex; align-items: center; justify-content: center; color: #B8860B;">✦</div>
          <div>
            <p style="font-size: 15px; font-weight: 700; color: #1A1208; margin: 0;">Reservation Summary</p>
            <p style="font-size: 13px; color: #1A1208; margin: 0; opacity: 0.7;">You have currently entrusted us with {{ $bookings->count() }} reservations.</p>
          </div>
        </div>
        <a href="{{ route('booking') }}" style="background: #B8860B; color: white; padding: 0.85rem 2rem; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; transition: 0.3s; box-shadow: 0 4px 10px rgba(184,134,11,0.2);" onmouseover="this.style.background='#C9A84C'" onmouseout="this.style.background='#B8860B'">+ New Reservation</a>
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; padding: 0 0.5rem;">
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; font-weight: 700; color: #1A1208; margin: 0;">My Bookings</h2>
      <p style="font-size: 11px; font-weight: 900; color: #B8860B90; text-transform: uppercase; letter-spacing: 0.2em; border-bottom: 2px solid #B8860B40; padding-bottom: 0.5rem;">{{ $bookings->count() }} TOTAL RECORDS</p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 2rem;">
      @forelse($bookings as $booking)
        <div id="booking-{{ $booking->id }}" style="background: white; border: 1px solid #B8860B15; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.02); overflow: hidden; scroll-margin-top: 100px;">

          {{-- Header --}}
          <div style="padding: 2rem 3rem; border-bottom: 1px solid #B8860B08; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem; background: #FAF8F3;">
            <div>
              <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.75rem; font-weight: 700; color: #1A1208; margin: 0;">#LDP-{{ $booking->booking_number }}</h3>
              <p style="font-size: 12px; color: #B8860B; font-weight: 600; margin-top: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Reservation Confirmed on {{ $booking->created_at->format('F d, Y') }}</p>
            </div>

            <div style="display: flex; gap: 0.75rem;">
              @php
                $sColor = \App\Models\Booking::statusColor($booking->status);
                $pColor = $booking->payment_status === 'fully_paid' ? '#3b82f6' : ($booking->payment_status === 'partially_paid' ? '#10b981' : '#ef4444');
              @endphp
              <span style="padding: 0.5rem 1rem; border-radius: 100px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; background: {{ $sColor }}10; color: {{ $sColor }}; border: 1px solid {{ $sColor }}20;">{{ $booking->status }}</span>
              <span style="padding: 0.5rem 1rem; border-radius: 100px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; background: {{ $pColor }}10; color: {{ $pColor }}; border: 1px solid {{ $pColor }}20;">{{ str_replace('_', ' ', $booking->payment_status) }}</span>
            </div>
          </div>

          {{-- Content --}}
          <div style="padding: 3rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem; margin-bottom: 4rem;">
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">The Event Package</p>
                <p style="font-size: 20px; font-weight: 700; color: #1A1208;">{{ $booking->event_type }} ({{ $booking->package }})</p>
              </div>
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Date of Celebration</p>
                <p style="font-size: 20px; font-weight: 700; color: #1A1208;">{{ $booking->event_date->format('F d, Y') }}</p>
              </div>
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Event Schedule</p>
                @if($booking->start_time && $booking->end_time)
                  <p style="font-size: 20px; font-weight: 700; color: #1A1208;">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</p>
                @else
                  <p style="font-size: 15px; font-weight: 600; color: #B8860B90; font-style: italic;">To be finalized during your venue visit</p>
                @endif
              </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem; padding-bottom: 3rem; border-bottom: 1px solid #B8860B08;">
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Guest Accommodation</p>
                <p style="font-size: 20px; font-weight: 700; color: #1A1208;">{{ $booking->guest_count }} Guests Expected</p>
              </div>
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Total Investment</p>
                <p style="font-size: 20px; font-weight: 700; color: #B8860B;">₱{{ number_format($booking->total_amount, 2) }}</p>
              </div>
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #B8860B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Venue Visit</p>
                @php $visit = $booking->visitSchedules->first(); @endphp
                @if($visit)
                  <p style="font-size: 18px; font-weight: 700; color: #1A1208;">{{ $visit->visit_date->format('M d, Y @ h:i A') }}</p>
                  <p style="font-size: 10px; font-weight: 900; text-transform: uppercase; color: {{ $visit->status === 'confirmed' ? '#B8860B' : '#1A120840' }}; letter-spacing: 0.05em; margin-top: 0.25rem;">
                    {{ $visit->status === 'confirmed' ? '✦ Appointment Confirmed' : 'Awaiting confirmation' }}
                  </p>
                @else
                  <a href="{{ route('visit-schedule.create', ['booking' => $booking->id]) }}" style="color: #B8860B; font-weight: 900; font-size: 13px; text-decoration: none; text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 2px solid #B8860B30; padding-bottom: 2px;">Schedule Walkthrough &rarr;</a>
                @endif
              </div>
            </div>

            <div style="margin-top: 2.5rem; display: flex; justify-content: flex-end; align-items: center; gap: 1rem; flex-wrap: wrap;">
              @if($booking->hasConfirmedPayment())
                <a href="{{ route('booking.receipt', $booking->id) }}" style="background: #1A1208; color: white; padding: 0.85rem 2rem; border-radius: 3px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; text-decoration: none; transition: 0.3s;">View Receipt</a>
                <a href="{{ route('booking.receipt', $booking->id) }}" target="_blank" style="background: transparent; color: #1A1208; padding: 0.85rem 2rem; border-radius: 3px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; text-decoration: none; border: 1px solid #1A120830; transition: 0.3s;">Print Receipt</a>
              @else
                <p style="margin: 0; font-size: 12.5px; color: #B8860B; font-style: italic; max-width: 320px; text-align: right;">Receipt not yet available. Payment confirmation is required.</p>
              @endif
              @if($booking->status === 'approved' && $booking->reschedule_status !== 'pending')
                @if($booking->event_date->format('Y-m-d') >= date('Y-m-d'))
                  <button type="button" 
                    style="background: #B8860B; color: white; padding: 0.85rem 2rem; border-radius: 3px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; border: none; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 12px rgba(184,134,11,0.25);"
                    onmouseover="this.style.background='#C9A84C'" onmouseout="this.style.background='#B8860B'"
                    onclick="openRescheduleModal({{ $booking->id }}, '{{ $booking->event_date->format('F d, Y') }}', '{{ optional($booking->visitSchedules->first())->visit_date ? $booking->visitSchedules->first()->visit_date->format('F d, Y') : 'Not scheduled' }}', {{ $booking->reschedule_count }})">
                    Reschedule
                  </button>
                @else
                  <button type="button" disabled title="Event already completed"
                    style="background: #e2e8f0; color: #94a3b8; padding: 0.85rem 2rem; border-radius: 3px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; border: none; cursor: not-allowed; opacity: 0.8;">
                    Event Completed
                  </button>
                @endif
              @endif
              @if($booking->canTransitionTo('cancelled') && $booking->event_date->format('Y-m-d') >= date('Y-m-d'))
                <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" onsubmit="return confirm('Are you sure you want to cancel this booking? This cannot be undone.');">
                  @csrf
                  <button type="submit" style="background: transparent; color: #ef4444; padding: 0.85rem 2rem; border-radius: 3px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; border: 1px solid #ef444440; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#ef44440a'" onmouseout="this.style.background='transparent'">
                    Cancel Booking
                  </button>
                </form>
              @endif
            </div>

            @if($booking->notes)
              <div style="margin-top: 2.5rem; padding: 1.5rem 2rem; background: #FAF8F3; border-radius: 8px; font-size: 15px; color: #1A120860; font-style: italic; border-left: 4px solid #B8860B20;">
                "{{ $booking->notes }}"
              </div>
            @endif
          </div>
        </div>
      @empty
        <div style="text-align: center; padding: 8rem 2rem; background: #FAF8F3; border: 2px dashed #B8860B20; border-radius: 12px; color: #B8860B50;">
          <p style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; margin-bottom: 2rem;">No reservation records found in our history.</p>
          <a href="{{ route('booking') }}" style="background: #B8860B; color: white; padding: 1.25rem 3rem; border-radius: 8px; text-decoration: none; font-weight: 900; font-size: 12px; text-transform: uppercase; letter-spacing: 0.2em; box-shadow: 0 10px 30px rgba(184,134,11,0.2);">Start Booking Now</a>
        </div>
      @endforelse
    </div>
  </div>
</section>

{{-- MODALS & SCRIPTS --}}
{{-- MODALS & SCRIPTS --}}
<div id="rescheduleModal" class="fixed inset-0 z-[1000] flex items-center justify-center p-4" style="display:none; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);">
    <div style="max-width: 500px; width: 100%; max-height: 90vh; overflow-y: auto; background: #FAF8F3; border: 1px solid #B8860B30; border-radius: 8px; padding: 45px; position: relative; box-shadow: 0 30px 60px rgba(0,0,0,0.25); font-family: 'Jost', sans-serif; margin: auto;">
        
        {{-- Header --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 35px;">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 700; color: #B8860B; margin: 0; line-height: 1.2;">Reschedule Reservation</h3>
            <button type="button" onclick="closeRescheduleModal()" style="border: none; background: transparent; cursor: pointer; font-size: 24px; color: #B8860B; opacity: 0.6; transition: 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">&times;</button>
        </div>

        <div style="display: flex; flex-direction: column; gap: 30px;">
            {{-- Current info --}}
            <div style="border: 1px solid #B8860B20; padding: 25px; background: rgba(184,134,11,0.02); border-radius: 4px;">
                <p style="font-size: 10px; color: #B8860B; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 15px 0; font-weight: 700;">CURRENT RESERVATION TIMING</p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 16px; font-weight: 600; color: #1A1208;">Event: <span id="currentEventDate" style="color: #B8860B;">--</span></span>
                    <span style="font-size: 16px; font-weight: 600; color: #1A1208;">Visit: <span id="currentVisitDate" style="color: #B8860B;">--</span></span>
                </div>
            </div>

            {{-- Fee notice --}}
            <div id="feeNotice" style="border-radius: 4px; padding: 18px; font-size: 12px; font-weight: 800; text-align: center; text-transform: uppercase; letter-spacing: 1.5px; border: 1px solid #B8860B30; background: #B8860B10; color: #B8860B;"></div>

            <form method="POST" action="" id="rescheduleForm" onsubmit="return validateRescheduleForm()">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <div>
                        <label style="display: block; font-size: 11px; color: rgba(26,18,8,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 600;">NEW EVENT DATE</label>
                        <input type="date" name="requested_event_date" id="reschedule_event_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            style="width: 100%; height: 54px; border: 1px solid #B8860B30; padding: 0 18px; border-radius: 4px; font-size: 15px; outline: none; background: white; color: #1A1208; transition: 0.3s;" 
                            onfocus="this.style.borderColor='#B8860B'" onblur="this.style.borderColor='#B8860B30'" />
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 11px; color: rgba(26,18,8,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 600;">NEW WALKTHROUGH DATE</label>
                        <input type="date" name="requested_visit_date" id="reschedule_visit_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            style="width: 100%; height: 54px; border: 1px solid #B8860B30; padding: 0 18px; border-radius: 4px; font-size: 15px; outline: none; background: white; color: #1A1208; transition: 0.3s;"
                            onfocus="this.style.borderColor='#B8860B'" onblur="this.style.borderColor='#B8860B30'" />
                        <p style="font-size: 11px; color: rgba(184,134,11,0.6); margin: 10px 0 0 0; font-style: italic; font-weight: 500;">* Note: Walkthrough should be prior to the event date.</p>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; color: rgba(26,18,8,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 600;">RESCHEDULING REASON</label>
                        <textarea name="reschedule_reason" rows="3" placeholder="Explain the reason for this change..."
                            style="width: 100%; border: 1px solid #B8860B30; padding: 15px 18px; border-radius: 4px; font-size: 15px; outline: none; background: white; color: #1A1208; resize: none; transition: 0.3s;"
                            onfocus="this.style.borderColor='#B8860B'" onblur="this.style.borderColor='#B8860B30'"></textarea>
                    </div>
                </div>

                <div id="rescheduleError" style="background: #fffafa; color: #c53030; border: 1px solid #feb2b2; padding: 15px; border-radius: 4px; font-size: 13px; font-weight: 700; margin-top: 25px; display: none; text-align: center;"></div>

                {{-- Footer buttons --}}
                <div style="display: flex; gap: 20px; margin-top: 40px;">
                    <button type="button" onclick="closeRescheduleModal()" 
                        style="flex: 1; padding: 18px; border: 1px solid #B8860B; background: transparent; color: #B8860B; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.2em; cursor: pointer; transition: 0.3s; border-radius: 2px;"
                        onmouseover="this.style.background='#B8860B10'" onmouseout="this.style.background='transparent'">
                        Cancel
                    </button>
                    <button type="submit" 
                        style="flex: 1; padding: 18px; border: none; background: #B8860B; color: white; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.2em; cursor: pointer; transition: 0.3s; border-radius: 3px; box-shadow: 0 4px 15px rgba(184,134,11,0.25);"
                        onmouseover="this.style.background='#C9A84C'" onmouseout="this.style.background='#B8860B'">
                        Review Change
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('partials._footer')

<script>
  function openRescheduleModal(bookingId, eventDate, visitDate, rescheduleCount) {
    document.getElementById('rescheduleForm').action = '/booking/' + bookingId + '/reschedule';
    document.getElementById('currentEventDate').textContent = eventDate;
    document.getElementById('currentVisitDate').textContent = visitDate;
    const eventField = document.getElementById('reschedule_event_date');
    const visitField = document.getElementById('reschedule_visit_date');
    if(eventField) eventField.value = '';
    if(visitField) visitField.value = '';
    document.getElementById('rescheduleError').classList.add('hidden');

    const feeNotice = document.getElementById('feeNotice');
    if (rescheduleCount === 0) {
      feeNotice.innerHTML = '✦ THIS IS YOUR FIRST RESCHEDULE — FREE OF CHARGE! ✦';
      feeNotice.style.backgroundColor = '#B8860B10';
      feeNotice.style.color = '#B8860B';
      feeNotice.style.border = '1px solid #B8860B30';
    } else {
      feeNotice.innerHTML = '⚠️ RESCHEDULE FEE: ₱5,000 (ATTEMPT #' + (rescheduleCount + 1) + ')';
      feeNotice.style.backgroundColor = '#1A120805';
      feeNotice.style.color = '#1A1208';
      feeNotice.style.border = '1px solid #1A120810';
    }

    const modal = document.getElementById('rescheduleModal');
    if(modal) modal.style.display = 'flex';
  }

  function closeRescheduleModal() {
    const modal = document.getElementById('rescheduleModal');
    if(modal) modal.style.display = 'none';
  }

  function validateRescheduleForm() {
    const eventDate = document.getElementById('reschedule_event_date').value;
    const visitDate = document.getElementById('reschedule_visit_date').value;
    const errorEl = document.getElementById('rescheduleError');
    const today = new Date().toISOString().split('T')[0];

    if (!eventDate || !visitDate) {
      errorEl.textContent = 'Please select both dates.';
      errorEl.style.display = 'block';
      return false;
    }

    if (eventDate <= today) {
      errorEl.textContent = 'Event date must be a future date.';
      errorEl.style.display = 'block';
      return false;
    }

    if (visitDate < today) {
      errorEl.textContent = 'The Site Visit date cannot be in the past.';
      errorEl.style.display = 'block';
      return false;
    }

    if (visitDate >= eventDate) {
      errorEl.textContent = 'The Site Visit must be scheduled before your event date.';
      errorEl.style.display = 'block';
      return false;
    }

    errorEl.style.display = 'none';
    return true;
  }

  // Dynamically set max visit date when new event date is picked in reschedule modal
  document.getElementById('reschedule_event_date')?.addEventListener('change', function() {
    const eventVal = this.value;
    const visitInput = document.getElementById('reschedule_visit_date');
    if (eventVal) {
      const d = new Date(eventVal);
      d.setDate(d.getDate() - 1);
      visitInput.max = d.toISOString().split('T')[0];
    } else {
      visitInput.removeAttribute('max');
    }
  });

  // Close modal on backdrop click
  window.addEventListener('click', function(e) {
    const modal = document.getElementById('rescheduleModal');
    if (e.target === modal) closeRescheduleModal();
  });

  // Highlight the booking a notification linked to (e.g. #booking-42)
  if (window.location.hash.startsWith('#booking-')) {
    const target = document.querySelector(window.location.hash);
    if (target) {
      target.style.transition = 'box-shadow 0.4s ease';
      target.style.boxShadow = '0 0 0 3px #B8860B';
      setTimeout(() => { target.style.boxShadow = ''; }, 2500);
    }
  }
</script>
</body>
</html>
