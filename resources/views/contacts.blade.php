@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
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
  <section class="page-hero">
    <div class="container contact-hero-center">
      <div class="eyebrow">Digital Library</div>
      <h1>{{ $copy['hero'] }}</h1>
      <p>{{ $copy['lead'] }}</p>
    </div>
  </section>

  {{-- About: mission + stats (merged from /about) --}}
  <section class="page-section">
    <div class="container about-grid">
      <div>
        <div class="eyebrow">{{ $copy['mission_label'] }}</div>
        <h2 class="heading-xl">{{ $copy['mission_title'] }}</h2>
        <p class="text-body" style="margin: 0 0 20px;">
          {{ $copy['mission_body'] }}
        </p>
      </div>
      <div class="card">
        <h3 class="heading-lg">Библиотека в цифрах</h3>
        <ul class="info-list">
          <li><span class="icon">📚</span><div><strong>Более 50 000</strong> единиц фонда — учебная, научная и справочная литература</div></li>
          <li><span class="icon">💻</span><div><strong>Электронные ресурсы</strong> — подписки на научные базы и электронные библиотеки</div></li>
          <li><span class="icon">🏛</span><div><strong>Читальные залы</strong> — оборудованные пространства для работы с литературой</div></li>
          <li><span class="icon">🌐</span><div><strong>Онлайн-каталог</strong> с поиском, бронированием и личным кабинетом</div></li>
        </ul>
      </div>
    </div>
  </section>

  {{-- Contact cards --}}
  <section class="page-section">
    <div class="container">
      <div class="section-head section-head-centered">
        <div><h2>{{ $copy['contacts'] }}</h2><p>{{ $copy['contacts_help'] }}</p></div>
      </div>
      <div class="contact-grid">
        <div class="contact-card">
          <div class="icon">📍</div>
          <h3>Адрес</h3>
          <p>г. Астана, ул. Кайым Мухамедханова, 37А<br>информационно-библиотечный центр</p>
          <p class="text-body-sm" style="margin-top: 8px;">Для очных консультаций, выдачи и возврата литературы</p>
        </div>

        <div class="contact-card">
          <div class="icon">📞</div>
          <h3>Телефон</h3>
          <p><a href="tel:+77172645858">+7 (7172) 64-58-58</a></p>
          <p class="text-body-sm" style="margin-top: 8px;">Справочная библиотеки по будням с 09:00 до 18:00</p>
        </div>

        <div class="contact-card">
          <div class="icon">✉️</div>
          <h3>Электронная почта</h3>
          <p><a href="mailto:library@digital-library.demo">library@digital-library.demo</a></p>
          <p class="text-body-sm" style="margin-top: 8px;">Для запросов по электронным ресурсам, доступу и обращениям</p>
        </div>

        <div class="contact-card">
          <div class="icon">💻</div>
          <h3>Онлайн-сервисы</h3>
          <p><a href="/catalog">Электронный каталог</a><br><a href="/account">Личный кабинет читателя</a></p>
          <p class="text-body-sm" style="margin-top: 8px;">Поиск книг, просмотр бронирований и работа с учётной записью</p>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container contacts-grid">
      <div>
        <div class="section-head" style="margin-bottom: 24px;">
          <div>
            <h2>{{ $copy['hours'] }}</h2>
            <p>{{ $copy['hours_help'] }}</p>
          </div>
        </div>

        <div class="card">
          <ul class="info-list">
            <li>
              <span class="icon">📅</span>
              <div>
                <strong>Понедельник – Пятница</strong><br>
                <span class="text-muted">09:00 – 18:00</span>
              </div>
            </li>
            <li>
              <span class="icon">📅</span>
              <div>
                <strong>Суббота</strong><br>
                <span class="text-muted">10:00 – 14:00</span>
              </div>
            </li>
            <li>
              <span class="icon">🔴</span>
              <div>
                <strong>Воскресенье</strong><br>
                <span class="text-muted">Выходной</span>
              </div>
            </li>
          </ul>
        </div>

        <p class="text-body-sm" style="margin-top: 16px;">
          Электронный каталог и личный кабинет доступны круглосуточно.<br>
          В период сессии возможен расширенный график работы читальных залов.
        </p>
      </div>

      <div>
        <div class="section-head" style="margin-bottom: 24px;">
          <div>
            <h2>{{ $copy['units'] }}</h2>
            <p>{{ $copy['units_help'] }}</p>
          </div>
        </div>

        <div class="card">
          <ul class="info-list">
            <li>
              <span class="icon">📋</span>
              <div>
                <strong>Абонемент</strong><br>
                <span class="text-muted">Выдача и возврат литературы, оформление читательских билетов</span>
              </div>
            </li>
            <li>
              <span class="icon">📖</span>
              <div>
                <strong>Читальный зал</strong><br>
                <span class="text-muted">Работа с литературой, доступ к периодическим изданиям</span>
              </div>
            </li>
            <li>
              <span class="icon">💡</span>
              <div>
                <strong>Информационно-библиографический отдел</strong><br>
                <span class="text-muted">Консультации, помощь в поиске источников, информационная грамотность</span>
              </div>
            </li>
            <li>
              <span class="icon">🗃</span>
              <div>
                <strong>Отдел комплектования</strong><br>
                <span class="text-muted">Комплектование фонда, работа с издательствами, каталогизация</span>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .about-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 48px;
    align-items: start;
  }

  .contact-hero-center {
    max-width: 820px;
    margin: 0 auto;
    text-align: center;
  }

  .contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 260px));
    gap: 24px;
    justify-content: center;
  }

  .contact-card {
    text-align: center;
  }

  .contacts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: start;
  }

  .support-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 320px));
    gap: 24px;
    justify-content: center;
  }

  .support-card {
    padding: 28px;
  }

  .support-card h3 {
    margin: 10px 0 12px;
    font-size: 20px;
  }

  .support-card p {
    margin: 0;
    color: var(--muted);
    line-height: 1.7;
  }

  @media (max-width: 900px) {
    .about-grid {
      grid-template-columns: 1fr;
      gap: 32px;
    }
    .contacts-grid {
      grid-template-columns: 1fr;
      gap: 32px;
    }
  }

  @media (max-width: 680px) {
    .contacts-grid { gap: 24px; }
    .contact-grid,
    .support-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@endsection
