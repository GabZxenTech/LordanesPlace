<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Profile | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<section class="py-12 md:py-24 bg-off-white min-h-screen" style="font-family: 'Jost', sans-serif;">
  <div class="max-w-[1200px] mx-auto px-4">
    
    {{-- PROFILE INFORMATION SECTION - BRANDED --}}
    <div style="background: #fdfbf7; border: 1px solid #a88a4c20; border-radius: 12px; margin-bottom: 3rem; overflow: hidden; shadow: 0 4px 20px rgba(168,138,76,0.05);">
      <div style="padding: 3rem; border-bottom: 1px solid #a88a4c10;">
        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 2.5rem; color: #a88a4c;">Profile Information</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem;">
          <div>
            <p style="font-size: 10px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Username</p>
            <p style="font-size: 18px; font-weight: 600; color: #1a1a1a;">{{ $user->name }}</p>
          </div>
          <div>
            <p style="font-size: 10px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Email Address</p>
            <p style="font-size: 18px; font-weight: 600; color: #1a1a1a; word-break: break-all;">{{ $user->email }}</p>
          </div>
          <div>
            <p style="font-size: 10px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Account Tier</p>
            <p style="font-size: 18px; font-weight: 700; color: #a88a4c;">Loyal Client</p>
          </div>
          <div>
            <p style="font-size: 10px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Member Since</p>
            <p style="font-size: 18px; font-weight: 600; color: #1a1a1a;">{{ $user->created_at->format('M d, Y') }}</p>
          </div>
        </div>
      </div>

      <div style="background: #a88a4c05; padding: 2rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #a88a4c10;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
          <div style="width: 48px; height: 48px; border-radius: 50%; background: #a88a4c15; display: flex; align-items: center; justify-content: center; color: #a88a4c;">✦</div>
          <div>
            <p style="font-size: 15px; font-weight: 700; color: #1a1a1a; margin: 0;">Reservation Summary</p>
            <p style="font-size: 13px; color: #1a1a1a; margin: 0; opacity: 0.7;">You have currently entrusted us with {{ $bookings->count() }} reservations.</p>
          </div>
        </div>
        <a href="{{ route('booking') }}" style="background: #a88a4c; color: white; padding: 0.85rem 2rem; border-radius: 2px; text-decoration: none; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; transition: 0.3s; box-shadow: 0 4px 10px rgba(168,138,76,0.15);">+ New Reservation</a>
      </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; padding: 0 0.5rem;">
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; font-weight: 700; color: #1a1a1a; margin: 0;">My Bookings</h2>
      <p style="font-size: 11px; font-weight: 900; color: #a88a4c90; text-transform: uppercase; letter-spacing: 0.2em; border-bottom: 2px solid #a88a4c40; padding-bottom: 0.5rem;">{{ $bookings->count() }} TOTAL RECORDS</p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 2rem;">
      @forelse($bookings as $booking)
        <div style="background: white; border: 1px solid #a88a4c15; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.02); overflow: hidden;">
          
          {{-- Header --}}
          <div style="padding: 2rem 3rem; border-bottom: 1px solid #a88a4c08; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem; background: #fffcf8;">
            <div>
              <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.75rem; font-weight: 700; color: #1a1a1a; margin: 0;">#LDP-{{ $booking->booking_number }}</h3>
              <p style="font-size: 12px; color: #a88a4c; font-weight: 600; margin-top: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Reservation Confirmed on {{ $booking->created_at->format('F d, Y') }}</p>
            </div>
            
            <div style="display: flex; gap: 0.75rem;">
              @php
                $sColor = $booking->status === 'approved' ? '#10b981' : ($booking->status === 'pending' ? '#f59e0b' : '#ef4444');
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
                <p style="font-size: 11px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">The Event Package</p>
                <p style="font-size: 20px; font-weight: 700; color: #1a1a1a;">{{ $booking->event_type }} ({{ $booking->package }})</p>
              </div>
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Date of Celebration</p>
                <p style="font-size: 20px; font-weight: 700; color: #1a1a1a;">{{ $booking->event_date->format('F d, Y') }}</p>
              </div>
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Event Schedule</p>
                <p style="font-size: 20px; font-weight: 700; color: #1a1a1a;">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</p>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 3rem; padding-bottom: 3rem; border-bottom: 1px solid #a88a4c08;">
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Guest Accommodation</p>
                <p style="font-size: 20px; font-weight: 700; color: #1a1a1a;">{{ $booking->guest_count }} Guests Expected</p>
              </div>
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Total Investment</p>
                <p style="font-size: 20px; font-weight: 700; color: #a88a4c;">₱{{ number_format($booking->total_amount, 2) }}</p>
              </div>
              <div>
                <p style="font-size: 11px; font-weight: 900; color: #a88a4c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.75rem;">Venue Visit</p>
                @php $visit = $booking->visitSchedules->first(); @endphp
                @if($visit)
                  <p style="font-size: 18px; font-weight: 700; color: #1a1a1a;">{{ $visit->visit_date->format('M d, Y @ h:i A') }}</p>
                  <p style="font-size: 10px; font-weight: 900; text-transform: uppercase; color: {{ $visit->status === 'confirmed' ? '#a88a4c' : '#1a1a1a40' }}; letter-spacing: 0.05em; margin-top: 0.25rem;">
                    {{ $visit->status === 'confirmed' ? '✦ Appointment Confirmed' : 'Awaiting confirmation' }}
                  </p>
                @else
                  <a href="{{ route('visit-schedule.create', ['booking' => $booking->id]) }}" style="color: #a88a4c; font-weight: 900; font-size: 13px; text-decoration: none; text-transform: uppercase; letter-spacing: 0.1em; border-bottom: 2px solid #a88a4c30; padding-bottom: 2px;">Schedule Walkthrough &rarr;</a>
                @endif
              </div>
            </div>

            <div style="margin-top: 2.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
              <a href="{{ route('booking.receipt', $booking->id) }}" style="background: #1a1a1a; color: white; padding: 0.85rem 2rem; border-radius: 2px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; text-decoration: none; transition: 0.3s;">Receipt PDF</a>
              @if($booking->status === 'approved' && $booking->reschedule_status !== 'pending')
                <button type="button" 
                  style="background: #a88a4c; color: white; padding: 0.85rem 2rem; border-radius: 2px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; border: none; cursor: pointer; transition: 0.3s;"
                  onclick="openRescheduleModal({{ $booking->id }}, '{{ $booking->event_date->format('F d, Y') }}', '{{ optional($booking->visitSchedules->first())->visit_date ? $booking->visitSchedules->first()->visit_date->format('F d, Y') : 'Not scheduled' }}', {{ $booking->reschedule_count }})">
                  Reschedule
                </button>
              @endif
            </div>

            @if($booking->notes)
              <div style="margin-top: 2.5rem; padding: 1.5rem 2rem; background: #fffcf8; border-radius: 8px; font-size: 15px; color: #1a1a1a60; font-style: italic; border-left: 4px solid #a88a4c20;">
                "{{ $booking->notes }}"
              </div>
            @endif
          </div>
        </div>
      @empty
        <div style="text-align: center; padding: 8rem 2rem; background: #fdfbf7; border: 2px dashed #a88a4c20; border-radius: 12px; color: #a88a4c50;">
          <p style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; margin-bottom: 2rem;">No reservation records found in our history.</p>
          <a href="{{ route('booking') }}" style="background: #a88a4c; color: white; padding: 1.25rem 3rem; border-radius: 8px; text-decoration: none; font-weight: 900; font-size: 12px; text-transform: uppercase; letter-spacing: 0.2em; box-shadow: 0 10px 30px rgba(168,138,76,0.2);">Start Booking Now</a>
        </div>
      @endforelse
    </div>
  </div>
</section>

{{-- MODALS & SCRIPTS --}}
{{-- MODALS & SCRIPTS --}}
<div id="rescheduleModal" class="fixed inset-0 z-[1000] flex items-center justify-center p-4" style="display:none; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);">
    <div style="max-width: 500px; width: 100%; max-height: 90vh; overflow-y: auto; background: #fdfbf7; border: 1px solid #a88a4c30; border-radius: 8px; padding: 45px; position: relative; box-shadow: 0 30px 60px rgba(0,0,0,0.25); font-family: 'Jost', sans-serif; margin: auto;">
        
        {{-- Header --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 35px;">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 700; color: #a88a4c; margin: 0; line-height: 1.2;">Reschedule Reservation</h3>
            <button type="button" onclick="closeRescheduleModal()" style="border: none; background: transparent; cursor: pointer; font-size: 24px; color: #a88a4c; opacity: 0.6; transition: 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">&times;</button>
        </div>

        <div style="display: flex; flex-direction: column; gap: 30px;">
            {{-- Current info --}}
            <div style="border: 1px solid #a88a4c20; padding: 25px; background: rgba(168,138,76,0.02); border-radius: 4px;">
                <p style="font-size: 10px; color: #a88a4c; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 15px 0; font-weight: 700;">CURRENT RESERVATION TIMING</p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 16px; font-weight: 600; color: #1a1a1a;">Event: <span id="currentEventDate" style="color: #a88a4c;">--</span></span>
                    <span style="font-size: 16px; font-weight: 600; color: #1a1a1a;">Visit: <span id="currentVisitDate" style="color: #a88a4c;">--</span></span>
                </div>
            </div>

            {{-- Fee notice --}}
            <div id="feeNotice" style="border-radius: 4px; padding: 18px; font-size: 12px; font-weight: 800; text-align: center; text-transform: uppercase; letter-spacing: 1.5px; border: 1px solid #a88a4c30; background: #a88a4c10; color: #a88a4c;"></div>

            <form method="POST" action="" id="rescheduleForm" onsubmit="return validateRescheduleForm()">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <div>
                        <label style="display: block; font-size: 11px; color: rgba(26,26,26,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 600;">NEW EVENT DATE</label>
                        <input type="date" name="requested_event_date" id="reschedule_event_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            style="width: 100%; height: 54px; border: 1px solid #a88a4c30; padding: 0 18px; border-radius: 4px; font-size: 15px; outline: none; background: white; color: #1a1a1a; transition: 0.3s;" 
                            onfocus="this.style.borderColor='#a88a4c'" onblur="this.style.borderColor='#a88a4c30'" />
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 11px; color: rgba(26,26,26,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 600;">NEW WALKTHROUGH DATE</label>
                        <input type="date" name="requested_visit_date" id="reschedule_visit_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            style="width: 100%; height: 54px; border: 1px solid #a88a4c30; padding: 0 18px; border-radius: 4px; font-size: 15px; outline: none; background: white; color: #1a1a1a; transition: 0.3s;"
                            onfocus="this.style.borderColor='#a88a4c'" onblur="this.style.borderColor='#a88a4c30'" />
                        <p style="font-size: 11px; color: rgba(168,138,76,0.6); margin: 10px 0 0 0; font-style: italic; font-weight: 500;">* Note: Walkthrough should be prior to the event date.</p>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; color: rgba(26,26,26,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 600;">RESCHEDULING REASON</label>
                        <textarea name="reschedule_reason" rows="3" placeholder="Explain the reason for this change..."
                            style="width: 100%; border: 1px solid #a88a4c30; padding: 15px 18px; border-radius: 4px; font-size: 15px; outline: none; background: white; color: #1a1a1a; resize: none; transition: 0.3s;"
                            onfocus="this.style.borderColor='#a88a4c'" onblur="this.style.borderColor='#a88a4c30'"></textarea>
                    </div>
                </div>

                <div id="rescheduleError" style="background: #fffafa; color: #c53030; border: 1px solid #feb2b2; padding: 15px; border-radius: 4px; font-size: 13px; font-weight: 700; margin-top: 25px; display: none; text-align: center;"></div>

                {{-- Footer buttons --}}
                <div style="display: flex; gap: 20px; margin-top: 40px;">
                    <button type="button" onclick="closeRescheduleModal()" 
                        style="flex: 1; padding: 18px; border: 1px solid #a88a4c; background: transparent; color: #a88a4c; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.2em; cursor: pointer; transition: 0.3s; border-radius: 2px;"
                        onmouseover="this.style.background='#a88a4c10'" onmouseout="this.style.background='transparent'">
                        Cancel
                    </button>
                    <button type="submit" 
                        style="flex: 1; padding: 18px; border: none; background: #a88a4c; color: white; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.2em; cursor: pointer; transition: 0.3s; border-radius: 2px; box-shadow: 0 4px 15px rgba(168,138,76,0.25);"
                        onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
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
      feeNotice.style.backgroundColor = '#a88a4c10';
      feeNotice.style.color = '#a88a4c';
      feeNotice.style.border = '1px solid #a88a4c30';
    } else {
      feeNotice.innerHTML = '⚠️ RESCHEDULE FEE: ₱5,000 (ATTEMPT #' + (rescheduleCount + 1) + ')';
      feeNotice.style.backgroundColor = '#1a1a1a05';
      feeNotice.style.color = '#1a1a1a';
      feeNotice.style.border = '1px solid #1a1a1a10';
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
      errorEl.classList.remove('hidden');
      return false;
    }

    if (eventDate <= today) {
      errorEl.textContent = 'Event date must be a future date.';
      errorEl.classList.remove('hidden');
      return false;
    }

    if (visitDate <= today) {
      errorEl.textContent = 'Visit date must be a future date.';
      errorEl.classList.remove('hidden');
      return false;
    }

    if (visitDate >= eventDate) {
      errorEl.textContent = 'Visit date must be before the event date.';
      errorEl.classList.remove('hidden');
      return false;
    }

    errorEl.classList.add('hidden');
    return true;
  }

  // Close modal on backdrop click
  window.addEventListener('click', function(e) {
    const modal = document.getElementById('rescheduleModal');
    if (e.target === modal) closeRescheduleModal();
  });
</script>
</body>
</html>
