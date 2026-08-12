<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Booking Confirmed | LorDane's Place</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body min-h-screen flex items-center justify-center p-5 md:p-10">

  <div class="bg-cream border border-gold-deep/25 rounded-2xl p-10 md:p-14 text-center max-w-[500px] w-full shadow-sm">
    <div class="text-[56px] md:text-[60px] mb-5">🎉</div>
    <h1 class="font-heading text-[28px] md:text-[32px] font-bold text-gold-deep mb-3">Booking Submitted!</h1>
    
    @if($booking)
      <div class="mb-5">
          <p class="text-[12px] tracking-[1px] text-warm-black/50 font-bold uppercase mb-1">Booking Reference</p>
          <p class="text-[20px] font-bold text-gold-deep">{{ $booking->booking_number }}</p>
      </div>
    @endif

    <p class="text-warm-black/90 text-[16px] leading-[1.7] mb-7">Your reservation request has been received. We will review your booking and get back to you shortly.</p>
    <div class="bg-green-400/10 border border-green-400 text-green-600 p-3.5 rounded-lg text-[15px] mb-7">✓ Your booking is currently <strong>pending approval</strong> from our team.</div>
    
    <div class="mb-7 text-[13px] text-warm-black/60 italic">Your official receipt will be available once the admin confirms your payment.</div>
    <div class="flex gap-3 justify-center flex-wrap">
      <a href="{{ route('profile') }}" class="bg-gold-deep text-white px-5 md:px-6 py-3 rounded-md no-underline font-bold text-[15px] tracking-[1px] transition-all hover:bg-gold-mid">VIEW MY PROFILE</a>
      <a href="{{ route('home') }}" class="bg-transparent border border-warm-black/25 text-warm-black/90 px-5 md:px-6 py-3 rounded-md no-underline text-[15px] transition-all hover:border-warm-black hover:text-warm-black">Back to Home</a>
    </div>
  </div>

</body>
</html>