<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit User | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page bg-admin-dark text-admin-cream font-body min-h-screen">

  @include('partials._admin-header')

  <div class="flex items-center justify-center p-5 md:p-10" style="min-height: calc(100vh - 70px);">
    <div class="bg-admin-card border border-admin-gold/25 rounded-lg p-7 md:p-10 w-full max-w-[500px]">
      <h2 class="font-heading text-[24px] text-admin-gold mb-1.5">Edit User</h2>
      <p class="text-admin-cream-dim text-[15px] mb-7">Update details for {{ $user->name }}</p>

      <form method="POST" action="{{ route('admin.update', $user->id) }}">
        @csrf @method('PUT')

        <div class="mb-5">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Full Name</label>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" required
            class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-4 py-3 rounded-md text-[16px] outline-none transition-colors focus:border-admin-gold font-body" />
          @error('name') <span class="text-admin-red text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-5">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Email Address</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" required
            class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-4 py-3 rounded-md text-[16px] outline-none transition-colors focus:border-admin-gold font-body" />
          @error('email') <span class="text-admin-red text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-5">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">New Password <span class="font-normal">(optional)</span></label>
          <input type="password" name="password" placeholder="Leave blank to keep current"
            class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-4 py-3 rounded-md text-[16px] outline-none transition-colors focus:border-admin-gold font-body placeholder:text-admin-cream-dim" />
          <span class="text-[11px] text-admin-cream-dim mt-1 block">Minimum 8 characters</span>
          @error('password') <span class="text-admin-red text-[12px] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
          <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Confirm New Password</label>
          <input type="password" name="password_confirmation" placeholder="Confirm new password"
            class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-4 py-3 rounded-md text-[16px] outline-none transition-colors focus:border-admin-gold font-body placeholder:text-admin-cream-dim" />
        </div>

        <div class="flex gap-3">
          <button type="submit" class="flex-1 bg-admin-gold text-admin-dark border-none py-3 rounded-md font-bold text-[16px] cursor-pointer transition-colors hover:bg-admin-gold-light">Save Changes</button>
          <a href="{{ route('admin.dashboard') }}" class="flex-1 bg-transparent border border-admin-cream-dim/30 text-admin-cream-dim text-center py-3 rounded-md text-[16px] no-underline transition-all hover:border-admin-cream hover:text-admin-cream">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>