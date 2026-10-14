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
    .cal-day.in-range { background: #f1e3bf; color: #6b4d06; font-weight: 600; border-radius: 0; }
    .cal-day.unavailable { background: #fde8e8; color: #e74c3c; cursor: pointer; }
    .cal-day.past { color: rgba(26,18,8,0.25); cursor: not-allowed; }
    .cal-day.beyond-year { background: #f0f0f0; color: rgba(26,18,8,0.20); cursor: not-allowed; }
    .cal-day.today { border: 1px solid #B8860B; }
    .cal-day.empty { cursor: default; }
    @keyframes popIn { from { transform: scale(0.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .modal-overlay.open { display: flex; }
    .modal-box { animation: popIn 0.3s ease; }

    /* Hide the number-input spin buttons (up/down arrows) — inline
       -webkit-appearance:none on the input itself doesn't remove them;
       Chrome/Safari/Edge only respect it on these pseudo-elements. */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type="number"] {
      -moz-appearance: textfield;
    }
    /* A dropdown still showing its "Select …" prompt reads as a placeholder
       (gray, like the text inputs' placeholders); real choices stay dark. */
    select.is-placeholder { color: #9a8b74; }
    select option { color: #1a1208; }
    /* A dropdown that doesn't apply right now (Event Type for Swimming/
       Staycation, Package before an occasion is chosen) must look unusable,
       not just ignore clicks. */
    select:disabled {
      background-color: #e4dccb;
      border-color: #d6cab2;
      color: #a99c84;
      cursor: not-allowed;
      opacity: 1;
    }
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
      <div class="mb-3 flex justify-center text-gold-deep"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
      <h4 class="text-[12px] md:text-[15px] font-bold text-gold-deep tracking-[1px] mb-2">OPERATING HOURS</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Open daily from 8:00 AM to 10:00 PM. Events must end by 10:00 PM.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 md:p-6 text-center bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1">
      <div class="mb-3 flex justify-center text-gold-deep"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
      <h4 class="text-[12px] md:text-[15px] font-bold text-gold-deep tracking-[1px] mb-2">PAYMENT POLICY</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">{{ \App\Models\Booking::downPaymentRatePercent() }}% downpayment required to confirm booking. Full payment due on event day.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 md:p-6 text-center bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1">
      <div class="mb-3 flex justify-center text-gold-deep"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></div>
      <h4 class="text-[12px] md:text-[15px] font-bold text-gold-deep tracking-[1px] mb-2">CANCELLATION POLICY</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Cancel at least 48 hours before your event for a refund. No-shows are non-refundable.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 md:p-6 text-center bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1">
      <div class="mb-3 flex justify-center text-gold-deep"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
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
        <div class="flex items-center gap-1.5 text-[11px] text-warm-black/60">
          <div class="w-3 h-3 rounded-sm" style="background: #f1e3bf;"></div> In range
        </div>
      </div>
      <p id="rangeHint" class="text-[12px] text-warm-black/60 mt-3 mb-0" style="display: none;">
        <strong class="text-gold-deep">Swimming &amp; Staycation:</strong> click a start date, then a later end date to book several days in a row. Each day is charged separately.
      </p>

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
        <div class="bg-gold-deep/10 border border-gold-deep/30 rounded-md p-3.5 text-[15px] text-warm-black/90 text-center mb-5 flex items-center justify-center gap-2">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gold-deep flex-shrink-0"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
          <span>You need to <a href="{{ route('login') }}" class="text-gold-deep font-bold no-underline hover:underline">login</a> or <a href="{{ route('register') }}" class="text-gold-deep font-bold no-underline hover:underline">create an account</a> to submit a booking.</span>
        </div>
      @endguest

      <div class="bg-gold-deep/10 border border-gold-deep/25 rounded-md p-2.5 text-[15px] text-gold-deep text-center mb-5 flex items-center justify-center gap-2" id="selectedDateDisplay">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span id="selectedDateText">Please select a date from the calendar</span>
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
        <input type="hidden" name="event_end_date" id="eventEndDateInput" value="{{ old('event_end_date') }}" />
        
        {{-- Hidden data container for JS --}}
        <div id="booking-data" class="hidden" 
             data-blocked-dates="{{ json_encode($blockedDates ?? []) }}"
             data-approved-dates="{{ json_encode($approvedDates ?? []) }}"
             data-old-date="{{ old('event_date') }}"
             data-old-end-date="{{ old('event_end_date') }}"
             data-booking-success="{{ session('booking_success') ? 'true' : 'false' }}"
             data-visit-success="{{ session('visit_success') ? 'true' : 'false' }}"
             data-check-date-url="{{ route('booking.check-date') }}"
             data-room-availability-url="{{ route('booking.check-room-availability') }}"
             data-old-items="{{ json_encode(old('items', [])) }}">
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Occasion</label>
          <select name="occasion" id="occasionSelect" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
            <option value="" disabled {{ old('occasion') ? '' : 'selected' }} hidden>Select an occasion</option>
            @foreach(\App\Models\Booking::OCCASIONS as $occasionValue => $occasionLabel)
              <option value="{{ $occasionValue }}" {{ old('occasion') === $occasionValue ? 'selected' : '' }}>{{ $occasionLabel }}</option>
            @endforeach
          </select>
          @error('occasion') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Event Type <span id="eventTypeHint" class="font-normal text-warm-black/50" style="display: none;"></span></label>
          <select name="event_type" id="eventTypeSelect" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
            <option value="" disabled selected hidden>Select event type</option>
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
          <select id="packageSelect" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
            <option value="" disabled {{ old('package') ? '' : 'selected' }} hidden id="packagePlaceholder">Select a package</option>

            {{-- Non-room packages split into "Swimming" and "Event" groups;
                 room-type packages are rendered separately below so they
                 always stay grouped together at the bottom. A "+" after the
                 price means a higher Saturday/Sunday rate applies. --}}
            @php
              $nonRoomPackages = $packages->reject(fn ($p) => \App\Models\Booking::isRoomPackage($p->name));
              $packageGroups = [
                'Swimming' => $nonRoomPackages->filter(fn ($p) => in_array($p->name, \App\Models\Booking::SWIM_PACKAGES, true)),
                'Event'    => $nonRoomPackages->reject(fn ($p) => in_array($p->name, \App\Models\Booking::SWIM_PACKAGES, true)),
              ];
            @endphp
            @foreach($packageGroups as $groupLabel => $groupPackages)
              @if($groupPackages->isNotEmpty())
                <optgroup label="{{ $groupLabel }}" data-occasion="{{ $groupLabel === 'Swimming' ? 'swimming' : 'event' }}">
                  @foreach($groupPackages as $pkg)
                    <option value="{{ $pkg->name }}" data-package="{{ $pkg->name }}" data-max="{{ $pkg->max_guests }}" data-price="{{ $pkg->price }}" data-weekend-price="{{ $pkg->weekend_price }}" data-description="{{ $pkg->description }}" data-start="{{ $pkg->start_time ? \Carbon\Carbon::parse($pkg->start_time)->format('H:i') : '' }}" data-end="{{ $pkg->end_time ? \Carbon\Carbon::parse($pkg->end_time)->format('H:i') : '' }}" {{ old('package') == $pkg->name && !old('room_number') ? 'selected' : '' }}>
                      {{ $pkg->name }} - ₱{{ number_format($pkg->price, 0) }}{{ $pkg->weekend_price !== null ? '+' : '' }} (up to {{ $pkg->max_guests }} guests{{ $pkg->duration ? ', ' . $pkg->duration : '' }})
                    </option>
                  @endforeach
                </optgroup>
              @endif
            @endforeach

            @foreach(\App\Models\Booking::ROOM_GROUPS as $roomPackageName => $roomNumbers)
              @php $pkg = $packages->firstWhere('name', $roomPackageName); @endphp
              @if($pkg)
                <optgroup label="{{ $pkg->name }}" data-occasion="staycation">
                  @foreach($roomNumbers as $roomNum)
                    @php
                      $roomLabel = $pkg->name . ' ' . $roomNum . ' (₱' . number_format($pkg->price, 0) . ', up to ' . $pkg->max_guests . ' guests' . ($pkg->duration ? ', ' . $pkg->duration : '') . ')';
                    @endphp
                    <option value="{{ $pkg->name }}|{{ $roomNum }}"
                      data-package="{{ $pkg->name }}"
                      data-room="{{ $roomNum }}"
                      data-room-type="1"
                      data-max="{{ $pkg->max_guests }}"
                      data-price="{{ $pkg->price }}"
                      data-weekend-price="{{ $pkg->weekend_price }}"
                      data-label="{{ $roomLabel }}"
                      data-description="{{ $pkg->description }}"
                      {{ old('package') == $pkg->name && old('room_number') == $roomNum ? 'selected' : '' }}>
                      {{ $roomLabel }}
                    </option>
                  @endforeach
                </optgroup>
              @endif
            @endforeach
          </select>
          <input type="hidden" name="package" id="packageHiddenInput" value="{{ old('package') }}" />
          <input type="hidden" name="room_number" id="roomNumberHiddenInput" value="{{ old('room_number') }}" />
          <div id="roomUnavailableNotice" class="text-red-500 text-[12px] mt-1" style="display:none;">That package or room is already booked for the selected date — please choose another.</div>
          @error('package') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
          @error('room_number') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>



        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Number of Guests</label>
          <input type="number" name="guest_count" id="guestCountInput" placeholder="How many guests?" min="1" value="{{ old('guest_count') }}" required
            inputmode="numeric" pattern="[0-9]*"
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            onkeydown="return (event.key >= '0' && event.key <= '9') || ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'].includes(event.key)"
            style="-webkit-appearance: none; -moz-appearance: textfield; appearance: textfield;"
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body" />
          @error('guest_count') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        {{-- Packages added with "+ Add to list" (same date). Filled by JS. --}}
        <div id="bookingItemsBox" class="mb-4" style="display: none;">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Your Packages <span class="font-normal text-warm-black/60">— same date</span></label>
          <div id="bookingItemsList" class="flex flex-col gap-2"></div>
        </div>
        <div id="itemsInputs"></div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Total Amount (₱) <span id="weekendRateNote" class="font-normal text-warm-black/60" style="display: none;">— Weekend rate (Sat–Sun)</span></label>
          <input type="number" name="total_amount" id="totalAmountInput" placeholder="0.00" step="0.01" value="{{ old('total_amount') }}" required
            readonly
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body cursor-not-allowed opacity-75" />
          @error('total_amount') <span class="text-red-500 text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-gold-deep mb-2 font-bold">Payment Option</label>
          <select name="payment_option" id="paymentOptionSelect" required
            class="w-full bg-cream border border-gold-deep/25 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
            <option value="" disabled selected hidden>Select a payment option</option>
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
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500 mt-0.5 flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <div class="text-[13.5px] text-warm-black/80 leading-relaxed">
                <strong>Please note:</strong> A {{ \App\Models\Booking::downPaymentRatePercent() }}% down payment is required to confirm your reservation. Our admin will contact you with payment instructions after booking.
            </div>
        </div>

        {{-- Hidden terms checkbox (submitted with form; checked programmatically after modal agreement) --}}
        <input type="checkbox" name="terms" id="termsCheckbox" class="hidden" />

        {{-- Visible Checkbox + Terms Label --}}
        <div class="mb-6 flex items-start gap-3" id="termsAgreementArea">
          {{-- Visible checkbox — clicking it opens the modal instead of checking directly --}}
          <input type="checkbox" id="termsVisibleCheckbox"
            class="mt-1 w-4 h-4 accent-gold-deep cursor-pointer flex-shrink-0" />
          <label for="termsVisibleCheckbox" class="text-[13.5px] text-warm-black/80 leading-relaxed cursor-pointer select-none">
            I have read and agree to the
            <button type="button" id="openTermsModalBtn"
              class="text-gold-deep font-bold underline hover:text-gold-mid bg-transparent border-none cursor-pointer p-0 text-[13.5px] font-body">
              Terms and Conditions
            </button>
            of LorDane's Place.
            <span id="termsViewAgainWrap" class="hidden">
              <button type="button" id="reopenTermsBtn"
                class="text-[11px] text-warm-black/40 hover:text-gold-deep underline bg-transparent border-none cursor-pointer font-body ml-1">
                (view again)
              </button>
            </span>
          </label>
        </div>

        {{-- Confirm Booking Button --}}
        <div class="flex gap-3">
          <button type="button" id="addToListBtn" class="shrink-0 bg-transparent border border-gold-deep text-gold-deep px-4 py-3.5 rounded-md font-bold text-[14px] tracking-[1px] cursor-pointer transition-all duration-300 hover:bg-gold-deep hover:text-white">+ ADD TO LIST</button>
        <button type="submit" id="confirmBookingBtn" class="flex-1 bg-gold-deep text-white border-none py-3.5 rounded-md font-bold text-[16px] tracking-[2px] cursor-pointer transition-all duration-300 hover:bg-gold-mid hover:shadow-lg">CONFIRM BOOKING</button>
        </div>
        <p class="text-[11px] text-warm-black/50 mt-2 italic">Booking more than one package for this date? Use “+ Add to list”, then Confirm Booking.</p>

      </form>
    </div>
  </div>
</section>

{{-- BOOKING SUMMARY — shown on "Confirm Booking"; its own Confirm button submits. Filled by JS. --}}
<div id="bookingSummaryModal" class="fixed inset-0 bg-black/70 z-[9999] items-center justify-center p-4" style="display: none;">
  <div class="bg-off-white border border-gold-deep/25 rounded-2xl max-w-[640px] w-full max-h-[90vh] flex flex-col overflow-hidden">
    <div class="px-6 md:px-8 pt-6 pb-4 border-b border-gold-deep/15">
      <h2 class="font-heading text-[26px] md:text-[30px] font-bold text-gold-deep m-0">Booking Summary</h2>
      <p class="text-[14px] text-warm-black/70 mt-1 mb-0">Please review your booking before confirming.</p>
    </div>
    <div class="px-6 md:px-8 py-5 overflow-y-auto">
      <div class="flex justify-between items-center mb-4 text-[14px]">
        <span class="text-warm-black/60 font-bold uppercase tracking-[1px] text-[11px]">Event Date</span>
        <span id="summaryDate" class="font-bold text-warm-black"></span>
      </div>
      <div id="summaryItems" class="flex flex-col gap-3 mb-5"></div>
      <div class="text-[14px] text-warm-black/80 bg-gold-deep/5 p-4 rounded-lg border border-gold-deep/10 flex flex-col gap-2">
        <div class="flex justify-between"><span>Payment Option:</span><span id="summaryPaymentOption" class="font-bold text-warm-black"></span></div>
        <div class="flex justify-between"><span>Total Booking Amount:</span><span id="summaryModalTotal" class="font-bold text-warm-black"></span></div>
        <div class="flex justify-between"><span id="summaryModalPayLabel"></span><span id="summaryModalPay" class="font-bold text-gold-deep"></span></div>
        <div class="flex justify-between" id="summaryModalRemainingRow"><span>Remaining Balance:</span><span id="summaryModalRemaining" class="font-bold text-warm-black"></span></div>
      </div>
      <p class="text-[12px] text-warm-black/50 italic mt-3 mb-0">Each package is saved as its own booking with its own reference number. This does not process any payment.</p>
    </div>
    <div class="px-6 md:px-8 py-4 border-t border-gold-deep/15 flex gap-3">
      <button type="button" id="summaryBackBtn" class="flex-1 bg-transparent border border-gold-deep/40 text-warm-black py-3 rounded-md font-bold text-[14px] tracking-[1px] cursor-pointer transition-all hover:border-gold-deep">BACK</button>
      <button type="button" id="summaryConfirmBtn" class="flex-1 bg-gold-deep text-white border-none py-3 rounded-md font-bold text-[14px] tracking-[1px] cursor-pointer transition-all hover:bg-gold-mid">CONFIRM BOOKING</button>
    </div>
  </div>
</div>

{{-- ============================================================ --}}
{{-- TERMS & CONDITIONS MODAL --}}
{{-- ============================================================ --}}
<div id="termsModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.65); z-index:10000; align-items:center; justify-content:center; padding:16px;">
  <div style="background:#fff; border-radius:12px; width:100%; max-width:520px; max-height:88vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,0.3);">

    {{-- Header --}}
    <div style="padding:20px 22px 14px; border-bottom:1px solid #e8e0ce; flex-shrink:0;">
      <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
        <div>
          <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#c9a84c; flex-shrink:0;"><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/></svg>
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:700; color:#2c1a0e; margin:0; letter-spacing:0.5px;">Terms and Conditions</h2>
          </div>
          <p style="font-size:12px; color:#8a6a40; margin:0; line-height:1.5;">Please read and agree to the terms and conditions before proceeding with your booking.</p>
        </div>
        <button type="button" id="closeTermsModalBtn" style="background:none; border:none; color:#999; cursor:pointer; line-height:1; padding:2px 4px; flex-shrink:0; display:flex;" title="Close"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
      </div>

      {{-- Scroll hint --}}
      <div id="termsScrollHint" style="margin-top:10px; display:flex; align-items:center; gap:6px; font-size:11px; color:#aaa;">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
        <span>Scroll to the bottom to unlock the agreement</span>
      </div>
      <div id="termsScrollDone" style="display:none; margin-top:10px; font-size:11px; color:#16a34a; font-weight:600; align-items: center; gap: 5px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> You've reached the end — check the box below to agree.
      </div>
    </div>

    {{-- Scrollable Body --}}
    <div id="termsScrollBody" style="overflow-y:auto; flex:1; padding:16px 22px;">

      {{-- 01 --}}
      <div style="margin-bottom:14px;">
        <div style="display:flex; gap:10px; align-items:baseline;">
          <span style="font-size:13px; font-weight:700; color:#c9a84c; min-width:28px; flex-shrink:0;">01.</span>
          <div>
            <div style="font-size:13.5px; font-weight:700; color:#2c1a0e; margin-bottom:5px;">Reservation Fee</div>
            <ul style="list-style:disc; padding-left:16px; margin:0; font-size:13px; color:#444; line-height:1.7; space-y:2px;">
              <li>A <strong>25% downpayment</strong> is required to secure your booking.</li>
              <li>The reservation fee will be deducted from the total bill.</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- 02 --}}
      <div style="margin-bottom:14px;">
        <div style="display:flex; gap:10px; align-items:baseline;">
          <span style="font-size:13px; font-weight:700; color:#c9a84c; min-width:28px; flex-shrink:0;">02.</span>
          <div>
            <div style="font-size:13.5px; font-weight:700; color:#2c1a0e; margin-bottom:5px;">Payment Terms</div>
            <ul style="list-style:disc; padding-left:16px; margin:0; font-size:13px; color:#444; line-height:1.7;">
              <li>Full payment must be settled on or before the event date.</li>
              <li>Accepted payment methods for onsite payment: <strong>Cash, GCash, and Bank Transfer</strong>.</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- 03 --}}
      <div style="margin-bottom:14px;">
        <div style="display:flex; gap:10px; align-items:baseline;">
          <span style="font-size:13px; font-weight:700; color:#c9a84c; min-width:28px; flex-shrink:0;">03.</span>
          <div>
            <div style="font-size:13.5px; font-weight:700; color:#2c1a0e; margin-bottom:5px;">Confirmation of Reservation</div>
            <ul style="list-style:disc; padding-left:16px; margin:0; font-size:13px; color:#444; line-height:1.7;">
              <li>Reservations are considered confirmed only upon receipt of the reservation fee.</li>
              <li>Once the reservation fee is settled or the booking is fully paid and the client has signed the contract, the reservation date is <strong>officially confirmed and cannot be cancelled</strong>.</li>
              <li>Rescheduling requests may be submitted and will be reviewed by the admin.</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- 04 --}}
      <div style="margin-bottom:14px;">
        <div style="display:flex; gap:10px; align-items:baseline;">
          <span style="font-size:13px; font-weight:700; color:#c9a84c; min-width:28px; flex-shrink:0;">04.</span>
          <div>
            <div style="font-size:13.5px; font-weight:700; color:#2c1a0e; margin-bottom:5px;">Cancellation Policy</div>
            <ul style="list-style:disc; padding-left:16px; margin:0; font-size:13px; color:#444; line-height:1.7;">
              <li>Reservation fees are <strong>strictly non-refundable</strong>.</li>
              <li>Cancellations made <strong>30–60 days</strong> before the event may be rebooked/rescheduled once, subject to availability.</li>
              <li>Same-day booking cancellations that remain unpaid will be automatically cancelled.</li>
              <li>Failure to visit the venue during your preferred visit schedule will result in <strong>automatic cancellation</strong> of the booking.</li>
              <li>No-shows on the reserved event date will result in <strong>forfeiture of the reservation fee</strong>.</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- 05 --}}
      <div style="margin-bottom:14px;">
        <div style="display:flex; gap:10px; align-items:baseline;">
          <span style="font-size:13px; font-weight:700; color:#c9a84c; min-width:28px; flex-shrink:0;">05.</span>
          <div>
            <div style="font-size:13.5px; font-weight:700; color:#2c1a0e; margin-bottom:5px;">Rebooking / Rescheduling</div>
            <ul style="list-style:disc; padding-left:16px; margin:0; font-size:13px; color:#444; line-height:1.7;">
              <li>Requests for event rebooking must be made at least <strong>30 days</strong> prior to the reserved date for full-package events. For room and amenities rentals only, rescheduling requests must be made before the reserved date.</li>
              <li>Approval of rebooking depends on schedule availability and if the conditions for rescheduling have been met.</li>
              <li>The client's <strong>first successful rescheduling request is free of charge</strong>. Any additional rescheduling requests may be subject to a penalty fee.</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- 06 --}}
      <div style="margin-bottom:14px;">
        <div style="display:flex; gap:10px; align-items:baseline;">
          <span style="font-size:13px; font-weight:700; color:#c9a84c; min-width:28px; flex-shrink:0;">06.</span>
          <div>
            <div style="font-size:13.5px; font-weight:700; color:#2c1a0e; margin-bottom:5px;">Changes in Reservation</div>
            <ul style="list-style:disc; padding-left:16px; margin:0; font-size:13px; color:#444; line-height:1.7;">
              <li>Any changes in the number of guests, date, or other details must be communicated on the day of your preferred visit schedule or at least <strong>14 days</strong> before the event.</li>
              <li>Additional charges may apply.</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- 07 --}}
      <div style="margin-bottom:6px;">
        <div style="display:flex; gap:10px; align-items:baseline;">
          <span style="font-size:13px; font-weight:700; color:#c9a84c; min-width:28px; flex-shrink:0;">07.</span>
          <div>
            <div style="font-size:13.5px; font-weight:700; color:#2c1a0e; margin-bottom:5px;">Client Responsibility</div>
            <ul style="list-style:disc; padding-left:16px; margin:0; font-size:13px; color:#444; line-height:1.7;">
              <li>The client is responsible for providing accurate information.</li>
              <li>Damages incurred during the event/service shall be charged to the client.</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- Bottom sentinel --}}
      <div id="termsBottomSentinel" style="height:4px;"></div>
    </div>

    {{-- Footer --}}
    <div style="padding:14px 22px 18px; border-top:1px solid #e8e0ce; flex-shrink:0; background:#fafafa; border-radius:0 0 12px 12px;">
      <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
        <input type="checkbox" id="termsAgreeCheckbox" disabled
          style="width:15px; height:15px; accent-color:#c9a84c; cursor:not-allowed; flex-shrink:0;" />
        <label for="termsAgreeCheckbox" id="termsAgreeLabel"
          style="font-size:13px; color:#aaa; cursor:not-allowed; user-select:none; line-height:1.4;">
          I Understand and Agree
        </label>
      </div>
      <button type="button" id="termsConfirmBtn" disabled
        style="width:100%; background:#c9a84c; color:#fff; border:none; padding:11px 20px; border-radius:7px; font-weight:700; font-size:13.5px; letter-spacing:1px; cursor:not-allowed; opacity:0.45; transition:opacity 0.2s, background 0.2s; font-family:'Jost',sans-serif;">
        Proceed to Booking
      </button>
    </div>

  </div>
</div>

{{-- ============================================================ --}}
{{-- TERMS MODAL JAVASCRIPT --}}
{{-- ============================================================ --}}

<script>
(function() {
  const termsModal        = document.getElementById('termsModal');
  const termsScrollBody   = document.getElementById('termsScrollBody');
  const termsScrollHint   = document.getElementById('termsScrollHint');
  const termsScrollDone   = document.getElementById('termsScrollDone');
  const termsAgreeChk     = document.getElementById('termsAgreeCheckbox');
  const termsAgreeLabel   = document.getElementById('termsAgreeLabel');
  const termsConfirmBtn   = document.getElementById('termsConfirmBtn');
  const termsCheckbox     = document.getElementById('termsCheckbox');        // hidden, submitted
  const termsVisibleChk   = document.getElementById('termsVisibleCheckbox'); // visible checkbox
  const termsViewAgainWrap = document.getElementById('termsViewAgainWrap');
  let   termsScrolledToBottom = false;
  let   termsAgreed = false; // track if user has completed the modal flow

  // ── Open / close helpers ──────────────────────────────────────
  function openTermsModal() {
    // Always reset scroll state when opening fresh
    termsScrolledToBottom = false;
    termsScrollHint.style.display = 'flex';
    termsScrollDone.style.display = 'none';
    termsAgreeChk.disabled = true;
    termsAgreeChk.checked  = false;
    termsAgreeChk.style.cursor = 'not-allowed';
    termsAgreeLabel.style.color = '#aaa';
    termsAgreeLabel.style.cursor = 'not-allowed';
    termsConfirmBtn.disabled = true;
    termsConfirmBtn.style.opacity = '0.45';
    termsConfirmBtn.style.cursor = 'not-allowed';

    // Reset scroll to top so user must scroll fresh
    termsScrollBody.scrollTop = 0;

    termsModal.style.removeProperty('display');
    termsModal.classList.remove('hidden');
    termsModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // After a tick, check if no scroll needed (short content)
    setTimeout(checkScrollPosition, 150);
  }

  function closeTermsModal() {
    termsModal.classList.add('hidden');
    termsModal.style.display = 'none';
    document.body.style.overflow = '';

    // If user closed without agreeing, uncheck the visible checkbox
    if (!termsAgreed) {
      termsVisibleChk.checked = false;
    }
  }

  // ── Intercept visible checkbox click → open modal ─────────────
  termsVisibleChk.addEventListener('click', function(e) {
    if (termsAgreed) {
      // Already agreed — allow unchecking which resets agreement
      if (!this.checked) {
        termsAgreed = false;
        termsCheckbox.checked = false;
        termsViewAgainWrap.classList.add('hidden');
      }
      return;
    }
    // Prevent the checkbox from checking itself — modal must be completed first
    e.preventDefault();
    openTermsModal();
  });

  // ── Scroll detection ─────────────────────────────────────────
  function checkScrollPosition() {
    if (termsScrolledToBottom) return;
    const el = termsScrollBody;
    // Within 10px of bottom counts as "reached end"
    if (el.scrollHeight - el.scrollTop - el.clientHeight <= 10) {
      termsScrolledToBottom = true;
      termsScrollHint.style.display = 'none';
      termsScrollDone.style.display = 'flex';
      termsAgreeChk.disabled = false;
      termsAgreeChk.style.cursor = 'pointer';
      termsAgreeLabel.style.color = '#2c1a0e';
      termsAgreeLabel.style.cursor = 'pointer';
    }
  }

  termsScrollBody.addEventListener('scroll', checkScrollPosition);

  // ── Modal agree checkbox toggles the Confirm button ──────────
  termsAgreeChk.addEventListener('change', function() {
    if (this.checked) {
      termsConfirmBtn.disabled = false;
      termsConfirmBtn.style.opacity = '1';
      termsConfirmBtn.style.cursor = 'pointer';
    } else {
      termsConfirmBtn.disabled = true;
      termsConfirmBtn.style.opacity = '0.45';
      termsConfirmBtn.style.cursor = 'not-allowed';
    }
  });

  // ── Confirm & Proceed: check both inputs, close modal ─────────
  termsConfirmBtn.addEventListener('click', function() {
    if (!termsAgreeChk.checked) return;

    termsAgreed = true;

    // Auto-check the visible checkbox and the hidden submitted input
    termsVisibleChk.checked = true;
    termsCheckbox.checked   = true;

    // Show "view again" link
    termsViewAgainWrap.classList.remove('hidden');

    closeTermsModal();
  });

  // ── Wire open / close buttons ─────────────────────────────────
  document.getElementById('openTermsModalBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    openTermsModal();
  });
  document.getElementById('closeTermsModalBtn').addEventListener('click', closeTermsModal);
  document.getElementById('reopenTermsBtn')?.addEventListener('click', function(e) {
    e.stopPropagation();
    openTermsModal();
  });

  // Close on backdrop click
  termsModal.addEventListener('click', function(e) {
    if (e.target === termsModal) closeTermsModal();
  });

  // ── Form submission guard ─────────────────────────────────────
  document.getElementById('bookingForm').addEventListener('submit', function(e) {
    if (!termsCheckbox.checked) {
      e.preventDefault();
      // Shake the checkbox area to draw attention
      const area = document.getElementById('termsAgreementArea');
      area.style.transition = 'transform 0.08s ease';
      let count = 0;
      const shake = setInterval(() => {
        area.style.transform = count % 2 === 0 ? 'translateX(6px)' : 'translateX(-6px)';
        count++;
        if (count >= 6) {
          clearInterval(shake);
          area.style.transform = '';
        }
      }, 80);
      openTermsModal();
      return;
    }

    // Show the Booking Summary first; its own Confirm button re-submits
    // the form with bookingSummaryConfirmed set.
    if (!window.bookingSummaryConfirmed) {
      e.preventDefault();
      if (window.openBookingSummary) window.openBookingSummary();
      return;
    }

    // Lock the button once the booking is actually being sent, so a
    // double-click (or a second click on a slow connection) can't create a
    // duplicate booking. A validation error reloads the page, which
    // restores the button.
    const btn = document.getElementById('confirmBookingBtn');
    if (btn.disabled) {
      e.preventDefault();
      return;
    }
    btn.disabled = true;
    btn.textContent = 'SUBMITTING...';
    btn.classList.add('opacity-70', 'cursor-not-allowed');
  });
})();
</script>

<!-- SUCCESS MODAL + VISIT SCHEDULING -->


<div class="modal-overlay hidden fixed inset-0 bg-black/70 z-[9999] items-center justify-center" id="successModal">
  <div class="modal-box bg-off-white border border-gold-deep/25 rounded-2xl p-8 md:p-10 max-w-[650px] w-[95%] overflow-y-auto max-h-[90vh]">
    <div class="text-center mb-6">
        <div class="mb-3 flex justify-center">
          <div class="w-16 h-16 rounded-full bg-gold-deep flex items-center justify-center">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
        </div>
        <h2 class="font-heading text-[26px] md:text-[32px] font-bold text-gold-deep mb-2">Booking Submitted!</h2>
        @if(session('booking_number'))
          <div class="mb-4">
              <p class="text-[11px] tracking-[1px] text-warm-black/50 font-bold uppercase mb-1">{{ str_contains(session('booking_number'), ',') ? 'Booking References' : 'Booking Reference' }}</p>
              <p class="text-[18px] font-bold text-gold-deep">#{{ str_replace(', ', ', #', session('booking_number')) }}</p>
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
            @foreach((array) session('new_booking_ids', []) as $groupBookingId)
              <input type="hidden" name="booking_ids[]" value="{{ $groupBookingId }}">
            @endforeach
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
                        <option value="" disabled selected hidden>Select time</option>
                        @for($i = 8; $i <= 17; $i++)
                            @foreach(['00', '30'] as $min)
                                @if($i == 17 && $min == '30') @continue @endif
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
    <button type="button" onclick="document.getElementById('visitSuccessModal').style.display='none'" class="absolute top-4 right-4 text-warm-black/40 hover:text-warm-black bg-transparent border-none cursor-pointer leading-none transition-colors flex items-center justify-center">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="mb-4 flex justify-center">
      <div class="w-16 h-16 rounded-full bg-gold-deep flex items-center justify-center">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
    </div>
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
  const roomAvailabilityUrl = dataEl.getAttribute('data-room-availability-url');
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
  const packageSelectEl = document.getElementById('packageSelect');
  const packageHiddenInput = document.getElementById('packageHiddenInput');
  const roomNumberHiddenInput = document.getElementById('roomNumberHiddenInput');
  const roomUnavailableNotice = document.getElementById('roomUnavailableNotice');
  let selectedPackage = packageHiddenInput.value;

  // Last day of a Swimming/Staycation date range (inclusive); null = one day.
  let selectedEndDate = null;

  // Packages added with "+ Add to list" for the selected date(s). Each entry
  // keeps a reference to its <option> for label/price/description.
  const bookingItems = [];

  function addDays(dateStr, n) {
    const d = new Date(dateStr + 'T00:00:00');
    d.setDate(d.getDate() + n);
    return formatDate(d);
  }

  // Every booked day: selectedDate .. selectedEndDate (inclusive).
  function selectedDates() {
    if (!selectedDate) return [];
    const dates = [selectedDate];
    if (selectedEndDate) {
      for (let d = addDays(selectedDate, 1); d <= selectedEndDate; d = addDays(d, 1)) dates.push(d);
    }
    return dates;
  }

  function isWeekend(dateStr) {
    const day = new Date(dateStr + 'T00:00:00').getDay();
    return day === 0 || day === 6;
  }

  function isWeekendDate() {
    return selectedDates().some(isWeekend);
  }

  // One day's price: the weekend rate on Saturday/Sunday when the package has one.
  function dayPrice(option, dateStr) {
    const useWeekend = dateStr && isWeekend(dateStr) && option.dataset.weekendPrice;
    return parseFloat(useWeekend ? option.dataset.weekendPrice : option.dataset.price) || 0;
  }

  // A package's price for the whole selection (each day paid separately).
  function optionPrice(option) {
    if (!option || !option.dataset.price) return 0;
    const dates = selectedDates();
    if (!dates.length) return dayPrice(option, null);
    return dates.reduce(function (sum, d) { return sum + dayPrice(option, d); }, 0);
  }

  // Ranges are for Swimming/Staycation only — Events are a single day.
  function rangeAllowed() {
    const occasion = document.getElementById('occasionSelect');
    return !!occasion && (occasion.value === 'swimming' || occasion.value === 'staycation')
      && bookingItems.every(function (item) { return item.occasion !== 'event'; });
  }

  function selectionLabel() {
    const dates = selectedDates();
    if (!dates.length) return 'Please select a date from the calendar';
    if (dates.length === 1) return 'Selected: ' + formatDisplay(dates[0]);
    const opts = { weekday: 'short', month: 'short', day: 'numeric' };
    const from = new Date(dates[0] + 'T00:00:00').toLocaleDateString('en-PH', opts);
    const to = new Date(dates[dates.length - 1] + 'T00:00:00').toLocaleDateString('en-PH', Object.assign({ year: 'numeric' }, opts));
    return 'Selected: ' + from + ' – ' + to + ' (' + dates.length + ' days)';
  }

  function syncDateInputs() {
    document.getElementById('eventDateInput').value = selectedDate || '';
    document.getElementById('eventEndDateInput').value = selectedEndDate || '';
    document.getElementById('selectedDateText').textContent = selectionLabel();
  }

  const oldDate = dataEl.getAttribute('data-old-date');
  if (oldDate) {
    selectedDate = oldDate;
    const oldEnd = dataEl.getAttribute('data-old-end-date');
    if (oldEnd && oldEnd > oldDate) selectedEndDate = oldEnd;
    syncDateInputs();
  }

  // Total preview = every listed package + the package currently selected
  // (which Confirm Booking also includes). Display only — the server
  // computes the real totals from the package records.
  function applyPackagePrice() {
    const option = packageSelectEl.options[packageSelectEl.selectedIndex];
    const pending = option && option.dataset.price ? option : null;
    const options = bookingItems.map(function (item) { return item.option; }).concat(pending ? [pending] : []);
    const total = options.reduce(function (sum, o) { return sum + optionPrice(o); }, 0);

    document.getElementById('totalAmountInput').value = total > 0 ? total.toFixed(2) : '';
    document.getElementById('weekendRateNote').style.display =
      isWeekendDate() && options.some(function (o) { return o.dataset.weekendPrice; }) ? '' : 'none';
    renderBookingItems();
    updateDownPayment();
  }

  function itemTitle(item) {
    return item.option.dataset.room ? item.packageName + ' ' + item.room : item.packageName;
  }

  function itemSubtitle(item) {
    const type = item.occasion === 'event'
      ? 'Event · ' + (item.eventType === 'Others' ? item.eventTypeOther : item.eventType)
      : occasionLabels[item.occasion];
    const days = selectedDates().length;
    return type + ' · ' + item.guests + ' guest' + (item.guests === 1 ? '' : 's') + (days > 1 ? ' · ' + days + ' days' : '');
  }

  function renderBookingItems() {
    const box = document.getElementById('bookingItemsBox');
    const list = document.getElementById('bookingItemsList');
    list.innerHTML = '';

    bookingItems.forEach(function (item, index) {
      const row = document.createElement('div');
      row.className = 'flex items-center justify-between gap-3 bg-cream border border-gold-deep/20 rounded-md px-3.5 py-2.5';

      const info = document.createElement('div');
      const title = document.createElement('div');
      title.className = 'text-[14px] font-bold text-warm-black';
      title.textContent = itemTitle(item);
      const sub = document.createElement('div');
      sub.className = 'text-[12px] text-warm-black/60';
      sub.textContent = itemSubtitle(item);
      info.append(title, sub);

      const right = document.createElement('div');
      right.className = 'flex items-center gap-3 shrink-0';
      const price = document.createElement('span');
      price.className = 'text-[14px] font-bold text-gold-deep';
      price.textContent = formatPeso(optionPrice(item.option));
      const remove = document.createElement('button');
      remove.type = 'button';
      remove.title = 'Remove from list';
      remove.className = 'w-7 h-7 rounded-full border border-red-400/50 text-red-500 bg-transparent cursor-pointer hover:bg-red-500 hover:text-white transition-colors';
      remove.textContent = '✕';
      remove.addEventListener('click', function () {
        bookingItems.splice(index, 1);
        applyPackagePrice();
        checkSelectedDateForOccasion();
      });
      right.append(price, remove);

      row.append(info, right);
      list.appendChild(row);
    });

    box.style.display = bookingItems.length ? '' : 'none';

    // With packages already in the list, the selection fields may be left
    // empty — they're only required when nothing has been added yet.
    const hasList = bookingItems.length > 0;
    document.getElementById('occasionSelect').required = !hasList;
    packageSelectEl.required = !hasList;
    document.getElementById('guestCountInput').required = !hasList;
    eventTypeSelect.required = !hasList;
  }

  function syncPackageFromSelect() {
    const selectedOption = packageSelectEl.options[packageSelectEl.selectedIndex];
    if (!selectedOption || !selectedOption.dataset.package) {
      packageHiddenInput.value = '';
      roomNumberHiddenInput.value = '';
      return;
    }

    packageHiddenInput.value = selectedOption.dataset.package;
    roomNumberHiddenInput.value = selectedOption.dataset.room || '';
    selectedPackage = selectedOption.dataset.package;

    if (selectedOption.dataset.max) {
      document.getElementById('guestCountInput').max = selectedOption.dataset.max;
      document.getElementById('guestCountInput').placeholder = "Max: " + selectedOption.dataset.max;

      // Auto-fill total amount (weekend rate if the chosen date is Sat/Sun)
      applyPackagePrice();
    } else {
      document.getElementById('guestCountInput').removeAttribute('max');
      document.getElementById('guestCountInput').placeholder = "How many guests?";
    }
  }

  packageSelectEl.addEventListener('change', function() {
    roomUnavailableNotice.style.display = 'none';
    syncPackageFromSelect();
    if (selectedDate) refreshRoomAvailability();
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

  // Dropdowns still on their "Select …" prompt are styled as placeholders.
  // User changes are caught by the document listener (it runs after each
  // field's own handler); programmatic resets call this directly.
  function syncSelectPlaceholders() {
    document.querySelectorAll('select').forEach(function (s) { s.classList.toggle('is-placeholder', s.value === ''); });
  }
  document.addEventListener('change', syncSelectPlaceholders);

  // ── Occasion: Swimming / Staycation / Event ─────────────────
  // Only the chosen occasion's packages are offered, and Event Type is only
  // used for Events (disabled for Swimming/Staycation). The server enforces
  // the same package/occasion pairing.
  const occasionSelect = document.getElementById('occasionSelect');
  const eventTypeHint = document.getElementById('eventTypeHint');
  const occasionLabels = { swimming: 'Swimming', staycation: 'Staycation', event: 'Event' };

  function packageOccasion(option) {
    return option && option.parentElement && option.parentElement.tagName === 'OPTGROUP'
      ? option.parentElement.dataset.occasion
      : null;
  }

  function applyOccasion() {
    const occasion = occasionSelect.value;
    const isEvent = occasion === 'event';

    eventTypeSelect.disabled = !isEvent;
    if (!isEvent && eventTypeSelect.value) {
      eventTypeSelect.selectedIndex = 0;
      toggleOtherEventType();
    }
    eventTypeHint.textContent = !occasion
      ? '— select an occasion first'
      : (isEvent ? '' : '— not needed for ' + occasionLabels[occasion]);
    eventTypeHint.style.display = isEvent ? 'none' : '';

    packageSelectEl.disabled = !occasion;
    packageSelectEl.querySelectorAll('optgroup').forEach(function (group) {
      const show = group.dataset.occasion === occasion;
      group.hidden = !show;
      group.disabled = !show;
    });

    // A package from another occasion can't stay selected.
    if (packageOccasion(packageSelectEl.options[packageSelectEl.selectedIndex]) !== occasion) {
      packageSelectEl.selectedIndex = 0;
      roomUnavailableNotice.style.display = 'none';
      syncPackageFromSelect();
      applyPackagePrice();
    }
  }

  occasionSelect.addEventListener('change', function () {
    applyOccasion();
    checkSelectedDateForOccasion();
  });

  // ── "+ Add to list": several packages for the same date ─────
  const guestCountInput = document.getElementById('guestCountInput');

  // The package currently picked in the form, as a list item. Returns null
  // when no package is picked, or { error } when it is incomplete.
  function pendingSelection() {
    const option = packageSelectEl.options[packageSelectEl.selectedIndex];
    if (!option || !option.dataset.package) return null;

    const name = option.dataset.room ? option.dataset.package + ' ' + option.dataset.room : option.dataset.package;
    const occasion = occasionSelect.value;
    let eventType = '';
    let eventTypeOther = '';

    if (occasion === 'event') {
      eventType = eventTypeSelect.value;
      if (!eventType) return { error: 'Please select an event type for ' + name + '.' };
      if (eventType === 'Others') {
        eventTypeOther = otherInput.value.trim();
        if (!eventTypeOther) return { error: 'Please specify the event type for ' + name + '.' };
      }
    }

    const guests = parseInt(guestCountInput.value, 10);
    const max = parseInt(option.dataset.max, 10);
    if (!guests || guests < 1) return { error: 'Please enter the number of guests for ' + name + '.' };
    if (max && guests > max) return { error: 'Guest count exceeds the maximum allowed for ' + name + ' (Max: ' + max + ').' };

    return { item: {
      occasion: occasion,
      eventType: eventType,
      eventTypeOther: eventTypeOther,
      packageName: option.dataset.package,
      room: option.dataset.room || '',
      guests: guests,
      option: option,
    } };
  }

  // Same rules the server enforces: no duplicates, one Event per day.
  function cannotAdd(item, list) {
    if (item.occasion !== 'staycation' && selectedDates().some(function (d) { return approvedDates.includes(d); })) {
      return EVENT_DAY_ROOMS_ONLY;
    }
    if (item.occasion === 'event' && selectedEndDate) {
      return 'Event packages are booked for a single date. Pick one day for the Event, or book it separately.';
    }
    if (list.some(function (i) { return i.packageName === item.packageName && i.room === item.room; })) {
      return itemTitle(item) + ' is already in your list.';
    }
    if (item.occasion === 'event' && list.some(function (i) { return i.occasion === 'event'; })) {
      return 'Only one Event package can be booked per day.';
    }
    return null;
  }

  function resetSelection() {
    setTimeout(syncSelectPlaceholders);
    packageSelectEl.selectedIndex = 0;
    roomUnavailableNotice.style.display = 'none';
    syncPackageFromSelect();
    guestCountInput.value = '';
    if (occasionSelect.value === 'event') {
      eventTypeSelect.selectedIndex = 0;
      toggleOtherEventType();
    }
  }

  document.getElementById('addToListBtn').addEventListener('click', function () {
    if (!selectedDate) { alert('Please select an event date first.'); return; }

    const picked = pendingSelection();
    if (!picked) { alert('Please select a package to add.'); return; }
    if (picked.error) { alert(picked.error); return; }

    const problem = cannotAdd(picked.item, bookingItems);
    if (problem) { alert(problem); return; }

    bookingItems.push(picked.item);
    resetSelection();
    applyPackagePrice();
  });

  // ── Booking Summary (opened by the form's submit guard) ─────
  const summaryModal = document.getElementById('bookingSummaryModal');

  function addHiddenInput(container, name, value) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    container.appendChild(input);
  }

  window.openBookingSummary = function () {
    if (!selectedDate) { alert('Please select an event date.'); return; }

    // Confirm Booking also books the package currently selected in the form.
    const items = bookingItems.slice();
    const picked = pendingSelection();
    if (picked && picked.error) { alert(picked.error); return; }
    if (picked) {
      const problem = cannotAdd(picked.item, items);
      if (problem) { alert(problem); return; }
      items.push(picked.item);
    }
    if (!items.length) { alert('Please select at least one package.'); return; }

    // What actually gets submitted: one items[] entry per package.
    const inputs = document.getElementById('itemsInputs');
    inputs.innerHTML = '';
    items.forEach(function (item, i) {
      addHiddenInput(inputs, 'items[' + i + '][occasion]', item.occasion);
      addHiddenInput(inputs, 'items[' + i + '][event_type]', item.eventType);
      addHiddenInput(inputs, 'items[' + i + '][event_type_other]', item.eventTypeOther);
      addHiddenInput(inputs, 'items[' + i + '][package]', item.packageName);
      addHiddenInput(inputs, 'items[' + i + '][room_number]', item.room);
      addHiddenInput(inputs, 'items[' + i + '][guest_count]', item.guests);
    });

    // Summary content (textContent only — package text is admin-entered).
    document.getElementById('summaryDate').textContent = selectionLabel().replace('Selected: ', '');
    const list = document.getElementById('summaryItems');
    list.innerHTML = '';
    items.forEach(function (item) {
      const card = document.createElement('div');
      card.className = 'border border-gold-deep/20 rounded-lg p-4 bg-cream';

      const head = document.createElement('div');
      head.className = 'flex justify-between items-start gap-3';
      const title = document.createElement('div');
      title.className = 'text-[15px] font-bold text-warm-black';
      title.textContent = itemTitle(item);
      const price = document.createElement('div');
      price.className = 'text-[15px] font-bold text-gold-deep shrink-0';
      price.textContent = formatPeso(optionPrice(item.option)) + (isWeekendDate() && item.option.dataset.weekendPrice ? (selectedDates().length > 1 ? ' (incl. weekend rate)' : ' (weekend)') : '');
      head.append(title, price);

      const sub = document.createElement('div');
      sub.className = 'text-[13px] text-warm-black/60 mt-0.5';
      sub.textContent = itemSubtitle(item);
      card.append(head, sub);

      if (item.option.dataset.description) {
        const desc = document.createElement('div');
        desc.className = 'text-[13px] text-warm-black/80 mt-2 pt-2 border-t border-gold-deep/10 leading-relaxed';
        desc.style.whiteSpace = 'pre-wrap';
        desc.textContent = item.option.dataset.description;
        card.appendChild(desc);
      }
      list.appendChild(card);
    });

    const total = items.reduce(function (sum, item) { return sum + optionPrice(item.option); }, 0);
    const isFull = paymentOptionSelect.value === 'full_payment';
    const toPay = isFull ? total : Math.round(total * DOWN_PAYMENT_RATE * 100) / 100;
    document.getElementById('summaryPaymentOption').textContent = paymentOptionSelect.options[paymentOptionSelect.selectedIndex].textContent;
    document.getElementById('summaryModalTotal').textContent = formatPeso(total);
    document.getElementById('summaryModalPayLabel').textContent = isFull ? 'Amount to Pay:' : DOWN_PAYMENT_PERCENT + '% Downpayment Amount:';
    document.getElementById('summaryModalPay').textContent = formatPeso(toPay);
    document.getElementById('summaryModalRemaining').textContent = formatPeso(Math.round((total - toPay) * 100) / 100);
    document.getElementById('summaryModalRemainingRow').style.display = isFull ? 'none' : 'flex';

    summaryModal.style.display = 'flex';
  };

  function closeBookingSummary() {
    summaryModal.style.display = 'none';
    document.getElementById('itemsInputs').innerHTML = '';
  }

  document.getElementById('summaryBackBtn').addEventListener('click', closeBookingSummary);
  summaryModal.addEventListener('click', function (e) { if (e.target === summaryModal) closeBookingSummary(); });

  const summaryConfirmBtn = document.getElementById('summaryConfirmBtn');
  summaryConfirmBtn.addEventListener('click', function () {
    summaryConfirmBtn.disabled = true;
    summaryConfirmBtn.textContent = 'SUBMITTING...';
    window.bookingSummaryConfirmed = true;
    document.getElementById('bookingForm').requestSubmit(document.getElementById('confirmBookingBtn'));
  });

  // If the browser blocks the submit (a required field is empty), go back to
  // the form so the customer can see the message, and reset the summary.
  document.getElementById('bookingForm').addEventListener('invalid', function () {
    if (!window.bookingSummaryConfirmed) return;
    window.bookingSummaryConfirmed = false;
    summaryConfirmBtn.disabled = false;
    summaryConfirmBtn.textContent = 'CONFIRM BOOKING';
    closeBookingSummary();
  }, true);

  const urlParams = new URLSearchParams(window.location.search);
  const packageFromUrl = urlParams.get('package');
  let oldPackage = selectedPackage;
  if (packageFromUrl) {
    // Room-type packages no longer have a plain option matching the package
    // name alone (e.g. "Family Room") — fall back to the first room option
    // under that package's optgroup so links like Discover's "BOOK NOW"
    // (?package=Family Room) still land on a selectable option.
    const directMatch = Array.from(packageSelectEl.options).find(opt => opt.value === packageFromUrl);
    const roomMatch = Array.from(packageSelectEl.options).find(opt => opt.dataset.package === packageFromUrl);
    const match = directMatch || roomMatch;
    if (match) {
      packageSelectEl.value = match.value;
      oldPackage = packageFromUrl;
      // Pick the occasion that package belongs to (e.g. Discover's "Book Now").
      if (packageOccasion(match)) occasionSelect.value = packageOccasion(match);
    }
  }
  if (oldPackage) {
    packageSelectEl.dispatchEvent(new Event('change'));
  }
  // Set up Event Type / package filtering for the current occasion (also
  // restores the state after a validation-failure reload).
  applyOccasion();
  syncSelectPlaceholders();

  // After a validation-failure reload, rebuild the submitted package list.
  // The last entry there was the in-form selection, so clear the form's
  // own fields to avoid showing it twice.
  const oldItems = JSON.parse(dataEl.getAttribute('data-old-items') || '[]');
  if (Array.isArray(oldItems) && oldItems.length) {
    oldItems.forEach(function (old) {
      const option = Array.from(packageSelectEl.options).find(function (o) {
        return o.dataset.package === old.package && (o.dataset.room || '') === (old.room_number || '');
      });
      if (!option) return;
      bookingItems.push({
        occasion: old.occasion,
        eventType: old.event_type || '',
        eventTypeOther: old.event_type_other || '',
        packageName: old.package,
        room: old.room_number || '',
        guests: parseInt(old.guest_count, 10) || 0,
        option: option,
      });
    });
    resetSelection();
    applyPackagePrice();
  }

  function formatDisplay(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-PH', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  }

  function formatDate(d) {
    return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
  }

  // A day that already has an approved event is still open for rooms: true
  // while the customer is booking Staycation only (current occasion and
  // everything in their list). Swimming/Event keep the one-event-per-day rule.
  function roomsOnly() {
    const occasion = document.getElementById('occasionSelect');
    return !!occasion && occasion.value === 'staycation'
      && bookingItems.every(function (item) { return item.occasion === 'staycation'; });
  }

  // Unavailable = admin-blocked dates (always) + days with an approved event
  // (not when booking rooms only).
  function getUnavailableDates() {
    const blockedDateStrings = Object.keys(blockedDates);
    return roomsOnly() ? blockedDateStrings : [...blockedDateStrings, ...approvedDates];
  }

  const EVENT_DAY_ROOMS_ONLY = 'This date already has an event — only Staycation (rooms) can be booked on this day.';

  function showDateMessage(message) {
    document.getElementById('blockedDateReason').textContent = message;
    document.getElementById('blockedDateAlert').classList.remove('hidden');
  }

  function clearSelection(message) {
    selectedDate = null;
    selectedEndDate = null;
    syncDateInputs();
    if (message) showDateMessage(message);
    applyPackagePrice();
    renderCalendar();
  }

  // Called when the occasion or the package list changes: a range only
  // stays for Swimming/Staycation, and an event day picked for Staycation
  // can't stay selected once Swimming/Event is involved.
  function checkSelectedDateForOccasion() {
    if (selectedEndDate && !rangeAllowed()) {
      selectedEndDate = null;
      syncDateInputs();
      showDateMessage('Event packages are booked for a single date, so your selection is now just ' + formatDisplay(selectedDate) + '.');
      applyPackagePrice();
      refreshRoomAvailability();
    }
    if (selectedDates().some(function (d) { return approvedDates.includes(d); }) && !roomsOnly()) {
      clearSelection(EVENT_DAY_ROOMS_ONLY);
      return;
    }
    renderCalendar();
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
    const chosen = selectedDates();

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
        if (chosen.includes(dateStr)) {
          div.classList.add(dateStr === selectedDate || dateStr === selectedEndDate ? 'selected' : 'in-range');
        }
        div.addEventListener('click', () => selectDate(dateStr, div));
      }
      grid.appendChild(div);
    }

    const hint = document.getElementById('rangeHint');
    if (hint) hint.style.display = rangeAllowed() ? '' : 'none';
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
    clearSelection(reason);
  }

  // Re-check every room's availability for ALL selected days and annotate/
  // disable the matching <option>s — a room must be free on every day of a
  // range. Called on date change AND package change.
  function refreshRoomAvailability() {
    const dates = selectedDates();
    if (!dates.length) return;

    Promise.all(dates.map(function (d) {
      return fetch(roomAvailabilityUrl + '?date=' + d).then(function (res) { return res.json(); });
    }))
      .then(results => {
        let selectedBecameUnavailable = false;

        Array.from(packageSelectEl.options).forEach(opt => {
          if (!opt.dataset.package) return;

          // Rooms: free per room. Other packages: one booking per package per
          // day (the endpoint's "_packages" map) — the first booking holds it.
          const available = results.every(function (data) {
            if (!opt.dataset.roomType) {
              return !data._packages || data._packages[opt.dataset.package] !== false;
            }
            const pkgAvailability = data[opt.dataset.package] || {};
            return pkgAvailability[opt.dataset.room] !== false; // default true if missing
          });

          if (!opt.dataset.roomType) {
            // Non-room options keep their price label; only mark them when taken.
            if (!opt.dataset.label) opt.dataset.label = opt.textContent.trim();
            opt.disabled = !available;
            opt.textContent = opt.dataset.label + (available ? '' : (dates.length > 1 ? ' — BOOKED ON SOME DAYS' : ' — BOOKED'));
            if (opt.selected && !available) selectedBecameUnavailable = true;
            return;
          }

          opt.disabled = !available;
          opt.textContent = opt.dataset.label + (available ? ' — AVAILABLE' : (dates.length > 1 ? ' — BOOKED ON SOME DAYS' : ' — BOOKED'));

          if (opt.selected && !available) selectedBecameUnavailable = true;
        });

        if (selectedBecameUnavailable) {
          packageSelectEl.value = '';
          packageHiddenInput.value = '';
          roomNumberHiddenInput.value = '';
          roomUnavailableNotice.style.display = 'block';
          syncSelectPlaceholders();
          applyPackagePrice();
        }
      })
      .catch(() => {
        // Silently fail — backend validation at submit time still catches it
      });
  }

  // Annotate rooms immediately if a date/package survived a validation-failure reload
  if (selectedDate) refreshRoomAvailability();

  function selectDate(dateStr, el) {
    // Hide blocked alert if it was showing
    document.getElementById('blockedDateAlert').classList.add('hidden');

    // A second click on a later day ends a range (Swimming/Staycation only).
    // Every day in between must be available.
    // A range that would cross an unavailable day isn't allowed — the click
    // then starts a new selection at that day instead (with a message).
    let rangeProblem = null;
    if (rangeAllowed() && selectedDate && !selectedEndDate && dateStr > selectedDate) {
      const unavailables = getUnavailableDates();
      for (let d = addDays(selectedDate, 1); d < dateStr; d = addDays(d, 1)) {
        if (unavailables.includes(d)) {
          rangeProblem = formatDisplay(d) + ' is not available, so a range can\'t include it. Your selection now starts on ' + formatDisplay(dateStr) + '.';
          break;
        }
      }
      if (!rangeProblem) {
        selectedEndDate = dateStr;
        syncDateInputs();
        applyPackagePrice();
        refreshRoomAvailability();
        renderCalendar();
        return;
      }
    }

    // Otherwise this click starts a new selection (a single day so far).
    selectedDate = dateStr;
    selectedEndDate = null;
    syncDateInputs();
    applyPackagePrice();
    refreshRoomAvailability();
    renderCalendar();
    if (rangeProblem) showDateMessage(rangeProblem);

    // Real-time AJAX validation: double-check with the server
    fetch(checkDateUrl + '?date=' + dateStr + (roomsOnly() ? '&rooms_only=1' : ''))
      .then(res => res.json())
      .then(data => {
        if (!data.available && selectedDate === dateStr) {
          // Date became unavailable (e.g., just approved by admin).
          // Add to local approved dates so the calendar re-renders correctly.
          if (!approvedDates.includes(dateStr)) {
            approvedDates.push(dateStr);
          }
          clearSelection(data.reason);
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