{{-- Shared footer partial — @include('partials.footer') --}}
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
      <p>Современная платформа для доступа к университетскому фонду, электронным ресурсам и библиотечным сервисам Казахского университета технологии и бизнеса.</p>
    </div>

    <div class="footer-col">
      <div class="footer-title">Студентам</div>
      <a href="/catalog">Каталог литературы</a>
      <a href="/resources">Электронные ресурсы</a>
      <a href="/account">Личный кабинет</a>
      <a href="/services">Сервисы библиотеки</a>
      <!-- FUTURE: Add links per school/department when institutional structure is confirmed -->
    </div>

    <div class="footer-col">
      <div class="footer-title">Преподавателям</div>
      <a href="/for-teachers">Ресурсы для преподавателей</a>
      <a href="/catalog">Научная литература</a>
      <a href="/resources">Базы данных</a>
      <a href="/services">Поддержка исследований</a>
    </div>

    <div class="footer-col">
      <div class="footer-title">О библиотеке</div>
      <a href="/about">О нас</a>
      <a href="/contacts">Контакты</a>
      <a href="/news">Новости</a>
      <a href="/services">Все сервисы</a>
    </div>

    <div class="footer-col">
      <div class="footer-title">Контакты</div>
      <p>г. Астана, ул. Кайым Мухамедханова, 37А</p>
      <a href="tel:+77172645858">+7 (7172) 64-58-58</a>
      <a href="mailto:library@kazutb.kz">library@kazutb.kz</a>
      <p style="margin-top:8px; font-size:13px; opacity:.8;">Пн–Пт: 09:00–18:00 · Сб: 10:00–14:00</p>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>© {{ date('Y') }} Библиотека КазУТБ. Все права защищены.</p>
    <div class="footer-bottom-links">
      <a href="/about">О библиотеке</a>
      <a href="/contacts">Контакты</a>
    </div>
  </div>
</footer>
