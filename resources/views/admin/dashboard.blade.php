<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page bg-admin-dark text-admin-cream font-body min-h-screen">

  @include('partials._admin-header')

  <div class="p-5 md:p-10">

    @if(session('success'))
      <div class="bg-green-400/15 border border-green-400 text-green-400 px-5 py-3 rounded-md mb-5 text-[16px]">✓ {{ session('success') }}</div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-5 mb-8 md:mb-10">
      <div class="bg-admin-card border border-admin-gold/25 rounded-lg p-5 md:p-6 text-center">
        <h3 class="text-[32px] md:text-[36px] font-bold text-admin-gold">{{ $users->count() }}</h3>
        <p class="text-[15px] text-admin-cream-dim mt-1 tracking-[1px]">TOTAL USERS</p>
      </div>
      <div class="bg-admin-card border border-admin-gold/25 rounded-lg p-5 md:p-6 text-center">
        <h3 class="text-[32px] md:text-[36px] font-bold text-admin-gold">{{ $users->filter(fn($u) => $u->last_active && \Carbon\Carbon::parse($u->last_active)->diffInMinutes(now()) <= 30)->count() }}</h3>
        <p class="text-[15px] text-admin-cream-dim mt-1 tracking-[1px]">ACTIVE NOW</p>
      </div>
      <div class="bg-admin-card border border-admin-gold/25 rounded-lg p-5 md:p-6 text-center">
        <h3 class="text-[32px] md:text-[36px] font-bold text-admin-gold">{{ $users->filter(fn($u) => $u->created_at->isToday())->count() }}</h3>
        <p class="text-[15px] text-admin-cream-dim mt-1 tracking-[1px]">NEW TODAY</p>
      </div>
    </div>

    <!-- Users Table -->
    <div class="bg-admin-card border border-admin-gold/20 rounded-lg overflow-hidden">
      <div class="p-5 border-b border-admin-gold/20 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h2 class="text-[15px] md:text-[16px] font-bold text-admin-cream tracking-[1px]">USER MANAGEMENT</h2>
        <input type="text" id="searchInput" placeholder="Search user..." onkeyup="searchTable()"
          class="bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2 rounded text-[15px] outline-none w-full sm:w-[220px] transition-colors focus:border-admin-gold placeholder:text-admin-cream-dim font-body" />
      </div>

      <div class="overflow-x-auto">
        <table class="w-full border-collapse" id="usersTable">
          <thead class="bg-admin-secondary">
            <tr>
              <th class="p-3.5 md:px-5 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">#</th>
              <th class="p-3.5 md:px-5 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">NAME</th>
              <th class="p-3.5 md:px-5 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold hidden md:table-cell">EMAIL</th>
              <th class="p-3.5 md:px-5 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold hidden lg:table-cell">REGISTERED</th>
              <th class="p-3.5 md:px-5 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold hidden lg:table-cell">LAST ACTIVE</th>
              <th class="p-3.5 md:px-5 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">STATUS</th>
              <th class="p-3.5 md:px-5 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $index => $user)
              <tr class="border-b border-admin-gold/10 transition-colors hover:bg-admin-gold/5">
                <td class="p-3.5 md:px-5 md:py-3.5 text-[15px] text-admin-cream-dim">{{ $index + 1 }}</td>
                <td class="p-3.5 md:px-5 md:py-3.5 text-[15px] text-admin-cream font-bold">{{ $user->name }}</td>
                <td class="p-3.5 md:px-5 md:py-3.5 text-[15px] text-admin-cream-dim hidden md:table-cell">{{ $user->email }}</td>
                <td class="p-3.5 md:px-5 md:py-3.5 text-[15px] text-admin-cream-dim hidden lg:table-cell">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="p-3.5 md:px-5 md:py-3.5 text-[15px] text-admin-cream-dim hidden lg:table-cell">
                  {{ $user->last_active ? \Carbon\Carbon::parse($user->last_active)->diffForHumans() : 'Never' }}
                </td>
                <td class="p-3.5 md:px-5 md:py-3.5">
                  @if($user->last_active && \Carbon\Carbon::parse($user->last_active)->diffInMinutes(now()) <= 30)
                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-400/15 text-green-400 border border-green-400">● Active</span>
                  @else
                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-admin-cream-dim/10 text-admin-cream-dim border border-admin-cream-dim/30">○ Inactive</span>
                  @endif
                </td>
                <td class="p-3.5 md:px-5 md:py-3.5">
                  <div class="flex gap-2">
                    <a href="{{ route('admin.edit', $user->id) }}" class="bg-admin-gold/15 border border-admin-gold text-admin-gold px-3 py-1.5 rounded text-[12px] no-underline transition-all hover:bg-admin-gold hover:text-admin-dark">Edit</a>
                    <form method="POST" action="{{ route('admin.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="bg-red-500/15 border border-red-500 text-red-500 px-3 py-1.5 rounded text-[12px] cursor-pointer transition-all hover:bg-red-500 hover:text-white">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center py-[60px] text-admin-cream-dim text-[16px]">No users found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function searchTable() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const rows = document.querySelectorAll('#usersTable tbody tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
      });
    }
  </script>
</body>
</html>