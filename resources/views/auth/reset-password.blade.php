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

          <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: rgba(255,255,255,0.5); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">New Password</label>
              <input type="password" name="password" required minlength="8"
                style="width: 100%; background: rgba(255,255,255,0.06); border: 1px solid rgba(191,155,48,0.3); color: #fff; padding: 12px 14px; border-radius: 8px; font-size: 14px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
            </div>

            <div style="margin-bottom: 24px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: rgba(255,255,255,0.5); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Confirm New Password</label>
              <input type="password" name="password_confirmation" required minlength="8"
                style="width: 100%; background: rgba(255,255,255,0.06); border: 1px solid rgba(191,155,48,0.3); color: #fff; padding: 12px 14px; border-radius: 8px; font-size: 14px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
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

</body>
</html>
