{{-- Terms & Conditions and Privacy Policy popup. Any link with data-legal="terms" or "privacy" opens it. --}}
@once
<style>
  #legalModal { display: none; position: fixed; inset: 0; z-index: 10050; background: rgba(0,0,0,0.65); align-items: center; justify-content: center; padding: 16px; }
  #legalModal.open { display: flex; }
  .lg-box { background: #fffcf5; border-radius: 12px; width: 100%; max-width: 720px; max-height: 88vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
  .lg-head { padding: 18px 22px 14px; border-bottom: 1px solid #e8e0ce; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-shrink: 0; }
  .lg-head h2 { font-family: 'Cormorant Garamond', serif; font-size: 34px; font-weight: 700; color: #2c1a0e; margin: 0; line-height: 1.15; }
  .lg-head p { font: 16px/1.5 'Jost', sans-serif; color: #8a6a40; margin: 3px 0 0; }
  .lg-close { background: none; border: 0; color: #999; cursor: pointer; padding: 4px; line-height: 0; border-radius: 6px; }
  .lg-close:hover { color: #2c1a0e; background: rgba(0,0,0,0.05); }
  .lg-body { overflow-y: auto; padding: 14px 22px 22px; }
  .lg-sec { margin-bottom: 16px; }
  .lg-sec h3 { font-family: 'Cormorant Garamond', serif; font-size: 28px; font-weight: 700; color: #1a1208; margin: 0 0 6px; }
  .lg-sec p, .lg-sec li { font: 18px/1.7 'Jost', sans-serif; color: #5c4a33; }
  .lg-sec p { margin: 0 0 8px; }
  .lg-sec ul { margin: 0; padding-left: 20px; }
  .lg-sec li { margin-bottom: 4px; }
  .lg-sec a, .lg-updated a { color: #b8860b; font-weight: 600; }
  .lg-updated { font: 16px/1.6 'Jost', sans-serif; color: #8f7c62; margin: 0 0 14px; }
  .lg-thanks { font: italic 700 30px 'Cormorant Garamond', serif; color: #a88a4c; text-align: center; margin: 18px 0 0; }
</style>

<div id="legalModal" role="dialog" aria-modal="true" aria-labelledby="legalTitle" aria-hidden="true">
  <div class="lg-box">
    <div class="lg-head">
      <div>
        <h2 id="legalTitle">Terms &amp; Conditions</h2>
        <p id="legalSub">Client reservation policies for booking</p>
      </div>
      <button type="button" class="lg-close" id="legalClose" aria-label="Close">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="lg-body" id="legalBody">
      <div data-legal-pane="terms" hidden>@include('partials._terms-content')</div>
      <div data-legal-pane="privacy" hidden>@include('partials._privacy-content')</div>
    </div>
  </div>
</div>

<script>
  (function () {
    var modal = document.getElementById('legalModal');
    if (!modal) return;
    var titles = {
      terms:   ['Terms & Conditions', 'Client reservation policies for booking'],
      privacy: ['Privacy Policy', 'How we collect, use and protect your information']
    };
    var lastFocus = null;

    function show(kind) {
      if (!titles[kind]) kind = 'terms';
      document.getElementById('legalTitle').textContent = titles[kind][0];
      document.getElementById('legalSub').textContent = titles[kind][1];
      modal.querySelectorAll('[data-legal-pane]').forEach(function (p) { p.hidden = p.dataset.legalPane !== kind; });
      document.getElementById('legalBody').scrollTop = 0;
    }

    function open(kind) {
      lastFocus = document.activeElement;
      show(kind);
      modal.classList.add('open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      document.getElementById('legalClose').focus();
    }

    function close() {
      modal.classList.remove('open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    document.addEventListener('click', function (e) {
      var link = e.target.closest('[data-legal]');
      if (link) {
        e.preventDefault();
        if (modal.classList.contains('open')) show(link.dataset.legal); else open(link.dataset.legal);
        return;
      }
      if (e.target === modal || e.target.closest('#legalClose')) close();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modal.classList.contains('open')) close();
    });
  })();
</script>
@endonce
