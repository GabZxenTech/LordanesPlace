<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password | LorDane's Place</title>
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

          {{-- Icon --}}
          <div style="display: flex; justify-content: center; margin-bottom: 16px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(191,155,48,0.12); border: 1px solid rgba(191,155,48,0.3); display: flex; align-items: center; justify-content: center;">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#BF9B30" style="width: 28px; height: 28px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
              </svg>
            </div>
          </div>

          <h2 class="font-heading" style="font-size: 30px; color: #fff; margin-bottom: 8px; text-align: center;">Forgot Password?</h2>
          <p style="color: rgba(255,255,255,0.6); font-size: 14px; margin-bottom: 24px; text-align: center; line-height: 1.6;">
            Enter the email address linked to your account and we'll send you a verification code to reset your password.
          </p>

          @if (session('message'))
            <div style="background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.35); color: #4ade80; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px;">
              {{ session('message') }}
            </div>
          @endif

          @if ($errors->any())
            <div style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.35); color: #f87171; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px;">
              @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          @endif

          <form method="POST" action="{{ route('password.otp.send') }}">
            @csrf

            <div style="margin-bottom: 20px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: rgba(255,255,255,0.5); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Email Address</label>
              <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required autofocus
                style="width: 100%; background: rgba(255,255,255,0.06); border: 1px solid rgba(191,155,48,0.3); color: #fff; padding: 12px 14px; border-radius: 8px; font-size: 14px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
            </div>

            <button type="submit"
              style="width: 100%; padding: 12px; border: none; border-radius: 8px; background: #BF9B30; color: #1a1208; font-size: 14px; font-weight: 700; letter-spacing: 2px; cursor: pointer; margin-bottom: 16px;">
              SEND VERIFICATION CODE
            </button>
          </form>

          <p style="text-align: center;">
            <a href="{{ route('login') }}" style="color: #BF9B30; font-size: 13px; text-decoration: none;">&larr; Back to Login</a>
          </p>

        </div>
      </div>
    </div>
  </section>

</body>
</html>
