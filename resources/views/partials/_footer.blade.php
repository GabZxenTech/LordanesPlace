<!-- FOOTER -->
<footer class="bg-warm-black border-t border-gold-deep/30 pt-6 pb-4 px-[5%] lg:px-[8%]">
  <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-5">

    <!-- Left Column -->
    <div class="flex flex-col items-start text-left max-w-[450px] w-full">
      <div class="flex items-center gap-3 mb-3">
        <img src="{{ asset('images/NEWLOGO.png') }}" alt="LorDane's Place Logo" style="height: 48px; width: auto; object-fit: contain;">
        <h3 class="font-heading text-[24px] text-gold-deep leading-none m-0">LorDane's Place</h3>
      </div>
      <p class="text-[14px] text-off-white/80 font-normal leading-[1.8] mb-3">An elegant event venue in Santa Maria, Bulacan — where every celebration becomes a lasting memory.</p>

      <div class="flex flex-col gap-2 w-full">
        <a href="https://www.facebook.com/LorDanesPlace" target="_blank" rel="noopener noreferrer" title="LorDane's Place Facebook" class="group flex items-center gap-3 w-fit text-off-white/70 hover:text-gold-deep transition-colors duration-300 no-underline">
          <div class="w-7 h-7 rounded-full border border-gold-deep/40 flex items-center justify-center group-hover:bg-gold-deep group-hover:text-warm-black group-hover:border-gold-deep transition-all duration-300">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
            </svg>
          </div>
          <span class="text-[14px]">LorDane's Place</span>
        </a>
        <a href="https://www.facebook.com/share/1Cs6GNFgjX/" target="_blank" rel="noopener noreferrer" title="Golden Tag Events Productions Facebook" class="group flex items-center gap-3 w-fit text-off-white/70 hover:text-gold-deep transition-colors duration-300 no-underline">
          <div class="w-7 h-7 rounded-full border border-gold-deep/40 flex items-center justify-center group-hover:bg-gold-deep group-hover:text-warm-black group-hover:border-gold-deep transition-all duration-300">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
            </svg>
          </div>
          <span class="text-[14px]">Golden Tag Events Production</span>
        </a>
      </div>
    </div>

    <!-- Right Column -->
    <div class="flex flex-col items-start text-left mt-2 md:mt-4 shrink-0">
      <h4 class="text-[14px] tracking-[1.5px] text-gold-deep font-bold mb-3 uppercase">QUICK LINKS</h4>
      <div class="grid grid-cols-2 gap-x-12 gap-y-2 text-left">
        <a href="{{ url('/') }}" class="text-off-white/80 text-[14px] font-normal transition-colors hover:text-gold-light no-underline">Home</a>
        <a href="{{ route('discover') }}#virtual-tour" class="text-off-white/80 text-[14px] font-normal transition-colors hover:text-gold-light no-underline">Virtual Tour</a>
        <a href="{{ route('home') }}#about" class="text-off-white/80 text-[14px] font-normal transition-colors hover:text-gold-light no-underline">About</a>
        <a href="{{ route('discover') }}#packages" class="text-off-white/80 text-[14px] font-normal transition-colors hover:text-gold-light no-underline">Packages</a>
        <a href="{{ route('discover') }}#gallery" class="text-off-white/80 text-[14px] font-normal transition-colors hover:text-gold-light no-underline">Gallery</a>
        <a href="{{ url('/contact') }}" class="text-off-white/80 text-[14px] font-normal transition-colors hover:text-gold-light no-underline">Contact Us Page</a>
      </div>
    </div>
  </div>

  <!-- Copyright -->
  <div class="border-t border-gold-deep/20 pt-3 text-center text-[13px] text-off-white/50 tracking-wide">
    <p class="m-0">&copy; 2026 <span class="text-gold-deep">LorDane's Place</span>. All rights reserved.</p>
  </div>
</footer>
