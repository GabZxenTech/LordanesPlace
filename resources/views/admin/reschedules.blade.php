<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reschedule Requests | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page bg-admin-dark text-admin-cream font-body min-h-screen">

  @include('partials._admin-header')

  <div class="p-5 md:p-10">
    <h2 class="font-heading text-[24px] md:text-[28px] text-admin-gold mb-6 md:mb-8">Reschedule Requests</h2>

    @if(session('success'))
      <div class="bg-green-400/15 border border-green-400 text-green-400 px-5 py-3 rounded-md mb-5 text-[16px]">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="bg-red-500/15 border border-red-500 text-red-400 p-3 rounded-md mb-5">
        @foreach($errors->all() as $err) <div class="text-[15px]">{{ $err }}</div> @endforeach
      </div>
    @endif

    <div class="bg-admin-card border border-admin-gold/20 rounded-lg overflow-hidden">
      <div class="p-4 md:p-5 border-b border-admin-gold/20 bg-admin-secondary">
        <h2 class="text-[12px] md:text-[15px] tracking-[2px] text-admin-gold font-bold">PENDING RESCHEDULE REQUESTS</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead class="bg-admin-secondary">
            <tr>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">BOOKING #</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">CLIENT</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">CURRENT DATE</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">REQUESTED DATE</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold hidden md:table-cell">VISIT DATE</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold hidden lg:table-cell">REASON</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">FEE</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">ACTION</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reschedules as $booking)
              <tr class="border-b border-admin-gold/10 transition-colors hover:bg-admin-gold/5">
                <td class="p-3 md:px-4 md:py-3.5 text-[13px] text-admin-gold font-mono">{{ $booking->booking_number }}</td>
                <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream font-bold">{{ $booking->user->name }}</td>
                <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream-dim">
                  <div>📅 {{ $booking->event_date->format('M d, Y') }}</div>
                  @php $currentVisit = $booking->visitSchedules->first(); @endphp
                  @if($currentVisit)
                    <div class="text-[11px] mt-0.5">🏠 Visit: {{ $currentVisit->visit_date->format('M d, Y') }}</div>
                  @endif
                </td>
                <td class="p-3 md:px-4 md:py-3.5">
                  <div class="text-[15px] text-admin-cream font-bold">📅 {{ $booking->requested_event_date->format('M d, Y') }}</div>
                </td>
                <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream-dim hidden md:table-cell">
                  @if($booking->requested_visit_date)
                    🏠 {{ $booking->requested_visit_date->format('M d, Y') }}
                  @else
                    <span class="text-admin-cream-dim/50">—</span>
                  @endif
                </td>
                <td class="p-3 md:px-4 md:py-3.5 text-[13px] text-admin-cream-dim hidden lg:table-cell max-w-[200px]">
                  {{ $booking->reschedule_reason ? Str::limit($booking->reschedule_reason, 60) : '—' }}
                </td>
                <td class="p-3 md:px-4 md:py-3.5">
                  @if($booking->reschedule_fee == 0)
                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-400/15 text-admin-green border border-admin-green">FREE</span>
                    <div class="text-[10px] text-admin-cream-dim mt-0.5">1st reschedule</div>
                  @else
                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-admin-gold/15 text-admin-gold border border-admin-gold">₱{{ number_format($booking->reschedule_fee) }}</span>
                    <div class="text-[10px] text-admin-cream-dim mt-0.5">Reschedule #{{ $booking->reschedule_count + 1 }}</div>
                  @endif
                </td>
                <td class="p-3 md:px-4 md:py-3.5">
                  <div class="flex gap-1.5 flex-wrap">
                    <form method="POST" action="{{ route('admin.reschedule.approve', $booking->id) }}">
                      @csrf
                      <button type="submit" class="bg-green-400/15 border border-admin-green text-admin-green px-3 py-1.5 rounded text-[11px] cursor-pointer transition-all hover:bg-admin-green hover:text-admin-dark font-bold" onclick="return confirm('Approve this reschedule request?')">✓ Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.reschedule.reject', $booking->id) }}">
                      @csrf
                      <button type="submit" class="bg-red-400/15 border border-admin-red text-admin-red px-3 py-1.5 rounded text-[11px] cursor-pointer transition-all hover:bg-admin-red hover:text-white font-bold" onclick="return confirm('Reject this reschedule request?')">✕ Reject</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="8" class="text-center py-[60px] text-admin-cream-dim text-[16px]">No pending reschedule requests.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
