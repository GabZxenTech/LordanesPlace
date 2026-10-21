{{-- Phone layout for admin pages: tables with class "m-cards" turn into one card per row. --}}
@once
<style>
  @media (max-width: 767px) {
    body > main { padding: 64px 14px 40px !important; min-width: 0; }
    body > main h1 { font-size: 30px !important; }
    .adm-stack { grid-template-columns: minmax(0, 1fr) !important; }
    .adm-stats { grid-template-columns: minmax(0, 1fr) !important; gap: 12px !important; }

    table.m-cards, table.m-cards tbody, table.m-cards tr, table.m-cards td { display: block; width: 100%; box-sizing: border-box; }
    table.m-cards thead { display: none; }
    table.m-cards tbody { padding: 12px; }
    table.m-cards tr { border: 1px solid #e8dcc8 !important; border-radius: 10px; margin-bottom: 12px; padding: 6px 14px; background: #fffdf8; }
    table.m-cards tr[hidden], table.m-cards tr[style*="display: none"] { display: none !important; }
    table.m-cards td { padding: 9px 0 !important; border: none !important; border-bottom: 1px dashed #efe4cf !important; text-align: left !important; }
    table.m-cards td:last-child { border-bottom: none !important; }
    table.m-cards td[data-label]::before { content: attr(data-label); display: block; font: 700 10px 'Jost', sans-serif; letter-spacing: 1.5px; text-transform: uppercase; color: #8a6a40; margin-bottom: 4px; }
    table.m-cards td[colspan] { text-align: center !important; border-bottom: none !important; }
    table.m-cards td > div[style*="display: flex"], table.m-cards td > div[style*="display:flex"] { flex-wrap: wrap; }
  }
</style>
<script>
  // Copies each table header onto its cells so the phone layout can label them.
  document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('table.m-cards').forEach(function (table) {
    const labels = Array.from(table.querySelectorAll('thead th')).map(function (th) { return th.textContent.trim(); });
    const label = function (row) {
      Array.from(row.children).forEach(function (td, i) {
        if (td.tagName === 'TD' && !td.hasAttribute('colspan') && labels[i]) td.dataset.label = labels[i];
      });
    };
    table.querySelectorAll('tbody tr').forEach(label);
    // Rows added later (e.g. by filters) get labels too.
    const body = table.querySelector('tbody');
    if (body) new MutationObserver(function (list) {
      list.forEach(function (m) { m.addedNodes.forEach(function (n) { if (n.tagName === 'TR') label(n); }); });
    }).observe(body, { childList: true });
  });
  });
</script>
@endonce
