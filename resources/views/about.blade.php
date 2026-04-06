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
        <h2 class="heading-xl">
          Обеспечение доступа к знаниям и информационным ресурсам
        </h2>
        <p class="text-body" style="margin: 0 0 20px;">
          Библиотека КазУТБ обеспечивает информационную поддержку учебного процесса и научных исследований университета. Наша задача — предоставить каждому пользователю удобный и полный доступ к фонду учебной, научной и справочной литературы.
        </p>
        <p class="text-body" style="margin: 0;">
          Мы развиваем фонд, расширяем цифровые подписки, внедряем современные технологии обслуживания и создаём комфортные условия для работы с информацией.
        </p>
      </div>

      <div class="card">
        <h3 class="heading-lg">Библиотека в цифрах</h3>
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
      <div class="section-head section-head-centered">
        <div>
          <h2>Проект «Цифровая умная библиотека»</h2>
          <p>Создание современной цифровой платформы для библиотеки университета.</p>
        </div>
      </div>

      <div class="about-project-grid">
        <div class="card">
          <div class="eyebrow eyebrow--violet">Цель проекта</div>
          <p class="text-body" style="margin: 0;">
            Создание полноценной цифровой библиотечной платформы, которая заменит устаревшую инфраструктуру и обеспечит современный уровень обслуживания, управления фондом и аналитики.
          </p>
        </div>

        <div class="card">
          <div class="eyebrow eyebrow--cyan">Что уже сделано</div>
          <p class="text-body" style="margin: 0;">
            Перенос данных в PostgreSQL, онлайн-каталог с поиском и карточками книг, личный кабинет читателя, система выдач и бронирований, внутренние инструменты библиотекарей.
          </p>
        </div>

        <div class="card">
          <div class="eyebrow eyebrow--pink">Следующие шаги</div>
          <p class="text-body" style="margin: 0;">
            Расширение цифровых сервисов, углубление аналитики, развитие электронных ресурсов, повышение качества данных и интеграция с информационными системами университета.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head section-head-centered">
        <div>
          <h2>Структура библиотеки</h2>
          <p>Подразделения и направления работы.</p>
        </div>
      </div>

      <div class="feature-grid about-structure-grid">
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

  <section class="cta-section">
    <div class="container">
      <h2>Есть вопросы?</h2>
      <p>Свяжитесь с нами или посетите библиотеку лично.</p>
      <div class="cta-buttons">
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

  .about-project-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 320px));
    gap: 24px;
    justify-content: center;
  }

  .about-structure-grid {
    grid-template-columns: repeat(auto-fit, minmax(240px, 320px));
    justify-content: center;
  }

  .about-structure-grid .feature-card {
    width: 100%;
    max-width: 320px;
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
