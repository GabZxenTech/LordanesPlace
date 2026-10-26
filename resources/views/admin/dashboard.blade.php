<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | LDReserve</title>
  @include('partials._favicon')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .dash-card { background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; }
    .dash-card-title { font-size: 13px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0; }
    .dash-stat { padding: 22px 24px; text-decoration: none; display: block; transition: transform 0.2s, box-shadow 0.2s; }
    .dash-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(44,26,14,0.08); }
    .dash-stat-label { font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700; text-transform: uppercase; margin: 0 0 8px; }
    .dash-stat-value { font-family: 'Cormorant Garamond', serif; font-size: 44px; font-weight: 700; color: #7a5a10; margin: 0; line-height: 1; }
    .dash-stat-note { font-size: 12px; color: #8a6a40; margin: 8px 0 0; }
    .dash-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .dash-grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
    .dash-grid-eq { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 1200px) { .dash-grid-4 { grid-template-columns: repeat(2, 1fr); } .dash-grid-2, .dash-grid-eq { grid-template-columns: 1fr; } }
    @media (max-width: 600px) { .dash-grid-4 { grid-template-columns: 1fr; } }
    .dash-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 24px; border-bottom: 1px solid #e8dcc8; text-decoration: none; transition: background 0.2s; }
    .dash-row:last-child { border-bottom: none; }
    .dash-row:hover { background: #f5edd8; }
    .dash-row-main { font-size: 14px; font-weight: 600; color: #2c1a0e; margin: 0; }
    .dash-row-sub { font-size: 12px; color: #8a6a40; margin: 2px 0 0; }
    .dash-empty { padding: 28px 24px; text-align: center; font-size: 13px; color: #8a6a40; margin: 0; }
    .dash-link { font-size: 12px; font-weight: 700; color: #8a6a40; text-decoration: none; letter-spacing: 1px; text-transform: uppercase; }
    .dash-link:hover { color: #2c1a0e; }
    .dash-head { padding: 18px 24px; border-bottom: 1px solid #d4c4a0; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .status-dot { display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; text-transform: capitalize; white-space: nowrap; }
    .status-pending { background: #fdf0c4; color: #9a5b0c; border: 1px solid #f6e3a1; }
    .status-approved { background: #d5f5e3; color: #15803d; border: 1px solid #b7ebcb; }
    .status-rejected { background: #fde0e0; color: #c0392b; border: 1px solid #f8c4c4; }
    .status-ongoing { background: #dbeafe; color: #1d6fb8; border: 1px solid #bfd9f8; }
    .status-completed { background: #ece3ff; color: #7c4dcc; border: 1px solid #d9c9fa; }
    .status-cancelled { background: #dde3ec; color: #3d4a5c; border: 1px solid #c9d2df; }
    .dash-action { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 14px 20px; border: 1px solid #d4c4a0; border-radius: 8px; background: #fff9ef; color: #2c1a0e; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s; }
    .dash-action:hover { background: #2c1a0e; color: #f5edd8; border-color: #2c1a0e; }
    .dash-action .count { min-width: 22px; height: 22px; padding: 0 6px; border-radius: 11px; background: #c0392b; color: #fff; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; }
  </style>
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  {{-- MAIN CONTENT --}}
  <main style="flex: 1; min-width: 0; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">

    <div style="margin-bottom: 32px;">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Dashboard</h1>
      <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">{{ now()->format('l, F j, Y') }}</p>
    </div>

    {{-- Stat cards --}}
    <div class="dash-grid-4" style="margin-bottom: 20px;">
      <a href="{{ route('admin.schedule') }}" class="dash-card dash-stat">
        <p class="dash-stat-label">Pending Approvals</p>
        <p class="dash-stat-value">{{ $stats['pending'] }}</p>
        <p class="dash-stat-note">Bookings waiting for review</p>
      </a>
      <a href="{{ route('admin.schedule') }}" class="dash-card dash-stat">
        <p class="dash-stat-label">Upcoming Events</p>
        <p class="dash-stat-value">{{ $stats['upcoming'] }}</p>
        <p class="dash-stat-note">Within the next 30 days</p>
      </a>
      <a href="{{ route('admin.reports.index') }}" class="dash-card dash-stat">
        <p class="dash-stat-label">Revenue This Month</p>
        <p class="dash-stat-value">₱{{ number_format($stats['revenue'], 2) }}</p>
        <p class="dash-stat-note">Confirmed payments</p>
      </a>
      <a href="{{ route('admin.schedule') }}" class="dash-card dash-stat">
        <p class="dash-stat-label">Balance to Collect</p>
        <p class="dash-stat-value">₱{{ number_format($stats['balance'], 2) }}</p>
        <p class="dash-stat-note">Unpaid on active bookings</p>
      </a>
    </div>

    {{-- Charts --}}
    <div class="dash-grid-2" style="margin-bottom: 20px;">
      <div class="dash-card">
        <div class="dash-head"><h2 class="dash-card-title">Bookings &amp; Revenue (6 Months)</h2></div>
        <div style="padding: 20px 24px; height: 300px;"><canvas id="monthlyChart"></canvas></div>
      </div>
      <div class="dash-card">
        <div class="dash-head"><h2 class="dash-card-title">Bookings by Status</h2></div>
        <div style="padding: 20px 24px; height: 300px;"><canvas id="statusChart"></canvas></div>
      </div>
    </div>

    {{-- Lists --}}
    <div class="dash-grid-eq" style="margin-bottom: 20px;">
      <div class="dash-card" style="overflow: hidden;">
        <div class="dash-head">
          <h2 class="dash-card-title">Pending Bookings</h2>
          <a href="{{ route('admin.schedule') }}" class="dash-link">View all</a>
        </div>
        @forelse($pendingBookings as $b)
          <a href="{{ route('admin.schedule') }}" class="dash-row">
            <div>
              <p class="dash-row-main">{{ $b->user->name ?? 'Deleted user' }}</p>
              <p class="dash-row-sub">{{ $b->package }} · {{ $b->event_date->format('M d, Y') }}</p>
            </div>
            <span class="status-dot status-pending">Pending</span>
          </a>
        @empty
          <p class="dash-empty">No bookings waiting for approval.</p>
        @endforelse
      </div>

      <div class="dash-card" style="overflow: hidden;">
        <div class="dash-head">
          <h2 class="dash-card-title">Upcoming Events</h2>
          <a href="{{ route('admin.schedule') }}" class="dash-link">View all</a>
        </div>
        @forelse($upcomingEvents as $b)
          <a href="{{ route('admin.schedule') }}" class="dash-row">
            <div>
              <p class="dash-row-main">{{ $b->user->name ?? 'Deleted user' }}</p>
              <p class="dash-row-sub">{{ $b->event_type }} · {{ $b->package }}</p>
            </div>
            <div style="text-align: right;">
              <p class="dash-row-main">{{ $b->event_date->format('M d, Y') }}</p>
              <p class="dash-row-sub">{{ $b->event_date->isToday() ? 'Today' : $b->event_date->diffForHumans() }}</p>
            </div>
          </a>
        @empty
          <p class="dash-empty">No upcoming events.</p>
        @endforelse
      </div>
    </div>

    <div class="dash-grid-eq" style="margin-bottom: 20px;">
      <div class="dash-card" style="overflow: hidden;">
        <div class="dash-head"><h2 class="dash-card-title">Recent Bookings</h2></div>
        @forelse($recentBookings as $b)
          <a href="{{ route('admin.schedule') }}" class="dash-row">
            <div>
              <p class="dash-row-main">{{ $b->user->name ?? 'Deleted user' }}</p>
              <p class="dash-row-sub">{{ $b->package }} · booked {{ $b->created_at->diffForHumans() }}</p>
            </div>
            <span class="status-dot status-{{ $b->status }}">{{ $b->status }}</span>
          </a>
        @empty
          <p class="dash-empty">No bookings yet.</p>
        @endforelse
      </div>

      <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="dash-card" style="overflow: hidden;">
          <div class="dash-head"><h2 class="dash-card-title">Popular Packages</h2></div>
          @forelse($topPackages as $p)
            <div class="dash-row" style="cursor: default;">
              <p class="dash-row-main">{{ $p->package }}</p>
              <span style="font-size: 13px; font-weight: 700; color: #7a5a10;">{{ $p->total }} {{ $p->total == 1 ? 'booking' : 'bookings' }}</span>
            </div>
          @empty
            <p class="dash-empty">No data yet.</p>
          @endforelse
        </div>

        <div class="dash-card" style="padding: 20px 24px;">
          <h2 class="dash-card-title" style="margin-bottom: 14px;">Quick Actions</h2>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <a href="{{ route('admin.reschedules.index') }}" class="dash-action">Reschedules @if($pendingReschedules)<span class="count">{{ $pendingReschedules }}</span>@endif</a>
            <a href="{{ route('admin.visits.index') }}" class="dash-action">Visits @if($pendingVisits)<span class="count">{{ $pendingVisits }}</span>@endif</a>
            <a href="{{ route('admin.chat.index') }}" class="dash-action">Chat @if($unreadChats)<span class="count">{{ $unreadChats }}</span>@endif</a>
            <a href="{{ route('admin.schedule') }}" class="dash-action">Block Date</a>
            <a href="{{ route('admin.packages.index') }}" class="dash-action">Packages</a>
            <a href="{{ route('admin.reports.index') }}" class="dash-action">Reports</a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    (function () {
      if (typeof Chart === 'undefined') return;
      Chart.defaults.font.family = "'Jost', sans-serif";
      Chart.defaults.color = '#8a6a40';

      const monthly = @json($monthlyChart);
      new Chart(document.getElementById('monthlyChart'), {
        data: {
          labels: monthly.labels,
          datasets: [
            { type: 'bar', label: 'Bookings', data: monthly.bookings, backgroundColor: '#e3cf94', borderRadius: 4, yAxisID: 'y' },
            { type: 'line', label: 'Revenue (₱)', data: monthly.revenue, borderColor: '#7a5a10', backgroundColor: '#7a5a10', tension: 0.3, yAxisID: 'y1' }
          ]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          plugins: { legend: { position: 'bottom' } },
          scales: {
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#e8dcc8' } },
            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { callback: v => '₱' + v.toLocaleString() } }
          }
        }
      });

      // Same colors as the status badges
      new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
          labels: ['Pending', 'Approved', 'Ongoing', 'Completed', 'Cancelled', 'Rejected'],
          datasets: [{ data: @json($statusChart), backgroundColor: ['#f6d86b', '#6fcf97', '#7fb4ee', '#b79cf0', '#aab4c4', '#ee8c8c'], borderColor: '#fff9ef', borderWidth: 2 }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { position: 'bottom' } } }
      });
    })();
  </script>
</body>
</html>
