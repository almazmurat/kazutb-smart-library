@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $routeWithLang = static function (string $path) use ($lang): string {
    if ($lang === 'ru') {
      return $path;
    }

    $separator = str_contains($path, '?') ? '&' : '?';
    return $path . $separator . 'lang=' . $lang;
  };

  $copy = [
    'ru' => [
      'meta' => 'О библиотеке и контакты — Digital Library',
      'hero' => 'О библиотеке и контакты',
      'lead' => 'Digital Library — центр знаний и информационной поддержки для студентов, преподавателей и исследователей.',
      'mission_label' => 'Миссия',
      'mission_title' => 'Доступ к знаниям и информационным ресурсам',
      'mission_body' => 'Digital Library поддерживает учебу и исследования, развивает фонд, расширяет цифровые подписки и создаёт комфортные условия для работы с информацией.',
      'contacts' => 'Контакты',
      'contacts_help' => 'Как связаться с библиотекой.',
      'hours' => 'Режим работы',
      'hours_help' => 'График работы библиотеки.',
      'units' => 'Подразделения',
      'units_help' => 'Контакты подразделений библиотеки.',
    ],
    'kk' => [
      'meta' => 'Кітапхана туралы және байланыс — Digital Library',
      'hero' => 'Кітапхана туралы және байланыс',
      'lead' => 'Digital Library студенттер, оқытушылар және зерттеушілер үшін білім мен ақпараттық қолдаудың заманауи орталығы болып табылады.',
      'mission_label' => 'Миссия',
      'mission_title' => 'Білім мен ақпараттық ресурстарға қолжетімділік',
      'mission_body' => 'Digital Library оқу үдерісі мен зерттеулерді ақпараттық тұрғыда қолдайды. Қорды дамытып, цифрлық жазылымдарды кеңейтіп, пайдаланушыларға ыңғайлы орта қалыптастырамыз.',
      'contacts' => 'Байланыс',
      'contacts_help' => 'Кітапханамен қалай байланысуға болады.',
      'hours' => 'Жұмыс уақыты',
      'hours_help' => 'Кітапхананың жұмыс кестесі.',
      'units' => 'Бөлімдер',
      'units_help' => 'Кітапхана бөлімдерінің байланыстары.',
    ],
    'en' => [
      'meta' => 'About the library and contacts — Digital Library',
      'hero' => 'About the library and contacts',
      'lead' => 'Digital Library is a modern knowledge and information-support center for students, faculty, and researchers.',
      'mission_label' => 'Mission',
      'mission_title' => 'Access to knowledge and information resources',
      'mission_body' => 'Digital Library supports study and research through a growing collection, digital subscriptions, and comfortable access for readers.',
      'contacts' => 'Contacts',
      'contacts_help' => 'How to reach the library.',
      'hours' => 'Opening hours',
      'hours_help' => 'The current library schedule.',
      'units' => 'Library units',
      'units_help' => 'Contact points across the library services.',
    ],
  ][$lang];
@endphp

@section('title', $copy['meta'])

@section('content')
  <section class="page-hero contact-hero">
    <div class="container contact-hero-shell">
      <div>
        <div class="eyebrow eyebrow--cyan">{{ ['ru' => 'Университетская библиотека', 'kk' => 'Университет кітапханасы', 'en' => 'Institutional library'][$lang] }}</div>
        <h1>{{ $copy['hero'] }}</h1>
        <p>{{ $copy['lead'] }}</p>
        <div class="contact-hero-actions">
          <a href="{{ $routeWithLang('/catalog') }}" class="btn btn-primary">{{ ['ru' => 'Каталог', 'kk' => 'Каталог', 'en' => 'Catalog'][$lang] }}</a>
          <a href="{{ $routeWithLang('/resources') }}" class="btn btn-ghost">{{ ['ru' => 'Ресурсы', 'kk' => 'Ресурстар', 'en' => 'Resources'][$lang] }}</a>
        </div>
      </div>

      <aside class="contact-highlight">
        <span>{{ ['ru' => 'Справочная стойка', 'kk' => 'Анықтама қызметі', 'en' => 'Library desk'][$lang] }}</span>
        <strong>+7 (7172) 64-58-58</strong>
        <p>{{ ['ru' => 'Астана · ул. Кайыма Мухамедханова, 37A', 'kk' => 'Астана · Қайым Мұхамедханов көшесі, 37A', 'en' => 'Astana · 37A Kayym Mukhamedkhanov Street'][$lang] }}</p>
        <a href="mailto:library@digital-library.demo">library@digital-library.demo</a>
      </aside>
    </div>
  </section>

  <section class="page-section">
    <div class="container about-grid">
      <div class="contact-editorial">
        <div class="eyebrow">{{ $copy['mission_label'] }}</div>
        <h2 class="heading-xl">{{ $copy['mission_title'] }}</h2>
        <p class="text-body" style="margin: 0 0 18px;">{{ $copy['mission_body'] }}</p>
        <div class="contact-facts">
          <div><strong>50K+</strong><span>{{ ['ru' => 'Единиц фонда', 'kk' => 'Қор бірліктері', 'en' => 'Collection items'][$lang] }}</span></div>
          <div><strong>24/7</strong><span>{{ ['ru' => 'Цифровой доступ', 'kk' => 'Цифрлық қолжетімділік', 'en' => 'Digital access'][$lang] }}</span></div>
          <div><strong>{{ ['ru' => 'Гибрид', 'kk' => 'Гибрид', 'en' => 'Hybrid'][$lang] }}</strong><span>{{ ['ru' => 'Печатный + цифровой', 'kk' => 'Баспа + цифрлық', 'en' => 'Print + digital'][$lang] }}</span></div>
        </div>
      </div>

      <div class="contact-stack">
        <article class="contact-strip">
          <span class="contact-strip-label">{{ ['ru' => 'Адрес', 'kk' => 'Мекенжай', 'en' => 'Address'][$lang] }}</span>
          <h3>{{ ['ru' => 'Информационно-библиотечный центр', 'kk' => 'Ақпараттық-кітапхана орталығы', 'en' => 'Information and library center'][$lang] }}</h3>
          <p>{{ ['ru' => 'Астана, ул. Кайыма Мухамедханова, 37A — очная помощь по выдаче, доступу и справочному сопровождению.', 'kk' => 'Астана, Қайым Мұхамедханов көшесі, 37A — беру, қолжетімділік және анықтамалық қолдау бойынша офлайн көмек.', 'en' => 'Astana, 37A Kayym Mukhamedkhanov Street — on-site help for circulation, access, and reference support.'][$lang] }}</p>
        </article>

        <article class="contact-strip">
          <span class="contact-strip-label">{{ ['ru' => 'Поддержка читателей', 'kk' => 'Оқырман қолдауы', 'en' => 'Reader support'][$lang] }}</span>
          <h3>{{ ['ru' => 'Позвоните или напишите в библиотеку', 'kk' => 'Кітапханаға қоңырау шалыңыз немесе жазыңыз', 'en' => 'Call or write to the library'][$lang] }}</h3>
          <p><a href="tel:+77172645858">+7 (7172) 64-58-58</a><br><a href="mailto:library@digital-library.demo">library@digital-library.demo</a></p>
        </article>

        <article class="contact-strip">
          <span class="contact-strip-label">{{ ['ru' => 'Онлайн-сервисы', 'kk' => 'Онлайн сервистер', 'en' => 'Online services'][$lang] }}</span>
          <h3>{{ ['ru' => 'Переход к рабочим разделам', 'kk' => 'Жұмыс бөлімдеріне өту', 'en' => 'Move directly into the working surfaces'][$lang] }}</h3>
          <p><a href="{{ $routeWithLang('/catalog') }}">{{ __('ui.nav.catalog') }}</a> · <a href="{{ $routeWithLang('/account') }}">{{ __('ui.nav.account') }}</a> · <a href="{{ $routeWithLang('/shortlist') }}">{{ __('ui.nav.shortlist') }}</a> · <a href="{{ $routeWithLang('/resources') }}">{{ __('ui.nav.resources') }}</a></p>
        </article>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container contacts-grid">
      <div class="card contact-panel">
        <div class="eyebrow eyebrow--violet">{{ $copy['hours'] }}</div>
        <h2>{{ $copy['hours_help'] }}</h2>
        <ul class="info-list">
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Понедельник – Пятница', 'kk' => 'Дүйсенбі – Жұма', 'en' => 'Monday – Friday'][$lang] }}</strong><br><span class="text-muted">09:00 – 18:00</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Суббота', 'kk' => 'Сенбі', 'en' => 'Saturday'][$lang] }}</strong><br><span class="text-muted">10:00 – 14:00</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Воскресенье', 'kk' => 'Жексенбі', 'en' => 'Sunday'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Выходной', 'kk' => 'Демалыс', 'en' => 'Closed'][$lang] }}</span></div></li>
        </ul>
        <p class="text-body-sm" style="margin-top: 14px;">{{ ['ru' => 'Каталог и личный кабинет остаются доступными круглосуточно, даже когда физическая стойка закрыта.', 'kk' => 'Физикалық қызмет көрсету орны жабық кезде де каталог пен жеке кабинет тәулік бойы қолжетімді.', 'en' => 'The catalog and account portal remain available around the clock even when the physical desk is closed.'][$lang] }}</p>
      </div>

      <div class="card contact-panel">
        <div class="eyebrow eyebrow--green">{{ $copy['units'] }}</div>
        <h2>{{ $copy['units_help'] }}</h2>
        <ul class="info-list">
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Абонемент', 'kk' => 'Абонемент', 'en' => 'Circulation'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Выдача, возврат, регистрация читателей и физический доступ.', 'kk' => 'Берілім, қайтарым, оқырманды тіркеу және физикалық қолжетімділік.', 'en' => 'Loans, returns, reader registration, and physical access.'][$lang] }}</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Читальный зал', 'kk' => 'Оқу залы', 'en' => 'Reading room'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Тихая работа со справочными материалами и текущей периодикой.', 'kk' => 'Анықтамалық материалдар мен мерзімді басылымдармен тыныш жұмыс.', 'en' => 'Quiet work with reference materials and current periodicals.'][$lang] }}</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Библиографическая поддержка', 'kk' => 'Библиографиялық қолдау', 'en' => 'Bibliographic support'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Помощь с поиском, подбором источников и академическими ссылками.', 'kk' => 'Іздеу, дереккөз таңдау және академиялық сілтемелер бойынша көмек.', 'en' => 'Search help, source discovery, and academic reference guidance.'][$lang] }}</span></div></li>
          <li><span class="icon">•</span><div><strong>{{ ['ru' => 'Формирование фонда', 'kk' => 'Қорды дамыту', 'en' => 'Collection development'][$lang] }}</strong><br><span class="text-muted">{{ ['ru' => 'Комплектование, каталогизация и сопровождение фонда.', 'kk' => 'Жинақтау, каталогтау және қорды сүйемелдеу.', 'en' => 'Acquisitions, cataloging, and fund maintenance.'][$lang] }}</span></div></li>
        </ul>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .contact-hero-shell {
    display: grid;
    grid-template-columns: 1.3fr 320px;
    gap: 20px;
    align-items: stretch;
    text-align: left;
    animation: contactReveal .45s cubic-bezier(0.2, 0.8, 0.2, 1) both;
  }

  .contact-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
  }

  .contact-highlight {
    padding: 20px;
    border-radius: var(--radius-xl);
    background: #fff;
    border: 1px solid var(--border);
    box-shadow: var(--shadow-soft);
    display: grid;
    gap: 8px;
    align-content: start;
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .contact-highlight:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(0,30,64,.12);
  }

  .contact-highlight span {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .14em;
    color: var(--cyan);
  }

  .contact-highlight strong {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.8rem;
    line-height: 1.1;
    color: var(--blue);
  }

  .contact-highlight p,
  .contact-highlight a {
    color: var(--muted);
    line-height: 1.7;
  }

  .about-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 24px;
    align-items: start;
  }

  .contact-editorial {
    padding-right: 10px;
  }

  .contact-facts {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
  }

  .contact-facts div {
    padding: 14px;
    border-radius: var(--radius-lg);
    background: #fff;
    border: 1px solid var(--border);
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .contact-facts div:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 12px 24px rgba(25, 28, 29, 0.04);
    border-color: rgba(20,105,109,.18);
  }

  .contact-facts strong {
    display: block;
    margin-bottom: 4px;
    font-size: 22px;
    color: var(--blue);
  }

  .contact-facts span {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--muted);
  }

  .contact-stack {
    display: grid;
    gap: 12px;
  }

  .contact-strip {
    padding: 18px;
    border-radius: var(--radius-lg);
    background: #fff;
    border: 1px solid var(--border);
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .contact-strip:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 12px 24px rgba(25, 28, 29, 0.04);
    border-color: rgba(0,30,64,.12);
  }

  .contact-strip-label {
    display: inline-block;
    margin-bottom: 6px;
    color: var(--cyan);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .12em;
  }

  .contact-strip h3 {
    margin: 0 0 6px;
    font-family: 'Newsreader', Georgia, serif;
    color: var(--blue);
    font-size: 1.35rem;
  }

  .contact-strip p,
  .contact-strip a {
    margin: 0;
    color: var(--muted);
    line-height: 1.7;
  }

  .contacts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    align-items: start;
  }

  .contact-panel {
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .contact-panel:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(20,105,109,.18);
  }

  .contact-panel h2 {
    margin: 0 0 10px;
    font-family: 'Newsreader', Georgia, serif;
    color: var(--blue);
    font-size: 1.6rem;
  }

  @media (max-width: 900px) {
    .contact-hero-shell,
    .about-grid,
    .contacts-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 680px) {
    .contact-facts {
      grid-template-columns: 1fr;
    }
  }

  @keyframes contactReveal {
    from {
      opacity: 0;
      transform: translate3d(0, 10px, 0);
    }

    to {
      opacity: 1;
      transform: translate3d(0, 0, 0);
    }
  }
</style>
@endsection
