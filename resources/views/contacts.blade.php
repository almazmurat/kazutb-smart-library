@extends('layouts.public')

@section('title', 'О библиотеке и контакты — КазУТБ')

@section('content')
  <section class="page-hero">
    <div class="container contact-hero-center">
      <div class="eyebrow">Библиотека КазУТБ</div>
      <h1>О библиотеке и контакты</h1>
      <p>Библиотека Казахского университета технологии и бизнеса — центр знаний и информационной поддержки для студентов, преподавателей и исследователей.</p>
    </div>
  </section>

  {{-- About: mission + stats (merged from /about) --}}
  <section class="page-section">
    <div class="container about-grid">
      <div>
        <div class="eyebrow">Миссия</div>
        <h2 class="heading-xl">Доступ к знаниям и информационным ресурсам</h2>
        <p class="text-body" style="margin: 0 0 20px;">
          Библиотека КазУТБ обеспечивает информационную поддержку учебного процесса и научных исследований университета. Мы развиваем фонд, расширяем цифровые подписки и создаём комфортные условия для работы с информацией.
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
        <div><h2>Контакты</h2><p>Как связаться с библиотекой.</p></div>
      </div>
      <div class="contact-grid">
        <div class="contact-card">
          <div class="icon">📍</div>
          <h3>Адрес</h3>
          <p>г. Астана, ул. Кайым Мухамедханова, 37А<br>главный корпус КазУТБ</p>
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
          <p><a href="mailto:library@kazutb.kz">library@kazutb.kz</a></p>
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
            <h2>Режим работы</h2>
            <p>График работы библиотеки.</p>
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
            <h2>Подразделения</h2>
            <p>Контакты подразделений библиотеки.</p>
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
