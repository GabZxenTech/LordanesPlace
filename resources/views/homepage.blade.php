<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LorDane's Place - Event Venue</title>
<meta name="description" content="LorDane's Place — an elegant event venue in Santa Maria, Bulacan. Book your dream celebration today.">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<!-- HERO -->
<section class="hero-bg min-h-screen flex justify-center items-center text-center text-white px-5 relative" id="home">
  <div class="gold-divider absolute bottom-0 left-0 right-0"></div>
  <div class="max-w-[720px]">
    <p class="tracking-[4px] text-gold-light text-[12px] md:text-[15px] font-bold mb-5">WELCOME TO LORDANE'S PLACE</p>
    <h1 class="font-heading text-[36px] sm:text-[46px] md:text-[62px] font-bold leading-[1.2] mb-5 text-white">
      Experience Every<br>
      Corner of <span class="italic text-gold-light">LorDane's Place</span>
    </h1>
    <p class="mb-9 text-white text-[16px] md:text-[17px] leading-[1.75] font-normal">
      Your dream celebration starts here. LorDane's Place offers an elegant venue
      experience in Santa Maria, Bulacan — where every moment becomes a masterpiece.
    </p>
    <div class="flex flex-wrap justify-center gap-3">
      <button onclick="window.location.href='{{ url('/tour') }}'"
        class="bg-gold-deep text-white px-6 md:px-8 py-3 md:py-4 border-none cursor-pointer text-[16px] md:text-[15px] font-bold tracking-wide rounded-[3px] transition-all duration-300 hover:bg-gold-mid hover:-translate-y-0.5">
        Start 360° Tour
      </button>
      <a href="{{ route('booking') }}"
        class="inline-block no-underline px-6 md:px-8 py-3 md:py-4 text-[16px] md:text-[15px] font-bold tracking-wide rounded-[3px] border border-white/70 text-white transition-all duration-300 hover:border-gold-light hover:text-gold-light hover:-translate-y-0.5">
        Reserve Your Date
      </a>
    </div>
  </div>
</section>


<!-- ABOUT -->
<section class="py-[60px] md:py-[90px] px-[5%] relative bg-off-white" id="about">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="flex gap-8 md:gap-[60px] items-center flex-wrap w-full">

    <div class="about-img-wrap flex-[1.2] min-w-[280px] lg:min-w-[350px] relative">
      <img src="Event.jpg" alt="LorDane's Place Event" class="w-full h-[300px] md:h-[450px] object-cover rounded-lg shadow-sm">
    </div>

    <div class="flex-1 min-w-[280px] lg:min-w-[300px]">
      <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">ABOUT US</span>
      <h2 class="font-heading text-[30px] md:text-[42px] font-bold leading-[1.25] mb-5 text-warm-black">
        Where Every Moment <br>Becomes a <span class="italic text-gold-deep">Masterpiece</span>
      </h2>
      <p class="text-[15px] md:text-[16px] font-normal leading-[1.85] text-warm-black/90 mb-4">
        LorDane's Place is more than a venue — it is an experience crafted
        for those who believe that life's greatest moments deserve the
        finest settings. Located in Pulong Buhangin, Santa Maria, Bulacan,
        we have been hosting unforgettable celebrations since 2024.
      </p>
      <p class="text-[15px] md:text-[16px] font-normal leading-[1.85] text-warm-black/90 mb-4">
        From intimate gatherings to grand celebrations, our team is dedicated
        to making every event truly special — with in-house catering, elegant
        décor, and professional event services all in one place.
      </p>
      <div class="mt-7 md:mt-9 grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5">
        <div class="p-4 md:p-5 border border-gold-deep/25 rounded-md bg-white/70 transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
          <h4 class="text-[16px] md:text-[15px] font-bold text-gold-deep tracking-[0.5px] mb-2">200–250 Guest Capacity</h4>
          <p class="text-[15px] md:text-[16px] font-normal text-warm-black/90 leading-[1.6]">Spacious venue with flexible arrangements for any event.</p>
        </div>
        <div class="p-4 md:p-5 border border-gold-deep/25 rounded-md bg-white/70 transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
          <h4 class="text-[16px] md:text-[15px] font-bold text-gold-deep tracking-[0.5px] mb-2">8 Booking Rooms</h4>
          <p class="text-[15px] md:text-[16px] font-normal text-warm-black/90 leading-[1.6]">Multiple spaces to suit different event sizes and styles.</p>
        </div>
        <div class="p-4 md:p-5 border border-gold-deep/25 rounded-md bg-white/70 transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
          <h4 class="text-[16px] md:text-[15px] font-bold text-gold-deep tracking-[0.5px] mb-2">Prime Location</h4>
          <p class="text-[15px] md:text-[16px] font-normal text-warm-black/90 leading-[1.6]">Conveniently located in Santa Maria, Bulacan.</p>
        </div>
        <div class="p-4 md:p-5 border border-gold-deep/25 rounded-md bg-white/70 transition-all duration-300 hover:border-gold-deep hover:shadow-sm">
          <h4 class="text-[16px] md:text-[15px] font-bold text-gold-deep tracking-[0.5px] mb-2">Full In-House Services</h4>
          <p class="text-[15px] md:text-[16px] font-normal text-warm-black/90 leading-[1.6]">Catering, décor, sound, lights, photo & video — all included.</p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- VIRTUAL TOUR TEASER -->
<section class="py-[60px] md:py-[90px] px-[5%] lg:px-[8%] relative bg-cream">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="flex gap-8 md:gap-[60px] items-center flex-wrap">
    <div class="flex-1 min-w-[280px] lg:min-w-[300px]">
      <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">EXPLORE VIRTUALLY</span>
      <h2 class="font-heading text-[30px] md:text-[42px] font-bold leading-[1.25] mb-5 text-warm-black">
        Tour the Venue <span class="italic text-gold-deep">From Home</span>
      </h2>
      <p class="text-[15px] md:text-[16px] font-normal leading-[1.85] text-warm-black/90 mb-8">
        Can't visit in person? No problem. Explore every corner of LorDane's Place
        through our immersive 360° virtual tour — see the rooms, the ambiance,
        and the spaces before your big day.
      </p>
      <button onclick="window.location.href='{{ url('/tour') }}'"
        class="px-6 md:px-8 py-3 md:py-4 bg-gold-deep text-white border-none font-bold text-[16px] md:text-[15px] tracking-wide cursor-pointer rounded-[3px] transition-all duration-300 hover:bg-gold-mid hover:-translate-y-0.5">
        Start Virtual Tour
      </button>
    </div>
    <div class="flex-[1.2] min-w-[280px] lg:min-w-[350px] relative">
      <img src="Event.jpg" alt="Virtual Tour Preview" class="w-full h-[280px] md:h-[380px] object-cover rounded-lg shadow-sm">
      <div class="play-btn w-14 h-14 md:w-16 md:h-16 bg-gold-deep text-white rounded-full flex items-center justify-center text-lg md:text-xl cursor-pointer hover:bg-gold-mid"
        onclick="window.location.href='{{ url('/tour') }}'">▶</div>
    </div>
  </div>
</section>


<!-- SERVICES -->
<section class="py-[60px] md:py-[90px] px-[5%] lg:px-[8%] relative bg-off-white">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="text-center mb-10 md:mb-[50px]">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">WHAT WE OFFER</span>
    <h2 class="font-heading text-[30px] md:text-[40px] mb-3 text-warm-black">
      Everything for Your <span class="italic text-gold-deep">Perfect Event</span>
    </h2>
    <p class="text-warm-black/90 text-[15px] md:text-[16px] font-normal">All the essentials — beautifully handled, all in one place.</p>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">In-House Catering</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Customizable menus crafted to satisfy every guest.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">Event Decoration</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Elegant setups tailored to your theme and vision.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">Sound & Lighting</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Professional equipment for a truly immersive atmosphere.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">Photo & Video</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Every precious moment captured with care and artistry.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">Host / Emcee</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">An experienced host to keep your event lively and on track.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">LED Wall & Projector</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Large displays for presentations, slideshows, and tributes.</p>
    </div>
  </div>
</section>


<!-- CTA BANNER -->
<section class="py-[60px] md:py-[80px] px-[5%] lg:px-[8%] text-center relative border-y border-gold-deep/25" style="background: linear-gradient(135deg, #E8C96D22, #F2EDE4);">
  <h2 class="font-heading text-[32px] md:text-[46px] mb-4 text-warm-black">
    Ready to Plan Your <span class="italic text-gold-deep">Dream Event?</span>
  </h2>
  <p class="text-[15px] md:text-[17px] text-warm-black/90 font-normal mb-7 md:mb-9">Book your date today and let us take care of the rest.</p>
  <div class="flex flex-wrap justify-center gap-3">
    <a href="{{ route('booking') }}"
      class="inline-block no-underline px-7 md:px-9 py-3 md:py-4 text-[16px] md:text-[15px] font-bold tracking-wide rounded-[3px] bg-gold-deep text-white transition-all duration-300 hover:bg-gold-mid hover:-translate-y-0.5">
      Reserve Your Date
    </a>
    <a href="{{ route('discover') }}"
      class="inline-block no-underline px-7 md:px-9 py-3 md:py-4 text-[16px] md:text-[15px] font-bold tracking-wide rounded-[3px] border border-warm-black/30 text-warm-black transition-all duration-300 hover:border-gold-deep hover:text-gold-deep hover:-translate-y-0.5">
      Discover the Venue
    </a>
  </div>
</section>

@include('partials._footer')

<script>
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-link');

  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(section => {
      const sectionTop = section.offsetTop - 100;
      if (window.scrollY >= sectionTop) current = section.getAttribute('id');
    });
    navLinks.forEach(link => {
      link.classList.remove('nav-link-active');
      const href = link.getAttribute('href');
      if (current && href && href.endsWith(`#${current}`)) {
        link.classList.add('nav-link-active');
      }
    });
    if (!current && window.scrollY < 200) {
      const homeLink = document.querySelector('.nav-link[href="{{ route("home") }}"]');
      if (homeLink) homeLink.classList.add('nav-link-active');
    }
  });
</script>

@include('chat-assistant')

</body>
</html>