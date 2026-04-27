<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-off-white text-warm-black font-body">

  <section class="auth-bg min-h-screen relative flex items-center justify-center px-5 py-10"
           style="background: linear-gradient(rgba(26,18,8,0.7), rgba(26,18,8,0.7)), url('{{ asset('images/LORDANES_BG.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="absolute inset-0 bg-gradient-to-r from-warm-black/40 to-warm-black/20"></div>

    <div class="relative z-10 w-full max-w-[1150px] grid grid-cols-1 lg:grid-cols-[1fr_470px] gap-10 items-center">

      <!-- Left side -->
      <div class="px-2 lg:px-4 text-center lg:text-left">
        <p class="text-[12px] tracking-[4px] text-gold-light font-bold mb-4">WELCOME BACK</p>
        <h1 class="font-heading text-[36px] sm:text-[46px] lg:text-[58px] leading-[1.1] text-white mb-4">
          Login to <span class="text-gold-light italic">LorDane's Place</span>
        </h1>
        <p class="max-w-[560px] text-[15px] md:text-[16px] leading-[1.8] text-white/70 mx-auto lg:mx-0">
          Access your account to manage bookings, view reservations,
          and explore LorDane's Place with ease.
        </p>
      </div>

      <!-- Right side (card) -->
      <div class="flex justify-center">
        <div class="w-full bg-warm-black/80 backdrop-blur-sm border border-gold-deep/20 rounded-2xl p-7 md:p-9 shadow-2xl">
          <h2 class="font-heading text-[30px] md:text-[34px] text-white mb-2">Login</h2>
          <p class="text-white/60 text-[16px] mb-7">Sign in to continue to your account</p>

          <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-4">
              <label class="block text-[15px] font-bold text-white/90 mb-2">Email Address</label>
              <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required
                class="w-full py-3.5 px-4 rounded-lg border border-gold-deep/20 bg-white/5 text-white text-[16px] outline-none transition-all focus:border-gold-deep focus:bg-white/8 placeholder:text-white/40 font-body" />
              @error('email') <span class="text-red-400 text-[12px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
              <label class="block text-[15px] font-bold text-white/90 mb-2">Password</label>
              <input type="password" name="password" placeholder="Enter your password" required
                class="w-full py-3.5 px-4 rounded-lg border border-gold-deep/20 bg-white/5 text-white text-[16px] outline-none transition-all focus:border-gold-deep focus:bg-white/8 placeholder:text-white/40 font-body" />
              @error('password') <span class="text-red-400 text-[12px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-between items-center gap-2.5 mb-5 text-[15px]">
              <label class="flex items-center gap-2 text-white/60 cursor-pointer">
                <input type="checkbox" name="remember" class="accent-gold-deep" />
                <span>Remember me</span>
              </label>
              <a href="#" class="text-gold-deep no-underline hover:text-gold-light transition-colors">Forgot password?</a>
            </div>

            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}" style="margin-bottom:16px;"></div>
            @error('recaptcha')
              <span class="text-red-400 text-[12px] block mb-3">{{ $message }}</span>
            @enderror

            <button type="submit" class="w-full py-3.5 border-none rounded-lg bg-gold-deep text-warm-black text-[15px] font-bold tracking-[2px] cursor-pointer transition-all hover:bg-gold-mid hover:-translate-y-0.5 mb-5">LOGIN</button>
          </form>

          <div class="text-center text-white/40 text-[16px] my-4">or</div>
          <button class="w-full flex items-center justify-center gap-2.5 py-3 rounded-lg border border-white/20 bg-white text-warm-black text-[15px] font-medium cursor-not-allowed opacity-70 mb-4" disabled title="Coming soon!">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" class="w-5 h-5" />
            Continue with Google
          </button>

          <p class="text-center text-[16px] text-white/60 mb-4">
            Don't have an account? <a href="{{ route('register') }}" class="text-gold-deep no-underline hover:text-gold-light">Sign Up</a>
          </p>
          <a href="{{ route('home') }}" class="text-gold-deep text-[16px] no-underline hover:text-gold-light inline-block">← Back to Home</a>
        </div>
      </div>
    </div>
  </section>

</body>
</html>