<!-- HEADER / NAVBAR -->
<header class="sticky top-0 z-[100] border-b border-gold-deep/20 shadow-sm bg-cream">
  <div class="flex justify-between items-center px-[5%] lg:px-[8%] py-[12px] md:py-[14px] gap-5">
    <!-- Logo -->
    <div class="shrink-0">
      <a href="{{ route('home') }}" class="no-underline flex items-center gap-3.5 group">
        <img src="{{ asset('images/NEWLOGO.png') }}" alt="LorDane's Place Logo" style="height: 66px; width: auto; object-fit: contain; transition: 0.3s;" class="group-hover:scale-105">
        <div>
          <h2 class="text-gold-deep font-heading text-[22px] lg:text-[26px] tracking-tight leading-none">LorDane's Place</h2>
          <p class="text-[10px] tracking-[2.5px] text-warm-black/50 mt-1 uppercase">PLACE • EVENT VENUE</p>
        </div>
      </a>
    </div>

    <!-- Desktop Navigation (hidden on mobile) -->
    <nav class="hidden lg:flex items-center gap-1 relative">
      <a href="{{ route('home') }}" class="nav-link mx-1.5 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep no-underline text-warm-black {{ request()->is('/') ? 'nav-link-active' : '' }}">Home</a>
      <a href="{{ route('home') }}#about" class="nav-link mx-1.5 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep no-underline text-warm-black">About</a>
      <a href="{{ url('/contact') }}" class="nav-link mx-1.5 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep no-underline text-warm-black {{ request()->is('contact') ? 'nav-link-active' : '' }}">Contact Us</a>

      <!-- Discover Dropdown -->
      <div class="dropdown relative" onclick="toggleDropdown(this)">
        <div class="nav-link mx-1.5 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep cursor-pointer no-underline text-warm-black flex items-center gap-1.5 {{ request()->is('discover*') ? 'nav-link-active' : '' }}">
          <span>Discover</span>
          <span class="dropdown-chevron text-[14px]">▾</span>
        </div>
        <div class="dropdown-menu hidden absolute top-full left-1/2 -translate-x-1/2 border border-gold-deep/20 rounded-lg shadow-md min-w-[200px] py-2 z-[999] bg-cream">
          <a href="{{ route('discover') }}" class="block px-5 py-3 text-warm-black/80 text-[14px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 no-underline border-b border-gold-deep/10">Discover Overview</a>
          <a href="{{ route('discover') }}#gallery" class="block px-5 py-3 text-warm-black/80 text-[14px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 no-underline">Gallery</a>
          <a href="{{ route('discover') }}#virtual-tour" class="block px-5 py-3 text-warm-black/80 text-[14px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 no-underline">360° Virtual Tour</a>
          <a href="{{ route('discover') }}#packages" class="block px-5 py-3 text-warm-black/80 text-[14px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 no-underline">Packages</a>
        </div>
      </div>

      @auth
        <div class="dropdown relative" onclick="toggleDropdown(this)">
          <div class="relative flex items-center justify-center w-9 h-9 rounded-full transition-colors duration-300 cursor-pointer hover:bg-gold-deep/10">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-warm-black/70"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            <span id="navNotifBadge" class="absolute top-0 right-0 min-w-[16px] h-4 px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center leading-none" style="{{ $navUnreadCount > 0 ? '' : 'display: none;' }}">{{ $navUnreadCount > 9 ? '9+' : $navUnreadCount }}</span>
          </div>
          <div id="navNotifMenu" class="dropdown-menu hidden absolute top-full right-0 mt-2 border border-gold-deep/20 rounded-lg shadow-xl w-[340px] max-h-[420px] overflow-y-auto z-[999] bg-cream"
               data-notif-base="{{ url('notifications') }}" data-csrf="{{ csrf_token() }}">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gold-deep/10 sticky top-0 bg-cream">
              <span class="text-[13px] font-bold text-warm-black uppercase tracking-wide">Notifications</span>
              <button type="button" id="navMarkAllBtn" onclick="markAllNavNotifsRead(event)" class="text-[11px] text-gold-deep font-semibold bg-transparent border-none cursor-pointer hover:underline" style="{{ $navUnreadCount > 0 ? '' : 'display: none;' }}">Mark all read</button>
            </div>
            <div id="navNotifList">
              @foreach($navNotifications as $notif)
                <div class="nav-notif-item flex items-start gap-2 px-4 py-3 border-b border-gold-deep/10 bg-gold-deep/10 transition-colors" data-notif-id="{{ $notif->id }}">
                  <a href="{{ route('notifications.open', $notif->id) }}" class="block flex-1 no-underline">
                    <p class="text-[13px] font-bold text-warm-black m-0">{{ $notif->title }}</p>
                    <p class="text-[12px] text-warm-black/70 m-0 mt-1" style="white-space: pre-line;">{{ \Illuminate\Support\Str::limit($notif->message, 90) }}</p>
                    <p class="text-[10px] text-gold-deep/80 m-0 mt-1 uppercase tracking-wide">{{ $notif->created_at->diffForHumans() }}</p>
                  </a>
                  <button type="button" onclick="markNavNotifRead(event, {{ $notif->id }})" title="Mark as read" class="shrink-0 w-6 h-6 rounded-full border border-gold-deep/30 bg-transparent text-gold-deep text-[12px] leading-none cursor-pointer hover:bg-gold-deep hover:text-white transition-colors">&check;</button>
                </div>
              @endforeach
            </div>
            <p id="navNotifEmpty" class="px-4 py-6 text-center text-[13px] text-warm-black/50 m-0" style="{{ $navUnreadCount > 0 ? 'display: none;' : '' }}">No new notifications.</p>
            <a href="{{ route('notifications.index') }}" class="block text-center px-4 py-3 text-[12px] font-bold text-gold-deep uppercase tracking-wide no-underline hover:bg-gold-deep/5 border-t border-gold-deep/10">View All Notifications</a>
          </div>
        </div>

        <div class="dropdown relative" onclick="toggleDropdown(this)">
          <div class="flex items-center gap-2 px-2.5 py-1.5 rounded transition-all duration-300 cursor-pointer group">
            <div class="w-8 h-8 rounded-full bg-gold-deep flex items-center justify-center shrink-0 shadow-sm border border-gold-deep/20 group-hover:scale-105 transition-transform">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <span class="text-gold-deep font-bold text-[15px] group-hover:text-gold-mid transition-colors">{{ Auth::user()->name }}</span>
            <span class="dropdown-chevron text-gold-deep text-[14px]">▾</span>
          </div>
          <div class="dropdown-menu hidden absolute top-full right-0 mt-2 border border-gold-deep/20 rounded-lg shadow-xl min-w-[210px] py-2 z-[999] bg-cream">
            <a href="{{ route('profile') }}" class="block px-5 py-3 text-warm-black/80 text-[14px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 no-underline border-b border-gold-deep/10">
              Profile & Bookings
            </a>
            <a href="{{ route('terms') }}" class="block px-5 py-3 text-warm-black/80 text-[14px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 no-underline border-b border-gold-deep/10">
              Terms & Conditions
            </a>
            <div class="mt-1">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left block px-5 py-3 bg-transparent border-none text-warm-black text-[14px] cursor-pointer transition-all hover:bg-gold-deep/5">
                  Logout
                </button>
              </form>
            </div>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}" class="nav-link mx-2 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep no-underline text-warm-black">Login</a>
        <a href="{{ route('register') }}" class="nav-link mx-2 text-[16px] tracking-wide px-2.5 py-1.5 rounded border border-gold-deep/40 transition-all duration-300 hover:border-gold-deep hover:text-gold-deep no-underline text-warm-black">Sign Up</a>
      @endauth

      <a href="{{ route('booking') }}" class="bg-gold-deep text-white px-[22px] py-2.5 font-bold text-[16px] tracking-wide rounded-[3px] transition-all duration-300 hover:bg-gold-mid hover:-translate-y-px ml-2 no-underline">Book Now</a>
    </nav>

    <!-- Mobile Hamburger Button (visible on mobile only) -->
    <button id="mobileMenuBtn" onclick="toggleMobileMenu()" class="lg:hidden flex flex-col justify-center items-center w-10 h-10 rounded border border-gold-deep/30 bg-transparent cursor-pointer gap-[5px] p-2 transition-all hover:border-gold-deep">
      <span class="hamburger-line block w-5 h-[2px] bg-gold-deep transition-all duration-300"></span>
      <span class="hamburger-line block w-5 h-[2px] bg-gold-deep transition-all duration-300"></span>
      <span class="hamburger-line block w-5 h-[2px] bg-gold-deep transition-all duration-300"></span>
    </button>
  </div>

  <!-- Mobile Menu Drawer -->
  <div id="mobileMenu" class="lg:hidden hidden border-t border-gold-deep/15 bg-cream overflow-y-auto max-h-[calc(100vh-100px)]">
    <div class="flex flex-col px-[5%] py-6 gap-1">
      <div class="px-4 mb-6 flex items-center gap-3.5">
        <img src="{{ asset('images/NEWLOGO.png') }}" alt="Logo" style="height: 58px; width: auto; object-fit: contain;">
        <div>
          <p class="text-gold-deep font-heading text-[24px] leading-tight mb-0">LorDane's Place</p>
          <p class="text-[10px] tracking-[2px] text-warm-black/40 uppercase">Place • Event Venue</p>
        </div>
      </div>
      <a href="{{ route('home') }}" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline {{ request()->is('/') ? 'text-gold-deep font-bold' : '' }}">Home</a>
      <a href="{{ route('home') }}#about" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">About</a>
      <a href="{{ url('/contact') }}" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline {{ request()->is('contact') ? 'text-gold-deep font-bold' : '' }}">Contact Us</a>

      <!-- Discover Sub-links -->
      <div class="border-t border-gold-deep/10 mt-1 pt-1">
        <a href="{{ route('discover') }}" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline {{ request()->is('discover*') ? 'text-gold-deep font-bold' : '' }}">Discover</a>
        <a href="{{ route('discover') }}#gallery" class="block py-2.5 px-8 text-[16px] text-warm-black/90 rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">Gallery</a>
        <a href="{{ route('discover') }}#virtual-tour" class="block py-2.5 px-8 text-[16px] text-warm-black/90 rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">360° Virtual Tour</a>
        <a href="{{ route('discover') }}#packages" class="block py-2.5 px-8 text-[16px] text-warm-black/90 rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">Packages</a>
      </div>

      @auth
        <div class="border-t border-gold-deep/10 mt-1 pt-1">
          <p class="px-4 py-2 text-[12px] tracking-[2px] text-warm-black/50 font-bold">ACCOUNT</p>
          <a href="{{ route('notifications.index') }}" class="flex items-center justify-between py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">
            <span>Notifications</span>
            @if($navUnreadCount > 0)
              <span class="min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center leading-none">{{ $navUnreadCount > 9 ? '9+' : $navUnreadCount }}</span>
            @endif
          </a>
          <a href="{{ route('profile') }}" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">Profile & Bookings</a>
          <a href="{{ route('terms') }}" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">Terms & Conditions</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left py-3 px-4 text-[15px] text-warm-black bg-transparent border-none cursor-pointer rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5">Logout</button>
          </form>
        </div>
      @else
        <div class="border-t border-gold-deep/10 mt-1 pt-1 flex gap-3 px-4 py-3">
          <a href="{{ route('login') }}" class="flex-1 text-center py-2.5 text-[16px] text-warm-black border border-gold-deep/30 rounded transition-all hover:border-gold-deep hover:text-gold-deep no-underline">Login</a>
          <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 text-[16px] text-warm-black border border-gold-deep/40 rounded transition-all hover:border-gold-deep hover:text-gold-deep no-underline">Sign Up</a>
        </div>
      @endauth

      <div class="px-4 pt-2 pb-1">
        <a href="{{ route('booking') }}" class="block text-center bg-gold-deep text-white py-3 font-bold text-[16px] tracking-wide rounded-[3px] transition-all duration-300 hover:bg-gold-mid no-underline">Book Now</a>
      </div>
    </div>
  </div>
</header>

<script>
  function toggleDropdown(el) {
    const menu = el.querySelector('.dropdown-menu');
    const chevron = el.querySelector('.dropdown-chevron');
    const isHidden = menu.classList.contains('hidden');
    
    // Close all other dropdowns and reset their arrows
    document.querySelectorAll('.dropdown').forEach(function(d) {
      d.querySelector('.dropdown-menu').classList.add('hidden');
      const c = d.querySelector('.dropdown-chevron');
      if (c) c.textContent = '▾';
    });

    // Toggle the clicked one
    if (isHidden) {
      menu.classList.remove('hidden');
      if (chevron) chevron.textContent = '▴';
    }
  }

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
      document.querySelectorAll('.dropdown').forEach(function(d) {
        d.querySelector('.dropdown-menu').classList.add('hidden');
        const c = d.querySelector('.dropdown-chevron');
        if (c) c.textContent = '▾';
      });
    }
  });

  // ---- Notification bell: mark-as-read without reloading the page ----
  function navNotifRefreshUi(unreadCount) {
    const badge = document.getElementById('navNotifBadge');
    const markAllBtn = document.getElementById('navMarkAllBtn');
    const empty = document.getElementById('navNotifEmpty');
    const remaining = document.querySelectorAll('#navNotifList .nav-notif-item').length;

    if (badge) {
      if (unreadCount > 0) {
        badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
        badge.style.display = 'flex';
      } else {
        badge.style.display = 'none';
      }
    }
    if (markAllBtn) markAllBtn.style.display = unreadCount > 0 ? '' : 'none';
    if (empty) empty.style.display = remaining === 0 ? '' : 'none';
  }

  function navNotifPost(path, onDone) {
    const menu = document.getElementById('navNotifMenu');
    if (!menu) return;
    fetch(menu.dataset.notifBase + path, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': menu.dataset.csrf,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(function (r) { return r.ok ? r.json() : Promise.reject(r.status); })
      .then(function (data) { onDone(data.unread_count); })
      .catch(function () { window.location.reload(); });
  }

  function markNavNotifRead(e, id) {
    e.preventDefault();
    e.stopPropagation();
    navNotifPost('/' + id + '/read', function (unreadCount) {
      const item = document.querySelector('#navNotifList .nav-notif-item[data-notif-id="' + id + '"]');
      if (item) item.remove();
      navNotifRefreshUi(unreadCount);
    });
  }

  function markAllNavNotifsRead(e) {
    e.preventDefault();
    e.stopPropagation();
    navNotifPost('/read-all', function (unreadCount) {
      const list = document.getElementById('navNotifList');
      if (list) list.innerHTML = '';
      navNotifRefreshUi(unreadCount);
    });
  }

  function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const btn = document.getElementById('mobileMenuBtn');
    const lines = btn.querySelectorAll('.hamburger-line');
    menu.classList.toggle('hidden');
    // Animate hamburger to X
    if (!menu.classList.contains('hidden')) {
      lines[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
      lines[1].style.opacity = '0';
      lines[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
    } else {
      lines[0].style.transform = '';
      lines[1].style.opacity = '';
      lines[2].style.transform = '';
    }
  }
</script>
