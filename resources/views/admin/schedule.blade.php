<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Schedule Management | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .modal-overlay.open { display: flex; }
  </style>
</head>
<body class="admin-page bg-admin-dark text-admin-cream font-body min-h-screen">

  @include('partials._admin-header')

  <div class="p-5 md:p-10">
    <h2 class="font-heading text-[24px] md:text-[28px] text-admin-gold mb-6 md:mb-8">Schedule Management</h2>

    @if(session('success'))
      <div class="bg-green-400/15 border border-green-400 text-green-400 px-5 py-3 rounded-md mb-5 text-[16px]">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="bg-red-500/15 border border-red-500 text-red-400 p-3 rounded-md mb-5">
        @foreach($errors->all() as $err) <div class="text-[15px]">{{ $err }}</div> @endforeach
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_2fr] gap-6 md:gap-8">

      <!-- BLOCK DATE -->
      <div>
        <div class="bg-admin-card border border-admin-gold/20 rounded-lg overflow-hidden">
          <div class="p-4 md:p-5 border-b border-admin-gold/20 bg-admin-secondary">
            <h2 class="text-[12px] md:text-[15px] tracking-[2px] text-admin-gold font-bold">BLOCK A DATE</h2>
          </div>
          <div class="p-5 md:p-6">
            <form method="POST" action="{{ route('admin.block.date') }}">
              @csrf
              <div class="mb-4">
                <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Date to Block (Applies globally)</label>
                <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                  class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body" />
              </div>
              <div class="mb-4">
                <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Reason <span class="font-normal">(optional)</span></label>
                <input type="text" name="reason" placeholder="e.g. Private event, Maintenance"
                  class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body placeholder:text-admin-cream-dim" />
              </div>
              <button type="submit" class="w-full bg-admin-red text-white border-none py-3 rounded-md font-bold text-[15px] tracking-[1px] cursor-pointer transition-opacity hover:opacity-85">🚫 BLOCK DATE</button>
            </form>
          </div>
        </div>

        @if($blockedDates->count() > 0)
          <div class="bg-admin-card border border-admin-gold/20 rounded-lg overflow-hidden mt-5">
            <div class="p-4 md:p-5 border-b border-admin-gold/20 bg-admin-secondary">
              <h2 class="text-[12px] md:text-[15px] tracking-[2px] text-admin-gold font-bold">BLOCKED DATES</h2>
            </div>
            <div class="p-5 md:p-6">
              @foreach($blockedDates as $blocked)
                <div class="flex justify-between items-center py-3 border-b border-admin-gold/10 last:border-b-0 text-[15px]">
                  <div>
                    <div class="text-admin-cream">{{ $blocked->date->format('F d, Y') }}</div>
                    @if($blocked->reason)
                      <div class="text-admin-cream-dim text-[12px] mt-0.5">{{ $blocked->reason }}</div>
                    @endif
                  </div>
                  <form method="POST" action="{{ route('admin.unblock.date', $blocked->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-transparent border border-admin-red text-admin-red px-3 py-1 rounded text-[11px] cursor-pointer transition-all hover:bg-admin-red hover:text-white">Unblock</button>
                  </form>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <!-- BOOKINGS TABLE -->
      <div class="bg-admin-card border border-admin-gold/20 rounded-lg overflow-hidden">
        <div class="p-4 md:p-5 border-b border-admin-gold/20 bg-admin-secondary">
          <h2 class="text-[12px] md:text-[15px] tracking-[2px] text-admin-gold font-bold">BOOKING REQUESTS</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead class="bg-admin-secondary">
              <tr>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">BOOKING #</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">GUEST</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold hidden md:table-cell">EVENT</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">DATE & TIME</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold hidden lg:table-cell">PACKAGE</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">GUESTS</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">PAYMENT</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">STATUS</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">ACTION</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings as $booking)
                <tr class="border-b border-admin-gold/10 transition-colors hover:bg-admin-gold/5">
                  <td class="p-3 md:px-4 md:py-3.5 text-[13px] text-admin-gold font-mono">{{ $booking->booking_number }}</td>
                  <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream font-bold">{{ $booking->user->name }}</td>
                  <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream-dim hidden md:table-cell">{{ $booking->event_type }}</td>
                  <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream-dim">{{ $booking->event_date->format('M d, Y') }}<br><span class="text-[11px]">{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span></td>
                  <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream-dim hidden lg:table-cell">{{ $booking->package }}</td>
                  <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream-dim">{{ $booking->guest_count }}</td>
                  <td class="p-3 md:px-4 md:py-3.5">
                    <div class="text-[14px] text-admin-cream font-bold">₱{{ number_format($booking->total_amount, 2) }}</div>
                    <div class="text-[11px] text-admin-cream-dim">DP: ₱{{ number_format($booking->down_payment_amount, 2) }}</div>
                    @if($booking->payment_status === 'unpaid')
                      <span class="text-[10px] text-admin-red font-bold uppercase">Unpaid</span>
                    @elseif($booking->payment_status === 'partially_paid')
                      <span class="text-[10px] text-admin-green font-bold uppercase">Partially Paid</span>
                    @else
                      <span class="text-[10px] text-admin-blue font-bold uppercase">Fully Paid</span>
                    @endif
                  </td>
                  <td class="p-3 md:px-4 md:py-3.5">
                    @if($booking->status === 'pending')
                      <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-400/15 text-admin-yellow border border-admin-yellow">⏳ Pending</span>
                    @elseif($booking->status === 'approved')
                      <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-400/15 text-admin-green border border-admin-green">✓ Approved</span>
                    @else
                      <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-400/15 text-admin-red border border-admin-red">✕ Rejected</span>
                    @endif
                  </td>
                  <td class="p-3 md:px-4 md:py-3.5">
                    <div class="flex gap-1.5 items-center">
                      <button type="button" class="bg-blue-400/15 border border-admin-blue text-admin-blue px-2.5 py-1 rounded text-[11px] cursor-pointer transition-all hover:bg-blue-400 hover:text-white" onclick="openEditModal({{ $booking->id }}, '{{ $booking->package }}', '{{ $booking->event_date->format('Y-m-d') }}', '{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}', '{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}', '{{ $booking->guest_count }}', '{{ addslashes($booking->notes) }}', '{{ $booking->total_amount }}')">Edit</button>
                      <form method="POST" action="{{ route('admin.booking.destroy', $booking->id) }}" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-transparent border border-admin-red text-admin-red px-2.5 py-1 rounded text-[11px] cursor-pointer transition-all hover:bg-admin-red hover:text-white">Delete</button>
                      </form>

                      {{-- 3-dot menu for status actions --}}
                      @if($booking->status === 'pending' || $booking->payment_status === 'unpaid')
                        <div class="relative" id="dotMenu{{ $booking->id }}">
                          <button type="button" class="bg-admin-gold/10 border border-admin-gold/30 text-admin-gold w-7 h-7 rounded flex items-center justify-center text-[16px] cursor-pointer transition-all hover:bg-admin-gold hover:text-admin-dark" onclick="toggleDotMenu({{ $booking->id }})">⋯</button>
                          <div class="hidden absolute right-0 top-full mt-1 bg-admin-card border border-admin-gold/30 rounded-lg shadow-lg min-w-[160px] py-1.5 z-[100]" id="dotMenuDropdown{{ $booking->id }}">
                            @if($booking->status === 'pending')
                              <form method="POST" action="{{ route('admin.booking.approve', $booking->id) }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-[13px] text-admin-green bg-transparent border-none cursor-pointer transition-colors hover:bg-admin-gold/10 flex items-center gap-2"><span>✓</span> Approve</button>
                              </form>
                              <form method="POST" action="{{ route('admin.booking.reject', $booking->id) }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-[13px] text-admin-red bg-transparent border-none cursor-pointer transition-colors hover:bg-admin-gold/10 flex items-center gap-2"><span>✕</span> Reject</button>
                              </form>
                            @endif
                            @if($booking->payment_status === 'unpaid')
                              <form method="POST" action="{{ route('admin.booking.confirm-downpayment', $booking->id) }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-[13px] text-admin-gold bg-transparent border-none cursor-pointer transition-colors hover:bg-admin-gold/10 flex items-center gap-2"><span>💰</span> Confirm DP</button>
                              </form>
                            @endif
                          </div>
                        </div>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center py-10 text-admin-cream-dim text-[16px]">No bookings yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- EDIT BOOKING MODAL -->
  <div class="modal-overlay hidden fixed inset-0 bg-black/80 z-[1000] items-center justify-center" id="editModal" style="display:none;">
    <div class="bg-admin-card border border-admin-gold/30 rounded-lg p-6 md:p-8 w-[90%] max-w-[500px]">
      <div class="text-[18px] text-admin-gold mb-5 font-bold font-heading">EDIT BOOKING</div>
      <form method="POST" action="" id="editBookingForm">
        @csrf @method('PUT')
        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Package</label>
          <input type="text" name="package" id="edit_package" required class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body" />
        </div>
        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Date</label>
          <input type="date" name="event_date" id="edit_event_date" required class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body" />
        </div>
        <div class="grid grid-cols-2 gap-3 mb-4">
          <div>
            <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Start Time</label>
            <select name="start_time" id="edit_start_time" required class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body">
              <option value="">Select</option>
              @for($i = 8; $i <= 22; $i++) @foreach(['00', '30'] as $min) @if($i == 22 && $min == '30') @continue @endif @php $val24 = sprintf('%02d:%s', $i, $min); $formatted = \Carbon\Carbon::createFromFormat('H:i', $val24)->format('h:i A'); @endphp
              <option value="{{ $val24 }}">{{ $formatted }}</option>
              @endforeach @endfor
            </select>
          </div>
          <div>
            <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">End Time</label>
            <select name="end_time" id="edit_end_time" required class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body">
              <option value="">Select</option>
              @for($i = 8; $i <= 22; $i++) @foreach(['00', '30'] as $min) @if($i == 22 && $min == '30') @continue @endif @php $val24 = sprintf('%02d:%s', $i, $min); $formatted = \Carbon\Carbon::createFromFormat('H:i', $val24)->format('h:i A'); @endphp
              <option value="{{ $val24 }}">{{ $formatted }}</option>
              @endforeach @endfor
            </select>
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Number of Guests</label>
          <input type="number" name="guest_count" id="edit_guest_count" required min="1" class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body" />
        </div>
        <div class="mb-4">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Total Amount (₱)</label>
          <input type="number" name="total_amount" id="edit_total_amount" required step="0.01" class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body" />
        </div>
        <div class="mb-5">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Notes</label>
          <textarea name="notes" id="edit_notes" rows="2" class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body resize-y"></textarea>
        </div>
        <div class="flex gap-3 justify-end">
          <button type="button" class="bg-transparent border border-admin-cream-dim text-admin-cream-dim px-5 py-2.5 rounded-md cursor-pointer text-[15px]" onclick="document.getElementById('editModal').style.display='none'">Cancel</button>
          <button type="submit" class="bg-admin-gold text-admin-dark border-none px-5 py-2.5 rounded-md font-bold cursor-pointer text-[15px]">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openEditModal(id, pkg, date, start, end, guests, notes, total) {
      document.getElementById('editBookingForm').action = '/admin/booking/' + id;
      document.getElementById('edit_package').value = pkg;
      document.getElementById('edit_event_date').value = date;
      document.getElementById('edit_start_time').value = start;
      document.getElementById('edit_end_time').value = end;
      document.getElementById('edit_guest_count').value = guests;
      document.getElementById('edit_notes').value = notes;
      document.getElementById('edit_total_amount').value = total;
      document.getElementById('editModal').style.display = 'flex';
    }

    function toggleDotMenu(id) {
      const dropdown = document.getElementById('dotMenuDropdown' + id);
      const isHidden = dropdown.classList.contains('hidden');
      
      // Close all other dot menus first
      document.querySelectorAll('[id^="dotMenuDropdown"]').forEach(el => {
        el.classList.add('hidden');
      });

      if (isHidden) {
        dropdown.classList.remove('hidden');
      }
    }

    // Close dot menus when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('[id^="dotMenu"]')) {
        document.querySelectorAll('[id^="dotMenuDropdown"]').forEach(el => {
          el.classList.add('hidden');
        });
      }
    });
  </script>
</body>
</html>