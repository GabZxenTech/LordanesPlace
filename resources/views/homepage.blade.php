<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LorDane's Place - Event Venue</title>
<meta name="description" content="LorDane's Place — an elegant event venue in Santa Maria, Bulacan. Book your dream celebration today.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<!-- HERO -->
<section class="hero-bg min-h-screen flex justify-center items-center text-center text-white px-5 relative" id="home" 
         style="background: linear-gradient(rgba(26,18,8,0.4), rgba(26,18,8,0.6)), url('{{ asset('images/LORDANES_BG.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
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
      <button onclick="window.location.href='{{ route('discover') }}#virtual-tour'"
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
<section class="py-[60px] md:py-[90px] px-[5%] lg:px-[8%] relative bg-off-white" id="about">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="flex gap-8 md:gap-[60px] items-center flex-wrap w-full">


<div class="flex-[1.2] min-w-[280px] lg:min-w-[350px] relative">
    <img
        src="{{ asset('images/aboutus.png') }}"
        alt="About LorDane's Place"
        class="w-full h-[300px] md:h-[450px] object-cover rounded-lg shadow-sm"
    />
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
      <button onclick="window.location.href='{{ route('discover') }}#virtual-tour'"
        class="px-6 md:px-8 py-3 md:py-4 bg-gold-deep text-white border-none font-bold text-[16px] md:text-[15px] tracking-wide cursor-pointer rounded-[3px] transition-all duration-300 hover:bg-gold-mid hover:-translate-y-0.5">
        Start Virtual Tour
      </button>
    </div>
    <div class="flex-[1.2] min-w-[280px] lg:min-w-[350px] relative">
      <img src="{{ asset('images/360_BG.png') }}" alt="Virtual Tour Preview" loading="lazy" class="w-full h-[280px] md:h-[380px] object-cover rounded-lg shadow-sm">
      <div class="play-btn w-14 h-14 md:w-16 md:h-16 bg-gold-deep text-white rounded-full flex items-center justify-center text-lg md:text-xl cursor-pointer hover:bg-gold-mid"
        onclick="window.location.href='{{ route('discover') }}#virtual-tour'">▶</div>
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
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(184,134,11,0.15)] bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">In-House Catering</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Customizable menus crafted to satisfy every guest.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(184,134,11,0.15)] bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">Event Decoration</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Elegant setups tailored to your theme and vision.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(184,134,11,0.15)] bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">Sound & Lighting</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Professional equipment for a truly immersive atmosphere.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(184,134,11,0.15)] bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">Photo & Video</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Every precious moment captured with care and artistry.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(184,134,11,0.15)] bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">Host / Emcee</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">An experienced host to keep your event lively and on track.</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg px-5 md:px-6 py-7 md:py-8 transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-[0_0_15px_rgba(184,134,11,0.15)] bg-cream">
      <h4 class="text-[15px] md:text-[16px] font-bold text-gold-deep mb-3 tracking-[0.5px]">LED Wall & Projector</h4>
      <p class="text-[16px] md:text-[15px] text-warm-black/90 font-normal leading-[1.65]">Large displays for presentations, slideshows, and tributes.</p>
    </div>
  </div>
</section>


<style>
  .review-card {
    flex: 0 0 100%;
    scroll-snap-align: start;
  }
  @media (min-width: 640px) {
    .review-card {
      flex: 0 0 calc((100% - 20px) / 2);
    }
  }
  @media (min-width: 1024px) {
    .review-card {
      flex: 0 0 calc((100% - 48px) / 3);
    }
  }
  /* Force-hide the horizontal scrollbar on the reviews track */
  #reviewsTrack::-webkit-scrollbar {
    display: none !important;
    width: 0 !important;
    height: 0 !important;
  }
  #reviewsTrack {
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
</style>

<!-- CUSTOMER REVIEWS / TESTIMONIALS -->
<section class="px-[5%] lg:px-[8%] relative bg-cream" id="reviews" style="scroll-margin-top: 100px; padding-top: 100px; padding-bottom: 90px;">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  
  <div class="text-center relative z-[1]" style="margin-bottom: 40px; padding-top: 8px;">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block uppercase">WHAT OUR GUESTS SAY</span>
    <h2 class="font-heading text-[30px] md:text-[42px] font-bold text-warm-black" style="line-height: 1.35;">
      Customer <span class="italic text-gold-deep">Reviews</span>
    </h2>
    <p class="text-warm-black/80 text-[15px] md:text-[16px] max-w-[600px] mx-auto mt-2 font-normal">
      Real feedback from guests who celebrated their special moments at LorDane's Place.
    </p>
  </div>

  <!-- Carousel Outer Wrapper -->
  <div class="relative mx-auto" style="max-width: 1300px; padding-left: 56px; padding-right: 56px; overflow: hidden;">
    <!-- Left Navigation Arrow -->
    <button id="reviewsPrevBtn" onclick="scrollReviews(-1)" type="button"
            class="hover:bg-gold-deep hover:text-white hover:border-gold-deep disabled:hover:bg-white disabled:hover:text-gold-deep"
            style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); z-index: 10; width: 44px; height: 44px; border-radius: 9999px; background-color: #ffffff; border: 1px solid rgba(184,134,11,0.4); color: #B8860B; font-size: 22px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.12); cursor: pointer; transition: all 0.3s;"
            aria-label="Previous Reviews">
      ‹
    </button>

    <!-- Scrollable Reviews Track -->
    <div id="reviewsTrack" class="flex no-scrollbar" style="display: flex; overflow-x: auto; overflow-y: hidden; scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none; gap: 20px; padding: 16px 4px;">
      
      <!-- Review 1 -->
      <div class="review-card bg-white/80 border border-gold-deep/25 rounded-xl p-6 transition-all duration-300 hover:border-gold-deep hover:shadow-md flex flex-col justify-between shrink-0">
        <div>
          <div class="flex items-center justify-between mb-4">
            <div class="flex text-[18px] tracking-widest" style="color: #F4B400 !important;">
              ★★★★★
            </div>
            <span class="text-[11px] font-semibold text-gold-deep bg-gold-deep/10 px-2.5 py-0.5 rounded-full flex items-center gap-1">
              Google Review
            </span>
          </div>
          <p class="text-warm-black/90 text-[14px] md:text-[15px] leading-[1.65] font-normal italic mb-6">
            "Attended several events here, and the experience has always been great."
          </p>
        </div>
        <div class="flex items-center gap-3 pt-4 border-t border-gold-deep/15">
          <div class="w-10 h-10 rounded-full font-bold text-[16px] flex items-center justify-center shrink-0 shadow-sm border border-white/20" style="background-color: #455A64 !important; color: #ffffff !important;">
            J
          </div>
          <div>
            <h4 class="text-[15px] font-bold text-warm-black leading-tight">Josephine Carlos</h4>
            <p class="text-[12px] text-warm-black/50">5-Star Guest Review</p>
          </div>
        </div>
      </div>

      <!-- Review 2 -->
      <div class="review-card bg-white/80 border border-gold-deep/25 rounded-xl p-6 transition-all duration-300 hover:border-gold-deep hover:shadow-md flex flex-col justify-between shrink-0">
        <div>
          <div class="flex items-center justify-between mb-4">
            <div class="flex text-[18px] tracking-widest" style="color: #F4B400 !important;">
              ★★★★★
            </div>
            <span class="text-[11px] font-semibold text-gold-deep bg-gold-deep/10 px-2.5 py-0.5 rounded-full flex items-center gap-1">
              Google Review
            </span>
          </div>
          <p class="text-warm-black/90 text-[14px] md:text-[15px] leading-[1.65] font-normal italic mb-6">
            "Our experience at Lordane's event was great, very accessible for our guest, the place is spacious, and most of all the owner is very nice to us,"
          </p>
        </div>
        <div class="flex items-center gap-3 pt-4 border-t border-gold-deep/15">
          <div class="w-10 h-10 rounded-full font-bold text-[16px] flex items-center justify-center shrink-0 shadow-sm border border-white/20" style="background-color: #7B1FA2 !important; color: #ffffff !important;">
            E
          </div>
          <div>
            <h4 class="text-[15px] font-bold text-warm-black leading-tight">Eve Cruz</h4>
            <p class="text-[12px] text-warm-black/50">5-Star Guest Review</p>
          </div>
        </div>
      </div>

      <!-- Review 3 -->
      <div class="review-card bg-white/80 border border-gold-deep/25 rounded-xl p-6 transition-all duration-300 hover:border-gold-deep hover:shadow-md flex flex-col justify-between shrink-0">
        <div>
          <div class="flex items-center justify-between mb-4">
            <div class="flex text-[18px] tracking-widest" style="color: #F4B400 !important;">
              ★★★★★
            </div>
            <span class="text-[11px] font-semibold text-gold-deep bg-gold-deep/10 px-2.5 py-0.5 rounded-full flex items-center gap-1">
              Google Review
            </span>
          </div>
          <p class="text-warm-black/90 text-[14px] md:text-[15px] leading-[1.65] font-normal italic mb-6">
            "I love the place. They offer excellent and good service. 😊"
          </p>
        </div>
        <div class="flex items-center gap-3 pt-4 border-t border-gold-deep/15">
          <div class="w-10 h-10 rounded-full font-bold text-[16px] flex items-center justify-center shrink-0 shadow-sm border border-white/20" style="background-color: #E91E63 !important; color: #ffffff !important;">
            K
          </div>
          <div>
            <h4 class="text-[15px] font-bold text-warm-black leading-tight">Kiss Niño</h4>
            <p class="text-[12px] text-warm-black/50">5-Star Guest Review</p>
          </div>
        </div>
      </div>

      <!-- Review 4 -->
      <div class="review-card bg-white/80 border border-gold-deep/25 rounded-xl p-6 transition-all duration-300 hover:border-gold-deep hover:shadow-md flex flex-col justify-between shrink-0">
        <div>
          <div class="flex items-center justify-between mb-4">
            <div class="flex text-[18px] tracking-widest" style="color: #F4B400 !important;">
              ★★★★★
            </div>
            <span class="text-[11px] font-semibold text-gold-deep bg-gold-deep/10 px-2.5 py-0.5 rounded-full flex items-center gap-1">
              Google Review
            </span>
          </div>
          <p class="text-warm-black/90 text-[14px] md:text-[15px] leading-[1.65] font-normal italic mb-6">
            "Great Experience!"
          </p>
        </div>
        <div class="flex items-center gap-3 pt-4 border-t border-gold-deep/15">
          <div class="w-10 h-10 rounded-full font-bold text-[16px] flex items-center justify-center shrink-0 shadow-sm border border-white/20" style="background-color: #0288D1 !important; color: #ffffff !important;">
            M
          </div>
          <div>
            <h4 class="text-[15px] font-bold text-warm-black leading-tight">Michael Tabor</h4>
            <p class="text-[12px] text-warm-black/50">5-Star Guest Review</p>
          </div>
        </div>
      </div>

      
      <div class="review-card bg-white/80 border border-gold-deep/25 rounded-xl p-6 transition-all duration-300 hover:border-gold-deep hover:shadow-md flex flex-col justify-between shrink-0">
        <div>
          <div class="flex text-[18px] tracking-widest" style="color: #F4B400 !important;">
            ★★★★★
          </div>
        </div>
        <div class="flex items-center gap-3 pt-4 border-t border-gold-deep/15">
          <div class="w-10 h-10 rounded-full font-bold text-[16px] flex items-center justify-center shrink-0 shadow-sm border border-white/20" style="background-color: #2E7D32 !important; color: #ffffff !important;">
            M
          </div>
          <div>
            <h4 class="text-[15px] font-bold text-warm-black leading-tight">Mae</h4>
            <p class="text-[12px] text-warm-black/50">5-Star Guest Review</p>
          </div>
        </div>
      </div>

      <!-- Review 6 (Minimal Google Review Style: Reyna Lim Baquiller) -->
      <div class="review-card bg-white/80 border border-gold-deep/25 rounded-xl p-6 transition-all duration-300 hover:border-gold-deep hover:shadow-md flex flex-col justify-between shrink-0">
        <div>
          <div class="flex text-[18px] tracking-widest" style="color: #F4B400 !important;">
            ★★★★★
          </div>
        </div>
        <div class="flex items-center gap-3 pt-4 border-t border-gold-deep/15">
          <div class="w-10 h-10 rounded-full font-bold text-[16px] flex items-center justify-center shrink-0 shadow-sm border border-white/20" style="background-color: #C2185B !important; color: #ffffff !important;">
            R
          </div>
          <div>
            <h4 class="text-[15px] font-bold text-warm-black leading-tight">Reyna Lim Baquiller</h4>
            <p class="text-[12px] text-warm-black/50">5-Star Guest Review</p>
          </div>
        </div>
      </div>

      <div class="review-card bg-white/80 border border-gold-deep/25 rounded-xl p-6 transition-all duration-300 hover:border-gold-deep hover:shadow-md flex flex-col justify-between shrink-0">
        <div>
          <div class="flex text-[18px] tracking-widest" style="color: #F4B400 !important;">
            ★★★★★
          </div>
        </div>
        <div class="flex items-center gap-3 pt-4 border-t border-gold-deep/15">
          <div class="w-10 h-10 rounded-full font-bold text-[16px] flex items-center justify-center shrink-0 shadow-sm border border-white/20" style="background-color: #1565C0 !important; color: #ffffff !important;">
            A
          </div>
          <div>
            <h4 class="text-[15px] font-bold text-warm-black leading-tight">Ayan Rapada</h4>
            <p class="text-[12px] text-warm-black/50">5-Star Guest Review</p>
          </div>
        </div>
      </div>

    </div>

    <button id="reviewsNextBtn" onclick="scrollReviews(1)" type="button"
            class="hover:bg-gold-deep hover:text-white hover:border-gold-deep disabled:hover:bg-white disabled:hover:text-gold-deep"
            style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); z-index: 10; width: 44px; height: 44px; border-radius: 9999px; background-color: #ffffff; border: 1px solid rgba(184,134,11,0.4); color: #B8860B; font-size: 22px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.12); cursor: pointer; transition: all 0.3s;"
            aria-label="Next Reviews">
      ›
    </button>
  </div>
</section>

<script>
  function getReviewsGap(track) {
    const style = window.getComputedStyle(track);
    const gapValue = style.columnGap || style.gap || '20px';
    const parsed = parseFloat(gapValue);
    return isNaN(parsed) ? 20 : parsed;
  }

  function scrollReviews(direction) {
    const track = document.getElementById('reviewsTrack');
    if (!track) return;
    const card = track.querySelector('.review-card');
    const cardWidth = card ? card.getBoundingClientRect().width : 300;
    const gap = getReviewsGap(track);
    track.scrollBy({ left: direction * (cardWidth + gap), behavior: 'smooth' });
  }

  function updateReviewArrowStates() {
    const track = document.getElementById('reviewsTrack');
    const prevBtn = document.getElementById('reviewsPrevBtn');
    const nextBtn = document.getElementById('reviewsNextBtn');
    if (!track || !prevBtn || !nextBtn) return;

    const maxScroll = track.scrollWidth - track.clientWidth;

    function setBtnState(btn, isDisabled) {
      btn.disabled = isDisabled;
      btn.style.opacity = isDisabled ? '0.3' : '1';
      btn.style.cursor = isDisabled ? 'not-allowed' : 'pointer';
    }

   
    if (maxScroll <= 1) {
      setBtnState(prevBtn, true);
      setBtnState(nextBtn, true);
      return;
    }

    setBtnState(prevBtn, track.scrollLeft <= 2);
    setBtnState(nextBtn, track.scrollLeft >= maxScroll - 2);
  }

  function initReviewsCarousel() {
    const track = document.getElementById('reviewsTrack');
    if (!track) return;
    track.addEventListener('scroll', updateReviewArrowStates);
    window.addEventListener('resize', updateReviewArrowStates);
    
    requestAnimationFrame(updateReviewArrowStates);
    window.addEventListener('load', updateReviewArrowStates);
    updateReviewArrowStates();
  }

  document.addEventListener('DOMContentLoaded', initReviewsCarousel);
</script>


<!-- CTA BANNER -->
<section class="mt-6 py-[60px] md:py-[80px] px-[5%] lg:px-[8%] text-center relative border-t border-b-0 border-gold-deep/25" style="background: linear-gradient(135deg, #E8C96D22, #F2EDE4);">
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
      class="inline-block no-underline px-7 md:px-9 py-3 md:py-4 text-[16px] md:text-[15px] font-bold tracking-wide rounded-[3px] border-2 border-gold-deep text-warm-black transition-all duration-300 hover:bg-gold-deep hover:text-white hover:-translate-y-0.5">
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