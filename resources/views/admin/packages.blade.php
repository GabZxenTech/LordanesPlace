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

      {{-- CREATE/EDIT FORM --}}
      <div style="background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; align-self: start;" id="form-card">
        <div style="padding: 18px 24px; border-bottom: 1px solid #d4c4a0; background: #f5edd8;">
          <h2 style="font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0;" id="form-title">ADD NEW PACKAGE</h2>
        </div>
        <div style="padding: 24px;">
          <form method="POST" action="{{ route('admin.packages.store') }}" id="package-form">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Package Name</label>
              <input type="text" name="name" id="pkg-name" required placeholder="e.g. Basic, Standard"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Price (₱)</label>
              <input type="number" name="price" id="pkg-price" required min="0" step="0.01"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Max Guests</label>
              <input type="number" name="max_guests" id="pkg-guests" required min="1"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 16px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Duration</label>
              <input type="text" name="duration" id="pkg-duration" placeholder="e.g. 4 hours"
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'" />
            </div>
            <div style="margin-bottom: 20px;">
              <label style="display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Description</label>
              <textarea name="description" id="pkg-desc" rows="3" placeholder="Brief details..."
                style="width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 12px 14px; border-radius: 6px; font-size: 14px; outline: none; transition: border 0.3s; font-family: 'Jost', sans-serif; resize: vertical; box-sizing: border-box;"
                onfocus="this.style.borderColor='#c9a84c'" onblur="this.style.borderColor='#d4c4a0'"></textarea>
            </div>
            <button type="submit" style="width: 100%; background: #c9a84c; color: #2c1a0e; border: none; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 14px; letter-spacing: 1px; cursor: pointer; transition: opacity 0.3s; font-family: 'Jost', sans-serif;" id="submit-btn" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">CREATE PACKAGE</button>
            <button type="button" id="cancel-edit-btn" onclick="cancelEdit()" style="width: 100%; border: 1px solid #c9a84c; background: transparent; color: #c9a84c; padding: 14px; border-radius: 6px; font-weight: 700; font-size: 14px; letter-spacing: 1px; cursor: pointer; margin-top: 10px; display: none; font-family: 'Jost', sans-serif;">CANCEL EDIT</button>
          </form>
        </div>
      </div>

      {{-- PACKAGES TABLE --}}
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
                <tr style="border-bottom: 1px solid #e8dcc8; transition: background 0.2s;" onmouseover="this.style.background='#f5edd8'" onmouseout="this.style.background='transparent'">
                  <td style="padding: 14px 20px; font-size: 15px; color: #2c1a0e; font-weight: 600;">{{ $pkg->name }}</td>
                  <td style="padding: 14px 20px; font-size: 15px; color: #8a6a40;">₱{{ number_format($pkg->price, 2) }}</td>
                  <td style="padding: 14px 20px; font-size: 15px; color: #8a6a40;">{{ $pkg->max_guests }}</td>
                  <td style="padding: 14px 20px; font-size: 15px; color: #8a6a40;">{{ $pkg->duration ?? 'N/A' }}</td>
                  <td style="padding: 14px 20px;">
                    <div style="display: flex; gap: 6px;">
                      <button type="button" 
                        class="edit-pkg-btn"
                        style="background: transparent; border: 1px solid #c9a84c; color: #c9a84c; padding: 6px 14px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s; font-family: 'Jost', sans-serif;" 
                        data-id="{{ $pkg->id }}"
                        data-name="{{ $pkg->name }}"
                        data-price="{{ $pkg->price }}"
                        data-guests="{{ $pkg->max_guests }}"
                        data-duration="{{ $pkg->duration }}"
                        data-desc="{{ $pkg->description }}"
                        onmouseover="this.style.background='#c9a84c'; this.style.color='#fff9ef';" onmouseout="this.style.background='transparent'; this.style.color='#c9a84c';">
                        Edit
                      </button>
                      <form method="POST" action="{{ route('admin.packages.destroy', $pkg->id) }}" onsubmit="return confirm('Delete this package?');">
                        @csrf @method('DELETE')
                        <button type="submit" style="background: transparent; border: 1px solid #e74c3c; color: #e74c3c; padding: 6px 14px; border-radius: 4px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s; font-family: 'Jost', sans-serif;" onmouseover="this.style.background='#e74c3c'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='#e74c3c';">Delete</button>
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

  <div id="pkg-data" class="hidden" 
       data-base-url="{{ url('admin/packages') }}"
       data-store-url="{{ route('admin.packages.store') }}">
  </div>

  <script>
    const pkgData = document.getElementById('pkg-data');
    const pkgBaseUrl = pkgData.getAttribute('data-base-url');
    const pkgStoreUrl = pkgData.getAttribute('data-store-url');

    document.querySelectorAll('.edit-pkg-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const price = this.getAttribute('data-price');
        const maxGuests = this.getAttribute('data-guests');
        const duration = this.getAttribute('data-duration');
        const desc = this.getAttribute('data-desc');
        editPackage(id, name, price, maxGuests, duration, desc);
      });
    });

    function editPackage(id, name, price, maxGuests, duration, desc) {
      document.getElementById('form-title').innerText = 'EDIT PACKAGE';
      document.getElementById('submit-btn').innerText = 'UPDATE PACKAGE';
      const form = document.getElementById('package-form');
      form.action = pkgBaseUrl + '/' + id;
      document.getElementById('form-method').value = 'PUT';
      document.getElementById('pkg-name').value = name;
      document.getElementById('pkg-price').value = price;
      document.getElementById('pkg-guests').value = maxGuests;
      document.getElementById('pkg-duration').value = duration;
      document.getElementById('pkg-desc').value = desc;
      document.getElementById('cancel-edit-btn').style.display = 'block';
      window.scrollTo(0, 0);
    }

    function cancelEdit() {
      document.getElementById('form-title').innerText = 'ADD NEW PACKAGE';
      document.getElementById('submit-btn').innerText = 'CREATE PACKAGE';
      const form = document.getElementById('package-form');
      form.action = pkgStoreUrl;
      document.getElementById('form-method').value = 'POST';
      form.reset();
      document.getElementById('cancel-edit-btn').style.display = 'none';
    }
  </script>
</body>
</html>
