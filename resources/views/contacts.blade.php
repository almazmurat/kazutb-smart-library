@extends('layouts.public')

@section('title', 'Контакты — Библиотека КазУТБ')

@section('content')
  <section class="page-hero">
    <div class="container">
      <div class="eyebrow">Контакты</div>
      <h1>Свяжитесь с нами</h1>
      <p>Контактная информация библиотеки Казахского университета технологии и бизнеса. Мы готовы ответить на ваши вопросы.</p>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="contact-grid">
        <div class="contact-card">
          <div class="icon">📍</div>
          <h3>Адрес</h3>
          <p>г. Астана, ул. Кайым Мухамедханова, 37А<br>Казахский университет технологии и бизнеса</p>
        </div>

        <div class="contact-card">
          <div class="icon">📞</div>
          <h3>Телефон</h3>
          <p><a href="tel:+77172645858">+7 (7172) 64-58-58</a></p>
          <p style="font-size: 13px; margin-top: 8px;">Справочная библиотеки</p>
        </div>

        <div class="contact-card">
          <div class="icon">✉️</div>
          <h3>Электронная почта</h3>
          <p><a href="mailto:library@kazutb.kz">library@kazutb.kz</a></p>
          <p style="font-size: 13px; margin-top: 8px;">Для запросов и обращений</p>
        </div>

        <div class="contact-card">
          <div class="icon">🌐</div>
          <h3>Веб-сайт университета</h3>
          <p><a href="https://kazutb.kz" target="_blank" rel="noopener">kazutb.kz</a></p>
          <p style="font-size: 13px; margin-top: 8px;">Официальный сайт КазУТБ</p>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: start;">
      <div>
        <div class="section-head" style="margin-bottom: 24px;">
          <div>
            <h2>Режим работы</h2>
            <p>График работы библиотеки.</p>
          </div>
        </div>

        <div class="card" style="padding: 28px;">
          <ul class="info-list">
            <li>
              <span class="icon">📅</span>
              <div>
                <strong>Понедельник – Пятница</strong><br>
                <span style="color: var(--muted);">09:00 – 18:00</span>
              </div>
            </li>
            <li>
              <span class="icon">📅</span>
              <div>
                <strong>Суббота</strong><br>
                <span style="color: var(--muted);">10:00 – 14:00</span>
              </div>
            </li>
            <li>
              <span class="icon">🔴</span>
              <div>
                <strong>Воскресенье</strong><br>
                <span style="color: var(--muted);">Выходной</span>
              </div>
            </li>
          </ul>
        </div>

        <p style="color: var(--muted); font-size: 14px; margin-top: 16px; line-height: 1.6;">
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

        <div class="card" style="padding: 28px;">
          <ul class="info-list">
            <li>
              <span class="icon">📋</span>
              <div>
                <strong>Абонемент</strong><br>
                <span style="color: var(--muted);">Выдача и возврат литературы, оформление читательских билетов</span>
              </div>
            </li>
            <li>
              <span class="icon">📖</span>
              <div>
                <strong>Читальный зал</strong><br>
                <span style="color: var(--muted);">Работа с литературой, доступ к периодическим изданиям</span>
              </div>
            </li>
            <li>
              <span class="icon">💡</span>
              <div>
                <strong>Информационно-библиографический отдел</strong><br>
                <span style="color: var(--muted);">Консультации, помощь в поиске источников, информационная грамотность</span>
              </div>
            </li>
            <li>
              <span class="icon">🗃</span>
              <div>
                <strong>Отдел комплектования</strong><br>
                <span style="color: var(--muted);">Комплектование фонда, работа с издательствами, каталогизация</span>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <h2>Как нас найти</h2>
          <p>Библиотека расположена в главном корпусе КазУТБ.</p>
        </div>
      </div>

      <div class="card" style="padding: 0; overflow: hidden; height: 400px; border-radius: var(--radius-xl);">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2503.8!2d71.4107!3d51.1283!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4245850e07ff5601%3A0x4d6a04db6e51e3e8!2z0JrQsNC30LDRhdGB0LrQuNC5INGD0L3QuNCy0LXRgNGB0LjRgtC10YIg0YLQtdGF0L3QvtC70L7Qs9C40Lgg0Lgg0LHQuNC30L3QtdGB0LA!5e0!3m2!1sru!2skz!4v1700000000000!5m2!1sru!2skz"
          width="100%"
          height="100%"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Расположение библиотеки КазУТБ на карте">
        </iframe>
      </div>
      <p style="color: var(--muted); font-size: 14px; margin-top: 14px; text-align: center;">
        📍 г. Астана, ул. Кайым Мухамедханова, 37А · Казахский университет технологии и бизнеса
      </p>
    </div>
  </section>

  <section class="page-section" style="text-align: center;">
    <div class="container">
      <h2 style="margin: 0 0 12px; font-size: clamp(28px, 4vw, 38px); font-weight: 900; letter-spacing: -1px;">Нужна помощь?</h2>
      <p style="color: var(--muted); font-size: 17px; margin: 0 0 28px;">Позвоните, напишите или посетите библиотеку лично.</p>
      <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
        <a href="mailto:library@kazutb.kz" class="btn btn-primary">Написать нам</a>
        <a href="/catalog" class="btn btn-ghost">Перейти в каталог</a>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  @media (max-width: 900px) {
    .container[style*="grid-template-columns: 1fr 1fr"] {
      grid-template-columns: 1fr !important;
    }
  }
</style>
@endsection
