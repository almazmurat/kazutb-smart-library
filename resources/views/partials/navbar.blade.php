@php
  $pageLang = $pageLang ?? app()->getLocale();
  $pageLang = in_array($pageLang, ['kk', 'ru', 'en'], true) ? $pageLang : 'ru';
  $routeWithLang = static function (string $path, array $query = []) use ($pageLang): string {
      $normalizedPath = '/' . ltrim($path, '/');
      if ($normalizedPath === '//') {
          $normalizedPath = '/';
      }

      if ($pageLang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $pageLang;
      }

      $query = array_filter($query, static fn ($value) => $value !== null && $value !== '');

      return $normalizedPath . ($query ? ('?' . http_build_query($query)) : '');
  };

  $isAuthenticated = (bool) session('library.user');
  $navItems = [
      ['key' => 'home', 'path' => '/'],
      ['key' => 'catalog', 'path' => '/catalog'],
      ['key' => 'resources', 'path' => '/resources'],
      ['key' => 'discover', 'path' => '/discover'],
      ['key' => 'shortlist', 'path' => '/shortlist'],
      ['key' => 'about', 'path' => '/about'],
  ];
@endphp
<header class="topbar topbar--glass">
  <div class="container nav-shell">
    <div class="nav-shell__row">
      <a href="{{ $routeWithLang('/') }}" class="brand brand--public" aria-label="{{ __('ui.brand.home_aria') }}">
        <span class="brand-mark brand-mark--logo">
          <img src="{{ asset('logo.png') }}" alt="{{ __('ui.brand.title') }} logo" class="logo-img logo-img--brand navbar-brand-logo" loading="eager" decoding="async">
        </span>
        <span class="brand-text">
          {{ __('ui.brand.title') }}
          <small>{{ __('ui.brand.subtitle') }}</small>
        </span>
      </a>

      <button
        class="mobile-toggle"
        type="button"
        onclick="const shell = this.closest('.nav-shell'); const nav = shell?.querySelector('.nav-links'); nav?.classList.toggle('open'); this.setAttribute('aria-expanded', nav?.classList.contains('open') ? 'true' : 'false');"
        aria-label="{{ __('ui.aria.open_menu') }}"
        aria-expanded="false"
        aria-controls="site-nav"
      >☰</button>

      <div class="nav-cluster">
        <nav id="site-nav" class="nav-links nav-links--public" aria-label="{{ __('ui.aria.main_navigation') }}" onclick="if(window.innerWidth<=900){ this.classList.remove('open'); this.closest('.nav-shell')?.querySelector('.mobile-toggle')?.setAttribute('aria-expanded', 'false'); }">
          @foreach($navItems as $item)
            <a href="{{ $routeWithLang($item['path']) }}" class="nav-link-pill @if(($activePage ?? '') === $item['key']) active @endif">{{ __('ui.nav.' . $item['key']) }}</a>
          @endforeach
        </nav>

        <div class="nav-actions">
          <div class="locale-switcher" data-locale-switcher aria-label="{{ __('ui.aria.locale_switcher') }}">
            <a href="{{ request()->fullUrlWithQuery(['lang' => 'kk']) }}" class="locale-link @if($pageLang === 'kk') active @endif">KK</a>
            <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}" class="locale-link @if($pageLang === 'ru') active @endif">RU</a>
            <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" class="locale-link @if($pageLang === 'en') active @endif">EN</a>
          </div>

          @if($isAuthenticated)
            <a href="{{ $routeWithLang('/account') }}" class="shell-btn shell-btn--ghost">{{ __('ui.actions.account') }}</a>
            <button type="button" class="shell-btn shell-btn--primary" id="shared-logout-btn">{{ __('ui.actions.sign_out') }}</button>
          @else
            <a href="{{ $routeWithLang('/login') }}" class="shell-btn shell-btn--ghost">{{ __('ui.actions.sign_in') }}</a>
            <a href="{{ $routeWithLang('/account') }}" class="shell-btn shell-btn--primary">{{ __('ui.actions.open_portal') }}</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</header>
