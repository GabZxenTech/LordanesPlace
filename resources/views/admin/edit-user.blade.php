<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit User | Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.open { display: flex; }
  </style>
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow-y: auto;">
    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 40px; width: 100%; max-width: 500px;">
      <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: #8a6a40; text-decoration: none; margin-bottom: 16px; transition: color 0.2s;" onmouseover="this.style.color='#c9a84c'" onmouseout="this.style.color='#8a6a40'">&larr; Back to User Management</a>
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Edit User</h2>
      <p style="font-size: 14px; color: #8a6a40; margin: 0 0 20px;">Update details for {{ $user->name }}</p>

      @if(session('success'))
        <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">✓ {{ session('success') }}</div>
      @endif
      @if($errors->has('error'))
        <div style="background: #f8d7da; border: 1px solid #dc3545; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">{{ $errors->first('error') }}</div>
      @endif

      <form method="POST" action="{{ route('admin.update', $user->id) }}" id="editUserForm">
        @csrf @method('PUT')

        <div style="margin-bottom: 20px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Full Name</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" required
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 14px 16px; border-radius: 6px; font-size: 15px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
            onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
          @error('name') <span style="color: #e74c3c; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 8px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Email Address</label>
          <input type="email" name="email" id="emailInput" value="{{ old('email', $user->email) }}" data-original="{{ $user->email }}" required
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 14px 16px; border-radius: 6px; font-size: 15px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
            onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
          @error('email') <span style="color: #e74c3c; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
          <span style="font-size: 11px; color: #8a6a40; margin-top: 6px; display: block;">Changing this sends a confirmation link to the new address — the account keeps using the current email until it's confirmed.</span>
        </div>

        @if($user->pending_email)
          <div style="background: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 13px;">
            ⏳ Pending confirmation for <strong>{{ $user->pending_email }}</strong>
          </div>
        @endif

        <div style="display: flex; gap: 12px; margin-top: 24px;">
          <button type="submit" style="flex: 1; background: #c9a84c; color: #2c1a0e; border: none; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 15px; cursor: pointer; transition: opacity 0.3s; font-family: 'Jost', sans-serif;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">Save Changes</button>
          <a href="{{ route('admin.dashboard') }}" style="flex: 1; border: 1px solid #d4c4a0; color: #8a6a40; text-align: center; padding: 14px; border-radius: 6px; font-size: 15px; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.borderColor='#c9a84c'; this.style.color='#c9a84c';" onmouseout="this.style.borderColor='#d4c4a0'; this.style.color='#8a6a40';">Cancel</a>
        </div>
      </form>

      <div style="border-top: 1px solid #e8dcc8; margin-top: 28px; padding-top: 24px;">
        <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Password</label>
        <p style="font-size: 13px; color: #8a6a40; margin: 0 0 14px;">Admins can't view or set a user's password directly. Send them a reset link instead — only the user can choose a new one.</p>
        <form method="POST" action="{{ route('admin.users.send-password-reset', $user->id) }}" id="passwordResetForm">
          @csrf
          <button type="submit" style="width: 100%; background: transparent; border: 1px solid #c9a84c; color: #c9a84c; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.2s; font-family: 'Jost', sans-serif;" onmouseover="this.style.background='#c9a84c'; this.style.color='#fff9ef';" onmouseout="this.style.background='transparent'; this.style.color='#c9a84c';">Send Password Reset Link</button>
        </form>
      </div>
    </div>
  </main>

  {{-- CONFIRM ACTION MODAL --}}
  <div id="confirmModal" class="modal-overlay">
    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 32px; width: 100%; max-width: 420px;">
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 24px; font-weight: 700; color: #2c1a0e; margin: 0 0 12px;">Are You Sure?</h2>
      <p id="confirmModalMessage" style="font-size: 14px; color: #8a6a40; margin: 0 0 24px; line-height: 1.6;"></p>
      <div style="display: flex; gap: 12px;">
        <button type="button" id="confirmModalConfirmBtn" style="flex: 1; background: #c9a84c; color: #2c1a0e; border: none; padding: 12px; border-radius: 6px; font-weight: 700; font-size: 14px; cursor: pointer; font-family: 'Jost', sans-serif;">Confirm</button>
        <button type="button" id="confirmModalCancelBtn" style="flex: 1; border: 1px solid #d4c4a0; background: transparent; color: #8a6a40; padding: 12px; border-radius: 6px; font-size: 14px; cursor: pointer; font-family: 'Jost', sans-serif;">Cancel</button>
      </div>
    </div>
  </div>

  <script>
    const confirmModal = document.getElementById('confirmModal');
    const confirmModalMessage = document.getElementById('confirmModalMessage');
    const confirmModalConfirmBtn = document.getElementById('confirmModalConfirmBtn');
    const confirmModalCancelBtn = document.getElementById('confirmModalCancelBtn');

    let pendingForm = null;

    function askToConfirm(form, message) {
      pendingForm = form;
      confirmModalMessage.textContent = message;
      confirmModal.classList.add('open');
    }

    confirmModalCancelBtn.addEventListener('click', function () {
      pendingForm = null;
      confirmModal.classList.remove('open');
    });

    confirmModalConfirmBtn.addEventListener('click', function () {
      const form = pendingForm;
      pendingForm = null;
      confirmModal.classList.remove('open');
      if (form) form.submit();
    });

    // Email change: only intercept if the address actually changed.
    const editUserForm = document.getElementById('editUserForm');
    const emailInput = document.getElementById('emailInput');

    editUserForm.addEventListener('submit', function (e) {
      if (pendingForm) return; // already confirmed, let it through
      const newEmail = emailInput.value.trim();
      const originalEmail = emailInput.getAttribute('data-original');
      if (newEmail !== originalEmail) {
        e.preventDefault();
        askToConfirm(editUserForm, 'This will send a confirmation link to "' + newEmail + '". The account keeps using its current email until that link is confirmed. Continue?');
      }
    });

    // Password reset: always confirm before sending.
    const passwordResetForm = document.getElementById('passwordResetForm');
    passwordResetForm.addEventListener('submit', function (e) {
      if (pendingForm) return;
      e.preventDefault();
      askToConfirm(passwordResetForm, 'This will email a password reset link to {{ $user->email }}. Continue?');
    });
  </script>
</body>
</html>
