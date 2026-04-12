<!-- HEADER / NAVBAR -->
<header class="sticky top-0 z-50 border-b border-gold-deep/20 shadow-sm bg-cream">
  <div class="flex justify-between items-center px-[5%] lg:px-[8%] py-[18px] gap-5">
    <!-- Logo -->
    <div class="shrink-0">
      <a href="{{ route('home') }}" class="no-underline">
        <h2 class="text-gold-deep font-heading text-[22px] lg:text-[24px] tracking-wide">LorDane's Place</h2>
        <p class="text-[10px] tracking-[3px] text-warm-black/50 mt-0.5">PLACE • EVENT VENUE</p>
      </a>
    </div>

    <!-- Desktop Navigation (hidden on mobile) -->
    <nav class="hidden lg:flex items-center gap-1 relative">
      <a href="{{ route('home') }}" class="nav-link mx-1.5 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep no-underline text-warm-black {{ request()->is('/') ? 'nav-link-active' : '' }}">Home</a>
      <a href="{{ route('home') }}#about" class="nav-link mx-1.5 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep no-underline text-warm-black">About</a>
      <a href="{{ url('/contact') }}" class="nav-link mx-1.5 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep no-underline text-warm-black {{ request()->is('contact') ? 'nav-link-active' : '' }}">Contact Us</a>

      <!-- Discover Dropdown -->
      <div class="dropdown relative" onclick="this.querySelector('.dropdown-menu').classList.toggle('hidden')">
        <a href="javascript:void(0)" class="nav-link mx-1.5 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep cursor-pointer no-underline text-warm-black {{ request()->is('discover*') ? 'nav-link-active' : '' }}">Discover ▾</a>
        <div class="dropdown-menu hidden absolute top-full left-1/2 -translate-x-1/2 border border-gold-deep/20 rounded-lg shadow-md min-w-[190px] py-2 z-[999] bg-cream">
          <a href="{{ route('discover') }}" class="block px-[18px] py-2.5 text-warm-black/80 text-[16px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 whitespace-nowrap no-underline font-bold border-b border-gold-deep/10">Discover Overview</a>
          <a href="{{ route('discover') }}#gallery" class="block px-[18px] py-2.5 text-warm-black/80 text-[16px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 whitespace-nowrap no-underline">Gallery</a>
          <a href="{{ url('/tour') }}" class="block px-[18px] py-2.5 text-warm-black/80 text-[16px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 whitespace-nowrap no-underline">360° Virtual Tour</a>
          <a href="{{ route('discover') }}#packages" class="block px-[18px] py-2.5 text-warm-black/80 text-[16px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 whitespace-nowrap no-underline">Packages</a>
        </div>
      </div>

      @auth
        <div class="dropdown relative" onclick="this.querySelector('.dropdown-menu').classList.toggle('hidden')">
          <a href="javascript:void(0)" class="nav-link mx-1.5 text-[16px] tracking-wide px-2.5 py-1.5 rounded transition-colors duration-300 hover:text-gold-deep cursor-pointer no-underline text-warm-black">
            👤 {{ Auth::user()->name }} ▾
          </a>
          <div class="dropdown-menu hidden absolute top-full left-1/2 -translate-x-1/2 border border-gold-deep/20 rounded-lg shadow-md min-w-[190px] py-2 z-[999] bg-cream">
            <a href="{{ route('my.bookings') }}" class="block px-[18px] py-2.5 text-warm-black/80 text-[16px] transition-all hover:text-gold-deep hover:bg-gold-deep/5 no-underline">My Bookings</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left px-[18px] py-2.5 bg-transparent border-none text-warm-black/80 text-[16px] cursor-pointer transition-all hover:text-gold-deep hover:bg-gold-deep/5">Logout</button>
            </form>
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
    <div class="flex flex-col px-[5%] py-4 gap-1">
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
          <a href="{{ route('my.bookings') }}" class="block py-3 px-4 text-[15px] text-warm-black rounded transition-colors hover:text-gold-deep hover:bg-gold-deep/5 no-underline">My Bookings</a>
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
