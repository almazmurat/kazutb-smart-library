@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $routeWithLang = static function (string $path, array $query = []) use ($lang): string {
      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }
      $qs = http_build_query(array_filter($query, static fn ($v) => $v !== null && $v !== ''));
      return $path . ($qs !== '' ? ('?' . $qs) : '');
  };

  $chrome = [
      'ru' => [
          'back' => 'Вернуться к новостям',
          'related_heading' => 'Связанные материалы',
          'title_suffix' => 'KazUTB Smart Library',
      ],
      'kk' => [
          'back' => 'Жаңалықтарға оралу',
          'related_heading' => 'Қатысты материалдар',
          'title_suffix' => 'KazUTB Smart Library',
      ],
      'en' => [
          'back' => 'Back to news',
          'related_heading' => 'Related articles',
          'title_suffix' => 'KazUTB Smart Library',
      ],
  ][$lang];
@endphp

@section('title', $article['title'][$lang] . ' — ' . $chrome['title_suffix'])

@section('content')
  <section class="page-section news-detail" data-section="news-detail">
    <div class="container">
      <div class="news-detail-grid">
        <article class="news-detail-article">
          <a href="{{ $routeWithLang('/news') }}" class="news-back-link">
            <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
            {{ $chrome['back'] }}
          </a>

          <header class="news-detail-head">
            <div class="news-meta">
              <span class="news-meta-badge">{{ $article['category'][$lang] }}</span>
              <time datetime="{{ $article['published_at'] }}" class="news-meta-date">{{ $article['published_display'][$lang] }}</time>
            </div>
            <h1 class="news-detail-title">{{ $article['title'][$lang] }}</h1>
          </header>

          @if(!empty($article['hero']['image']))
            <figure class="news-detail-hero">
              <img src="{{ asset($article['hero']['image']) }}" alt="{{ $article['hero']['alt'][$lang] ?? '' }}" loading="lazy" />
            </figure>
          @endif

          <div class="news-detail-body">
            @foreach($article['body'][$lang] as $block)
              @switch($block['type'])
                @case('lead')
                  <p class="news-body-lead">{{ $block['text'] }}</p>
                  @break
                @case('h2')
                  <h2 class="news-body-h2">{{ $block['text'] }}</h2>
                  @break
                @case('list')
                  <ul class="news-body-list">
                    @foreach($block['items'] as $item)
                      <li>
                        <span class="material-symbols-outlined" aria-hidden="true">check_circle</span>
                        <span><strong>{{ $item['term'] }}:</strong> {{ $item['text'] }}</span>
                      </li>
                    @endforeach
                  </ul>
                  @break
                @default
                  <p>{{ $block['text'] }}</p>
              @endswitch
            @endforeach

            @if(!empty($article['cta']))
              <aside class="news-body-cta">
                <h3>{{ $article['cta'][$lang]['heading'] }}</h3>
                <p>{{ $article['cta'][$lang]['body'] }}</p>
                <a href="{{ $routeWithLang($article['cta'][$lang]['href']) }}" class="btn btn-primary">
                  {{ $article['cta'][$lang]['label'] }}
                </a>
              </aside>
            @endif
          </div>
        </article>

        @if(! empty($relatedArticles))
          <aside class="news-detail-sidebar" aria-label="{{ $chrome['related_heading'] }}">
            <div class="news-sidebar-sticky">
              <h2 class="news-sidebar-heading">{{ $chrome['related_heading'] }}</h2>
              <ul class="news-sidebar-list">
                @foreach($relatedArticles as $related)
                  <li>
                    <a href="{{ $routeWithLang('/news/' . $related['slug']) }}" class="news-sidebar-item">
                      <span class="news-sidebar-eyebrow">{{ $related['category'][$lang] }}</span>
                      <span class="news-sidebar-title">{{ $related['title'][$lang] }}</span>
                      <time class="news-sidebar-date">{{ $related['published_display'][$lang] }}</time>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </aside>
        @endif
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .news-detail { padding-top: 48px; padding-bottom: 64px; }
  .news-detail-grid {
    display: grid;
    grid-template-columns: minmax(0, 8fr) minmax(0, 4fr);
    gap: 64px;
    align-items: start;
  }
  .news-back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--blue, #000613);
    font-family: 'Manrope', sans-serif;
    font-size: 14px;
    text-decoration: none;
    margin-bottom: 28px;
    transition: color .2s;
  }
  .news-back-link:hover { color: var(--teal, #006a6a); }
  .news-back-link .material-symbols-outlined { font-size: 18px; }

  .news-detail-head { margin-bottom: 28px; }
  .news-meta {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 20px;
  }
  .news-meta-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 999px;
    background: rgba(0, 31, 63, 0.08);
    color: var(--blue, #000613);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
  }
  .news-meta-date {
    color: var(--muted);
    font-size: 13px;
    font-family: 'Manrope', sans-serif;
  }
  .news-detail-title {
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2.25rem, 4.5vw, 3.75rem);
    line-height: 1.08;
    color: var(--blue, #000613);
    margin: 0;
    letter-spacing: -0.01em;
  }

  .news-detail-hero {
    margin: 0 0 40px;
    border-radius: var(--radius-lg, 16px);
    overflow: hidden;
    background: rgba(195,198,209,.2);
  }
  .news-detail-hero img {
    width: 100%;
    height: auto;
    display: block;
    aspect-ratio: 16 / 9;
    object-fit: cover;
  }

  .news-detail-body {
    font-family: 'Manrope', sans-serif;
    font-size: 1.0625rem;
    line-height: 1.75;
    color: var(--on-surface, #191c1d);
  }
  .news-detail-body p { margin: 0 0 18px; }
  .news-body-lead {
    font-size: 1.25rem;
    line-height: 1.6;
    color: var(--blue, #000613);
    font-weight: 500;
    margin: 0 0 28px !important;
  }
  .news-body-h2 {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.75rem;
    line-height: 1.2;
    color: var(--blue, #000613);
    margin: 40px 0 18px;
  }
  .news-body-list {
    list-style: none;
    padding: 0;
    margin: 0 0 32px;
    display: grid;
    gap: 14px;
  }
  .news-body-list li {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 14px;
    align-items: start;
  }
  .news-body-list .material-symbols-outlined {
    color: var(--teal, #006a6a);
    font-size: 20px;
    margin-top: 3px;
  }

  .news-body-cta {
    background: var(--surface-container-low, #f3f4f5);
    border-radius: var(--radius-lg, 16px);
    padding: 28px;
    display: grid;
    gap: 14px;
    margin-top: 36px;
    align-items: start;
    justify-items: start;
  }
  .news-body-cta h3 {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.5rem;
    margin: 0;
    color: var(--blue, #000613);
  }
  .news-body-cta p {
    margin: 0;
    color: var(--muted);
  }

  .news-detail-sidebar { min-width: 0; }
  .news-sidebar-sticky { position: sticky; top: 96px; }
  .news-sidebar-heading {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.5rem;
    color: var(--blue, #000613);
    margin: 0 0 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(195,198,209,.45);
  }
  .news-sidebar-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 20px;
  }
  .news-sidebar-item {
    display: grid;
    gap: 8px;
    padding: 14px;
    border-radius: var(--radius-lg, 16px);
    text-decoration: none;
    color: inherit;
    transition: background .2s;
  }
  .news-sidebar-item:hover { background: #fff; }
  .news-sidebar-eyebrow {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--teal, #006a6a);
  }
  .news-sidebar-title {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.125rem;
    line-height: 1.3;
    color: var(--blue, #000613);
  }
  .news-sidebar-date {
    font-size: 12px;
    color: var(--muted);
  }

  @media (max-width: 960px) {
    .news-detail-grid { grid-template-columns: 1fr; gap: 40px; }
    .news-sidebar-sticky { position: static; }
  }
</style>
@endsection
