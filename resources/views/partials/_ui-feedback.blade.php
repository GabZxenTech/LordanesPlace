{{-- Styled toasts, field errors and confirm dialogs (replace the browser's alert/confirm). --}}
@once
<style>
  .ui-toast-wrap { position: fixed; top: 18px; left: 50%; transform: translateX(-50%); z-index: 100000; display: flex; flex-direction: column; gap: 8px; width: min(420px, calc(100vw - 32px)); pointer-events: none; }
  .ui-toast { pointer-events: auto; display: flex; align-items: flex-start; gap: 10px; background: #fff9ef; color: #2c1a0e; border: 1px solid #e3d3ad; border-left: 4px solid #b8860b; border-radius: 8px; padding: 12px 14px; font: 500 14px/1.45 'Jost', sans-serif; box-shadow: 0 10px 28px rgba(44,26,14,0.18); opacity: 0; transform: translateY(-8px); transition: opacity .2s, transform .2s; }
  .ui-toast.show { opacity: 1; transform: none; }
  .ui-toast.ui-toast-success { border-left-color: #2e7d32; }
  .ui-toast.ui-toast-info { border-left-color: #b8860b; }
  .ui-toast-icon { flex-shrink: 0; margin-top: 1px; color: #b8860b; }
  .ui-toast-success .ui-toast-icon { color: #2e7d32; }
  .ui-toast-info .ui-toast-icon { color: #b8860b; }
  .ui-toast-text { flex: 1; }
  .ui-toast-close { flex-shrink: 0; background: none; border: none; color: #8a6a40; cursor: pointer; font-size: 18px; line-height: 1; padding: 0 2px; }

  .ui-field-error { outline: 2px solid #b8860b !important; outline-offset: 3px; border-radius: 6px; box-shadow: 0 0 0 6px rgba(184,134,11,0.15) !important; animation: ui-shake .35s ease; }
  .ui-field-msg { color: #8a6a40; font-weight: 600; font: 500 13px/1.4 'Jost', sans-serif; margin: 8px 0 0; display: flex; align-items: center; gap: 6px; }
  @keyframes ui-shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }

  .ui-confirm-overlay { position: fixed; inset: 0; background: rgba(44,26,14,0.45); display: none; align-items: center; justify-content: center; z-index: 100001; padding: 16px; }
  .ui-confirm-overlay.open { display: flex; }
  .ui-confirm-box { background: #fff9ef; border: 1px solid #d4c4a0; border-radius: 12px; width: 100%; max-width: 400px; padding: 28px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.2); font-family: 'Jost', sans-serif; }
  .ui-confirm-icon { width: 52px; height: 52px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px; background: #f5edd8; color: #b8860b; }
  .ui-confirm-box.is-danger .ui-confirm-icon { background: #fbe9e6; color: #b03a2e; }
  .ui-confirm-box h3 { font-family: 'Cormorant Garamond', serif; font-size: 24px; font-weight: 700; color: #2c1a0e; margin: 0 0 8px; }
  .ui-confirm-box p { font-size: 14px; color: #6b5233; line-height: 1.55; margin: 0 0 22px; }
  .ui-confirm-actions { display: flex; gap: 10px; }
  .ui-confirm-actions button { flex: 1; padding: 11px; border-radius: 6px; font: 700 13px 'Jost', sans-serif; letter-spacing: .5px; cursor: pointer; }
  .ui-confirm-cancel { background: transparent; border: 1px solid #d4c4a0; color: #2c1a0e; }
  .ui-confirm-ok { background: #b8860b; border: 1px solid #b8860b; color: #fff; }
  .ui-confirm-box.is-danger .ui-confirm-ok { background: #b03a2e; border-color: #b03a2e; }

  /* Page loading bar and spinner */
  .ui-loadbar { position: fixed; top: 0; left: 0; height: 3px; width: 0; background: linear-gradient(90deg, #b8860b, #e8c96d); z-index: 100002; opacity: 0; pointer-events: none; transition: width .4s ease, opacity .3s ease; }
  .ui-loadbar.is-on { opacity: 1; }
  .ui-spinner { width: 34px; height: 34px; border: 3px solid rgba(232,201,109,0.3); border-top-color: #e8c96d; border-radius: 50%; animation: ui-spin .8s linear infinite; }
  @keyframes ui-spin { to { transform: rotate(360deg); } }
</style>

<div class="ui-loadbar" id="uiLoadBar" role="progressbar" aria-hidden="true"></div>
<div class="ui-toast-wrap" id="uiToastWrap" aria-live="polite"></div>

<div class="ui-confirm-overlay" id="uiConfirm" role="dialog" aria-modal="true" aria-labelledby="uiConfirmTitle">
  <div class="ui-confirm-box">
    <div class="ui-confirm-icon" aria-hidden="true">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <h3 id="uiConfirmTitle">Are you sure?</h3>
    <p id="uiConfirmText"></p>
    <div class="ui-confirm-actions">
      <button type="button" class="ui-confirm-cancel" id="uiConfirmCancel">Cancel</button>
      <button type="button" class="ui-confirm-ok" id="uiConfirmOk">Confirm</button>
    </div>
  </div>
</div>

<script>
(function () {
  const ICONS = {
    error: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
    success: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
  };
  ICONS.info = ICONS.error;

  window.uiToast = function (message, type) {
    type = type || 'error';
    const wrap = document.getElementById('uiToastWrap');
    if (!wrap) return;
    const el = document.createElement('div');
    el.className = 'ui-toast ui-toast-' + type;
    el.setAttribute('role', type === 'error' ? 'alert' : 'status');
    el.innerHTML = '<span class="ui-toast-icon">' + ICONS[type] + '</span><span class="ui-toast-text"></span><button type="button" class="ui-toast-close" aria-label="Close">&times;</button>';
    el.querySelector('.ui-toast-text').textContent = message;
    const remove = function () { el.classList.remove('show'); setTimeout(function () { el.remove(); }, 200); };
    el.querySelector('.ui-toast-close').addEventListener('click', remove);
    // Only the latest few stay on screen.
    while (wrap.children.length >= 3) wrap.firstChild.remove();
    wrap.appendChild(el);
    requestAnimationFrame(function () { el.classList.add('show'); });
    setTimeout(remove, 4500);
  };

  window.uiClearFieldError = function (field) {
    if (typeof field === 'string') field = document.querySelector(field);
    if (!field) return;
    field.classList.remove('ui-field-error');
    if (field._uiMsg) { field._uiMsg.remove(); field._uiMsg = null; }
  };

  // Scrolls to a field, highlights it and shows the message under it.
  window.uiFieldError = function (field, message) {
    if (typeof field === 'string') field = document.querySelector(field);
    uiToast(message, 'error');
    if (!field) return;

    uiClearFieldError(field);
    void field.offsetWidth; // restart the shake animation
    field.classList.add('ui-field-error');

    const msg = document.createElement('p');
    msg.className = 'ui-field-msg';
    msg.innerHTML = ICONS.error;
    msg.appendChild(document.createTextNode(message));
    field.insertAdjacentElement('afterend', msg);
    field._uiMsg = msg;

    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
    if (/^(INPUT|SELECT|TEXTAREA)$/.test(field.tagName)) setTimeout(function () { field.focus({ preventScroll: true }); }, 300);

    const clear = function () {
      uiClearFieldError(field);
      ['input', 'change', 'click'].forEach(function (ev) { field.removeEventListener(ev, clear); });
    };
    ['input', 'change', 'click'].forEach(function (ev) { field.addEventListener(ev, clear); });
  };

  // Styled replacement for confirm(). Resolves true when confirmed.
  window.uiConfirm = function (opts) {
    if (typeof opts === 'string') opts = { message: opts };
    const overlay = document.getElementById('uiConfirm');
    const box = overlay.querySelector('.ui-confirm-box');
    const okBtn = document.getElementById('uiConfirmOk');
    const cancelBtn = document.getElementById('uiConfirmCancel');
    const firstWord = (opts.message || '').split(/\s+/)[0].replace(/[^A-Za-z]/g, '');
    const danger = opts.danger !== undefined ? opts.danger : /^(Delete|Remove|Reject|Cancel)$/i.test(firstWord);

    document.getElementById('uiConfirmTitle').textContent = opts.title || opts.message || 'Are you sure?';
    document.getElementById('uiConfirmText').textContent = opts.detail || (danger ? 'This cannot be undone.' : 'Please confirm to continue.');
    okBtn.textContent = opts.confirmText || (firstWord ? firstWord.charAt(0).toUpperCase() + firstWord.slice(1).toLowerCase() : 'Confirm');
    cancelBtn.textContent = opts.cancelText || (okBtn.textContent === 'Cancel' ? 'Keep' : 'Cancel');
    box.classList.toggle('is-danger', danger);
    overlay.classList.add('open');
    setTimeout(function () { cancelBtn.focus(); }, 50);

    return new Promise(function (resolve) {
      const done = function (result) {
        overlay.classList.remove('open');
        okBtn.removeEventListener('click', onOk);
        cancelBtn.removeEventListener('click', onCancel);
        overlay.removeEventListener('click', onBackdrop);
        document.removeEventListener('keydown', onKey);
        resolve(result);
      };
      const onOk = function () { done(true); };
      const onCancel = function () { done(false); };
      const onBackdrop = function (e) { if (e.target === overlay) done(false); };
      const onKey = function (e) { if (e.key === 'Escape') done(false); };
      okBtn.addEventListener('click', onOk);
      cancelBtn.addEventListener('click', onCancel);
      overlay.addEventListener('click', onBackdrop);
      document.addEventListener('keydown', onKey);
    });
  };

  // Loading bar while the next page loads. If the server is slow to answer
  // (the free host waking up), a message explains the wait.
  (function () {
    const bar = document.getElementById('uiLoadBar');
    let slowTimer = null, slowToast = null;

    function start() {
      if (!bar || bar.classList.contains('is-on')) return;
      bar.classList.add('is-on');
      bar.style.width = '70%';
      slowTimer = setTimeout(function () {
        if (typeof uiToast === 'function') uiToast('Waking up the server, this can take up to a minute. Please wait...', 'info');
      }, 4000);
    }

    function stop() {
      clearTimeout(slowTimer);
      if (!bar) return;
      bar.style.width = '100%';
      setTimeout(function () { bar.classList.remove('is-on'); bar.style.width = '0'; }, 300);
    }

    // Links that open another page on this site.
    document.addEventListener('click', function (e) {
      const a = e.target.closest('a[href]');
      if (!a || e.defaultPrevented || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
      if (a.target && a.target !== '_self') return;
      if (a.hasAttribute('download') || a.origin !== location.origin) return;
      if (/^(mailto:|tel:|javascript:)/i.test(a.getAttribute('href'))) return;
      if (a.pathname === location.pathname && a.search === location.search) return; // same page (anchor)
      if (/\/receipt|\/avatar|\.pdf$/i.test(a.pathname)) return; // downloads and images stay on the page
      start();
    });

    // Forms that submit to the server.
    document.addEventListener('submit', function (e) {
      if (e.defaultPrevented || (e.target.target && e.target.target !== '_self')) return;
      start();
    });

    // Back/forward from the browser cache: clear a stuck bar.
    window.addEventListener('pageshow', function () { clearTimeout(slowTimer); if (bar) { bar.classList.remove('is-on'); bar.style.width = '0'; } });
    window.addEventListener('pagehide', function () { clearTimeout(slowTimer); });
  })();

  // Forms or buttons with data-confirm ask before submitting.
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('button[data-confirm], input[type="submit"][data-confirm]');
    if (!btn || !btn.form || btn.form.dataset.uiConfirmed === '1') return;
    e.preventDefault();
    e.stopPropagation();
    uiConfirm({ message: btn.dataset.confirm }).then(function (ok) {
      if (!ok) return;
      btn.form.dataset.uiConfirmed = '1';
      btn.form.requestSubmit ? btn.form.requestSubmit(btn) : btn.form.submit();
    });
  }, true);

  document.addEventListener('submit', function (e) {
    const form = e.target;
    if (!form.matches('form[data-confirm]') || form.dataset.uiConfirmed === '1') return;
    e.preventDefault();
    e.stopPropagation(); // other submit handlers wait for the real submit
    const submitter = e.submitter;
    uiConfirm({ message: form.dataset.confirm }).then(function (ok) {
      if (!ok) return;
      form.dataset.uiConfirmed = '1';
      form.requestSubmit ? form.requestSubmit(submitter || undefined) : form.submit();
    });
  }, true);
})();
</script>
@endonce
