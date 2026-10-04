<div class="nav-notif-item flex items-start gap-2 px-4 py-3 border-b border-gold-deep/10 bg-gold-deep/10 transition-colors" data-notif-id="{{ $notif->id }}">
  <a href="{{ route('notifications.open', $notif->id) }}" class="block flex-1 no-underline">
    <p class="text-[13px] font-bold text-warm-black m-0">{{ $notif->title }}</p>
    <p class="text-[12px] text-warm-black/70 m-0 mt-1" style="white-space: pre-line;">{{ \Illuminate\Support\Str::limit($notif->message, 90) }}</p>
    <p class="text-[10px] text-gold-deep/80 m-0 mt-1 uppercase tracking-wide">{{ $notif->created_at->diffForHumans() }}</p>
  </a>
  <button type="button" onclick="markNavNotifRead(event, {{ $notif->id }})" title="Mark as read" class="shrink-0 w-6 h-6 rounded-full border border-gold-deep/30 bg-transparent text-gold-deep cursor-pointer hover:bg-gold-deep hover:text-white transition-colors flex items-center justify-center"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></button>
</div>
