<div class="nav-notif-item flex items-start gap-2 px-4 py-3 border-b border-gold-deep/10 transition-colors {{ $notif->is_read ? '' : 'is-unread' }}" data-notif-id="{{ $notif->id }}" data-sig="{{ $notif->id }}{{ $notif->is_read ? 'r' : 'u' }}">
  <a href="{{ route('notifications.open', $notif->id) }}" class="block flex-1 no-underline">
    <p class="nav-notif-title text-[13px] text-warm-black m-0"><span class="nav-notif-dot" aria-label="Unread"></span>{{ $notif->title }}</p>
    <p class="nav-notif-msg text-[12px] text-warm-black/70 m-0 mt-1" style="white-space: pre-line;">{{ \Illuminate\Support\Str::limit($notif->message, 90) }}</p>
    <p class="text-[10px] text-gold-deep/80 m-0 mt-1 uppercase tracking-wide">{{ $notif->created_at->diffForHumans() }}</p>
  </a>
  <div class="nav-notif-acts">
    <button type="button" onclick="markNavNotifRead(event, {{ $notif->id }})" title="Mark as read" aria-label="Mark as read" class="nav-notif-btn nav-notif-read"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></button>
    <button type="button" onclick="deleteNavNotif(event, {{ $notif->id }})" title="Delete" aria-label="Delete notification" class="nav-notif-btn nav-notif-del"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg></button>
  </div>
</div>
