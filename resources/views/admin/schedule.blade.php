<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Schedule Management | Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.open { display: flex; }
    .status-dot { display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; text-transform: capitalize; }
    .status-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .status-approved { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .status-rejected { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .status-ongoing { background: #d6e9ff; color: #0d4a91; border: 1px solid #b8d9ff; }
    .status-completed { background: #f5edd8; color: #8a6a40; border: 1px solid #d4c4a0; }
    .status-cancelled { background: #e5e7eb; color: #4b5563; border: 1px solid #d1d5db; }
  </style>
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">
    <div style="margin-bottom: 32px;">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Schedule Management</h1>
      <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Manage Venue Availability & Bookings</p>
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px;">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div style="background: #f8d7da; border: 1px solid #dc3545; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px;">
        @foreach($errors->all() as $err) <div style="font-size: 14px;">{{ $err }}</div> @endforeach
      </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2.5fr; gap: 28px;">

      {{-- LEFT COLUMN: BLOCK DATE --}}
      <div style="display: flex; flex-direction: column; gap: 28px;">
        <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden;">
          <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
            <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">BLOCK A DATE</h2>
          </div>
          <div style="padding: 24px;">
            <form method="POST" action="{{ route('admin.block.date') }}">
              @csrf
              <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Date to Block</label>
                <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                  style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                  onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
              </div>
              <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Reason <span style="font-weight: 400;">(optional)</span></label>
                <input type="text" name="reason" placeholder="e.g. Private event, Maintenance"
                  style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                  onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
              </div>
              <button type="submit" style="width: 100%; background: #e74c3c; color: white; border: none; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 14px; letter-spacing: 1px; cursor: pointer; transition: opacity 0.3s; font-family: 'Jost', sans-serif;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">🚫 BLOCK DATE</button>
            </form>
          </div>
        </div>

        @if($blockedDates->count() > 0)
          <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden;">
            <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
              <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">BLOCKED DATES</h2>
            </div>
            <div style="padding: 12px 24px;">
              @foreach($blockedDates as $blocked)
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e8dcc8; padding: 12px 0;">
                  <div style="padding-bottom: 12px;">
                    <div style="font-size: 15px; color: #2c1a0e; font-weight: 600;">{{ $blocked->date->format('F d, Y') }}</div>
                    @if($blocked->reason)
                      <div style="font-size: 12px; color: #8a6a40; margin-top: 2px;">{{ $blocked->reason }}</div>
                    @endif
                  </div>
                  <form method="POST" action="{{ route('admin.unblock.date', $blocked->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" style="background: transparent; border: 1px solid #e74c3c; color: #e74c3c; padding: 5px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#e74c3c'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#e74c3c';">Unblock</button>
                  </form>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      {{-- RIGHT COLUMN: BOOKINGS TABLE --}}
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden;">
        <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
          <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">BOOKING REQUESTS</h2>
        </div>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="border-bottom: 1px solid #d4c4a0;">
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">#</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">GUEST</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">DATE & TIME</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">PAYMENT</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">STATUS</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">ACTION</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings as $booking)
                <tr id="booking-{{ $booking->id }}" style="border-bottom: 1px solid #e8dcc8; transition: background 0.2s; scroll-margin-top: 30px;" onmouseover="this.style.background='#f5edd8'" onmouseout="this.style.background='transparent'">
                  <td style="padding: 14px 20px; font-size: 13px; color: #c9a84c; font-family: monospace;">{{ $booking->booking_number }}</td>
                  <td style="padding: 14px 20px;">
                    <div style="font-size: 15px; color: #2c1a0e; font-weight: 600;">{{ $booking->user->name }}</div>
                    <div style="font-size: 12px; color: #8a6a40;">{{ $booking->event_type }}</div>
                  </td>
                  <td style="padding: 14px 20px;">
                    <div style="font-size: 14px; color: #2c1a0e; font-weight: 600;">{{ $booking->event_date->format('M d, Y') }}</div>
                    <div style="font-size: 11px; color: #8a6a40;">
                      @if($booking->start_time && $booking->end_time)
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                      @else
                        <span style="color: #e74c3c;">Schedule not yet assigned</span>
                      @endif
                    </div>
                  </td>
                  <td style="padding: 14px 20px;">
                    <div style="font-size: 14px; color: #2c1a0e; font-weight: 700;">₱{{ number_format($booking->total_amount, 2) }}</div>
                    <div style="font-size: 10px; color: #8a6a40; text-transform: uppercase; font-weight: 700;">{{ str_replace('_', ' ', $booking->payment_status) }}</div>
                    <div style="font-size: 10px; color: #c9a84c; margin-top: 2px;">{{ \App\Models\Booking::paymentOptionLabel($booking->payment_option) }}</div>
                    @if($booking->hasConfirmedPayment())
                      <div style="font-size: 10px; color: #28a745; margin-top: 2px;">Paid ₱{{ number_format($booking->amountPaid(), 2) }}@if($booking->remainingBalance() > 0) &middot; ₱{{ number_format($booking->remainingBalance(), 2) }} left @endif</div>
                    @endif
                  </td>
                  <td style="padding: 14px 20px;">
                    <span class="status-dot status-{{ $booking->status }}">
                      {{ $booking->status }}
                    </span>
                  </td>
                  <td style="padding: 14px 20px;">
                    <div style="display: flex; gap: 8px; align-items: center;">
                      <button type="button" 
                        class="edit-booking-btn"
                        style="background: transparent; border: 1px solid #3498db; color: #3498db; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" 
                        data-id="{{ $booking->id }}"
                        data-package="{{ $booking->package }}"
                        data-date="{{ $booking->event_date->format('Y-m-d') }}"
                        data-start="{{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '' }}"
                        data-end="{{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : '' }}"
                        data-guests="{{ $booking->guest_count }}"
                        data-notes="{{ addslashes($booking->notes) }}"
                        data-total="{{ $booking->total_amount }}"
                        onmouseover="this.style.background='#3498db'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#3498db';">
                        Edit
                      </button>
                      
                      {{-- Action Menu --}}
                      <div style="position: relative;">
                        <button type="button" class="action-menu-btn" style="background: #2c1a0e; color: #c9a84c; width: 28px; height: 28px; border-radius: 4px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px;" data-id="{{ $booking->id }}">⋮</button>
                        <div id="menu-{{ $booking->id }}" style="display: none; position: absolute; right: 0; top: 100%; background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 50; min-width: 140px; margin-top: 5px;">
                          {{-- Actions are driven by the model's transition map, so the menu
                               can never offer a status change the server would reject. --}}
                          @if($booking->canTransitionTo('approved'))
                            <form method="POST" action="{{ route('admin.booking.approve', $booking->id) }}">
                              @csrf
                              <button type="submit" style="width: 100%; text-align: left; padding: 10px 16px; border: none; background: transparent; font-size: 13px; color: #28a745; font-weight: 600; cursor: pointer;" onmouseover="this.style.background='#f5f0e8'">Approve</button>
                            </form>
                          @endif
                          @if($booking->canTransitionTo('rejected'))
                            <form method="POST" action="{{ route('admin.booking.reject', $booking->id) }}">
                              @csrf
                              <button type="submit" style="width: 100%; text-align: left; padding: 10px 16px; border: none; background: transparent; font-size: 13px; color: #e74c3c; font-weight: 600; cursor: pointer;" onmouseover="this.style.background='#f5f0e8'">Reject</button>
                            </form>
                          @endif
                          @if($booking->canTransitionTo('ongoing'))
                            <form method="POST" action="{{ route('admin.booking.ongoing', $booking->id) }}">
                              @csrf
                              <button type="submit" style="width: 100%; text-align: left; padding: 10px 16px; border: none; background: transparent; font-size: 13px; color: #3498db; font-weight: 600; cursor: pointer;" onmouseover="this.style.background='#f5f0e8'">Mark Ongoing</button>
                            </form>
                          @endif
                          @if($booking->canTransitionTo('completed'))
                            <form method="POST" action="{{ route('admin.booking.completed', $booking->id) }}">
                              @csrf
                              <button type="submit" style="width: 100%; text-align: left; padding: 10px 16px; border: none; background: transparent; font-size: 13px; color: #8a6a40; font-weight: 600; cursor: pointer;" onmouseover="this.style.background='#f5f0e8'">Mark Completed</button>
                            </form>
                          @endif
                          @if($booking->canTransitionTo('cancelled'))
                            <form method="POST" action="{{ route('admin.booking.cancel', $booking->id) }}" onsubmit="return confirm('Cancel this booking? The customer will be notified.')">
                              @csrf
                              <button type="submit" style="width: 100%; text-align: left; padding: 10px 16px; border: none; background: transparent; font-size: 13px; color: #e74c3c; font-weight: 600; cursor: pointer;" onmouseover="this.style.background='#f5f0e8'">Cancel Booking</button>
                            </form>
                          @endif
                          @if($booking->remainingBalance() > 0)
                            <button type="button"
                              class="record-payment-btn"
                              style="width: 100%; text-align: left; padding: 10px 16px; border: none; background: transparent; font-size: 13px; color: #c9a84c; font-weight: 600; cursor: pointer;"
                              onmouseover="this.style.background='#f5f0e8'" onmouseout="this.style.background='transparent'"
                              data-id="{{ $booking->id }}"
                              data-remaining="{{ $booking->remainingBalance() }}"
                              data-name="{{ addslashes($booking->user->name) }}">
                              {{ $booking->hasConfirmedPayment() ? 'Record Additional Payment' : 'Record Payment' }}
                            </button>
                          @endif
                          @if($booking->hasConfirmedPayment())
                            <a href="{{ route('booking.receipt', $booking->id) }}" target="_blank" style="display: block; width: 100%; text-align: left; padding: 10px 16px; font-size: 13px; color: #2c1a0e; font-weight: 600; text-decoration: none; box-sizing: border-box;" onmouseover="this.style.background='#f5f0e8'" onmouseout="this.style.background='transparent'">View / Print Receipt</a>
                          @endif
                          <form method="POST" action="{{ route('admin.booking.destroy', $booking->id) }}" onsubmit="return confirm('Delete this booking?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="width: 100%; text-align: left; padding: 10px 16px; border: none; background: transparent; font-size: 13px; color: #e74c3c; font-weight: 600; cursor: pointer;" onmouseover="this.style.background='#f5f0e8'">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" style="text-align: center; padding: 60px; color: #8a6a40; font-size: 16px;">No bookings found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  {{-- EDIT MODAL --}}
  <div id="editModal" class="modal-overlay">
    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 40px; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto;">
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 700; color: #2c1a0e; margin: 0 0 28px; text-transform: uppercase; letter-spacing: 1px;">Edit Booking</h2>
      <form method="POST" action="" id="editBookingForm">
        @csrf @method('PUT')
        
        <div style="margin-bottom: 16px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Package</label>
          <input type="text" name="package" id="edit_package" required 
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
        </div>

        <div style="margin-bottom: 16px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Event Date</label>
          <input type="date" name="event_date" id="edit_event_date" required min="{{ date('Y-m-d') }}"
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
          <div>
            <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Event Start Time <span style="font-weight: 400;">(optional)</span></label>
            <select name="start_time" id="edit_start_time" style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box;">
              <option value="">-- Not yet assigned --</option>
              @for($i = 8; $i <= 22; $i++) @foreach(['00', '30'] as $min) @php $val = sprintf('%02d:%s', $i, $min); @endphp <option value="{{ $val }}">{{ \Carbon\Carbon::createFromFormat('H:i', $val)->format('h:i A') }}</option> @endforeach @endfor
            </select>
          </div>
          <div>
            <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Event End Time <span style="font-weight: 400;">(optional)</span></label>
            <select name="end_time" id="edit_end_time" style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box;">
              <option value="">-- Not yet assigned --</option>
              @for($i = 8; $i <= 22; $i++) @foreach(['00', '30'] as $min) @php $val = sprintf('%02d:%s', $i, $min); @endphp <option value="{{ $val }}">{{ \Carbon\Carbon::createFromFormat('H:i', $val)->format('h:i A') }}</option> @endforeach @endfor
            </select>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
          <div>
            <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Guests</label>
            <input type="number" name="guest_count" id="edit_guest_count" required 
              style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
          </div>
          <div>
            <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Total (₱)</label>
            <input type="number" name="total_amount" id="edit_total_amount" required step="0.01" 
              style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
          </div>
        </div>

        <div style="margin-bottom: 28px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Notes</label>
          <textarea name="notes" id="edit_notes" rows="2" 
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; resize: vertical; box-sizing: border-box;"></textarea>
        </div>

        <div style="display: flex; gap: 12px;">
          <button type="submit" style="flex: 1; background: #c9a84c; color: #2c1a0e; border: none; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 15px; cursor: pointer; transition: opacity 0.3s; font-family: 'Jost', sans-serif;">Save Changes</button>
          <button type="button" style="flex: 1; border: 1px solid #d4c4a0; background: transparent; color: #8a6a40; padding: 14px; border-radius: 6px; font-size: 15px; cursor: pointer;" onclick="document.getElementById('editModal').classList.remove('open')">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  {{-- RECORD PAYMENT MODAL --}}
  <div id="paymentModal" class="modal-overlay">
    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 40px; width: 100%; max-width: 420px;">
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 700; color: #2c1a0e; margin: 0 0 8px; text-transform: uppercase; letter-spacing: 1px;">Record Payment</h2>
      <p id="payment_customer_name" style="font-size: 13px; color: #8a6a40; margin: 0 0 24px;"></p>
      <form method="POST" action="" id="paymentForm">
        @csrf
        <div style="margin-bottom: 16px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Remaining Balance</label>
          <p id="payment_remaining_display" style="font-size: 20px; font-weight: 700; color: #2c1a0e; margin: 0;">₱0.00</p>
        </div>
        <div style="margin-bottom: 16px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Amount Actually Received (₱)</label>
          <input type="number" name="amount" id="payment_amount" required min="0.01" step="0.01"
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
          <p style="font-size: 11px; color: #8a6a40; margin: 6px 0 0;">Pre-filled with the full remaining balance — lower it if the customer only paid part of it.</p>
        </div>
        <div style="margin-bottom: 24px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Note <span style="font-weight: 400;">(optional)</span></label>
          <input type="text" name="note" id="payment_note" placeholder="e.g. GCash reference, cash on-site"
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
        </div>
        <div style="display: flex; gap: 12px;">
          <button type="submit" style="flex: 1; background: #28a745; color: white; border: none; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 15px; cursor: pointer; transition: opacity 0.3s; font-family: 'Jost', sans-serif;">Confirm Payment</button>
          <button type="button" style="flex: 1; border: 1px solid #d4c4a0; background: transparent; color: #8a6a40; padding: 14px; border-radius: 6px; font-size: 15px; cursor: pointer;" onclick="document.getElementById('paymentModal').classList.remove('open')">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <div id="admin-data" class="hidden" data-base-url="{{ url('admin/booking') }}"></div>

  <script>
    const adminData = document.getElementById('admin-data');
    const bookingBaseUrl = adminData.getAttribute('data-base-url');

    document.querySelectorAll('.edit-booking-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const pkg = this.getAttribute('data-package');
        const date = this.getAttribute('data-date');
        const start = this.getAttribute('data-start');
        const end = this.getAttribute('data-end');
        const guests = this.getAttribute('data-guests');
        const notes = this.getAttribute('data-notes');
        const total = this.getAttribute('data-total');
        openEditModal(id, pkg, date, start, end, guests, notes, total);
      });
    });

    document.querySelectorAll('.action-menu-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        const id = this.getAttribute('data-id');
        toggleActionMenu(id, e);
      });
    });

    document.querySelectorAll('.record-payment-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const remaining = parseFloat(this.getAttribute('data-remaining')) || 0;
        const name = this.getAttribute('data-name');
        openPaymentModal(id, remaining, name);
      });
    });

    function openPaymentModal(id, remaining, name) {
      document.getElementById('paymentForm').action = bookingBaseUrl + '/' + id + '/record-payment';
      document.getElementById('payment_customer_name').textContent = name;
      document.getElementById('payment_remaining_display').textContent =
        '₱' + remaining.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      document.getElementById('payment_amount').value = remaining.toFixed(2);
      document.getElementById('payment_amount').max = remaining.toFixed(2);
      document.getElementById('payment_note').value = '';
      document.getElementById('paymentModal').classList.add('open');
    }

    function toggleActionMenu(id, e) {
      e.stopPropagation();
      const menu = document.getElementById('menu-' + id);
      const isVisible = menu.style.display === 'block';
      
      // Close all menus first
      document.querySelectorAll('[id^="menu-"]').forEach(m => m.style.display = 'none');
      
      if (!isVisible) {
        menu.style.display = 'block';
      }
    }

    function openEditModal(id, pkg, date, start, end, guests, notes, total) {
      document.getElementById('editBookingForm').action = bookingBaseUrl + '/' + id;
      document.getElementById('edit_package').value = pkg;
      document.getElementById('edit_event_date').value = date;
      document.getElementById('edit_start_time').value = start;
      document.getElementById('edit_end_time').value = end;
      document.getElementById('edit_guest_count').value = guests;
      document.getElementById('edit_notes').value = notes;
      document.getElementById('edit_total_amount').value = total;
      document.getElementById('editModal').classList.add('open');
    }

    document.addEventListener('click', () => {
      document.querySelectorAll('[id^="menu-"]').forEach(m => m.style.display = 'none');
    });

    // Highlight the booking row a notification linked to (e.g. #booking-42)
    if (window.location.hash.startsWith('#booking-')) {
      const target = document.querySelector(window.location.hash);
      if (target) {
        target.style.transition = 'background 0.4s ease';
        target.style.background = '#f5edd8';
        setTimeout(() => { target.style.background = ''; }, 2500);
      }
    }
  </script>

</body>
</html>