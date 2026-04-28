<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | LorDane's Place</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  {{-- MAIN CONTENT --}}
  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">

    {{-- Page Header --}}
    <div style="margin-bottom: 32px;">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">User Management</h1>
      <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Manage Registered Accounts</p>
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px;">✓ {{ session('success') }}</div>
    @endif

    {{-- Stats Cards --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 36px;">
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 28px 24px; text-align: center;">
        <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 48px; font-weight: 700; color: #c9a84c; margin: 0; line-height: 1;">{{ $users->count() }}</h3>
        <p style="font-size: 11px; letter-spacing: 3px; color: #8a6a40; margin: 10px 0 0; text-transform: uppercase; font-weight: 700;">Total Users</p>
      </div>
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 28px 24px; text-align: center;">
        <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 48px; font-weight: 700; color: #c9a84c; margin: 0; line-height: 1;">{{ $users->filter(fn($u) => $u->last_active && \Carbon\Carbon::parse($u->last_active)->diffInMinutes(now()) <= 30)->count() }}</h3>
        <p style="font-size: 11px; letter-spacing: 3px; color: #8a6a40; margin: 10px 0 0; text-transform: uppercase; font-weight: 700;">Active Now</p>
      </div>
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; padding: 28px 24px; text-align: center;">
        <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 48px; font-weight: 700; color: #c9a84c; margin: 0; line-height: 1;">{{ $users->filter(fn($u) => $u->created_at->isToday())->count() }}</h3>
        <p style="font-size: 11px; letter-spacing: 3px; color: #8a6a40; margin: 10px 0 0; text-transform: uppercase; font-weight: 700;">New Today</p>
      </div>
    </div>

    {{-- Users Table --}}
    <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden;">
      <div style="padding: 20px 28px; border-bottom: 1px solid #d4c4a0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <h2 style="font-size: 13px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">All Users</h2>
        <input type="text" id="searchInput" placeholder="Search user..." onkeyup="searchTable()"
          style="background: #2c1a0e; border: none; color: #f5f0e8; padding: 12px 20px; border-radius: 6px; font-size: 14px; outline: none; width: 260px; font-family: 'Jost', sans-serif;" />
      </div>

      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;" id="usersTable">
          <thead>
            <tr style="border-bottom: 1px solid #d4c4a0;">
              <th style="padding: 14px 24px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">#</th>
              <th style="padding: 14px 24px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">NAME</th>
              <th style="padding: 14px 24px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">EMAIL</th>
              <th style="padding: 14px 24px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">REGISTERED</th>
              <th style="padding: 14px 24px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">STATUS</th>
              <th style="padding: 14px 24px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $index => $user)
              <tr style="border-bottom: 1px solid #e8dcc8; transition: background 0.2s;" onmouseover="this.style.background='#f5edd8'" onmouseout="this.style.background='transparent'">
                <td style="padding: 16px 24px; font-size: 15px; color: #8a6a40;">{{ $index + 1 }}</td>
                <td style="padding: 16px 24px; font-size: 15px; color: #2c1a0e; font-weight: 600;">{{ $user->name }}</td>
                <td style="padding: 16px 24px; font-size: 15px; color: #8a6a40;">{{ $user->email }}</td>
                <td style="padding: 16px 24px; font-size: 15px; color: #8a6a40;">{{ $user->created_at->format('M d, Y') }}</td>
                <td style="padding: 16px 24px;">
                  @if($user->last_active && \Carbon\Carbon::parse($user->last_active)->diffInMinutes(now()) <= 30)
                    <span style="display: inline-block; padding: 5px 14px; border-radius: 100px; font-size: 11px; font-weight: 700; background: #d4edda; color: #28a745; border: 1px solid #28a745;">● Active</span>
                  @else
                    <span style="display: inline-block; padding: 5px 14px; border-radius: 100px; font-size: 11px; font-weight: 700; background: #f5f0e8; color: #8a6a40; border: 1px solid #d4c4a0;">○ Inactive</span>
                  @endif
                </td>
                <td style="padding: 16px 24px;">
                  <div style="display: flex; gap: 8px;">
                    <a href="{{ route('admin.edit', $user->id) }}" style="background: transparent; border: 1px solid #c9a84c; color: #c9a84c; padding: 6px 16px; border-radius: 4px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#c9a84c'; this.style.color='#fff9ef';" onmouseout="this.style.background='transparent'; this.style.color='#c9a84c';">Edit</a>
                    <form method="POST" action="{{ route('admin.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')">
                      @csrf @method('DELETE')
                      <button type="submit" style="background: transparent; border: 1px solid #e74c3c; color: #e74c3c; padding: 6px 16px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s; font-family: 'Jost', sans-serif;" onmouseover="this.style.background='#e74c3c'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#e74c3c';">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" style="text-align: center; padding: 60px; color: #8a6a40; font-size: 16px;">No users found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </main>

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