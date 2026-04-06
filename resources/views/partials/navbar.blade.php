{{-- Shared navbar partial — @include('partials.navbar', ['activePage' => 'home']) --}}
<header class="topbar">
  <div class="container nav">
    <a href="/" class="brand">
      <div class="brand-badge">
        <img src="/logo.png" alt="Logo" class="logo-img">
      </div>
      <div class="brand-text">
        КАЗАХСКИЙ УНИВЕРСИТЕТ ТЕХНОЛОГИИ и БИЗНЕСА
        <small>Цифровая библиотека университета</small>
      </div>
    </a>

    <button class="mobile-toggle" onclick="document.querySelector('.nav-links').classList.toggle('open')" aria-label="Меню">☰</button>

    <nav class="nav-links" onclick="if(window.innerWidth<=900)this.classList.remove('open')">
      <a href="/" @if(($activePage ?? '') === 'home') class="active" @endif>Главная</a>
      <a href="/catalog" @if(($activePage ?? '') === 'catalog') class="active" @endif>Каталог</a>
      <a href="/resources" @if(($activePage ?? '') === 'resources') class="active" @endif>Ресурсы</a>
      <a href="/services" @if(($activePage ?? '') === 'services') class="active" @endif>Сервисы</a>
      <a href="/news" @if(($activePage ?? '') === 'news') class="active" @endif>Новости</a>
      <a href="/about" @if(($activePage ?? '') === 'about') class="active" @endif>О библиотеке</a>
      <a href="/contacts" @if(($activePage ?? '') === 'contacts') class="active" @endif>Контакты</a>
    </nav>

    <div class="nav-actions">
      @if(session('library.user'))
        <a href="/account" class="btn btn-ghost">Кабинет</a>
        <button type="button" class="btn btn-primary" id="shared-logout-btn">Выйти</button>
      @else
        <a href="/login" class="btn btn-ghost">Войти</a>
        <a href="/account" class="btn btn-primary">Личный кабинет</a>
      @endif
    </div>
  </div>
</header>
