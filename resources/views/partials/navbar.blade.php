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

    <button
      class="mobile-toggle"
      type="button"
      onclick="const nav = this.parentElement.querySelector('.nav-links'); nav?.classList.toggle('open'); this.setAttribute('aria-expanded', nav?.classList.contains('open') ? 'true' : 'false');"
      aria-label="Меню"
      aria-expanded="false"
      aria-controls="site-nav"
    >☰</button>

    <nav id="site-nav" class="nav-links" onclick="if(window.innerWidth<=900){ this.classList.remove('open'); this.parentElement.querySelector('.mobile-toggle')?.setAttribute('aria-expanded', 'false'); }">
      <a href="/" @if(($activePage ?? '') === 'home') class="active" @endif>Главная</a>
      <a href="/catalog" @if(($activePage ?? '') === 'catalog') class="active" @endif>Каталог</a>
      <a href="/resources" @if(($activePage ?? '') === 'resources') class="active" @endif>Ресурсы</a>
      <a href="/for-teachers" @if(($activePage ?? '') === 'for-teachers') class="active" @endif>Преподавателям</a>
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
