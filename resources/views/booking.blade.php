<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Now | LorDane's Place</title>
  <meta name="description" content="Book your event at LorDane's Place. Check availability, choose a package, and reserve your date.">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* Calendar-specific styles that are hard to replicate in pure Tailwind */
    .cal-day:hover:not(.unavailable):not(.past):not(.empty) { background: rgba(184,134,11,0.15); color: #B8860B; }
    .cal-day.selected { background: #B8860B; color: #fff; font-weight: 700; }
    .cal-day.unavailable { background: rgba(231,76,60,0.1); color: #e74c3c; cursor: not-allowed; text-decoration: line-through; }
    .cal-day.past { color: rgba(26,18,8,0.25); cursor: not-allowed; }
    .cal-day.today { border: 1px solid #B8860B; }
    .cal-day.empty { cursor: default; }
    @keyframes popIn { from { transform: scale(0.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .modal-overlay.open { display: flex; }
    .modal-box { animation: popIn 0.3s ease; }
  </style>
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<!-- BOOKING INFO -->
<section class="py-[50px] md:py-[70px] px-[5%] lg:px-[8%] bg-cream border-b border-gold-deep/15">
  <div class="text-center mb-8 md:mb-10">
    <span class="text-[11px] md:text-[12px] tracking-[4px] text-gold-deep font-bold block mb-2">BEFORE YOU BOOK</span>
    <h2 class="font-heading text-[28px] md:text-[36px] font-bold text-warm-black">Booking Information</h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
    <div class="border border-gold-deep/25 rounded-lg p-5 md:p-6 text-center bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1">
      <div class="text-[28px] mb-3">🕐</div>
      <h4 class="text-[12px] md:text-[15px] font-bold text-gold-deep tracking-[1px] mb-2">OPERATING HOURS</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Open daily from 8:00 AM to 10:00 PM. Events must end by 10:00 PM.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 md:p-6 text-center bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1">
      <div class="text-[28px] mb-3">💳</div>
      <h4 class="text-[12px] md:text-[15px] font-bold text-gold-deep tracking-[1px] mb-2">PAYMENT POLICY</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">20% downpayment required to confirm booking. Full payment due on event day.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 md:p-6 text-center bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1">
      <div class="text-[28px] mb-3">🔄</div>
      <h4 class="text-[12px] md:text-[15px] font-bold text-gold-deep tracking-[1px] mb-2">CANCELLATION POLICY</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Cancel at least 48 hours before your event for a refund. No-shows are non-refundable.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 md:p-6 text-center bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1">
      <div class="text-[28px] mb-3">👥</div>
      <h4 class="text-[12px] md:text-[15px] font-bold text-gold-deep tracking-[1px] mb-2">MAXIMUM CAPACITY</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Up to 200–250 guests. Packages available for small to large events.</p>
    </div>
  </div>
</section>

<!-- BOOKING PROCESS -->
<section class="py-[50px] md:py-[70px] px-[5%] lg:px-[8%] bg-off-white border-b border-gold-deep/15">
  <div class="text-center mb-8 md:mb-10">
    <span class="text-[11px] md:text-[12px] tracking-[4px] text-gold-deep font-bold block mb-2">HOW IT WORKS</span>
    <h2 class="font-heading text-[28px] md:text-[36px] font-bold text-warm-black">Booking Process</h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
    <div class="text-center p-5 md:p-6 bg-cream border border-gold-deep/15 rounded-lg">
      <div class="w-10 h-10 rounded-full bg-gold-deep text-white font-bold text-[16px] flex items-center justify-center mx-auto mb-3">1</div>
      <h4 class="text-[16px] font-bold text-warm-black mb-2">Check Availability</h4>
      <p class="text-[15px] text-warm-black/60 font-normal leading-[1.6]">Review the calendar for available dates.</p>
    </div>
    <div class="text-center p-5 md:p-6 bg-cream border border-gold-deep/15 rounded-lg">
      <div class="w-10 h-10 rounded-full bg-gold-deep text-white font-bold text-[16px] flex items-center justify-center mx-auto mb-3">2</div>
      <h4 class="text-[16px] font-bold text-warm-black mb-2">Submit Request</h4>
      <p class="text-[15px] text-warm-black/60 font-normal leading-[1.6]">Fill out the booking form with your event details.</p>
    </div>
    <div class="text-center p-5 md:p-6 bg-cream border border-gold-deep/15 rounded-lg">
      <div class="w-10 h-10 rounded-full bg-gold-deep text-white font-bold text-[16px] flex items-center justify-center mx-auto mb-3">3</div>
      <h4 class="text-[16px] font-bold text-warm-black mb-2">Pay Deposit</h4>
      <p class="text-[15px] text-warm-black/60 font-normal leading-[1.6]">Pay the 20% downpayment to confirm your reservation.</p>
    </div>
    <div class="text-center p-5 md:p-6 bg-cream border border-gold-deep/15 rounded-lg">
      <div class="w-10 h-10 rounded-full bg-gold-deep text-white font-bold text-[16px] flex items-center justify-center mx-auto mb-3">4</div>
      <h4 class="text-[16px] font-bold text-warm-black mb-2">Confirmation</h4>
      <p class="text-[15px] text-warm-black/60 font-normal leading-[1.6]">Receive confirmation and prepare for your event!</p>
    </div>
  </div>
</section>

<!-- MAIN BOOKING -->
<section class="py-[50px] md:py-[70px] px-[5%] lg:px-[8%] bg-cream">
  <div class="text-center mb-8 md:mb-10">
    <span class="text-[11px] md:text-[12px] tracking-[4px] text-gold-deep font-bold block mb-2">RESERVE YOUR DATE</span>
    <h2 class="font-heading text-[28px] md:text-[36px] font-bold text-warm-black">Availability Calendar & Booking Form</h2>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 max-w-[1200px] mx-auto">
    <!-- CALENDAR -->
    <div class="bg-off-white border border-gold-deep/20 rounded-xl p-5 md:p-7">
      <h3 class="text-[12px] md:text-[15px] tracking-[2px] text-gold-deep mb-5 font-bold">AVAILABILITY CALENDAR</h3>
      <div class="flex justify-between items-center mb-4">
        <button onclick="changeMonth(-1)" class="w-8 h-8 rounded-full border border-gold-deep/30 text-gold-deep bg-transparent cursor-pointer text-[16px] transition-all hover:bg-gold-deep hover:text-white">‹</button>
        <span id="calMonthYear" class="text-[15px] font-bold text-warm-black"></span>
        <button onclick="changeMonth(1)" class="w-8 h-8 rounded-full border border-gold-deep/30 text-gold-deep bg-transparent cursor-pointer text-[16px] transition-all hover:bg-gold-deep hover:text-white">›</button>
      </div>
      <div class="grid grid-cols-7 gap-1" id="calGrid">
        <div class="text-center text-[11px] text-warm-black/50 font-bold tracking-[1px] py-1.5">SUN</div>
        <div class="text-center text-[11px] text-warm-black/50 font-bold tracking-[1px] py-1.5">MON</div>
        <div class="text-center text-[11px] text-warm-black/50 font-bold tracking-[1px] py-1.5">TUE</div>
        <div class="text-center text-[11px] text-warm-black/50 font-bold tracking-[1px] py-1.5">WED</div>
        <div class="text-center text-[11px] text-warm-black/50 font-bold tracking-[1px] py-1.5">THU</div>
        <div class="text-center text-[11px] text-warm-black/50 font-bold tracking-[1px] py-1.5">FRI</div>
        <div class="text-center text-[11px] text-warm-black/50 font-bold tracking-[1px] py-1.5">SAT</div>
      </div>
      <div class="flex gap-3 md:gap-4 mt-4 flex-wrap">
        <div class="flex items-center gap-1.5 text-[11px] text-warm-black/60">
          <div class="w-3 h-3 rounded-sm bg-gold-deep/20 border border-gold-deep"></div> Available
        </div>
        <div class="flex items-center gap-1.5 text-[11px] text-warm-black/60">
          <div class="w-3 h-3 rounded-sm bg-red-500/20"></div> Unavailable
        </div>
        <div class="flex items-center gap-1.5 text-[11px] text-warm-black/60">
          <div class="w-3 h-3 rounded-sm bg-gold-deep"></div> Selected
        </div>
      </div>
    </div>

    <!-- FORM -->
    <div class="bg-off-white border border-gold-deep/20 rounded-xl p-5 md:p-7">
      <h3 class="text-[12px] md:text-[15px] tracking-[2px] text-gold-deep mb-5 font-bold">BOOKING DETAILS</h3>

      @guest
        <div class="bg-gold-deep/10 border border-gold-deep/30 rounded-md p-3.5 text-[15px] text-warm-black/90 text-center mb-5">
          ⚠️ You need to <a href="{{ route('login') }}" class="text-gold-deep font-bold no-underline hover:underline">login</a> or <a href="{{ route('register') }}" class="text-gold-deep font-bold no-underline hover:underline">create an account</a> to submit a booking.
        </div>
      @endguest

      <div class="bg-gold-deep/10 border border-gold-deep/25 rounded-md p-2.5 text-[15px] text-gold-deep text-center mb-5" id="selectedDateDisplay">
        📅 Please select a date from the calendar
      </div>

      @if($errors->any())
        <div class="bg-red-500/10 border border-red-500 text-red-600 p-3 rounded-md mb-5 text-[15px]">
          @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
        @csrf
        <input type="hidden" name="event_date" id="eventDateInput" value="{{ old('event_date') }}" required />

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Event Type</label>
          <input type="text" name="event_type" placeholder="e.g. Birthday, Wedding, Debut" value="{{ old('event_type') }}" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body" />
          @error('event_type') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Package</label>
          <select name="package" id="packageSelect" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
            <option value="">Select a package</option>
            @foreach($packages as $pkg)
              <option value="{{ $pkg->name }}" data-max="{{ $pkg->max_guests }}" data-price="{{ $pkg->price }}" {{ old('package') == $pkg->name ? 'selected' : '' }}>
                {{ $pkg->name }} — ₱{{ number_format($pkg->price, 0) }} (up to {{ $pkg->max_guests }} guests{{ $pkg->duration ? ', ' . $pkg->duration : '' }})
              </option>
            @endforeach
          </select>
          @error('package') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-4">
          <div>
            <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Start Time</label>
            <select name="start_time" required
              class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
              <option value="">Select start time</option>
              @for($i = 8; $i <= 22; $i++)
                @foreach(['00', '30'] as $min)
                  @if($i == 22 && $min == '30') @continue @endif
                  @php
                    $val24 = sprintf('%02d:%s', $i, $min);
                    $formatted = \Carbon\Carbon::createFromFormat('H:i', $val24)->format('h:i A');
                  @endphp
                  <option value="{{ $val24 }}" {{ old('start_time') == $val24 ? 'selected' : '' }}>{{ $formatted }}</option>
                @endforeach
              @endfor
            </select>
            @error('start_time') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
          </div>
          <div>
            <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">End Time</label>
            <select name="end_time" required
              class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
              <option value="">Select end time</option>
              @for($i = 8; $i <= 22; $i++)
                @foreach(['00', '30'] as $min)
                  @if($i == 22 && $min == '30') @continue @endif
                  @php
                    $val24 = sprintf('%02d:%s', $i, $min);
                    $formatted = \Carbon\Carbon::createFromFormat('H:i', $val24)->format('h:i A');
                  @endphp
                  <option value="{{ $val24 }}" {{ old('end_time') == $val24 ? 'selected' : '' }}>{{ $formatted }}</option>
                @endforeach
              @endfor
            </select>
            @error('end_time') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Number of Guests</label>
          <input type="number" name="guest_count" id="guestCountInput" placeholder="How many guests?" min="1" value="{{ old('guest_count') }}" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body" />
          @error('guest_count') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Total Amount (₱)</label>
          <input type="number" name="total_amount" id="totalAmountInput" placeholder="0.00" step="0.01" value="{{ old('total_amount') }}" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body" />
          <div class="mt-2 text-[13px] text-warm-black/70 flex justify-between items-center bg-gold-deep/5 p-2.5 rounded border border-gold-deep/10">
            <span>20% Required Down Payment:</span>
            <span class="font-bold text-gold-deep" id="downPaymentDisplay">₱0.00</span>
          </div>
          @error('total_amount') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-5">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Additional Notes <span class="font-normal">(optional)</span></label>
          <textarea name="notes" rows="3" placeholder="Any special requests or notes..."
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body resize-y">{{ old('notes') }}</textarea>
        </div>

        <div class="bg-blue-500/10 border border-blue-400/30 rounded-lg p-4 mb-6 flex gap-3.5 items-start">
            <span class="text-[20px] mt-0.5">📌</span>
            <div class="text-[13.5px] text-warm-black/80 leading-relaxed">
                <strong>Please note:</strong> A 20% down payment is required to confirm your reservation. Our admin will contact you with payment instructions after booking.
            </div>
        </div>

        <button type="submit" class="w-full bg-gold-deep text-white border-none py-3 rounded-md font-bold text-[16px] tracking-[1px] cursor-pointer transition-all hover:bg-gold-mid">CONFIRM BOOKING</button>
      </form>
    </div>
  </div>
</section>

<!-- SUCCESS MODAL + VISIT SCHEDULING -->
<div class="modal-overlay hidden fixed inset-0 bg-black/70 z-[9999] items-center justify-center {{ session('booking_success') ? 'open' : '' }}" id="successModal" style="display:none; {{ session('booking_success') ? 'display:flex;' : '' }}">
  <div class="modal-box bg-off-white border border-gold-deep/25 rounded-2xl p-8 md:p-10 max-w-[650px] w-[95%] overflow-y-auto max-h-[90vh]">
    <div class="text-center mb-6">
        <div class="text-[48px] mb-2">🎉</div>
        <h2 class="font-heading text-[26px] md:text-[32px] font-bold text-gold-deep mb-2">Booking Submitted!</h2>
        @if(session('booking_number'))
          <div class="mb-4">
              <p class="text-[11px] tracking-[1px] text-warm-black/50 font-bold uppercase mb-1">Booking Reference</p>
              <p class="text-[18px] font-bold text-gold-deep">#{{ session('booking_number') }}</p>
          </div>
        @endif
        <p class="text-warm-black/80 text-[15px] leading-relaxed mb-4">Your reservation request has been received and is currently <strong class="text-gold-deep">pending approval</strong>.</p>
        
        @if(session('new_booking_id'))
          <div class="mb-6">
            <a href="{{ route('booking.receipt', session('new_booking_id')) }}" class="inline-flex items-center gap-2 bg-warm-black text-white px-5 py-2.5 rounded-md no-underline text-[14px] font-bold transition-all hover:bg-gold-deep shadow-sm">
              <span>📄</span> DOWNLOAD PDF RECEIPT
            </a>
          </div>
        @endif
    </div>

    <div class="bg-cream border border-gold-deep/15 rounded-xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-full bg-gold-deep text-white flex items-center justify-center font-bold text-[14px]">2</div>
            <h3 class="text-[18px] md:text-[20px] font-bold text-warm-black">Schedule Your Visit</h3>
        </div>
        <p class="text-[14px] text-warm-black/70 mb-6 font-normal">Please select a date and time to visit our venue for final payment and a walkthrough before your event.</p>

        <form action="{{ route('visit-schedule.store') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_id" value="{{ session('new_booking_id') }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-[11px] tracking-[1px] text-gold-deep mb-2 font-bold uppercase">Preferred Date</label>
                    <input type="date" name="visit_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full bg-off-white border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[14px] outline-none transition-colors focus:border-gold-deep font-body" />
                </div>
                <div>
                    <label class="block text-[11px] tracking-[1px] text-gold-deep mb-2 font-bold uppercase">Preferred Time</label>
                    <select name="visit_time" required
                        class="w-full bg-off-white border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[14px] outline-none transition-colors focus:border-gold-deep font-body">
                        <option value="">Select time</option>
                        @for($i = 8; $i <= 22; $i++)
                            @foreach(['00', '30'] as $min)
                                @if($i == 22 && $min == '30') @continue @endif
                                @php
                                    $val24 = sprintf('%02d:%s', $i, $min);
                                    $formatted = \Carbon\Carbon::createFromFormat('H:i', $val24)->format('h:i A');
                                @endphp
                                <option value="{{ $val24 }}">{{ $formatted }}</option>
                            @endforeach
                        @endfor
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-[11px] tracking-[1px] text-gold-deep mb-2 font-bold uppercase">Optional Notes</label>
                <textarea name="notes" rows="2" placeholder="e.g. I'll bring my partner..."
                    class="w-full bg-off-white border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[14px] outline-none transition-colors focus:border-gold-deep font-body resize-y"></textarea>
            </div>

            <button type="submit" class="w-full bg-gold-deep text-white border-none py-3.5 rounded-md font-bold text-[15px] tracking-[1px] cursor-pointer transition-all hover:bg-gold-mid">CONFIRM VISIT SCHEDULE</button>
        </form>

        <div class="mt-6 flex justify-center gap-6">
            <a href="{{ route('my.bookings') }}" class="text-[13px] text-warm-black/40 hover:text-gold-deep transition-colors font-medium">Skip for now</a>
            <a href="{{ route('home') }}" class="text-[13px] text-warm-black/40 hover:text-gold-deep transition-colors font-medium">Back to Home</a>
        </div>
    </div>
  </div>
</div>

<!-- FINAL VISIT SUCCESS MODAL -->
<div class="modal-overlay hidden fixed inset-0 bg-black/70 z-[9999] items-center justify-center {{ session('visit_success') ? 'open' : '' }}" id="visitSuccessModal" style="display:none; {{ session('visit_success') ? 'display:flex;' : '' }}">
  <div class="modal-box bg-off-white border border-gold-deep/25 rounded-2xl p-10 md:p-12 text-center max-w-[460px] w-[90%]">
    <div class="text-[56px] mb-4">✨</div>
    <h2 class="font-heading text-[26px] md:text-[28px] font-bold text-gold-deep mb-3">All Set!</h2>
    <p class="text-warm-black/90 text-[16px] leading-[1.7] mb-6">Thank you! Your visit has been scheduled. We look forward to seeing you at LorDane's Place soon!</p>
    <div class="flex gap-3 justify-center flex-wrap">
      <a href="{{ route('my.bookings') }}" class="bg-gold-deep text-white px-6 py-2.5 rounded-md no-underline font-bold text-[15px] tracking-[1px] transition-all hover:bg-gold-mid">GO TO MY BOOKINGS</a>
    </div>
  </div>
</div>

@include('chat-assistant')

<script>
  const blockedDates = @json($blockedDates ?? []);
  const bookedDatesByPackage = @json($bookedDatesByPackage ?? []);
  const unavailableDatesLegacy = @json($unavailableDates ?? []);

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  let currentDate = new Date();
  let selectedDate = null;
  let selectedPackage = document.getElementById('packageSelect').value;

  const oldDate = "{{ old('event_date') }}";
  if (oldDate) {
    selectedDate = oldDate;
    document.getElementById('selectedDateDisplay').textContent = '📅 Selected: ' + formatDisplay(oldDate);
  }

  document.getElementById('packageSelect').addEventListener('change', function() {
    selectedPackage = this.value;
    const selectedOption = this.options[this.selectedIndex];
    if(selectedOption && selectedOption.dataset.max) {
      document.getElementById('guestCountInput').max = selectedOption.dataset.max;
      document.getElementById('guestCountInput').placeholder = "Max: " + selectedOption.dataset.max;
      
      // Auto-fill total amount
      if(selectedOption.dataset.price) {
        document.getElementById('totalAmountInput').value = selectedOption.dataset.price;
        updateDownPayment();
      }
    } else {
      document.getElementById('guestCountInput').removeAttribute('max');
      document.getElementById('guestCountInput').placeholder = "How many guests?";
    }
    renderCalendar();
  });

  // Dynamic Down Payment Calculation
  const totalAmountInput = document.getElementById('totalAmountInput');
  const downPaymentDisplay = document.getElementById('downPaymentDisplay');

  function updateDownPayment() {
    const total = parseFloat(totalAmountInput.value) || 0;
    const downPayment = total * 0.20;
    downPaymentDisplay.textContent = '₱' + downPayment.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  totalAmountInput.addEventListener('input', updateDownPayment);
  if (totalAmountInput.value) updateDownPayment();

  const urlParams = new URLSearchParams(window.location.search);
  const packageFromUrl = urlParams.get('package');
  let oldPackage = selectedPackage;
  if (packageFromUrl) {
    let selectEl = document.getElementById('packageSelect');
    let optionExists = Array.from(selectEl.options).some(opt => opt.value === packageFromUrl);
    if (optionExists) { selectEl.value = packageFromUrl; oldPackage = packageFromUrl; }
  }
  if(oldPackage) { document.getElementById('packageSelect').dispatchEvent(new Event('change')); }

  function formatDisplay(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-PH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  }

  function formatDate(d) {
    return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
  }

  function getUnavailableDates() {
    if(unavailableDatesLegacy.length > 0) return unavailableDatesLegacy;
    let packageBooked = selectedPackage && bookedDatesByPackage[selectedPackage] ? bookedDatesByPackage[selectedPackage] : [];
    return [...blockedDates, ...packageBooked];
  }

  function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    document.getElementById('calMonthYear').textContent = `${monthNames[month]} ${year}`;

    const grid = document.getElementById('calGrid');
    while (grid.children.length > 7) grid.removeChild(grid.lastChild);

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const unavailables = getUnavailableDates();

    for (let i = 0; i < firstDay; i++) {
      const empty = document.createElement('div');
      empty.className = 'cal-day empty text-center py-2 text-[15px]';
      grid.appendChild(empty);
    }

    for (let d = 1; d <= daysInMonth; d++) {
      const dateObj = new Date(year, month, d);
      const dateStr = formatDate(dateObj);
      const div = document.createElement('div');
      div.className = 'cal-day text-center py-2 rounded-md text-[15px] cursor-pointer transition-all text-warm-black';
      div.textContent = d;

      if (dateObj < today) {
        div.classList.add('past');
      } else if (unavailables.includes(dateStr)) {
        div.classList.add('unavailable');
      } else {
        if (formatDate(today) === dateStr) div.classList.add('today');
        if (selectedDate === dateStr) div.classList.add('selected');
        div.addEventListener('click', () => selectDate(dateStr, div));
      }
      grid.appendChild(div);
    }
  }

  function selectDate(dateStr, el) {
    if (!selectedPackage) { alert('Please select a package first to view accurate availability.'); return; }
    document.querySelectorAll('.cal-day.selected').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');
    selectedDate = dateStr;
    document.getElementById('eventDateInput').value = dateStr;
    document.getElementById('selectedDateDisplay').textContent = '📅 Selected: ' + formatDisplay(dateStr);
  }

  function changeMonth(dir) {
    currentDate.setMonth(currentDate.getMonth() + dir);
    renderCalendar();
  }

  renderCalendar();
</script>
</body>
</html>