<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $title }} | LorDane's Place</title>
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
        <div class="bg-warm-black/80 backdrop-blur-sm border border-gold-deep/20 rounded-2xl p-7 md:p-9 shadow-2xl text-center">

          {{-- Icon --}}
          <div style="display: flex; justify-content: center; margin-bottom: 16px;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: {{ $success ? 'rgba(34,197,94,0.12)' : 'rgba(239,68,68,0.12)' }}; border: 1px solid {{ $success ? 'rgba(34,197,94,0.3)' : 'rgba(239,68,68,0.3)' }}; display: flex; align-items: center; justify-content: center;">
              @if($success)
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4ade80" style="width: 28px; height: 28px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
              @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f87171" style="width: 28px; height: 28px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              @endif
            </div>
          </div>

          <h2 class="font-heading" style="font-size: 26px; color: #fff; margin-bottom: 10px;">{{ $title }}</h2>

          <p style="color: rgba(255,255,255,0.7); font-size: 14px; margin-bottom: 24px; line-height: 1.6;">
            {{ $message }}
          </p>

          <a href="{{ route('login') }}" style="display: inline-block; width: 100%; box-sizing: border-box; padding: 12px; border: none; border-radius: 8px; background: #BF9B30; color: #1a1208; font-size: 14px; font-weight: 700; letter-spacing: 1px; text-decoration: none;">
            GO TO LOGIN
          </a>

        </div>
      </div>
    </div>
  </section>

</body>
</html>
