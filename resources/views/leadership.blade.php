@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $activePage = $activePage ?? 'leadership';
  $pageLang = $lang;

  $routeWithLang = static function (string $path, array $query = []) use ($lang): string {
      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }
      $qs = http_build_query(array_filter($query, static fn ($v) => $v !== null && $v !== ''));
      return $path . ($qs !== '' ? ('?' . $qs) : '');
  };

  $header = $leadership['header'][$lang];
  $mandate = $leadership['mandate'][$lang];
  $supportCta = $leadership['support_cta'][$lang];
  $profiles = collect($leadership['profiles'])
      ->sortBy('order')
      ->values()
      ->all();
  $lastReviewedAt = $leadership['last_reviewed_at'] ?? null;

  $chrome = [
      'ru' => [
          'title' => 'Руководство — KazUTB Smart Library',
          'directory_eyebrow' => 'Состав руководства',
          'directory_heading' => 'Роли и ответственность',
          'directory_note' => 'Разделы отражают закреплённые обязанности. Имена и профили публикуются по мере подтверждения руководством библиотеки.',
          'reviewed_label' => 'Последняя проверка',
      ],
      'kk' => [
          'title' => 'Басшылық — KazUTB Smart Library',
          'directory_eyebrow' => 'Басшылық құрамы',
          'directory_heading' => 'Рөлдер мен жауапкершілік',
          'directory_note' => 'Бөлімдер бекітілген міндеттерді көрсетеді. Есімдер мен профильдер кітапхана басшылығы растаған соң жарияланады.',
          'reviewed_label' => 'Соңғы тексеру',
      ],
      'en' => [
          'title' => 'Leadership — KazUTB Smart Library',
          'directory_eyebrow' => 'Leadership directory',
          'directory_heading' => 'Roles and responsibilities',
          'directory_note' => 'Entries reflect assigned responsibilities. Names and full profiles are published once confirmed by library leadership.',
          'reviewed_label' => 'Last reviewed',
      ],
  ][$lang];
@endphp

@section('title', $chrome['title'])

@section('content')
  <section class="page-section leadership-header" data-section="leadership-header">
    <div class="container">
      <div class="max-w-prose">
        <div class="eyebrow eyebrow--cyan">{{ $header['eyebrow'] }}</div>
        <h1 class="heading-xl">{{ $header['headline'] }}</h1>
        <p class="text-body">{{ $header['lede'] }}</p>
        @if($lastReviewedAt)
          <p class="leadership-meta" data-test-id="leadership-last-reviewed">
            <span class="leadership-meta-label">{{ $chrome['reviewed_label'] }}:</span>
            <time datetime="{{ $lastReviewedAt }}">{{ $lastReviewedAt }}</time>
          </p>
        @endif
      </div>
    </div>
  </section>

  <section class="page-section leadership-mandate" data-section="leadership-mandate">
    <div class="container">
      <div class="leadership-mandate-grid">
        <div class="leadership-mandate-copy">
          <div class="eyebrow eyebrow--teal">{{ $mandate['eyebrow'] }}</div>
          <h2 class="heading-lg">{{ $mandate['title'] }}</h2>
          <p class="text-body">{{ $mandate['paragraph'] }}</p>
        </div>
        <dl class="leadership-mandate-meta" data-test-id="leadership-reports-to">
          <dt>{{ $mandate['reports_to_label'] }}</dt>
          <dd>{{ $mandate['reports_to_value'] }}</dd>
        </dl>
      </div>
    </div>
  </section>

  <section class="page-section leadership-directory" data-section="leadership-directory">
    <div class="container">
      <div class="section-head">
        <div class="eyebrow eyebrow--cyan">{{ $chrome['directory_eyebrow'] }}</div>
        <h2 class="heading-lg">{{ $chrome['directory_heading'] }}</h2>
        <p class="text-body-sm leadership-directory-note">{{ $chrome['directory_note'] }}</p>
      </div>

      <div class="leadership-grid" role="list">
        @foreach($profiles as $profile)
          @php
            $roleTitle = $profile['role_title'][$lang];
            $roleScope = $profile['role_scope_line'][$lang];
            $roleDescription = $profile['role_description'][$lang];
            $initials = $profile['portrait_initials'][$lang] ?? mb_substr($roleTitle, 0, 1);
            $fullName = $profile['full_name'][$lang] ?? null;
            $portrait = $profile['portrait'] ?? null;
            $email = $profile['email'] ?? null;
          @endphp
          <article class="leadership-card" role="listitem" data-leadership-slug="{{ $profile['slug'] }}">
            <div class="leadership-card-portrait" aria-hidden="true">
              @if($portrait)
                <img
                  src="{{ asset($portrait) }}"
                  alt=""
                  loading="lazy"
                  decoding="async"
                  class="leadership-card-portrait-img"
                />
              @else
                <span class="leadership-card-portrait-initials">{{ $initials }}</span>
              @endif
            </div>
            <div class="leadership-card-body">
              <h3 class="leadership-card-role">{{ $roleTitle }}</h3>
              <p class="leadership-card-scope">{{ $roleScope }}</p>
              @if($fullName)
                <p class="leadership-card-name" data-test-id="leadership-name">{{ $fullName }}</p>
              @endif
              <p class="leadership-card-description">{{ $roleDescription }}</p>
              @if($email)
                <p class="leadership-card-contact">
                  <a href="mailto:{{ $email }}">{{ $email }}</a>
                </p>
              @endif
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section leadership-support-cta" data-section="leadership-support-cta">
    <div class="container">
      <div class="leadership-cta-card">
        <div class="eyebrow eyebrow--teal">{{ $supportCta['eyebrow'] }}</div>
        <h2 class="heading-lg">{{ $supportCta['heading'] }}</h2>
        <p class="text-body">{{ $supportCta['body'] }}</p>
        <a href="{{ $routeWithLang($supportCta['href']) }}" class="btn btn-primary leadership-cta-link">
          {{ $supportCta['label'] }}
          <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
        </a>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .leadership-header { padding-top: 48px; }
  .leadership-meta {
    margin-top: 18px;
    font-family: 'Manrope', sans-serif;
    font-size: 0.8125rem;
    color: var(--muted, #43474e);
    letter-spacing: 0.02em;
  }
  .leadership-meta-label {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: var(--teal, #006a6a);
    font-size: 0.6875rem;
    margin-right: 6px;
  }

  .leadership-mandate-grid {
    display: grid;
    grid-template-columns: minmax(0, 7fr) minmax(0, 4fr);
    gap: 40px;
    align-items: start;
    padding: 28px 0;
    border-top: 1px solid rgba(195, 198, 209, 0.35);
    border-bottom: 1px solid rgba(195, 198, 209, 0.35);
  }
  .leadership-mandate-copy .eyebrow,
  .leadership-mandate-copy .heading-lg,
  .leadership-mandate-copy .text-body {
    margin-bottom: 14px;
  }
  .leadership-mandate-copy .text-body { margin-bottom: 0; max-width: 62ch; }
  .leadership-mandate-meta {
    display: grid;
    gap: 4px;
    padding: 20px 24px;
    background: var(--surface-container-low, #f3f4f5);
    border-radius: var(--radius-lg, 16px);
    border: 1px solid rgba(195, 198, 209, 0.4);
  }
  .leadership-mandate-meta dt {
    font-family: 'Manrope', sans-serif;
    font-size: 0.6875rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--teal, #006a6a);
  }
  .leadership-mandate-meta dd {
    margin: 0;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.125rem;
    color: var(--blue, #000613);
  }

  .leadership-directory .section-head {
    max-width: 720px;
    margin-bottom: 40px;
  }
  .leadership-directory .section-head .eyebrow,
  .leadership-directory .section-head .heading-lg {
    margin-bottom: 10px;
  }
  .leadership-directory-note {
    margin: 6px 0 0;
    color: var(--muted, #43474e);
  }

  .leadership-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 36px;
    padding-top: 64px;
  }
  .leadership-card {
    position: relative;
    display: block;
    padding: 80px 28px 28px;
    background: #fff;
    border: 1px solid rgba(195, 198, 209, 0.5);
    border-radius: var(--radius-lg, 16px);
    transition: transform 0.24s cubic-bezier(0.2, 0.8, 0.2, 1),
      box-shadow 0.24s cubic-bezier(0.2, 0.8, 0.2, 1),
      border-color 0.24s ease;
  }
  .leadership-card:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 18px 36px rgba(0, 6, 19, 0.08);
    border-color: rgba(0, 106, 106, 0.3);
  }
  .leadership-card-portrait {
    position: absolute;
    top: -52px;
    left: 28px;
    width: 104px;
    height: 104px;
    border-radius: 999px;
    background: linear-gradient(135deg, #e7e8e9 0%, #f3f4f5 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 3px solid #fff;
    box-shadow: 0 4px 16px rgba(0, 6, 19, 0.10);
    font-family: 'Newsreader', Georgia, serif;
    color: var(--blue, #000613);
  }
  .leadership-card-portrait-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    filter: grayscale(1);
    transition: filter 0.6s ease;
  }
  .leadership-card:hover .leadership-card-portrait-img { filter: grayscale(0); }
  .leadership-card-portrait-initials {
    font-size: 2rem;
    font-weight: 600;
    letter-spacing: 0.02em;
  }
  .leadership-card-body { display: grid; gap: 10px; }
  .leadership-card-role {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.5rem;
    line-height: 1.2;
    color: var(--blue, #000613);
    margin: 0;
  }
  .leadership-card-scope {
    margin: 0;
    font-family: 'Manrope', sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--teal, #006a6a);
  }
  .leadership-card-name {
    margin: 2px 0 0;
    font-family: 'Manrope', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--blue, #000613);
  }
  .leadership-card-description {
    margin: 0;
    font-family: 'Manrope', sans-serif;
    font-size: 0.95rem;
    line-height: 1.6;
    color: var(--muted, #43474e);
  }
  .leadership-card-contact a {
    font-family: 'Manrope', sans-serif;
    font-size: 0.875rem;
    color: var(--teal, #006a6a);
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: border-color 0.2s ease;
  }
  .leadership-card-contact a:hover { border-color: currentColor; }

  .leadership-cta-card {
    display: grid;
    gap: 14px;
    justify-items: start;
    padding: 40px;
    background: var(--surface-container-low, #f3f4f5);
    border-radius: var(--radius-lg, 16px);
    border: 1px solid rgba(195, 198, 209, 0.4);
    max-width: 820px;
  }
  .leadership-cta-card .heading-lg { margin: 0; }
  .leadership-cta-card .text-body { margin: 0; max-width: 62ch; }
  .leadership-cta-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .leadership-cta-link .material-symbols-outlined { font-size: 18px; }

  @media (max-width: 960px) {
    .leadership-mandate-grid { grid-template-columns: 1fr; gap: 24px; }
  }
</style>
@endsection
