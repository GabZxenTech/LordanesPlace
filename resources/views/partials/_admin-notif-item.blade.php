<div class="admin-notif-item" data-notif-id="{{ $notif->id }}" style="display: flex; align-items: flex-start; gap: 8px; padding: 12px 18px; border-bottom: 1px solid #e8dcc8; background: rgba(201,168,76,0.12);">
  <a href="{{ route('admin.notifications.open', $notif->id) }}" style="display: block; flex: 1; text-decoration: none;">
    <p style="font-size: 13px; font-weight: 700; color: #2c1a0e; margin: 0;">{{ $notif->title }}</p>
    <p style="font-size: 12px; color: #8a6a40; margin: 4px 0 0; white-space: pre-line;">{{ \Illuminate\Support\Str::limit($notif->message, 90) }}</p>
    <p style="font-size: 10px; color: #c9a84c; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $notif->created_at->diffForHumans() }}</p>
  </a>
  <button type="button" onclick="markAdminNotifRead(event, {{ $notif->id }})" title="Mark as read" style="flex-shrink: 0; width: 24px; height: 24px; border-radius: 50%; border: 1px solid #d4c4a0; background: transparent; color: #c9a84c; cursor: pointer; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='#c9a84c'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#c9a84c';"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></button>
</div>
