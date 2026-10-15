<div class="admin-notif-item {{ $notif->is_read ? '' : 'is-unread' }}" data-notif-id="{{ $notif->id }}" data-sig="{{ $notif->id }}{{ $notif->is_read ? 'r' : 'u' }}">
  <a href="{{ route('admin.notifications.open', $notif->id) }}" style="display: block; flex: 1; text-decoration: none;">
    <p class="admin-notif-title"><span class="admin-notif-dot" aria-label="Unread"></span>{{ $notif->title }}</p>
    <p class="admin-notif-msg">{{ \Illuminate\Support\Str::limit($notif->message, 90) }}</p>
    <p style="font-size: 10px; color: #c9a84c; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $notif->created_at->diffForHumans() }}</p>
  </a>
  <div class="admin-notif-acts">
    <button type="button" onclick="markAdminNotifRead(event, {{ $notif->id }})" title="Mark as read" aria-label="Mark as read" class="admin-notif-btn admin-notif-read"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></button>
    <button type="button" onclick="deleteAdminNotif(event, {{ $notif->id }})" title="Delete" aria-label="Delete notification" class="admin-notif-btn admin-notif-del"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg></button>
  </div>
</div>
