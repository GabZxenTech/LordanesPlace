<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Successful | LDReserve</title>
  @include('partials._favicon')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body { margin: 0; min-height: 100vh; font-family: 'Jost', sans-serif; background: #eef3fb; color: #1b2a41; display: flex; flex-direction: column; align-items: center; padding: 0 16px 40px; }
    .card { width: 100%; max-width: 440px; margin-top: 90px; background: #fff; border-radius: 16px; padding: 40px 28px 32px; text-align: center; box-shadow: 0 8px 30px rgba(0, 60, 160, 0.12); }
    .check { width: 84px; height: 84px; border-radius: 50%; background: #1fa85a; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; animation: pop .5s ease; }
    .check svg { width: 44px; height: 44px; }
    .check path { stroke-dasharray: 30; stroke-dashoffset: 30; animation: draw .45s .3s ease forwards; }
    @keyframes pop { 0% { transform: scale(0.3); opacity: 0; } 70% { transform: scale(1.08); } 100% { transform: scale(1); opacity: 1; } }
    @keyframes draw { to { stroke-dashoffset: 0; } }
    h1 { margin: 0 0 6px; font-size: 28px; font-weight: 700; color: #1b2a41; }
    .thanks { margin: 0 0 22px; font-family: 'Cormorant Garamond', serif; font-size: 24px; font-weight: 700; color: #b8860b; }
    .row { display: flex; justify-content: space-between; font-size: 14px; padding: 8px 0; color: #4a5b75; border-top: 1px dashed #cfd9e8; }
    .row b { color: #1b2a41; }
    .redirect { margin-top: 22px; font-size: 14px; color: #6b7a90; }
    .redirect a { color: #007dfe; font-weight: 600; text-decoration: none; }
    .bar { height: 4px; background: #e2eaf6; border-radius: 4px; overflow: hidden; margin-top: 12px; }
    .bar i { display: block; height: 100%; width: 0; background: #1fa85a; animation: fill 5s linear forwards; }
    @keyframes fill { to { width: 100%; } }
  </style>
</head>
<body>
  <div class="card">
    <div class="check">
      <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
    </div>
    <h1>Payment Successful</h1>
    <p class="thanks">Thank you for choosing LDReserve!</p>

    <div class="row"><span>Amount paid</span><b>₱{{ number_format($total, 2) }}</b></div>
    <div class="row"><span>Reference no.</span><b>{{ $ref }}</b></div>

    <p class="redirect">Taking you to your bookings in <b id="count">5</b>… <a href="{{ $profileUrl }}">Go now</a></p>
    <div class="bar"><i></i></div>
  </div>

  <script>
    (function () {
      var url = @json($profileUrl);
      var left = 5;
      var count = document.getElementById('count');
      var timer = setInterval(function () {
        left--;
        count.textContent = Math.max(left, 0);
        if (left <= 0) { clearInterval(timer); window.location.href = url; }
      }, 1000);
    })();
  </script>
</body>
</html>
