<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verify Email | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body">

  <section class="auth-bg min-h-screen relative flex items-center justify-center px-5 py-10"
           style="background: linear-gradient(rgba(26,18,8,0.7), rgba(26,18,8,0.7)), url('{{ asset('images/LORDANES_BG.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="absolute inset-0 bg-gradient-to-r from-warm-black/40 to-warm-black/20"></div>

    <div class="relative z-10 w-full max-w-[600px] flex justify-center">
      <div class="w-full bg-warm-black/80 backdrop-blur-sm border border-gold-deep/20 rounded-2xl p-7 md:p-9 shadow-2xl text-center">
        <h2 class="font-heading text-[30px] md:text-[34px] text-white mb-4">Verify Your Email</h2>
        
        <p class="text-white/80 text-[16px] mb-6 leading-relaxed">
          Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </p>

        @if (session('message'))
            <div class="bg-green-500/20 border border-green-500 text-green-400 p-3 rounded-md mb-6 text-[15px]">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full py-3.5 border-none rounded-lg bg-gold-deep text-warm-black text-[15px] font-bold tracking-[2px] cursor-pointer transition-all hover:bg-gold-mid hover:-translate-y-0.5 mb-5">
                RESEND VERIFICATION EMAIL
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-white/60 hover:text-gold-light text-[15px] bg-transparent border-none cursor-pointer underline">
                Log Out
            </button>
        </form>

      </div>
    </div>
  </section>

</body>
</html>
