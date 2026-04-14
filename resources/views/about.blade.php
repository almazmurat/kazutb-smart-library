@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $routeWithLang = static function (string $path, array $query = []) use ($lang): string {
      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }

      $queryString = http_build_query(array_filter($query, static fn ($value) => $value !== null && $value !== ''));

      return $path . ($queryString !== '' ? ('?' . $queryString) : '');
  };

  $copy = [
      'ru' => [
          'title' => 'О библиотеке — Digital Library',
          'hero_eyebrow' => 'О библиотеке',
          'hero_title' => 'КазТБУ Digital Library',
          'hero_body' => 'Цифровая библиотека КазТБУ объединяет каталог, поиск по темам, доступ к материалам и рабочие маршруты для читателя и преподавателя.',
          'hero_body_secondary' => 'Здесь можно быстро перейти к фонду, электронным ресурсам и основным сервисам без лишней навигации.',
          'hero_primary' => 'Открыть каталог',
          'hero_secondary' => 'Научные ресурсы',
          'contact_eyebrow' => 'Контакты и режим',
          'contact_title' => 'Как связаться с библиотекой',
          'contact_body' => 'Если нужна помощь с поиском, доступом или работой с материалами, используйте реальные контакты библиотеки.',
          'contacts' => [
              ['label' => 'Телефон', 'value' => '+7 (7172) 64-58-58', 'href' => 'tel:+77172645858'],
              ['label' => 'Email', 'value' => 'library@digital-library.demo', 'href' => 'mailto:library@digital-library.demo'],
              ['label' => 'Адрес', 'value' => 'Астана, ул. Кайыма Мухамедханова, 37A'],
          ],
          'hours_title' => 'Режим работы',
          'hours' => [
              ['label' => 'Понедельник – Пятница', 'value' => '09:00 – 18:00'],
              ['label' => 'Суббота', 'value' => '10:00 – 14:00'],
              ['label' => 'Воскресенье', 'value' => 'Выходной'],
          ],
          'cards_eyebrow' => 'Что доступно на платформе',
          'cards' => [
              ['title' => 'Каталог и навигация', 'body' => 'Поиск по фонду, академическая навигация по темам и быстрый переход к нужным записям.'],
              ['title' => 'Печатные и цифровые материалы', 'body' => 'Книги, электронные материалы и внешние ресурсы собраны в одном понятном маршруте доступа.'],
              ['title' => 'Кабинет, подборка и доступ', 'body' => 'Читатель и преподаватель могут собирать рабочие списки, переходить к ресурсам и продолжать работу без лишних шагов.'],
          ],
            'cta_eyebrow' => 'Начать работу',
          'cta_title' => 'Переходите сразу к нужному разделу',
          'cta_body' => 'Откройте каталог, навигацию по темам, подборку или раздел ресурсов и продолжайте работу без лишних объяснений.',
            'cta_links' => [
              ['label' => 'Каталог', 'href' => '/catalog'],
              ['label' => 'Навигация по УДК', 'href' => '/discover'],
              ['label' => 'Shortlist', 'href' => '/shortlist'],
              ['label' => 'Ресурсы', 'href' => '/resources'],
            ],
      ],
      'kk' => [
          'title' => 'Кітапхана туралы — Digital Library',
          'hero_eyebrow' => 'Кітапхана туралы',
          'hero_title' => 'KazTBU Digital Library',
          'hero_body' => 'KazTBU цифрлық кітапханасы каталогты, тақырып бойынша іздеуді, материалдарға қолжетімділікті және оқырман мен оқытушыға арналған негізгі маршруттарды біріктіреді.',
          'hero_body_secondary' => 'Мұнда қорға, электрондық ресурстарға және негізгі сервистерге артық түсіндірмесіз тез өтуге болады.',
          'hero_primary' => 'Каталогты ашу',
          'hero_secondary' => 'Ғылыми ресурстар',
          'contact_eyebrow' => 'Байланыс және кесте',
          'contact_title' => 'Кітапханамен қалай байланысуға болады',
          'contact_body' => 'Іздеу, қолжетімділік немесе материалдар бойынша көмек керек болса, кітапхананың нақты байланыс арналарына жүгініңіз.',
          'contacts' => [
              ['label' => 'Телефон', 'value' => '+7 (7172) 64-58-58', 'href' => 'tel:+77172645858'],
              ['label' => 'Email', 'value' => 'library@digital-library.demo', 'href' => 'mailto:library@digital-library.demo'],
              ['label' => 'Мекенжай', 'value' => 'Астана, Қайым Мұхамедханов көшесі, 37A'],
          ],
          'hours_title' => 'Жұмыс уақыты',
          'hours' => [
              ['label' => 'Дүйсенбі – Жұма', 'value' => '09:00 – 18:00'],
              ['label' => 'Сенбі', 'value' => '10:00 – 14:00'],
              ['label' => 'Жексенбі', 'value' => 'Демалыс'],
          ],
          'cards_eyebrow' => 'Платформада не істеуге болады',
          'cards' => [
              ['title' => 'Каталог және навигация', 'body' => 'Қор бойынша іздеу, тақырыппен жүру және қажет материалға тез өту бір маршрутта жиналған.'],
              ['title' => 'Баспа және цифрлық материалдар', 'body' => 'Кітаптар, электрондық материалдар және сыртқы ресурстар бір түсінікті қолжетімділік логикасында берілген.'],
              ['title' => 'Кабинет, shortlist және қолжетімділік', 'body' => 'Оқырман мен оқытушы жұмыс тізімдерін жинап, ресурстарға өтіп, жұмысты артық қадамсыз жалғастыра алады.'],
          ],
            'cta_eyebrow' => 'Жұмысты бастау',
          'cta_title' => 'Қажетті бөлімге бірден өтіңіз',
          'cta_body' => 'Каталогты, тақырыптық навигацияны, shortlist-ті немесе ресурстар бөлімін ашып, жұмысты бірден жалғастырыңыз.',
            'cta_links' => [
              ['label' => 'Каталог', 'href' => '/catalog'],
              ['label' => 'ӘОЖ навигациясы', 'href' => '/discover'],
              ['label' => 'Shortlist', 'href' => '/shortlist'],
              ['label' => 'Ресурстар', 'href' => '/resources'],
            ],
      ],
      'en' => [
          'title' => 'About Library — Digital Library',
          'hero_eyebrow' => 'About Library',
          'hero_title' => 'KazTBU Digital Library',
          'hero_body' => 'KazTBU Digital Library brings together catalog search, subject navigation, access to materials, and core routes for readers and faculty.',
          'hero_body_secondary' => 'From here, people can move quickly into the collection, digital resources, and everyday library services.',
          'hero_primary' => 'Open catalog',
          'hero_secondary' => 'Research resources',
          'contact_eyebrow' => 'Contacts and hours',
          'contact_title' => 'How to reach the library',
          'contact_body' => 'Use the library contact details when you need help with search, access, or working with materials.',
          'contacts' => [
              ['label' => 'Phone', 'value' => '+7 (7172) 64-58-58', 'href' => 'tel:+77172645858'],
              ['label' => 'Email', 'value' => 'library@digital-library.demo', 'href' => 'mailto:library@digital-library.demo'],
              ['label' => 'Address', 'value' => '37A Kayym Mukhamedkhanov Street, Astana'],
          ],
          'hours_title' => 'Opening hours',
          'hours' => [
              ['label' => 'Monday – Friday', 'value' => '09:00 – 18:00'],
              ['label' => 'Saturday', 'value' => '10:00 – 14:00'],
              ['label' => 'Sunday', 'value' => 'Closed'],
          ],
          'cards_eyebrow' => 'What people can do here',
          'cards' => [
              ['title' => 'Catalog and navigation', 'body' => 'Search the collection, move by subject, and get to the right records without extra steps.'],
              ['title' => 'Print and digital materials', 'body' => 'Books, electronic materials, and external resources are gathered into one clear access route.'],
              ['title' => 'Account, shortlist, and access', 'body' => 'Readers and faculty can collect working lists, move into resources, and continue their work smoothly.'],
          ],
            'cta_eyebrow' => 'Start working',
          'cta_title' => 'Go directly to the section you need',
          'cta_body' => 'Open the catalog, subject navigation, shortlist, or resources and continue from there.',
            'cta_links' => [
              ['label' => 'Catalog', 'href' => '/catalog'],
              ['label' => 'UDC navigation', 'href' => '/discover'],
              ['label' => 'Shortlist', 'href' => '/shortlist'],
              ['label' => 'Resources', 'href' => '/resources'],
            ],
      ],
  ][$lang];
@endphp

@section('title', $copy['title'])

@section('content')
  <section class="page-hero about-hero">
    <div class="container about-hero-shell">
      <div class="about-hero-copy">
        <div class="eyebrow eyebrow--cyan">{{ $copy['hero_eyebrow'] }}</div>
        <h1>{{ $copy['hero_title'] }}</h1>
        <p>{{ $copy['hero_body'] }}</p>
        <p class="about-hero-secondary">{{ $copy['hero_body_secondary'] }}</p>
        <div class="about-hero-actions">
          <a href="{{ $routeWithLang('/catalog') }}" class="btn btn-primary">{{ $copy['hero_primary'] }}</a>
          <a href="{{ $routeWithLang('/resources') }}" class="btn btn-ghost">{{ $copy['hero_secondary'] }}</a>
        </div>
      </div>

      <aside class="about-contact-card">
        <span>{{ $copy['contact_eyebrow'] }}</span>
        <strong>{{ $copy['contact_title'] }}</strong>
        <p>{{ $copy['contact_body'] }}</p>
        <div class="about-contact-list">
          @foreach($copy['contacts'] as $item)
            <div class="contact-row">
              <span>{{ $item['label'] }}</span>
              @if(!empty($item['href']))
                <a href="{{ $item['href'] }}">{{ $item['value'] }}</a>
              @else
                <strong>{{ $item['value'] }}</strong>
              @endif
            </div>
          @endforeach
        </div>
        <div class="about-hours-list">
          <div class="contact-row contact-row--heading">
            <span>{{ $copy['hours_title'] }}</span>
          </div>
          @foreach($copy['hours'] as $item)
            <div class="contact-row">
              <span>{{ $item['label'] }}</span>
              <strong>{{ $item['value'] }}</strong>
            </div>
          @endforeach
        </div>
      </aside>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow eyebrow--violet">{{ $copy['cards_eyebrow'] }}</div>
        </div>
      </div>

      <div class="about-card-grid">
        @foreach($copy['cards'] as $card)
          <article class="about-card">
            <h3>{{ $card['title'] }}</h3>
            <p>{{ $card['body'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="about-cta-band">
        <div>
          <div class="eyebrow eyebrow--green">{{ $copy['cta_eyebrow'] }}</div>
          <h2>{{ $copy['cta_title'] }}</h2>
          <p>{{ $copy['cta_body'] }}</p>
        </div>
        <div class="about-cta-links">
          @foreach($copy['cta_links'] as $link)
            <a href="{{ $routeWithLang($link['href']) }}">{{ $link['label'] }}</a>
          @endforeach
        </div>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .about-hero-shell {
    display: grid;
    grid-template-columns: minmax(0, 1.25fr) minmax(280px, 360px);
    gap: 28px;
    align-items: stretch;
    text-align: left;
    animation: aboutReveal .45s cubic-bezier(0.2, 0.8, 0.2, 1) both;
  }

  .about-hero-copy {
    max-width: 760px;
  }

  .about-hero-copy h1 {
    margin-bottom: 14px;
  }

  .about-hero-copy p {
    margin: 0;
    color: var(--muted);
    line-height: 1.72;
    max-width: 62ch;
  }

  .about-hero-secondary {
    margin-top: 12px;
  }

  .about-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
  }

  .about-cta-links a {
    display: inline-flex;
    align-items: center;
    padding: 9px 12px;
    border-radius: var(--radius-md);
    border: 1px solid rgba(195,198,209,.55);
    background: rgba(255,255,255,.84);
    color: var(--blue);
    font-size: 12px;
    font-weight: 700;
  }

  .about-card,
  .about-contact-card {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .about-card:hover,
  .about-contact-card:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(20,105,109,.18);
  }

  .about-contact-card {
    padding: 22px;
    display: grid;
    align-content: start;
    gap: 10px;
  }

  .about-contact-card > span,
  .contact-row span {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--cyan);
  }

  .about-contact-card > strong {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.7rem;
    line-height: 1.08;
    color: var(--blue);
  }

  .about-contact-card > p,
  .about-card p {
    margin: 0;
    color: var(--muted);
    line-height: 1.68;
  }

  .about-contact-list,
  .about-hours-list {
    display: grid;
    gap: 10px;
  }

  .contact-row {
    display: grid;
    gap: 4px;
    padding-top: 12px;
    border-top: 1px solid rgba(195,198,209,.45);
  }

  .contact-row--heading {
    padding-top: 4px;
  }

  .contact-row strong,
  .contact-row a {
    color: var(--text);
    font-weight: 700;
    text-decoration: none;
  }

  .contact-row a:hover {
    color: var(--blue);
  }

  .section-head {
    margin-bottom: 16px;
  }

  .section-head p {
    margin: 6px 0 0;
    color: var(--muted);
  }

  .about-card-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }

  .about-card {
    padding: 22px;
  }

  .about-card h3 {
    margin: 0 0 10px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.45rem;
    color: var(--blue);
    line-height: 1.12;
  }

  .page-section {
    padding-top: 28px;
    padding-bottom: 28px;
  }

  .about-cta-band {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 24px;
    background: linear-gradient(135deg, rgba(0,30,64,.98), rgba(14,54,87,.94));
    color: #fff;
    display: grid;
    grid-template-columns: 1.05fr .95fr;
    gap: 24px;
    align-items: center;
  }

  .about-cta-band h2 {
    margin: 10px 0 8px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 2rem;
    line-height: 1.02;
    color: #fff;
  }

  .about-cta-band p {
    margin: 0;
    color: rgba(255,255,255,.76);
    line-height: 1.68;
  }

  .about-cta-links {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-end;
  }

  .about-cta-links a {
    background: rgba(255,255,255,.12);
    border-color: rgba(255,255,255,.16);
    color: #fff;
    text-decoration: none;
  }

  @keyframes aboutReveal {
    from {
      opacity: 0;
      transform: translate3d(0, 18px, 0);
    }
    to {
      opacity: 1;
      transform: translate3d(0, 0, 0);
    }
  }

  @media (max-width: 960px) {
    .about-hero-shell,
    .about-card-grid,
    .about-cta-band {
      grid-template-columns: 1fr;
    }

    .about-cta-links {
      justify-content: flex-start;
    }
  }
</style>
@endsection
