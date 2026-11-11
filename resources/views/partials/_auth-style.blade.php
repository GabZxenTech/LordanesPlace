{{-- Shared look of the login and sign up pages (same fonts and sizes). --}}
<style>
    /* Sign up: hero text on the left, form card on the right (system palette: warm black and gold) */
    .su-page { --su-card: rgba(255,255,255,0.05); --su-line: rgba(201,168,76,0.28); --su-gold: #b8860b; --su-gold-mid: #c9a84c; --su-gold-light: #e8c96d;
      min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 32px 20px; font-family: 'Jost', sans-serif; color: #fff;
      background: linear-gradient(rgba(26,18,8,0.7), rgba(26,18,8,0.7)), url('{{ asset('images/LORDANES_BG.jpg') }}') center / cover no-repeat; }
    .su-grid { width: 100%; max-width: 1150px; display: grid; grid-template-columns: minmax(0, 1fr) 500px; gap: 40px; align-items: center; }

    .su-hero { padding: 0 8px; }
    .su-eyebrow { margin: 0 0 16px; font-size: 14px; letter-spacing: 4px; font-weight: 700; color: var(--su-gold-light); }
    .su-hero h1 { font-family: 'Cormorant Garamond', serif; font-size: 58px; line-height: 1.1; margin: 0 0 16px; font-weight: 400; }
    .su-hero h1 em { color: var(--su-gold-light); font-style: italic; }
    .su-hero p { max-width: 560px; font-size: 18px; line-height: 1.8; color: rgba(255,255,255,0.7); margin: 0; }

    .su-card { background: rgba(26,18,8,0.82); backdrop-filter: blur(4px); border: 1px solid rgba(184,134,11,0.25); border-radius: 18px; padding: 32px 30px; box-shadow: 0 24px 60px rgba(0,0,0,0.45); }
    .su-card h2 { font-family: 'Cormorant Garamond', serif; font-size: 36px; font-weight: 700; margin: 0 0 4px; line-height: 1.1; }
    .su-sub { margin: 0 0 20px; font-size: 16px; color: rgba(255,255,255,0.62); }

    .su-google { display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%; padding: 13px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2); background: #fff; color: #1a1208; font: 500 17px 'Jost', sans-serif; text-decoration: none; transition: background .2s; }
    .su-google:hover { background: #f3f4f6; }
    .su-google img { width: 20px; height: 20px; }
    .su-gnote { margin: 10px 0 0; font-size: 13px; color: rgba(255,255,255,0.5); text-align: center; }
    .su-gnote a, .su-terms a { color: var(--su-gold-light); text-decoration: underline; }
    .su-or { display: flex; align-items: center; gap: 14px; margin: 18px 0; font-size: 14px; color: rgba(255,255,255,0.55); }
    .su-or::before, .su-or::after { content: ""; flex: 1; height: 1px; background: var(--su-line); }

    .su-err { background: rgba(239,68,68,0.14); border: 1px solid #ef4444; color: #fca5a5; padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; font-size: 15px; }
    .su-err ul { list-style: none; margin: 0; padding: 0; }
    .su-field { margin-bottom: 16px; }
    .su-field label { display: block; margin-bottom: 7px; font-size: 13px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: rgba(255,255,255,0.85); }
    .su-input { position: relative; display: flex; align-items: center; }
    .su-input input { width: 100%; padding: 13px 16px; border-radius: 10px; border: 1px solid var(--su-line); background: var(--su-card); color: #fff; font: 400 17px 'Jost', sans-serif; outline: none; transition: border-color .2s, box-shadow .2s; }
    .su-input input::placeholder { color: rgba(255,255,255,0.38); }
    .su-input input:focus { border-color: var(--su-gold-mid); box-shadow: 0 0 0 3px rgba(201,168,76,0.18); }
    .su-input.has-eye input { padding-right: 48px; }
    .su-eye { position: absolute; right: 14px; display: flex; background: none; border: 0; padding: 0; cursor: pointer; color: rgba(255,255,255,0.5); }
    .su-eye:hover { color: var(--su-gold-light); }
    .su-eye svg { width: 22px; height: 22px; }
    .su-fe { display: block; margin-top: 6px; font-size: 14px; color: #fca5a5; }

    .su-bars { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin: 12px 0 10px; }
    .su-bars span { height: 6px; border-radius: 99px; background: rgba(255,255,255,0.14); transition: background .2s; }
    .su-rules { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 6px 14px; }
    .su-rules li { display: flex; align-items: center; gap: 8px; font-size: 14px; color: rgba(255,255,255,0.55); transition: color .15s; }
    .su-rules i { width: 16px; height: 16px; border-radius: 50%; border: 1.5px solid currentColor; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; font-style: normal; font-size: 10px; line-height: 1; }
    .su-rules li.ok { color: #4ade80; }
    .su-rules li.ok i { background: #4ade80; border-color: #4ade80; color: #0b2a14; }
    .su-rules li.ok i::after { content: "\2713"; font-weight: 700; }
    .su-match { margin: 8px 0 0; font-size: 14px; }
    .su-match.ok { color: #4ade80; }
    .su-match.bad { color: #f87171; }

    .su-terms { display: flex; align-items: flex-start; gap: 10px; margin: 4px 0 14px; font-size: 15px; line-height: 1.5; color: rgba(255,255,255,0.72); cursor: pointer; }
    .su-terms input { width: 18px; height: 18px; margin-top: 2px; flex-shrink: 0; accent-color: #b8860b; }
    .su-captcha { margin: 12px 0 16px; }
    .su-submit { width: 100%; padding: 14px; border: 0; border-radius: 10px; background: var(--su-gold); color: #fff; font: 700 17px 'Jost', sans-serif; letter-spacing: .5px; cursor: pointer; transition: background .2s, transform .2s; }
    .su-submit:hover { background: var(--su-gold-mid); color: #1a1208; transform: translateY(-1px); }
    .su-alt { margin: 20px 0 8px; text-align: center; font-size: 16px; color: rgba(255,255,255,0.62); }
    .su-alt a, .su-back { color: var(--su-gold-light); font-weight: 700; text-decoration: none; }
    .su-alt a:hover, .su-back:hover { text-decoration: underline; }
    .su-back { display: inline-block; font-size: 16px; font-weight: 500; }

    @media (max-width: 1000px) {
      .su-grid { grid-template-columns: minmax(0, 1fr); gap: 24px; max-width: 520px; }
      .su-hero { text-align: center; }
      .su-hero h1 { font-size: 40px; }
      .su-hero p { margin: 0 auto; font-size: 16px; }
    }
    @media (max-width: 480px) {
      .su-card { padding: 24px 18px; }
      .su-rules { grid-template-columns: 1fr; }
    }

    /* Login: remember me and forgot password row */
    .su-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin: 2px 0 14px; font-size: 15px; }
    .su-row label { display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.7); cursor: pointer; }
    .su-row input { width: 18px; height: 18px; accent-color: #b8860b; }
    .su-row a { color: var(--su-gold-light); text-decoration: none; font-weight: 600; }
    .su-row a:hover { text-decoration: underline; }
    .su-ok { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.35); color: #4ade80; padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; font-size: 15px; }
</style>
