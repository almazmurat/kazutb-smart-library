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
          'title' => 'Новости и анонсы — KazUTB Smart Library',
          'eyebrow_featured' => 'Главный материал',
          'hero_read' => 'Читать полностью',
          'grid_heading' => 'Последние обновления',
          'intro_heading' => 'Новости KazUTB Smart Library',
          'intro_body' => 'Официальные обновления университетской библиотеки: симпозиумы, обновления фонда, расширение цифрового доступа и сервисные объявления.',
      ],
      'kk' => [
          'title' => 'Жаңалықтар мен хабарландырулар — KazUTB Smart Library',
          'eyebrow_featured' => 'Басты материал',
          'hero_read' => 'Толығырақ оқу',
          'grid_heading' => 'Соңғы жаңартулар',
          'intro_heading' => 'KazUTB Smart Library жаңалықтары',
          'intro_body' => 'Университет кітапханасының ресми жаңартулары: симпозиумдар, қор жаңартулары, цифрлық қолжетімділіктің кеңеюі және сервистік хабарландырулар.',
      ],
      'en' => [
          'title' => 'News and announcements — KazUTB Smart Library',
          'eyebrow_featured' => 'Featured Report',
          'hero_read' => 'Read Full Coverage',
          'grid_heading' => 'Recent Updates',
          'intro_heading' => 'KazUTB Smart Library news',
          'intro_body' => 'Official updates from the university library: symposiums, collection updates, expanded digital access, and service announcements.',
      ],
  ][$lang];

  $featured = null;
  $rest = [];
  foreach ($newsArticles as $article) {
      if ($featured === null && ! empty($article['featured'])) {
          $featured = $article;
          continue;
      }
      $rest[] = $article;
  }
  if ($featured === null && ! empty($newsArticles)) {
      $featured = $newsArticles[0];
      $rest = array_slice($newsArticles, 1);
  }
@endphp

@section('title', $chrome['title'])

@section('content')
  <section class="page-section news-intro" data-section="news-intro">
    <div class="container">
      <div class="section-head-centered">
        <div class="max-w-prose mx-auto">
          <div class="eyebrow eyebrow--cyan">KazUTB Smart Library</div>
          <h1 class="heading-xl">{{ $chrome['intro_heading'] }}</h1>
          <p class="text-body">{{ $chrome['intro_body'] }}</p>
        </div>
      </div>
    </div>
  </section>

  @if($featured)
    <section class="page-section news-featured" data-section="news-featured">
      <div class="container">
        <div class="news-featured-grid">
          <div class="news-featured-copy">
            <div class="news-meta">
              <span class="news-meta-badge">{{ $featured['category'][$lang] }}</span>
              <span class="news-meta-date">{{ $featured['published_display'][$lang] }}</span>
            </div>
            <h2 class="news-featured-title">{{ $featured['title'][$lang] }}</h2>
            <p class="news-featured-excerpt">{{ $featured['excerpt'][$lang] }}</p>
            <a href="{{ $routeWithLang('/news/' . $featured['slug']) }}" class="btn btn-primary news-featured-cta">
              {{ $chrome['hero_read'] }}
            </a>
          </div>
          <div class="news-featured-media" aria-hidden="true">
            @if(!empty($featured['hero']['image']))
              <img src="{{ asset($featured['hero']['image']) }}" alt="{{ $featured['hero']['alt'][$lang] ?? '' }}" loading="lazy" />
            @endif
          </div>
        </div>
      </div>
    </section>
  @endif

  <section class="page-section news-grid-section" data-section="news-grid">
    <div class="container">
      <div class="news-grid-head">
        <h2 class="heading-lg">{{ $chrome['grid_heading'] }}</h2>
      </div>
      <div class="news-grid">
        @foreach($rest as $article)
          <article class="news-card">
            <a href="{{ $routeWithLang('/news/' . $article['slug']) }}" class="news-card-link">
              <div class="news-card-media" aria-hidden="true">
                @if(!empty($article['hero']['image']))
                  <img src="{{ asset($article['hero']['image']) }}" alt="{{ $article['hero']['alt'][$lang] ?? '' }}" loading="lazy" />
                @endif
              </div>
              <div class="news-card-meta">
                <span class="news-card-category">{{ $article['category'][$lang] }}</span>
                <span class="news-card-date">{{ $article['published_display'][$lang] }}</span>
              </div>
              <h3 class="news-card-title">{{ $article['title'][$lang] }}</h3>
              <p class="news-card-excerpt">{{ $article['excerpt'][$lang] }}</p>
            </a>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .news-intro { padding-top: 48px; }
  .news-featured-grid {
    display: grid;
    grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
    gap: 32px;
    align-items: center;
  }
  .news-featured-copy { display: grid; gap: 18px; }
  .news-meta {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
  }
  .news-meta-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 999px;
    background: rgba(0, 31, 63, 0.06);
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
  .news-featured-title {
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2.25rem, 4.5vw, 3.5rem);
    line-height: 1.08;
    color: var(--blue, #000613);
    margin: 0;
  }
  .news-featured-excerpt {
    margin: 0;
    color: var(--muted);
    font-size: 1.125rem;
    line-height: 1.65;
    max-width: 56ch;
  }
  .news-featured-cta { align-self: start; }
  .news-featured-media {
    width: 100%;
    aspect-ratio: 4 / 3;
    border-radius: var(--radius-lg, 16px);
    overflow: hidden;
    background: rgba(195,198,209,.2);
    box-shadow: 0 16px 36px rgba(0, 6, 19, 0.08);
  }
  .news-featured-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .news-grid-head {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    border-bottom: 1px solid rgba(195,198,209,.45);
    padding-bottom: 12px;
    margin-bottom: 28px;
  }
  .news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 32px;
  }
  .news-card {
    border: 1px solid var(--border, #e1e3e4);
    border-radius: var(--radius-lg, 16px);
    background: #fff;
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1);
    overflow: hidden;
  }
  .news-card:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.06);
  }
  .news-card-link {
    display: grid;
    gap: 12px;
    padding: 0 0 20px;
    text-decoration: none;
    color: inherit;
  }
  .news-card-media {
    width: 100%;
    aspect-ratio: 16 / 10;
    overflow: hidden;
    background: rgba(195,198,209,.2);
  }
  .news-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  .news-card-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 0 20px;
    font-size: 11px;
    font-family: 'Manrope', sans-serif;
  }
  .news-card-category {
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--teal, #006a6a);
  }
  .news-card-date { color: var(--muted); }
  .news-card-title {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.5rem;
    line-height: 1.15;
    color: var(--blue, #000613);
    margin: 0;
    padding: 0 20px;
  }
  .news-card-excerpt {
    margin: 0;
    padding: 0 20px;
    color: var(--muted);
    font-size: 0.98rem;
    line-height: 1.6;
  }

  @media (max-width: 960px) {
    .news-featured-grid { grid-template-columns: 1fr; }
  }
</style>
@endsection
