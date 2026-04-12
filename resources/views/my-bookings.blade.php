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
      <div>
        <h3 class="text-[15px] md:text-[16px] font-bold text-warm-black mb-1.5">{{ $booking->event_type }} — {{ $booking->package }} Package</h3>
        <p class="text-[15px] text-warm-black/90 mb-0.5">📅 {{ $booking->event_date->format('F d, Y') }}</p>
        <p class="text-[15px] text-warm-black/90 mb-0.5">⏰ {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</p>
        <p class="text-[15px] text-warm-black/90 mb-0.5">👥 {{ $booking->guest_count }} guests</p>
        @if($booking->notes)
          <p class="text-[15px] text-warm-black/90">📝 {{ $booking->notes }}</p>
        @endif
      </div>
      <div class="shrink-0">
        @if($booking->status === 'pending')
          <span class="inline-block px-3.5 py-1.5 rounded-full text-[12px] font-bold tracking-[0.5px] bg-yellow-400/15 text-yellow-600 border border-yellow-400">⏳ Pending</span>
        @elseif($booking->status === 'approved')
          <span class="inline-block px-3.5 py-1.5 rounded-full text-[12px] font-bold tracking-[0.5px] bg-green-400/15 text-green-600 border border-green-400">✓ Approved</span>
        @else
          <span class="inline-block px-3.5 py-1.5 rounded-full text-[12px] font-bold tracking-[0.5px] bg-red-400/15 text-red-500 border border-red-400">✕ Rejected</span>
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

@include('partials._footer')
</body>
</html>