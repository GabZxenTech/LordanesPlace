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
      <p style="font-size: 14px; color: #8a6a40; margin: 0 0 20px;">Details for {{ $user->name }}</p>

      @if(session('success'))
        <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">✓ {{ session('success') }}</div>
      @endif
      @if($errors->has('error'))
        <div style="background: #f8d7da; border: 1px solid #dc3545; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">{{ $errors->first('error') }}</div>
      @endif

      <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Full Name</label>
        <input type="text" value="{{ $user->name }}" readonly disabled
          style="width: 100%; background: #ece3d3; border: 1px solid #d4c4a0; color: #6b5636; padding: 14px 16px; border-radius: 6px; font-size: 15px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box; cursor: not-allowed;" />
        <span style="font-size: 11px; color: #8a6a40; margin-top: 6px; display: block;">Full Name cannot be changed from this page.</span>
      </div>

      <div style="margin-bottom: 8px;">
        <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Email Address</label>
        <input type="email" value="{{ $user->email }}" readonly disabled
          style="width: 100%; background: #ece3d3; border: 1px solid #d4c4a0; color: #6b5636; padding: 14px 16px; border-radius: 6px; font-size: 15px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box; cursor: not-allowed;" />
        <span style="font-size: 11px; color: #8a6a40; margin-top: 6px; display: block;">Email Address cannot be changed from this page.</span>
      </div>

      @if($user->pending_email)
        <div style="background: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 12px 16px; border-radius: 6px; margin: 20px 0; font-size: 13px;">
          ⏳ Pending confirmation for <strong>{{ $user->pending_email }}</strong>
        </div>
      @endif

      <div style="margin-top: 24px;">
        <a href="{{ route('admin.dashboard') }}" style="display: block; text-align: center; border: 1px solid #d4c4a0; color: #8a6a40; padding: 14px; border-radius: 6px; font-size: 15px; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.borderColor='#c9a84c'; this.style.color='#c9a84c';" onmouseout="this.style.borderColor='#d4c4a0'; this.style.color='#8a6a40';">Back to User Management</a>
      </div>

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
