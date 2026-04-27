<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Bookings | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<section class="py-[40px] md:py-[60px] px-[5%] lg:px-[8%] min-h-[60vh]">
  <a href="{{ route('home') }}" class="text-warm-black/50 text-[15px] no-underline hover:text-gold-deep transition-colors inline-block mb-5">← Back to Home</a>
  <h1 class="font-heading text-[28px] md:text-[36px] font-bold text-gold-deep mb-2">My Bookings</h1>
  <p class="text-warm-black/60 text-[16px] mb-8">Track your reservation requests here.</p>

  @forelse($bookings as $booking)
    <div class="bg-cream border border-gold-deep/20 rounded-xl p-5 md:p-6 mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-all hover:border-gold-deep/40">
      <div class="flex-1">
        <div class="flex justify-between items-start mb-1.5">
            <h3 class="text-[15px] md:text-[16px] font-bold text-warm-black">{{ $booking->event_type }} — {{ $booking->package }} Package</h3>
            <a href="{{ route('booking.receipt', $booking->id) }}" class="shrink-0 inline-flex items-center gap-1.5 bg-gold-deep/10 border border-gold-deep/30 text-gold-deep px-3 py-1.5 rounded-md text-[12px] font-bold no-underline transition-all hover:bg-gold-deep hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                PDF Receipt
            </a>
        </div>
        <p class="text-[13px] text-gold-deep font-bold mb-2 tracking-[0.5px]">#{{ $booking->booking_number }}</p>
        <p class="text-[15px] text-warm-black/90 mb-0.5">📅 {{ $booking->event_date->format('F d, Y') }}</p>
        <p class="text-[15px] text-warm-black/90 mb-0.5">⏰ {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</p>
        <p class="text-[15px] text-warm-black/90 mb-3">👥 {{ $booking->guest_count }} guests</p>

        <!-- Payment & Visit Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4 border-t border-gold-deep/10 pt-4">
            <div>
                <div class="text-[11px] text-warm-black/50 font-bold uppercase mb-1">Payment Status</div>
                @if($booking->payment_status === 'unpaid')
                  <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold bg-red-400/15 text-red-500 border border-red-400">Unpaid</span>
                @elseif($booking->payment_status === 'partially_paid')
                  <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold bg-green-400/15 text-green-600 border border-green-400">Partially Paid (20%)</span>
                @else
                  <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-400/15 text-blue-600 border border-blue-400">Fully Paid</span>
                @endif
                <div class="text-[13px] mt-1 text-warm-black/60">Total: ₱{{ number_format($booking->total_amount, 2) }}</div>
            </div>
            <div>
                <div class="text-[11px] text-warm-black/50 font-bold uppercase mb-1">Venue Visit</div>
                @php $visit = $booking->visitSchedules->first(); @endphp
                @if($visit)
                  <div class="text-[13px] font-bold">{{ $visit->visit_date->format('M d, Y @ h:i A') }}</div>
                  @if($visit->status === 'pending')
                    <span class="text-[11px] font-bold text-yellow-600">⏳ Pending Approval</span>
                  @elseif($visit->status === 'confirmed')
                    <span class="text-[11px] font-bold text-green-600">✓ Confirmed</span>
                  @elseif($visit->status === 'rescheduled')
                    <span class="text-[11px] font-bold text-blue-600">🔄 Rescheduled</span>
                  @else
                    <span class="text-[11px] font-bold text-warm-black/50">Completed</span>
                  @endif
                @else
                  <a href="{{ route('visit-schedule.create', ['booking' => $booking->id]) }}" class="inline-block bg-warm-black text-[11px] text-white px-3 py-1.5 rounded font-bold hover:bg-gold-deep transition-colors">📅 Schedule Visit</a>
                @endif
            </div>
        </div>

        @if($booking->notes)
          <p class="text-[14px] text-warm-black/60 mt-3 italic border-l-2 border-gold-deep/20 pl-3">"{{ $booking->notes }}"</p>
        @endif
      </div>
      <div class="shrink-0 flex flex-col items-end gap-2">
        <div class="text-[11px] text-warm-black/50 font-bold uppercase">Booking Status</div>
        @if($booking->status === 'pending')
          <span class="inline-block px-3.5 py-1.5 rounded-full text-[12px] font-bold tracking-[0.5px] bg-yellow-400/15 text-yellow-600 border border-yellow-400">⏳ Pending</span>
        @elseif($booking->status === 'approved')
          <span class="inline-block px-3.5 py-1.5 rounded-full text-[12px] font-bold tracking-[0.5px] bg-green-400/15 text-green-600 border border-green-400">✓ Approved</span>
        @else
          <span class="inline-block px-3.5 py-1.5 rounded-full text-[12px] font-bold tracking-[0.5px] bg-red-400/15 text-red-500 border border-red-400">✕ Rejected</span>
        @endif

        {{-- Reschedule Status Badge --}}
        @if($booking->reschedule_status === 'pending')
          <span class="inline-block px-3 py-1.5 rounded-full text-[11px] font-bold tracking-[0.5px] bg-yellow-400/15 text-yellow-600 border border-yellow-400">🔄 Reschedule Pending</span>
        @elseif($booking->reschedule_status === 'approved')
          <span class="inline-block px-3 py-1.5 rounded-full text-[11px] font-bold tracking-[0.5px] bg-green-400/15 text-green-600 border border-green-400">
            ✓ Rescheduled — {{ $booking->reschedule_fee == 0 ? 'FREE' : '₱' . number_format($booking->reschedule_fee) }}
          </span>
        @elseif($booking->reschedule_status === 'rejected')
          <span class="inline-block px-3 py-1.5 rounded-full text-[11px] font-bold tracking-[0.5px] bg-red-400/15 text-red-500 border border-red-400">✕ Reschedule Rejected</span>
        @endif

        {{-- Reschedule Button —  only for approved bookings with no pending reschedule --}}
        @if($booking->status === 'approved' && $booking->reschedule_status !== 'pending')
          <button type="button"
            class="mt-1 inline-block bg-blue-500/10 border border-blue-500 text-blue-600 px-3.5 py-1.5 rounded-full text-[12px] font-bold tracking-[0.5px] cursor-pointer transition-all hover:bg-blue-500 hover:text-white"
            onclick="openRescheduleModal({{ $booking->id }}, '{{ $booking->event_date->format('F d, Y') }}', '{{ optional($booking->visitSchedules->first())->visit_date ? $booking->visitSchedules->first()->visit_date->format('F d, Y') : 'Not scheduled' }}', {{ $booking->reschedule_count }})">
            🔄 Reschedule
          </button>
        @endif
      </div>
    </div>
  @empty
    <div class="text-center py-[80px] text-warm-black/50 text-[16px]">
      <p class="mb-5">You have no bookings yet.</p>
      <a href="{{ route('booking') }}" class="inline-block bg-gold-deep text-white px-6 py-3 rounded-md no-underline font-bold text-[15px] tracking-[1px] transition-all hover:bg-gold-mid">BOOK NOW</a>
    </div>
  @endforelse

  @if($bookings->count() > 0)
    <a href="{{ route('booking') }}" class="inline-block bg-gold-deep text-white px-6 py-3 rounded-md no-underline font-bold text-[15px] tracking-[1px] transition-all hover:bg-gold-mid mt-4">+ NEW BOOKING</a>
  @endif
</section>

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
    
    // Reset dates
    const ev = document.getElementById('reschedule_event_date');
    const vi = document.getElementById('reschedule_visit_date');
    if(ev) ev.value = '';
    if(vi) vi.value = '';
    
    document.getElementById('rescheduleError').classList.add('hidden');

    // Fee notice
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

    const m = document.getElementById('rescheduleModal');
    if(m) m.style.display = 'flex';
  }

  function closeRescheduleModal() {
    const m = document.getElementById('rescheduleModal');
    if(m) m.style.display = 'none';
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

  window.addEventListener('click', function(e) {
    const m = document.getElementById('rescheduleModal');
    if (e.target === m) closeRescheduleModal();
  });
</script>
</body>
</html>