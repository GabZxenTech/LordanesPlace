<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notifications | LorDane's Place</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<section class="py-12 md:py-24 bg-off-white min-h-screen" style="font-family: 'Jost', sans-serif;">
  <div class="max-w-[900px] mx-auto px-4">

    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem; padding: 0 0.5rem;">
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; font-weight: 700; color: #1A1208; margin: 0;">Notifications</h2>
      @if($unreadCount > 0)
        <form method="POST" action="{{ route('notifications.read-all') }}">
          @csrf
          <button type="submit" style="background: transparent; border: 1px solid #B8860B; color: #B8860B; padding: 0.6rem 1.4rem; border-radius: 3px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer;">Mark All Read</button>
        </form>
      @endif
    </div>

    {{-- FILTER TABS --}}
    <div style="display: flex; gap: 0.5rem; margin-bottom: 2rem; padding: 0 0.5rem; flex-wrap: wrap;">
      @php
        $tabs = ['all' => 'All', 'unread' => 'Unread (' . $unreadCount . ')', 'read' => 'Read'];
      @endphp
      @foreach($tabs as $key => $label)
        <a href="{{ route('notifications.index', ['filter' => $key]) }}"
           style="padding: 0.55rem 1.4rem; border-radius: 100px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; text-decoration: none; transition: 0.2s; {{ $filter === $key ? 'background: #B8860B; color: white; border: 1px solid #B8860B;' : 'background: transparent; color: #B8860B; border: 1px solid #B8860B40;' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>

    <div style="display: flex; flex-direction: column; gap: 1rem;">
      @forelse($notifications as $notif)
        <div style="background: {{ $notif->is_read ? 'white' : '#B8860B08' }}; border: 1px solid {{ $notif->is_read ? '#B8860B15' : '#B8860B40' }}; border-radius: 10px; padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: flex-start; gap: 1.5rem;">
          <a href="{{ route('notifications.open', $notif->id) }}" style="text-decoration: none; color: inherit; flex: 1;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.4rem;">
              @if(!$notif->is_read)
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #B8860B; display: inline-block;"></span>
              @endif
              <p style="font-size: 16px; font-weight: 700; color: #1A1208; margin: 0;">{{ $notif->title }}</p>
            </div>
            <p style="font-size: 14px; color: #1A120890; margin: 0; white-space: pre-line;">{{ $notif->message }}</p>
            <p style="font-size: 11px; color: #B8860B; margin: 0.6rem 0 0; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">{{ $notif->created_at->format('M d, Y \a\t h:i A') }} &middot; {{ $notif->created_at->diffForHumans() }}</p>
          </a>
          <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @if(!$notif->is_read)
              <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                @csrf
                <button type="submit" style="width: 100%; background: transparent; border: 1px solid #B8860B40; color: #B8860B; padding: 0.4rem 0.9rem; border-radius: 3px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; white-space: nowrap;">Mark Read</button>
              </form>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}" onsubmit="return confirm('Delete this notification?');">
              @csrf @method('DELETE')
              <button type="submit" style="width: 100%; background: transparent; border: 1px solid #ef444440; color: #ef4444; padding: 0.4rem 0.9rem; border-radius: 3px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; white-space: nowrap;">Delete</button>
            </form>
          </div>
        </div>
      @empty
        <div style="text-align: center; padding: 6rem 2rem; background: #FAF8F3; border: 2px dashed #B8860B20; border-radius: 12px; color: #B8860B50;">
          <p style="font-family: 'Cormorant Garamond', serif; font-size: 1.75rem; margin: 0;">
            @if($filter === 'unread')
              No new notifications.
            @elseif($filter === 'read')
              No read notifications yet.
            @else
              You have no notifications yet.
            @endif
          </p>
        </div>
      @endforelse
    </div>

    <div style="margin-top: 2.5rem;">
      {{ $notifications->links() }}
    </div>
  </div>
</section>

@include('partials._footer')
</body>
</html>
