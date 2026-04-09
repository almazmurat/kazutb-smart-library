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
      'meta' => 'Электронные ресурсы — Digital Library',
      'eyebrow' => 'Электронные ресурсы',
      'hero' => 'Цифровые коллекции и научные базы данных',
      'lead' => 'Доступ к электронным учебникам, международным научным базам, лицензированным платформам и открытым образовательным ресурсам.',
      'banner' => 'Digital Library',
      'banner_body' => 'Откройте основной библиотечный фонд: более 50 000 единиц — учебники, монографии, методические материалы и периодика в электронном и печатном формате.',
      'support_label' => 'Поддержка преподавателей',
      'support_title' => 'Подборка литературы для дисциплин и исследований',
      'support_body' => 'Инструменты для силлабуса теперь встроены в основной библиотечный маршрут: ищите книги в каталоге, добавляйте источники в подборку и дополняйте её электронными ресурсами без отдельной страницы.',
      'faq' => 'Частые вопросы',
      'faq_help' => 'Ответы на основные вопросы о работе с электронными ресурсами библиотеки.',
    ],
    'kk' => [
      'meta' => 'Электрондық ресурстар — Digital Library',
      'eyebrow' => 'Электрондық ресурстар',
      'hero' => 'Цифрлық коллекциялар мен ғылыми дерекқорлар',
      'lead' => 'Электрондық оқулықтарға, халықаралық ғылыми базаларға, лицензиялық платформаларға және ашық білім беру ресурстарына қолжетімділік.',
      'banner' => 'Digital Library',
      'banner_body' => 'Негізгі кітапхана қорын ашыңыз: 50 000-нан астам оқу, ғылыми және әдістемелік материалдар цифрлық және баспа форматында қолжетімді.',
      'support_label' => 'Оқытушыларға қолдау',
      'support_title' => 'Пәндер мен зерттеулерге арналған әдебиеттер топтамасы',
      'support_body' => 'Силлабусқа арналған құралдар енді негізгі кітапхана бағытына біріктірілді: каталогтан іздеңіз, материалдарды топтамаға қосыңыз және электрондық ресурстармен толықтырыңыз.',
      'faq' => 'Жиі қойылатын сұрақтар',
      'faq_help' => 'Кітапхананың электрондық ресурстары бойынша негізгі жауаптар.',
    ],
    'en' => [
      'meta' => 'Electronic resources — Digital Library',
      'eyebrow' => 'Electronic resources',
      'hero' => 'Digital collections and research databases',
      'lead' => 'Access electronic textbooks, international scholarly databases, licensed platforms, and open educational resources from one modern library entry point.',
      'banner' => 'Digital Library',
      'banner_body' => 'Open the main library collection: 50,000+ textbooks, monographs, teaching materials, and periodicals across digital and print formats.',
      'support_label' => 'Faculty support',
      'support_title' => 'Reading lists for courses and research',
      'support_body' => 'The syllabus workflow now lives inside the main library experience: search the catalog, collect sources into a shortlist, and enrich them with electronic resources without a separate landing page.',
      'faq' => 'Frequently asked questions',
      'faq_help' => 'Answers to the main questions about the library’s electronic resources.',
    ],
  ][$lang];
@endphp

@section('title', $copy['meta'])

@section('head')
<style>
  .resource-hero-stats {
    display: flex;
    justify-content: center;
    gap: 48px;
    margin-top: 36px;
  }
  .resource-hero-stats .rh-stat {
    text-align: center;
  }
  .resource-hero-stats .rh-stat strong {
    display: block;
    font-size: 36px;
    font-weight: 900;
    letter-spacing: -1px;
    color: var(--blue);
  }
  .resource-hero-stats .rh-stat span {
    font-size: 14px;
    color: var(--muted);
    font-weight: 600;
  }

  .resource-hero-panels {
    margin-top: 28px;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
  }
  .resource-hero-panel {
    text-align: left;
    padding: 18px;
    border-radius: var(--radius-lg);
    background: #fff;
    border: 1px solid var(--border);
    box-shadow: none;
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .resource-hero-panel:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(20,105,109,.18);
  }
  .resource-hero-panel span {
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--violet);
    margin-bottom: 8px;
  }
  .resource-hero-panel strong {
    display: block;
    font-size: 18px;
    line-height: 1.25;
    margin-bottom: 6px;
    color: var(--blue);
    font-family: 'Newsreader', Georgia, serif;
    font-weight: 700;
  }
  .resource-hero-panel p {
    margin: 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.6;
  }

  /* Compact local catalog banner */
  .local-catalog-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 28px 32px;
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: none;
    transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .local-catalog-banner:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 16px 32px rgba(25, 28, 29, 0.05);
    border-color: rgba(0,30,64,.12);
  }
  .local-catalog-banner h2 { margin: 0 0 6px; font-size: 22px; font-weight: 800; font-family: 'Newsreader', Georgia, serif; }
  .local-catalog-banner p { margin: 0; color: var(--muted); font-size: 15px; line-height: 1.6; }
  .local-catalog-banner .btn { white-space: nowrap; flex-shrink: 0; }

  /* Compact access guide */
  .access-inline {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
  }
  .access-chip {
    padding: 12px 18px;
    border-radius: var(--radius-md);
    font-size: 14px;
    line-height: 1.4;
    border: 1px solid var(--border);
    background: #fff;
  }
  .access-chip--campus { border-color: rgba(0,30,64,.16); background: rgba(0,30,64,.03); }
  .access-chip--remote { border-color: rgba(20,105,109,.16); background: rgba(20,105,109,.04); }
  .access-chip--open { border-color: rgba(27,109,113,.16); background: rgba(27,109,113,.05); }

  .resource-section-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    align-items: start;
  }
  .resource-section-info {
    padding: 8px 0;
  }
  .resource-section-info h2 {
    margin: 0 0 12px;
    font-size: clamp(26px, 3.5vw, 38px);
    font-family: 'Newsreader', Georgia, serif;
    font-weight: 700;
    letter-spacing: -.5px;
  }
  .resource-section-info p {
    color: var(--muted);
    font-size: 16px;
    line-height: 1.75;
    margin: 0 0 20px;
  }
  .resource-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 14px;
  }
  .resource-list-item {
    display: flex;
    gap: 16px;
    padding: 20px;
    border-radius: var(--radius-md);
    background: #fff;
    border: 1px solid var(--border);
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), background .2s ease, box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }
  .resource-list-item:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25,28,29,.05);
    background: rgba(243,244,245,.96);
    border-color: rgba(20,105,109,.18);
  }
  .resource-list-item .rli-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: grid;
    place-items: center;
    font-size: 22px;
    flex-shrink: 0;
    color: #fff;
  }
  .resource-list-item .rli-icon--blue { background: linear-gradient(135deg, var(--blue), var(--cyan)); }
  .resource-list-item .rli-icon--violet { background: linear-gradient(135deg, var(--violet), var(--pink)); }
  .resource-list-item .rli-icon--green { background: linear-gradient(135deg, var(--green), var(--cyan)); }
  .resource-list-item .rli-icon--pink { background: linear-gradient(135deg, var(--pink), #f97316); }
  .resource-list-item h4 { margin: 0 0 4px; font-size: 17px; font-weight: 700; }
  .resource-list-item p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.6; }

  .access-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }
  .access-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 32px 28px;
    text-align: center;
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), background .2s ease, box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }
  .access-card:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25,28,29,.05);
    background: rgba(243,244,245,.96);
    border-color: rgba(0,30,64,.12);
  }
  .access-card .ac-icon {
    width: 64px;
    height: 64px;
    border-radius: 8px;
    display: grid;
    place-items: center;
    font-size: 28px;
    margin: 0 auto 18px;
    color: #fff;
  }
  .access-card h3 { margin: 0 0 10px; font-size: 20px; font-weight: 800; }
  .access-card p { margin: 0 0 18px; color: var(--muted); font-size: 15px; line-height: 1.65; }
  .access-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
  }
  .access-badge--campus { background: rgba(0,30,64,.08); color: var(--blue); }
  .access-badge--remote { background: rgba(20,105,109,.10); color: var(--cyan); }
  .access-badge--open { background: rgba(42,28,0,.08); color: var(--violet); }

  .resource-policy-note {
    margin-top: 16px;
    padding: 14px 16px;
    border-radius: 8px;
    background: rgba(23,60,107,.04);
    border: 1px solid rgba(23,60,107,.10);
    color: var(--text);
    font-size: 14px;
    line-height: 1.6;
  }

  .resource-access-matrix {
    margin-top: 18px;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }
  .resource-access-card {
    padding: 18px;
    border-radius: var(--radius-lg);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    border: 1px solid var(--border);
    box-shadow: none;
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .resource-access-card:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25,28,29,.05);
    border-color: rgba(20,105,109,.18);
  }
  .resource-access-card strong {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    color: var(--text);
  }
  .resource-access-card p {
    margin: 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.6;
  }

  .faq-list {
    display: grid;
    gap: 16px;
    max-width: 800px;
    margin: 32px auto 0;
  }
  .faq-item {
    padding: 24px;
    border-radius: var(--radius-md);
    background: var(--surface-glass);
    border: 1px solid var(--border);
  }
  .faq-item h4 { margin: 0 0 8px; font-size: 17px; font-weight: 700; }
  .faq-item p { margin: 0; color: var(--muted); font-size: 15px; line-height: 1.65; }

  @media (max-width: 900px) {
    .resource-section-grid { grid-template-columns: 1fr; }
    .access-inline { flex-direction: column; }
    .local-catalog-banner { flex-direction: column; align-items: flex-start; }
    .resource-hero-stats { gap: 24px; flex-wrap: wrap; }
    .resource-hero-panels,
    .resource-access-matrix { grid-template-columns: 1fr; }
  }

  @media (max-width: 680px) {
    .resource-hero-stats { gap: 16px; justify-content: space-around; }
    .resource-hero-stats .rh-stat strong { font-size: 28px; }
    .resource-hero-stats .rh-stat span { font-size: 12px; }
    .resource-list-item { padding: 16px; gap: 12px; }
    .resource-list-item .rli-icon { width: 40px; height: 40px; font-size: 18px; border-radius: 12px; }
    .resource-list-item h4 { font-size: 15px; }
    .resource-list-item p { font-size: 13px; }
    .access-card { padding: 20px 16px; }
    .access-card .ac-icon { width: 52px; height: 52px; font-size: 24px; }
    .access-card h3 { font-size: 18px; }
    .access-card p { font-size: 14px; }
    .faq-item { padding: 18px; }
    .faq-item h4 { font-size: 15px; }
    .faq-item p { font-size: 14px; }
    .faq-list { margin-top: 20px; gap: 12px; }
  }

  @media (max-width: 480px) {
    .resource-hero-stats { flex-direction: column; gap: 8px; align-items: center; }
    .resource-hero-stats .rh-stat { display: flex; gap: 8px; align-items: baseline; }
    .resource-hero-stats .rh-stat strong { font-size: 24px; }
    .resource-list-item .rli-icon { width: 36px; height: 36px; font-size: 16px; }
    .access-card { padding: 16px; }
    .ext-filter-bar { gap: 6px; }
    .ext-filter-btn { padding: 6px 12px; font-size: 12px; }
  }

  /* External resources filter bar */
  .ext-filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
  }
  .ext-filter-btn {
    padding: 8px 18px;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: var(--surface-glass);
    font-size: 14px;
    font-weight: 600;
    color: var(--muted);
    cursor: pointer;
    transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }
  .ext-filter-btn:hover {
    border-color: var(--blue);
    color: var(--blue);
    transform: translate3d(0, -1px, 0);
  }
  .ext-filter-btn--active {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
  }
  .ext-filter-btn--active:hover {
    color: #fff;
  }

  /* External resources grid */
  .ext-resources-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
  }
  .ext-resource-card {
    background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(247,244,238,.92));
    border: 1px solid var(--border);
    border-top: 3px solid rgba(23,60,107,.16);
    border-radius: var(--radius-lg);
    padding: 28px;
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .2s ease, background-color .2s ease, box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1);
    display: flex;
    flex-direction: column;
  }
  .ext-resource-card:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25,28,29,.05);
    border-color: rgba(0,30,64,.12);
  }
  .ext-resource-card__header {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 14px;
  }
  .ext-resource-card__icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: grid;
    place-items: center;
    font-size: 22px;
    flex-shrink: 0;
    color: #fff;
  }
  .ext-resource-card__icon--blue { background: linear-gradient(135deg, var(--blue), #214c6f); }
  .ext-resource-card__icon--violet { background: linear-gradient(135deg, var(--violet), var(--pink)); }
  .ext-resource-card__icon--green { background: linear-gradient(135deg, var(--cyan), #1b6d71); }
  .ext-resource-card__icon--pink { background: linear-gradient(135deg, var(--pink), #5d4201); }
  .ext-resource-card__title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
  }
  .ext-resource-card__provider {
    font-size: 13px;
    color: var(--muted);
    margin-top: 2px;
  }
  .ext-resource-card__desc {
    color: var(--muted);
    font-size: 14px;
    line-height: 1.65;
    margin: 0 0 16px;
    flex: 1;
  }
  .ext-resource-card__footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-top: auto;
  }
  .ext-resource-card__badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
  }
  .ext-resource-card__actions {
    display: flex;
    gap: 8px;
    margin-top: 14px;
  }
  .ext-resource-card__actions a,
  .ext-resource-card__actions button {
    font-size: 13px;
    font-weight: 600;
    padding: 7px 16px;
    border-radius: 10px;
    text-decoration: none;
    transition: all .2s;
    cursor: pointer;
  }
  .ext-resource-card__actions .ext-link-btn {
    background: var(--blue);
    color: #fff;
    border: none;
  }
  .ext-resource-card__actions .ext-link-btn:hover {
    opacity: .85;
  }
  .ext-resource-card__actions .ext-shortlist-btn {
    background: transparent;
    color: var(--muted);
    border: 1px solid var(--border);
  }
  .ext-resource-card__actions .ext-shortlist-btn:hover {
    border-color: var(--violet);
    color: var(--violet);
  }
  .ext-resource-card__actions .ext-shortlist-btn--added {
    border-color: var(--green);
    color: var(--green);
    pointer-events: none;
  }
  .ext-resource-card__expiry {
    font-size: 12px;
    color: var(--muted);
    margin-left: auto;
  }

  @media (max-width: 680px) {
    .ext-resources-grid { grid-template-columns: 1fr; }
    .ext-resource-card { padding: 20px; }
    .ext-resource-card__icon { width: 40px; height: 40px; font-size: 18px; }
    .ext-resource-card__title { font-size: 16px; }
  }
</style>
@endsection

@section('content')
<div class="page-hero">
  <div class="container">
    <div class="eyebrow">{{ $copy['eyebrow'] }}</div>
    <h1>{{ $copy['hero'] }}</h1>
    <p>{{ $copy['lead'] }}</p>
    <div class="resource-hero-stats">
      <div class="rh-stat"><strong id="stat-total">—</strong><span>{{ ['ru' => 'внешних ресурсов', 'kk' => 'сыртқы ресурс', 'en' => 'external resources'][$lang] }}</span></div>
      <div class="rh-stat"><strong>50 000+</strong><span>{{ ['ru' => 'электронных документов', 'kk' => 'электрондық құжат', 'en' => 'digital documents'][$lang] }}</span></div>
      <div class="rh-stat"><strong>24/7</strong><span>{{ ['ru' => 'удалённый доступ', 'kk' => 'қашықтан қолжетімділік', 'en' => 'remote access'][$lang] }}</span></div>
      <div class="rh-stat"><strong>3</strong><span>{{ ['ru' => 'режима доступа', 'kk' => 'қолжетімділік режимі', 'en' => 'access modes'][$lang] }}</span></div>
    </div>

    <div class="resource-hero-panels" id="resource-hero-panels">
      <article class="resource-hero-panel">
        <span>{{ ['ru' => 'Лицензионный доступ', 'kk' => 'Лицензиялық қолжетімділік', 'en' => 'Licensed access'][$lang] }}</span>
        <strong>{{ ['ru' => 'Подписные базы и цифровая библиотека', 'kk' => 'Жазылым дерекқорлары мен цифрлық кітапхана', 'en' => 'Subscribed databases and the digital library'][$lang] }}</strong>
        <p>{{ ['ru' => 'Соберите маршрут от каталога к внешней платформе без отдельного преподавательского лендинга.', 'kk' => 'Каталогтан сыртқы платформаға дейінгі маршрутты бөлек оқытушы лендингінсіз жинаңыз.', 'en' => 'Move from the catalog to the external platform without a separate faculty landing page.'][$lang] }}</p>
      </article>
      <article class="resource-hero-panel">
        <span>{{ ['ru' => 'Удалённая работа', 'kk' => 'Қашықтан жұмыс', 'en' => 'Remote work'][$lang] }}</span>
        <strong>{{ ['ru' => 'Удалённый сценарий для учебы и исследований', 'kk' => 'Оқу мен зерттеуге арналған қашықтан сценарий', 'en' => 'Remote flows for study and research'][$lang] }}</strong>
        <p>{{ ['ru' => 'Переходите к ресурсам из кабинета, подборки литературы и страницы доступа в едином стиле.', 'kk' => 'Ресурстарға кабинеттен, әдебиеттер топтамасынан және қолжетімділік бетінен бірізді стильде өтіңіз.', 'en' => 'Open resources from the account, reading list, and access surfaces within one shared shell.'][$lang] }}</p>
      </article>
      <article class="resource-hero-panel">
        <span>{{ ['ru' => 'Открытое знание', 'kk' => 'Ашық білім', 'en' => 'Open knowledge'][$lang] }}</span>
        <strong>{{ ['ru' => 'Открытые коллекции и академические сервисы', 'kk' => 'Ашық коллекциялар мен академиялық сервистер', 'en' => 'Open collections and academic services'][$lang] }}</strong>
        <p>{{ ['ru' => 'Разделяйте кампусные, удалённые и открытые источники по понятным статусам и подсказкам.', 'kk' => 'Кампус ішіндегі, қашықтан және ашық ресурстарды түсінікті мәртебелер арқылы ажыратыңыз.', 'en' => 'Distinguish campus-only, remote, and open resources through clear status labels and guidance.'][$lang] }}</p>
      </article>
    </div>
  </div>
</div>

{{-- Local library — compact banner --}}
<section class="page-section">
  <div class="container">
    <div class="local-catalog-banner">
      <div>
        <h2>{{ $copy['banner'] }}</h2>
        <p>{{ $copy['banner_body'] }}</p>
        <div class="resource-policy-note" id="resource-policy-note">{{ ['ru' => 'Эта страница теперь выстроена как единый академический маршрут: сначала понимаем режим доступа, затем открываем внешний ресурс или продолжаем работу через каталог и подборку литературы.', 'kk' => 'Бұл бет енді бірыңғай академиялық маршрут ретінде құрылған: алдымен қолжетімділік режимін түсінеміз, содан кейін сыртқы ресурсты ашамыз немесе каталог пен әдебиеттер топтамасы арқылы жұмысты жалғастырамыз.', 'en' => 'This page now follows one academic route: first confirm the access mode, then open the external resource or continue through the catalog and reading list workbench.'][$lang] }}</div>
      </div>
      <a href="{{ $routeWithLang('/catalog') }}" class="btn btn-primary">{{ ['ru' => 'Перейти в каталог', 'kk' => 'Каталогқа өту', 'en' => 'Open catalog'][$lang] }}</a>
    </div>
  </div>
</section>

<section class="page-section">
  <div class="container">
    <div class="resource-section-grid">
      <div class="resource-section-info">
        <div class="eyebrow eyebrow--violet">{{ $copy['support_label'] }}</div>
        <h2>{{ $copy['support_title'] }}</h2>
        <p>{{ $copy['support_body'] }}</p>
        <div style="display:flex; gap:12px; flex-wrap:wrap;">
          <a href="{{ $routeWithLang('/shortlist') }}" class="btn btn-primary">{{ ['ru' => 'Открыть подборку', 'kk' => 'Іріктемені ашу', 'en' => 'Open shortlist'][$lang] }}</a>
          <a href="{{ $routeWithLang('/catalog') }}" class="btn btn-ghost">{{ ['ru' => 'Искать в каталоге', 'kk' => 'Каталогтан іздеу', 'en' => 'Search the catalog'][$lang] }}</a>
        </div>
      </div>

      <ul class="resource-list">
        <li class="resource-list-item">
          <div class="rli-icon rli-icon--violet">📋</div>
          <div>
            <h4>{{ ['ru' => 'Подборка литературы', 'kk' => 'Әдебиеттер топтамасы', 'en' => 'Shortlist'][$lang] }}</h4>
            <p>{{ ['ru' => 'Собирайте учебники, монографии и внешние базы в единый черновик для силлабуса или рабочей программы.', 'kk' => 'Оқулықтарды, монографияларды және сыртқы базаларды силлабус не оқу бағдарламасына арналған бір жұмыс нұсқасына жинаңыз.', 'en' => 'Collect textbooks, monographs, and external databases into one working draft for a syllabus or course plan.'][$lang] }}</p>
          </div>
        </li>
        <li class="resource-list-item">
          <div class="rli-icon rli-icon--blue">🔎</div>
          <div>
            <h4>{{ ['ru' => 'Поиск по направлениям', 'kk' => 'Бағыттар бойынша іздеу', 'en' => 'Browse subjects'][$lang] }}</h4>
            <p>{{ ['ru' => 'Используйте каталог и раздел направлений, чтобы быстро отобрать литературу по теме курса или исследовательскому треку.', 'kk' => 'Каталог пен бағыттар бөлімін пайдаланып, курс тақырыбы немесе зерттеу бағыты бойынша әдебиетті жылдам іріктеңіз.', 'en' => 'Use the catalog and the subject area to quickly narrow literature by course topic or research track.'][$lang] }}</p>
          </div>
        </li>
        <li class="resource-list-item">
          <div class="rli-icon rli-icon--green">🌐</div>
          <div>
            <h4>{{ ['ru' => 'Электронные ресурсы', 'kk' => 'Электрондық ресурстар', 'en' => 'Electronic resources'][$lang] }}</h4>
            <p>{{ ['ru' => 'Дополняйте список лицензированными платформами и открытыми научными коллекциями с учётом режима доступа.', 'kk' => 'Тізімді қолжетімділік режимін ескере отырып, лицензиялық платформалармен және ашық ғылыми коллекциялармен толықтырыңыз.', 'en' => 'Extend the list with licensed platforms and open scholarly collections while keeping the access mode clear.'][$lang] }}</p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>

{{-- External licensed resources section — loaded from API --}}
<section class="page-section">
  <div class="container">
    <div class="section-head">
      <div>
        <div class="eyebrow eyebrow--violet">{{ ['ru' => 'Внешние лицензированные ресурсы', 'kk' => 'Сыртқы лицензиялық ресурстар', 'en' => 'External licensed resources'][$lang] }}</div>
        <h2>{{ ['ru' => 'Подписные платформы и научные базы данных', 'kk' => 'Жазылым платформалары мен ғылыми дерекқорлар', 'en' => 'Subscribed platforms and research databases'][$lang] }}</h2>
        <p>{{ ['ru' => 'Внешние электронные ресурсы, доступные пользователям платформы по подписке или в открытом доступе. Это не материалы библиотечного фонда — каждый ресурс размещён на внешней платформе со своими условиями доступа.', 'kk' => 'Платформа пайдаланушыларына жазылым немесе ашық қолжетімділік арқылы берілетін сыртқы электрондық ресурстар. Бұл кітапхана қорының материалдары емес — әр ресурс өзінің шарттары бар сыртқы платформада орналасқан.', 'en' => 'External electronic resources available to platform users through subscription or open access. These are not library collection holdings — each resource lives on its own external platform with its own access rules.'][$lang] }}</p>
      </div>
    </div>

    <div class="ext-filter-bar" id="ext-filter-bar">
      <button class="ext-filter-btn ext-filter-btn--active" data-filter="all">{{ ['ru' => 'Все', 'kk' => 'Барлығы', 'en' => 'All'][$lang] }}</button>
    </div>

    <div id="ext-resources-loading" style="text-align:center; padding:32px;">
      <div style="display:inline-block;width:28px;height:28px;border:3px solid #e5e7eb;border-top-color:var(--blue);border-radius:50%;animation:spin .7s linear infinite;"></div>
      <p style="margin:8px 0 0; color:var(--muted); font-size:14px;">{{ ['ru' => 'Загрузка ресурсов...', 'kk' => 'Ресурстар жүктелуде...', 'en' => 'Loading resources...'][$lang] }}</p>
    </div>

    <div id="ext-resources-grid" class="ext-resources-grid" style="display:none;"></div>
  </div>
</section>

<section class="page-section">
  <div class="container">
    <h2 style="margin-bottom: 16px;">{{ ['ru' => 'Режимы доступа', 'kk' => 'Қолжетімділік режимдері', 'en' => 'Access modes'][$lang] }}</h2>
    <div class="access-inline">
      <div class="access-chip access-chip--campus">🏫 <strong>{{ ['ru' => 'Из кампуса', 'kk' => 'Кампустан', 'en' => 'On campus'][$lang] }}</strong> — {{ ['ru' => 'автоматически через Wi‑Fi и компьютеры залов', 'kk' => 'Wi‑Fi және оқу залдарының компьютерлері арқылы автоматты түрде', 'en' => 'automatically through campus Wi‑Fi and library workstations'][$lang] }}</div>
      <div class="access-chip access-chip--remote">🌐 <strong>{{ ['ru' => 'Удалённо', 'kk' => 'Қашықтан', 'en' => 'Remote'][$lang] }}</strong> — {{ ['ru' => 'через личный кабинет библиотеки', 'kk' => 'кітапхананың жеке кабинеті арқылы', 'en' => 'through the library account'][$lang] }}</div>
      <div class="access-chip access-chip--open">🔓 <strong>{{ ['ru' => 'Открытый доступ', 'kk' => 'Ашық қолжетімділік', 'en' => 'Open access'][$lang] }}</strong> — {{ ['ru' => 'без ограничений', 'kk' => 'шектеусіз', 'en' => 'without restrictions'][$lang] }}</div>
    </div>

    <div class="resource-access-matrix" id="resource-access-matrix">
      <article class="resource-access-card">
        <strong>{{ ['ru' => '1. Найдите источник', 'kk' => '1. Дереккөзді табыңыз', 'en' => '1. Find the source'][$lang] }}</strong>
        <p>{{ ['ru' => 'Начните с каталога или из блока подписных платформ, чтобы понять, где находится нужный материал.', 'kk' => 'Қажетті материалдың қайда орналасқанын түсіну үшін каталогтан немесе жазылым платформалары блогынан бастаңыз.', 'en' => 'Start from the catalog or the subscribed-platform block to understand where the needed material lives.'][$lang] }}</p>
      </article>
      <article class="resource-access-card">
        <strong>{{ ['ru' => '2. Проверьте режим доступа', 'kk' => '2. Қолжетімділік режимін тексеріңіз', 'en' => '2. Check the access mode'][$lang] }}</strong>
        <p>{{ ['ru' => 'Ориентируйтесь на кампусные, удалённые и открытые статусы — они показывают реальный сценарий входа.', 'kk' => 'Кампус, қашықтан және ашық мәртебелерге назар аударыңыз — олар нақты кіру сценарийін көрсетеді.', 'en' => 'Use the campus, remote, and open statuses to understand the real entry flow.'][$lang] }}</p>
      </article>
      <article class="resource-access-card">
        <strong>{{ ['ru' => '3. Добавьте в рабочую подборку', 'kk' => '3. Жұмыс топтамасына қосыңыз', 'en' => '3. Add it to the working shortlist'][$lang] }}</strong>
        <p>{!! ['ru' => 'Если ресурс нужен для курса или исследования, сразу отправляйте его в <a href="' . $routeWithLang('/shortlist') . '" style="color:var(--blue);font-weight:700;">подборку литературы</a>.', 'kk' => 'Егер ресурс курсқа немесе зерттеуге қажет болса, оны бірден <a href="' . $routeWithLang('/shortlist') . '" style="color:var(--blue);font-weight:700;">әдебиеттер топтамасына</a> жіберіңіз.', 'en' => 'If the resource is needed for a course or research, send it straight to the <a href="' . $routeWithLang('/shortlist') . '" style="color:var(--blue);font-weight:700;">reading list workbench</a>.'][$lang] !!}</p>
      </article>
    </div>
  </div>
</section>

<section class="page-section">
  <div class="container">
    <div class="section-head section-head-centered">
      <div>
        <h2>{{ $copy['faq'] }}</h2>
        <p>{{ $copy['faq_help'] }}</p>
      </div>
    </div>

    <div class="faq-list">
      <div class="faq-item">
        <h4>{{ ['ru' => 'Как получить удалённый доступ?', 'kk' => 'Қашықтан қолжетімділікті қалай алуға болады?', 'en' => 'How do I get remote access?'][$lang] }}</h4>
        <p>{!! ['ru' => 'Войдите в <a href="' . $routeWithLang('/account') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">личный кабинет</a> — после авторизации подписные ресурсы будут доступны из любой точки.', 'kk' => '<a href="' . $routeWithLang('/account') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">Жеке кабинетке</a> кіріңіз — авторизациядан кейін жазылым ресурстары кез келген жерден қолжетімді болады.', 'en' => 'Sign in to your <a href="' . $routeWithLang('/account') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">account</a> and the subscribed resources will be available from any location.'][$lang] !!}</p>
      </div>
      <div class="faq-item">
        <h4>{{ ['ru' => 'Можно ли скачивать материалы?', 'kk' => 'Материалдарды жүктеп алуға бола ма?', 'en' => 'Can I download the materials?'][$lang] }}</h4>
        <p>{{ ['ru' => 'Зависит от лицензии ресурса. Некоторые материалы доступны только для просмотра.', 'kk' => 'Бұл ресурс лицензиясына байланысты. Кейбір материалдар тек қарауға ғана ашылады.', 'en' => 'It depends on the resource license. Some materials are available for viewing only.'][$lang] }}</p>
      </div>
      <div class="faq-item">
        <h4>{{ ['ru' => 'Нужна помощь с подбором литературы?', 'kk' => 'Әдебиеттерді іріктеуге көмек керек пе?', 'en' => 'Need help building a reading list?'][$lang] }}</h4>
        <p>{!! ['ru' => 'Обратитесь в библиографический отдел через <a href="' . $routeWithLang('/contacts') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">контакты</a>, откройте <a href="' . $routeWithLang('/shortlist') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">подборку литературы</a> или начните поиск в <a href="' . $routeWithLang('/catalog') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">каталоге</a>.', 'kk' => 'Библиографиялық бөлімге <a href="' . $routeWithLang('/contacts') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">байланыс беті</a> арқылы жүгініңіз, <a href="' . $routeWithLang('/shortlist') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">әдебиеттер топтамасын</a> ашыңыз немесе іздеуді <a href="' . $routeWithLang('/catalog') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">каталогтан</a> бастаңыз.', 'en' => 'Reach the bibliographic team through <a href="' . $routeWithLang('/contacts') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">contacts</a>, open the <a href="' . $routeWithLang('/shortlist') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">reading list workbench</a>, or begin the search in the <a href="' . $routeWithLang('/catalog') . '" style="color:var(--blue);font-weight:600;text-decoration:none;">catalog</a>.'][$lang] !!}</p>
      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')
<script>
(function() {
  const API_URL = '/api/v1/external-resources';
  const SHORTLIST_API = '/api/v1/shortlist';
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
  const RES_LANG = @json($lang);
  const RES_I18N_MAP = {
    ru: {
      all: 'Все',
      validUntil: 'Действует',
      open: 'Перейти ↗',
      add: '+ В подборку',
      added: '✓ В подборке',
      loadingFailed: 'Не удалось загрузить внешние ресурсы.',
    },
    kk: {
      all: 'Барлығы',
      validUntil: 'Қолданыста',
      open: 'Ашу ↗',
      add: '+ Топтамаға',
      added: '✓ Топтамада',
      loadingFailed: 'Сыртқы ресурстарды жүктеу мүмкін болмады.',
    },
    en: {
      all: 'All',
      validUntil: 'Available until',
      open: 'Open ↗',
      add: '+ Add to shortlist',
      added: '✓ In shortlist',
      loadingFailed: 'Unable to load external resources.',
    },
  };
  const RES_I18N = RES_I18N_MAP[RES_LANG] || RES_I18N_MAP.ru;

  const accessBadgeClass = {
    campus: 'access-badge--campus',
    remote_auth: 'access-badge--remote',
    open: 'access-badge--open'
  };

  const iconColorMap = {
    electronic_library: 'blue',
    research_database: 'violet',
    open_access: 'green',
    analytics: 'pink'
  };

  let allResources = [];
  let categories = {};
  let accessTypes = {};
  let shortlistedIds = new Set();

  function escapeHtml(text) {
    if (!text) return '';
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
  }

  function formatExpiry(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    const months = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
    return `до ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
  }

  function renderFilterBar() {
    const bar = document.getElementById('ext-filter-bar');
    const usedCats = [...new Set(allResources.map(r => r.category))];
    let html = `<button class="ext-filter-btn ext-filter-btn--active" data-filter="all">${RES_I18N.all} (${allResources.length})</button>`;
    usedCats.forEach(cat => {
      const info = categories[cat] || {};
      const count = allResources.filter(r => r.category === cat).length;
      html += `<button class="ext-filter-btn" data-filter="${escapeHtml(cat)}">${escapeHtml(info.icon || '')} ${escapeHtml(info.label || cat)} (${count})</button>`;
    });
    bar.innerHTML = html;
    bar.querySelectorAll('.ext-filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        bar.querySelectorAll('.ext-filter-btn').forEach(b => b.classList.remove('ext-filter-btn--active'));
        btn.classList.add('ext-filter-btn--active');
        renderResources(btn.dataset.filter);
      });
    });
  }

  function renderResources(filter) {
    const grid = document.getElementById('ext-resources-grid');
    const filtered = filter === 'all' ? allResources : allResources.filter(r => r.category === filter);

    grid.innerHTML = filtered.map(r => {
      const catInfo = categories[r.category] || {};
      const accInfo = accessTypes[r.access_type] || {};
      const color = iconColorMap[r.category] || 'blue';
      const badgeClass = accessBadgeClass[r.access_type] || 'access-badge--campus';
      const inShortlist = shortlistedIds.has('ext:' + r.slug);

      return `
        <div class="ext-resource-card" data-slug="${escapeHtml(r.slug)}">
          <div class="ext-resource-card__header">
            <div class="ext-resource-card__icon ext-resource-card__icon--${color}">${escapeHtml(catInfo.icon || '📄')}</div>
            <div>
              <h3 class="ext-resource-card__title">${escapeHtml(r.title)}</h3>
              <div class="ext-resource-card__provider">${escapeHtml(r.provider)}</div>
            </div>
          </div>
          <p class="ext-resource-card__desc">${escapeHtml(r.description)}</p>
          <div class="ext-resource-card__footer">
            <span class="ext-resource-card__badge ${badgeClass}">${escapeHtml(accInfo.label || r.access_type)}</span>
            ${r.expiry_date ? `<span class="ext-resource-card__expiry">${RES_I18N.validUntil} ${formatExpiry(r.expiry_date)}</span>` : ''}
          </div>
          <div class="ext-resource-card__actions">
            ${r.url ? `<a href="${escapeHtml(r.url)}" target="_blank" rel="noopener" class="ext-link-btn">${RES_I18N.open}</a>` : ''}
            <button class="ext-shortlist-btn ${inShortlist ? 'ext-shortlist-btn--added' : ''}"
              onclick="addExtToShortlist(this, '${escapeHtml(r.slug)}')"
              ${inShortlist ? 'disabled' : ''}>
              ${inShortlist ? RES_I18N.added : RES_I18N.add}
            </button>
          </div>
        </div>
      `;
    }).join('');

    grid.style.display = 'grid';
  }

  window.addExtToShortlist = async function(btn, slug) {
    const resource = allResources.find(r => r.slug === slug);
    if (!resource) return;

    btn.disabled = true;
    btn.textContent = '...';

    try {
      const res = await fetch(SHORTLIST_API, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF
        },
        credentials: 'same-origin',
        body: JSON.stringify({
          identifier: 'ext:' + slug,
          title: resource.title,
          type: 'external_resource',
          provider: resource.provider,
          url: resource.url || null,
          access_type: resource.access_type
        })
      });

      if (res.ok || res.status === 409) {
        btn.textContent = RES_I18N.added;
        btn.classList.add('ext-shortlist-btn--added');
        shortlistedIds.add('ext:' + slug);
      } else {
        btn.textContent = RES_I18N.add;
        btn.disabled = false;
      }
    } catch (e) {
      btn.textContent = '+ В подборку';
      btn.disabled = false;
    }
  };

  async function loadShortlistState() {
    try {
      const res = await fetch(SHORTLIST_API, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
      if (res.ok) {
        const json = await res.json();
        (json.data || []).forEach(item => {
          if (item.identifier) shortlistedIds.add(item.identifier);
        });
      }
    } catch (_) {}
  }

  async function init() {
    const loading = document.getElementById('ext-resources-loading');
    try {
      const [resResponse] = await Promise.all([
        fetch(API_URL, { headers: { Accept: 'application/json' } }),
        loadShortlistState()
      ]);

      if (!resResponse.ok) throw new Error('API error');

      const json = await resResponse.json();
      allResources = json.data || [];
      categories = json.meta?.categories || {};
      accessTypes = json.meta?.access_types || {};

      document.getElementById('stat-total').textContent = allResources.length + '+';
      loading.style.display = 'none';
      renderFilterBar();
      renderResources('all');
    } catch (e) {
      loading.innerHTML = `<p style="color:var(--muted);">${RES_I18N.loadingFailed}</p>`;
      console.error('External resources load error:', e);
    }
  }

  init();
})();
</script>
@endsection
