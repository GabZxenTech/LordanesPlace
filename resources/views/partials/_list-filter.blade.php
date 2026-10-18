{{-- Tabs, filters, sort and search for admin tables (shows/hides and reorders rows). --}}
<style>
  .lf-bar { display: flex; flex-direction: column; gap: 12px; padding: 14px 24px; border-bottom: 1px solid #d4c4a0; background: #fffcf5; }
  .lf-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
  .lf-tab { font-family: 'Jost', sans-serif; font-size: 13px; font-weight: 600; background: transparent; color: #5c4a33; border: 1px solid #d4c4a0; border-radius: 999px; padding: 6px 14px; cursor: pointer; transition: border-color .2s, color .2s; }
  .lf-tab:hover { border-color: #c9a84c; color: #2c1a0e; }
  .lf-tab .lf-n { margin-left: 5px; color: #8a6a40; font-variant-numeric: tabular-nums; }
  .lf-tab[aria-selected="true"] { background: #2c1a0e; border-color: #2c1a0e; color: #fff; }
  .lf-tab[aria-selected="true"] .lf-n { color: #c9a84c; }
  .lf-controls { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
  .lf-control { display: flex; align-items: center; gap: 6px; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 700; color: #8a6a40; }
  .lf-control select, .lf-search { font-family: 'Jost', sans-serif; font-size: 13px; color: #2c1a0e; background: #f5f0e8; border: 1px solid #d4c4a0; border-radius: 6px; padding: 7px 10px; outline: none; }
  .lf-control select:focus, .lf-search:focus { border-color: #c9a84c; }
  .lf-search { flex: 1; min-width: 200px; letter-spacing: 0; text-transform: none; font-weight: 400; }
  .lf-tab:focus-visible, .lf-control select:focus-visible, .lf-search:focus-visible { outline: 2px solid #2c1a0e; outline-offset: 2px; }
  [data-list-rows] tr[hidden] { display: none; }
</style>
<script>
  (function () {
    document.querySelectorAll('[data-list-filter]').forEach(function (bar) {
      const key = bar.dataset.listFilter;
      const tbody = document.querySelector('[data-list-rows="' + key + '"]');
      if (!tbody) return;

      const rows = Array.from(tbody.querySelectorAll('tr[data-tabs]'));
      const emptyRow = tbody.querySelector('[data-list-empty]');
      const tabs = Array.from(bar.querySelectorAll('[data-filter-tab]'));
      const fields = Array.from(bar.querySelectorAll('[data-filter-field]'));
      const sortSel = bar.querySelector('[data-filter-sort]');
      const search = bar.querySelector('[data-filter-search]');
      const storeKey = 'listFilter:' + key;

      // Tab counts (whole list for this page).
      tabs.forEach(function (t) {
        const n = t.dataset.filterTab === 'all'
          ? rows.length
          : rows.filter(function (r) { return r.dataset.tabs.split(' ').includes(t.dataset.filterTab); }).length;
        const badge = t.querySelector('[data-count]');
        if (badge) badge.textContent = n;
      });

      // Restore the admin's last choices (per browser; ignored if unavailable).
      let state = { tab: bar.dataset.defaultTab || 'all', fields: {}, sort: sortSel ? sortSel.value : '', q: '' };
      try { Object.assign(state, JSON.parse(localStorage.getItem(storeKey) || '{}')); } catch (e) {}
      if (!tabs.some(function (t) { return t.dataset.filterTab === state.tab; })) state.tab = bar.dataset.defaultTab || 'all';
      fields.forEach(function (f) { if (state.fields[f.dataset.filterField] !== undefined) f.value = state.fields[f.dataset.filterField]; });
      if (sortSel && state.sort && Array.from(sortSel.options).some(function (o) { return o.value === state.sort; })) sortSel.value = state.sort;
      // Search is not restored: a stale query would silently hide rows.

      function save() {
        try { localStorage.setItem(storeKey, JSON.stringify({ tab: state.tab, fields: state.fields, sort: state.sort })); } catch (e) {}
      }

      function apply() {
        const q = (search ? search.value : '').trim().toLowerCase();

        // Sort: "attr:asc" / "attr:desc" on a numeric data-* value.
        if (sortSel) {
          const parts = sortSel.value.split(':');
          const attr = parts[0], dir = parts[1] === 'desc' ? -1 : 1;
          rows.slice().sort(function (a, b) {
            return (Number(a.dataset[attr]) - Number(b.dataset[attr])) * dir;
          }).forEach(function (r) { tbody.appendChild(r); });
          if (emptyRow) tbody.appendChild(emptyRow);
        }

        let visible = 0;
        rows.forEach(function (r) {
          let show = state.tab === 'all' || r.dataset.tabs.split(' ').includes(state.tab);
          fields.forEach(function (f) {
            if (show && f.value && r.dataset[f.dataset.filterField] !== f.value) show = false;
          });
          if (show && q && !(r.dataset.search || '').includes(q)) show = false;
          r.hidden = !show;
          if (show) visible++;
        });

        tabs.forEach(function (t) { t.setAttribute('aria-selected', String(t.dataset.filterTab === state.tab)); });
        if (emptyRow) emptyRow.hidden = visible > 0 || rows.length === 0;
      }

      tabs.forEach(function (t) {
        t.addEventListener('click', function () { state.tab = t.dataset.filterTab; save(); apply(); });
      });
      fields.forEach(function (f) {
        f.addEventListener('change', function () { state.fields[f.dataset.filterField] = f.value; save(); apply(); });
      });
      if (sortSel) sortSel.addEventListener('change', function () { state.sort = sortSel.value; save(); apply(); });
      if (search) search.addEventListener('input', apply);

      // A notification link (#booking-42) must land on a visible row.
      const target = location.hash ? rows.find(function (r) { return '#' + r.id === location.hash; }) : null;
      if (target) {
        state.tab = 'all';
        fields.forEach(function (f) { f.value = ''; state.fields[f.dataset.filterField] = ''; });
      }

      apply();
      if (target) target.scrollIntoView({ block: 'center' });
    });
  })();
</script>
