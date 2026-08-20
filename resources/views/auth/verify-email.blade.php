<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verify Email | LorDane's Place</title>
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
            <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(191,155,48,0.12); border: 1px solid rgba(191,155,48,0.3); display: flex; align-items: center; justify-content: center;">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#BF9B30" style="width: 28px; height: 28px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
              </svg>
            </div>
          </div>

          <h2 class="font-heading" style="font-size: 30px; color: #fff; margin-bottom: 8px;">Verify Your Email</h2>

          <p style="color: rgba(255,255,255,0.65); font-size: 14px; margin-bottom: 4px; line-height: 1.6;">
            We've sent a 6-digit verification code to
          </p>
          <p style="color: #BF9B30; font-weight: 700; font-size: 14px; margin-bottom: 24px;">
            {{ $maskedEmail }}
          </p>

          {{-- Success Message --}}
          @if (session('message'))
            <div style="background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.35); color: #4ade80; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px;">
              {{ session('message') }}
            </div>
          @endif

          {{-- Email Delivery Warning --}}
          @if (session('email_warning'))
            <div style="background: rgba(249,115,22,0.12); border: 1px solid rgba(249,115,22,0.35); color: #fb923c; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px;">
              ⚠️ {{ session('email_warning') }}
            </div>
          @endif

          {{-- OTP Error --}}
          @if ($errors->has('otp'))
            <div style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.35); color: #f87171; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px;">
              {{ $errors->first('otp') }}
            </div>
          @endif

          {{-- Resend Error --}}
          @if ($errors->has('resend'))
            <div style="background: rgba(234,179,8,0.12); border: 1px solid rgba(234,179,8,0.35); color: #facc15; padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 13px;">
              {{ $errors->first('resend') }}
            </div>
          @endif

          {{-- OTP Input Form --}}
          <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
            @csrf
            <input type="hidden" name="otp" id="otpHidden" value="">

            <div style="display: flex; gap: 8px; justify-content: center; margin: 20px 0;">
              @for ($i = 0; $i < 6; $i++)
                <input type="text"
                  class="otp-box"
                  maxlength="1"
                  inputmode="numeric"
                  pattern="[0-9]"
                  data-index="{{ $i }}"
                  autocomplete="off"
                  {{ $i === 0 ? 'autofocus' : '' }}
                  style="width: 46px; height: 52px; text-align: center; font-size: 22px; font-weight: 700;
                         border: 2px solid rgba(191,155,48,0.25); border-radius: 8px;
                         background: rgba(255,255,255,0.06); color: #fff; outline: none;
                         transition: all 0.2s ease; font-family: 'Jost', sans-serif; caret-color: #BF9B30;" />
              @endfor
            </div>

            <p style="color: rgba(255,255,255,0.35); font-size: 12px; margin-bottom: 20px;">Enter the 6-digit code • Expires in 5 minutes</p>

            <button type="submit" id="verifyBtn"
              style="width: 100%; padding: 12px; border: none; border-radius: 8px; background: #BF9B30; color: #1a1208;
                     font-size: 14px; font-weight: 700; letter-spacing: 2px; cursor: pointer;
                     transition: all 0.2s; margin-bottom: 16px; opacity: 0.4; pointer-events: none;"
              disabled>
              VERIFY
            </button>
          </form>

          {{-- Resend OTP --}}
          <div style="margin-top: 8px; margin-bottom: 16px;">
            <p style="color: rgba(255,255,255,0.45); font-size: 13px; margin-bottom: 6px;">Didn't receive the code?</p>
            <form method="POST" action="{{ route('otp.resend') }}" id="resendForm">
              @csrf
              <button type="submit" id="resendBtn"
                style="color: #BF9B30; font-size: 13px; background: transparent; border: none; cursor: pointer;
                       text-decoration: underline; font-weight: 500; transition: color 0.2s;">
                Resend Code
              </button>
            </form>
            <p id="cooldownText" style="color: rgba(255,255,255,0.35); font-size: 12px; margin-top: 4px; display: none; font-variant-numeric: tabular-nums;"></p>
          </div>

          <div style="border-top: 1px solid rgba(191,155,48,0.15); padding-top: 14px;">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                style="color: rgba(255,255,255,0.45); font-size: 13px; background: transparent; border: none; cursor: pointer; transition: color 0.2s;">
                ← Log Out
              </button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const inputs = document.querySelectorAll('.otp-box');
      const hidden = document.getElementById('otpHidden');
      const verifyBtn = document.getElementById('verifyBtn');
      const resendBtn = document.getElementById('resendBtn');
      const cooldownText = document.getElementById('cooldownText');

      // ── OTP Input Logic ──
      function updateHidden() {
        let val = '';
        inputs.forEach(i => val += i.value);
        hidden.value = val;
        if (val.length === 6) {
          verifyBtn.disabled = false;
          verifyBtn.style.opacity = '1';
          verifyBtn.style.pointerEvents = 'auto';
        } else {
          verifyBtn.disabled = true;
          verifyBtn.style.opacity = '0.4';
          verifyBtn.style.pointerEvents = 'none';
        }
      }

      inputs.forEach((input, idx) => {
        // Focus style
        input.addEventListener('focus', function () {
          this.style.borderColor = '#BF9B30';
          this.style.background = 'rgba(255,255,255,0.1)';
          this.style.boxShadow = '0 0 0 3px rgba(191,155,48,0.15)';
          this.select();
        });
        input.addEventListener('blur', function () {
          this.style.borderColor = 'rgba(191,155,48,0.25)';
          this.style.background = 'rgba(255,255,255,0.06)';
          this.style.boxShadow = 'none';
        });

        input.addEventListener('input', function () {
          this.value = this.value.replace(/[^0-9]/g, '');
          if (this.value && idx < inputs.length - 1) {
            inputs[idx + 1].focus();
          }
          updateHidden();
        });

        input.addEventListener('keydown', function (e) {
          if (e.key === 'Backspace' && !this.value && idx > 0) {
            inputs[idx - 1].focus();
            inputs[idx - 1].value = '';
            updateHidden();
          }
        });

        // Handle paste
        input.addEventListener('paste', function (e) {
          e.preventDefault();
          const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 6);
          pasted.split('').forEach((char, i) => {
            if (inputs[i]) inputs[i].value = char;
          });
          const focusIdx = Math.min(pasted.length, inputs.length - 1);
          inputs[focusIdx].focus();
          updateHidden();
        });
      });

      // ── Resend Cooldown Timer ──
      @if(session('message'))
        startCooldown(60);
      @endif

      function startCooldown(seconds) {
        let remaining = seconds;
        resendBtn.disabled = true;
        resendBtn.style.opacity = '0.4';
        resendBtn.style.pointerEvents = 'none';
        cooldownText.style.display = '';
        tick();

        function tick() {
          if (remaining <= 0) {
            resendBtn.disabled = false;
            resendBtn.style.opacity = '1';
            resendBtn.style.pointerEvents = 'auto';
            cooldownText.style.display = 'none';
            return;
          }
          cooldownText.textContent = 'You can resend in ' + remaining + 's';
          remaining--;
          setTimeout(tick, 1000);
        }
      }
    });
  </script>

</body>
</html>
