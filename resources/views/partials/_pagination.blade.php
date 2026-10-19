{{-- Page links for an admin table: "Showing 1–20 of 57" with Prev / numbers / Next. --}}
@if($paginator->total() > 0)
<nav class="pg-bar" aria-label="Pages">
  <span class="pg-info">Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}</span>
  @if($paginator->hasPages())
    <div class="pg-links">
      @if($paginator->onFirstPage())
        <span class="pg-link is-disabled">‹ Prev</span>
      @else
        <a class="pg-link" href="{{ $paginator->previousPageUrl() }}">‹ Prev</a>
      @endif

      @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $pages = collect([1, $last, $current - 1, $current, $current + 1])->filter(fn ($p) => $p >= 1 && $p <= $last)->unique()->sort()->values();
      @endphp
      @foreach($pages as $i => $page)
        @if($i > 0 && $page - $pages[$i - 1] > 1)<span class="pg-gap">…</span>@endif
        @if($page === $current)
          <span class="pg-link is-current" aria-current="page">{{ $page }}</span>
        @else
          <a class="pg-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
        @endif
      @endforeach

      @if($paginator->hasMorePages())
        <a class="pg-link" href="{{ $paginator->nextPageUrl() }}">Next ›</a>
      @else
        <span class="pg-link is-disabled">Next ›</span>
      @endif
    </div>
  @endif
</nav>
@endif

@once
<style>
  .pg-bar { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 14px 24px; border-top: 1px solid #e8dcc8; font-family: 'Jost', sans-serif; }
  .pg-info { font-size: 13px; color: #8a6a40; }
  .pg-links { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
  .pg-link { min-width: 34px; text-align: center; padding: 6px 10px; border: 1px solid #d4c4a0; border-radius: 6px; font-size: 13px; font-weight: 600; color: #5c4a33; text-decoration: none; background: transparent; }
  a.pg-link:hover { border-color: #c9a84c; color: #2c1a0e; }
  .pg-link.is-current { background: #2c1a0e; border-color: #2c1a0e; color: #fff; }
  .pg-link.is-disabled { opacity: .45; }
  .pg-gap { color: #8a6a40; padding: 0 2px; }
</style>
@endonce
