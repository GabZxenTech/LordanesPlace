<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>This request could not be completed | LDReserve</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Jost:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    :root { --gold-deep:#B8860B; --gold-mid:#C9A84C; --warm-black:#1A1208; --off-white:#FAF8F3; --cream:#F2EDE4; }
    * { box-sizing: border-box; }
    body { margin: 0; min-height: 100vh; display: flex; flex-direction: column; background: #E8DCC3; color: var(--warm-black); font-family: 'Jost', sans-serif; }
    .eh { padding: 18px 5%; border-bottom: 1px solid rgba(122,90,16,0.3); background: #F2E9D3; }
    .eh a { font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 700; color: #8F6A0A; text-decoration: none; letter-spacing: 1px; }
    .eh small { display: block; font-size: 10px; letter-spacing: 3px; color: rgba(26,18,8,0.55); text-transform: uppercase; margin-top: 2px; }
    .em { flex: 1; display: flex; align-items: center; justify-content: center; gap: 56px; padding: 48px 5%; flex-wrap: wrap-reverse; }
    .et { max-width: 540px; }
    .et h1 { font-family: 'Cormorant Garamond', serif; font-size: clamp(38px, 6vw, 62px); line-height: 1.08; margin: 0 0 16px; color: var(--warm-black); }
    .et p { font-size: 17px; line-height: 1.7; color: rgba(26,18,8,0.82); margin: 0 0 20px; }
    .et p.ec { font-size: 12px; letter-spacing: 4px; color: #7A5A10; font-weight: 700; margin: 0 0 14px; text-transform: uppercase; }
    .tip { background: #F6F0E0; border: 1px solid rgba(122,90,16,0.3); border-radius: 12px; padding: 14px 18px; margin: 0 0 24px; font-size: 15px; line-height: 1.6; color: rgba(26,18,8,0.82); }
    .tip b { display: block; color: var(--warm-black); margin-bottom: 2px; }
    .eb { display: flex; gap: 12px; flex-wrap: wrap; }
    .eb a, .eb button { font: 700 14px 'Jost', sans-serif; letter-spacing: 1px; text-transform: uppercase; padding: 14px 26px; border-radius: 8px; text-decoration: none; cursor: pointer; transition: background .2s; }
    .eb .p { background: var(--gold-deep); border: 1px solid var(--gold-deep); color: #fff; }
    .eb .p:hover { background: var(--gold-mid); border-color: var(--gold-mid); color: var(--warm-black); }
    .eb .s { background: transparent; border: 1px solid #8F6A0A; color: #7A5A10; }
    .eb .s:hover { background: rgba(122,90,16,0.1); }
    .ei { width: 260px; height: 260px; border-radius: 50%; background: #D9C9A3; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ei svg { width: 110px; height: 110px; stroke: #8F6A0A; fill: none; stroke-width: 1.6; stroke-linecap: round; stroke-linejoin: round; }
    .ef { padding: 16px 5%; border-top: 1px solid rgba(122,90,16,0.3); font-size: 13px; color: rgba(26,18,8,0.65); text-align: center; }
    @media (max-width: 640px) { .ei { width: 170px; height: 170px; } .ei svg { width: 72px; height: 72px; } .em { gap: 28px; padding: 32px 6%; } }
  </style>
</head>
<body>
  <header class="eh"><a href="{{ url('/') }}">LDReserve</a><small>Place &bull; Event Venue</small></header>
  <main class="em">
    <div class="et">
      <p class="ec">Error {{ $exception->getStatusCode() }}</p>
      <h1>This request could not be completed</h1>
      <p>Something about this request did not work. Please go back and try again.</p>
      <div class="eb">
        <button type="button" class="p" onclick="if (history.length > 1) { history.back(); } else { location.href = '{{ url('/') }}'; }">Go back</button>
        <a href="{{ url('/') }}" class="s">Back to home</a>
      </div>
    </div>
    <div class="ei" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 3l10 18H2L12 3z"/><path d="M12 10v5"/><path d="M12 18h.01"/></svg></div>
  </main>
  <footer class="ef">LorDane's Place &middot; 0917 745 5049 &middot; lordanesplace@gmail.com</footer>
</body>
</html>
