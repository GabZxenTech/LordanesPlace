<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - LorDane's Place</title>
<meta name="description" content="Get in touch with LorDane's Place. Find our location, opening hours, phone number, and email address.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Leaflet map height inside the aspect-ratio container */
    #lordanes-map {
        width: 100%;
        height: 100%;
        min-height: 400px;
        position: relative;
        z-index: 1;
        isolation: isolate;
    }

    /* Match your gold theme for the popup */
    .leaflet-popup-content-wrapper {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        border: 1px solid #C9A84C33;
    }

    .map-popup h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 17px;
        font-weight: 700;
        color: #1a1208;
        margin-bottom: 4px;
    }

    .map-popup p {
        font-family: 'Jost', sans-serif;
        font-size: 13px;
        color: #555;
        margin: 2px 0;
    }

    .map-popup .hours {
        margin-top: 8px;
    }
</style>
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<!-- CONTACT HERO -->
<section class="contact-hero-bg h-[280px] md:h-[350px] flex justify-center items-center text-center text-white px-5 relative"
         style="background: linear-gradient(rgba(26,18,8,0.5), rgba(26,18,8,0.7)), url('{{ asset('images/LORDANES_BG.jpg') }}'); background-size: cover; background-position: center;">
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
      <div class="mb-3 flex justify-center text-gold-deep"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
      <h4 class="text-[16px] font-bold text-gold-deep tracking-[0.5px] mb-2 uppercase">Opening Hours</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Day Tour: 8:00 AM - 3:00 PM</p>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Night Swimming: 5:00 PM - 9:00 AM</p>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Open 7 days a week</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-6 md:p-7 text-center bg-cream transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm">
      <div class="mb-3 flex justify-center text-gold-deep"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
      <h4 class="text-[16px] font-bold text-gold-deep tracking-[0.5px] mb-2 uppercase">Our Location</h4>
      <p class="text-[15px] text-warm-black/90 font-normal leading-[1.7]">Pulong Buhangin, Santa Maria, Bulacan</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-6 md:p-7 text-center bg-cream transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm">
      <div class="mb-3 flex justify-center text-gold-deep"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
      <h4 class="text-[16px] font-bold text-gold-deep tracking-[0.5px] mb-2 uppercase">Phone Number</h4>
      <p class="text-[15px] font-bold text-warm-black leading-[1.7]">0917 745 5049</p>
      <p class="text-[14px] text-warm-black/70 font-normal leading-[1.7]">Available 8:00 AM - 8:00 PM</p>
    </div>
    <div class="border border-gold-deep/25 rounded-lg p-6 md:p-7 text-center bg-cream transition-all duration-300 hover:border-gold-deep hover:-translate-y-1 hover:shadow-sm">
      <div class="mb-3 flex justify-center text-gold-deep"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></div>
      <h4 class="text-[16px] font-bold text-gold-deep tracking-[0.5px] mb-2 uppercase">Email Address</h4>
      <p class="text-[15px] font-bold text-warm-black leading-[1.7]">lordanesplace@gmail.com</p>
      <p class="text-[14px] text-warm-black/70 font-normal leading-[1.7]">Reply within 24 hours</p>
    </div>
  </div>
</section>


<!-- MAP SECTION (centered) -->
<section class="py-[50px] md:py-[80px] px-[5%] lg:px-[8%] bg-cream relative z-0">
  <div class="gold-divider absolute top-0 left-0 right-0"></div>
  <div class="text-center mb-8 md:mb-12">
    <span class="text-[12px] tracking-[4px] text-gold-deep font-bold mb-3 block">FIND US</span>
    <h2 class="font-heading text-[28px] md:text-[40px] font-bold text-warm-black">Our <span class="italic text-gold-deep">Location</span></h2>
    <p class="text-[16px] md:text-[16px] text-warm-black/90 font-normal mt-2">Visit us at Pulong Buhangin, Santa Maria, Bulacan</p>
  </div>

  <div class="flex justify-center items-center">
    <div class="w-full max-w-[900px] rounded-xl overflow-hidden border border-gold-deep/30 shadow-sm relative z-0" style="aspect-ratio: 16/9;">
      <div id="lordanes-map"></div>
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

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ── Exact coordinates ng LorDane's Place ─────────────────────────
    const LORDANES = { lat: 14.8617228, lng: 120.9910017 };

    // ── Initialize map ────────────────────────────────────────────────
    const map = L.map('lordanes-map', {
        center: [LORDANES.lat, LORDANES.lng],
        zoom: 17,
        zoomControl: true,
        scrollWheelZoom: false
    });

    // ── Tile layers (Satellite + Street) ──────────────────────────────
    const satellite = L.tileLayer(
        'https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
        { attribution: '© Google Satellite', maxZoom: 21 }
    );
    const street = L.tileLayer(
        'https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',
        { attribution: '© Google Maps', maxZoom: 21 }
    );

    satellite.addTo(map); // Default: satellite

    L.control.layers(
        { "Satellite": satellite, "Street": street },
        null,
        { position: 'topright', collapsed: false }
    ).addTo(map);

    // ── Keep map layout and tiles stable on resize ────────────────────
    window.addEventListener('resize', function() {
        map.invalidateSize();
    });

    // ── Bubble icon helper ────────────────────────────────────────────
    function bubbleIcon(iconSvg, size = 36) {
        return L.divIcon({
            html: `<div style="
                width: ${size}px;
                height: ${size}px;
                background: #C9A84C;
                border: 2.5px solid #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(0,0,0,0.35);
                line-height: 1;
            ">${iconSvg}</div>`,
            iconSize: [size, size],
            iconAnchor: [size / 2, size / 2],
            popupAnchor: [0, -(size / 2 + 4)],
            className: ''
        });
    }

    // ── Markers ───────────────────────────────────────────────────────
    const ICONS = {
        home: '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
        door: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="1"/><circle cx="14" cy="12" r="1"/></svg>',
        waves: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/><path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/></svg>',
        star: '<svg width="14" height="14" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
        bell: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>',
    };

    const locations = [
        {
            lat: 14.8617228, lng: 120.9910017,
            label: "LorDane's Place",
            desc: "Pulong Buhangin, Santa Maria, Bulacan",
            extra: "<div class=\"hours\"><p>Day Tour: 8:00 AM – 3:00 PM</p><p>Overnight: 5:00 PM – 9:00 AM</p></div>",
            icon: ICONS.home, open: true, main: true
        },
        {
            lat: 14.861744, lng: 120.99081,
            label: "Main Entrance",
            desc: "Main entrance ng LorDane's Place",
            extra: "", icon: ICONS.door, open: false
        },
        {
            lat: 14.862122, lng: 120.990891,
            label: "Pool Area",
            desc: "Swimming pool area",
            extra: "", icon: ICONS.waves, open: false
        },
        {
            lat: 14.862078, lng: 120.990784,
            label: "Venue Hall",
            desc: "Events venue hall",
            extra: "", icon: ICONS.star, open: false
        },
        {
            lat: 14.862186, lng: 120.991061,
            label: "Lobby Area",
            desc: "Lobby / reception area",
            extra: "", icon: ICONS.bell, open: false
        }
    ];

    locations.forEach(loc => {
        const m = L.marker([loc.lat, loc.lng], { icon: bubbleIcon(loc.icon, loc.main ? 32 : 26) }).addTo(map);

        m.bindPopup(
            `<div class="map-popup" style="padding:4px 2px;min-width:180px;">
                <p class="gold-label">LorDane's Place</p>
                <h3>${loc.label}</h3>
                <p>${loc.desc}</p>
                ${loc.extra}
            </div>`,
            { maxWidth: 240 }
        );

        if (loc.open) m.openPopup();
    });
</script>
</body>
</html>