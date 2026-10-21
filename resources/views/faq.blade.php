<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQs - LorDane's Place</title>
<meta name="description" content="Frequently asked questions about LorDane's Place: packages, booking, payment, cancellation, location and hours.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
  /* FAQ page */
  .faq-wrap { max-width: 860px; margin: 0 auto; }
  .faq-search { width: 100%; box-sizing: border-box; background: #fff; border: 1px solid rgba(184,134,11,0.3); border-radius: 10px; padding: 14px 18px; font: 15px 'Jost', sans-serif; color: #1a1208; outline: none; }
  .faq-search:focus { border-color: #b8860b; }
  .faq-cats { display: flex; gap: 8px; flex-wrap: wrap; margin: 16px 0 28px; }
  .faq-cat { border: 1px solid rgba(184,134,11,0.3); background: transparent; color: #5c4a33; border-radius: 999px; padding: 7px 14px; font: 600 13px 'Jost', sans-serif; cursor: pointer; }
  .faq-cat.is-active { background: #2c1a0e; border-color: #2c1a0e; color: #fff; }
  .faq-group h2 { font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 700; color: #1a1208; margin: 28px 0 12px; }
  .faq-item { background: #fffcf5; border: 1px solid rgba(184,134,11,0.2); border-radius: 10px; margin-bottom: 10px; overflow: hidden; }
  .faq-item summary { list-style: none; cursor: pointer; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; gap: 14px; font: 600 16px 'Jost', sans-serif; color: #1a1208; }
  .faq-item summary::-webkit-details-marker { display: none; }
  .faq-item summary::after { content: '+'; font-size: 22px; font-weight: 400; color: #b8860b; flex-shrink: 0; transition: transform .2s; }
  .faq-item[open] summary::after { transform: rotate(45deg); }
  .faq-item[open] { border-color: #b8860b; }
  .faq-answer { padding: 0 20px 18px; font: 15px/1.7 'Jost', sans-serif; color: #5c4a33; }
  .faq-empty { text-align: center; color: #8f7c62; padding: 30px 0; display: none; }
  .faq-help { margin-top: 40px; background: #2c1a0e; color: #f5ecd8; border-radius: 14px; padding: 28px; display: grid; gap: 18px; }
  .faq-help h3 { font-family: 'Cormorant Garamond', serif; font-size: 28px; margin: 0; color: #fff; }
  .faq-help p { margin: 0; color: rgba(245,236,216,0.75); font-size: 15px; }
  .faq-contacts { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
  .faq-contact { background: rgba(255,255,255,0.06); border: 1px solid rgba(201,168,76,0.25); border-radius: 10px; padding: 14px 16px; color: #f5ecd8; text-decoration: none; display: block; }
  .faq-contact span { display: block; font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: #c9a84c; font-weight: 700; margin-bottom: 4px; }
  .faq-contact b { font-size: 16px; font-weight: 600; }
  .faq-contact small { display: block; color: rgba(245,236,216,0.6); font-size: 12px; margin-top: 2px; }
  button.faq-contact { text-align: left; font: inherit; cursor: pointer; }
</style>
</head>
<body class="bg-off-white text-warm-black font-body">

@include('partials._navbar')

<!-- FAQ HERO -->
<section class="h-[280px] md:h-[350px] flex justify-center items-center text-center text-white px-5 relative"
         style="background: linear-gradient(rgba(26,18,8,0.5), rgba(26,18,8,0.7)), url('{{ asset('images/LORDANES_BG.jpg') }}'); background-size: cover; background-position: center;">
  <div class="gold-divider absolute bottom-0 left-0 right-0"></div>
  <div>
    <p class="text-[11px] md:text-[12px] tracking-[4px] text-gold-light font-bold mb-3">HELP CENTER</p>
    <h1 class="font-heading text-[38px] md:text-[52px] font-bold text-white mb-2">Frequently Asked <span class="italic text-gold-light">Questions</span></h1>
    <p class="text-white/60 text-[15px] md:text-[16px] tracking-wide"><a href="{{ route('home') }}" class="hover:text-gold-light transition-colors">Home</a> / FAQs</p>
  </div>
</section>

<section class="py-[50px] md:py-[70px] px-[5%] lg:px-[8%] bg-off-white">
  <div class="faq-wrap">
    <input type="search" id="faqSearch" class="faq-search" placeholder="Search a question, e.g. down payment, reschedule, pool..." aria-label="Search FAQs">

    @php $groups = $faqs->groupBy('category')->sortBy(fn ($items, $cat) => array_search($cat, \App\Models\Faq::CATEGORIES) === false ? 99 : array_search($cat, \App\Models\Faq::CATEGORIES)); @endphp

    <div class="faq-cats" role="tablist" aria-label="FAQ categories">
      <button type="button" class="faq-cat is-active" data-cat="">All</button>
      @foreach($groups->keys() as $cat)
        <button type="button" class="faq-cat" data-cat="{{ $cat }}">{{ $cat }}</button>
      @endforeach
    </div>

    @forelse($groups as $cat => $items)
      <div class="faq-group" data-cat="{{ $cat }}">
        <h2>{{ $cat }}</h2>
        @foreach($items as $faq)
          <details class="faq-item" id="faq-{{ $faq->id }}" data-text="{{ mb_strtolower($faq->question . ' ' . $faq->answer) }}">
            <summary>{{ $faq->question }}</summary>
            <div class="faq-answer">{!! nl2br(preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', e($faq->answer))) !!}</div>
          </details>
        @endforeach
      </div>
    @empty
      <p class="faq-empty" style="display:block;">No FAQs yet.</p>
    @endforelse
    <p class="faq-empty" id="faqNoMatch">No questions match your search.</p>

    {{-- Contact options for questions not answered here --}}
    <div class="faq-help">
      <div>
        <h3>Still have a question?</h3>
        <p>Our team is happy to help with anything not covered here.</p>
      </div>
      <div class="faq-contacts">
        <a class="faq-contact" href="tel:09177455049"><span>Call or text</span><b>0917 745 5049</b><small>Available 8:00 AM – 8:00 PM</small></a>
        <a class="faq-contact" href="mailto:lordanesplace@gmail.com"><span>Email</span><b>lordanesplace@gmail.com</b><small>Reply within 24 hours</small></a>
        <button type="button" class="faq-contact" onclick="if (typeof toggleChat === 'function' && !chatOpen) toggleChat();"><span>Live chat</span><b>Chat with us</b><small>Message our admin here</small></button>
      </div>
    </div>
  </div>
</section>

@include('partials._footer')
@include('chat-assistant')

<script>
  // Search and category filter for the FAQ list.
  (function () {
    const search = document.getElementById('faqSearch');
    const cats = Array.from(document.querySelectorAll('.faq-cat'));
    let activeCat = '';

    function apply() {
      const q = search.value.trim().toLowerCase();
      let shown = 0;
      document.querySelectorAll('.faq-group').forEach(function (group) {
        let groupShown = 0;
        group.querySelectorAll('.faq-item').forEach(function (item) {
          const ok = (!activeCat || group.dataset.cat === activeCat) && (!q || item.dataset.text.includes(q));
          item.style.display = ok ? '' : 'none';
          if (ok) groupShown++;
        });
        group.style.display = groupShown ? '' : 'none';
        shown += groupShown;
      });
      document.getElementById('faqNoMatch').style.display = shown || !document.querySelector('.faq-item') ? 'none' : 'block';
    }

    search.addEventListener('input', apply);
    cats.forEach(function (btn) {
      btn.addEventListener('click', function () {
        activeCat = btn.dataset.cat;
        cats.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
        apply();
      });
    });

    // A link like /faq#faq-3 opens that question.
    if (location.hash.startsWith('#faq-')) {
      const item = document.querySelector(location.hash);
      if (item) { item.open = true; item.scrollIntoView({ block: 'center' }); }
    }
  })();
</script>
</body>
</html>
