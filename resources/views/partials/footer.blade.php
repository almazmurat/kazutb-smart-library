{{-- Shared footer partial — @include('partials.footer') --}}
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-col">
      <div class="footer-title">Библиотека КазУТБ</div>
      <p>Цифровая библиотека Казахского университета технологии и бизнеса — современная платформа для доступа к фонду, электронным ресурсам и библиотечным сервисам.</p>
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
      <a href="/catalog">Научная литература</a>
      <a href="/resources">Базы данных</a>
      <a href="/services">Поддержка исследований</a>
      <a href="/news">Новости и мероприятия</a>
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
      <a href="#">г. Астана, ул. Кайым Мухамедханова, 37А</a>
      <a href="tel:+77172645858">+7 (7172) 64-58-58</a>
      <a href="mailto:library@kazutb.kz">library@kazutb.kz</a>
      <p style="margin-top:6px">Пн–Пт: 09:00–18:00<br>Сб: 10:00–14:00</p>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>© {{ date('Y') }} Библиотека КазУТБ. Все права защищены.</p>
  </div>
</footer>
