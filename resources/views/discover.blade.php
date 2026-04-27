<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Discover — LorDane's Place</title>
<meta name="description" content="Browse gallery, take a 360° virtual tour, and explore packages at LorDane's Place event venue.">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<!-- PAGE HERO -->
<section class="discover-hero-bg h-[280px] md:h-[350px] flex justify-center items-center text-center text-white px-5 relative"
         style="background: linear-gradient(rgba(26,18,8,0.5), rgba(26,18,8,0.7)), url('{{ asset('images/LORDANES_BG.jpg') }}'); background-size: cover; background-position: center;">
  <div class="gold-divider absolute bottom-0 left-0 right-0"></div>
  <div>
    <p class="text-[11px] md:text-[12px] tracking-[4px] text-gold-light font-bold mb-3">EXPLORE LORDANE'S PLACE</p>
    <h1 class="font-heading text-[38px] md:text-[52px] font-bold text-white mb-2">Discover the <span class="italic text-gold-light">Venue</span></h1>
    <p class="text-white/70 text-[15px] md:text-[15px] font-normal max-w-[500px] mx-auto">Browse our gallery, take a 360° virtual tour, and explore our packages — all in one place.</p>
  </div>
</section>


<!-- GALLERY -->
<section class="py-[50px] md:py-[80px] px-[5%] lg:px-[8%] bg-off-white relative" id="gallery">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="text-center mb-8 md:mb-12">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">GALLERY</span>
    <h2 class="font-heading text-[28px] md:text-[40px] font-bold text-warm-black mb-2">Captured Moments of <span class="italic text-gold-deep">Elegance</span></h2>
    <p class="text-warm-black/90 text-[16px] md:text-[16px] font-normal">A glimpse of the celebrations held at LorDane's Place.</p>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
    <img src="{{ asset('images/g1.jpg') }}" alt="Event at LorDane's Place" class="w-full h-[220px] md:h-[260px] object-cover rounded-lg border border-gold-deep/15 transition-transform duration-300 hover:scale-[1.02]">
    <img src="{{ asset('images/g2.jpg') }}" alt="Event at LorDane's Place" class="w-full h-[220px] md:h-[260px] object-cover rounded-lg border border-gold-deep/15 transition-transform duration-300 hover:scale-[1.02]">
    <img src="{{ asset('images/g3.jpg') }}" alt="Event at LorDane's Place" class="w-full h-[220px] md:h-[260px] object-cover rounded-lg border border-gold-deep/15 transition-transform duration-300 hover:scale-[1.02]">
    <img src="{{ asset('images/g4.jpg') }}" alt="Event at LorDane's Place" class="w-full h-[220px] md:h-[260px] object-cover rounded-lg border border-gold-deep/15 transition-transform duration-300 hover:scale-[1.02]">
    <img src="{{ asset('images/g5.jpg') }}" alt="Event at LorDane's Place" class="w-full h-[220px] md:h-[260px] object-cover rounded-lg border border-gold-deep/15 transition-transform duration-300 hover:scale-[1.02]">
    <img src="{{ asset('images/g6.jpg') }}" alt="Event at LorDane's Place" class="w-full h-[220px] md:h-[260px] object-cover rounded-lg border border-gold-deep/15 transition-transform duration-300 hover:scale-[1.02]">
  </div>
</section>


<!-- VIRTUAL TOUR -->
<section class="py-[50px] md:py-[80px] px-[5%] lg:px-[8%] bg-cream relative" id="virtual-tour">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="text-center mb-8 md:mb-12">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">360° VIRTUAL TOUR</span>
    <h2 class="font-heading text-[28px] md:text-[40px] font-bold text-warm-black mb-2">Explore the Venue <span class="italic text-gold-deep">Virtually</span></h2>
    <p class="text-warm-black/90 text-[16px] md:text-[16px] font-normal">Experience every corner of LorDane's Place from the comfort of your home.</p>
  </div>
  <div class="relative max-w-[1000px] mx-auto">
    <a href="{{ asset('lordanes360view/index.html') }}" target="_blank"
      class="absolute top-4 right-4 z-10 bg-gold-deep/90 text-white px-4 py-2 rounded text-[15px] font-bold no-underline transition-all hover:bg-gold-mid">
      ⛶ Open Fullscreen
    </a>
    <iframe
      src="{{ asset('lordanes360view/index.html') }}"
      class="w-full h-[350px] md:h-[500px] lg:h-[600px] rounded-xl border border-gold-deep/20"
      style="border:none;"
      allow="accelerometer; gyroscope; magnetometer; fullscreen">
    </iframe>
  </div>
</section>


<!-- PACKAGES -->
<section class="py-[50px] md:py-[80px] px-[5%] lg:px-[8%] bg-off-white relative" id="packages">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="text-center mb-8 md:mb-12">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">OUR PACKAGES</span>
    <h2 class="font-heading text-[28px] md:text-[40px] font-bold text-warm-black mb-2">Resort <span class="italic text-gold-deep">Packages</span></h2>
    <p class="text-warm-black/90 text-[16px] md:text-[16px] font-normal">Enjoy exclusive use of LorDane's Place with simple and flexible swim packages.</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
    <!-- DAY SWIM -->
    <div class="border border-gold-deep/25 rounded-xl bg-cream p-6 md:p-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-md">
      <p class="text-[11px] tracking-[3px] text-gold-deep font-bold mb-2">DAYTIME</p>
      <h3 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-1">Day Swim</h3>
      <p class="text-[15px] text-warm-black/60 mb-4">8:00 AM – 4:00 PM (8 Hours)</p>
      <p class="text-[18px] font-bold text-gold-deep">₱12,500 <span class="text-[12px] font-normal text-warm-black/50">Weekday</span></p>
      <p class="text-[18px] font-bold text-gold-deep mb-4">₱14,500 <span class="text-[12px] font-normal text-warm-black/50">Weekend</span></p>
      <ul class="space-y-1.5 mb-4">
        <li class="text-[15px] text-warm-black/90 font-normal flex items-center gap-2"><span class="text-gold-deep">✓</span> Max 30 pax</li>
        <li class="text-[15px] text-warm-black/90 font-normal flex items-center gap-2"><span class="text-gold-deep">✓</span> 2 Gazebo</li>
        <li class="text-[15px] text-warm-black/90 font-normal flex items-center gap-2"><span class="text-gold-deep">✓</span> Griller & Sink</li>
      </ul>
      <p class="text-[12px] text-warm-black/50 mb-5">Extra Guest: ₱200/head</p>
      <button onclick="window.location.href='{{ route('booking', ['package' => 'Day Swim']) }}'" class="w-full bg-gold-deep text-white py-3 rounded font-bold text-[15px] tracking-wide border-none cursor-pointer transition-all duration-300 hover:bg-gold-mid">RESERVE</button>
    </div>

    <!-- NIGHT SWIM -->
    <div class="border border-gold-deep/25 rounded-xl bg-cream p-6 md:p-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-md">
      <p class="text-[11px] tracking-[3px] text-gold-deep font-bold mb-2">EVENING</p>
      <h3 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-1">Night Swim</h3>
      <p class="text-[15px] text-warm-black/60 mb-4">7:00 PM – 7:00 AM (12 Hours)</p>
      <p class="text-[18px] font-bold text-gold-deep">₱22,000 <span class="text-[12px] font-normal text-warm-black/50">Weekday</span></p>
      <p class="text-[18px] font-bold text-gold-deep mb-4">₱24,000 <span class="text-[12px] font-normal text-warm-black/50">Weekend</span></p>
      <ul class="space-y-1.5 mb-4">
        <li class="text-[15px] text-warm-black/90 font-normal flex items-center gap-2"><span class="text-gold-deep">✓</span> Max 30 pax</li>
        <li class="text-[15px] text-warm-black/90 font-normal flex items-center gap-2"><span class="text-gold-deep">✓</span> 3 Aircon Rooms</li>
        <li class="text-[15px] text-warm-black/90 font-normal flex items-center gap-2"><span class="text-gold-deep">✓</span> With Videoke</li>
      </ul>
      <p class="text-[12px] text-warm-black/50 mb-5">Extra Guest: ₱300/head</p>
      <button onclick="window.location.href='{{ route('booking', ['package' => 'Night Swim']) }}'" class="w-full bg-gold-deep text-white py-3 rounded font-bold text-[15px] tracking-wide border-none cursor-pointer transition-all duration-300 hover:bg-gold-mid">RESERVE</button>
    </div>

    <!-- OVERNIGHT -->
    <div class="border border-gold-deep/25 rounded-xl bg-cream p-6 md:p-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-md">
      <p class="text-[11px] tracking-[3px] text-gold-deep font-bold mb-2">OVERNIGHT</p>
      <h3 class="font-heading text-[24px] md:text-[28px] font-bold text-warm-black mb-1">22 Hours Overnight</h3>
      <p class="text-[15px] text-warm-black/60 mb-4">2:00 PM – 12:00 NN</p>
      <p class="text-[18px] font-bold text-gold-deep">₱28,000 <span class="text-[12px] font-normal text-warm-black/50">Weekday</span></p>
      <p class="text-[18px] font-bold text-gold-deep mb-4">₱30,000 <span class="text-[12px] font-normal text-warm-black/50">Weekend</span></p>
      <ul class="space-y-1.5 mb-4">
        <li class="text-[15px] text-warm-black/90 font-normal flex items-center gap-2"><span class="text-gold-deep">✓</span> Max 30 pax</li>
        <li class="text-[15px] text-warm-black/90 font-normal flex items-center gap-2"><span class="text-gold-deep">✓</span> 3 Aircon Rooms</li>
        <li class="text-[15px] text-warm-black/90 font-normal flex items-center gap-2"><span class="text-gold-deep">✓</span> Kitchen Access</li>
      </ul>
      <p class="text-[12px] text-warm-black/50 mb-5">Extra Guest: ₱300/head</p>
      <button onclick="window.location.href='{{ route('booking', ['package' => '22 Hours Overnight']) }}'" class="w-full bg-gold-deep text-white py-3 rounded font-bold text-[15px] tracking-wide border-none cursor-pointer transition-all duration-300 hover:bg-gold-mid">RESERVE</button>
    </div>
  </div>

  <!-- COMMON INCLUSIONS -->
  <div class="text-center mt-12 md:mt-16 mb-8">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">MORE DETAILS</span>
    <h2 class="font-heading text-[28px] md:text-[36px] font-bold text-warm-black">Common <span class="italic text-gold-deep">Inclusions</span></h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
    <div class="border border-gold-deep/25 rounded-lg p-5 bg-cream transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
      <h4 class="text-[16px] font-bold text-gold-deep mb-2">Exclusive Use</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6]">Private resort access for your group only.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 bg-cream transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
      <h4 class="text-[16px] font-bold text-gold-deep mb-2">Rooms & Gazebo</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6]">Comfortable rooms and shaded areas included.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 bg-cream transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
      <h4 class="text-[16px] font-bold text-gold-deep mb-2">Kitchen & Grill</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6]">Use of kitchen, griller, and basic facilities.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 bg-cream transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
      <h4 class="text-[16px] font-bold text-gold-deep mb-2">Pet Friendly</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6]">Pets are welcome with responsible owners.</p>
    </div>
  </div>

  <!-- GUIDELINES -->
  <div class="text-center mt-12 md:mt-16 mb-8">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">GUIDELINES</span>
    <h2 class="font-heading text-[28px] md:text-[36px] font-bold text-warm-black">Resort <span class="italic text-gold-deep">Guidelines</span></h2>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
    <div class="border border-gold-deep/25 rounded-lg p-5 bg-cream transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
      <h4 class="text-[16px] font-bold text-gold-deep mb-2">Max Capacity</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6]">Maximum of 30 guests only per booking.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 bg-cream transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
      <h4 class="text-[16px] font-bold text-gold-deep mb-2">Time Policy</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6]">Please follow your selected schedule strictly.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 bg-cream transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
      <h4 class="text-[16px] font-bold text-gold-deep mb-2">Cleanliness</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6]">Practice CLAYGO.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-5 bg-cream transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
      <h4 class="text-[16px] font-bold text-gold-deep mb-2">Pet Policy</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6]">Pets are allowed. Owners must be responsible.</p>
    </div>
  </div>
</section>


<!-- ROOMS & SPACES -->
<section class="py-[50px] md:py-[80px] px-[5%] lg:px-[8%] bg-cream relative" id="rooms">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="text-center mb-8 md:mb-12">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">OUR SPACES</span>
    <h2 class="font-heading text-[28px] md:text-[40px] font-bold text-warm-black mb-2">Rooms & <span class="italic text-gold-deep">Spaces</span></h2>
    <p class="text-warm-black/90 text-[16px] md:text-[16px] font-normal">Choose the perfect space that fits your group and budget.</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
    <!-- STANDARD ROOM -->
    <div class="border border-gold-deep/20 rounded-xl overflow-hidden bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-md">
      <div class="h-[200px] md:h-[220px] bg-cream flex items-center justify-center overflow-hidden">
        <img src="{{ asset('images/room-standard.jpg') }}" alt="Standard Room" class="w-full h-full object-cover"
             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'flex items-center justify-center w-full h-full text-warm-black/30 text-[16px]\'>Image Coming Soon</div>';">
      </div>
      <div class="p-5 md:p-6">
        <h4 class="text-[16px] md:text-[18px] font-bold text-warm-black mb-2">Standard Room</h4>
        <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6] mb-3">Comfortable air-conditioned room good for up to 4 guests.</p>
        <p class="text-[18px] font-bold text-gold-deep mb-3">₱4,500 <span class="text-[12px] font-normal text-warm-black/50">/ solo rate</span></p>
        <div class="flex justify-between items-center">
          <span class="text-[15px] text-warm-black/60">👥 Good for 4 pax</span>
          <button onclick="window.location.href='{{ route('booking', ['package' => 'Standard Room']) }}'" class="bg-gold-deep text-white px-4 py-2 rounded text-[12px] font-bold tracking-wide border-none cursor-pointer transition-all hover:bg-gold-mid">BOOK NOW</button>
        </div>
      </div>
    </div>

    <!-- FAMILY ROOM -->
    <div class="border border-gold-deep/20 rounded-xl overflow-hidden bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-md">
      <div class="h-[200px] md:h-[220px] bg-cream flex items-center justify-center overflow-hidden">
        <img src="{{ asset('images/room-family.jpg') }}" alt="Family Room" class="w-full h-full object-cover"
             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'flex items-center justify-center w-full h-full text-warm-black/30 text-[16px]\'>Image Coming Soon</div>';">
      </div>
      <div class="p-5 md:p-6">
        <h4 class="text-[16px] md:text-[18px] font-bold text-warm-black mb-2">Family Room</h4>
        <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6] mb-3">Spacious air-conditioned room perfect for families or small groups.</p>
        <p class="text-[18px] font-bold text-gold-deep mb-3">₱8,500 <span class="text-[12px] font-normal text-warm-black/50">/ solo rate</span></p>
        <div class="flex justify-between items-center">
          <span class="text-[15px] text-warm-black/60">👥 Good for 4 pax</span>
          <button onclick="window.location.href='{{ route('booking', ['package' => 'Family Room']) }}'" class="bg-gold-deep text-white px-4 py-2 rounded text-[12px] font-bold tracking-wide border-none cursor-pointer transition-all hover:bg-gold-mid">BOOK NOW</button>
        </div>
      </div>
    </div>

    <!-- DORMITORY ROOM -->
    <div class="border border-gold-deep/20 rounded-xl overflow-hidden bg-off-white transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-md">
      <div class="h-[200px] md:h-[220px] bg-cream flex items-center justify-center overflow-hidden">
        <img src="{{ asset('images/room-dormitory.jpg') }}" alt="Dormitory Room" class="w-full h-full object-cover"
             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'flex items-center justify-center w-full h-full text-warm-black/30 text-[16px]\'>Image Coming Soon</div>';">
      </div>
      <div class="p-5 md:p-6">
        <h4 class="text-[16px] md:text-[18px] font-bold text-warm-black mb-2">Dormitory Room</h4>
        <p class="text-[15px] text-warm-black/90 font-normal leading-[1.6] mb-3">Large air-conditioned dormitory ideal for big groups and sleepovers.</p>
        <p class="text-[18px] font-bold text-gold-deep mb-3">₱11,500 <span class="text-[12px] font-normal text-warm-black/50">/ solo rate</span></p>
        <div class="flex justify-between items-center">
          <span class="text-[15px] text-warm-black/60">👥 Good for 4 pax</span>
          <button onclick="window.location.href='{{ route('booking', ['package' => 'Dormitory Room']) }}'" class="bg-gold-deep text-white px-4 py-2 rounded text-[12px] font-bold tracking-wide border-none cursor-pointer transition-all hover:bg-gold-mid">BOOK NOW</button>
        </div>
      </div>
    </div>
  </div>

  <!-- VENUE HALL (full-width) -->
  <div class="mt-6 border border-gold-deep/20 rounded-xl overflow-hidden bg-off-white transition-all duration-300 hover:border-gold-deep hover:shadow-md">
    <div class="grid grid-cols-1 md:grid-cols-2">
      <div class="h-[220px] md:h-full bg-cream flex items-center justify-center overflow-hidden">
        <img src="{{ asset('images/venue-hall.jpg') }}" alt="Venue Hall / Pavilion" class="w-full h-full object-cover"
             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'flex items-center justify-center w-full h-full text-warm-black/30 text-[16px]\'>Image Coming Soon</div>';">
      </div>
      <div class="p-6 md:p-8 flex flex-col justify-center">
        <h4 class="text-[18px] md:text-[22px] font-bold text-warm-black mb-2">Venue Hall / Pavilion</h4>
        <p class="text-[15px] md:text-[16px] text-warm-black/90 font-normal leading-[1.6] mb-4">Grand event hall with 2 preparation rooms — perfect for parties, reunions, and special events.</p>
        <p class="text-[22px] font-bold text-gold-deep mb-4">₱35,000</p>
        <div class="flex justify-between items-center">
          <span class="text-[15px] text-warm-black/60">👥 Good for 4 pax</span>
          <button onclick="window.location.href='{{ route('booking', ['package' => 'Venue Hall']) }}'" class="bg-gold-deep text-white px-6 py-2.5 rounded text-[15px] font-bold tracking-wide border-none cursor-pointer transition-all hover:bg-gold-mid">BOOK NOW</button>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- CTA -->
<section class="py-[50px] md:py-[70px] px-[5%] lg:px-[8%] text-center relative border-y border-gold-deep/25" style="background: linear-gradient(135deg, #E8C96D22, #F2EDE4);">
  <h2 class="font-heading text-[28px] md:text-[40px] mb-4 text-warm-black">
    Ready to Book Your <span class="italic text-gold-deep">Perfect Getaway?</span>
  </h2>
  <p class="text-[15px] md:text-[17px] text-warm-black/90 font-normal mb-7 md:mb-9">Reserve your date today and let us take care of the rest.</p>
  <div class="flex flex-wrap justify-center gap-3">
    <a href="{{ route('booking') }}" class="inline-block no-underline px-7 md:px-9 py-3 md:py-4 text-[16px] md:text-[15px] font-bold tracking-wide rounded-[3px] bg-gold-deep text-white transition-all duration-300 hover:bg-gold-mid hover:-translate-y-0.5">Reserve Your Date</a>
    <a href="{{ url('/') }}" class="inline-block no-underline px-7 md:px-9 py-3 md:py-4 text-[16px] md:text-[15px] font-bold tracking-wide rounded-[3px] border border-warm-black/30 text-warm-black transition-all duration-300 hover:border-gold-deep hover:text-gold-deep hover:-translate-y-0.5">Back to Home</a>
  </div>
</section>

@include('partials._footer')
@include('chat-assistant')
</body>
</html>