<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>360° Tour | Admin</title>
  @include('partials._favicon')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .tour-layout { display: grid; grid-template-columns: minmax(280px, 1fr) minmax(0, 2.2fr); gap: 28px; }
    @media (max-width: 1100px) { .tour-layout { grid-template-columns: 1fr; } }
    @media (max-width: 700px) { .tour-main { padding: 64px 16px 24px !important; } }
    .tour-input { width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box; }
    .tour-input:focus { border-color: #c9a84c; }
    .tour-label { display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase; }
    .tour-btn { border: 1px solid #d4c4a0; background: transparent; color: #8a6a40; padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; font-family: 'Jost', sans-serif; display: inline-block; }
    .tour-btn:hover { background: #f5edd8; }
    .tour-btn.gold { background: #c9a84c; border-color: #c9a84c; color: #2c1a0e; }
    .tour-btn.gold:hover { background: #b8860b; color: #fff; }
    .tour-btn.red { color: #c0392b; border-color: rgba(192,57,43,0.35); }
    .tour-btn.red:hover { background: #c0392b; color: #fff; }
  </style>
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main class="tour-main" style="flex: 1; min-width: 0; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">
    <div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 12px;">
      <div>
        <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">360° Tour</h1>
        <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Manage the pictures and arrows of the virtual tour</p>
      </div>
      <a href="{{ route('tour') }}" target="_blank" class="tour-btn">View public tour ↗</a>
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div style="background: #f8d7da; border: 1px solid #dc3545; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px;">
        @foreach($errors->all() as $err) <div style="font-size: 14px;">{{ $err }}</div> @endforeach
      </div>
    @endif

    <div class="tour-layout">

      {{-- ADD SCENE --}}
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; align-self: start;">
        <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
          <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">ADD NEW SCENE</h2>
        </div>
        <form method="POST" action="{{ route('admin.tour.store') }}" enctype="multipart/form-data" style="padding: 24px;">
          @csrf
          <div style="margin-bottom: 16px;">
            <label class="tour-label">Scene name</label>
            <input type="text" name="name" class="tour-input" maxlength="120" required placeholder="e.g. Garden Area" value="{{ old('name') }}">
          </div>
          <div style="margin-bottom: 16px;">
            <label class="tour-label">360 picture (JPG)</label>
            <input type="file" name="image" class="tour-input" accept="image/jpeg" required>
            <p style="font-size: 11px; color: #8a6a40; margin: 6px 0 0;">A 2:1 panorama from a 360 camera (for example 4096 × 2048 px), up to 25 MB.</p>
          </div>
          <button type="submit" class="tour-btn gold" style="width: 100%; padding: 12px;">Upload scene</button>
        </form>
      </div>

      {{-- SCENES --}}
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; align-self: start;">
        <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
          <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">ALL SCENES ({{ $scenes->count() }})</h2>
        </div>
        @forelse($scenes as $scene)
          <div style="display: flex; align-items: center; gap: 14px; padding: 14px 24px; border-bottom: 1px solid #e8dcc8; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 180px;">
              <div style="font-size: 15px; font-weight: 700; color: #2c1a0e;">{{ $scene->name }}</div>
              <div style="font-size: 12px; color: #8a6a40; margin-top: 2px;">
                {{ $scene->hotspots_count }} {{ $scene->hotspots_count === 1 ? 'arrow' : 'arrows' }}
                · {{ $scene->isTiled() ? 'Marzipano tiles' : 'Uploaded picture' }}
              </div>
            </div>
            <a href="{{ route('admin.tour.show', $scene) }}" class="tour-btn gold">Edit arrows</a>
            <form method="POST" action="{{ route('admin.tour.destroy', $scene) }}" data-confirm="Delete the scene &quot;{{ $scene->name }}&quot; and its arrows? Arrows from other scenes that point to it are removed too.">
              @csrf @method('DELETE')
              <button type="submit" class="tour-btn red">Delete</button>
            </form>
          </div>
        @empty
          <div style="padding: 40px 24px; text-align: center; color: #8a6a40; font-size: 14px;">
            No scenes yet. Upload the first 360 picture, or run <code>php artisan tour:import</code> to load the current tour.
          </div>
        @endforelse
      </div>
    </div>
  </main>
</body>
</html>
