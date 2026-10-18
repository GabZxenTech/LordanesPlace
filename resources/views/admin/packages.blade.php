<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Packages Management | Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* Long descriptions collapse to a 3-line preview; "See more…" expands one row. */
    .pkg-desc { font-size: 14px; color: #8a6a40; white-space: pre-wrap; word-break: break-word; line-height: 1.5; }
    .pkg-desc:not(.is-expanded) { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .pkg-desc-toggle { display: none; margin-top: 4px; padding: 0; background: none; border: none; color: #c9a84c; font-size: 12px; font-weight: 700; cursor: pointer; font-family: 'Jost', sans-serif; }
    .pkg-desc-toggle:hover { text-decoration: underline; }
    .pkg-desc-wrap.is-long .pkg-desc-toggle { display: inline-block; }

    /* On narrower screens the form goes above the table */
    @media (max-width: 1599px) {
      .pkg-layout { grid-template-columns: 1fr !important; }
      .pkg-add-form { display: grid; grid-template-columns: repeat(3, 1fr); grid-auto-flow: dense; column-gap: 16px; }
      .pkg-add-form .pkg-span-all { grid-column: 1 / -1; }
      .pkg-add-form .pkg-span-2 { grid-column: span 2; }
    }
    /* Phones: back to one field per row. */
    @media (max-width: 700px) {
      .pkg-add-form { display: block !important; }
    }
    .pkg-actions { white-space: nowrap; }
    @media (max-width: 700px) {
      .pkg-main { padding: 64px 16px 24px !important; }
    }
  </style>
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main class="pkg-main" style="flex: 1; min-width: 0; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">
    <div style="margin-bottom: 32px;">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Packages Management</h1>
      <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Create and manage event packages</p>
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px; display: flex; align-items: center; gap: 8px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg> {{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div style="background: #f8d7da; border: 1px solid #dc3545; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px;">
        @foreach($errors->all() as $err) <div style="font-size: 14px;">{{ $err }}</div> @endforeach
      </div>
    @endif

    <div class="pkg-layout" style="display: grid; grid-template-columns: minmax(280px, 1fr) minmax(0, 2.4fr); gap: 28px;">

      {{-- ADD NEW PACKAGE FORM --}}
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; align-self: start;">
        <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
          <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">ADD NEW PACKAGE</h2>
        </div>
        <div style="padding: 24px;">
          <form method="POST" action="{{ route('admin.packages.store') }}" class="pkg-add-form">
            @csrf
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Package Name</label>
              <input type="text" name="name" required placeholder="e.g. Basic, Standard"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div class="pkg-span-all" style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Description</label>
              <textarea name="description" rows="4" placeholder="e.g. Includes venue, catering, and decoration..."
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box; resize: vertical;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'"></textarea>
            </div>
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Price (₱)</label>
              <input type="number" name="price" required min="0" step="0.01"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Weekend Price (₱) <span style="font-weight: 500; letter-spacing: 0; text-transform: none;">— optional, Sat–Sun</span></label>
              <input type="number" name="weekend_price" min="0" step="0.01" placeholder="Leave blank if same price daily"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Max Guests</label>
              <input type="number" name="max_guests" required min="1"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Duration</label>
              <input type="text" name="duration" placeholder="e.g. 4 hours"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <div>
                <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Start Time</label>
                <input type="time" name="start_time"
                  style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                  onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
              </div>
              <div>
                <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">End Time</label>
                <input type="time" name="end_time"
                  style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                  onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
              </div>
            </div>
            <button type="submit" class="pkg-span-all" style="width: 100%; background: #c9a84c; color: #2c1a0e; border: none; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 14px; letter-spacing: 1px; cursor: pointer; transition: opacity 0.3s; font-family: 'Jost', sans-serif;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">CREATE PACKAGE</button>
          </form>
        </div>
      </div>

      {{-- PACKAGES TABLE with inline editing --}}
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; align-self: start;">
        <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
          <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">ALL PACKAGES</h2>
        </div>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="border-bottom: 1px solid #d4c4a0;">
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">NAME</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">PRICE</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">CAPACITY</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">DURATION</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">SCHEDULE</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">DESCRIPTION</th>
                <th style="padding: 14px 20px; text-align: left; font-size: 11px; letter-spacing: 2px; color: #8a6a40; font-weight: 700;">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($packages as $pkg)
                <tr id="row-{{ $pkg->id }}" style="border-bottom: 1px solid #e8dcc8; transition: background 0.2s;" onmouseover="this.style.background='#f5edd8'" onmouseout="this.style.background='transparent'">

                  {{-- NAME cell --}}
                  <td style="padding: 14px 20px;">
                    <span id="view-name-{{ $pkg->id }}" style="font-size: 15px; color: #2c1a0e; font-weight: 600;">{{ $pkg->name }}</span>
                    <input id="edit-name-{{ $pkg->id }}" type="text" value="{{ $pkg->name }}"
                      style="display:none; width: 100%; background: #f5f0e8; border: 1px solid #c9a84c; color: #2c1a0e; padding: 6px 10px; border-radius: 5px; font-size: 14px; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
                  </td>

                  {{-- PRICE cell --}}
                  <td style="padding: 14px 20px;">
                    <span id="view-price-{{ $pkg->id }}" style="font-size: 15px; color: #8a6a40; white-space: nowrap;">₱{{ number_format($pkg->price, 2) }}@if($pkg->weekend_price !== null)<br><span style="font-size: 12px; color: #a08a6a;">Weekend: ₱{{ number_format($pkg->weekend_price, 2) }}</span>@endif</span>
                    <input id="edit-price-{{ $pkg->id }}" type="number" value="{{ $pkg->price }}" min="0" step="0.01" title="Price"
                      style="display:none; width: 100%; background: #f5f0e8; border: 1px solid #c9a84c; color: #2c1a0e; padding: 6px 10px; border-radius: 5px; font-size: 14px; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
                    <input id="edit-weekend_price-{{ $pkg->id }}" type="number" value="{{ $pkg->weekend_price }}" min="0" step="0.01" placeholder="Weekend (optional)" title="Weekend price (Sat–Sun) — leave blank for none"
                      style="display:none; width: 100%; margin-top: 6px; background: #f5f0e8; border: 1px solid #c9a84c; color: #2c1a0e; padding: 6px 10px; border-radius: 5px; font-size: 13px; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
                  </td>

                  {{-- CAPACITY cell --}}
                  <td style="padding: 14px 20px;">
                    <span id="view-guests-{{ $pkg->id }}" style="font-size: 15px; color: #8a6a40;">{{ $pkg->max_guests }}</span>
                    <input id="edit-guests-{{ $pkg->id }}" type="number" value="{{ $pkg->max_guests }}" min="1"
                      style="display:none; width: 100%; background: #f5f0e8; border: 1px solid #c9a84c; color: #2c1a0e; padding: 6px 10px; border-radius: 5px; font-size: 14px; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
                  </td>

                  {{-- DURATION cell --}}
                  <td style="padding: 14px 20px;">
                    <span id="view-duration-{{ $pkg->id }}" style="font-size: 15px; color: #8a6a40;">{{ $pkg->duration ?? 'N/A' }}</span>
                    <input id="edit-duration-{{ $pkg->id }}" type="text" value="{{ $pkg->duration }}"
                      style="display:none; width: 100%; background: #f5f0e8; border: 1px solid #c9a84c; color: #2c1a0e; padding: 6px 10px; border-radius: 5px; font-size: 14px; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
                  </td>

                  {{-- SCHEDULE cell --}}
                  <td style="padding: 14px 20px;">
                    <span id="view-schedule-{{ $pkg->id }}" style="font-size: 15px; color: #8a6a40;">
                      @if($pkg->start_time && $pkg->end_time)
                        {{ \Carbon\Carbon::parse($pkg->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($pkg->end_time)->format('h:i A') }}
                      @else
                        <span style="color: #e74c3c;">Not set</span>
                      @endif
                    </span>
                    <div id="edit-schedule-{{ $pkg->id }}" style="display:none; grid-template-columns: 1fr 1fr; gap: 6px;">
                      <input id="edit-start_time-{{ $pkg->id }}" type="time" value="{{ $pkg->start_time ? \Carbon\Carbon::parse($pkg->start_time)->format('H:i') : '' }}"
                        style="width: 100%; background: #f5f0e8; border: 1px solid #c9a84c; color: #2c1a0e; padding: 6px 8px; border-radius: 5px; font-size: 13px; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
                      <input id="edit-end_time-{{ $pkg->id }}" type="time" value="{{ $pkg->end_time ? \Carbon\Carbon::parse($pkg->end_time)->format('H:i') : '' }}"
                        style="width: 100%; background: #f5f0e8; border: 1px solid #c9a84c; color: #2c1a0e; padding: 6px 8px; border-radius: 5px; font-size: 13px; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
                    </div>
                  </td>

                  {{-- DESCRIPTION cell --}}
                  <td style="padding: 14px 20px; min-width: 240px; max-width: 360px;">
                    <div id="view-description-{{ $pkg->id }}" class="pkg-desc-wrap">
                      <div class="pkg-desc">{{ $pkg->description ?? '—' }}</div>
                      <button type="button" class="pkg-desc-toggle" onclick="togglePkgDescription(this)" aria-expanded="false">See more…</button>
                    </div>
                    <textarea id="edit-description-{{ $pkg->id }}" rows="3"
                      style="display:none; width: 100%; background: #f5f0e8; border: 1px solid #c9a84c; color: #2c1a0e; padding: 6px 10px; border-radius: 5px; font-size: 13px; font-family: 'Jost', sans-serif; box-sizing: border-box; resize: vertical;">{{ $pkg->description }}</textarea>
                  </td>

                  {{-- ACTIONS cell --}}
                  <td style="padding: 14px 20px;">
                    <div class="pkg-actions" style="display: flex; gap: 8px; align-items: center;">

                      {{-- Edit button --}}
                      <button type="button"
                        id="edit-btn-{{ $pkg->id }}"
                        onclick="enableRowEdit({{ $pkg->id }})"
                        style="background: transparent; border: 1px solid #c9a84c; color: #c9a84c; padding: 7px 15px; border-radius: 5px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: 'Jost', sans-serif; transition: all 0.2s;"
                        onmouseover="this.style.background='#c9a84c'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#c9a84c';">
                        Edit
                      </button>

                      {{-- Update button --}}
                      <button type="button"
                        id="update-btn-{{ $pkg->id }}"
                        onclick="submitRowEdit({{ $pkg->id }})"
                        style="display:none; background: #c9a84c; border: 1px solid #c9a84c; color: #2c1a0e; padding: 7px 15px; border-radius: 5px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: 'Jost', sans-serif; transition: all 0.2s;"
                        onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        Update
                      </button>

                      {{-- Cancel button --}}
                      <button type="button"
                        id="cancel-btn-{{ $pkg->id }}"
                        onclick="cancelRowEdit({{ $pkg->id }})"
                        style="display:none; background: #fff; border: 1px solid #d4c4a0; color: #8a6a40; padding: 7px 15px; border-radius: 5px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: 'Jost', sans-serif; transition: all 0.2s;"
                        onmouseover="this.style.background='#f5f0e8'" onmouseout="this.style.background='#fff'">
                        Cancel
                      </button>

                      {{-- Delete form --}}
                      <form method="POST" action="{{ route('admin.packages.destroy', $pkg->id) }}" data-confirm="Delete this package?" style="margin: 0;">
                        @csrf @method('DELETE')
                        <button type="submit"
                          id="delete-btn-{{ $pkg->id }}"
                          style="background: #fff; border: 1px solid #e74c3c; color: #e74c3c; padding: 7px 15px; border-radius: 5px; font-size: 12px; font-weight: 700; cursor: pointer; font-family: 'Jost', sans-serif; transition: all 0.2s;"
                          onmouseover="this.style.background='#e74c3c'; this.style.color='white';" onmouseout="this.style.background='#fff'; this.style.color='#e74c3c';">
                          Delete
                        </button>
                      </form>

                      {{-- Hidden update form --}}
                      <form id="update-form-{{ $pkg->id }}" method="POST" action="{{ url('admin/packages/' . $pkg->id) }}" style="display:none;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="form-name-{{ $pkg->id }}" name="name" />
                        <input type="hidden" id="form-price-{{ $pkg->id }}" name="price" />
                        <input type="hidden" id="form-weekend_price-{{ $pkg->id }}" name="weekend_price" />
                        <input type="hidden" id="form-guests-{{ $pkg->id }}" name="max_guests" />
                        <input type="hidden" id="form-duration-{{ $pkg->id }}" name="duration" />
                        <input type="hidden" id="form-start_time-{{ $pkg->id }}" name="start_time" />
                        <input type="hidden" id="form-end_time-{{ $pkg->id }}" name="end_time" />
                        <input type="hidden" id="form-description-{{ $pkg->id }}" name="description" />
                      </form>

                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" style="text-align: center; padding: 50px; color: #8a6a40; font-size: 15px;">No packages found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>

  <script>
    // ── Description preview: "See more…" / "See less" per package ──
    function togglePkgDescription(btn) {
      const desc = btn.parentElement.querySelector('.pkg-desc');
      const expanded = desc.classList.toggle('is-expanded');
      btn.textContent = expanded ? 'See less' : 'See more…';
      btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    }

    // Show "See more" only when the text is cut off (re-checked on resize).
    function markLongPkgDescriptions() {
      document.querySelectorAll('.pkg-desc-wrap').forEach(function (wrap) {
        const desc = wrap.querySelector('.pkg-desc');
        if (desc.classList.contains('is-expanded')) return;
        wrap.classList.toggle('is-long', desc.scrollHeight > desc.clientHeight + 1);
      });
    }

    document.addEventListener('DOMContentLoaded', function () {
      markLongPkgDescriptions();
      const table = document.querySelector('.pkg-layout');
      if (table && 'ResizeObserver' in window) {
        new ResizeObserver(markLongPkgDescriptions).observe(table);
      } else {
        window.addEventListener('resize', markLongPkgDescriptions);
      }
    });

    // Store original values so Cancel can restore them
    const originalValues = {};

    function enableRowEdit(id) {
      // Save originals
      originalValues[id] = {
        name:        document.getElementById('edit-name-' + id).value,
        price:       document.getElementById('edit-price-' + id).value,
        weekend_price: document.getElementById('edit-weekend_price-' + id).value,
        guests:      document.getElementById('edit-guests-' + id).value,
        duration:    document.getElementById('edit-duration-' + id).value,
        start_time:  document.getElementById('edit-start_time-' + id).value,
        end_time:    document.getElementById('edit-end_time-' + id).value,
        description: document.getElementById('edit-description-' + id).value,
      };

      // Hide text spans, show inputs
      ['name','price','guests','duration'].forEach(field => {
        document.getElementById('view-' + field + '-' + id).style.display = 'none';
        document.getElementById('edit-' + field + '-' + id).style.display = 'block';
      });
      document.getElementById('edit-weekend_price-' + id).style.display = 'block';
      document.getElementById('view-schedule-' + id).style.display = 'none';
      document.getElementById('edit-schedule-' + id).style.display = 'grid';
      document.getElementById('view-description-' + id).style.display = 'none';
      document.getElementById('edit-description-' + id).style.display = 'block';

      // Swap buttons
      document.getElementById('edit-btn-' + id).style.display   = 'none';
      document.getElementById('update-btn-' + id).style.display = 'inline-block';
      document.getElementById('cancel-btn-' + id).style.display = 'inline-block';
      document.getElementById('delete-btn-' + id).style.display = 'none';
    }

    function cancelRowEdit(id) {
      // Restore original values into inputs
      if (originalValues[id]) {
        document.getElementById('edit-name-' + id).value        = originalValues[id].name;
        document.getElementById('edit-price-' + id).value       = originalValues[id].price;
        document.getElementById('edit-weekend_price-' + id).value = originalValues[id].weekend_price;
        document.getElementById('edit-guests-' + id).value      = originalValues[id].guests;
        document.getElementById('edit-duration-' + id).value    = originalValues[id].duration;
        document.getElementById('edit-start_time-' + id).value  = originalValues[id].start_time;
        document.getElementById('edit-end_time-' + id).value    = originalValues[id].end_time;
        document.getElementById('edit-description-' + id).value = originalValues[id].description;
      }

      // Show text spans, hide inputs
      ['name','price','guests','duration'].forEach(field => {
        document.getElementById('view-' + field + '-' + id).style.display = '';
        document.getElementById('edit-' + field + '-' + id).style.display = 'none';
      });
      document.getElementById('edit-weekend_price-' + id).style.display = 'none';
      document.getElementById('view-schedule-' + id).style.display = '';
      document.getElementById('edit-schedule-' + id).style.display = 'none';
      document.getElementById('view-description-' + id).style.display = '';
      document.getElementById('edit-description-' + id).style.display = 'none';

      // Swap buttons back
      document.getElementById('edit-btn-' + id).style.display   = 'inline-block';
      document.getElementById('update-btn-' + id).style.display = 'none';
      document.getElementById('cancel-btn-' + id).style.display = 'none';
      document.getElementById('delete-btn-' + id).style.display = 'inline-block';
    }

    function submitRowEdit(id) {
      // Copy input values into the hidden form fields
      document.getElementById('form-name-' + id).value        = document.getElementById('edit-name-' + id).value;
      document.getElementById('form-price-' + id).value       = document.getElementById('edit-price-' + id).value;
      document.getElementById('form-weekend_price-' + id).value = document.getElementById('edit-weekend_price-' + id).value;
      document.getElementById('form-guests-' + id).value      = document.getElementById('edit-guests-' + id).value;
      document.getElementById('form-duration-' + id).value    = document.getElementById('edit-duration-' + id).value;
      document.getElementById('form-start_time-' + id).value  = document.getElementById('edit-start_time-' + id).value;
      document.getElementById('form-end_time-' + id).value    = document.getElementById('edit-end_time-' + id).value;
      document.getElementById('form-description-' + id).value = document.getElementById('edit-description-' + id).value;

      // Submit
      document.getElementById('update-form-' + id).submit();
    }
  </script>
</body>
</html>