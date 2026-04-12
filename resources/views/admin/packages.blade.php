<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Packages Management | Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page bg-admin-dark text-admin-cream font-body min-h-screen">

  @include('partials._admin-header')

  <div class="p-5 md:p-10">
    <h2 class="font-heading text-[24px] md:text-[28px] text-admin-gold mb-6 md:mb-8">Packages Management</h2>

    @if(session('success'))
      <div class="bg-green-400/15 border border-green-400 text-green-400 px-5 py-3 rounded-md mb-5 text-[16px]">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div class="bg-red-500/15 border border-red-500 text-red-400 p-3 rounded-md mb-5">
        @foreach($errors->all() as $err) <div class="text-[15px]">{{ $err }}</div> @endforeach
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_2fr] gap-6 md:gap-8">

      <!-- CREATE PACKAGE FORM -->
      <div>
        <div class="bg-admin-card border border-admin-gold/20 rounded-lg overflow-hidden" id="form-card">
          <div class="p-4 md:p-5 border-b border-admin-gold/20 bg-admin-secondary">
            <h2 class="text-[12px] md:text-[15px] tracking-[2px] text-admin-gold font-bold uppercase" id="form-title">ADD NEW PACKAGE</h2>
          </div>
          <div class="p-5 md:p-6">
            <form method="POST" action="{{ route('admin.packages.store') }}" id="package-form">
              @csrf
              <input type="hidden" name="_method" id="form-method" value="POST">
              <div class="mb-4">
                <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Package Name</label>
                <input type="text" name="name" id="pkg-name" required placeholder="e.g. Basic, Standard"
                  class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body placeholder:text-admin-cream-dim" />
              </div>
              <div class="mb-4">
                <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Price (₱)</label>
                <input type="number" name="price" id="pkg-price" required min="0" step="0.01"
                  class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body" />
              </div>
              <div class="mb-4">
                <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Max Guests</label>
                <input type="number" name="max_guests" id="pkg-guests" required min="1"
                  class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body" />
              </div>
              <div class="mb-4">
                <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Duration</label>
                <input type="text" name="duration" id="pkg-duration" placeholder="e.g. 4 hours"
                  class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body placeholder:text-admin-cream-dim" />
              </div>
              <div class="mb-5">
                <label class="block text-[12px] tracking-[1px] text-admin-gold mb-2 font-bold">Description</label>
                <textarea name="description" id="pkg-desc" rows="3" placeholder="Brief details..."
                  class="w-full bg-admin-secondary border border-admin-gold/30 text-admin-cream px-3.5 py-2.5 rounded-md text-[15px] outline-none transition-colors focus:border-admin-gold font-body resize-y placeholder:text-admin-cream-dim"></textarea>
              </div>
              <button type="submit" class="w-full bg-admin-gold text-admin-dark border-none py-3 rounded-md font-bold text-[15px] tracking-[1px] cursor-pointer transition-opacity hover:opacity-85" id="submit-btn">CREATE PACKAGE</button>
              <button type="button" id="cancel-edit-btn" onclick="cancelEdit()" class="w-full bg-transparent border border-admin-gold text-admin-gold py-3 rounded-md font-bold text-[15px] tracking-[1px] cursor-pointer mt-2.5 hidden">CANCEL EDIT</button>
            </form>
          </div>
        </div>
      </div>

      <!-- PACKAGES TABLE -->
      <div class="bg-admin-card border border-admin-gold/20 rounded-lg overflow-hidden">
        <div class="p-4 md:p-5 border-b border-admin-gold/20 bg-admin-secondary">
          <h2 class="text-[12px] md:text-[15px] tracking-[2px] text-admin-gold font-bold">ALL PACKAGES</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full border-collapse">
            <thead class="bg-admin-secondary">
              <tr>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">NAME</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">PRICE</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold hidden md:table-cell">CAPACITY</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold hidden md:table-cell">DURATION</th>
                <th class="p-3 md:px-4 md:py-3.5 text-left text-[11px] tracking-[2px] text-admin-gold font-bold">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($packages as $pkg)
                <tr class="border-b border-admin-gold/10 transition-colors hover:bg-admin-gold/5">
                  <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream font-bold">{{ $pkg->name }}</td>
                  <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream-dim">₱{{ number_format($pkg->price, 2) }}</td>
                  <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream-dim hidden md:table-cell">{{ $pkg->max_guests }}</td>
                  <td class="p-3 md:px-4 md:py-3.5 text-[15px] text-admin-cream-dim hidden md:table-cell">{{ $pkg->duration ?? 'N/A' }}</td>
                  <td class="p-3 md:px-4 md:py-3.5">
                    <div class="flex gap-1.5">
                      <button type="button" class="bg-blue-400/15 border border-admin-blue text-admin-blue px-2.5 py-1 rounded text-[11px] cursor-pointer transition-all hover:bg-admin-blue hover:text-white" onclick="editPackage({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', '{{ $pkg->price }}', '{{ $pkg->max_guests }}', '{{ addslashes($pkg->duration) }}', '{{ addslashes($pkg->description) }}')">Edit</button>
                      <form method="POST" action="{{ route('admin.packages.destroy', $pkg->id) }}" onsubmit="return confirm('Are you sure you want to delete this package?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-400/15 border border-admin-red text-admin-red px-2.5 py-1 rounded text-[11px] cursor-pointer transition-all hover:bg-admin-red hover:text-white">Delete</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center py-10 text-admin-cream-dim text-[16px]">No packages found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    function editPackage(id, name, price, maxGuests, duration, desc) {
      document.getElementById('form-title').innerText = 'EDIT PACKAGE';
      document.getElementById('submit-btn').innerText = 'UPDATE PACKAGE';
      const form = document.getElementById('package-form');
      form.action = '/admin/packages/' + id;
      document.getElementById('form-method').value = 'PUT';
      document.getElementById('pkg-name').value = name;
      document.getElementById('pkg-price').value = price;
      document.getElementById('pkg-guests').value = maxGuests;
      document.getElementById('pkg-duration').value = duration;
      document.getElementById('pkg-desc').value = desc;
      document.getElementById('cancel-edit-btn').classList.remove('hidden');
      window.scrollTo(0, 0);
    }

    function cancelEdit() {
      document.getElementById('form-title').innerText = 'ADD NEW PACKAGE';
      document.getElementById('submit-btn').innerText = 'CREATE PACKAGE';
      const form = document.getElementById('package-form');
      form.action = '{{ route("admin.packages.store") }}';
      document.getElementById('form-method').value = 'POST';
      form.reset();
      document.getElementById('cancel-edit-btn').classList.add('hidden');
    }
  </script>
</body>
</html>
