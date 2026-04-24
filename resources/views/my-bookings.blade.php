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
            <a href="{{ route('booking.receipt', $booking->id) }}" class="text-[12px] text-gold-deep hover:underline font-bold flex items-center gap-1">
                <span>📄</span> Receipt
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