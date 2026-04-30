<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Packages Management | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main style="flex: 1; padding: 40px 48px; min-height: 100vh; overflow-y: auto;">
    <div style="margin-bottom: 32px;">
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 38px; font-weight: 700; color: #2c1a0e; margin: 0 0 4px;">Packages Management</h1>
      <p style="font-size: 12px; letter-spacing: 3px; color: #8a6a40; text-transform: uppercase; font-weight: 600; margin: 0;">Create and manage event packages</p>
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 15px;">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div style="background: #f8d7da; border: 1px solid #dc3545; color: #721c24; padding: 14px 20px; border-radius: 6px; margin-bottom: 20px;">
        @foreach($errors->all() as $err) <div style="font-size: 14px;">{{ $err }}</div> @endforeach
      </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 28px;">

      {{-- ADD NEW PACKAGE FORM (description removed) --}}
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; align-self: start;">
        <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
          <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;">ADD NEW PACKAGE</h2>
        </div>
        <div style="padding: 24px;">
          <form method="POST" action="{{ route('admin.packages.store') }}">
            @csrf
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Package Name</label>
              <input type="text" name="name" required placeholder="e.g. Basic, Standard"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Price (₱)</label>
              <input type="number" name="price" required min="0" step="0.01"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Max Guests</label>
              <input type="number" name="max_guests" required min="1"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 20px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Duration</label>
              <input type="text" name="duration" placeholder="e.g. 4 hours"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <button type="submit" style="width: 100%; background: #c9a84c; color: #2c1a0e; border: none; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 14px; letter-spacing: 1px; cursor: pointer; transition: opacity 0.3s; font-family: 'Jost', sans-serif;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">CREATE PACKAGE</button>
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
                    <span id="view-price-{{ $pkg->id }}" style="font-size: 15px; color: #8a6a40;">₱{{ number_format($pkg->price, 2) }}</span>
                    <input id="edit-price-{{ $pkg->id }}" type="number" value="{{ $pkg->price }}" min="0" step="0.01"
                      style="display:none; width: 100%; background: #f5f0e8; border: 1px solid #c9a84c; color: #2c1a0e; padding: 6px 10px; border-radius: 5px; font-size: 14px; font-family: 'Jost', sans-serif; box-sizing: border-box;" />
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

                  {{-- ACTIONS cell --}}
                  <td style="padding: 14px 20px;">
                    <div style="display: flex; gap: 8px; align-items: center;">

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
                      <form method="POST" action="{{ route('admin.packages.destroy', $pkg->id) }}" onsubmit="return confirm('Delete this package?');" style="margin: 0;">
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
                        <input type="hidden" id="form-guests-{{ $pkg->id }}" name="max_guests" />
                        <input type="hidden" id="form-duration-{{ $pkg->id }}" name="duration" />
                      </form>

                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" style="text-align: center; padding: 50px; color: #8a6a40; font-size: 15px;">No packages found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>

  <script>
    // Store original values so Cancel can restore them
    const originalValues = {};

    function enableRowEdit(id) {
      // Save originals
      originalValues[id] = {
        name:     document.getElementById('edit-name-' + id).value,
        price:    document.getElementById('edit-price-' + id).value,
        guests:   document.getElementById('edit-guests-' + id).value,
        duration: document.getElementById('edit-duration-' + id).value,
      };

      // Hide text spans, show inputs
      ['name','price','guests','duration'].forEach(field => {
        document.getElementById('view-' + field + '-' + id).style.display = 'none';
        document.getElementById('edit-' + field + '-' + id).style.display = 'block';
      });

      // Swap buttons
      document.getElementById('edit-btn-' + id).style.display   = 'none';
      document.getElementById('update-btn-' + id).style.display = 'inline-block';
      document.getElementById('cancel-btn-' + id).style.display = 'inline-block';
      document.getElementById('delete-btn-' + id).style.display = 'none';
    }

    function cancelRowEdit(id) {
      // Restore original values into inputs
      if (originalValues[id]) {
        document.getElementById('edit-name-' + id).value     = originalValues[id].name;
        document.getElementById('edit-price-' + id).value    = originalValues[id].price;
        document.getElementById('edit-guests-' + id).value   = originalValues[id].guests;
        document.getElementById('edit-duration-' + id).value = originalValues[id].duration;
      }

      // Show text spans, hide inputs
      ['name','price','guests','duration'].forEach(field => {
        document.getElementById('view-' + field + '-' + id).style.display = '';
        document.getElementById('edit-' + field + '-' + id).style.display = 'none';
      });

      // Swap buttons back
      document.getElementById('edit-btn-' + id).style.display   = 'inline-block';
      document.getElementById('update-btn-' + id).style.display = 'none';
      document.getElementById('cancel-btn-' + id).style.display = 'none';
      document.getElementById('delete-btn-' + id).style.display = 'inline-block';
    }

    function submitRowEdit(id) {
      // Copy input values into the hidden form fields
      document.getElementById('form-name-' + id).value     = document.getElementById('edit-name-' + id).value;
      document.getElementById('form-price-' + id).value    = document.getElementById('edit-price-' + id).value;
      document.getElementById('form-guests-' + id).value   = document.getElementById('edit-guests-' + id).value;
      document.getElementById('form-duration-' + id).value = document.getElementById('edit-duration-' + id).value;

      // Submit
      document.getElementById('update-form-' + id).submit();
    }
  </script>
</body>
</html>