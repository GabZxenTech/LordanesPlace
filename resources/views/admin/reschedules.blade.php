<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reschedule Requests | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">
    <div style="margin-bottom: 32px;">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Reschedule Requests</h1>
      <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Manage Reservation Changes</p>
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px;">✓ {{ session('success') }}</div>
    @endif

    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden;">
      <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
        <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">PENDING REQUESTS</h2>
      </div>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="border-bottom: 1px solid #d4c4a0;">
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">#</th>
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">CLIENT</th>
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">CURRENT DATE</th>
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">REQUESTED DATE</th>
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">FEE</th>
              <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">ACTION</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reschedules as $booking)
              <tr style="border-bottom: 1px solid #e8dcc8; transition: background 0.2s;" onmouseover="this.style.background='#f5edd8'" onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 20px; font-size: 13px; color: #c9a84c; font-family: monospace;">{{ $booking->booking_number }}</td>
                <td style="padding: 14px 20px;">
                  <div style="font-size: 15px; color: #2c1a0e; font-weight: 600;">{{ $booking->user->name }}</div>
                  <div style="font-size: 12px; color: #8a6a40;">Reason: {{ $booking->reschedule_reason ?? '—' }}</div>
                </td>
                <td style="padding: 14px 20px;">
                  <div style="font-size: 14px; color: #8a6a40;">📅 {{ $booking->event_date->format('M d, Y') }}</div>
                </td>
                <td style="padding: 14px 20px;">
                  <div style="font-size: 15px; color: #2c1a0e; font-weight: 700;">📅 {{ $booking->requested_event_date->format('M d, Y') }}</div>
                </td>
                <td style="padding: 14px 20px;">
                  @if($booking->reschedule_fee == 0)
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; background: #d4edda; color: #28a745; border: 1px solid #28a745;">FREE</span>
                  @else
                    <span style="display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; background: #fff3cd; color: #856404; border: 1px solid #ffeeba;">₱{{ number_format($booking->reschedule_fee) }}</span>
                  @endif
                </td>
                <td style="padding: 14px 20px;">
                  <div style="display: flex; gap: 8px;">
                    <form method="POST" action="{{ route('admin.reschedule.approve', $booking->id) }}">
                      @csrf
                      <button type="submit" style="background: transparent; border: 1px solid #28a745; color: #28a745; padding: 6px 14px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#28a745'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#28a745';" onclick="return confirm('Approve this reschedule?')">✓ Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.reschedule.reject', $booking->id) }}">
                      @csrf
                      <button type="submit" style="background: transparent; border: 1px solid #e74c3c; color: #e74c3c; padding: 6px 14px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#e74c3c'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#e74c3c';" onclick="return confirm('Reject this reschedule?')">✕ Reject</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" style="text-align: center; padding: 60px; color: #8a6a40; font-size: 16px;">No pending requests.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>
</html>
