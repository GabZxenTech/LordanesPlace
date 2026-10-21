<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FAQs | Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* Admin FAQ page */
    .fq-card { background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; margin-bottom: 24px; }
    .fq-head { padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8; display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
    .fq-head h2 { font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0; }
    .fq-form { padding: 20px 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .fq-form .full { grid-column: 1 / -1; }
    .fq-form label { display: block; font-size: 11px; letter-spacing: 1.5px; color: #8a6a40; margin-bottom: 6px; font-weight: 700; text-transform: uppercase; }
    .fq-input { width: 100%; box-sizing: border-box; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 11px 13px; border-radius: 6px; font: 14px 'Jost', sans-serif; outline: none; }
    .fq-input:focus { border-color: #c9a84c; }
    textarea.fq-input { min-height: 110px; resize: vertical; line-height: 1.5; }
    .fq-hint { font-size: 11px; color: #8a6a40; margin: 5px 0 0; }
    .fq-btn { background: #2c1a0e; color: #c9a84c; border: none; border-radius: 6px; padding: 11px 20px; font: 700 13px 'Jost', sans-serif; letter-spacing: 1px; cursor: pointer; }
    .fq-btn-ghost { background: transparent; color: #8a6a40; border: 1px solid #d4c4a0; }
    .fq-btn-danger { background: transparent; color: #c0392b; border: 1px solid #e5b3ad; }
    .fq-item { border-bottom: 1px solid #e8dcc8; padding: 16px 24px; }
    .fq-item:last-child { border-bottom: none; }
    .fq-row { display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; }
    .fq-q { font-size: 15px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px; }
    .fq-a { font-size: 13px; color: #6b5233; margin: 0; white-space: pre-line; line-height: 1.5; }
    .fq-meta { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px; }
    .fq-tag { font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 999px; background: #f5edd8; color: #8a6a40; }
    .fq-tag.chat { background: #2c1a0e; color: #c9a84c; }
    .fq-tag.hidden-tag { background: #eee; color: #888; }
    .fq-actions { display: flex; gap: 6px; flex-shrink: 0; }
    .fq-actions .fq-btn { padding: 7px 14px; font-size: 12px; }
    .fq-edit { display: none; margin-top: 14px; background: #fffdf8; border: 1px solid #e8dcc8; border-radius: 8px; }
    .fq-item.is-editing .fq-edit { display: grid; }
    .fq-check { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #2c1a0e; }
    @media (max-width: 767px) {
      .fq-form { grid-template-columns: 1fr; }
      .fq-row { flex-direction: column; }
    }
  </style>
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main style="flex: 1; min-width: 0; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">
    <div style="margin-bottom: 28px; display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap;">
      <div>
        <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">FAQs</h1>
        <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Questions on the FAQ page and chat</p>
      </div>
      <a href="{{ route('faq') }}" target="_blank" rel="noopener" style="font-size: 13px; color: #8a6a40; font-weight: 600;">View FAQ page ↗</a>
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div style="background: #fbf0d9; border: 1px solid #ecd9a8; color: #7a5a12; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
    @endif

    {{-- Add FAQ --}}
    <section class="fq-card">
      <div class="fq-head"><h2>Add FAQ</h2></div>
      <form method="POST" action="{{ route('admin.faqs.store') }}" class="fq-form">
        @csrf
        @include('admin._faq-fields', ['faq' => null])
        <div class="full"><button type="submit" class="fq-btn">Add FAQ</button></div>
      </form>
    </section>

    {{-- FAQ list --}}
    <section class="fq-card">
      <div class="fq-head"><h2>All FAQs ({{ $faqs->count() }})</h2></div>
      @forelse($faqs as $faq)
        <div class="fq-item" id="fq-{{ $faq->id }}">
          <div class="fq-row">
            <div style="min-width: 0;">
              <p class="fq-q">{{ $faq->question }}</p>
              <p class="fq-a">{{ \Illuminate\Support\Str::limit($faq->answer, 220) }}</p>
              <div class="fq-meta">
                <span class="fq-tag">{{ $faq->category }}</span>
                <span class="fq-tag">Order {{ $faq->sort_order }}</span>
                @if($faq->chat_label)<span class="fq-tag chat">Chat button: {{ $faq->chat_label }}</span>@endif
                @unless($faq->is_published)<span class="fq-tag hidden-tag">Hidden</span>@endunless
              </div>
            </div>
            <div class="fq-actions">
              <button type="button" class="fq-btn fq-btn-ghost" onclick="document.getElementById('fq-{{ $faq->id }}').classList.toggle('is-editing')">Edit</button>
              <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" data-confirm="Delete this FAQ?">
                @csrf @method('DELETE')
                <button type="submit" class="fq-btn fq-btn-danger">Delete</button>
              </form>
            </div>
          </div>
          <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="fq-form fq-edit">
            @csrf @method('PUT')
            @include('admin._faq-fields', ['faq' => $faq])
            <div class="full" style="display: flex; gap: 8px;">
              <button type="submit" class="fq-btn">Save</button>
              <button type="button" class="fq-btn fq-btn-ghost" onclick="document.getElementById('fq-{{ $faq->id }}').classList.remove('is-editing')">Cancel</button>
            </div>
          </form>
        </div>
      @empty
        <p style="padding: 30px; text-align: center; color: #8a6a40; margin: 0;">No FAQs yet.</p>
      @endforelse
    </section>
  </main>
</body>
</html>
