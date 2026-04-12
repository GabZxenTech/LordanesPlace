<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - LorDane's Place</title>
<meta name="description" content="Get in touch with LorDane's Place. Find our location, opening hours, phone number, and email address.">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<!-- CONTACT HERO -->
<section class="contact-hero-bg h-[280px] md:h-[350px] flex justify-center items-center text-center text-white px-5 relative">
  <div class="gold-divider absolute bottom-0 left-0 right-0"></div>
  <div>
    <p class="text-[11px] md:text-[12px] tracking-[4px] text-gold-light font-bold mb-3">GET IN TOUCH</p>
    <h1 class="font-heading text-[38px] md:text-[52px] font-bold text-white mb-2">Contact <span class="italic text-gold-light">Us</span></h1>
    <p class="text-white/60 text-[15px] md:text-[16px] tracking-wide">Home / Contact Us</p>
  </div>
</section>


<!-- CONTACT INFO CARDS -->
<section class="py-[50px] md:py-[70px] px-[5%] lg:px-[8%] bg-off-white relative">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="text-center mb-8 md:mb-12">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">HOW TO REACH US</span>
    <h2 class="font-heading text-[28px] md:text-[40px] font-bold text-warm-black">Get in <span class="italic text-gold-deep">Touch</span></h2>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="border border-gold-deep/25 rounded-lg p-6 md:p-7 text-center bg-cream transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm">
      <div class="text-[28px] md:text-[30px] mb-3">🕒</div>
      <h4 class="text-[16px] font-bold text-gold-deep tracking-[0.5px] mb-2 uppercase">Opening Hours</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Day Tour: 8:00 AM - 3:00 PM</p>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Overnight: 5:00 PM - 9:00 AM</p>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Open 7 days a week</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-6 md:p-7 text-center bg-cream transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm">
      <div class="text-[28px] md:text-[30px] mb-3">📍</div>
      <h4 class="text-[16px] font-bold text-gold-deep tracking-[0.5px] mb-2 uppercase">Our Location</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Pulong Buhangin, Santa Maria, Bulacan</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-6 md:p-7 text-center bg-cream transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm">
      <div class="text-[28px] md:text-[30px] mb-3">📞</div>
      <h4 class="text-[16px] font-bold text-gold-deep tracking-[0.5px] mb-2 uppercase">Phone Number</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">+63 912 345 6789</p>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">+63 998 765 4321</p>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">8:00 AM - 8:00 PM</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-6 md:p-7 text-center bg-cream transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm">
      <div class="text-[28px] md:text-[30px] mb-3">✉️</div>
      <h4 class="text-[16px] font-bold text-gold-deep tracking-[0.5px] mb-2 uppercase">Email Address</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">info@lordanesplace.com</p>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">bookings@lordanesplace.com</p>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Reply within 24 hours</p>
    </div>
  </div>
</section>


<!-- MAP SECTION (centered) -->
<section class="py-[50px] md:py-[80px] px-[5%] lg:px-[8%] bg-cream relative">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="text-center mb-8 md:mb-12">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">FIND US</span>
    <h2 class="font-heading text-[28px] md:text-[40px] font-bold text-warm-black">Our <span class="italic text-gold-deep">Location</span></h2>
    <p class="text-[16px] md:text-[16px] text-warm-black/90 font-normal mt-2">Visit us at Pulong Buhangin, Santa Maria, Bulacan</p>
  </div>

  <div class="flex justify-center items-center">
    <div class="w-full max-w-[900px] rounded-xl overflow-hidden border border-gold-deep/30 shadow-sm" style="aspect-ratio: 16/9;">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1005.512394744185!2d120.99150997422898!3d14.861853705944357!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397a90028979e19%3A0x97955c24c4d19ac4!2sLorDane&#39;s%20Place!5e1!3m2!1sen!2sph!4v1775222048800!5m2!1sen!2sph"
        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
      </iframe>
    </div>
  </div>
</section>


<!-- CTA -->
<section class="py-[50px] md:py-[70px] px-[5%] lg:px-[8%] text-center relative border-y border-gold-deep/25" style="background: linear-gradient(135deg, #E8C96D22, #F2EDE4);">
  <h2 class="font-heading text-[28px] md:text-[40px] mb-4 text-warm-black">
    Ready to Visit <span class="italic text-gold-deep">LorDane's Place?</span>
  </h2>
  <p class="text-[15px] md:text-[17px] text-warm-black/90 font-normal mb-7 md:mb-9">Book your date today and experience it for yourself.</p>
  <div class="flex flex-wrap justify-center gap-3">
    <a href="{{ route('booking') }}" class="inline-block no-underline px-7 md:px-9 py-3 md:py-4 text-[16px] md:text-[15px] font-bold tracking-wide rounded-[3px] bg-gold-deep text-white transition-all duration-300 hover:bg-gold-mid hover:-translate-y-0.5">
      Reserve Your Date
    </a>
    <a href="{{ route('discover') }}" class="inline-block no-underline px-7 md:px-9 py-3 md:py-4 text-[16px] md:text-[15px] font-bold tracking-wide rounded-[3px] border border-warm-black/30 text-warm-black transition-all duration-300 hover:border-gold-deep hover:text-gold-deep hover:-translate-y-0.5">
      Discover the Venue
    </a>
  </div>
</section>

@include('partials._footer')
@include('chat-assistant')
</body>
</html>