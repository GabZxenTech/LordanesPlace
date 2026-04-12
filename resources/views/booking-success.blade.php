<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Booking Confirmed | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body min-h-screen flex items-center justify-center p-5 md:p-10">

  <div class="bg-cream border border-gold-deep/25 rounded-2xl p-10 md:p-14 text-center max-w-[500px] w-full shadow-sm">
    <div class="text-[56px] md:text-[60px] mb-5">🎉</div>
    <h1 class="font-heading text-[28px] md:text-[32px] font-bold text-gold-deep mb-3">Booking Submitted!</h1>
    <p class="text-warm-black/90 text-[16px] leading-[1.7] mb-7">Your reservation request has been received. We will review your booking and get back to you shortly.</p>
    <div class="bg-green-400/10 border border-green-400 text-green-600 p-3.5 rounded-lg text-[15px] mb-7">✓ Your booking is currently <strong>pending approval</strong> from our team.</div>
    <div class="flex gap-3 justify-center flex-wrap">
      <a href="{{ route('my.bookings') }}" class="bg-gold-deep text-white px-5 md:px-6 py-3 rounded-md no-underline font-bold text-[15px] tracking-[1px] transition-all hover:bg-gold-mid">VIEW MY BOOKINGS</a>
      <a href="{{ route('home') }}" class="bg-transparent border border-warm-black/25 text-warm-black/90 px-5 md:px-6 py-3 rounded-md no-underline text-[15px] transition-all hover:border-warm-black hover:text-warm-black">Back to Home</a>
    </div>
  </div>

</body>
</html>