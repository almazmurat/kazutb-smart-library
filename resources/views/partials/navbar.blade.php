{{-- Shared navbar partial — @include('partials.navbar', ['activePage' => 'home']) --}}
@php
  $pageLang = request()->query('lang', 'ru');
  $pageLang = in_array($pageLang, ['kk', 'ru', 'en'], true) ? $pageLang : 'ru';
  $langSuffix = $pageLang === 'ru' ? '' : ('?lang=' . $pageLang);
@endphp
<header class="topbar topbar--glass">
  <div class="container nav">
    <a href="/" class="brand" aria-label="Перейти на главную страницу Digital Library">
      <div class="brand-badge">
        <img src="/logo.png" alt="Логотип Digital Library" class="logo-img">
      </div>
      <div class="brand-text">
        DIGITAL LIBRARY
        <small>единое пространство знаний</small>
      </div>
    </a>

    <button
      class="mobile-toggle"
      type="button"
      onclick="const nav = this.parentElement.querySelector('.nav-links'); nav?.classList.toggle('open'); this.setAttribute('aria-expanded', nav?.classList.contains('open') ? 'true' : 'false');"
      aria-label="Открыть меню сайта"
      aria-expanded="false"
      aria-controls="site-nav"
    >☰</button>

    <nav id="site-nav" class="nav-links" aria-label="Основная навигация сайта" onclick="if(window.innerWidth<=900){ this.classList.remove('open'); this.parentElement.querySelector('.mobile-toggle')?.setAttribute('aria-expanded', 'false'); }">
      <a href="/{{ $langSuffix }}" class="nav-link-pill @if(($activePage ?? '') === 'home') active @endif">Главная</a>
      <a href="/catalog{{ $langSuffix }}" class="nav-link-pill @if(($activePage ?? '') === 'catalog') active @endif">Каталог</a>
      <a href="/resources{{ $langSuffix }}" class="nav-link-pill @if(($activePage ?? '') === 'resources') active @endif">Ресурсы</a>
      <a href="/discover{{ $langSuffix }}" class="nav-link-pill @if(($activePage ?? '') === 'discover') active @endif">Направления</a>
      <a href="/contacts{{ $langSuffix }}" class="nav-link-pill @if(($activePage ?? '') === 'contacts') active @endif">Контакты</a>
    </nav>

    <div class="nav-actions">
      <div class="locale-switcher" data-locale-switcher aria-label="Переключение языка">
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'kk']) }}" class="locale-link @if($pageLang === 'kk') active @endif">KK</a>
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}" class="locale-link @if($pageLang === 'ru') active @endif">RU</a>
        <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" class="locale-link @if($pageLang === 'en') active @endif">EN</a>
      </div>
      @if(session('library.user'))
        <a href="/account" class="btn btn-ghost">Кабинет</a>
        <button type="button" class="btn btn-primary" id="shared-logout-btn">Выйти</button>
      @else
        <a href="/login" class="btn btn-ghost">Войти</a>
        <a href="/account" class="btn btn-primary">Открыть кабинет</a>
      @endif
    </div>
  </div>
</header>
