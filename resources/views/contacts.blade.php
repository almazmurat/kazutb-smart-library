@extends('layouts.public')

@section('title', 'Контакты — Библиотека КазУТБ')

@section('content')
  <section class="page-hero">
    <div class="container contact-hero-center">
      <div class="eyebrow">Контакты</div>
      <h1>Свяжитесь с нами</h1>
      <p>Контактная информация библиотеки Казахского университета технологии и бизнеса. Мы поможем с поиском литературы, электронными ресурсами, доступом к личному кабинету, продлением и бронированием.</p>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
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

  <section class="page-section">
    <div class="container">
      <div class="section-head section-head-centered">
        <div>
          <h2>Чем можем помочь</h2>
          <p>Основные направления, по которым библиотека консультирует и сопровождает читателей.</p>
        </div>
      </div>

      <div class="support-grid">
        <div class="card support-card">
          <div class="eyebrow eyebrow--blue">Работа с читателем</div>
          <h3>Помощь по обслуживанию</h3>
          <p>Консультации по поиску литературы, оформлению читательского профиля, продлению сроков пользования и правилам обслуживания.</p>
        </div>

        <div class="card support-card">
          <div class="eyebrow eyebrow--cyan">Электронные ресурсы</div>
          <h3>Доступ к цифровым материалам</h3>
          <p>Поддержка по работе с электронным каталогом, библиотечными сервисами, подписными базами данных и доступом к материалам университета.</p>
        </div>

        <div class="card support-card">
          <div class="eyebrow eyebrow--pink">Быстрые действия</div>
          <h3>Куда обратиться быстрее всего</h3>
          <p>По срочным вопросам лучше звонить в справочную, по доступу и электронным обращениям — писать на почту, а для поиска книг использовать каталог и личный кабинет.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <h2>Нужна помощь?</h2>
      <p>Позвоните, напишите или посетите библиотеку лично.</p>
      <div class="cta-buttons">
        <a href="mailto:library@kazutb.kz" class="btn btn-primary">Написать нам</a>
        <a href="/catalog" class="btn btn-ghost">Перейти в каталог</a>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
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
