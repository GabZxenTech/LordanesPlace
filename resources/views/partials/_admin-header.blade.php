<!-- ADMIN HEADER -->
<div class="bg-admin-dark/[0.98] border-b border-admin-gold/30 px-4 lg:px-10 py-4 flex flex-col lg:flex-row justify-between items-start lg:items-center sticky top-0 z-[100] gap-3 lg:gap-5">
  <div class="flex items-center gap-3">
    <img src="{{ asset('images/LOGO_LORDANES.png') }}" alt="Logo" style="height: 50px; width: auto; object-fit: contain;">
    <h1 class="font-[Playfair_Display,serif] text-admin-gold text-[20px] lg:text-[22px] whitespace-nowrap">LorDane's Admin</h1>
  </div>

  <div class="flex flex-wrap items-center gap-2 lg:gap-2.5">
    <a href="{{ route('admin.dashboard') }}" class="text-admin-cream-dim no-underline text-[15px] px-3 lg:px-4 py-2 rounded border border-admin-gold/30 transition-all duration-200 hover:bg-admin-gold hover:text-admin-dark hover:border-admin-gold {{ request()->routeIs('admin.dashboard') ? 'bg-admin-gold text-admin-dark border-admin-gold' : '' }}">Users</a>
    <a href="{{ route('admin.packages.index') }}" class="text-admin-cream-dim no-underline text-[15px] px-3 lg:px-4 py-2 rounded border border-admin-gold/30 transition-all duration-200 hover:bg-admin-gold hover:text-admin-dark hover:border-admin-gold {{ request()->routeIs('admin.packages.*') ? 'bg-admin-gold text-admin-dark border-admin-gold' : '' }}">Packages</a>
    <a href="{{ route('admin.schedule') }}" class="text-admin-cream-dim no-underline text-[15px] px-3 lg:px-4 py-2 rounded border border-admin-gold/30 transition-all duration-200 hover:bg-admin-gold hover:text-admin-dark hover:border-admin-gold {{ request()->routeIs('admin.schedule') ? 'bg-admin-gold text-admin-dark border-admin-gold' : '' }}">Schedule</a>
    <a href="{{ route('admin.reschedules.index') }}" class="text-admin-cream-dim no-underline text-[15px] px-3 lg:px-4 py-2 rounded border border-admin-gold/30 transition-all duration-200 hover:bg-admin-gold hover:text-admin-dark hover:border-admin-gold {{ request()->routeIs('admin.reschedules.*') ? 'bg-admin-gold text-admin-dark border-admin-gold' : '' }}">
      Reschedules
      @php $pendingRescheduleCount = \App\Models\Booking::where('reschedule_status', 'pending')->count(); @endphp
      @if($pendingRescheduleCount > 0)
        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-500 text-white text-[10px] font-bold ml-1">{{ $pendingRescheduleCount }}</span>
      @endif
    </a>
    <a href="{{ route('admin.chat.index') }}" class="text-admin-cream-dim no-underline text-[15px] px-3 lg:px-4 py-2 rounded border border-admin-gold/30 transition-all duration-200 hover:bg-admin-gold hover:text-admin-dark hover:border-admin-gold {{ request()->routeIs('admin.chat.*') ? 'bg-admin-gold text-admin-dark border-admin-gold' : '' }}">Chat</a>
  </div>

  <div class="flex items-center gap-3 lg:gap-4">
    <span class="text-[15px] text-admin-cream-dim whitespace-nowrap hidden sm:inline">Welcome, {{ Auth::user()->name }}</span>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="bg-transparent border border-admin-gold text-admin-gold px-4 py-2 rounded text-[15px] cursor-pointer transition-all duration-300 whitespace-nowrap hover:bg-admin-gold hover:text-admin-dark">Logout</button>
    </form>
  </div>
</div>
