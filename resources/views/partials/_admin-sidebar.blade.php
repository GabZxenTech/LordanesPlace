{{-- ADMIN SIDEBAR --}}
<aside style="position: fixed; top: 0; left: 0; height: 100vh; width: 260px; background: #2c1a0e; font-family: 'Jost', sans-serif; display: flex; flex-direction: column; justify-content: space-between; z-index: 200; box-shadow: 4px 0 15px rgba(0,0,0,0.1);">
  
  {{-- Brand --}}
  <div>
    <div style="padding: 32px 28px 24px; border-bottom: 1px solid rgba(201,168,76,0.15);">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 24px; font-weight: 700; color: #c9a84c; margin: 0; line-height: 1.2;">LorDane's Place</h1>
      <p style="font-size: 11px; letter-spacing: 3px; color: #8a6a40; margin: 6px 0 0; text-transform: uppercase; font-weight: 600;">ADMIN PANEL</p>
    </div>

    {{-- Navigation --}}
    <div style="padding: 24px 0;">
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
    </div>
  </div>

  {{-- Footer --}}
  <div style="padding: 20px 28px 28px; border-top: 1px solid rgba(201,168,76,0.12);">
    <p style="font-size: 13px; color: #8a6a40; margin: 0 0 14px;">Signed in as <span style="color: #c9a84c; font-weight: 700;">{{ Auth::user()->name }}</span></p>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" style="width: 100%; padding: 12px; border: 1px solid #8a6a40; background: transparent; color: #b89060; font-size: 14px; font-weight: 700; letter-spacing: 1px; cursor: pointer; transition: all 0.3s; border-radius: 4px; font-family: 'Jost', sans-serif;" onmouseover="this.style.background='#c9a84c'; this.style.color='#2c1a0e'; this.style.borderColor='#c9a84c';" onmouseout="this.style.background='transparent'; this.style.color='#b89060'; this.style.borderColor='#8a6a40';">Logout</button>
    </form>
  </div>
</aside>

{{-- Spacer to push content right --}}
<div style="width: 260px; flex-shrink: 0;"></div>
