<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Schedule Visit | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body min-h-screen flex items-center justify-center p-5 md:p-10">

  <div class="bg-cream border border-gold-deep/25 rounded-2xl p-8 md:p-10 max-w-[500px] w-full shadow-sm">
    <a href="{{ route('profile') }}" class="text-warm-black/50 text-[14px] no-underline hover:text-gold-deep transition-colors inline-block mb-5">← Back to My Bookings</a>

    <h1 class="font-heading text-[24px] md:text-[28px] font-bold text-gold-deep mb-2">Schedule a Visit</h1>
    <p class="text-warm-black/60 text-[15px] mb-6">Book a venue visit before your event day.</p>

    {{-- Booking Info --}}
    <div class="bg-gold-deep/5 border border-gold-deep/15 rounded-lg p-4 mb-6">
      <div class="text-[11px] text-warm-black/50 font-bold uppercase tracking-[1px] mb-2">Booking Details</div>
      <div class="text-[14px] text-warm-black/80 mb-1">📋 <strong>{{ $booking->event_type }}</strong> — {{ $booking->package }} Package</div>
      <div class="text-[14px] text-warm-black/80 mb-1">📅 Event Date: <strong>{{ $booking->event_date->format('F d, Y') }}</strong></div>
      <div class="text-[14px] text-warm-black/80"># <strong class="text-gold-deep">{{ $booking->booking_number }}</strong></div>
    </div>

    @if($errors->any())
      <div class="bg-red-400/10 border border-red-400 text-red-500 p-3 rounded-lg text-[14px] mb-5">
        @foreach($errors->all() as $err) <div>{{ $err }}</div> @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('visit-schedule.store') }}">
      @csrf
      <input type="hidden" name="booking_id" value="{{ $booking->id }}" />

      <div class="mb-4">
        <label class="block text-[12px] tracking-[1px] text-warm-black/60 mb-2 font-bold">Visit Date <span class="text-red-500">*</span></label>
        <input type="date" name="visit_date" required
          min="{{ date('Y-m-d', strtotime('+1 day')) }}"
          max="{{ $booking->event_date->subDay()->format('Y-m-d') }}"
          value="{{ old('visit_date') }}"
          class="w-full bg-white border border-gold-deep/30 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body" />
        <p class="text-[11px] text-warm-black/40 mt-1">Must be before {{ $booking->event_date->format('F d, Y') }}</p>
      </div>

      <div class="mb-4">
        <label class="block text-[12px] tracking-[1px] text-warm-black/60 mb-2 font-bold">Preferred Time <span class="text-red-500">*</span></label>
        <select name="visit_time" required class="w-full bg-white border border-gold-deep/30 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body">
          <option value="">Select a time</option>
          @for($i = 8; $i <= 17; $i++)
            @foreach(['00', '30'] as $min)
              @php
                $val24 = sprintf('%02d:%s', $i, $min);
                $formatted = \Carbon\Carbon::createFromFormat('H:i', $val24)->format('h:i A');
              @endphp
              <option value="{{ $val24 }}" {{ old('visit_time') == $val24 ? 'selected' : '' }}>{{ $formatted }}</option>
            @endforeach
          @endfor
        </select>
      </div>

      <div class="mb-6">
        <label class="block text-[12px] tracking-[1px] text-warm-black/60 mb-2 font-bold">Notes <span class="text-warm-black/30 font-normal">(optional)</span></label>
        <textarea name="notes" rows="2" placeholder="Any special requests for your visit?"
          class="w-full bg-white border border-gold-deep/30 text-warm-black px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-gold-deep font-body resize-y">{{ old('notes') }}</textarea>
      </div>

      <div class="flex gap-3 justify-end">
        <a href="{{ route('profile') }}" class="px-5 py-2.5 rounded-md border border-warm-black/20 text-warm-black/60 no-underline text-[14px] transition-colors hover:border-warm-black/40 inline-flex items-center">Cancel</a>
        <button type="submit" class="px-5 py-2.5 rounded-md bg-gold-deep text-white font-bold cursor-pointer text-[14px] border-none transition-all hover:bg-gold-mid">Schedule Visit</button>
      </div>
    </form>
  </div>

</body>
</html>
