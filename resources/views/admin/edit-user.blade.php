<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit User | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 40px; width: 100%; max-width: 500px;">
      <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Edit User</h2>
      <p style="font-size: 14px; color: #8a6a40; margin: 0 0 28px;">Update details for {{ $user->name }}</p>

      <form method="POST" action="{{ route('admin.update', $user->id) }}">
        @csrf @method('PUT')

        <div style="margin-bottom: 20px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Full Name</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" required
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 14px 16px; border-radius: 6px; font-size: 15px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
            onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
          @error('name') <span style="color: #e74c3c; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 20px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Email Address</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" required
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 14px 16px; border-radius: 6px; font-size: 15px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
            onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
          @error('email') <span style="color: #e74c3c; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 20px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">New Password <span style="font-weight: 400;">(optional)</span></label>
          <input type="password" name="password" placeholder="Leave blank to keep current"
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 14px 16px; border-radius: 6px; font-size: 15px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
            onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
          <span style="font-size: 11px; color: #8a6a40; margin-top: 4px; display: block;">Minimum 8 characters</span>
          @error('password') <span style="color: #e74c3c; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 28px;">
          <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Confirm New Password</label>
          <input type="password" name="password_confirmation" placeholder="Confirm new password"
            style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 14px 16px; border-radius: 6px; font-size: 15px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
            onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
        </div>

        <div style="display: flex; gap: 12px;">
          <button type="submit" style="flex: 1; background: #c9a84c; color: #2c1a0e; border: none; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 15px; cursor: pointer; transition: opacity 0.3s; font-family: 'Jost', sans-serif;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">Save Changes</button>
          <a href="{{ route('admin.dashboard') }}" style="flex: 1; border: 1px solid #d4c4a0; color: #8a6a40; text-align: center; padding: 14px; border-radius: 6px; font-size: 15px; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.borderColor='#c9a84c'; this.style.color='#c9a84c';" onmouseout="this.style.borderColor='#d4c4a0'; this.style.color='#8a6a40';">Cancel</a>
        </div>
      </form>
    </div>
  </main>
</body>
</html>