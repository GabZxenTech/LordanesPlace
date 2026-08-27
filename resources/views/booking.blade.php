<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Book Now | LorDane's Place</title>
  <meta name="description" content="Book your event at LorDane's Place. Check availability, choose a package, and reserve your date.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* Calendar-specific styles that are hard to replicate in pure Tailwind */
    .cal-day:hover:not(.unavailable):not(.past):not(.empty) { background: rgba(184,134,11,0.15); color: #B8860B; }
    .cal-day.selected { background: #B8860B; color: #fff; font-weight: 700; }
    .cal-day.unavailable { background: #fde8e8; color: #e74c3c; cursor: pointer; }
    .cal-day.past { color: rgba(26,18,8,0.25); cursor: not-allowed; }
    .cal-day.beyond-year { background: #f0f0f0; color: rgba(26,18,8,0.20); cursor: not-allowed; }
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
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">{{ \App\Models\Booking::downPaymentRatePercent() }}% downpayment required to confirm booking. Full payment due on event day.</p>
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
      <p class="text-[15px] text-warm-black/60 font-normal leading-[1.6]">Pay the {{ \App\Models\Booking::downPaymentRatePercent() }}% downpayment to confirm your reservation.</p>
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

      {{-- Blocked Date Alert Box --}}
      <div id="blockedDateAlert" class="hidden mt-6 bg-[#fde8e8] border border-[#e74c3c] rounded-xl p-5 flex gap-4 items-center animate-[popIn_0.3s_ease]">
          <div class="w-7 h-7 flex-shrink-0 bg-[#fa5252] rounded-full flex items-center justify-center">
            <div class="w-3.5 h-1 bg-white rounded-full"></div>
          </div>
          <div class="text-[14px] text-[#e74c3c] font-medium leading-relaxed" id="blockedDateReason">
              This date has been blocked by the venue admin (e.g. maintenance, private hold, or holiday).
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

      @if($errors->any() && !session('booking_success'))
        <div class="bg-red-500/10 border border-red-500 text-red-600 p-3 rounded-md mb-5 text-[15px]">
          @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
        @csrf
        <input type="hidden" name="event_date" id="eventDateInput" value="{{ old('event_date') }}" required />
        
        {{-- Hidden data container for JS --}}
        <div id="booking-data" class="hidden" 
             data-blocked-dates='{!! json_encode($blockedDates ?? []) !!}'
             data-approved-dates='{!! json_encode($approvedDates ?? []) !!}'
             data-old-date="{{ old('event_date') }}"
             data-booking-success="{{ session('booking_success') ? 'true' : 'false' }}"
             data-visit-success="{{ session('visit_success') ? 'true' : 'false' }}"
             data-check-date-url="{{ route('booking.check-date') }}">
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Event Type</label>
          <select name="event_type" id="eventTypeSelect" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
            <option value="">Select event type</option>
            @php
              $eventTypes = ['Birthday', 'Wedding', 'Debut', 'Baptismal', 'Christening', 'Christmas Party', 'Corporate Event', 'Reunion', 'Others'];
            @endphp
            @foreach($eventTypes as $type)
              <option value="{{ $type }}" {{ old('event_type') == $type || (old('event_type') && !in_array(old('event_type'), $eventTypes) && $type == 'Others') ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
          </select>
          @error('event_type') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4" id="otherEventTypeContainer" style="display: none;">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Please Specify Event Type</label>
          <input type="text" name="event_type_other" id="eventTypeOther" placeholder="Enter your event type"
            value="{{ old('event_type_other', (old('event_type') && !in_array(old('event_type'), ['Birthday','Wedding','Debut','Baptismal','Christening','Christmas Party','Corporate Event','Reunion','Others',''])) ? old('event_type') : '') }}"
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body" />
          @error('event_type_other') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Package</label>
          <select name="package" id="packageSelect" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
            <option value="">Select a package</option>
            @foreach($packages as $pkg)
              <option value="{{ $pkg->name }}" data-max="{{ $pkg->max_guests }}" data-price="{{ $pkg->price }}" data-start="{{ $pkg->start_time ? \Carbon\Carbon::parse($pkg->start_time)->format('H:i') : '' }}" data-end="{{ $pkg->end_time ? \Carbon\Carbon::parse($pkg->end_time)->format('H:i') : '' }}" {{ old('package') == $pkg->name ? 'selected' : '' }}>
                {{ $pkg->name }} — ₱{{ number_format($pkg->price, 0) }} (up to {{ $pkg->max_guests }} guests{{ $pkg->duration ? ', ' . $pkg->duration : '' }})
              </option>
            @endforeach
          </select>
          @error('package') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>



        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Number of Guests</label>
          <input type="number" name="guest_count" id="guestCountInput" placeholder="How many guests?" min="1" value="{{ old('guest_count') }}" required
            inputmode="numeric" pattern="[0-9]*"
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            onkeydown="return (event.key >= '0' && event.key <= '9') || ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'].includes(event.key)"
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body" />
          @error('guest_count') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Total Amount (₱)</label>
          <input type="number" name="total_amount" id="totalAmountInput" placeholder="0.00" step="0.01" value="{{ old('total_amount') }}" required
            readonly
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body cursor-not-allowed opacity-75" />
          @error('total_amount') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Payment Option</label>
          <select name="payment_option" id="paymentOptionSelect" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
            <option value="">Select a payment option</option>
            <option value="downpayment" {{ old('payment_option', 'downpayment') === 'downpayment' ? 'selected' : '' }}>{{ \App\Models\Booking::downPaymentRatePercent() }}% Downpayment</option>
            <option value="full_payment" {{ old('payment_option') === 'full_payment' ? 'selected' : '' }}>Full Payment</option>
          </select>
          @error('payment_option') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror

          <div class="mt-2 text-[13px] text-warm-black/70 bg-gold-deep/5 p-3 rounded border border-gold-deep/10 flex flex-col gap-1.5">
            <div class="flex justify-between items-center">
              <span>Total Booking Amount:</span>
              <span class="font-bold text-warm-black" id="summaryTotal">₱0.00</span>
            </div>
            <div class="flex justify-between items-center">
              <span id="summaryAmountLabel">{{ \App\Models\Booking::downPaymentRatePercent() }}% Downpayment Amount:</span>
              <span class="font-bold text-gold-deep" id="summaryAmountToPay">₱0.00</span>
            </div>
            <div class="flex justify-between items-center" id="summaryRemainingRow">
              <span>Remaining Balance:</span>
              <span class="font-bold text-warm-black" id="summaryRemaining">₱0.00</span>
            </div>
          </div>
          <p class="text-[11px] text-warm-black/50 mt-1.5 italic">This selects your preferred payment arrangement only — it does not process or confirm any payment.</p>
        </div>

        <div class="mb-5">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Additional Notes <span class="font-normal">(optional)</span></label>
          <textarea name="notes" rows="3" placeholder="Any special requests or notes..."
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body resize-y">{{ old('notes') }}</textarea>
        </div>

        {{-- Please Note Box --}}
        <div class="bg-blue-500/10 border border-blue-400/30 rounded-lg p-4 mb-6 flex gap-3.5 items-start">
            <span class="text-[20px] mt-0.5">📌</span>
            <div class="text-[13.5px] text-warm-black/80 leading-relaxed">
                <strong>Please note:</strong> A {{ \App\Models\Booking::downPaymentRatePercent() }}% down payment is required to confirm your reservation. Our admin will contact you with payment instructions after booking.
            </div>
        </div>

        {{-- Checkbox + Terms --}}
        <div class="mb-6 flex items-start gap-3">
          <input type="checkbox" name="terms" id="termsCheckbox" required class="mt-1 w-4 h-4 accent-gold-deep cursor-pointer" />
          <label for="termsCheckbox" class="text-[13.5px] text-warm-black/80 leading-relaxed cursor-pointer">
            I have read and agree to the <a href="{{ route('terms') }}" target="_blank" class="text-gold-deep font-bold underline hover:text-gold-mid">Terms and Conditions</a> of LorDane's Place.
          </label>
        </div>

        {{-- Confirm Booking Button --}}
        <button type="submit" class="w-full bg-gold-deep text-white border-none py-3.5 rounded-md font-bold text-[16px] tracking-[2px] cursor-pointer transition-all duration-300 hover:bg-gold-mid hover:shadow-lg">CONFIRM BOOKING</button>
      </form>
    </div>
  </div>
</section>

<!-- SUCCESS MODAL + VISIT SCHEDULING -->
<div class="modal-overlay hidden fixed inset-0 bg-black/70 z-[9999] items-center justify-center" id="successModal">
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

        <div class="mb-6 text-[13px] text-warm-black/60 italic">Your official receipt will be available here once the admin confirms your payment.</div>
    </div>

    <div class="bg-cream border border-gold-deep/15 rounded-xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-full bg-gold-deep text-white flex items-center justify-center font-bold text-[14px]">2</div>
            <h3 class="text-[18px] md:text-[20px] font-bold text-warm-black">Schedule Your Visit</h3>
        </div>
        <p class="text-[14px] text-warm-black/70 mb-6 font-normal">Please select a date and time to visit our venue for final payment and a walkthrough before your event.</p>

        @if($errors->any() && session('booking_success'))
          <div class="bg-red-500/10 border border-red-500 text-red-600 p-3 rounded-md mb-5 text-[14px]">
            @foreach($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        @endif

        @php
            $newBooking = session('new_booking_id') ? \App\Models\Booking::find(session('new_booking_id')) : null;
            $maxVisitDate = $newBooking ? $newBooking->event_date->copy()->subDay()->format('Y-m-d') : null;
            $eventDateVal = $newBooking ? $newBooking->event_date->format('Y-m-d') : null;
        @endphp
        <form action="{{ route('visit-schedule.store') }}" method="POST" id="visitModalForm">
            @csrf
            <input type="hidden" name="booking_id" value="{{ session('new_booking_id') }}">
            <input type="hidden" id="modal_event_date" value="{{ $eventDateVal }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-[11px] tracking-[1px] text-gold-deep mb-2 font-bold uppercase">Preferred Date</label>
                    <input type="date" name="visit_date" id="modal_visit_date_input" required
                        min="{{ date('Y-m-d') }}"
                        @if($maxVisitDate) max="{{ $maxVisitDate }}" @endif
                        value="{{ old('visit_date') }}"
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
                                <option value="{{ $val24 }}" {{ old('visit_time') == $val24 ? 'selected' : '' }}>{{ $formatted }}</option>
                            @endforeach
                        @endfor
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-[11px] tracking-[1px] text-gold-deep mb-2 font-bold uppercase">Optional Notes</label>
                <textarea name="notes" rows="2" placeholder="e.g. I'll bring my partner..."
                    class="w-full bg-off-white border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[14px] outline-none transition-colors focus:border-gold-deep font-body resize-y">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="w-full bg-gold-deep text-white border-none py-3.5 rounded-md font-bold text-[15px] tracking-[1px] cursor-pointer transition-all hover:bg-gold-mid">CONFIRM VISIT SCHEDULE</button>
        </form>
        <script>
            document.getElementById('visitModalForm')?.addEventListener('submit', function(e) {
                const visitVal = document.getElementById('modal_visit_date_input').value;
                const visitTimeVal = document.querySelector('#visitModalForm select[name="visit_time"]').value;
                const eventVal = document.getElementById('modal_event_date').value;
                const today = "{{ date('Y-m-d') }}";

                if (!visitVal || !visitTimeVal) {
                    e.preventDefault();
                    alert('Please select a visit schedule.');
                    return;
                }

                if (visitVal < today) {
                    e.preventDefault();
                    alert('The Site Visit date cannot be in the past.');
                    return;
                }

                if (eventVal && visitVal >= eventVal) {
                    e.preventDefault();
                    alert('The Site Visit must be scheduled before your event date.');
                    return;
                }
            });
        </script>
    </div>
  </div>
</div>

<!-- FINAL VISIT SUCCESS MODAL -->
<div class="modal-overlay hidden fixed inset-0 bg-black/70 z-[9999] items-center justify-center" id="visitSuccessModal">
  <div class="modal-box bg-off-white border border-gold-deep/25 rounded-2xl p-10 md:p-12 text-center max-w-[460px] w-[90%] relative">
    <button type="button" onclick="document.getElementById('visitSuccessModal').style.display='none'" class="absolute top-4 right-4 text-warm-black/40 hover:text-warm-black text-[22px] bg-transparent border-none cursor-pointer leading-none transition-colors">✕</button>
    <div class="text-[56px] mb-4">✨</div>
    <h2 class="font-heading text-[26px] md:text-[28px] font-bold text-gold-deep mb-3">Thank You!</h2>
    <p class="text-warm-black/90 text-[16px] leading-[1.7] mb-6">Thank you! Your visit has been scheduled. We look forward to seeing you at LorDane's Place soon!</p>
    <div class="flex gap-3 justify-center flex-wrap">
      <a href="{{ route('profile') }}" class="bg-gold-deep text-white px-6 py-2.5 rounded-md no-underline font-bold text-[15px] tracking-[1px] transition-all hover:bg-gold-mid">GO TO MY PROFILE</a>
    </div>
  </div>
</div>

@include('chat-assistant')

<script>
  const dataEl = document.getElementById('booking-data');
  const blockedDates = JSON.parse(dataEl.getAttribute('data-blocked-dates'));
  const approvedDates = JSON.parse(dataEl.getAttribute('data-approved-dates'));
  const checkDateUrl = dataEl.getAttribute('data-check-date-url');
  const bookingSuccess = dataEl.getAttribute('data-booking-success') === 'true';
  const visitSuccess = dataEl.getAttribute('data-visit-success') === 'true';

  // Modal Initial Visibility
  if (bookingSuccess) {
    const m = document.getElementById('successModal');
    m.style.display = 'flex';
    m.classList.add('open');
  }
  if (visitSuccess) {
    const m = document.getElementById('visitSuccessModal');
    m.style.display = 'flex';
    m.classList.add('open');
  }

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  // Event Type: show/hide "Others" text input
  const eventTypeSelect = document.getElementById('eventTypeSelect');
  const otherContainer = document.getElementById('otherEventTypeContainer');
  const otherInput = document.getElementById('eventTypeOther');

  function toggleOtherEventType() {
    if (eventTypeSelect.value === 'Others') {
      otherContainer.style.display = '';
      otherInput.required = true;
    } else {
      otherContainer.style.display = 'none';
      otherInput.required = false;
      otherInput.value = '';
    }
  }

  eventTypeSelect.addEventListener('change', toggleOtherEventType);
  // Initialize on load (handles old() after validation failure)
  toggleOtherEventType();

  let currentDate = new Date();
  let selectedDate = null;
  let selectedPackage = document.getElementById('packageSelect').value;

  const oldDate = dataEl.getAttribute('data-old-date');
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
  });

  // Dynamic Payment Option Summary (Full Payment vs. Downpayment)
  const DOWN_PAYMENT_RATE = {{ \App\Models\Booking::DOWN_PAYMENT_RATE }};
  const DOWN_PAYMENT_PERCENT = {{ \App\Models\Booking::downPaymentRatePercent() }};
  const totalAmountInput = document.getElementById('totalAmountInput');
  const paymentOptionSelect = document.getElementById('paymentOptionSelect');
  const summaryTotal = document.getElementById('summaryTotal');
  const summaryAmountLabel = document.getElementById('summaryAmountLabel');
  const summaryAmountToPay = document.getElementById('summaryAmountToPay');
  const summaryRemaining = document.getElementById('summaryRemaining');
  const summaryRemainingRow = document.getElementById('summaryRemainingRow');

  function formatPeso(amount) {
    return '₱' + amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function updateDownPayment() {
    const total = parseFloat(totalAmountInput.value) || 0;
    const option = paymentOptionSelect.value;

    let amountToPay = 0;
    let remaining = 0;

    if (option === 'full_payment') {
      amountToPay = total;
      remaining = 0;
      summaryAmountLabel.textContent = 'Amount to Pay:';
      summaryRemainingRow.style.display = 'none';
    } else {
      // Default preview (downpayment) shown even before a choice is made, so
      // the summary is never blank while the customer is still deciding.
      amountToPay = Math.round(total * DOWN_PAYMENT_RATE * 100) / 100;
      remaining = Math.round((total - amountToPay) * 100) / 100;
      summaryAmountLabel.textContent = DOWN_PAYMENT_PERCENT + '% Downpayment Amount:';
      summaryRemainingRow.style.display = 'flex';
    }

    summaryTotal.textContent = formatPeso(total);
    summaryAmountToPay.textContent = formatPeso(amountToPay);
    summaryRemaining.textContent = formatPeso(remaining);
  }

  totalAmountInput.addEventListener('input', updateDownPayment);
  paymentOptionSelect.addEventListener('change', updateDownPayment);
  updateDownPayment();

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

  // One Event Per Day: unavailable = blocked dates + approved booking dates (global, not per-package)
  function getUnavailableDates() {
    const blockedDateStrings = Object.keys(blockedDates);
    return [...blockedDateStrings, ...approvedDates];
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
        // Click listener to show reason for unavailability
        div.addEventListener('click', () => {
          showBlockedReason(dateStr);
        });
      } else {
        if (formatDate(today) === dateStr) div.classList.add('today');
        if (selectedDate === dateStr) div.classList.add('selected');
        div.addEventListener('click', () => selectDate(dateStr, div));
      }
      grid.appendChild(div);
    }
  }

  function showBlockedReason(dateStr) {
    // Determine the reason: blocked by admin or reserved by approved booking
    let reason;
    if (blockedDates[dateStr]) {
      reason = blockedDates[dateStr];
    } else if (approvedDates.includes(dateStr)) {
      reason = 'This date is already reserved for another event. Please choose another available date.';
    } else {
      reason = 'This date has been blocked by the venue admin (e.g. maintenance, private hold, or holiday).';
    }
    
    const alertBox = document.getElementById('blockedDateAlert');
    const reasonText = document.getElementById('blockedDateReason');
    
    reasonText.textContent = reason;
    alertBox.classList.remove('hidden');
    
    // Unselect any selected date
    selectedDate = null;
    document.getElementById('eventDateInput').value = '';
    document.getElementById('selectedDateDisplay').textContent = '📅 Please select an available date';
    renderCalendar();
  }

  function selectDate(dateStr, el) {
    document.querySelectorAll('.cal-day.selected').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');
    selectedDate = dateStr;
    document.getElementById('eventDateInput').value = dateStr;
    document.getElementById('selectedDateDisplay').textContent = '📅 Selected: ' + formatDisplay(dateStr);
    
    // Hide blocked alert if it was showing
    document.getElementById('blockedDateAlert').classList.add('hidden');

    // Real-time AJAX validation: double-check with the server
    fetch(checkDateUrl + '?date=' + dateStr)
      .then(res => res.json())
      .then(data => {
        if (!data.available) {
          // Date became unavailable (e.g., just approved by admin)
          selectedDate = null;
          document.getElementById('eventDateInput').value = '';
          document.getElementById('selectedDateDisplay').textContent = '📅 Please select an available date';
          
          const alertBox = document.getElementById('blockedDateAlert');
          const reasonText = document.getElementById('blockedDateReason');
          reasonText.textContent = data.reason;
          alertBox.classList.remove('hidden');

          // Add to local approved dates so calendar re-renders correctly
          if (!approvedDates.includes(dateStr)) {
            approvedDates.push(dateStr);
          }
          renderCalendar();
        }
      })
      .catch(() => {
        // Silently fail — backend validation will still catch it
      });
  }

  function changeMonth(dir) {
    const nextDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + dir, 1);
    // Prevent navigating before the current month
    if (dir < 0 && (nextDate.getFullYear() < today.getFullYear() || (nextDate.getFullYear() === today.getFullYear() && nextDate.getMonth() < today.getMonth()))) return;
    currentDate = nextDate;
    renderCalendar();
  }

  renderCalendar();
</script>
</body>
</html>