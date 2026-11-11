<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up | LDReserve</title>
  @include('partials._favicon')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  @include('partials._auth-style')
</head>
<body class="bg-off-white text-warm-black font-body">

  <section class="su-page">
    <div class="su-grid">

      <!-- Left side -->
      <div class="su-hero">
        <p class="su-eyebrow">CREATE ACCOUNT</p>
        <h1>Join <em>LDReserve</em></h1>
        <p>Create your account to start booking events, checking schedules, and exploring LDReserve online.</p>
      </div>

      <!-- Form card -->
      <div class="su-card">
        <h2>Create your account</h2>
        <p class="su-sub">Book events and track your reservations in one place.</p>

        <form method="POST" action="{{ route('register.post') }}">
          @csrf

          @if ($errors->any())
            <div class="su-err">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="su-field">
            <label for="name">Full name</label>
            <div class="su-input"><input type="text" id="name" name="name" placeholder="e.g. Juan Dela Cruz" value="{{ old('name') }}" required autocomplete="name"></div>
            @error('name') <span class="su-fe">{{ $message }}</span> @enderror
          </div>

          <div class="su-field">
            <label for="email">Email address</label>
            <div class="su-input"><input type="email" id="email" name="email" placeholder="you@gmail.com" value="{{ old('email') }}" required autocomplete="email"></div>
            @error('email') <span class="su-fe">{{ $message }}</span> @enderror
          </div>

          <div class="su-field">
            <label for="password">Password</label>
            <div class="su-input has-eye">
              <input type="password" name="password" id="password" placeholder="Create your password" required autocomplete="new-password">
              <button type="button" class="su-eye" onclick="togglePassword('password', this)" aria-label="Show or hide password">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.789 0 8.601 3.049 9.964 6.678.045.12.045.26 0 .381C20.601 15.951 16.789 19 12 19c-4.789 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              </button>
            </div>
            @error('password') <span class="su-fe">{{ $message }}</span> @enderror
            <div class="su-bars" id="pwBars" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
            <ul class="su-rules" id="pwRules" aria-live="polite">
              <li data-rule="length"><i></i>8+ characters</li>
              <li data-rule="case"><i></i>Upper &amp; lower case</li>
              <li data-rule="number"><i></i>A number</li>
              <li data-rule="symbol"><i></i>A symbol (!@#$)</li>
            </ul>
          </div>

          <div class="su-field">
            <label for="password_confirmation">Confirm password</label>
            <div class="su-input has-eye">
              <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-enter your password" required autocomplete="new-password">
              <button type="button" class="su-eye" onclick="togglePassword('password_confirmation', this)" aria-label="Show or hide password">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.789 0 8.601 3.049 9.964 6.678.045.12.045.26 0 .381C20.601 15.951 16.789 19 12 19c-4.789 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              </button>
            </div>
            <p id="pwMatch" class="su-match" aria-live="polite"></p>
          </div>

          <label class="su-terms">
            <input type="checkbox" name="terms" value="1" required {{ old('terms') ? 'checked' : '' }}>
            <span>I agree to the <a href="#" data-legal="terms">Terms &amp; Conditions</a> and the <a href="#" data-legal="privacy">Privacy Policy</a>.</span>
          </label>
          @error('terms') <span class="su-fe" style="margin-bottom: 10px;">{{ $message }}</span> @enderror

          <div class="su-captcha">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('recaptcha') <span class="su-fe">{{ $message }}</span> @enderror
          </div>

          <button type="submit" class="su-submit">Create account</button>
        </form>

        <div class="su-or"><span>or</span></div>

        <a href="{{ route('auth.google') }}" class="su-google">
          <img src="https://developers.google.com/identity/images/g-logo.png" alt="" />
          Continue with Google
        </a>
        <p class="su-gnote">By continuing with Google you agree to our <a href="#" data-legal="terms">Terms</a> and <a href="#" data-legal="privacy">Privacy Policy</a>.</p>

        <p class="su-alt">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        <a href="{{ route('home') }}" class="su-back">← Back to Home</a>
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
        ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.789 0 8.601 3.049 9.964 6.678.045.12.045.26 0 .381C20.601 15.951 16.789 19 12 19c-4.789 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`;
    }

    // Live password check: strength bars and rules (the server still checks everything on submit).
    (function () {
      var pw = document.getElementById('password');
      var cf = document.getElementById('password_confirmation');
      var rules = document.querySelectorAll('#pwRules li');
      var bars = document.querySelectorAll('#pwBars span');
      var match = document.getElementById('pwMatch');
      if (!pw) return;

      var tests = {
        length: function (v) { return v.length >= 8; },
        case:   function (v) { return /\p{Lu}/u.test(v) && /\p{Ll}/u.test(v); },
        number: function (v) { return /\p{N}/u.test(v); },
        symbol: function (v) { return /[^\p{L}\p{N}]/u.test(v); }
      };
      var colors = ['#ef4444', '#f59e0b', '#e8c96d', '#4ade80'];

      function update() {
        var score = 0;
        rules.forEach(function (li) {
          var ok = tests[li.dataset.rule](pw.value);
          li.classList.toggle('ok', ok);
          if (ok) score++;
        });
        bars.forEach(function (b, i) {
          b.style.background = i < score ? colors[score - 1] : '';
        });
        if (!cf.value) { match.textContent = ''; match.className = 'su-match'; return; }
        var same = cf.value === pw.value;
        match.textContent = same ? 'Passwords match' : 'Passwords do not match';
        match.className = 'su-match ' + (same ? 'ok' : 'bad');
      }

      pw.addEventListener('input', update);
      cf.addEventListener('input', update);
      update();
    })();
  </script>
@include('partials._legal-modal')
</body>
</html>
