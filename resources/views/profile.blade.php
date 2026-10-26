<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Reservations | LorDane's Place</title>
  @include('partials._favicon')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* My Reservations page styles */
    .rv-page { --rv-surface: #fffcf5; --rv-tint: #f2ebdc; --rv-ink: #1a1208; --rv-soft: #5c4a33; --rv-faint: #8f7c62; --rv-line: #e8dcc4; --rv-gold: #b8860b; --rv-brown: #2c1a0e;
      padding: 40px 0 72px; min-height: 100vh; font-family: 'Jost', sans-serif; color: var(--rv-ink); }
    .rv-wrap { max-width: 1080px; margin: 0 auto; padding: 0 16px; }
    .rv-page [hidden] { display: none !important; } /* tabs hide cards whose class sets display */
    .rv-flash { border-radius: 8px; padding: 12px 16px; margin-bottom: 18px; font-size: 14px; }
    .rv-flash-ok { background: #e3f3ea; color: #1f6b43; border: 1px solid #b9e0c9; }
    .rv-flash-err { background: #fbe5e2; color: #9b2c20; border: 1px solid #f1bdb6; }

    .rv-profile { background: var(--rv-surface); border: 1px solid var(--rv-line); border-radius: 12px; padding: 24px; display: grid; gap: 20px; }
    .rv-profile-top { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .rv-avatar { width: 56px; height: 56px; border-radius: 50%; background: var(--rv-gold); color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 20px; flex-shrink: 0; }
    .rv-who { flex: 1; min-width: 0; }
    .rv-who h1 { font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 700; line-height: 1.1; margin: 0; }
    .rv-who p { margin: 4px 0 0; color: var(--rv-faint); font-size: 14px; display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .rv-email { overflow-wrap: anywhere; } /* wraps only when the address can't fit */
    .rv-tier { background: #fbf0d9; color: var(--rv-gold); font-weight: 700; font-size: 12px; padding: 2px 10px; border-radius: 999px; }
    .rv-btn { display: inline-block; background: var(--rv-gold); color: #fff; font-weight: 700; font-size: 13px; letter-spacing: 1.2px; text-transform: uppercase; padding: 12px 20px; border-radius: 6px; text-decoration: none; transition: background .2s; }
    .rv-btn:hover { background: #c9a84c; }
    .rv-btn-outline { background: transparent; color: var(--rv-gold); border: 1px solid var(--rv-gold); padding: 11px 18px; }
    .rv-btn-outline:hover { background: #fbf0d9; }
    .rv-head-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .rv-avatar { overflow: hidden; }
    .rv-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .rv-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
    .rv-stat { background: var(--rv-tint); border-radius: 10px; padding: 14px 16px; }
    .rv-stat b { display: block; font-family: 'Cormorant Garamond', serif; font-size: 32px; line-height: 1; font-variant-numeric: lining-nums tabular-nums; }
    .rv-stat span { font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--rv-faint); font-weight: 600; }
    .rv-stat-money b { color: var(--rv-gold); font-size: 28px; }

    .rv-list-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin: 36px 0 14px; }
    .rv-list-head h2 { font-family: 'Cormorant Garamond', serif; font-size: 36px; font-weight: 700; margin: 0; }
    .rv-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .rv-tab { font: inherit; font-size: 14px; font-weight: 500; background: transparent; color: var(--rv-soft); border: 1px solid var(--rv-line); border-radius: 999px; padding: 6px 14px; cursor: pointer; }
    .rv-tab .rv-n { margin-left: 5px; color: var(--rv-faint); font-variant-numeric: tabular-nums; }
    .rv-tab[aria-selected="true"] { background: var(--rv-brown); border-color: var(--rv-brown); color: #fff; }
    .rv-tab[aria-selected="true"] .rv-n { color: #c9a84c; }
    .rv-tab:focus-visible, .rv-act:focus-visible, .rv-btn:focus-visible { outline: 2px solid var(--rv-ink); outline-offset: 2px; }

    .rv-list { display: grid; gap: 14px; }
    .rv-ticket { display: grid; grid-template-columns: 96px minmax(0, 1fr) 240px; background: var(--rv-surface); border: 1px solid var(--rv-line); border-radius: 12px; overflow: hidden; scroll-margin-top: 110px; transition: box-shadow .3s; }
    .rv-group { display: grid; grid-template-columns: 96px minmax(0, 1fr); background: var(--rv-surface); border: 1px solid var(--rv-line); border-radius: 12px; overflow: hidden; }
    .is-dim { opacity: .72; }
    .rv-stub { background: var(--rv-tint); border-right: 2px dashed var(--rv-line); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 16px 6px; text-align: center; }
    .rv-stub .m { font-size: 12px; letter-spacing: 2px; font-weight: 700; color: var(--rv-gold); }
    .rv-stub .d { font-family: 'Cormorant Garamond', serif; font-size: 42px; font-weight: 700; line-height: 1; font-variant-numeric: lining-nums; }
    .rv-stub .w { font-size: 11px; color: var(--rv-faint); text-transform: uppercase; letter-spacing: 1px; }

    .rv-body { padding: 16px 20px; min-width: 0; display: grid; gap: 8px; align-content: start; }
    .rv-title { display: flex; gap: 8px; align-items: baseline; flex-wrap: wrap; }
    .rv-title h3 { margin: 0; font-size: 18px; font-weight: 700; }
    .rv-occ { font-size: 13px; color: var(--rv-faint); }
    .rv-meta { display: flex; flex-wrap: wrap; gap: 4px 16px; font-size: 13px; color: var(--rv-soft); }
    .rv-ref { color: var(--rv-faint); font-variant-numeric: tabular-nums; }
    .rv-chips { display: flex; gap: 6px; flex-wrap: wrap; }
    .rv-chip { font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; padding: 3px 9px; border-radius: 999px; }
    .rv-s-pending { color: #a86a00; background: #fbf0d9; }
    .rv-s-approved { color: #2f8f5b; background: #e3f3ea; }
    .rv-s-ongoing { color: #2f6fbf; background: #e3ecf8; }
    .rv-s-completed { color: #fff; background: #c9a84c; }
    .rv-s-cancelled { color: #fff9ef; background: #2c1a0e; }
    .rv-s-rejected { color: #c0392b; background: #fbe5e2; }
    .rv-p-unpaid { color: #c0392b; background: #fbe5e2; }
    .rv-p-partial { color: #a86a00; background: #fbf0d9; }
    .rv-p-paid { color: #2f8f5b; background: #e3f3ea; }
    .rv-visit { font-size: 13px; color: var(--rv-soft); display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
    .rv-visit svg { color: var(--rv-gold); flex-shrink: 0; }
    .rv-muted { color: var(--rv-faint); font-size: 12px; }
    .rv-link { color: var(--rv-gold); font-weight: 700; text-decoration: none; }
    .rv-link:hover { text-decoration: underline; }
    .rv-reason { margin: 0; font-size: 13px; color: #7a4f00; background: #fff6e0; border-radius: 6px; padding: 8px 12px; }
    .rv-note { margin: 0; font-size: 13px; color: var(--rv-faint); font-style: italic; }

    .rv-steps { max-width: 420px; margin-top: 2px; }
    .rv-track { display: flex; align-items: center; }
    .rv-step { display: flex; align-items: center; flex: 1; min-width: 0; }
    .rv-step:last-child { flex: 0; }
    .rv-dot { width: 10px; height: 10px; border-radius: 50%; border: 2px solid var(--rv-line); background: var(--rv-surface); flex-shrink: 0; }
    .rv-bar { height: 2px; background: var(--rv-line); flex: 1; }
    .rv-step.done .rv-dot { background: var(--rv-gold); border-color: var(--rv-gold); }
    .rv-step.done .rv-bar { background: var(--rv-gold); }
    .rv-step.now .rv-dot { background: var(--rv-surface); box-shadow: 0 0 0 3px #fbf0d9; }
    .rv-step.now .rv-bar { background: var(--rv-line); }
    .rv-step-labels { display: flex; justify-content: space-between; font-size: 11px; color: var(--rv-faint); margin-top: 3px; }
    .rv-step-labels .cur { color: var(--rv-gold); font-weight: 700; }

    .rv-money { border-left: 1px solid var(--rv-line); padding: 16px 18px; display: grid; gap: 8px; align-content: start; }
    .rv-total { font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 700; line-height: 1; font-variant-numeric: lining-nums tabular-nums; }
    .rv-total small { display: block; font-family: 'Jost', sans-serif; font-size: 11px; color: var(--rv-faint); font-weight: 500; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 4px; }
    .rv-pay { display: grid; gap: 4px; font-size: 12px; color: var(--rv-soft); }
    .rv-pay-track { height: 6px; border-radius: 999px; background: var(--rv-tint); overflow: hidden; }
    .rv-pay-fill { height: 100%; background: var(--rv-gold); border-radius: 999px; }
    .rv-pay-row { display: flex; justify-content: space-between; font-variant-numeric: tabular-nums; }
    .rv-due { font-size: 11px; color: var(--rv-gold); font-weight: 600; margin-top: 4px; line-height: 1.35; }
    .rv-acts { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 2px; }
    .rv-acts form { margin: 0; }
    .rv-act { font: inherit; font-size: 12px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; padding: 8px 12px; border-radius: 6px; cursor: pointer; background: transparent; border: 1px solid var(--rv-line); color: var(--rv-soft); transition: background .2s; }
    .rv-act-primary { border-color: var(--rv-gold); color: var(--rv-gold); }
    .rv-act-primary:hover { background: #fbf0d9; }
    .rv-act-danger { border-color: #e9b7b1; color: #c0392b; }
    .rv-act-danger:hover { background: #fbe5e2; }

    .rv-group-body { min-width: 0; }
    .rv-group-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 14px 20px; border-bottom: 1px solid var(--rv-line); }
    .rv-group-head h3 { margin: 0; font-size: 16px; font-weight: 700; }
    .rv-sum { font-size: 13px; color: var(--rv-soft); font-variant-numeric: tabular-nums; }
    .rv-sum b { font-family: 'Cormorant Garamond', serif; font-size: 22px; color: var(--rv-ink); font-variant-numeric: lining-nums; }
    .rv-sub { display: grid; grid-template-columns: minmax(0, 1fr) 240px; border-bottom: 1px solid var(--rv-line); scroll-margin-top: 110px; transition: box-shadow .3s; }
    .rv-sub:last-child { border-bottom: 0; }

    /* Cancel confirmation modal (system palette: cream, gold, brown) */
    .rv-modal { position: fixed; inset: 0; z-index: 1100; background: rgba(26,18,8,.55); backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center; padding: 16px; font-family: 'Jost', sans-serif; }
    .rv-modal[hidden] { display: none; }
    .rv-modal-box { position: relative; background: #fffcf5; border: 1px solid #e8dcc4; border-radius: 16px; max-width: 520px; width: 100%; padding: 32px 28px 26px; text-align: center; box-shadow: 0 30px 60px rgba(26,18,8,.28); }
    .rv-modal-close { position: absolute; top: 14px; right: 14px; width: 34px; height: 34px; border-radius: 50%; border: 0; background: transparent; color: #8f7c62; display: grid; place-items: center; cursor: pointer; }
    .rv-modal-close:hover { background: #f2ebdc; color: #1a1208; }
    .rv-modal-icon { width: 54px; height: 54px; border-radius: 50%; background: #f5ead2; color: #b8860b; display: grid; place-items: center; margin: 0 auto 14px; }
    .rv-modal-box h3 { font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 700; color: #1a1208; margin: 0 0 8px; line-height: 1.1; }
    .rv-modal-box > p { font-size: 14px; color: #5c4a33; line-height: 1.55; margin: 0 0 20px; }
    .rv-modal-booking { display: flex; align-items: center; gap: 14px; text-align: left; background: #f7f1e4; border: 1px solid #e8dcc4; border-radius: 12px; padding: 12px; margin-bottom: 22px; }
    .rv-modal-thumb { width: 96px; height: 72px; border-radius: 8px; object-fit: cover; flex-shrink: 0; background: #e8dcc4; }
    .rv-modal-info { flex: 1; min-width: 0; display: grid; gap: 3px; }
    .rv-modal-info strong { font-size: 16px; color: #1a1208; }
    .rv-modal-info > span { font-size: 13px; color: #8f7c62; font-variant-numeric: tabular-nums; }
    .rv-modal-date { display: flex; align-items: center; gap: 6px; }
    .rv-modal-date svg { color: #b8860b; flex-shrink: 0; }
    .rv-modal-status { flex-shrink: 0; align-self: center; margin-left: 2px; padding: 4px 12px; border-radius: 999px; background: #f5ead2; color: #b8860b; font-size: 12px; font-weight: 700; position: relative; }
    .rv-modal-status::before { content: ""; position: absolute; left: -15px; top: -12px; bottom: -12px; border-left: 1px solid #e8dcc4; }
    .rv-modal-acts { display: flex; gap: 12px; }
    .rv-modal-btn { flex: 1; font: inherit; font-size: 14px; font-weight: 600; padding: 13px; border-radius: 8px; cursor: pointer; background: #fffcf5; border: 1px solid #b8860b; color: #b8860b; transition: background .2s; }
    .rv-modal-btn:hover { background: #fbf0d9; }
    .rv-modal-btn-danger { background: #2c1a0e; border-color: #2c1a0e; color: #fff; }
    .rv-modal-btn-danger:hover { background: #4a2f1a; }
    .rv-modal-btn:focus-visible, .rv-modal-close:focus-visible { outline: 2px solid #1a1208; outline-offset: 2px; }
    .rv-modal-btn[disabled] { opacity: .7; cursor: default; }
    @media (max-width: 520px) {
      .rv-br { display: none; }
      .rv-modal-booking { flex-wrap: wrap; }
      .rv-modal-status::before { display: none; }
      .rv-modal-acts { flex-direction: column-reverse; }
    }

    .rv-empty { text-align: center; padding: 48px 16px; color: var(--rv-faint); border: 1px dashed var(--rv-line); border-radius: 12px; display: grid; gap: 14px; justify-items: center; }
    .rv-empty p { margin: 0; font-family: 'Cormorant Garamond', serif; font-size: 24px; }

    @media (max-width: 860px) {
      .rv-ticket { grid-template-columns: 80px minmax(0, 1fr); }
      .rv-ticket .rv-money { grid-column: 1 / -1; border-left: 0; border-top: 1px solid var(--rv-line); }
      .rv-group { grid-template-columns: 80px minmax(0, 1fr); }
      .rv-sub { grid-template-columns: minmax(0, 1fr); }
      .rv-sub .rv-money { border-left: 0; border-top: 1px dashed var(--rv-line); }
      .rv-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    /* Phone layout */
    @media (max-width: 640px) {
      .rv-page { padding-top: 24px; }
      .rv-profile { padding: 18px; gap: 16px; }
      .rv-profile-top { display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 12px 14px; align-items: center; }
      .rv-profile-top .rv-btn { grid-column: 1 / -1; text-align: center; }
      .rv-profile-top .rv-head-actions { grid-column: 1 / -1; }
      .rv-head-actions .rv-btn { flex: 1; }
      .rv-avatar { width: 48px; height: 48px; font-size: 17px; }
      .rv-who p { flex-direction: column; align-items: flex-start; gap: 4px; font-size: 13px; }
      .rv-who p > span[aria-hidden] { display: none; }
      .rv-stats { gap: 10px; }
      .rv-stat { padding: 12px 14px; }
      .rv-stat b { font-size: 28px; }
      .rv-stat-money b { font-size: 24px; }
      .rv-list-head { margin-top: 28px; }
      .rv-list-head h2 { font-size: 30px; }
      .rv-body, .rv-money { padding: 14px 16px; }
      .rv-group-head { padding: 12px 16px; }
    }
    @media (max-width: 480px) {
      .rv-ticket, .rv-group { grid-template-columns: minmax(0, 1fr); }
      .rv-stub { flex-direction: row; gap: 8px; border-right: 0; border-bottom: 2px dashed var(--rv-line); justify-content: flex-start; padding: 10px 16px; }
      .rv-stub .d { font-size: 28px; }
      .rv-who h1 { font-size: 26px; }
    }
  </style>
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<section class="rv-page">
  <div class="rv-wrap">
    @php
      $today = now()->toDateString();

      // Which tab a booking belongs to.
      $bucketOf = function ($b) use ($today) {
          if ($b->isCancelledOrRejected()) return 'cancelled';
          if ($b->status === \App\Models\Booking::STATUS_COMPLETED || $b->event_date->toDateString() < $today) return 'past';
          return $b->status === \App\Models\Booking::STATUS_PENDING ? 'pending' : 'upcoming';
      };

      // Bookings made together are one card; upcoming first, then past.
      $groups = $bookings->groupBy(fn ($b) => $b->booking_group ?: 'd' . $b->event_date->toDateString())
          ->map(fn ($g) => $g->sortBy(fn ($b) => $b->event_date->toDateString() . '|' . $b->package . '|' . $b->room_number)->values())
          ->sortBy(function ($g) use ($today) {
              $date = $g->last()->event_date->toDateString() >= $today ? $g->first()->event_date->toDateString() : $g->last()->event_date->toDateString();
              return $date >= $today ? '0' . $date : '1' . (99999999 - (int) str_replace('-', '', $date));
          });

      $counts = ['upcoming' => 0, 'pending' => 0, 'past' => 0, 'cancelled' => 0];
      foreach ($bookings as $b) { $counts[$bucketOf($b)]++; }

      // From the controller: all-time completed bookings (deleted ones too).
      $completedCount = $completedCount ?? $bookings->where('status', \App\Models\Booking::STATUS_COMPLETED)->count();
      $accountTier = $completedCount >= 2 ? 'Loyal Client' : ($completedCount === 1 ? 'Member' : 'New Member');
      $balance = $bookings->reject(fn ($b) => $b->isCancelledOrRejected())->sum(fn ($b) => max(0, $b->remainingBalance()));
      $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    @endphp

    @if(session('success'))
      <div class="rv-flash rv-flash-ok">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="rv-flash rv-flash-err">@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
    @endif

    {{-- PROFILE HEADER --}}
    <section class="rv-profile" aria-label="Profile">
      <div class="rv-profile-top">
        <div class="rv-avatar" aria-hidden="true">@if($user->avatarUrl())<img src="{{ $user->avatarUrl() }}" alt="" referrerpolicy="no-referrer">@else{{ $initials ?: '?' }}@endif</div>
        <div class="rv-who">
          <h1>{{ $user->name }}</h1>
          <p>
            <span class="rv-email">{{ $user->email }}</span>
            <span aria-hidden="true">·</span>
            <span class="rv-tier">★ {{ $accountTier }}</span>
            <span aria-hidden="true">·</span>
            <span>Member since {{ $user->created_at->format('M d, Y') }}</span>
          </p>
        </div>
        <div class="rv-head-actions">
          <a href="{{ route('account.settings') }}" class="rv-btn rv-btn-outline">Update Profile</a>
          <a href="{{ route('booking') }}" class="rv-btn">+ New Reservation</a>
        </div>
      </div>
      <div class="rv-stats">
        <div class="rv-stat"><b>{{ $counts['upcoming'] }}</b><span>Upcoming</span></div>
        <div class="rv-stat"><b>{{ $counts['pending'] }}</b><span>Pending approval</span></div>
        <div class="rv-stat"><b>{{ $completedCount }}</b><span>Completed</span></div>
        <div class="rv-stat rv-stat-money"><b>₱{{ number_format($balance, 0) }}</b><span>Balance to pay</span></div>
      </div>
    </section>

    {{-- RESERVATIONS --}}
    <div class="rv-list-head">
      <h2>My Reservations</h2>
      <div class="rv-tabs" role="tablist" aria-label="Filter reservations">
        @foreach(['upcoming' => 'Upcoming', 'pending' => 'Pending', 'past' => 'Past', 'cancelled' => 'Cancelled', 'all' => 'All'] as $key => $label)
          <button type="button" class="rv-tab" role="tab" data-tab="{{ $key }}" aria-selected="false">
            {{ $label }}<span class="rv-n">{{ $key === 'all' ? $bookings->count() : $counts[$key] }}</span>
          </button>
        @endforeach
      </div>
    </div>

    <div class="rv-list" id="rvList">
      @forelse($groups as $date => $group)
        @php
          $first = $group->first();
          $buckets = $group->map($bucketOf)->unique()->implode(' ');
          $allDead = $group->every(fn ($b) => $b->isCancelledOrRejected());
        @endphp

        @if($group->count() === 1)
          <article class="rv-ticket {{ $allDead ? 'is-dim' : '' }}" id="booking-{{ $first->id }}" data-buckets="{{ $buckets }}">
            <div class="rv-stub">
              <span class="m">{{ strtoupper($first->event_date->format('M')) }}</span>
              <span class="d">{{ $first->event_date->format('j') }}</span>
              <span class="w">{{ $first->event_date->format('D') }} · {{ $first->event_date->format('Y') }}</span>
            </div>
            @include('partials._reservation-item', ['booking' => $first])
          </article>
        @else
          @php
            $live = $group->reject(fn ($b) => $b->isCancelledOrRejected());
            $groupTotal = $live->sum(fn ($b) => (float) $b->total_amount);
            $groupBalance = $live->sum(fn ($b) => max(0, $b->remainingBalance()));
          @endphp
          <article class="rv-group {{ $allDead ? 'is-dim' : '' }}" data-buckets="{{ $buckets }}">
            <div class="rv-stub">
              <span class="m">{{ strtoupper($first->event_date->format('M')) }}</span>
              <span class="d">{{ $first->event_date->format('j') }}</span>
              <span class="w">{{ $first->event_date->format('D') }} · {{ $first->event_date->format('Y') }}</span>
            </div>
            <div class="rv-group-body">
              @php
                $last = $group->last()->event_date;
                $isRange = !$first->event_date->isSameDay($last);
              @endphp
              <div class="rv-group-head">
                <h3>{{ $isRange ? $first->event_date->format('M d') . ' – ' . $last->format('M d, Y') . ' · ' . $group->count() . ' bookings' : $group->count() . ' packages on this date' }}</h3>
                <div class="rv-sum">Combined <b>₱{{ number_format($groupTotal, 0) }}</b> · balance ₱{{ number_format($groupBalance, 0) }}</div>
              </div>
              @foreach($group as $booking)
                <div class="rv-sub {{ $booking->isCancelledOrRejected() ? 'is-dim' : '' }}" id="booking-{{ $booking->id }}">
                  @include('partials._reservation-item', ['booking' => $booking, 'showDate' => $isRange])
                </div>
              @endforeach
            </div>
          </article>
        @endif
      @empty
        <div class="rv-empty">
          <p>You don't have any reservations yet.</p>
          <a href="{{ route('booking') }}" class="rv-btn">Start Booking Now</a>
        </div>
      @endforelse
      <div class="rv-empty" id="rvTabEmpty" hidden><p>No reservations here.</p></div>
    </div>
  </div>
</section>

{{-- Modals and scripts --}}
<div id="rescheduleModal" class="fixed inset-0 z-[1000] flex items-center justify-center p-4" style="display:none; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);">
    <div style="max-width: 500px; width: 100%; max-height: 90vh; overflow-y: auto; background: #FAF8F3; border: 1px solid #B8860B30; border-radius: 8px; padding: 45px; position: relative; box-shadow: 0 30px 60px rgba(0,0,0,0.25); font-family: 'Jost', sans-serif; margin: auto;">
        
        {{-- Header --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 35px;">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 32px; font-weight: 700; color: #B8860B; margin: 0; line-height: 1.2;">Reschedule Reservation</h3>
            <button type="button" onclick="closeRescheduleModal()" style="border: none; background: transparent; cursor: pointer; color: #B8860B; opacity: 0.6; transition: 0.3s; display: flex;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
        </div>

        <div style="display: flex; flex-direction: column; gap: 30px;">
            {{-- Current info --}}
            <div style="border: 1px solid #B8860B20; padding: 25px; background: rgba(184,134,11,0.02); border-radius: 4px;">
                <p style="font-size: 10px; color: #B8860B; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 15px 0; font-weight: 700;">CURRENT RESERVATION TIMING</p>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 16px; font-weight: 600; color: #1A1208;">Event: <span id="currentEventDate" style="color: #B8860B;">--</span></span>
                    <span style="font-size: 16px; font-weight: 600; color: #1A1208;">Visit: <span id="currentVisitDate" style="color: #B8860B;">--</span></span>
                </div>
            </div>

            {{-- Fee notice --}}
            <div id="feeNotice" style="border-radius: 4px; padding: 18px; font-size: 12px; font-weight: 800; text-align: center; text-transform: uppercase; letter-spacing: 1.5px; border: 1px solid #B8860B30; background: #B8860B10; color: #B8860B;"></div>

            <form method="POST" action="" id="rescheduleForm" onsubmit="return validateRescheduleForm()">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <div>
                        <label style="display: block; font-size: 11px; color: rgba(26,18,8,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 600;">NEW EVENT DATE</label>
                        <input type="date" name="requested_event_date" id="reschedule_event_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            style="width: 100%; height: 54px; border: 1px solid #B8860B30; padding: 0 18px; border-radius: 4px; font-size: 15px; outline: none; background: white; color: #1A1208; transition: 0.3s;" 
                            onfocus="this.style.borderColor='#B8860B'" onblur="this.style.borderColor='#B8860B30'" />
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 11px; color: rgba(26,18,8,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 600;">NEW WALKTHROUGH DATE</label>
                        <input type="date" name="requested_visit_date" id="reschedule_visit_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            style="width: 100%; height: 54px; border: 1px solid #B8860B30; padding: 0 18px; border-radius: 4px; font-size: 15px; outline: none; background: white; color: #1A1208; transition: 0.3s;"
                            onfocus="this.style.borderColor='#B8860B'" onblur="this.style.borderColor='#B8860B30'" />
                        <p style="font-size: 11px; color: rgba(184,134,11,0.6); margin: 10px 0 0 0; font-style: italic; font-weight: 500;">* Note: Walkthrough should be prior to the event date.</p>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; color: rgba(26,18,8,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; font-weight: 600;">RESCHEDULING REASON</label>
                        <textarea name="reschedule_reason" rows="3" placeholder="Explain the reason for this change..."
                            style="width: 100%; border: 1px solid #B8860B30; padding: 15px 18px; border-radius: 4px; font-size: 15px; outline: none; background: white; color: #1A1208; resize: none; transition: 0.3s;"
                            onfocus="this.style.borderColor='#B8860B'" onblur="this.style.borderColor='#B8860B30'"></textarea>
                    </div>
                </div>

                <div id="rescheduleError" style="background: #fffafa; color: #c53030; border: 1px solid #feb2b2; padding: 15px; border-radius: 4px; font-size: 13px; font-weight: 700; margin-top: 25px; display: none; text-align: center;"></div>

                {{-- Footer buttons --}}
                <div style="display: flex; gap: 20px; margin-top: 40px;">
                    <button type="button" onclick="closeRescheduleModal()" 
                        style="flex: 1; padding: 18px; border: 1px solid #B8860B; background: transparent; color: #B8860B; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.2em; cursor: pointer; transition: 0.3s; border-radius: 2px;"
                        onmouseover="this.style.background='#B8860B10'" onmouseout="this.style.background='transparent'">
                        Cancel
                    </button>
                    <button type="submit" 
                        style="flex: 1; padding: 18px; border: none; background: #B8860B; color: white; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.2em; cursor: pointer; transition: 0.3s; border-radius: 3px; box-shadow: 0 4px 15px rgba(184,134,11,0.25);"
                        onmouseover="this.style.background='#C9A84C'" onmouseout="this.style.background='#B8860B'">
                        Review Change
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Cancel confirmation (replaces the browser's confirm() dialog) --}}
<div id="cancelModal" class="rv-modal" role="dialog" aria-modal="true" aria-labelledby="cancelModalTitle" hidden>
  <div class="rv-modal-box">
    <button type="button" class="rv-modal-close" id="cancelModalClose" aria-label="Close">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    <div class="rv-modal-icon" aria-hidden="true">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
    </div>
    <h3 id="cancelModalTitle">Cancel this booking?</h3>
    <p id="cancelModalText">This can't be undone. The date will be released and you'll need<br class="rv-br"> to make a new reservation to book it again.</p>
    <div class="rv-modal-booking">
      <img id="cancelModalImage" src="" alt="" class="rv-modal-thumb">
      <div class="rv-modal-info">
        <strong id="cancelModalLabel"></strong>
        <span id="cancelModalRef"></span>
        <span class="rv-modal-date">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
          <span id="cancelModalDate"></span>
        </span>
      </div>
      <span class="rv-modal-status" id="cancelModalStatus"></span>
    </div>
    <div class="rv-modal-acts">
      <button type="button" class="rv-modal-btn" id="cancelModalKeep">Keep Booking</button>
      <button type="button" class="rv-modal-btn rv-modal-btn-danger" id="cancelModalConfirm">Yes, Cancel Booking</button>
    </div>
  </div>
</div>

@include('partials._footer')

<script>
  function openRescheduleModal(bookingId, eventDate, visitDate, rescheduleCount) {
    document.getElementById('rescheduleForm').action = '/booking/' + bookingId + '/reschedule';
    document.getElementById('currentEventDate').textContent = eventDate;
    document.getElementById('currentVisitDate').textContent = visitDate;
    const eventField = document.getElementById('reschedule_event_date');
    const visitField = document.getElementById('reschedule_visit_date');
    if(eventField) eventField.value = '';
    if(visitField) visitField.value = '';
    document.getElementById('rescheduleError').classList.add('hidden');

    const feeNotice = document.getElementById('feeNotice');
    if (rescheduleCount === 0) {
      feeNotice.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" style="display:inline-block;vertical-align:-2px;margin-right:4px;"><rect x="9" y="9" width="6" height="6" transform="rotate(45 12 12)"/></svg>THIS IS YOUR FIRST RESCHEDULE — FREE OF CHARGE!<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" style="display:inline-block;vertical-align:-2px;margin-left:4px;"><rect x="9" y="9" width="6" height="6" transform="rotate(45 12 12)"/></svg>';
      feeNotice.style.backgroundColor = '#B8860B10';
      feeNotice.style.color = '#B8860B';
      feeNotice.style.border = '1px solid #B8860B30';
    } else {
      feeNotice.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px;margin-right:4px;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>RESCHEDULE FEE: ₱5,000 (ATTEMPT #' + (rescheduleCount + 1) + ')';
      feeNotice.style.backgroundColor = '#1A120805';
      feeNotice.style.color = '#1A1208';
      feeNotice.style.border = '1px solid #1A120810';
    }

    const modal = document.getElementById('rescheduleModal');
    if(modal) modal.style.display = 'flex';
  }

  function closeRescheduleModal() {
    const modal = document.getElementById('rescheduleModal');
    if(modal) modal.style.display = 'none';
  }

  function validateRescheduleForm() {
    const eventDate = document.getElementById('reschedule_event_date').value;
    const visitDate = document.getElementById('reschedule_visit_date').value;
    const errorEl = document.getElementById('rescheduleError');
    const today = new Date().toISOString().split('T')[0];

    if (!eventDate || !visitDate) {
      errorEl.textContent = 'Please select both dates.';
      errorEl.style.display = 'block';
      return false;
    }

    if (eventDate <= today) {
      errorEl.textContent = 'Event date must be a future date.';
      errorEl.style.display = 'block';
      return false;
    }

    if (visitDate < today) {
      errorEl.textContent = 'The Site Visit date cannot be in the past.';
      errorEl.style.display = 'block';
      return false;
    }

    if (visitDate >= eventDate) {
      errorEl.textContent = 'The Site Visit must be scheduled before your event date.';
      errorEl.style.display = 'block';
      return false;
    }

    errorEl.style.display = 'none';
    return true;
  }

  // Dynamically set max visit date when new event date is picked in reschedule modal
  document.getElementById('reschedule_event_date')?.addEventListener('change', function() {
    const eventVal = this.value;
    const visitInput = document.getElementById('reschedule_visit_date');
    if (eventVal) {
      const d = new Date(eventVal);
      d.setDate(d.getDate() - 1);
      visitInput.max = d.toISOString().split('T')[0];
    } else {
      visitInput.removeAttribute('max');
    }
  });

  // Close modal on backdrop click
  window.addEventListener('click', function(e) {
    const modal = document.getElementById('rescheduleModal');
    if (e.target === modal) closeRescheduleModal();
  });

  // Cancel/delete buttons open the confirm modal first.
  (function () {
    const modal = document.getElementById('cancelModal');
    const confirmBtn = document.getElementById('cancelModalConfirm');
    const keepBtn = document.getElementById('cancelModalKeep');
    let pendingForm = null;

    // The same dialog confirms both actions; each has its own wording.
    const kinds = {
      cancel: {
        title: 'Cancel this booking?',
        text: 'This can\'t be undone. The date will be released and you\'ll need to make a new reservation to book it again.',
        confirm: 'Yes, Cancel Booking',
        busy: 'Cancelling...',
      },
      delete: {
        title: 'Delete this booking?',
        text: 'It will be removed from your reservations and can\'t be brought back.',
        confirm: 'Yes, Delete',
        busy: 'Deleting...',
      },
    };

    function close() {
      modal.hidden = true;
      pendingForm = null;
    }

    document.querySelectorAll('.rv-cancel-form, .rv-delete-form').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        pendingForm = form;
        const kind = kinds[form.dataset.kind] || kinds.cancel;
        document.getElementById('cancelModalTitle').textContent = kind.title;
        document.getElementById('cancelModalText').textContent = kind.text;
        confirmBtn.disabled = false;
        confirmBtn.textContent = kind.confirm;
        document.getElementById('cancelModalLabel').textContent = form.dataset.label;
        document.getElementById('cancelModalRef').textContent = form.dataset.ref;
        document.getElementById('cancelModalDate').textContent = form.dataset.date;
        document.getElementById('cancelModalStatus').textContent = form.dataset.status;
        const img = document.getElementById('cancelModalImage');
        img.src = form.dataset.image;
        img.alt = form.dataset.label;
        modal.hidden = false;
        keepBtn.focus();
      });
    });

    confirmBtn.addEventListener('click', function () {
      if (!pendingForm) return;
      confirmBtn.disabled = true;
      confirmBtn.textContent = (kinds[pendingForm.dataset.kind] || kinds.cancel).busy;
      pendingForm.submit(); // form.submit() skips the submit listener above
    });
    keepBtn.addEventListener('click', close);
    document.getElementById('cancelModalClose').addEventListener('click', close);
    modal.addEventListener('click', function (e) { if (e.target === modal) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) close(); });
  })();

  // Reservation tabs: Upcoming / Pending / Past / Cancelled / All.
  (function () {
    const tabs = document.querySelectorAll('.rv-tab');
    const cards = document.querySelectorAll('#rvList [data-buckets]');
    const empty = document.getElementById('rvTabEmpty');
    if (!tabs.length || !cards.length) return;

    function show(key) {
      let visible = 0;
      cards.forEach(function (card) {
        const match = key === 'all' || card.dataset.buckets.split(' ').includes(key);
        card.hidden = !match;
        if (match) visible++;
      });
      tabs.forEach(function (t) { t.setAttribute('aria-selected', String(t.dataset.tab === key)); });
      if (empty) empty.hidden = visible > 0;
    }

    tabs.forEach(function (t) { t.addEventListener('click', function () { show(t.dataset.tab); }); });

    // Start on Upcoming if there is any, otherwise All.
    const hasUpcoming = Array.from(cards).some(function (c) { return c.dataset.buckets.split(' ').includes('upcoming'); });
    show(window.location.hash.startsWith('#booking-') || !hasUpcoming ? 'all' : 'upcoming');
  })();

  // Highlight the booking a notification linked to (e.g. #booking-42)
  if (window.location.hash.startsWith('#booking-')) {
    const target = document.querySelector(window.location.hash);
    if (target) {
      target.scrollIntoView({ block: 'start' });
      target.style.transition = 'box-shadow 0.4s ease';
      target.style.boxShadow = '0 0 0 3px #B8860B';
      setTimeout(() => { target.style.boxShadow = ''; }, 2500);
    }
  }
</script>
</body>
</html>
