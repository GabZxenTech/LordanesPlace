<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notifications | Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
      <div>
        <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Notifications</h1>
        <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">All Admin Alerts</p>
      </div>
      @if($unreadCount > 0)
        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
          @csrf
          <button type="submit" style="background: transparent; border: 1px solid #c9a84c; color: #c9a84c; padding: 10px 20px; border-radius: 5px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; cursor: pointer;" onmouseover="this.style.background='#c9a84c'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#c9a84c';">Mark All Read</button>
        </form>
      @endif
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px;">✓ {{ session('success') }}</div>
    @endif

    {{-- FILTER TABS --}}
    <div style="display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap;">
      @php
        $tabs = ['all' => 'All', 'unread' => 'Unread (' . $unreadCount . ')', 'read' => 'Read'];
      @endphp
      @foreach($tabs as $key => $label)
        <a href="{{ route('admin.notifications.index', ['filter' => $key]) }}"
           style="padding: 9px 22px; border-radius: 100px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; transition: 0.2s; {{ $filter === $key ? 'background: #c9a84c; color: #2c1a0e; border: 1px solid #c9a84c;' : 'background: transparent; color: #8a6a40; border: 1px solid #d4c4a0;' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>

    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden;">
      @forelse($notifications as $notif)
        <div style="padding: 18px 24px; border-bottom: 1px solid #e8dcc8; display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; background: {{ $notif->is_read ? 'transparent' : 'rgba(201,168,76,0.08)' }};">
          <a href="{{ route('admin.notifications.open', $notif->id) }}" style="text-decoration: none; color: inherit; flex: 1;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
              @if(!$notif->is_read)
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #c9a84c; display: inline-block;"></span>
              @endif
              <p style="font-size: 15px; font-weight: 700; color: #2c1a0e; margin: 0;">{{ $notif->title }}</p>
            </div>
            <p style="font-size: 13px; color: #8a6a40; margin: 0; white-space: pre-line;">{{ $notif->message }}</p>
            <p style="font-size: 10px; color: #c9a84c; margin: 6px 0 0; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">{{ $notif->created_at->format('M d, Y \a\t h:i A') }} &middot; {{ $notif->created_at->diffForHumans() }}</p>
          </a>
          <div style="display: flex; flex-direction: column; gap: 6px;">
            @if(!$notif->is_read)
              <form method="POST" action="{{ route('admin.notifications.read', $notif->id) }}">
                @csrf
                <button type="submit" style="width: 100%; background: transparent; border: 1px solid #d4c4a0; color: #8a6a40; padding: 6px 14px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; white-space: nowrap;">Mark Read</button>
              </form>
            @endif
            <form method="POST" action="{{ route('admin.notifications.destroy', $notif->id) }}" onsubmit="return confirm('Delete this notification?');">
              @csrf @method('DELETE')
              <button type="submit" style="width: 100%; background: transparent; border: 1px solid #e74c3c; color: #e74c3c; padding: 6px 14px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; white-space: nowrap;">Delete</button>
            </form>
          </div>
        </div>
      @empty
        <p style="text-align: center; padding: 60px; color: #8a6a40; font-size: 15px; margin: 0;">
          @if($filter === 'unread')
            No new notifications.
          @elseif($filter === 'read')
            No read notifications yet.
          @else
            No notifications yet.
          @endif
        </p>
      @endforelse
    </div>

    <div style="margin-top: 24px;">
      {{ $notifications->links() }}
    </div>
  </main>
</body>
</html>
