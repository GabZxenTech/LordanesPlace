<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password | LorDane's Place</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-off-white text-warm-black font-body">

  <section class="auth-bg min-h-screen relative flex items-center justify-center px-5 py-10"
           style="background: linear-gradient(rgba(26,18,8,0.7), rgba(26,18,8,0.7)), url('{{ asset('images/LORDANES_BG.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="absolute inset-0 bg-gradient-to-r from-warm-black/40 to-warm-black/20"></div>

    <div class="relative z-10 w-full flex justify-center">
      <div style="max-width: 440px; width: 100%;">
        <div class="bg-warm-black/80 backdrop-blur-sm border border-gold-deep/20 rounded-2xl p-7 md:p-9 shadow-2xl">

          <h2 class="font-heading" style="font-size: 28px; color: #fff; margin-bottom: 6px; text-align: center;">Reset Your Password</h2>
          <p style="color: rgba(255,255,255,0.6); font-size: 13px; margin-bottom: 24px; text-align: center;">Choose a new password for {{ $email }}</p>

          @if ($errors->any())
            <div style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.35); color: #f87171; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px;">
              @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          @endif

          <form method="POST" action="{{ route('password.otp.reset') }}">
            @csrf

            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: rgba(255,255,255,0.5); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">New Password</label>
              <div class="relative flex items-center">
                <input type="password" name="password" id="password" required minlength="8"
                  style="width: 100%; background: rgba(255,255,255,0.06); border: 1px solid rgba(191,155,48,0.3); color: #fff; padding: 12px 44px 12px 14px; border-radius: 8px; font-size: 14px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
                <button type="button" onclick="togglePassword('password', this)" class="absolute right-4 flex items-center justify-center bg-transparent border-none cursor-pointer text-white/40 hover:text-gold-light transition-colors focus:outline-none p-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.789 0 8.601 3.049 9.964 6.678.045.12.045.26 0 .381C20.601 15.951 16.789 19 12 19c-4.789 0-8.601-3.049-9.964-6.678z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </button>
              </div>
              <span style="font-size: 11px; color: rgba(255,255,255,0.35); margin-top: 6px; display: block;">Min. 8 characters, with uppercase, lowercase, a number, and a symbol.</span>
            </div>

            <div style="margin-bottom: 24px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: rgba(255,255,255,0.5); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Confirm New Password</label>
              <div class="relative flex items-center">
                <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                  style="width: 100%; background: rgba(255,255,255,0.06); border: 1px solid rgba(191,155,48,0.3); color: #fff; padding: 12px 44px 12px 14px; border-radius: 8px; font-size: 14px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
                <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-4 flex items-center justify-center bg-transparent border-none cursor-pointer text-white/40 hover:text-gold-light transition-colors focus:outline-none p-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.789 0 8.601 3.049 9.964 6.678.045.12.045.26 0 .381C20.601 15.951 16.789 19 12 19c-4.789 0-8.601-3.049-9.964-6.678z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </button>
              </div>
            </div>

            <button type="submit"
              style="width: 100%; padding: 12px; border: none; border-radius: 8px; background: #BF9B30; color: #1a1208; font-size: 14px; font-weight: 700; letter-spacing: 2px; cursor: pointer;">
              RESET PASSWORD
            </button>
          </form>

        </div>
      </div>
    </div>
  </section>

  <script>
    function togglePassword(inputId, btn) {
      const input = document.getElementById(inputId);
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';

      btn.innerHTML = isPassword
        ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.789 0 8.601 3.049 9.964 6.678.045.12.045.26 0 .381C20.601 15.951 16.789 19 12 19c-4.789 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`;
    }
  </script>

</body>
</html>
