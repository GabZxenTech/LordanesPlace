{{-- ADMIN SIDEBAR --}}
<aside style="position: fixed; top: 0; left: 0; height: 100vh; width: 260px; background: #2c1a0e; font-family: 'Jost', sans-serif; display: flex; flex-direction: column; z-index: 200; box-shadow: 4px 0 15px rgba(0,0,0,0.1);">

  {{-- Brand --}}
  <div style="flex-shrink: 0;">
    <div style="padding: 32px 28px 24px; border-bottom: 1px solid rgba(201,168,76,0.15);">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 24px; font-weight: 700; color: #c9a84c; margin: 0; line-height: 1.2;">LorDane's Place</h1>
      <p style="font-size: 11px; letter-spacing: 3px; color: #8a6a40; margin: 6px 0 0; text-transform: uppercase; font-weight: 600;">ADMIN PANEL</p>
    </div>
  </div>

    {{-- Navigation --}}
    <div style="padding: 24px 0; flex: 1; overflow-y: auto; overflow-x: hidden;">
      <p style="font-size: 10px; letter-spacing: 3px; color: #8a6a40; padding: 0 28px; margin: 0 0 14px; text-transform: uppercase; font-weight: 700;">Main Menu</p>

      <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 14px; padding: 13px 28px; font-size: 15px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.edit') ? 'color: #c9a84c; background: #3d2312; border-left: 3px solid #c9a84c;' : 'color: #b89060; border-left: 3px solid transparent;' }}" onmouseover="if(!this.classList.contains('active-nav')){this.style.background='#3d2312'; this.style.color='#c9a84c';}" onmouseout="if(!this.classList.contains('active-nav')){this.style.background='transparent'; this.style.color='#b89060';}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Users
      </a>

      <a href="{{ route('admin.packages.index') }}" style="display: flex; align-items: center; gap: 14px; padding: 13px 28px; font-size: 15px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request()->routeIs('admin.packages.*') ? 'color: #c9a84c; background: #3d2312; border-left: 3px solid #c9a84c;' : 'color: #b89060; border-left: 3px solid transparent;' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg>
        Packages
      </a>

      <a href="{{ route('admin.schedule') }}" style="display: flex; align-items: center; gap: 14px; padding: 13px 28px; font-size: 15px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request()->routeIs('admin.schedule') ? 'color: #c9a84c; background: #3d2312; border-left: 3px solid #c9a84c;' : 'color: #b89060; border-left: 3px solid transparent;' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Schedule
      </a>

      <div class="admin-notif-dropdown" style="position: relative;">
        <div onclick="toggleAdminNotifDropdown(event)" style="display: flex; align-items: center; gap: 14px; padding: 13px 28px; font-size: 15px; font-weight: 500; cursor: pointer; transition: all 0.2s; position: relative; color: #b89060; border-left: 3px solid transparent;" onmouseover="this.style.background='#3d2312'; this.style.color='#c9a84c';" onmouseout="this.style.background='transparent'; this.style.color='#b89060';">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
          Notifications
          <span id="adminNotifBadge" style="position: absolute; right: 20px; {{ $adminUnreadCount > 0 ? 'display: inline-flex;' : 'display: none;' }} align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: #e74c3c; color: white; font-size: 10px; font-weight: 700;">{{ $adminUnreadCount > 9 ? '9+' : $adminUnreadCount }}</span>
        </div>
        <div id="adminNotifPanel" data-notif-base="{{ url('admin/notifications') }}" data-csrf="{{ csrf_token() }}" style="display: none; position: absolute; left: 100%; top: 0; margin-left: 6px; width: 340px; max-height: 440px; overflow-y: auto; background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); z-index: 500;">
          <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
            <span style="font-size: 12px; font-weight: 800; color: #2c1a0e; text-transform: uppercase; letter-spacing: 1px;">Notifications</span>
            <button type="button" id="adminMarkAllBtn" onclick="markAllAdminNotifsRead(event)" style="font-size: 11px; color: #c9a84c; font-weight: 700; background: transparent; border: none; cursor: pointer; {{ $adminUnreadCount > 0 ? '' : 'display: none;' }}" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Mark all read</button>
          </div>
          <div id="adminNotifList">
            @foreach($adminNotifications as $notif)
              <div class="admin-notif-item" data-notif-id="{{ $notif->id }}" style="display: flex; align-items: flex-start; gap: 8px; padding: 12px 18px; border-bottom: 1px solid #e8dcc8; background: rgba(201,168,76,0.12);">
                <a href="{{ route('admin.notifications.open', $notif->id) }}" style="display: block; flex: 1; text-decoration: none;">
                  <p style="font-size: 13px; font-weight: 700; color: #2c1a0e; margin: 0;">{{ $notif->title }}</p>
                  <p style="font-size: 12px; color: #8a6a40; margin: 4px 0 0; white-space: pre-line;">{{ \Illuminate\Support\Str::limit($notif->message, 90) }}</p>
                  <p style="font-size: 10px; color: #c9a84c; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.5px;">{{ $notif->created_at->diffForHumans() }}</p>
                </a>
                <button type="button" onclick="markAdminNotifRead(event, {{ $notif->id }})" title="Mark as read" style="flex-shrink: 0; width: 24px; height: 24px; border-radius: 50%; border: 1px solid #d4c4a0; background: transparent; color: #c9a84c; font-size: 12px; line-height: 1; cursor: pointer;" onmouseover="this.style.background='#c9a84c'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#c9a84c';">&check;</button>
              </div>
            @endforeach
          </div>
          <p id="adminNotifEmpty" style="padding: 24px 18px; text-align: center; font-size: 13px; color: #8a6a40; margin: 0; {{ $adminUnreadCount > 0 ? 'display: none;' : '' }}">No new notifications.</p>
          <a href="{{ route('admin.notifications.index') }}" style="display: block; text-align: center; padding: 12px 18px; font-size: 12px; font-weight: 700; color: #c9a84c; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; border-top: 1px solid #e8dcc8;">View All Notifications</a>
        </div>
      </div>

      <a href="{{ route('admin.reschedules.index') }}" style="display: flex; align-items: center; gap: 14px; padding: 13px 28px; font-size: 15px; font-weight: 500; text-decoration: none; transition: all 0.2s; position: relative; {{ request()->routeIs('admin.reschedules.*') ? 'color: #c9a84c; background: #3d2312; border-left: 3px solid #c9a84c;' : 'color: #b89060; border-left: 3px solid transparent;' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
        Reschedules
        @php $pendingRescheduleCount = \App\Models\Booking::where('reschedule_status', 'pending')->count(); @endphp
        @if($pendingRescheduleCount > 0)
          <span style="position: absolute; right: 20px; display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: #e74c3c; color: white; font-size: 10px; font-weight: 700;">{{ $pendingRescheduleCount }}</span>
        @endif
      </a>

      <a href="{{ route('admin.visits.index') }}" style="display: flex; align-items: center; gap: 14px; padding: 13px 28px; font-size: 15px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request()->routeIs('admin.visits.*') ? 'color: #c9a84c; background: #3d2312; border-left: 3px solid #c9a84c;' : 'color: #b89060; border-left: 3px solid transparent;' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Visits
      </a>

      <a href="{{ route('admin.chat.index') }}" style="display: flex; align-items: center; gap: 14px; padding: 13px 28px; font-size: 15px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request()->routeIs('admin.chat.*') ? 'color: #c9a84c; background: #3d2312; border-left: 3px solid #c9a84c;' : 'color: #b89060; border-left: 3px solid transparent;' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Chat
      </a>

      <a href="{{ route('admin.reports.index') }}" style="display: flex; align-items: center; gap: 14px; padding: 13px 28px; font-size: 15px; font-weight: 500; text-decoration: none; transition: all 0.2s; {{ request()->routeIs('admin.reports.*') ? 'color: #c9a84c; background: #3d2312; border-left: 3px solid #c9a84c;' : 'color: #b89060; border-left: 3px solid transparent;' }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><line x1="10" y1="9" x2="10" y2="9"/></svg>
        Reports
      </a>
    </div>

  {{-- Footer --}}
  <div style="padding: 20px 28px 28px; border-top: 1px solid rgba(201,168,76,0.12); flex-shrink: 0;">
    <p style="font-size: 13px; color: #8a6a40; margin: 0 0 14px;">Signed in as <span style="color: #c9a84c; font-weight: 700;">{{ Auth::user()->name }}</span></p>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" style="width: 100%; padding: 12px; border: 1px solid #8a6a40; background: transparent; color: #b89060; font-size: 14px; font-weight: 700; letter-spacing: 1px; cursor: pointer; transition: all 0.3s; border-radius: 4px; font-family: 'Jost', sans-serif;" onmouseover="this.style.background='#c9a84c'; this.style.color='#2c1a0e'; this.style.borderColor='#c9a84c';" onmouseout="this.style.background='transparent'; this.style.color='#b89060'; this.style.borderColor='#8a6a40';">Logout</button>
    </form>
  </div>
</aside>

{{-- Spacer to push content right --}}
<div style="width: 260px; flex-shrink: 0;"></div>

<script>
  function toggleAdminNotifDropdown(e) {
    e.stopPropagation();
    const panel = document.getElementById('adminNotifPanel');
    panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
  }

  document.addEventListener('click', function(e) {
    const wrapper = document.querySelector('.admin-notif-dropdown');
    const panel = document.getElementById('adminNotifPanel');
    if (wrapper && panel && !wrapper.contains(e.target)) {
      panel.style.display = 'none';
    }
  });

  // ---- Mark notifications read without reloading the page ----
  function adminNotifRefreshUi(unreadCount) {
    const badge = document.getElementById('adminNotifBadge');
    const markAllBtn = document.getElementById('adminMarkAllBtn');
    const empty = document.getElementById('adminNotifEmpty');
    const remaining = document.querySelectorAll('#adminNotifList .admin-notif-item').length;

    if (badge) {
      if (unreadCount > 0) {
        badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
        badge.style.display = 'inline-flex';
      } else {
        badge.style.display = 'none';
      }
    }
    if (markAllBtn) markAllBtn.style.display = unreadCount > 0 ? '' : 'none';
    if (empty) empty.style.display = remaining === 0 ? '' : 'none';
  }

  function adminNotifPost(path, onDone) {
    const panel = document.getElementById('adminNotifPanel');
    if (!panel) return;
    fetch(panel.dataset.notifBase + path, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': panel.dataset.csrf,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(function (r) { return r.ok ? r.json() : Promise.reject(r.status); })
      .then(function (data) { onDone(data.unread_count); })
      .catch(function () { window.location.reload(); });
  }

  function markAdminNotifRead(e, id) {
    e.preventDefault();
    e.stopPropagation();
    adminNotifPost('/' + id + '/read', function (unreadCount) {
      const item = document.querySelector('#adminNotifList .admin-notif-item[data-notif-id="' + id + '"]');
      if (item) item.remove();
      adminNotifRefreshUi(unreadCount);
    });
  }

  function markAllAdminNotifsRead(e) {
    e.preventDefault();
    e.stopPropagation();
    adminNotifPost('/read-all', function (unreadCount) {
      const list = document.getElementById('adminNotifList');
      if (list) list.innerHTML = '';
      adminNotifRefreshUi(unreadCount);
    });
  }
</script>
