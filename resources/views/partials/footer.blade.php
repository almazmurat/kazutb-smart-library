{{-- Shared footer partial — @include('partials.footer') --}}
@php
  $pageLang = request()->query('lang', 'ru');
  $pageLang = in_array($pageLang, ['kk', 'ru', 'en'], true) ? $pageLang : 'ru';
  $langSuffix = $pageLang === 'ru' ? '' : ('?lang=' . $pageLang);
@endphp
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-col">
      <div class="footer-brand">
        <img src="/logo.png" alt="КазУТБ" class="logo-img">
        <div class="footer-brand-name">
          КазУТБ
          <small>Цифровая библиотека университета</small>
        </div>
      </div>
      <p>Современная библиотечная платформа для доступа к университетскому фонду, электронным ресурсам, подборкам литературы и внутренним библиотечным сервисам.</p>
      <div class="footer-badge-row" aria-label="Ключевые преимущества платформы">
        <span class="footer-badge">Каталог 24/7</span>
        <span class="footer-badge">Reader-first UX</span>
        <span class="footer-badge">Digital + print</span>
      </div>
    </div>

    <div class="footer-col">
      <div class="footer-title">Студентам</div>
      <a href="/catalog{{ $langSuffix }}">Каталог литературы</a>
      <a href="/resources{{ $langSuffix }}">Электронные ресурсы</a>
      <a href="/account">Личный кабинет</a>
      <a href="/discover{{ $langSuffix }}">По направлениям</a>
    </div>

    <div class="footer-col">
      <div class="footer-title">Преподавателям и исследователям</div>
      <a href="/shortlist">Подборка литературы</a>
      <a href="/catalog{{ $langSuffix }}">Научная литература</a>
      <a href="/resources{{ $langSuffix }}">Базы данных и доступ</a>
      <a href="/discover{{ $langSuffix }}">По направлениям</a>
    </div>

    <div class="footer-col">
      <div class="footer-title">О библиотеке</div>
      <a href="/contacts{{ $langSuffix }}">О нас и контакты</a>
      <a href="/catalog{{ $langSuffix }}">Каталог</a>
      <a href="/resources{{ $langSuffix }}">Электронные ресурсы</a>
      <a href="/login">Вход в систему</a>
    </div>

    <div class="footer-col">
      <div class="footer-title">Контакты</div>
      <p>г. Астана, ул. Кайым Мухамедханова, 37А</p>
      <a href="tel:+77172645858">+7 (7172) 64-58-58</a>
      <a href="mailto:library@kazutb.kz">library@kazutb.kz</a>
      <p class="footer-note">Пн–Пт: 09:00–18:00 · Сб: 10:00–14:00</p>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>© {{ date('Y') }} Библиотека КазУТБ. Все права защищены.</p>
    <div class="footer-bottom-links">
      <a href="/{{ $langSuffix }}">Главная</a>
      <a href="/catalog{{ $langSuffix }}">Каталог</a>
      <a href="/contacts{{ $langSuffix }}">О библиотеке</a>
    </div>
  </div>
</footer>
