<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="bg-off-white text-warm-black font-body">

  <section class="auth-bg min-h-screen relative flex items-center justify-center px-5 py-4"
           style="background: linear-gradient(rgba(26,18,8,0.7), rgba(26,18,8,0.7)), url('{{ asset('images/LORDANES_BG.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="absolute inset-0 bg-gradient-to-r from-warm-black/40 to-warm-black/20"></div>

    <div class="relative z-10 w-full max-w-[1150px] grid grid-cols-1 lg:grid-cols-[1fr_470px] gap-10 items-center">

      <!-- Left side -->
      <div class="px-2 lg:px-4 text-center lg:text-left">
        <p class="text-[12px] tracking-[4px] text-gold-light font-bold mb-4">CREATE ACCOUNT</p>
        <h1 class="font-heading text-[36px] sm:text-[46px] lg:text-[58px] leading-[1.1] text-white mb-4">
          Join <span class="text-gold-light italic">LorDane's Place</span>
        </h1>
        <p class="max-w-[560px] text-[15px] md:text-[16px] leading-[1.8] text-white/70 mx-auto lg:mx-0">
          Create your account to start booking events, checking schedules,
          and exploring LorDane's Place online.
        </p>
      </div>

      <div class="flex justify-center">
        <div class="w-full bg-warm-black/80 backdrop-blur-sm border border-gold-deep/20 rounded-2xl p-5 md:p-7 shadow-2xl">
          <h2 class="font-heading text-[26px] md:text-[30px] text-white mb-1">Sign Up</h2>
          <p class="text-white/60 text-[14px] mb-4">Create your account to get started</p>

          <form method="POST" action="{{ route('register.post') }}">
            @csrf

            @if ($errors->any())
              <div class="bg-red-500/15 border border-red-500 text-red-400 p-3 rounded-md mb-4 text-[15px]">
                <ul class="list-none">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="mb-3">
              <label class="block text-[13px] font-bold text-white/90 mb-1.5">Full Name</label>
              <input type="text" name="name" placeholder="Enter your full name" value="{{ old('name') }}" required
                class="w-full py-2.5 px-4 rounded-lg border border-gold-deep/20 bg-white/5 text-white text-[15px] outline-none transition-all focus:border-gold-deep focus:bg-white/8 placeholder:text-white/40 font-body" />
              @error('name') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
              <label class="block text-[13px] font-bold text-white/90 mb-1.5">Email Address</label>
              <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required
                class="w-full py-2.5 px-4 rounded-lg border border-gold-deep/20 bg-white/5 text-white text-[15px] outline-none transition-all focus:border-gold-deep focus:bg-white/8 placeholder:text-white/40 font-body" />
              @error('email') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
              <label class="block text-[13px] font-bold text-white/90 mb-1.5">Password</label>
              <div class="relative flex items-center">
                <input type="password" name="password" id="password" placeholder="Create a password" required
                  class="w-full py-2.5 px-4 rounded-lg border border-gold-deep/20 bg-white/5 text-white text-[15px] outline-none transition-all focus:border-gold-deep focus:bg-white/8 placeholder:text-white/40 font-body pr-12" />
                <button type="button" onclick="togglePassword('password', this)" class="absolute right-4 flex items-center justify-center bg-transparent border-none cursor-pointer text-white/40 hover:text-gold-light transition-colors focus:outline-none p-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.789 0 8.601 3.049 9.964 6.678.045.12.045.26 0 .381C20.601 15.951 16.789 19 12 19c-4.789 0-8.601-3.049-9.964-6.678z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </button>
              </div>
              @error('password') <span class="text-red-400 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
              <label class="block text-[13px] font-bold text-white/90 mb-1.5">Confirm Password</label>
              <div class="relative flex items-center">
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password" required
                  class="w-full py-2.5 px-4 rounded-lg border border-gold-deep/20 bg-white/5 text-white text-[15px] outline-none transition-all focus:border-gold-deep focus:bg-white/8 placeholder:text-white/40 font-body pr-12" />
                <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-4 flex items-center justify-center bg-transparent border-none cursor-pointer text-white/40 hover:text-gold-light transition-colors focus:outline-none p-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.789 0 8.601 3.049 9.964 6.678.045.12.045.26 0 .381C20.601 15.951 16.789 19 12 19c-4.789 0-8.601-3.049-9.964-6.678z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </button>
              </div>
            </div>

            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}" style="margin-bottom:16px;"></div>
            @error('recaptcha')
              <span class="text-red-400 text-[12px] block mb-3">{{ $message }}</span>
            @enderror

            <button type="submit" class="w-full py-3 border-none rounded-lg bg-gold-deep text-warm-black text-[14px] font-bold tracking-[2px] cursor-pointer transition-all hover:bg-gold-mid hover:-translate-y-0.5 mb-4">CREATE ACCOUNT</button>
          </form>

          <div class="text-center text-white/40 text-[14px] my-3">or</div>
          <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-2.5 py-3 rounded-lg border border-white/20 bg-white text-warm-black text-[15px] font-medium hover:bg-gray-100 transition-colors mb-4 no-underline">
            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" class="w-5 h-5" />
            Continue with Google
          </a>

          <p class="text-center text-[14px] text-white/60 mb-3">
            Already have an account? <a href="{{ route('login') }}" class="text-gold-deep no-underline hover:text-gold-light">Login</a>
          </p>
          <a href="{{ route('home') }}" class="text-gold-deep text-[14px] no-underline hover:text-gold-light inline-block">← Back to Home</a>
        </div>
      </div>
    </div>
  </section>

  <script>
    function togglePassword(inputId, btn) {
      const input = document.getElementById(inputId);
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      
      // Update icon
      btn.innerHTML = isPassword 
        ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.789 0 8.601 3.049 9.964 6.678.045.12.045.26 0 .381C20.601 15.951 16.789 19 12 19c-4.789 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`;
    }
  </script>
</body>
</html>