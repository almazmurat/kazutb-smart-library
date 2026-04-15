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
    :root {
      --res-bg: #f4f6f8;
      --res-surface: #ffffff;
      --res-border: rgba(179, 191, 207, .55);
      --res-text: #0f1f3a;
      --res-muted: #526071;
      --res-ink: #0b2347;
      --res-accent: #14696d;
      --res-accent-soft: rgba(20, 105, 109, .08);
      --res-shadow: 0 12px 30px rgba(15, 23, 42, .05);
      --res-space-1: 8px;
      --res-space-2: 12px;
      --res-space-3: 16px;
      --res-space-4: 24px;
      --res-space-5: 32px;
    }

    .resources-shell {
      background: var(--res-bg);
      padding: var(--shell-first-section-gap) 0 72px;
    }

    .resources-layout {
      display: grid;
      grid-template-columns: 264px minmax(0, 1fr);
      gap: 28px;
      align-items: start;
    }

    .support-rail {
      position: sticky;
      top: calc(var(--shell-sticky-offset) + 8px);
      background: linear-gradient(180deg, #fbfdff, #f3f6fa);
      border: 1px solid var(--res-border);
      border-radius: 2px;
      padding: 18px 14px;
      box-shadow: var(--res-shadow);
      display: grid;
      gap: 16px;
    }

    .support-rail-head h3 {
      margin: 0;
      font-family: 'Newsreader', Georgia, serif;
      font-size: 30px;
      color: var(--res-ink);
      line-height: 1;
      letter-spacing: -.2px;
    }

    .support-rail-head p {
      margin: 6px 0 0;
      font-size: 11px;
      letter-spacing: .11em;
      text-transform: uppercase;
      color: var(--res-accent);
      font-weight: 800;
    }

    .support-rail-nav {
      display: grid;
      gap: 4px;
    }

    .support-rail-link {
      display: block;
      padding: 10px 10px;
      border-left: 2px solid transparent;
      color: var(--res-muted);
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
    }

    .support-rail-link:hover,
    .support-rail-link.is-active {
      color: var(--res-ink);
      border-left-color: var(--res-accent);
      background: rgba(20,105,109,.04);
    }

    .support-rail-cta {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      min-height: 42px;
      background: var(--res-ink);
      color: #fff;
      border: 1px solid var(--res-ink);
      text-decoration: none;
      font-size: 13px;
      font-weight: 700;
    }

    .resources-main {
      display: grid;
      gap: 28px;
      align-content: start;
    }

    .hero-card {
      border: 1px solid var(--res-border);
      background: radial-gradient(circle at right top, rgba(20,105,109,.08), rgba(20,105,109,0) 38%), #fdfefe;
      box-shadow: var(--res-shadow);
      padding: 34px 38px 32px;
    }

    .hero-eyebrow {
      margin: 0 0 12px;
      font-size: 11px;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: var(--res-accent);
      font-weight: 800;
    }

    .hero-title {
      margin: 0;
      color: var(--res-ink);
      font-family: 'Newsreader', Georgia, serif;
      font-size: clamp(48px, 5.2vw, 68px);
      line-height: .97;
      letter-spacing: -.6px;
      max-width: 780px;
      text-wrap: balance;
    }

    .hero-lead {
      margin: 16px 0 0;
      color: var(--res-muted);
      font-size: 21px;
      line-height: 1.58;
      max-width: 840px;
    }

    .hero-metrics {
      margin-top: 22px;
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 10px;
    }

    .hero-metric {
      border: 1px solid var(--res-border);
      background: #fff;
      padding: 12px;
      min-height: 96px;
      display: grid;
      align-content: start;
      gap: 6px;
    }

    .hero-metric strong {
      color: var(--res-ink);
      font-family: 'Newsreader', Georgia, serif;
      font-size: 34px;
      line-height: 1;
      font-weight: 700;
      letter-spacing: -.4px;
    }

    .hero-metric span {
      color: var(--res-muted);
      font-size: 13px;
      line-height: 1.4;
      font-weight: 600;
    }

    .resource-hero-panels {
      margin-top: 18px;
      display: grid;
      grid-template-columns: minmax(0, 1.15fr) minmax(260px, .85fr);
      gap: 14px;
    }

    .resource-policy-note,
    .resource-access-matrix {
      border: 1px solid var(--res-border);
      background: #fff;
      padding: 16px 18px;
      min-height: 100%;
    }

    .resource-policy-note strong,
    .resource-access-matrix strong {
      display: block;
      margin-bottom: 8px;
      color: var(--res-ink);
      font-size: 12px;
      letter-spacing: .14em;
      text-transform: uppercase;
      font-weight: 800;
    }

    .resource-policy-note p,
    .resource-access-matrix p {
      margin: 0;
      color: var(--res-muted);
      font-size: 14px;
      line-height: 1.7;
    }

    .resource-access-modes {
      display: grid;
      gap: 8px;
      margin-top: 12px;
    }

    .resource-access-modes span {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--res-ink);
      font-size: 13px;
      font-weight: 700;
    }

    .resource-access-modes span::before {
      content: '•';
      color: var(--res-accent);
      font-size: 16px;
      line-height: 1;
    }

    .guidance-grid {
      margin-top: 16px;
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 12px;
    }

    .guidance-card {
      border: 1px solid var(--res-border);
      background: #fff;
      padding: 18px;
      min-height: 184px;
      display: grid;
      align-content: start;
      gap: 8px;
    }

    .guidance-card span {
      margin: 0;
      color: var(--res-accent);
      font-size: 10px;
      letter-spacing: .13em;
      text-transform: uppercase;
      font-weight: 800;
    }

    .guidance-card h3 {
      margin: 0;
      color: var(--res-ink);
      font-size: 34px;
      line-height: .98;
      font-family: 'Newsreader', Georgia, serif;
      letter-spacing: -.25px;
      text-wrap: balance;
    }

    .guidance-card p {
      margin: 0;
      color: var(--res-muted);
      font-size: 14px;
      line-height: 1.62;
    }

    .section-block {
      border: 1px solid var(--res-border);
      background: #fff;
      box-shadow: var(--res-shadow);
      padding: 32px;
    }

    .section-heading {
      display: grid;
      gap: 12px;
      max-width: 860px;
    }

    .section-eyebrow {
      margin: 0;
      color: var(--res-accent);
      font-size: 11px;
      letter-spacing: .16em;
      text-transform: uppercase;
      font-weight: 800;
    }

    .section-title {
      margin: 0;
      color: var(--res-ink);
      font-family: 'Newsreader', Georgia, serif;
      font-size: clamp(36px, 4vw, 50px);
      line-height: .98;
      letter-spacing: -.45px;
      text-wrap: balance;
      max-width: 14ch;
    }

    .section-lead {
      margin: 0;
      color: var(--res-muted);
      font-size: 16px;
      line-height: 1.78;
      max-width: 760px;
    }

    .ext-filter-bar {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin: 28px 0 24px;
    }

    .ext-filter-btn {
      min-height: 40px;
      padding: 0 16px;
      border: 1px solid var(--res-border);
      border-radius: 999px;
      background: #f7f9fb;
      font-size: 12px;
      letter-spacing: .02em;
      color: var(--res-muted);
      font-weight: 700;
      cursor: pointer;
      transition: border-color .18s ease, background-color .18s ease, color .18s ease, transform .18s ease;
    }

    .ext-filter-btn:hover {
      color: var(--res-ink);
      border-color: rgba(11,35,71,.22);
      background: #fff;
      transform: translateY(-1px);
    }

    .ext-filter-btn--active {
      background: var(--res-ink);
      border-color: var(--res-ink);
      color: #fff;
    }

    .ext-resources-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 18px;
      align-items: stretch;
    }

    .ext-resource-card {
      border: 1px solid rgba(171, 184, 201, .72);
      border-radius: 12px;
      background: linear-gradient(180deg, #ffffff, #fafbfd 92%);
      padding: 20px;
      min-height: 388px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background-color .18s ease;
      box-shadow: 0 10px 24px rgba(15,23,42,.035);
    }

    .ext-resource-card:hover {
      transform: translateY(-2px);
      border-color: rgba(11,35,71,.26);
      box-shadow: 0 18px 34px rgba(15,23,42,.07);
      background: linear-gradient(180deg, #ffffff, #f7fafc 100%);
    }

    .ext-resource-card__availability {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
      min-height: 30px;
    }

    .ext-resource-card__provider {
      color: #6f8094;
      font-size: 11px;
      letter-spacing: .09em;
      text-transform: uppercase;
      font-weight: 700;
      line-height: 1.35;
    }

    .ext-resource-card__header {
      display: grid;
      grid-template-columns: 44px minmax(0, 1fr);
      align-items: start;
      gap: 14px;
    }

    .ext-resource-card__icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      display: grid;
      place-items: center;
      color: #fff;
      font-size: 17px;
      flex-shrink: 0;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.18);
    }

    .ext-resource-card__icon--blue { background: linear-gradient(135deg, var(--res-ink), #214c6f); }
    .ext-resource-card__icon--violet { background: linear-gradient(135deg, #5b3f79, #8f1f5b); }
    .ext-resource-card__icon--green { background: linear-gradient(135deg, #1b6d71, #14696d); }
    .ext-resource-card__icon--pink { background: linear-gradient(135deg, #6f3a2b, #9a5a2d); }

    .ext-resource-card__title {
      margin: 0;
      color: var(--res-ink);
      font-family: 'Newsreader', Georgia, serif;
      font-size: 25px;
      line-height: 1.08;
      letter-spacing: -.18px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      min-height: 3.3em;
      text-wrap: balance;
    }

    .ext-resource-card__desc {
      margin: 0;
      color: var(--res-muted);
      font-size: 14px;
      line-height: 1.78;
      display: -webkit-box;
      -webkit-line-clamp: 5;
      -webkit-box-orient: vertical;
      overflow: hidden;
      min-height: 8.9em;
    }

    .ext-resource-card__footer {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px;
      padding-top: 2px;
      margin-top: auto;
    }

    .ext-resource-card__badge {
      display: inline-flex;
      align-items: center;
      min-height: 30px;
      padding: 0 11px;
      border-radius: 8px;
      background: #eef3f6;
      color: #314255;
      font-size: 10px;
      letter-spacing: .08em;
      text-transform: uppercase;
      font-weight: 800;
    }

    .ext-resource-card__category {
      display: inline-flex;
      align-items: center;
      min-height: 28px;
      padding: 0 10px;
      border-radius: 8px;
      background: rgba(20, 105, 109, .08);
      color: var(--res-accent);
      font-size: 10px;
      letter-spacing: .08em;
      text-transform: uppercase;
      font-weight: 800;
    }

    .access-badge--campus {
      background: rgba(15, 31, 58, .08);
      color: #0f1f3a;
    }

    .access-badge--remote {
      background: rgba(20, 105, 109, .12);
      color: #14696d;
    }

    .access-badge--open {
      background: rgba(138, 105, 45, .15);
      color: #6f4f13;
    }

    .ext-resource-card__expiry {
      color: #6b7280;
      font-size: 11px;
      font-weight: 600;
      line-height: 1.45;
    }

    .ext-resource-card__actions {
      margin-top: 2px;
      display: grid;
      grid-template-columns: minmax(0, 1.25fr) minmax(128px, .95fr);
      align-items: center;
      gap: 10px;
    }

    .ext-resource-card__actions a,
    .ext-resource-card__actions button {
      min-height: 42px;
      padding: 0 16px;
      border: 1px solid rgba(167, 181, 200, .75);
      background: #fff;
      color: var(--res-text);
      font-size: 12px;
      font-weight: 800;
      letter-spacing: .01em;
      text-decoration: none;
      cursor: pointer;
      border-radius: 10px;
      transition: border-color .18s ease, background-color .18s ease, color .18s ease, transform .18s ease;
    }

    .ext-resource-card__actions--single {
      grid-template-columns: minmax(148px, .95fr);
      justify-content: end;
    }

    .ext-resource-card__actions .ext-link-btn {
      background: var(--res-ink);
      color: #fff;
      border-color: var(--res-ink);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.08);
    }

    .ext-resource-card__actions .ext-link-btn:hover {
      background: #12345e;
      border-color: #12345e;
    }

    .ext-resource-card__actions .ext-shortlist-btn {
      background: #fff;
    }

    .ext-resource-card__actions .ext-shortlist-btn:hover {
      border-color: rgba(11,35,71,.26);
      background: #f8fafc;
      color: var(--res-ink);
      transform: translateY(-1px);
    }

    .ext-resource-card__actions .ext-shortlist-btn--added {
      border-color: rgba(20,105,109,.4);
      background: rgba(20,105,109,.1);
      color: var(--res-accent);
      pointer-events: none;
    }

    .support-section-layout {
      display: grid;
      grid-template-columns: minmax(0, 1.18fr) minmax(312px, .82fr);
      gap: 24px;
      align-items: start;
      margin-top: 34px;
    }

    .support-section-heading {
      gap: 14px;
      max-width: 760px;
    }

    .support-section-heading .section-title {
      max-width: 12.5ch;
      line-height: 1.01;
      letter-spacing: -.38px;
    }

    .support-section-heading .section-lead {
      max-width: 690px;
      line-height: 1.84;
      color: #5a6879;
    }

    .support-steps {
      border: 1px solid rgba(188, 198, 212, .46);
      border-radius: 18px;
      background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(250,252,253,.96));
      padding: 10px;
      display: grid;
      gap: 0;
    }

    .support-step {
      display: grid;
      grid-template-columns: 40px minmax(0, 1fr);
      gap: 14px;
      align-items: start;
      padding: 20px 18px;
      border-radius: 14px;
      transition: background-color .18s ease, box-shadow .18s ease;
    }

    .support-step + .support-step {
      border-top: 1px solid rgba(188, 198, 212, .3);
    }

    .support-step:hover {
      background: rgba(247, 250, 252, .9);
      box-shadow: inset 0 1px 0 rgba(255,255,255,.6);
    }

    .support-step-index {
      width: 32px;
      height: 32px;
      border-radius: 999px;
      display: grid;
      place-items: center;
      font-size: 11px;
      font-weight: 900;
      line-height: 1;
      background: linear-gradient(180deg, rgba(20,105,109,.15), rgba(20,105,109,.08));
      color: var(--res-accent);
      box-shadow: inset 0 1px 0 rgba(255,255,255,.56);
      margin-top: 2px;
    }

    .support-step h4 {
      margin: 0 0 6px;
      font-size: 20px;
      color: var(--res-ink);
      font-family: 'Newsreader', Georgia, serif;
      line-height: 1.08;
      letter-spacing: -.12px;
    }

    .support-step p {
      margin: 0;
      color: var(--res-muted);
      font-size: 14px;
      line-height: 1.76;
    }

    .help-card {
      border-radius: 20px;
      background: linear-gradient(180deg, #102b51, #0b2242 68%, #091b34 100%);
      color: #e5edf7;
      padding: 26px 24px 24px;
      border: 1px solid rgba(152, 180, 215, .24);
      box-shadow: 0 18px 36px rgba(9, 27, 52, .16);
      display: grid;
      gap: 14px;
    }

    .help-card__eyebrow {
      margin: 0;
      color: #9bc7d0;
      font-size: 10px;
      letter-spacing: .16em;
      text-transform: uppercase;
      font-weight: 800;
    }

    .help-card h3 {
      margin: 0;
      color: #fff;
      font-family: 'Newsreader', Georgia, serif;
      font-size: 32px;
      line-height: 1.01;
      letter-spacing: -.2px;
    }

    .help-card p {
      margin: 0;
      font-size: 14px;
      line-height: 1.76;
      color: #c5d4e8;
    }

    .help-meta {
      display: grid;
      gap: 7px;
      padding: 16px 0 6px;
      border-top: 1px solid rgba(197, 212, 232, .16);
      font-size: 12px;
      line-height: 1.5;
      font-weight: 700;
    }

    .help-card a {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      min-height: 44px;
      width: auto;
      min-width: 210px;
      padding: 0 20px;
      border-radius: 10px;
      background: #e8f3f3;
      color: #0b2347;
      border: 1px solid rgba(232, 243, 243, .65);
      box-shadow: inset 0 1px 0 rgba(255,255,255,.45);
      text-decoration: none;
      font-size: 12px;
      letter-spacing: .06em;
      text-transform: uppercase;
      font-weight: 700;
    }

    @media (max-width: 1200px) {
      .resources-layout {
        grid-template-columns: 1fr;
      }

      .support-rail {
        position: static;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        align-items: start;
      }

      .support-rail-head {
        grid-column: 1 / -1;
      }

      .support-rail-nav {
        grid-column: 1 / span 2;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 4px 8px;
      }

      .support-rail-cta {
        align-self: end;
      }

      .section-block {
        padding: 28px;
      }

      .hero-metrics {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .resource-hero-panels,
      .guidance-grid,
      .ext-resources-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 820px) {
      .resources-shell {
        padding: var(--shell-first-section-gap) 0 40px;
      }

      .hero-card,
      .section-block,
      .support-rail {
        padding: 16px;
      }

      .section-heading {
        gap: 10px;
      }

      .section-title {
        max-width: none;
      }

      .hero-title {
        font-size: clamp(34px, 8.2vw, 46px);
      }

      .hero-lead {
        font-size: 16px;
      }

      .ext-filter-bar {
        margin: 22px 0 20px;
      }

      .resource-hero-panels,
      .guidance-grid,
      .ext-resources-grid,
      .hero-metrics {
        grid-template-columns: 1fr;
      }

      .support-section-layout {
        grid-template-columns: 1fr;
        gap: 18px;
      }

      .support-section-heading .section-title,
      .support-section-heading .section-lead {
        max-width: none;
      }

      .support-rail {
        grid-template-columns: 1fr;
        gap: 10px;
      }

      .support-rail-nav {
        grid-column: auto;
        grid-template-columns: 1fr;
      }

      .ext-resource-card {
        min-height: auto;
        padding: 18px;
      }

      .ext-resource-card__availability {
        gap: 8px;
      }

      .ext-resource-card__desc,
      .ext-resource-card__title {
        min-height: 0;
      }

      .ext-resource-card__actions {
        grid-template-columns: 1fr;
      }

      .ext-resource-card__actions .ext-shortlist-btn,
      .ext-resource-card__actions .ext-link-btn {
        width: 100%;
      }

      .support-steps {
        padding: 4px;
      }

      .support-step {
        grid-template-columns: 38px minmax(0, 1fr);
        gap: 12px;
        padding: 16px;
      }

      .help-card {
        padding: 20px;
      }

      .help-card a {
        width: 100%;
      }
    }
</style>
@endsection

@section('content')
<section class="resources-shell">
  <div class="container">
    <div class="resources-layout">
      <aside class="support-rail">
        <div class="support-rail-head">
          <h3>{{ ['ru' => 'КазТБУ', 'kk' => 'КазТБУ', 'en' => 'KazUTB'][$lang] }}</h3>
          <p>{{ ['ru' => 'Служба поддержки по академическим вопросам', 'kk' => 'Academic Resources Desk', 'en' => 'Academic Resources Desk'][$lang] }}</p>
        </div>
        <nav class="support-rail-nav">
          <a href="#resources-hero" class="support-rail-link is-active">{{ ['ru' => 'Обзор и ориентиры', 'kk' => 'Шолу және бағыттар', 'en' => 'Overview and orientation'][$lang] }}</a>
          <a href="#resources-directory" class="support-rail-link">{{ ['ru' => 'Каталог платформ', 'kk' => 'Платформа каталогы', 'en' => 'Platform directory'][$lang] }}</a>
          <a href="#resources-help" class="support-rail-link">{{ ['ru' => 'Нужна помощь', 'kk' => 'Көмек қажет', 'en' => 'Need support'][$lang] }}</a>
          </nav>
        <a href="{{ $routeWithLang('/catalog') }}" class="support-rail-cta">{{ ['ru' => 'Открыть каталог', 'kk' => 'Каталогты ашу', 'en' => 'Open catalog'][$lang] }}</a>
      </aside>

      <div class="resources-main">
        <section class="hero-card" id="resources-hero">
          <p class="hero-eyebrow">{{ $copy['eyebrow'] }}</p>
          <h1 class="hero-title">{{ $copy['hero'] }}</h1>
          <p class="hero-lead">{{ $copy['lead'] }}</p>

          <div class="resource-hero-panels">
            <article class="resource-policy-note">
              <strong>{{ $copy['banner'] }}</strong>
              <p>{{ $copy['banner_body'] }}</p>
            </article>
            <article class="resource-access-matrix">
              <strong>{{ ['ru' => 'Режимы доступа', 'kk' => 'Қолжетімділік режимдері', 'en' => 'Access modes'][$lang] }}</strong>
              <p>{{ ['ru' => 'Проверяйте формат входа до перехода на платформу.', 'kk' => 'Платформаға өтпес бұрын кіру режимін тексеріңіз.', 'en' => 'Check the entry mode before opening a platform.'][$lang] }}</p>
              <div class="resource-access-modes">
                <span>{{ ['ru' => 'Из кампуса', 'kk' => 'Кампус ішінде', 'en' => 'On campus'][$lang] }}</span>
                <span>{{ ['ru' => 'Удалённо', 'kk' => 'Қашықтан', 'en' => 'Remote'][$lang] }}</span>
                <span>{{ ['ru' => 'Открытый доступ', 'kk' => 'Ашық қолжетімділік', 'en' => 'Open access'][$lang] }}</span>
              </div>
            </article>
          </div>

          <div class="hero-metrics">
            <div class="hero-metric"><strong id="stat-total">—</strong><span>{{ ['ru' => 'внешних ресурсов', 'kk' => 'сыртқы ресурс', 'en' => 'external resources'][$lang] }}</span></div>
            <div class="hero-metric"><strong>50 000+</strong><span>{{ ['ru' => 'электронных документов', 'kk' => 'электрондық құжат', 'en' => 'digital documents'][$lang] }}</span></div>
            <div class="hero-metric"><strong>24/7</strong><span>{{ ['ru' => 'удалённый доступ', 'kk' => 'қашықтан қолжетімділік', 'en' => 'remote access'][$lang] }}</span></div>
            <div class="hero-metric"><strong>3</strong><span>{{ ['ru' => 'режима доступа', 'kk' => 'қолжетімділік режимі', 'en' => 'access modes'][$lang] }}</span></div>
          </div>

          <div class="guidance-grid">
            <article class="guidance-card">
              <span>{{ ['ru' => 'Лицензионный доступ', 'kk' => 'Лицензиялық қолжетімділік', 'en' => 'Licensed access'][$lang] }}</span>
              <h3>{{ ['ru' => 'Подписные платформы', 'kk' => 'Жазылым платформалары', 'en' => 'Subscribed platforms'][$lang] }}</h3>
              <p>{{ ['ru' => 'Соберите маршрут от каталога к внешней платформе и сразу закрепите источник в рабочей подборке.', 'kk' => 'Каталогтан сыртқы платформаға дейінгі маршрутты құрып, дереккөзді бірден жұмыс топтамасына бекітіңіз.', 'en' => 'Move from the catalog to the external platform and lock the source into your working shortlist.'][$lang] }}</p>
            </article>
            <article class="guidance-card">
              <span>{{ ['ru' => 'Удалённая работа', 'kk' => 'Қашықтан жұмыс', 'en' => 'Remote work'][$lang] }}</span>
              <h3>{{ ['ru' => 'Единый сценарий', 'kk' => 'Бірізді сценарий', 'en' => 'One flow'][$lang] }}</h3>
              <p>{{ ['ru' => 'Переходите к ресурсам из личного кабинета, каталога и подборки литературы в единой структуре.', 'kk' => 'Ресурстарға жеке кабинеттен, каталогтан және әдебиеттер топтамасынан бірыңғай құрылыммен өтіңіз.', 'en' => 'Reach resources from account, catalog, and shortlist in one consistent academic structure.'][$lang] }}</p>
            </article>
            <article class="guidance-card">
              <span>{{ ['ru' => 'Открытое знание', 'kk' => 'Ашық білім', 'en' => 'Open knowledge'][$lang] }}</span>
              <h3>{{ ['ru' => 'Проверка режима', 'kk' => 'Режимді тексеру', 'en' => 'Access check'][$lang] }}</h3>
              <p>{{ ['ru' => 'Кампусный, удалённый и открытый статусы помогают мгновенно понять, как войти в источник.', 'kk' => 'Кампус, қашықтан және ашық мәртебелер дереккөзге қалай кіруге болатынын жылдам түсіндіреді.', 'en' => 'Campus, remote, and open status labels make entry requirements immediately clear.'][$lang] }}</p>
            </article>
          </div>
        </section>

        <section class="section-block" id="resources-directory">
          <div class="section-heading">
            <p class="section-eyebrow">{{ ['ru' => 'Внешние лицензированные ресурсы', 'kk' => 'Сыртқы лицензиялық ресурстар', 'en' => 'External licensed resources'][$lang] }}</p>
            <h2 class="section-title">{{ ['ru' => 'Подписные платформы и научные базы данных', 'kk' => 'Жазылым платформалары мен ғылыми дерекқорлар', 'en' => 'Subscribed platforms and research databases'][$lang] }}</h2>
            <p class="section-lead">{{ ['ru' => 'Внешние электронные ресурсы доступны по подписке или в открытом доступе. Выберите нужную платформу, быстро проверьте режим входа и сразу переходите к источнику или добавляйте его в рабочую подборку.', 'kk' => 'Сыртқы электрондық ресурстар жазылым немесе ашық қолжетімділік арқылы беріледі. Платформаны таңдап, кіру режимін бірден тексеріп, дереккөзге өтіңіз немесе оны жұмыс топтамасына қосыңыз.', 'en' => 'External electronic resources are available via subscription or open access. Choose a platform, check the access mode at a glance, and either open the source or add it to your working shortlist immediately.'][$lang] }}</p>
          </div>

          <div class="ext-filter-bar" id="ext-filter-bar">
            <button class="ext-filter-btn ext-filter-btn--active" data-filter="all">{{ ['ru' => 'Все', 'kk' => 'Барлығы', 'en' => 'All'][$lang] }}</button>
          </div>

          <div id="ext-resources-loading" style="text-align:center; padding:32px;">
            <div style="display:inline-block;width:28px;height:28px;border:3px solid #e5e7eb;border-top-color:var(--res-ink);border-radius:50%;animation:spin .7s linear infinite;"></div>
            <p style="margin:8px 0 0; color:var(--res-muted); font-size:14px;">{{ ['ru' => 'Загрузка ресурсов...', 'kk' => 'Ресурстар жүктелуде...', 'en' => 'Loading resources...'][$lang] }}</p>
          </div>

          <div id="ext-resources-grid" class="ext-resources-grid" style="display:none;"></div>
        </section>

        <section class="section-block" id="resources-help">
          <div class="section-heading support-section-heading">
            <p class="section-eyebrow">{{ $copy['support_label'] }}</p>
            <h2 class="section-title">{{ $copy['support_title'] }}</h2>
            <p class="section-lead">{{ $copy['support_body'] }}</p>
          </div>

          <div class="support-section-layout">
            <div class="support-steps">
              <article class="support-step">
                <div class="support-step-index">1</div>
                <div>
                  <h4>{{ ['ru' => 'Проверьте режим доступа', 'kk' => 'Қолжетімділік режимін тексеріңіз', 'en' => 'Check access mode'][$lang] }}</h4>
                  <p>{{ ['ru' => 'Если видите campus-only, подключитесь из сети университета. Для remote-access используйте авторизацию через личный кабинет.', 'kk' => 'Campus-only болса, университет желісінен кіріңіз. Remote-access үшін жеке кабинет арқылы авторизацияны пайдаланыңыз.', 'en' => 'If the source is campus-only, connect from the university network. For remote access, authenticate via your account.'][$lang] }}</p>
                </div>
              </article>
              <article class="support-step">
                <div class="support-step-index">2</div>
                <div>
                  <h4>{{ ['ru' => 'Соберите рабочий набор', 'kk' => 'Жұмыс жиынтығын құраңыз', 'en' => 'Build your working set'][$lang] }}</h4>
                  <p>{{ ['ru' => 'Добавляйте ресурсы в подборку литературы, чтобы подготовить syllabus, рабочую программу или исследовательский список.', 'kk' => 'Syllabus, оқу бағдарламасы немесе зерттеу тізімін дайындау үшін ресурстарды әдебиеттер топтамасына қосыңыз.', 'en' => 'Add sources to the reading shortlist to prepare a syllabus, course plan, or research list.'][$lang] }}</p>
                </div>
              </article>
              <article class="support-step">
                <div class="support-step-index">3</div>
                <div>
                  <h4>{{ ['ru' => 'Запросите консультацию', 'kk' => 'Кеңес алыңыз', 'en' => 'Request consultation'][$lang] }}</h4>
                  <p>{{ ['ru' => 'Библиографический отдел поможет подобрать базы данных по дисциплине и оформить подборку для учебного модуля.', 'kk' => 'Библиографиялық бөлім пән бойынша дерекқорларды іріктеуге және оқу модуліне арналған топтаманы рәсімдеуге көмектеседі.', 'en' => 'The bibliographic team can curate databases by discipline and shape a shortlist for your teaching module.'][$lang] }}</p>
                </div>
              </article>
            </div>

            <aside class="help-card">
              <p class="help-card__eyebrow">{{ ['ru' => 'Помощь библиотекаря', 'kk' => 'Кітапханашы көмегі', 'en' => 'Librarian support'][$lang] }}</p>
              <h3>{{ ['ru' => 'Нужна помощь?', 'kk' => 'Көмек керек пе?', 'en' => 'Need help?'][$lang] }}</h3>
              <p>{{ ['ru' => 'Напишите библиотекарю, если нужен удалённый доступ, проверка лицензии или подбор источников по дисциплине.', 'kk' => 'Қашықтан қолжетімділік, лицензияны тексеру немесе пән бойынша дереккөз іріктеу қажет болса, кітапханашыға жазыңыз.', 'en' => 'Message a librarian for remote access support, license checks, or discipline-specific source curation.'][$lang] }}</p>
              <div class="help-meta">
                <div>{{ ['ru' => 'Пн–Пт · 09:00–18:00', 'kk' => 'Дс–Жм · 09:00–18:00', 'en' => 'Mon-Fri · 09:00-18:00'][$lang] }}</div>
                <div>{{ ['ru' => 'Ответ в течение рабочего дня', 'kk' => 'Жұмыс күні ішінде жауап', 'en' => 'Response within one business day'][$lang] }}</div>
              </div>
              <a href="{{ $routeWithLang('/contacts') }}">{{ ['ru' => 'Связаться с библиотекой', 'kk' => 'Кітапханамен байланысу', 'en' => 'Contact library team'][$lang] }}</a>
            </aside>
          </div>
        </section>
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
  const railLinks = Array.from(document.querySelectorAll('.support-rail-link'));

  function setActiveRailLink(targetId) {
    railLinks.forEach(link => {
      const isActive = link.getAttribute('href') === `#${targetId}`;
      link.classList.toggle('is-active', isActive);
      if (isActive) {
        link.setAttribute('aria-current', 'true');
      } else {
        link.removeAttribute('aria-current');
      }
    });
  }

  function initSupportRailSpy() {
    const sectionMap = railLinks
      .map(link => {
        const href = link.getAttribute('href') || '';
        const targetId = href.startsWith('#') ? href.slice(1) : '';
        return {
          link,
          targetId,
          section: targetId ? document.getElementById(targetId) : null,
        };
      })
      .filter(item => item.section);

    if (!sectionMap.length) {
      return;
    }

    const updateActiveLink = () => {
      const viewportOffset = Math.max(window.innerHeight * 0.24, 120);
      let activeTargetId = sectionMap[0].targetId;

      for (const item of sectionMap) {
        const top = item.section.getBoundingClientRect().top;
        if (top - viewportOffset <= 0) {
          activeTargetId = item.targetId;
        }
      }

      setActiveRailLink(activeTargetId);
    };

    railLinks.forEach(link => {
      link.addEventListener('click', () => {
        const href = link.getAttribute('href') || '';
        if (href.startsWith('#')) {
          setActiveRailLink(href.slice(1));
        }
      });
    });

    window.addEventListener('scroll', updateActiveLink, { passive: true });
    window.addEventListener('resize', updateActiveLink);
    updateActiveLink();
  }

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
      const hasPrimaryLink = Boolean(r.url);

      return `
        <div class="ext-resource-card" data-slug="${escapeHtml(r.slug)}">
          <div class="ext-resource-card__availability">
            <span class="ext-resource-card__badge ${badgeClass}">${escapeHtml(accInfo.label || r.access_type)}</span>
            ${r.expiry_date ? `<span class="ext-resource-card__expiry">${RES_I18N.validUntil} ${formatExpiry(r.expiry_date)}</span>` : ''}
          </div>
          <div class="ext-resource-card__provider">${escapeHtml(r.provider)}</div>
          <div class="ext-resource-card__header">
            <div class="ext-resource-card__icon ext-resource-card__icon--${color}">${escapeHtml(catInfo.icon || '📄')}</div>
            <div>
              <h3 class="ext-resource-card__title">${escapeHtml(r.title)}</h3>
            </div>
          </div>
          <p class="ext-resource-card__desc">${escapeHtml(r.description)}</p>
          <div class="ext-resource-card__footer">
            <span class="ext-resource-card__category">${escapeHtml(catInfo.label || r.category)}</span>
          </div>
          <div class="ext-resource-card__actions${hasPrimaryLink ? '' : ' ext-resource-card__actions--single'}">
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
      btn.textContent = RES_I18N.add;
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
    initSupportRailSpy();

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
      loading.innerHTML = `<p style="color:var(--res-muted);">${RES_I18N.loadingFailed}</p>`;
      console.error('External resources load error:', e);
    }
  }

  init();
})();
</script>
@endsection
