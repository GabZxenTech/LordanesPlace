<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Privacy Policy - LorDane's Place</title>
<meta name="description" content="How LorDane's Place collects, uses and protects your personal information.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
  /* Privacy policy page */
  .pv-wrap { max-width: 860px; margin: 0 auto; }
  .pv-card { background: #fffcf5; border: 1px solid rgba(184,134,11,0.2); border-radius: 12px; padding: 22px 26px; margin-bottom: 16px; }
  .pv-card h2 { font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 700; color: #1a1208; margin: 0 0 10px; }
  .pv-card p, .pv-card li { font: 15px/1.75 'Jost', sans-serif; color: #5c4a33; }
  .pv-card p { margin: 0 0 10px; }
  .pv-card ul { margin: 0 0 6px; padding-left: 20px; }
  .pv-card li { margin-bottom: 4px; }
  .pv-updated { font: 13px 'Jost', sans-serif; color: #8f7c62; margin-bottom: 20px; }
  .pv-card a { color: #b8860b; font-weight: 600; }
</style>
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<!-- PRIVACY HERO -->
<section class="h-[280px] md:h-[350px] flex justify-center items-center text-center text-white px-5 relative"
         style="background: linear-gradient(rgba(26,18,8,0.5), rgba(26,18,8,0.7)), url('{{ asset('images/LORDANES_BG.jpg') }}'); background-size: cover; background-position: center;">
  <div class="gold-divider absolute bottom-0 left-0 right-0"></div>
  <div>
    <p class="text-[11px] md:text-[12px] tracking-[4px] text-gold-light font-bold mb-3">YOUR INFORMATION</p>
    <h1 class="font-heading text-[38px] md:text-[52px] font-bold text-white mb-2">Privacy <span class="italic text-gold-light">Policy</span></h1>
    <p class="text-white/60 text-[15px] md:text-[16px] tracking-wide"><a href="{{ route('home') }}" class="hover:text-gold-light transition-colors">Home</a> / Privacy Policy</p>
  </div>
</section>

<section class="py-[50px] md:py-[70px] px-[5%] lg:px-[8%] bg-off-white">
  <div class="pv-wrap">
    <p class="pv-updated">Last updated: October 2026. This policy follows the Data Privacy Act of 2012 (Republic Act No. 10173) of the Philippines.</p>

    <div class="pv-card">
      <h2>What we collect</h2>
      <ul>
        <li><strong>Account details:</strong> your name, email address and password (stored only as a secure hash), and your profile picture if you add one.</li>
        <li><strong>Google sign-in:</strong> if you continue with Google, we receive your name, email address and profile photo from Google.</li>
        <li><strong>Bookings:</strong> the package, date, number of guests, notes, payment records, venue visit schedules and reschedule requests.</li>
        <li><strong>Messages:</strong> chat messages you send us and notifications about your bookings.</li>
      </ul>
    </div>

    <div class="pv-card">
      <h2>How we use it</h2>
      <ul>
        <li>To create and manage your account and your reservations.</li>
        <li>To confirm bookings, record payments, schedule venue visits and send you updates.</li>
        <li>To send verification codes and password reset codes to your email.</li>
        <li>To answer your questions and protect the site against abuse.</li>
      </ul>
      <p>We do not sell your information or use it for advertising.</p>
    </div>

    <div class="pv-card">
      <h2>Who can see it</h2>
      <p>Our admin team can see your account and booking details to serve you. We use trusted services to run the site: web hosting (Render), our database (Supabase), email delivery (Brevo), Google reCAPTCHA to block bots, and Google sign-in if you choose it. They only process data for these purposes.</p>
    </div>

    <div class="pv-card">
      <h2>How we protect it</h2>
      <ul>
        <li>Passwords and verification codes are stored as hashes, never as plain text.</li>
        <li>The site is served over a secure (HTTPS) connection, and login sessions are protected cookies.</li>
        <li>Customers can only see their own bookings, notifications and chats.</li>
      </ul>
    </div>

    <div class="pv-card">
      <h2>How long we keep it</h2>
      <p>We keep your account and booking records while your account is active. Records of paid bookings are kept for the resort's accounting and reports, even after an account is deleted.</p>
    </div>

    <div class="pv-card">
      <h2>Your choices</h2>
      <ul>
        <li>Update your name, email, password and profile picture in <a href="{{ route('account.settings') }}">Account Settings</a>.</li>
        <li>Delete your account there once you have no pending, approved or ongoing bookings. Your personal details, notifications and chats are then removed.</li>
        <li>Ask us what information we hold about you, or ask for a correction, using the contact details below.</li>
      </ul>
    </div>

    <div class="pv-card">
      <h2>Contact us</h2>
      <p>For privacy questions or requests, reach us at <a href="mailto:lordanesplace@gmail.com">lordanesplace@gmail.com</a> or <a href="tel:09177455049">0917 745 5049</a>. You can also read our <a href="{{ route('terms') }}">Client Guidelines for Booking</a> and <a href="{{ route('faq') }}">FAQs</a>.</p>
    </div>
  </div>
</section>

@include('partials._footer')
@include('chat-assistant')
</body>
</html>
