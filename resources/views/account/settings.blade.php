<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Account Settings | LorDane's Place</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* Account settings page */
    .as-page { --as-surface: #fffcf5; --as-tint: #f2ebdc; --as-ink: #1a1208; --as-soft: #5c4a33; --as-faint: #8f7c62; --as-line: #e8dcc4; --as-gold: #b8860b; --as-brown: #2c1a0e;
      padding: 40px 0 72px; min-height: 100vh; font-family: 'Jost', sans-serif; color: var(--as-ink); }
    .as-wrap { max-width: 1000px; margin: 0 auto; padding: 0 16px; }
    .as-back { display: inline-flex; align-items: center; gap: 6px; color: var(--as-faint); font-size: 14px; text-decoration: none; margin-bottom: 14px; }
    .as-back:hover { color: var(--as-gold); }
    .as-flash { border-radius: 8px; padding: 12px 16px; margin-bottom: 18px; font-size: 14px; }
    .as-flash-ok { background: #e3f3ea; color: #1f6b43; border: 1px solid #b9e0c9; }
    .as-flash-err { background: #fbf0d9; color: #7a5a12; border: 1px solid #ecd9a8; }

    .as-hero { background: var(--as-brown); color: #f5ecd8; border-radius: 12px; padding: 24px; display: flex; align-items: center; gap: 18px; flex-wrap: wrap; margin-bottom: 20px; }
    .as-avatar { width: 72px; height: 72px; border-radius: 50%; background: var(--as-gold); color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 26px; flex-shrink: 0; overflow: hidden; border: 3px solid rgba(255,255,255,0.25); }
    .as-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .as-hero h1 { font-family: 'Cormorant Garamond', serif; font-size: 30px; font-weight: 700; margin: 0; line-height: 1.1; color: #fff; }
    .as-hero p { margin: 4px 0 8px; font-size: 14px; color: rgba(245,236,216,0.75); overflow-wrap: anywhere; }
    .as-chip { display: inline-block; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: 3px 10px; border-radius: 999px; background: rgba(201,168,76,0.2); color: #e8c96d; margin-right: 6px; }

    .as-grid { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 20px; align-items: start; }
    .as-col { display: grid; gap: 20px; min-width: 0; }
    .as-col > * { min-width: 0; }
    .as-card { background: var(--as-surface); border: 1px solid var(--as-line); border-radius: 12px; padding: 22px; }
    .as-card-head { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
    .as-icon { width: 38px; height: 38px; border-radius: 10px; background: var(--as-tint); color: var(--as-gold); display: grid; place-items: center; flex-shrink: 0; }
    .as-card-head h2 { font-size: 15px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin: 0; }
    .as-card-head p { font-size: 13px; color: var(--as-faint); margin: 2px 0 0; }
    .as-field { margin-bottom: 14px; }
    .as-field label { display: block; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--as-soft); margin-bottom: 6px; }
    .as-input { width: 100%; box-sizing: border-box; background: #fff; border: 1px solid var(--as-line); border-radius: 8px; padding: 11px 13px; font: 15px 'Jost', sans-serif; color: var(--as-ink); outline: none; transition: border-color .2s; }
    .as-input:focus { border-color: var(--as-gold); }
    .as-hint { font-size: 12px; color: var(--as-faint); margin: 6px 0 0; }
    .as-err { font-size: 12px; color: #8a6a40; font-weight: 600; margin: 6px 0 0; }
    .as-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: var(--as-gold); color: #fff; border: none; border-radius: 8px; padding: 11px 18px; font: 700 13px 'Jost', sans-serif; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; text-decoration: none; transition: background .2s; }
    .as-btn:hover { background: #c9a84c; }
    .as-btn-ghost { background: transparent; color: var(--as-soft); border: 1px solid var(--as-line); }
    .as-btn-ghost:hover { background: var(--as-tint); }
    .as-btn-danger { background: #b03a2e; }
    .as-btn-danger:hover { background: #962f25; }
    .as-btn[disabled] { opacity: .5; cursor: not-allowed; }
    .as-pending { background: #fbf0d9; border: 1px solid #ecd9a8; color: #7a5a12; border-radius: 8px; padding: 10px 12px; font-size: 13px; margin-bottom: 14px; }
    .as-photo { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .as-photo .as-avatar { width: 84px; height: 84px; font-size: 28px; border-color: var(--as-line); }
    .as-photo-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .as-detail { display: flex; gap: 10px; align-items: flex-start; padding: 10px 0; border-bottom: 1px dashed var(--as-line); font-size: 14px; }
    .as-detail:last-child { border-bottom: none; }
    .as-detail span { color: var(--as-faint); font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; display: block; }
    .as-detail b { font-weight: 600; overflow-wrap: anywhere; }
    .as-danger { border-color: #ecc9c3; }
    .as-danger .as-icon { background: #fbe9e6; color: #b03a2e; }
    .as-danger p.as-text { font-size: 13px; color: var(--as-soft); line-height: 1.55; margin: 0 0 14px; }

    .as-modal { position: fixed; inset: 0; background: rgba(44,26,14,0.45); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 16px; }
    .as-modal.open { display: flex; }
    .as-modal-box { background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 12px; width: 100%; max-width: 420px; padding: 26px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
    .as-modal-box h3 { font-family: 'Cormorant Garamond', serif; font-size: 24px; margin: 0 0 8px; }
    .as-modal-box p { font-size: 14px; color: var(--as-soft); line-height: 1.55; margin: 0 0 16px; }
    .as-modal-actions { display: flex; gap: 10px; margin-top: 6px; }
    .as-modal-actions .as-btn { flex: 1; }

    @media (max-width: 820px) {
      .as-grid { grid-template-columns: minmax(0, 1fr); }
      .as-hero { padding: 18px; }
      .as-hero h1 { font-size: 26px; }
    }
  </style>
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<main class="as-page">
  <div class="as-wrap">
    @php
      $tier = $completedCount >= 2 ? 'Loyal Client' : ($completedCount === 1 ? 'Member' : 'New Member');
      $avatarUrl = $user->avatarUrl();
      $isGoogle = (bool) $user->google_id;
    @endphp

    <a href="{{ route('profile') }}" class="as-back">&larr; Back to Profile & Bookings</a>

    @if(session('success'))
      <div class="as-flash as-flash-ok">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="as-flash as-flash-err">@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
    @endif

    {{-- Header --}}
    <section class="as-hero">
      <div class="as-avatar">
        @if($avatarUrl)<img src="{{ $avatarUrl }}" alt="" referrerpolicy="no-referrer">@else{{ $user->initials() }}@endif
      </div>
      <div style="flex: 1; min-width: 0;">
        <h1>{{ $user->name }}</h1>
        <p>{{ $user->email }}</p>
        <span class="as-chip">★ {{ $tier }}</span>
        <span class="as-chip">Member since {{ $user->created_at->format('M Y') }}</span>
      </div>
    </section>

    <div class="as-grid">
      <div class="as-col">
        {{-- Profile information --}}
        <section class="as-card">
          <div class="as-card-head">
            <div class="as-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            <div><h2>Profile Information</h2><p>Your name and email address</p></div>
          </div>

          @if($user->pending_email)
            <div class="as-pending">Waiting for confirmation of <b>{{ $user->pending_email }}</b>. Check that inbox for the link.</div>
          @endif

          <form method="POST" action="{{ route('account.profile') }}" id="profileForm">
            @csrf
            <div class="as-field">
              <label for="name">Full Name</label>
              <input class="as-input" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255">
              @error('name')<p class="as-err">{{ $message }}</p>@enderror
            </div>
            <div class="as-field">
              <label for="email">Email Address</label>
              <input class="as-input" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255" data-current="{{ $user->email }}">
              <p class="as-hint">Changing it sends a confirmation link to the new address.</p>
              @error('email')<p class="as-err">{{ $message }}</p>@enderror
            </div>
            <div class="as-field" id="emailPasswordField" style="{{ $errors->has('email_password') ? '' : 'display:none;' }}">
              <label for="profile_current_password">Current Password</label>
              <input class="as-input" type="password" id="profile_current_password" name="email_password" autocomplete="current-password">
              <p class="as-hint">Needed to change your email.</p>
              @error('email_password')<p class="as-err">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="as-btn">Save Changes</button>
          </form>
        </section>

        {{-- Password --}}
        <section class="as-card">
          <div class="as-card-head">
            <div class="as-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
            <div><h2>Update Password</h2><p>Use a strong password you don't use elsewhere</p></div>
          </div>

          @if($isGoogle)
            <p class="as-hint" style="margin: 0 0 14px;">Signed up with Google? You may not have a password yet — <a href="{{ route('password.request') }}" style="color: var(--as-gold); font-weight: 600;">set one with Forgot Password</a>.</p>
          @endif

          <form method="POST" action="{{ route('account.password') }}">
            @csrf
            <div class="as-field">
              <label for="current_password">Current Password</label>
              <input class="as-input" type="password" id="current_password" name="current_password" required autocomplete="current-password">
              @error('current_password')<p class="as-err">{{ $message }}</p>@enderror
            </div>
            <div class="as-field">
              <label for="password">New Password</label>
              <input class="as-input" type="password" id="password" name="password" required autocomplete="new-password">
              <p class="as-hint">At least 8 characters with uppercase, lowercase, a number and a symbol.</p>
              @error('password')<p class="as-err">{{ $message }}</p>@enderror
            </div>
            <div class="as-field">
              <label for="password_confirmation">Confirm New Password</label>
              <input class="as-input" type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>
            <button type="submit" class="as-btn">Update Password</button>
          </form>
        </section>
      </div>

      <div class="as-col">
        {{-- Profile picture --}}
        <section class="as-card">
          <div class="as-card-head">
            <div class="as-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
            <div><h2>Profile Picture</h2><p>JPG, PNG or WebP</p></div>
          </div>
          <div class="as-photo">
            <div class="as-avatar" id="avatarPreview">
              @if($avatarUrl)<img src="{{ $avatarUrl }}" alt="" referrerpolicy="no-referrer">@else{{ $user->initials() }}@endif
            </div>
            <div class="as-photo-actions">
              <form method="POST" action="{{ route('account.avatar.update') }}" id="avatarForm">
                @csrf
                <input type="hidden" name="avatar_data" id="avatarData">
                <input type="file" id="avatarFile" accept="image/jpeg,image/png,image/webp" hidden>
                <button type="button" class="as-btn" onclick="document.getElementById('avatarFile').click()">Upload Photo</button>
              </form>
              @if($avatarUrl)
                <form method="POST" action="{{ route('account.avatar.remove') }}" data-confirm="Remove your profile picture?">
                  @csrf
                  <button type="submit" class="as-btn as-btn-ghost">Remove</button>
                </form>
              @endif
            </div>
          </div>
          @error('avatar')<p class="as-err">{{ $message }}</p>@enderror
        </section>

        {{-- Account details --}}
        <section class="as-card">
          <div class="as-card-head">
            <div class="as-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></div>
            <div><h2>Account Details</h2></div>
          </div>
          <div class="as-detail"><div><span>Account tier</span><b>{{ $tier }}</b></div></div>
          <div class="as-detail"><div><span>Email</span><b>{{ $user->email }}</b></div></div>
          <div class="as-detail"><div><span>Signed in with</span><b>{{ $isGoogle ? 'Google and email' : 'Email and password' }}</b></div></div>
          <div class="as-detail"><div><span>Member since</span><b>{{ $user->created_at->format('F d, Y') }}</b></div></div>
        </section>

        {{-- Delete account --}}
        <section class="as-card as-danger">
          <div class="as-card-head">
            <div class="as-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
            <div><h2>Delete Account</h2><p>This cannot be undone</p></div>
          </div>
          @if($activeBookings > 0)
            <p class="as-text">You have {{ $activeBookings }} active {{ $activeBookings === 1 ? 'booking' : 'bookings' }} (pending, approved or ongoing). You can delete your account once they are cancelled or finished.</p>
            <button type="button" class="as-btn as-btn-danger" disabled>Delete Account</button>
          @else
            <p class="as-text">Your profile, notifications and chats will be removed and you won't be able to sign in. Records of paid bookings are kept for the resort's reports.</p>
            <button type="button" class="as-btn as-btn-danger" onclick="openDeleteModal()">Delete Account</button>
          @endif
          @error('delete_password')<p class="as-err">{{ $message }}</p>@enderror
        </section>
      </div>
    </div>
  </div>
</main>

{{-- Delete account confirmation --}}
<div class="as-modal" id="deleteModal" role="dialog" aria-modal="true" aria-labelledby="deleteTitle" onclick="if (event.target === this) closeDeleteModal()">
  <div class="as-modal-box">
    <h3 id="deleteTitle">Delete your account?</h3>
    <p>Enter your password to confirm. This cannot be undone.</p>
    <form method="POST" action="{{ route('account.delete') }}">
      @csrf
      <div class="as-field">
        <label for="delete_password">Password</label>
        <input class="as-input" type="password" id="delete_password" name="delete_password" required autocomplete="current-password">
      </div>
      <div class="as-modal-actions">
        <button type="button" class="as-btn as-btn-ghost" onclick="closeDeleteModal()">Keep Account</button>
        <button type="submit" class="as-btn as-btn-danger">Delete</button>
      </div>
    </form>
  </div>
</div>

@include('partials._footer')

<script>
  // Ask for the current password only when the email is being changed.
  (function () {
    const email = document.getElementById('email');
    const field = document.getElementById('emailPasswordField');
    const pwd = document.getElementById('profile_current_password');
    const sync = function () {
      const changed = email.value.trim().toLowerCase() !== email.dataset.current.toLowerCase();
      field.style.display = changed ? '' : 'none';
      pwd.required = changed;
    };
    email.addEventListener('input', sync);
    sync();
  })();

  // Crop the chosen photo to a 320px square in the browser, then upload it.
  document.getElementById('avatarFile').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    if (!/^image\/(jpeg|png|webp)$/.test(file.type)) { uiToast('Please choose a JPG, PNG or WebP image.'); return; }
    if (file.size > 10 * 1024 * 1024) { uiToast('That image is too large (max 10 MB).'); return; }

    const img = new Image();
    img.onload = function () {
      const size = 320;
      const side = Math.min(img.width, img.height);
      const canvas = document.createElement('canvas');
      canvas.width = canvas.height = size;
      const ctx = canvas.getContext('2d');
      ctx.fillStyle = '#ffffff';
      ctx.fillRect(0, 0, size, size);
      ctx.drawImage(img, (img.width - side) / 2, (img.height - side) / 2, side, side, 0, 0, size, size);
      const data = canvas.toDataURL('image/jpeg', 0.85);
      URL.revokeObjectURL(img.src);
      document.getElementById('avatarPreview').innerHTML = '<img src="' + data + '" alt="">';
      document.getElementById('avatarData').value = data;
      document.getElementById('avatarForm').submit();
    };
    img.onerror = function () { uiToast('That image could not be opened. Please choose another photo.'); };
    img.src = URL.createObjectURL(file);
  });

  function openDeleteModal() {
    document.getElementById('deleteModal').classList.add('open');
    setTimeout(function () { document.getElementById('delete_password').focus(); }, 50);
  }
  function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
  }
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDeleteModal(); });
  @if($errors->has('delete_password')) openDeleteModal(); @endif
</script>
</body>
</html>
