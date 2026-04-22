@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $activePage = $activePage ?? 'rules';
  $pageLang = $lang;

  $routeWithLang = static function (string $path, array $query = []) use ($lang): string {
      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }
      $qs = http_build_query(array_filter($query, static fn ($v) => $v !== null && $v !== ''));
      return $path . ($qs !== '' ? ('?' . $qs) : '');
  };

  // Anchors below are a PUBLIC contract (Cluster B Content Contract §2) —
  // keep them stable: #general, #borrowing, #digital, #conduct, #penalties.
  $header      = $rules['header'][$lang];
  $toc         = $rules['toc'][$lang];
  $general     = $rules['general'][$lang];
  $borrowing   = $rules['borrowing'][$lang];
  $digital     = $rules['digital'][$lang];
  $conduct     = $rules['conduct'][$lang];
  $penalties   = $rules['penalties'][$lang];
  $footerMeta  = $rules['footer_meta'][$lang];
  $lastReviewedAt = $rules['last_reviewed_at'] ?? null;

  $chrome = [
      'ru' => [
          'title' => 'Правила пользования библиотекой — KazUTB Smart Library',
          'toc_aria' => 'Содержание документа',
          'effective_on' => 'Действует с',
          'reviewed_on' => 'Последняя проверка',
          'on_this_page' => 'На этой странице',
      ],
      'kk' => [
          'title' => 'Кітапхананы пайдалану ережелері — KazUTB Smart Library',
          'toc_aria' => 'Құжат мазмұны',
          'effective_on' => 'Күшіне енген күні',
          'reviewed_on' => 'Соңғы тексеру',
          'on_this_page' => 'Осы бетте',
      ],
      'en' => [
          'title' => 'Library Usage Rules — KazUTB Smart Library',
          'toc_aria' => 'Table of contents',
          'effective_on' => 'Effective from',
          'reviewed_on' => 'Last reviewed',
          'on_this_page' => 'On this page',
      ],
  ][$lang];
@endphp

@section('title', $chrome['title'])

@section('content')
  <section class="page-section rules-header" data-section="rules-header">
    <div class="container">
      <div class="max-w-prose">
        <div class="eyebrow eyebrow--cyan">{{ $header['eyebrow'] }}</div>
        <h1 class="heading-xl rules-headline">
          {{ $header['headline'] }}
          @if(!empty($header['subtitle_secondary_lang']))
            <span class="rules-headline-secondary">({{ $header['subtitle_secondary_lang'] }})</span>
          @endif
        </h1>
        <p class="text-body">{{ $header['preamble'] }}</p>
        <dl class="rules-meta" data-test-id="rules-meta">
          <div class="rules-meta-row">
            <dt>{{ $header['effective_label'] }}</dt>
            <dd><time datetime="{{ $header['effective_date'] }}" data-test-id="rules-effective-date">{{ $header['effective_date'] }}</time></dd>
          </div>
          @if($lastReviewedAt)
            <div class="rules-meta-row">
              <dt>{{ $header['reviewed_label'] }}</dt>
              <dd><time datetime="{{ $lastReviewedAt }}" data-test-id="rules-last-reviewed">{{ $lastReviewedAt }}</time></dd>
            </div>
          @endif
        </dl>
      </div>
    </div>
  </section>

  <div class="rules-layout">
    <div class="container">
      <div class="rules-layout-grid">
        {{-- TOC --}}
        <aside
          class="rules-toc"
          data-section="rules-toc"
          aria-label="{{ $chrome['toc_aria'] }}"
        >
          <div class="rules-toc-inner">
            <p class="rules-toc-label">{{ $toc['label'] }}</p>
            <nav>
              <ol class="rules-toc-list">
                @foreach($toc['items'] as $item)
                  <li>
                    <a href="{{ $item['href'] }}" class="rules-toc-link">{{ $item['label'] }}</a>
                  </li>
                @endforeach
              </ol>
            </nav>
          </div>
        </aside>

        <article class="rules-article">
          {{-- 1. General --}}
          <section id="general" class="rules-section" data-section="rules-general">
            <header class="rules-section-head">
              <div class="eyebrow eyebrow--teal">{{ $general['eyebrow'] }}</div>
              <h2 class="heading-lg">{{ $general['title'] }}</h2>
              <p class="text-body rules-section-lede">{{ $general['lede'] }}</p>
            </header>
            <ul class="rules-list">
              @foreach($general['items'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </section>

          {{-- 2. Borrowing --}}
          <section id="borrowing" class="rules-section" data-section="rules-borrowing">
            <header class="rules-section-head">
              <div class="eyebrow eyebrow--teal">{{ $borrowing['eyebrow'] }}</div>
              <h2 class="heading-lg">{{ $borrowing['title'] }}</h2>
              <p class="text-body rules-section-lede">{{ $borrowing['lede'] }}</p>
            </header>
            <div class="rules-borrowing-grid">
              @foreach($borrowing['groups'] as $group)
                <div class="rules-borrowing-card" data-audience-slot>
                  <span class="material-symbols-outlined rules-borrowing-icon" aria-hidden="true">{{ $group['icon'] }}</span>
                  <h3 class="rules-borrowing-audience">{{ $group['audience'] }}</h3>
                  <dl class="rules-borrowing-rows">
                    @foreach($group['rows'] as $row)
                      <div class="rules-borrowing-row">
                        <dt>{{ $row['label'] }}</dt>
                        <dd>{{ $row['value'] }}</dd>
                      </div>
                    @endforeach
                  </dl>
                </div>
              @endforeach
            </div>
            @if(!empty($borrowing['notes']))
              <ul class="rules-list rules-notes">
                @foreach($borrowing['notes'] as $note)
                  <li>{{ $note }}</li>
                @endforeach
              </ul>
            @endif
          </section>

          {{-- 3. Digital access --}}
          <section id="digital" class="rules-section" data-section="rules-digital-access">
            <header class="rules-section-head">
              <div class="eyebrow eyebrow--teal">{{ $digital['eyebrow'] }}</div>
              <h2 class="heading-lg">{{ $digital['title'] }}</h2>
              <p class="text-body rules-section-lede">{{ $digital['lede'] }}</p>
            </header>
            <ul class="rules-list">
              @foreach($digital['items'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </section>

          {{-- 4. Conduct --}}
          <section id="conduct" class="rules-section" data-section="rules-conduct">
            <header class="rules-section-head">
              <div class="eyebrow eyebrow--teal">{{ $conduct['eyebrow'] }}</div>
              <h2 class="heading-lg">{{ $conduct['title'] }}</h2>
              <p class="text-body rules-section-lede">{{ $conduct['lede'] }}</p>
            </header>
            <ul class="rules-list">
              @foreach($conduct['items'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </section>

          {{-- 5. Penalties --}}
          <section id="penalties" class="rules-section" data-section="rules-penalties">
            <header class="rules-section-head">
              <div class="eyebrow eyebrow--teal">{{ $penalties['eyebrow'] }}</div>
              <h2 class="heading-lg">{{ $penalties['title'] }}</h2>
              <p class="text-body rules-section-lede">{{ $penalties['lede'] }}</p>
            </header>
            <ul class="rules-list">
              @foreach($penalties['items'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>

            @if(!empty($penalties['suspension_ladder']))
              <div class="rules-ladder" data-test-id="rules-suspension-ladder">
                <p class="rules-ladder-label">{{ $penalties['suspension_ladder_label'] }}</p>
                <ol class="rules-ladder-list">
                  @foreach($penalties['suspension_ladder'] as $step)
                    <li>
                      <span class="rules-ladder-level">{{ $step['level'] }}</span>
                      <span class="rules-ladder-detail">{{ $step['detail'] }}</span>
                    </li>
                  @endforeach
                </ol>
              </div>
            @endif

            @if(!empty($penalties['appeal_text']))
              <div class="rules-appeal" data-test-id="rules-appeal">
                <p class="rules-appeal-label">{{ $penalties['appeal_label'] }}</p>
                <p class="rules-appeal-body">{{ $penalties['appeal_text'] }}</p>
              </div>
            @endif
          </section>

          {{-- Footer meta --}}
          <section class="rules-section rules-footer-meta" data-section="rules-footer-meta">
            <div class="rules-footer-meta-card">
              <div class="eyebrow eyebrow--cyan">{{ $footerMeta['eyebrow'] }}</div>
              <h2 class="heading-lg">{{ $footerMeta['heading'] }}</h2>
              <p class="text-body">{{ $footerMeta['body'] }}</p>
              <div class="rules-footer-meta-actions">
                <a
                  href="{{ $routeWithLang($footerMeta['contacts_href']) }}"
                  class="btn btn-primary rules-footer-meta-link"
                  data-test-id="rules-contacts-link"
                >
                  {{ $footerMeta['contacts_label'] }}
                  <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                </a>
                <a
                  href="{{ $routeWithLang($footerMeta['leadership_href']) }}"
                  class="rules-footer-meta-secondary"
                  data-test-id="rules-leadership-link"
                >
                  {{ $footerMeta['leadership_label'] }}
                </a>
              </div>
              <p class="rules-footer-meta-version">
                <span>{{ $footerMeta['version_label'] }}:</span> {{ $footerMeta['version_value'] }}
              </p>
            </div>
          </section>
        </article>
      </div>
    </div>
  </div>
@endsection

@section('head')
<style>
  .rules-header { padding-top: 48px; }
  .rules-headline { display: block; }
  .rules-headline-secondary {
    display: block;
    margin-top: 10px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.375rem;
    font-weight: 500;
    color: var(--muted, #43474e);
    letter-spacing: -0.005em;
  }
  .rules-meta {
    margin-top: 24px;
    display: grid;
    gap: 6px;
  }
  .rules-meta-row {
    display: inline-flex;
    align-items: baseline;
    gap: 8px;
    font-family: 'Manrope', sans-serif;
    font-size: 0.8125rem;
    color: var(--muted, #43474e);
  }
  .rules-meta-row dt {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    font-size: 0.6875rem;
    color: var(--teal, #006a6a);
    margin: 0;
  }
  .rules-meta-row dd { margin: 0; }

  .rules-layout { padding: 24px 0 72px; }
  .rules-layout-grid {
    display: grid;
    grid-template-columns: 240px minmax(0, 1fr);
    gap: 56px;
    align-items: start;
  }

  .rules-toc {
    position: sticky;
    top: 112px;
    align-self: start;
  }
  .rules-toc-inner {
    padding: 20px 18px 20px 20px;
    border-left: 1px solid rgba(195, 198, 209, 0.55);
  }
  .rules-toc-label {
    margin: 0 0 14px;
    font-family: 'Manrope', sans-serif;
    font-size: 0.6875rem;
    font-weight: 800;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--teal, #006a6a);
  }
  .rules-toc-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    gap: 12px;
  }
  .rules-toc-link {
    display: inline-block;
    font-family: 'Manrope', sans-serif;
    font-size: 0.9375rem;
    line-height: 1.4;
    color: var(--muted, #43474e);
    text-decoration: none;
    transition: color 0.2s ease;
  }
  .rules-toc-link:hover,
  .rules-toc-link:focus-visible {
    color: var(--teal, #006a6a);
  }

  .rules-article { display: grid; gap: 56px; }
  .rules-section { scroll-margin-top: 112px; }
  .rules-section-head { margin-bottom: 20px; }
  .rules-section-head .eyebrow,
  .rules-section-head .heading-lg { margin-bottom: 10px; }
  .rules-section-lede {
    margin: 6px 0 0;
    color: var(--muted, #43474e);
    max-width: 62ch;
  }

  .rules-list {
    list-style: disc;
    padding-left: 22px;
    margin: 0;
    display: grid;
    gap: 10px;
    font-family: 'Manrope', sans-serif;
    font-size: 0.9375rem;
    line-height: 1.65;
    color: var(--on-surface, #191c1d);
  }
  .rules-list li::marker { color: var(--teal, #006a6a); }
  .rules-notes {
    margin-top: 20px;
    padding: 18px 22px 18px 40px;
    background: var(--surface-container-low, #f3f4f5);
    border-radius: var(--radius-lg, 16px);
    border: 1px solid rgba(195, 198, 209, 0.4);
    font-size: 0.875rem;
    color: var(--muted, #43474e);
  }

  .rules-borrowing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 18px;
    margin-top: 4px;
  }
  .rules-borrowing-card {
    padding: 24px 22px;
    background: #fff;
    border: 1px solid rgba(195, 198, 209, 0.55);
    border-radius: var(--radius-lg, 16px);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .rules-borrowing-card:hover {
    border-color: rgba(0, 106, 106, 0.3);
    box-shadow: 0 14px 28px rgba(0, 6, 19, 0.06);
  }
  .rules-borrowing-icon {
    font-size: 1.75rem;
    color: var(--teal, #006a6a);
    margin-bottom: 10px;
  }
  .rules-borrowing-audience {
    margin: 0 0 14px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.25rem;
    line-height: 1.25;
    color: var(--blue, #000613);
  }
  .rules-borrowing-rows {
    margin: 0;
    display: grid;
    gap: 6px;
  }
  .rules-borrowing-row {
    display: grid;
    grid-template-columns: minmax(108px, 38%) minmax(0, 1fr);
    gap: 8px;
    font-family: 'Manrope', sans-serif;
    font-size: 0.875rem;
  }
  .rules-borrowing-row dt {
    margin: 0;
    color: var(--muted, #43474e);
    font-weight: 600;
  }
  .rules-borrowing-row dd {
    margin: 0;
    color: var(--on-surface, #191c1d);
  }

  .rules-ladder {
    margin-top: 24px;
    padding: 22px 24px;
    background: var(--surface-container-low, #f3f4f5);
    border-radius: var(--radius-lg, 16px);
    border: 1px solid rgba(195, 198, 209, 0.45);
  }
  .rules-ladder-label {
    margin: 0 0 12px;
    font-family: 'Manrope', sans-serif;
    font-size: 0.6875rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--teal, #006a6a);
  }
  .rules-ladder-list {
    list-style: none;
    counter-reset: ladder;
    margin: 0;
    padding: 0;
    display: grid;
    gap: 10px;
  }
  .rules-ladder-list li {
    counter-increment: ladder;
    display: grid;
    grid-template-columns: 28px minmax(0, 1fr);
    gap: 14px;
    align-items: baseline;
  }
  .rules-ladder-list li::before {
    content: counter(ladder);
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 1px solid rgba(0, 106, 106, 0.35);
    border-radius: 999px;
    color: var(--teal, #006a6a);
    font-family: 'Manrope', sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
  }
  .rules-ladder-level {
    display: block;
    font-family: 'Manrope', sans-serif;
    font-size: 0.9375rem;
    font-weight: 700;
    color: var(--blue, #000613);
  }
  .rules-ladder-detail {
    display: block;
    font-family: 'Manrope', sans-serif;
    font-size: 0.875rem;
    line-height: 1.55;
    color: var(--muted, #43474e);
  }

  .rules-appeal {
    margin-top: 20px;
    padding: 18px 20px;
    border-left: 3px solid var(--teal, #006a6a);
    background: rgba(0, 106, 106, 0.04);
    border-radius: 0 12px 12px 0;
  }
  .rules-appeal-label {
    margin: 0 0 6px;
    font-family: 'Manrope', sans-serif;
    font-size: 0.6875rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--teal, #006a6a);
  }
  .rules-appeal-body {
    margin: 0;
    font-family: 'Manrope', sans-serif;
    font-size: 0.9375rem;
    line-height: 1.65;
    color: var(--on-surface, #191c1d);
  }

  .rules-footer-meta-card {
    padding: 32px 28px;
    background: linear-gradient(180deg, #ffffff 0%, var(--surface-container-low, #f3f4f5) 100%);
    border: 1px solid rgba(195, 198, 209, 0.5);
    border-radius: var(--radius-lg, 16px);
  }
  .rules-footer-meta-card .eyebrow,
  .rules-footer-meta-card .heading-lg { margin-bottom: 10px; }
  .rules-footer-meta-card .text-body { margin: 0 0 20px; max-width: 62ch; }
  .rules-footer-meta-actions {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
  }
  .rules-footer-meta-link { display: inline-flex; align-items: center; gap: 8px; }
  .rules-footer-meta-secondary {
    font-family: 'Manrope', sans-serif;
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--teal, #006a6a);
    text-decoration: none;
    border-bottom: 1px solid rgba(0, 106, 106, 0.35);
    padding-bottom: 2px;
    transition: color 0.2s ease, border-color 0.2s ease;
  }
  .rules-footer-meta-secondary:hover,
  .rules-footer-meta-secondary:focus-visible {
    color: var(--blue, #000613);
    border-color: rgba(0, 6, 19, 0.4);
  }
  .rules-footer-meta-version {
    margin: 18px 0 0;
    font-family: 'Manrope', sans-serif;
    font-size: 0.75rem;
    color: var(--muted, #43474e);
  }
  .rules-footer-meta-version span {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: var(--teal, #006a6a);
    font-size: 0.6875rem;
    margin-right: 4px;
  }

  @media (max-width: 960px) {
    .rules-layout-grid {
      grid-template-columns: minmax(0, 1fr);
      gap: 32px;
    }
    .rules-toc {
      position: static;
    }
    .rules-toc-inner {
      padding: 16px 18px;
      border-left: none;
      background: var(--surface-container-low, #f3f4f5);
      border-radius: var(--radius-lg, 16px);
    }
    .rules-article { gap: 48px; }
    .rules-headline-secondary { font-size: 1.125rem; }
  }
</style>
@endsection
