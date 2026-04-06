@extends('layouts.public')

@section('title', 'О библиотеке — КазУТБ')

@section('content')
  <section class="page-hero">
    <div class="container">
      <div class="eyebrow">О библиотеке</div>
      <h1>Библиотека КазУТБ</h1>
      <p>Библиотека Казахского университета технологии и бизнеса — центр знаний и информационной поддержки для студентов, преподавателей и исследователей.</p>
    </div>
  </section>

  <section class="page-section">
    <div class="container about-grid">
      <div>
        <div class="eyebrow">Миссия</div>
        <h2 style="margin: 0 0 16px; font-size: clamp(28px, 4vw, 38px); font-weight: 900; letter-spacing: -1px; line-height: 1.1;">
          Обеспечение доступа к знаниям и информационным ресурсам
        </h2>
        <p style="color: var(--muted); font-size: 17px; line-height: 1.7; margin: 0 0 20px;">
          Библиотека КазУТБ обеспечивает информационную поддержку учебного процесса и научных исследований университета. Наша задача — предоставить каждому пользователю удобный и полный доступ к фонду учебной, научной и справочной литературы.
        </p>
        <p style="color: var(--muted); font-size: 17px; line-height: 1.7; margin: 0;">
          Мы развиваем фонд, расширяем цифровые подписки, внедряем современные технологии обслуживания и создаём комфортные условия для работы с информацией.
        </p>
      </div>

      <div class="card" style="padding: 32px;">
        <h3 style="margin: 0 0 20px; font-size: 22px; font-weight: 800;">Библиотека в цифрах</h3>
        <ul class="info-list">
          <li>
            <span class="icon">📚</span>
            <div><strong>Более 50 000</strong> единиц фонда — учебная, научная и справочная литература</div>
          </li>
          <li>
            <span class="icon">💻</span>
            <div><strong>Электронные ресурсы</strong> — подписки на научные базы и электронные библиотеки</div>
          </li>
          <li>
            <span class="icon">🏛</span>
            <div><strong>Читальные залы</strong> — оборудованные пространства для работы с литературой</div>
          </li>
          <li>
            <span class="icon">👥</span>
            <div><strong>Обслуживание</strong> студентов, магистрантов, докторантов и преподавателей</div>
          </li>
          <li>
            <span class="icon">🌐</span>
            <div><strong>Онлайн-каталог</strong> с поиском, бронированием и личным кабинетом</div>
          </li>
        </ul>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <h2>Проект «Цифровая умная библиотека»</h2>
          <p>Создание современной цифровой платформы для библиотеки университета.</p>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
        <div class="card" style="padding: 28px;">
          <div class="eyebrow" style="color: var(--violet);">Цель проекта</div>
          <p style="color: var(--muted); font-size: 16px; line-height: 1.7; margin: 0;">
            Создание полноценной цифровой библиотечной платформы, которая заменит устаревшую инфраструктуру и обеспечит современный уровень обслуживания, управления фондом и аналитики.
          </p>
        </div>

        <div class="card" style="padding: 28px;">
          <div class="eyebrow" style="color: var(--cyan);">Что уже сделано</div>
          <p style="color: var(--muted); font-size: 16px; line-height: 1.7; margin: 0;">
            Перенос данных в PostgreSQL, онлайн-каталог с поиском и карточками книг, личный кабинет читателя, система выдач и бронирований, внутренние инструменты библиотекарей.
          </p>
        </div>

        <div class="card" style="padding: 28px;">
          <div class="eyebrow" style="color: var(--pink);">Следующие шаги</div>
          <p style="color: var(--muted); font-size: 16px; line-height: 1.7; margin: 0;">
            Расширение цифровых сервисов, углубление аналитики, развитие электронных ресурсов, повышение качества данных и интеграция с информационными системами университета.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <h2>Структура библиотеки</h2>
          <p>Подразделения и направления работы.</p>
        </div>
      </div>

      <div class="feature-grid">
        <div class="feature-card">
          <div class="icon">📋</div>
          <h3>Отдел обслуживания</h3>
          <p>Выдача и возврат литературы, работа с читателями на абонементе и в читальных залах, обслуживание бронирований.</p>
        </div>

        <div class="feature-card">
          <div class="icon">🗃</div>
          <h3>Отдел комплектования и каталогизации</h3>
          <p>Формирование и пополнение фонда, библиографическая обработка, ведение каталогов, работа с поставщиками и издательствами.</p>
        </div>

        <div class="feature-card">
          <div class="icon">💡</div>
          <h3>Информационно-библиографический отдел</h3>
          <p>Консультации по поиску информации, формирование библиографических списков, информационная грамотность, работа с электронными ресурсами.</p>
        </div>

        <div class="feature-card">
          <div class="icon">🖥</div>
          <h3>Цифровое развитие</h3>
          <p>Развитие и поддержка цифровой библиотечной платформы, онлайн-каталога, электронных сервисов и интеграций с университетскими системами.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section" style="text-align: center;">
    <div class="container">
      <h2 style="margin: 0 0 12px; font-size: clamp(28px, 4vw, 38px); font-weight: 900; letter-spacing: -1px;">Есть вопросы?</h2>
      <p style="color: var(--muted); font-size: 17px; margin: 0 0 28px;">Свяжитесь с нами или посетите библиотеку лично.</p>
      <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="/contacts" class="btn btn-primary">Контакты</a>
        <a href="/catalog" class="btn btn-ghost">Перейти в каталог</a>
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

  @media (max-width: 900px) {
    .about-grid {
      grid-template-columns: 1fr;
      gap: 32px;
    }
  }

  @media (max-width: 680px) {
    .about-grid {
      gap: 24px;
    }
  }
</style>
@endsection
