<!-- HEADER / NAVBAR -->
<header class="sticky top-0 z-50 border-b border-gold-deep/20 shadow-sm bg-cream">
  <div class="flex justify-between items-center px-[5%] lg:px-[8%] py-[18px] gap-5">
    <!-- Logo -->
    <div class="shrink-0">
      <a href="{{ route('home') }}" class="no-underline flex items-center gap-3 group">
        <img src="{{ asset('images/LOGO_LORDANES.png') }}" alt="LorDane's Place Logo" style="height: 60px; width: auto; object-fit: contain; transition: 0.3s;" class="group-hover:scale-105">
        <div>
          <h2 class="text-gold-deep font-heading text-[20px] lg:text-[26px] tracking-tight leading-none">LorDane's Place</h2>
          <p class="text-[9px] tracking-[2px] text-warm-black/50 mt-1 uppercase">PLACE • EVENT VENUE</p>
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
          <a href="{{ url('/tour') }}" class="block px-5 py-3 text-warm-black/80 text-[14px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 no-underline">360° Virtual Tour</a>
          <a href="{{ route('discover') }}#packages" class="block px-5 py-3 text-warm-black/80 text-[14px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 no-underline">Packages</a>
        </div>
      </div>

      @auth
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
  <div id="mobileMenu" class="lg:hidden hidden border-t border-gold-deep/15 bg-cream">
    <div class="flex flex-col px-[5%] py-6 gap-1">
      <div class="px-4 mb-6 flex items-center gap-3">
        <img src="{{ asset('images/LOGO_LORDANES.png') }}" alt="Logo" style="height: 55px; width: auto; object-fit: contain;">
        <div>
          <p class="text-gold-deep font-heading text-[18px] leading-tight mb-0">LorDane's Place</p>
          <p class="text-[8px] tracking-[2px] text-warm-black/40 uppercase">Place • Event Venue</p>
        </div>
      </div>
      <a href="{{ route('home') }}" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline {{ request()->is('/') ? 'text-gold-deep font-bold' : '' }}">Home</a>
      <a href="{{ route('home') }}#about" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">About</a>
      <a href="{{ url('/contact') }}" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline {{ request()->is('contact') ? 'text-gold-deep font-bold' : '' }}">Contact Us</a>

      <!-- Discover Sub-links -->
      <div class="border-t border-gold-deep/10 mt-1 pt-1">
        <a href="{{ route('discover') }}" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline {{ request()->is('discover*') ? 'text-gold-deep font-bold' : '' }}">Discover</a>
        <a href="{{ route('discover') }}#gallery" class="block py-2.5 px-8 text-[16px] text-warm-black/90 rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">Gallery</a>
        <a href="{{ url('/tour') }}" class="block py-2.5 px-8 text-[16px] text-warm-black/90 rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">360° Virtual Tour</a>
        <a href="{{ route('discover') }}#packages" class="block py-2.5 px-8 text-[16px] text-warm-black/90 rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">Packages</a>
      </div>

      @auth
        <div class="border-t border-gold-deep/10 mt-1 pt-1">
          <p class="px-4 py-2 text-[12px] tracking-[2px] text-warm-black/50 font-bold">ACCOUNT</p>
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
