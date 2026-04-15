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
      'meta' => 'Ресурсы — Digital Library',
      'eyebrow' => 'Институциональные ресурсы',
      'hero_start' => 'Институциональные',
      'hero_emphasis' => 'ресурсы',
      'hero_end' => 'и глобальные исследовательские инструменты',
      'lead' => 'Кураторский шлюз к лицензированным внешним базам данных, академическим журналам и специализированной исследовательской инфраструктуре Казахского университета технологии и бизнеса.',
      'support_label' => 'Поддержка и обучение',
      'support_title' => 'Институциональная поддержка исследователей',
      'support_body' => 'Если возникают сложности с доступом к внешним ресурсам, библиотека помогает с подключением, навигацией по платформам и быстрыми консультациями для преподавателей и студентов.',
    ],
    'kk' => [
      'meta' => 'Ресурстар — Digital Library',
      'eyebrow' => 'Институционалдық ресурстар',
      'hero_start' => 'Институционалдық',
      'hero_emphasis' => 'ресурстар',
      'hero_end' => 'және жаһандық зерттеу құралдары',
      'lead' => 'ҚазТБУ қауымдастығына арналған лицензиялық сыртқы дерекқорларға, академиялық журналдарға және зерттеу инфрақұрылымына ашылатын сенімді кітапхана шлюзі.',
      'support_label' => 'Қолдау және оқыту',
      'support_title' => 'Зерттеушілерге институционалдық қолдау',
      'support_body' => 'Сыртқы ресурстарға қолжетімділік қиындаса, кітапхана платформалармен жұмыс істеуге, қосылуға және оқытушылар мен студенттерге кеңес беруге көмектеседі.',
    ],
    'en' => [
      'meta' => 'Resources — Digital Library',
      'eyebrow' => 'Institutional resources',
      'hero_start' => 'Institutional',
      'hero_emphasis' => 'Resources',
      'hero_end' => '& Global Research Tools',
      'lead' => 'A curated gateway to licensed external databases, academic journals, and specialized research infrastructure provided by the Kazakh University of Technology and Business.',
      'support_label' => 'Assistance & training',
      'support_title' => 'Institutional Support for Researchers',
      'support_body' => 'Experiencing difficulty accessing external resources? Our library staff provides one-on-one training sessions and technical support for faculty and students.',
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
  .resources-page {
    background: #f8f9fa;
    padding: 3.75rem 0 4.5rem;
  }

  .resources-hero {
    max-width: 58rem;
    margin-bottom: 3.5rem;
  }

  .resources-title {
    margin: 0 0 1.5rem;
    color: #000613;
    font-family: 'Newsreader', serif;
    font-size: clamp(3rem, 5.8vw, 4.7rem);
    line-height: .96;
    letter-spacing: -.04em;
    max-width: 48rem;
    text-wrap: balance;
  }

  .resources-title .accent {
    font-style: italic;
    font-weight: 500;
  }

  .resources-lead {
    max-width: 44rem;
    margin: 0;
    color: #5b6372;
    font-size: 1.125rem;
    line-height: 1.75;
  }

  .resources-filter-label {
    width: 100%;
    color: #14696d;
    font-size: .7rem;
    font-weight: 800;
    letter-spacing: .16em;
    text-transform: uppercase;
  }

  .resources-filters {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    align-items: center;
    margin-bottom: 2rem;
  }

  .resources-filter-btn {
    border: 1px solid #d7dce3;
    border-radius: 999px;
    background: #e9ecef;
    padding: .65rem 1rem;
    color: #4d5563;
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .18s ease;
  }

  .resources-filter-btn:hover {
    background: #dfe4ea;
  }

  .resources-filter-btn.is-active {
    background: #000613;
    color: #fff;
    border-color: #000613;
  }

  .resources-bento {
    display: grid;
    grid-template-columns: repeat(12, minmax(0, 1fr));
    gap: 1rem;
    margin-bottom: 4rem;
  }

  .resource-card {
    border: 1px solid rgba(196, 198, 207, .45);
    border-radius: 1rem;
    background: #fff;
    padding: 1.35rem;
    box-shadow: 0 8px 22px rgba(15, 23, 42, .035);
    display: flex;
    flex-direction: column;
    gap: .95rem;
    min-height: 12rem;
  }

  .resource-card--featured {
    grid-column: span 8;
    min-height: 15rem;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
  }

  .resource-card--side {
    grid-column: span 4;
    min-height: 15rem;
  }

  .resource-card--small {
    grid-column: span 4;
    min-height: 12.5rem;
  }

  .resource-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 12px 28px rgba(15, 23, 42, .05);
  }

  .resource-badge-row {
    display: flex;
    gap: .6rem;
    align-items: center;
    flex-wrap: wrap;
  }

  .resource-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: .28rem .6rem;
    background: rgba(20, 105, 109, .12);
    color: #14696d;
    font-size: .6rem;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .resource-badge.resource-badge--neutral {
    background: transparent;
    color: #6b7280;
    padding-inline: 0;
  }

  .resource-icon-tile {
    width: 3rem;
    height: 3rem;
    border-radius: .85rem;
    display: inline-grid;
    place-items: center;
    background: #e9ecef;
    color: #006a6a;
  }

  .resource-card-title {
    margin: 0;
    color: #0b2242;
    font-family: 'Newsreader', serif;
    font-size: clamp(1.55rem, 2.3vw, 2.45rem);
    line-height: 1.08;
  }

  .resource-card--small .resource-card-title,
  .resource-card--side .resource-card-title {
    font-size: 2rem;
  }

  .resource-card-desc {
    margin: 0;
    color: #5b6372;
    font-size: .95rem;
    line-height: 1.7;
  }

  .resource-feature-ghost {
    position: absolute;
    right: 1.4rem;
    top: 1rem;
    width: 5.5rem;
    height: 5.5rem;
    border-radius: .9rem;
    background: rgba(15, 23, 42, .04);
    color: rgba(15, 23, 42, .12);
    display: grid;
    place-items: center;
    font-size: 3rem;
  }

  .resource-actions {
    display: flex;
    gap: .75rem;
    flex-wrap: wrap;
    margin-top: auto;
  }

  .resource-actions a,
  .resource-actions button {
    min-height: 2.6rem;
    border-radius: .45rem;
    border: 1px solid #d3d8df;
    padding: 0 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    text-decoration: none;
    font-size: .76rem;
    font-weight: 800;
    letter-spacing: .01em;
    cursor: pointer;
    background: #fff;
    color: #0b2242;
  }

  .resource-actions .resource-primary {
    background: #000613;
    border-color: #000613;
    color: #fff;
  }

  .resource-actions .resource-secondary {
    color: #14696d;
  }

  .resources-support {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(260px, .8fr);
    gap: 1.5rem;
    border-radius: 1.25rem;
    background: linear-gradient(180deg, #04101f 0%, #010a15 100%);
    color: #f5f8fb;
    padding: 2rem;
    overflow: hidden;
  }

  .resources-support-copy span {
    display: block;
    margin-bottom: 1rem;
    color: rgba(217, 243, 247, .7);
    font-size: .68rem;
    font-weight: 800;
    letter-spacing: .18em;
    text-transform: uppercase;
  }

  .resources-support-copy h2 {
    margin: 0 0 1rem;
    font-family: 'Newsreader', serif;
    font-size: clamp(2rem, 3.5vw, 3rem);
    line-height: 1.08;
  }

  .resources-support-copy p {
    margin: 0 0 1.25rem;
    color: rgba(229, 237, 247, .8);
    line-height: 1.8;
  }

  .resources-support-meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
  }

  .resources-support-meta strong {
    display: block;
    margin-bottom: .3rem;
    font-size: .68rem;
    color: rgba(217, 243, 247, .7);
    letter-spacing: .15em;
    text-transform: uppercase;
  }

  .resources-support-cta {
    display: inline-flex;
    margin-top: 1rem;
    min-height: 2.7rem;
    align-items: center;
    justify-content: center;
    border-radius: .5rem;
    padding: 0 1rem;
    background: #eaf3f4;
    color: #04101f;
    text-decoration: none;
    font-size: .8rem;
    font-weight: 800;
  }

  .resources-support-image {
    min-height: 18rem;
    border-radius: 1rem;
    background: linear-gradient(rgba(4, 16, 31, .15), rgba(4, 16, 31, .6)), url('/images/news/default-library.jpg') center/cover no-repeat;
  }

  .resources-empty {
    grid-column: 1 / -1;
    border: 1px solid rgba(196, 198, 207, .45);
    border-radius: 1rem;
    background: #fff;
    padding: 2rem;
    color: #5b6372;
  }

  @media (max-width: 900px) {
    .resource-card--featured,
    .resource-card--side,
    .resource-card--small {
      grid-column: span 12;
    }

    .resources-support {
      grid-template-columns: 1fr;
    }

    .resources-support-meta {
      grid-template-columns: 1fr;
    }
  }
</style>
@endsection

@section('content')
<section id="resources-page" class="resources-page">
  <div class="container">
    <header class="resources-hero">
      <p class="resources-filter-label">{{ strtoupper($copy['eyebrow']) }}</p>
      <h1 class="resources-title">{{ $copy['hero_start'] }} <span class="accent">{{ $copy['hero_emphasis'] }}</span> {{ $copy['hero_end'] }}</h1>
      <p class="resources-lead">{{ $copy['lead'] }}</p>
    </header>

    <section id="resources-filter-bar" class="resources-filters">
      <span class="resources-filter-label">{{ ['ru' => 'Фильтр по дисциплине', 'kk' => 'Пән бойынша сүзгі', 'en' => 'Filter by Discipline'][$lang] }}</span>
      <button type="button" class="resources-filter-btn is-active" data-filter="all">{{ ['ru' => 'Все ресурсы', 'kk' => 'Барлық ресурстар', 'en' => 'All Access'][$lang] }}</button>
      <button type="button" class="resources-filter-btn" data-filter="engineering">Engineering</button>
      <button type="button" class="resources-filter-btn" data-filter="economics">Economics</button>
      <button type="button" class="resources-filter-btn" data-filter="social">Social Sciences</button>
      <button type="button" class="resources-filter-btn" data-filter="technology">Technology</button>
    </section>

    <div id="resources-grid" class="resources-bento" data-resource-grid>
      <article class="resource-card resource-card--featured">
        <div>
          <div class="resource-badge-row">
            <span class="resource-badge">{{ ['ru' => 'Премиум доступ', 'kk' => 'Премиум қолжетімділік', 'en' => 'Premium Access'][$lang] }}</span>
            <span class="resource-badge resource-badge--neutral">{{ ['ru' => 'Общий академический доступ', 'kk' => 'Жалпы академиялық қолжетімділік', 'en' => 'General Research'][$lang] }}</span>
          </div>
          <h3 class="resource-card-title">{{ ['ru' => 'Загрузка платформ...', 'kk' => 'Платформалар жүктелуде...', 'en' => 'Loading platforms...'][$lang] }}</h3>
          <p class="resource-card-desc">{{ ['ru' => 'Подготавливаем лицензированные и открытые ресурсы библиотеки.', 'kk' => 'Кітапхананың лицензиялық және ашық ресурстары дайындалуда.', 'en' => 'Preparing the library’s licensed and open resources.'][$lang] }}</p>
        </div>
      </article>
    </div>

    <section id="resource-support-section" class="resources-support">
      <div class="resources-support-copy">
        <span>{{ $copy['support_label'] }}</span>
        <h2>{{ $copy['support_title'] }}</h2>
        <p>{{ $copy['support_body'] }}</p>
        <div class="resources-support-meta">
          <div>
            <strong>{{ ['ru' => 'Email inquiry', 'kk' => 'Email inquiry', 'en' => 'Email inquiry'][$lang] }}</strong>
            <div>library-support@kazutb.edu.kz</div>
          </div>
          <div>
            <strong>{{ ['ru' => 'Live consultation', 'kk' => 'Live consultation', 'en' => 'Live consultation'][$lang] }}</strong>
            <div>Room 402, Block B</div>
          </div>
        </div>
        <a class="resources-support-cta" href="{{ $routeWithLang('/contacts') }}">{{ ['ru' => 'Связаться с библиотекой', 'kk' => 'Кітапханамен байланысу', 'en' => 'Contact Librarian'][$lang] }}</a>
      </div>
      <div class="resources-support-image" aria-hidden="true"></div>
    </section>
  </div>
</section>
@endsection

@section('scripts')
<script>
(function () {
  const API_URL = '/api/v1/external-resources';
  const CONTACT_URL = @json($routeWithLang('/contacts'));
  const RES_LANG = @json($lang);
  const copy = {
    ru: {
      loading: 'Загрузка ресурсов...',
      empty: 'Подходящие ресурсы скоро появятся в этом разделе.',
      support: 'Связаться с библиотекой',
      open: 'Access Resource',
      guide: 'User Guide',
      login: 'LOG IN VIA INSTITUTION',
      database: 'ACCESS DATABASE',
      explore: 'EXPLORE CITATIONS',
      repository: 'OPEN REPOSITORY',
      premium: 'Premium Access',
      general: 'General Research',
      openBadge: 'Open access',
      remoteBadge: 'Remote access',
      campusBadge: 'Campus only'
    },
    kk: {
      loading: 'Ресурстар жүктелуде...',
      empty: 'Бұл бөлімге лайық ресурстар жақында қосылады.',
      support: 'Кітапханамен байланысу',
      open: 'Access Resource',
      guide: 'User Guide',
      login: 'LOG IN VIA INSTITUTION',
      database: 'ACCESS DATABASE',
      explore: 'EXPLORE CITATIONS',
      repository: 'OPEN REPOSITORY',
      premium: 'Premium Access',
      general: 'General Research',
      openBadge: 'Open access',
      remoteBadge: 'Remote access',
      campusBadge: 'Campus only'
    },
    en: {
      loading: 'Loading resources...',
      empty: 'Matching resources will appear here soon.',
      support: 'Contact Librarian',
      open: 'Access Resource',
      guide: 'User Guide',
      login: 'LOG IN VIA INSTITUTION',
      database: 'ACCESS DATABASE',
      explore: 'EXPLORE CITATIONS',
      repository: 'OPEN REPOSITORY',
      premium: 'Premium Access',
      general: 'General Research',
      openBadge: 'Open access',
      remoteBadge: 'Remote access',
      campusBadge: 'Campus only'
    }
  }[RES_LANG] || {
    loading: 'Loading resources...',
    empty: 'Matching resources will appear here soon.',
    support: 'Contact Librarian',
    open: 'Access Resource',
    guide: 'User Guide',
    login: 'LOG IN VIA INSTITUTION',
    database: 'ACCESS DATABASE',
    explore: 'EXPLORE CITATIONS',
    repository: 'OPEN REPOSITORY',
    premium: 'Premium Access',
    general: 'General Research',
    openBadge: 'Open access',
    remoteBadge: 'Remote access',
    campusBadge: 'Campus only'
  };

  const iconMap = {
    electronic_library: 'menu_book',
    research_database: 'history_edu',
    analytics: 'query_stats',
    open_access: 'library_books'
  };

  const filterButtons = Array.from(document.querySelectorAll('[data-filter]'));
  const grid = document.getElementById('resources-grid');
  let resources = [];

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = String(text || '');
    return div.innerHTML;
  }

  function guessDiscipline(item) {
    const haystack = `${item.title || ''} ${item.provider || ''} ${item.category || ''}`.toLowerCase();
    if (/econ|business|polpred|analytic/.test(haystack)) return 'economics';
    if (/jstor|рмэб|rmeb|elibrary|social|human/.test(haystack)) return 'social';
    if (/science|engineering|springer|elsevier|doaj/.test(haystack)) return 'engineering';
    return 'technology';
  }

  function accessLabel(kind) {
    if (kind === 'open') return copy.openBadge;
    if (kind === 'remote_auth') return copy.remoteBadge;
    return copy.campusBadge;
  }

  function normalizeItem(item, index) {
    return {
      slug: item.slug || `resource-${index}`,
      title: item.title || 'Library Resource',
      provider: item.provider || 'KazUTB Digital Library',
      description: item.description || item.access_note || 'Institutional access to scholarly materials and curated research support.',
      category: item.category || 'electronic_library',
      accessType: item.access_type || 'campus',
      url: item.url || CONTACT_URL,
      discipline: guessDiscipline(item),
      icon: iconMap[item.category] || 'open_in_new'
    };
  }

  function secondaryActionLabel(index) {
    return [copy.login, copy.database, copy.explore, copy.repository][index] || copy.database;
  }

  function featuredCard(item) {
    return `
      <article class="resource-card resource-card--featured">
        <div class="resource-feature-ghost">
          <span class="material-symbols-outlined">article</span>
        </div>
        <div>
          <div class="resource-badge-row">
            <span class="resource-badge">${copy.premium}</span>
            <span class="resource-badge resource-badge--neutral">${copy.general}</span>
          </div>
          <h3 class="resource-card-title">${escapeHtml(item.title)}</h3>
          <p class="resource-card-desc">${escapeHtml(item.description)}</p>
        </div>
        <div class="resource-actions">
          <a class="resource-primary" href="${escapeHtml(item.url)}" target="_blank" rel="noopener">${copy.open} <span class="material-symbols-outlined text-sm">open_in_new</span></a>
          <a class="resource-secondary" href="${escapeHtml(CONTACT_URL)}">${copy.guide}</a>
        </div>
      </article>`;
  }

  function smallCard(item, modifier, index) {
    return `
      <article class="resource-card ${modifier}">
        <div class="resource-icon-tile">
          <span class="material-symbols-outlined">${item.icon}</span>
        </div>
        <h3 class="resource-card-title">${escapeHtml(item.title)}</h3>
        <p class="resource-card-desc">${escapeHtml(item.description)}</p>
        <div class="resource-badge-row">
          <span class="resource-badge">${escapeHtml(accessLabel(item.accessType))}</span>
        </div>
        <div class="resource-actions">
          <a class="resource-secondary" href="${escapeHtml(item.url)}" target="_blank" rel="noopener">${secondaryActionLabel(index)}</a>
        </div>
      </article>`;
  }

  function render(filter) {
    const filtered = filter === 'all' ? resources : resources.filter((item) => item.discipline === filter);
    if (!filtered.length) {
      grid.innerHTML = `<article class="resources-empty">${escapeHtml(copy.empty)}</article>`;
      return;
    }

    const items = filtered.slice(0, 5);
    const blocks = [];
    if (items[0]) blocks.push(featuredCard(items[0]));
    if (items[1]) blocks.push(smallCard(items[1], 'resource-card--side', 0));
    items.slice(2, 5).forEach((item, index) => blocks.push(smallCard(item, 'resource-card--small', index + 1)));
    grid.innerHTML = blocks.join('');
  }

  filterButtons.forEach((button) => {
    button.addEventListener('click', () => {
      filterButtons.forEach((btn) => btn.classList.remove('is-active'));
      button.classList.add('is-active');
      render(button.dataset.filter || 'all');
    });
  });

  async function init() {
    try {
      const response = await fetch(API_URL, { headers: { Accept: 'application/json' } });
      const payload = await response.json();
      resources = Array.isArray(payload?.data) ? payload.data.map(normalizeItem) : [];
      render('all');
    } catch (error) {
      grid.innerHTML = `<article class="resources-empty">${escapeHtml(copy.empty)}</article>`;
      console.error('Resources load failed:', error);
    }
  }

  init();
})();
</script>
@endsection
