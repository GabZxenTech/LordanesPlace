<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edit Scene | Admin</title>
  @include('partials._favicon')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    .ed-layout { display: grid; grid-template-columns: minmax(0, 2.2fr) minmax(300px, 1fr); gap: 24px; align-items: start; }
    @media (max-width: 1100px) { .ed-layout { grid-template-columns: 1fr; } }
    @media (max-width: 700px) { .ed-main { padding: 64px 16px 24px !important; } }
    .ed-card { background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 10px; overflow: hidden; margin-bottom: 20px; }
    .ed-card-head { padding: 14px 20px; border-bottom: 1px solid #d4c4a0; background: #f5edd8; }
    .ed-card-head h2 { font-size: 12px; letter-spacing: 3px; color: #2c1a0e; text-transform: uppercase; font-weight: 800; margin: 0; }
    .ed-input { width: 100%; background: #f5f0e8; border: 1px solid #d4c4a0; color: #2c1a0e; padding: 9px 12px; border-radius: 6px; font-size: 14px; outline: none; font-family: 'Jost', sans-serif; box-sizing: border-box; }
    .ed-input:focus { border-color: #c9a84c; }
    .ed-label { display: block; font-size: 11px; letter-spacing: 2px; color: #8a6a40; margin-bottom: 6px; font-weight: 700; text-transform: uppercase; }
    .ed-btn { border: 1px solid #d4c4a0; background: transparent; color: #8a6a40; padding: 7px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; font-family: 'Jost', sans-serif; display: inline-block; }
    .ed-btn:hover { background: #f5edd8; }
    .ed-btn.gold { background: #c9a84c; border-color: #c9a84c; color: #2c1a0e; }
    .ed-btn.gold:hover { background: #b8860b; color: #fff; }
    .ed-btn.red { color: #c0392b; border-color: rgba(192,57,43,0.35); }
    .ed-btn.red:hover { background: #c0392b; color: #fff; }
    .ed-btn.on { background: #2c1a0e; border-color: #2c1a0e; color: #fff9ef; }

    #pano { width: 100%; height: 66vh; min-height: 360px; background: #1a1208; border-radius: 10px; position: relative; overflow: hidden; }
    #pano.placing { cursor: crosshair; }
    .ed-hint { position: absolute; left: 12px; bottom: 12px; z-index: 5; background: rgba(0,0,0,0.65); color: #fff; font-size: 13px; padding: 8px 12px; border-radius: 6px; pointer-events: none; display: none; }

    /* Arrow on the picture */
    .ed-hs { width: 44px; height: 44px; margin-left: -22px; margin-top: -22px; cursor: pointer; }
    .ed-hs img { width: 100%; height: 100%; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); }
    .ed-hs span { position: absolute; top: 100%; left: 50%; transform: translateX(-50%); margin-top: 4px; background: rgba(0,0,0,0.7); color: #fff; font-size: 12px; padding: 3px 8px; border-radius: 4px; white-space: nowrap; max-width: 180px; overflow: hidden; text-overflow: ellipsis; pointer-events: none; }
    .ed-hs.is-active img { outline: 3px solid #c9a84c; border-radius: 50%; }
    .ed-hs.is-temp { opacity: 0.8; }

    .ed-row { padding: 14px 20px; border-bottom: 1px solid #e8dcc8; }
    .ed-row.is-active { background: rgba(201,168,76,0.14); }
    .ed-row:last-child { border-bottom: none; }
  </style>
</head>
<body style="margin: 0; font-family: 'Jost', sans-serif; background: #f5f0e8; min-height: 100vh; display: flex;">

  @include('partials._admin-sidebar')

  <main class="ed-main" style="flex: 1; min-width: 0; padding: 32px 40px; min-height: 100vh; overflow-y: auto;">
    <div style="margin-bottom: 20px;">
      <a href="{{ route('admin.tour.index') }}" style="font-size: 13px; color: #8a6a40; text-decoration: none; font-weight: 600;">← All scenes</a>
      <h1 style="font-family: 'Cormorant Garamond', serif; font-size: 34px; font-weight: 700; color: #2c1a0e; margin: 6px 0 0;">{{ $scene->name }}</h1>
    </div>

    @if(session('success'))
      <div style="background: #d4edda; border: 1px solid #28a745; color: #155724; padding: 12px 18px; border-radius: 6px; margin-bottom: 16px; font-size: 14px;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
      <div style="background: #f8d7da; border: 1px solid #dc3545; color: #721c24; padding: 12px 18px; border-radius: 6px; margin-bottom: 16px;">
        @foreach($errors->all() as $err) <div style="font-size: 14px;">{{ $err }}</div> @endforeach
      </div>
    @endif

    <div class="ed-layout">

      {{-- PICTURE --}}
      <div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px;">
          <button type="button" class="ed-btn gold" id="addModeBtn">＋ Add arrow</button>
          <button type="button" class="ed-btn" id="startViewBtn" title="Use what you see now as the view visitors start with">Set as starting view</button>
        </div>
        <div id="pano"><div class="ed-hint" id="hint"></div></div>
        <p style="font-size: 12px; color: #8a6a40; margin: 10px 0 0;">Drag to look around. Press "Add arrow", then click where the arrow should be.</p>
      </div>

      {{-- SIDE PANEL --}}
      <div>
        {{-- New arrow form --}}
        <div class="ed-card" id="newCard" style="display: none;">
          <div class="ed-card-head"><h2>New arrow</h2></div>
          <div style="padding: 16px 20px;">
            <label class="ed-label">Goes to</label>
            <select id="newTarget" class="ed-input" style="margin-bottom: 12px;">
              @foreach($targets as $t) <option value="{{ $t->id }}">{{ $t->name }}</option> @endforeach
            </select>
            <label class="ed-label">Arrow name (optional)</label>
            <input type="text" id="newLabel" class="ed-input" maxlength="120" placeholder="Shown on the arrow; blank = scene name" style="margin-bottom: 14px;">
            <div style="display: flex; gap: 8px;">
              <button type="button" class="ed-btn gold" id="newSave">Save arrow</button>
              <button type="button" class="ed-btn" id="newCancel">Cancel</button>
            </div>
          </div>
        </div>

        {{-- Arrows in this scene --}}
        <div class="ed-card">
          <div class="ed-card-head"><h2>Arrows in this scene (<span id="arrowCount">0</span>)</h2></div>
          <div id="arrowList"></div>
          <p id="arrowEmpty" style="padding: 20px; margin: 0; font-size: 13px; color: #8a6a40; display: none;">No arrows yet.</p>
        </div>

        {{-- Scene settings --}}
        <div class="ed-card">
          <div class="ed-card-head"><h2>Scene</h2></div>
          <form method="POST" action="{{ route('admin.tour.update', $scene) }}" enctype="multipart/form-data" style="padding: 16px 20px;">
            @csrf @method('PUT')
            <label class="ed-label">Name</label>
            <input type="text" name="name" class="ed-input" maxlength="120" required value="{{ old('name', $scene->name) }}" style="margin-bottom: 12px;">
            <label class="ed-label">Replace 360 picture (JPG)</label>
            <input type="file" name="image" class="ed-input" accept="image/jpeg" style="margin-bottom: 14px;">
            <button type="submit" class="ed-btn gold">Save scene</button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script src="{{ asset('lordanes-place-360/app-files/vendor/marzipano.js') }}"></script>
  <script>
    (function () {
      var Marzipano = window.Marzipano;
      var SCENE = @json($sceneData);
      var TARGETS = @json($targets);
      var TILE_BASE = @json(asset('lordanes-place-360/app-files/tiles'));
      var ARROW_ICON = @json(asset('lordanes-place-360/app-files/img/link.png'));
      var URLS = {
        store: @json(route('admin.tour.hotspots.store', $scene)),
        update: @json(route('admin.tour.hotspots.update', ['hotspot' => '__ID__'])),
        destroy: @json(route('admin.tour.hotspots.destroy', ['hotspot' => '__ID__'])),
        view: @json(route('admin.tour.view', $scene))
      };
      var CSRF = document.querySelector('meta[name="csrf-token"]').content;

      var panoEl = document.getElementById('pano');
      var hintEl = document.getElementById('hint');
      var addBtn = document.getElementById('addModeBtn');
      var newCard = document.getElementById('newCard');
      var listEl = document.getElementById('arrowList');

      var hotspots = SCENE.linkHotspots.slice(); // arrows saved on the server
      var marks = {};                            // id -> { hotspot, el }
      var mode = null;                           // 'add' | { move: id } | null
      var tempPos = null;                        // clicked spot while the new-arrow form is open
      var tempMark = null;
      var activeId = null;

      // ---- Viewer ----
      var viewer = new Marzipano.Viewer(panoEl, { controls: { mouseViewMode: 'drag' } });
      var source, geometry, limiter;
      if (SCENE.imageUrl) {
        source = Marzipano.ImageUrlSource.fromString(SCENE.imageUrl);
        geometry = new Marzipano.EquirectGeometry([{ width: SCENE.imageWidth }]);
        limiter = Marzipano.RectilinearView.limit.traditional(65536, 100 * Math.PI / 180);
      } else {
        source = Marzipano.ImageUrlSource.fromString(
          TILE_BASE + '/' + SCENE.id + '/{z}/{f}/{y}/{x}.jpg',
          { cubeMapPreviewUrl: TILE_BASE + '/' + SCENE.id + '/preview.jpg' });
        geometry = new Marzipano.CubeGeometry(SCENE.levels);
        limiter = Marzipano.RectilinearView.limit.traditional(SCENE.faceSize, 100 * Math.PI / 180, 120 * Math.PI / 180);
      }
      var view = new Marzipano.RectilinearView(SCENE.initialViewParameters, limiter);
      var scene = viewer.createScene({ source: source, geometry: geometry, view: view, pinFirstLevel: true });
      scene.switchTo();

      // ---- Helpers ----
      function api(method, url, body) {
        return fetch(url, {
          method: method,
          headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          credentials: 'same-origin',
          body: body ? JSON.stringify(body) : undefined
        }).then(function (r) {
          return r.json().catch(function () { return {}; }).then(function (data) {
            if (!r.ok) {
              var msg = data.message || 'Something went wrong.';
              if (data.errors) msg = Object.values(data.errors)[0][0];
              throw new Error(msg);
            }
            return data;
          });
        });
      }
      function fail(e) { if (window.uiToast) uiToast(e.message || 'Something went wrong.', 'error'); }
      function ok(msg) { if (window.uiToast) uiToast(msg, 'success'); }
      function url(tpl, id) { return tpl.replace('__ID__', id); }
      function targetName(id) {
        var t = TARGETS.find(function (x) { return x.id === Number(id); });
        return t ? t.name : '';
      }
      function find(id) { return hotspots.find(function (h) { return h.id === id; }); }

      // ---- Arrows on the picture ----
      function makeArrowEl(label, temp) {
        var el = document.createElement('div');
        el.className = 'ed-hs' + (temp ? ' is-temp' : '');
        var img = document.createElement('img');
        img.src = ARROW_ICON;
        el.appendChild(img);
        if (label) {
          var span = document.createElement('span');
          span.textContent = label;
          el.appendChild(span);
        }
        // Keep clicks and wheel on the arrow from moving the view.
        ['touchstart', 'touchmove', 'wheel', 'mousewheel'].forEach(function (ev) {
          el.addEventListener(ev, function (e) { e.stopPropagation(); });
        });
        return el;
      }

      function arrowLabel(h) { return h.label || targetName(h.targetId); }

      function drawMark(h) {
        if (marks[h.id]) scene.hotspotContainer().destroyHotspot(marks[h.id].hotspot);
        var el = makeArrowEl(arrowLabel(h));
        el.addEventListener('click', function (e) { e.stopPropagation(); setActive(h.id); });
        var hotspot = scene.hotspotContainer().createHotspot(el, { yaw: h.yaw, pitch: h.pitch });
        marks[h.id] = { hotspot: hotspot, el: el };
      }

      function setActive(id) {
        activeId = id;
        Object.keys(marks).forEach(function (k) { marks[k].el.classList.toggle('is-active', Number(k) === id); });
        renderList();
        var row = listEl.querySelector('[data-id="' + id + '"]');
        if (row) row.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
      }

      // ---- Arrow list ----
      function renderList() {
        listEl.innerHTML = '';
        document.getElementById('arrowCount').textContent = hotspots.length;
        document.getElementById('arrowEmpty').style.display = hotspots.length ? 'none' : 'block';

        hotspots.forEach(function (h) {
          var row = document.createElement('div');
          row.className = 'ed-row' + (h.id === activeId ? ' is-active' : '');
          row.dataset.id = h.id;

          var sel = document.createElement('select');
          sel.className = 'ed-input';
          sel.style.marginBottom = '8px';
          TARGETS.forEach(function (t) {
            var o = document.createElement('option');
            o.value = t.id; o.textContent = t.name;
            if (t.id === h.targetId) o.selected = true;
            sel.appendChild(o);
          });

          var label = document.createElement('input');
          label.type = 'text'; label.className = 'ed-input'; label.maxLength = 120;
          label.value = h.label || '';
          label.placeholder = 'Arrow name (blank = scene name)';
          label.style.marginBottom = '10px';

          var btns = document.createElement('div');
          btns.style.cssText = 'display:flex; gap:6px; flex-wrap:wrap;';
          btns.appendChild(button('Save', 'ed-btn gold', function () {
            api('PUT', url(URLS.update, h.id), { target_scene_id: Number(sel.value), label: label.value.trim() || null })
              .then(function (data) { Object.assign(h, data); drawMark(h); setActive(h.id); ok('Arrow saved.'); })
              .catch(fail);
          }));
          btns.appendChild(button('Move', 'ed-btn', function () {
            startMode({ move: h.id });
            setActive(h.id);
          }));
          btns.appendChild(button('Delete', 'ed-btn red', function () {
            uiConfirm({ message: 'Delete this arrow?', detail: 'Visitors will no longer see it.' }).then(function (yes) {
              if (!yes) return;
              api('DELETE', url(URLS.destroy, h.id)).then(function () {
                scene.hotspotContainer().destroyHotspot(marks[h.id].hotspot);
                delete marks[h.id];
                hotspots = hotspots.filter(function (x) { return x.id !== h.id; });
                if (activeId === h.id) activeId = null;
                renderList();
                ok('Arrow deleted.');
              }).catch(fail);
            });
          }));

          row.appendChild(sel); row.appendChild(label); row.appendChild(btns);
          row.addEventListener('click', function (e) {
            if (e.target.closest('button, select, input')) return;
            setActive(h.id);
            // Look at the arrow.
            view.setParameters({ yaw: h.yaw, pitch: h.pitch });
          });
          listEl.appendChild(row);
        });
      }

      function button(text, cls, fn) {
        var b = document.createElement('button');
        b.type = 'button'; b.className = cls; b.textContent = text;
        b.addEventListener('click', fn);
        return b;
      }

      // ---- Placing and moving ----
      function startMode(m) {
        cancelNew();
        mode = m;
        panoEl.classList.add('placing');
        addBtn.classList.toggle('on', m === 'add');
        hintEl.style.display = 'block';
        hintEl.textContent = m === 'add' ? 'Click where the arrow should go.' : 'Click the new spot for this arrow.';
      }

      function stopMode() {
        mode = null;
        panoEl.classList.remove('placing');
        addBtn.classList.remove('on');
        hintEl.style.display = 'none';
      }

      function cancelNew() {
        if (tempMark) { scene.hotspotContainer().destroyHotspot(tempMark); tempMark = null; }
        tempPos = null;
        newCard.style.display = 'none';
      }

      addBtn.addEventListener('click', function () {
        if (!TARGETS.length) { fail(new Error('Add another scene first, so the arrow has somewhere to go.')); return; }
        mode === 'add' ? stopMode() : startMode('add');
      });

      // A click counts only if the mouse barely moved (not a drag).
      var downAt = null;
      panoEl.addEventListener('mousedown', function (e) { downAt = { x: e.clientX, y: e.clientY }; });
      panoEl.addEventListener('click', function (e) {
        if (!mode || !downAt) return;
        if (Math.abs(e.clientX - downAt.x) > 5 || Math.abs(e.clientY - downAt.y) > 5) return;

        var rect = panoEl.getBoundingClientRect();
        var pos = view.screenToCoordinates({ x: e.clientX - rect.left, y: e.clientY - rect.top });
        if (pos.yaw == null || pos.pitch == null) return;

        if (mode === 'add') {
          stopMode();
          tempPos = { yaw: pos.yaw, pitch: pos.pitch };
          tempMark = scene.hotspotContainer().createHotspot(makeArrowEl('New', true), tempPos);
          newCard.style.display = 'block';
          document.getElementById('newLabel').value = '';
        } else {
          var id = mode.move;
          stopMode();
          api('PUT', url(URLS.update, id), { yaw: pos.yaw, pitch: pos.pitch })
            .then(function (data) { Object.assign(find(id), data); drawMark(find(id)); setActive(id); ok('Arrow moved.'); })
            .catch(fail);
        }
      });
      window.addEventListener('keydown', function (e) { if (e.key === 'Escape') { stopMode(); cancelNew(); } });

      document.getElementById('newCancel').addEventListener('click', cancelNew);
      document.getElementById('newSave').addEventListener('click', function () {
        if (!tempPos) return;
        api('POST', URLS.store, {
          target_scene_id: Number(document.getElementById('newTarget').value),
          yaw: tempPos.yaw,
          pitch: tempPos.pitch,
          label: document.getElementById('newLabel').value.trim() || null
        }).then(function (data) {
          cancelNew();
          hotspots.push(data);
          drawMark(data);
          setActive(data.id);
          ok('Arrow added.');
        }).catch(fail);
      });

      document.getElementById('startViewBtn').addEventListener('click', function () {
        api('PUT', URLS.view, { yaw: view.yaw(), pitch: view.pitch(), fov: view.fov() })
          .then(function () { ok('Starting view saved.'); })
          .catch(fail);
      });

      // ---- Start ----
      hotspots.forEach(drawMark);
      renderList();
    })();
  </script>
</body>
</html>
