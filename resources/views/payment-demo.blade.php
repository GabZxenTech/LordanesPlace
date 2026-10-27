<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pay with GCash | LDReserve</title>
  @include('partials._favicon')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body { margin: 0; min-height: 100vh; font-family: 'Jost', sans-serif; color: #17263c; background: #eef3fb; }
    .hero { background: linear-gradient(135deg, #0a7cff 0%, #0057d9 100%); height: 260px; position: relative; overflow: hidden; }
    .hero::before, .hero::after { content: ''; position: absolute; border-radius: 50%; background: rgba(255,255,255,0.07); }
    .hero::before { width: 380px; height: 380px; top: -190px; right: -90px; }
    .hero::after { width: 260px; height: 260px; bottom: -140px; left: -60px; }
    .brand { position: relative; z-index: 1; max-width: 460px; margin: 0 auto; padding: 30px 20px 0; display: flex; align-items: center; gap: 12px; color: #fff; }
    .logo { width: 44px; height: 44px; border-radius: 12px; background: #fff; color: #0a6cf2; font-weight: 700; font-size: 26px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(0,0,0,0.18); }
    .brand b { font-size: 24px; letter-spacing: -0.5px; }
    .brand span { display: block; font-size: 13px; opacity: 0.85; margin-top: -2px; }

    .card { position: relative; z-index: 2; width: calc(100% - 32px); max-width: 460px; margin: -120px auto 40px; background: #fff; border-radius: 20px; box-shadow: 0 18px 50px rgba(0, 50, 140, 0.18); overflow: hidden; }
    .merchant { display: flex; align-items: center; gap: 14px; padding: 22px 24px 0; }
    .merchant .m-logo { width: 46px; height: 46px; border-radius: 12px; background: #1a1208; color: #e8c96d; font-weight: 700; font-size: 15px; display: flex; align-items: center; justify-content: center; letter-spacing: 0.5px; }
    .merchant b { display: block; font-size: 17px; }
    .merchant small { color: #6b7a90; font-size: 13px; }

    .amount { text-align: center; padding: 22px 24px 8px; }
    .amount small { display: block; font-size: 12px; letter-spacing: 2px; color: #6b7a90; text-transform: uppercase; margin-bottom: 4px; }
    .amount strong { font-size: 44px; font-weight: 700; color: #0a6cf2; letter-spacing: -1px; }

    .summary { margin: 14px 24px 0; background: #f4f8ff; border-radius: 14px; padding: 6px 16px; }
    .row { display: flex; justify-content: space-between; gap: 14px; font-size: 14px; padding: 10px 0; color: #51627b; }
    .row + .row { border-top: 1px dashed #d3def0; }
    .row b { color: #17263c; text-align: right; font-weight: 600; }

    .form { padding: 22px 24px 26px; }
    label { display: block; font-size: 12px; letter-spacing: 1.2px; font-weight: 700; color: #51627b; text-transform: uppercase; margin-bottom: 8px; }
    .field { display: flex; align-items: center; border: 1.5px solid #cdd9ec; border-radius: 12px; overflow: hidden; background: #fff; transition: border-color .15s, box-shadow .15s; }
    .field:focus-within { border-color: #0a7cff; box-shadow: 0 0 0 4px rgba(10,124,255,0.12); }
    .field .pre { padding: 0 14px; font-weight: 600; color: #51627b; background: #f4f8ff; align-self: stretch; display: flex; align-items: center; border-right: 1.5px solid #cdd9ec; }
    .field input { flex: 1; border: none; outline: none; padding: 15px 14px; font-size: 18px; font-family: inherit; letter-spacing: 1px; min-width: 0; }
    .err { color: #d63a3a; font-size: 13px; margin-top: 8px; min-height: 18px; }

    .btn { width: 100%; padding: 16px; border: none; border-radius: 12px; font-size: 17px; font-weight: 700; font-family: inherit; cursor: pointer; background: linear-gradient(135deg, #0a7cff, #0062e6); color: #fff; margin-top: 8px; box-shadow: 0 8px 20px rgba(10,124,255,0.32); transition: transform .1s, box-shadow .15s; }
    .btn:hover { box-shadow: 0 10px 24px rgba(10,124,255,0.42); }
    .btn:active { transform: translateY(1px); }
    .btn:disabled { opacity: .6; cursor: default; }
    .link { display: block; width: 100%; text-align: center; margin-top: 16px; background: none; border: none; color: #6b7a90; font-size: 14px; font-family: inherit; cursor: pointer; }
    .link:hover { color: #0a6cf2; text-decoration: underline; }

    .step { display: none; }
    .step.on { display: block; animation: fade .25s ease; }
    @keyframes fade { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

    .spin { width: 54px; height: 54px; border: 5px solid #d9e7fc; border-top-color: #0a7cff; border-radius: 50%; margin: 28px auto 16px; animation: spin .8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .center { text-align: center; color: #51627b; font-size: 15px; margin: 0 0 26px; }

    .otp { display: flex; gap: 10px; justify-content: space-between; }
    .otp input { width: 100%; max-width: 54px; height: 58px; text-align: center; font-size: 24px; font-weight: 600; font-family: inherit; border: 1.5px solid #cdd9ec; border-radius: 12px; outline: none; }
    .otp input:focus { border-color: #0a7cff; box-shadow: 0 0 0 4px rgba(10,124,255,0.12); }
    .sub { color: #51627b; font-size: 14px; margin: 0 0 16px; }
  </style>
</head>
<body>
  <div class="hero">
    <div class="brand">
      <div class="logo">G</div>
      <div><b>GCash</b><span>Pay online</span></div>
    </div>
  </div>

  <div class="card">
    <div class="merchant">
      <div class="m-logo">LD</div>
      <div><b>LDReserve</b><small>Event venue · Santa Maria, Bulacan</small></div>
    </div>

    <div class="amount">
      <small>Amount to pay</small>
      <strong>₱{{ number_format($total, 2) }}</strong>
    </div>

    <div class="summary">
      @foreach($bookings as $b)
        <div class="row">
          <span>{{ $b->package }}{{ $b->room_number ? ' ' . $b->room_number : '' }}<br><small style="color:#8a98ad;">#{{ $b->booking_number }}</small></span>
          <b>₱{{ number_format($b->amountToPay(), 2) }}</b>
        </div>
      @endforeach
      <div class="row"><span>Payment</span><b>{{ \App\Models\Booking::paymentOptionLabel($bookings->first()->payment_option) }}</b></div>
    </div>

    <div class="form">
      <form method="POST" action="{{ route('payment.demo.pay') }}" id="payForm" novalidate>
        @csrf
        <input type="hidden" name="otp" id="otpHidden">

        {{-- Step 1: mobile number --}}
        <div class="step on" id="step1">
          <label for="mobile">GCash mobile number</label>
          <div class="field">
            <span class="pre">+63</span>
            <input type="tel" id="mobile" name="mobile" inputmode="numeric" maxlength="11" placeholder="09XX XXX XXXX" value="{{ old('mobile') }}" autocomplete="off">
          </div>
          <div class="err" id="mobileErr">{{ $errors->first('mobile') }}</div>
          <button type="button" class="btn" id="payBtn">Pay ₱{{ number_format($total, 2) }}</button>
        </div>

        {{-- Step 2: processing --}}
        <div class="step" id="step2">
          <div class="spin"></div>
          <p class="center">Processing your payment…</p>
        </div>

        {{-- Step 3: code --}}
        <div class="step" id="step3">
          <label>Enter the 6-digit code</label>
          <p class="sub">Confirm this payment for <b id="maskedMobile"></b>.</p>
          <div class="otp" id="otpBoxes">
            <input type="text" inputmode="numeric" maxlength="1" autocomplete="off">
            <input type="text" inputmode="numeric" maxlength="1" autocomplete="off">
            <input type="text" inputmode="numeric" maxlength="1" autocomplete="off">
            <input type="text" inputmode="numeric" maxlength="1" autocomplete="off">
            <input type="text" inputmode="numeric" maxlength="1" autocomplete="off">
            <input type="text" inputmode="numeric" maxlength="1" autocomplete="off">
          </div>
          <div class="err" id="otpErr">{{ $errors->first('otp') }}</div>
          <button type="submit" class="btn" id="confirmBtn">Confirm payment</button>
          <button type="button" class="link" id="backBtn">Change number</button>
        </div>
      </form>

      <form method="POST" action="{{ route('payment.demo.skip') }}">
        @csrf
        <button type="submit" class="link">Cancel</button>
      </form>
    </div>
  </div>

  <script>
    (function () {
      var steps = [document.getElementById('step1'), document.getElementById('step2'), document.getElementById('step3')];
      var mobile = document.getElementById('mobile');
      var boxes = Array.prototype.slice.call(document.querySelectorAll('#otpBoxes input'));
      var form = document.getElementById('payForm');

      function show(n) { steps.forEach(function (s, i) { s.classList.toggle('on', i === n); }); }
      mobile.addEventListener('input', function () { mobile.value = mobile.value.replace(/\D/g, ''); });

      // One box per digit: move forward as you type, back on delete, paste fills all.
      boxes.forEach(function (box, i) {
        box.addEventListener('input', function () {
          box.value = box.value.replace(/\D/g, '').slice(0, 1);
          if (box.value && boxes[i + 1]) boxes[i + 1].focus();
        });
        box.addEventListener('keydown', function (e) {
          if (e.key === 'Backspace' && !box.value && boxes[i - 1]) boxes[i - 1].focus();
        });
        box.addEventListener('paste', function (e) {
          var text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
          if (!text) return;
          e.preventDefault();
          text.split('').forEach(function (d, j) { boxes[j].value = d; });
          boxes[Math.min(text.length, 5)].focus();
        });
      });

      // The server rejected the code: show that step again.
      @if($errors->has('otp') && !$errors->has('mobile'))
        show(2);
      @endif

      document.getElementById('payBtn').addEventListener('click', function () {
        var err = document.getElementById('mobileErr');
        if (!/^09\d{9}$/.test(mobile.value)) {
          err.textContent = 'Enter an 11-digit mobile number that starts with 09.';
          return;
        }
        err.textContent = '';
        document.getElementById('maskedMobile').textContent = mobile.value.slice(0, 4) + '•••' + mobile.value.slice(-4);
        show(1);
        setTimeout(function () { show(2); boxes[0].focus(); }, 1500);
      });

      document.getElementById('backBtn').addEventListener('click', function () { show(0); mobile.focus(); });

      form.addEventListener('submit', function (e) {
        var code = boxes.map(function (b) { return b.value; }).join('');
        var err = document.getElementById('otpErr');
        if (!/^\d{6}$/.test(code)) {
          e.preventDefault();
          err.textContent = 'Enter the 6-digit code.';
          return;
        }
        document.getElementById('otpHidden').value = code;
        document.getElementById('confirmBtn').disabled = true;
      });
    })();
  </script>
</body>
</html>
