{{-- Shared footer partial — @include('partials.footer') --}}
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-col">
      <div class="footer-title">Библиотека КазУТБ</div>
      <p>Цифровая библиотека Казахского университета технологии и бизнеса — современная платформа для доступа к фонду, электронным ресурсам и библиотечным сервисам.</p>
    </div>

    <div class="footer-col">
      <div class="footer-title">Разделы</div>
      <a href="/catalog">Каталог</a>
      <a href="/services">Сервисы</a>
      <a href="/about">О библиотеке</a>
      <a href="/contacts">Контакты</a>
      <a href="/account">Личный кабинет</a>
    </div>

    <div class="footer-col">
      <div class="footer-title">Контакты</div>
      <a href="#">г. Астана, ул. Кайым Мухамедханова, 37А</a>
      <a href="tel:+77172645858">+7 (7172) 64-58-58</a>
      <a href="mailto:library@kazutb.kz">library@kazutb.kz</a>
    </div>

    <div class="footer-col">
      <div class="footer-title">Режим работы</div>
      <p>Пн–Пт: 09:00 – 18:00</p>
      <p>Сб: 10:00 – 14:00</p>
      <p>Вс: выходной</p>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>© {{ date('Y') }} Библиотека КазУТБ. Все права защищены.</p>
  </div>
</footer>
