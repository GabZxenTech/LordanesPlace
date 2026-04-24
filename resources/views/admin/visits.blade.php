<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visit Schedules | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page bg-admin-dark text-admin-cream font-body min-h-screen">

  @include('partials._admin-header')

  <div class="p-5 md:p-10">
    <div class="flex justify-between items-center mb-8">
        <h2 class="font-heading text-[24px] md:text-[28px] text-admin-gold">Visit Schedules</h2>
    </div>

    @if(session('success'))
      <div class="bg-green-400/15 border border-admin-green text-admin-green px-5 py-3 rounded-md mb-6 text-[16px]">✓ {{ session('success') }}</div>
    @endif

    <div class="bg-admin-card border border-admin-gold/20 rounded-lg overflow-hidden">
      <div class="p-4 md:p-5 border-b border-admin-gold/20 bg-admin-secondary">
        <h2 class="text-[12px] md:text-[15px] tracking-[2px] text-admin-gold font-bold">ALL SCHEDULED VISITS</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead class="bg-admin-secondary">
            <tr>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">CLIENT</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">BOOKING</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">VISIT DATE</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">NOTES</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">STATUS</th>
              <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">ACTION</th>
            </tr>
          </thead>
          <tbody>
            @forelse($visits as $visit)
              <tr class="border-b border-admin-gold/10 transition-colors hover:bg-admin-gold/5">
                <td class="p-3 md:px-4 md:py-3.5">
                    <div class="text-[15px] text-admin-cream font-bold">{{ $visit->user->name }}</div>
                    <div class="text-[12px] text-admin-cream-dim">{{ $visit->user->email }}</div>
                </td>
                <td class="p-3 md:px-4 md:py-3.5">
                    <div class="text-[14px] text-admin-cream">{{ $visit->booking->event_type }}</div>
                    <div class="text-[12px] text-admin-gold font-bold">Event: {{ $visit->booking->event_date->format('M d, Y') }}</div>
                </td>
                <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream">
                    {{ $visit->visit_date->format('M d, Y') }}<br>
                    <span class="text-[12px] text-admin-cream-dim">{{ $visit->visit_date->format('h:i A') }}</span>
                </td>
                <td class="p-3 md:px-4 md:py-3.5 text-[14px] text-admin-cream-dim">
                    <div class="max-w-[200px] whitespace-normal">{{ $visit->notes ?? 'None' }}</div>
                </td>
                <td class="p-3 md:px-4 md:py-3.5">
                    @if($visit->status === 'pending')
                      <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-400/15 text-admin-yellow border border-admin-yellow">⏳ Pending</span>
                    @elseif($visit->status === 'confirmed')
                      <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-400/15 text-admin-green border border-admin-green">✓ Confirmed</span>
                    @elseif($visit->status === 'rescheduled')
                      <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-400/15 text-admin-blue border border-admin-blue">🔄 Rescheduled</span>
                    @else
                      <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-white/10 text-admin-cream-dim border border-admin-cream-dim">Completed</span>
                    @endif
                </td>
                <td class="p-3 md:px-4 md:py-3.5">
                    <div class="flex gap-2 flex-wrap">
                        @if($visit->status === 'pending')
                          <form method="POST" action="{{ route('admin.visits.confirm', $visit->id) }}">
                            @csrf
                            <button type="submit" class="bg-green-400/15 border border-admin-green text-admin-green px-2.5 py-1 rounded text-[11px] cursor-pointer hover:bg-admin-green hover:text-admin-dark transition-all">Confirm</button>
                          </form>
                        @endif
                        
                        @if($visit->status !== 'completed')
                          <button type="button" class="bg-blue-400/15 border border-admin-blue text-admin-blue px-2.5 py-1 rounded text-[11px] cursor-pointer hover:bg-admin-blue hover:text-white transition-all" 
                            onclick="openRescheduleModal({{ $visit->id }}, '{{ $visit->visit_date->format('Y-m-d\TH:i') }}', '{{ $visit->booking->event_date->format('Y-m-d') }}')">
                            Reschedule
                          </button>
                          <form method="POST" action="{{ route('admin.visits.complete', $visit->id) }}">
                             @csrf
                             <button type="submit" class="bg-white/10 border border-admin-cream-dim text-admin-cream-dim px-2.5 py-1 rounded text-[11px] cursor-pointer hover:bg-admin-cream-dim hover:text-admin-dark transition-all">Complete</button>
                          </form>
                        @endif
                    </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center py-10 text-admin-cream-dim text-[16px]">No scheduled visits yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- RESCHEDULE MODAL -->
  <div class="modal-overlay hidden fixed inset-0 bg-black/80 z-[1000] items-center justify-center flex" id="rescheduleModal" style="display:none;">
    <div class="bg-admin-card border border-admin-gold/30 rounded-lg p-6 md:p-8 w-[90%] max-w-[400px]">
      <div class="text-[18px] text-admin-gold mb-5 font-bold font-heading uppercase tracking-[1px]">Reschedule Visit</div>
      <form method="POST" action="" id="rescheduleForm">
        @csrf
        <div class="mb-5">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold uppercase">New Visit Date & Time</label>
          <input type="datetime-local" name="visit_date" id="reschedule_date" required class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body" />
          <p id="reschedule_warning" class="mt-2 text-[11px] text-admin-red hidden">Must be before the event date.</p>
        </div>
        <div class="flex gap-3 justify-end">
          <button type="button" class="bg-transparent border border-admin-cream-dim text-admin-cream-dim px-5 py-2.5 rounded-md cursor-pointer text-[15px]" onclick="document.getElementById('rescheduleModal').style.display='none'">Cancel</button>
          <button type="submit" class="bg-admin-gold text-admin-dark border-none px-5 py-2.5 rounded-md font-bold cursor-pointer text-[15px]">Save New Date</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openRescheduleModal(id, current, eventDate) {
      const modal = document.getElementById('rescheduleModal');
      const form = document.getElementById('rescheduleForm');
      const dateInput = document.getElementById('reschedule_date');
      
      form.action = '/admin/visits/' + id + '/reschedule';
      dateInput.value = current;
      dateInput.max = eventDate + 'T00:00';
      
      modal.style.display = 'flex';
    }
  </script>

</body>
</html>
