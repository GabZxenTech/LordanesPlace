<!-- FOOTER -->
<footer class="bg-warm-black border-t border-gold-deep/30 px-[5%] lg:px-[8%] pt-[60px] pb-[30px]">
  <div class="grid grid-cols-1 md:grid-cols-[2fr_1fr] gap-10 md:gap-[60px] mb-10">
    <div>
      <div class="flex items-center gap-3.5 mb-3.5">
        <img src="{{ asset('images/NEWLOGO.png') }}" alt="LorDane's Place Logo" style="height: 54px; width: auto; object-fit: contain;">
        <div>
          <h3 class="font-heading text-[24px] text-gold-deep leading-none mb-1">LorDane's Place</h3>
          <p class="text-[11px] tracking-[2px] text-gold-light/90 font-bold uppercase mb-0">GOLDEN TAG EVENTS PRODUCTIONS</p>
        </div>
      </div>
      <p class="text-[15px] text-off-white/70 font-normal leading-[1.8] mb-6 max-w-[500px]">An elegant event venue in Santa Maria, Bulacan — where every celebration becomes a lasting memory.</p>
      <div class="flex items-center gap-4">
        <a href="https://www.facebook.com/LorDanesPlace" target="_blank" rel="noopener noreferrer" title="LorDane's Place Facebook" class="w-11 h-11 rounded-full border border-gold-deep/30 flex items-center justify-center text-off-white/70 hover:bg-gold-deep hover:text-warm-black hover:border-gold-deep transition-all duration-300">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
          </svg>
        </a>
        <a href="https://www.facebook.com/share/1Cs6GNFgjX/" target="_blank" rel="noopener noreferrer" title="Golden Tag Events Productions Facebook" class="w-11 h-11 rounded-full border border-gold-deep/30 flex items-center justify-center text-off-white/70 hover:bg-gold-deep hover:text-warm-black hover:border-gold-deep transition-all duration-300">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
          </svg>
        </a>
      </div>
    </div>
    <div>
      <h4 class="text-[15px] tracking-[2px] text-gold-light font-bold mb-4 uppercase">Quick Links</h4>
      <div class="grid grid-cols-2 gap-x-4 gap-y-2.5">
        <a href="{{ url('/') }}" class="text-off-white/80 text-[15px] font-normal transition-colors hover:text-gold-light no-underline">Home</a>
        <a href="{{ route('home') }}#about" class="text-off-white/80 text-[15px] font-normal transition-colors hover:text-gold-light no-underline">About</a>
        <a href="{{ route('discover') }}#gallery" class="text-off-white/80 text-[15px] font-normal transition-colors hover:text-gold-light no-underline">Gallery</a>
        <a href="{{ route('discover') }}#virtual-tour" class="text-off-white/80 text-[15px] font-normal transition-colors hover:text-gold-light no-underline">Virtual Tour</a>
        <a href="{{ route('discover') }}#packages" class="text-off-white/80 text-[15px] font-normal transition-colors hover:text-gold-light no-underline">Packages</a>
        <a href="{{ url('/contact') }}" class="text-off-white/80 text-[15px] font-normal transition-colors hover:text-gold-light no-underline">Contact Us Page</a>
      </div>
    </div>
  </div>
  <div class="border-t border-gold-deep/15 pt-6 text-center text-[15px] text-off-white/50 tracking-wide">
    <p>&copy; 2026 <span class="text-gold-deep">LorDane's Place</span>. All rights reserved.</p>
  </div>
</footer>
