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
  $copy = [
      'ru' => [
          'links' => [
              ['key' => 'catalog', 'label' => 'Каталог', 'href' => $routeWithLang('/catalog')],
              ['key' => 'resources', 'label' => 'Ресурсы', 'href' => $routeWithLang('/resources')],
              ['key' => 'discover', 'label' => 'Навигация', 'href' => $routeWithLang('/discover')],
              ['key' => 'about', 'label' => 'О библиотеке', 'href' => $routeWithLang('/about')],
          ],
          'guest' => 'Войти',
          'logout' => 'Выйти',
          'account' => 'Кабинет',
      ],
      'kk' => [
          'links' => [
              ['key' => 'catalog', 'label' => 'Каталог', 'href' => $routeWithLang('/catalog')],
              ['key' => 'resources', 'label' => 'Ресурстар', 'href' => $routeWithLang('/resources')],
              ['key' => 'discover', 'label' => 'Бағыттар', 'href' => $routeWithLang('/discover')],
              ['key' => 'about', 'label' => 'Кітапхана туралы', 'href' => $routeWithLang('/about')],
          ],
          'guest' => 'Кіру',
          'logout' => 'Шығу',
          'account' => 'Кабинет',
      ],
      'en' => [
          'links' => [
              ['key' => 'catalog', 'label' => 'Catalog', 'href' => $routeWithLang('/catalog')],
              ['key' => 'resources', 'label' => 'Resources', 'href' => $routeWithLang('/resources')],
              ['key' => 'discover', 'label' => 'Discover', 'href' => $routeWithLang('/discover')],
              ['key' => 'about', 'label' => 'About', 'href' => $routeWithLang('/about')],
          ],
          'guest' => 'Sign in',
          'logout' => 'Sign out',
          'account' => 'Account',
      ],
  ][$pageLang];
@endphp
<header class="top-0 sticky z-50 transition-all">
  <nav class="bg-slate-50/80 backdrop-blur-md text-blue-950 border-b border-slate-200/60">
    <div class="flex justify-between items-center px-6 md:px-8 py-4 w-full max-w-screen-2xl mx-auto gap-4">
      <a href="{{ $routeWithLang('/') }}" class="inline-flex items-center gap-3 text-lg md:text-xl font-['Newsreader'] tracking-tight text-blue-950 font-medium whitespace-nowrap" aria-label="{{ __('ui.brand.home_aria') }}">
        <img src="{{ asset('logo.png') }}" alt="{{ __('ui.brand.title') }} logo" class="navbar-brand-logo w-10 h-10 md:w-11 md:h-11 rounded-full object-contain bg-white p-1.5 shadow-sm ring-1 ring-slate-200" loading="eager" decoding="async">
        <span>{{ __('ui.brand.title') }}</span>
      </a>

      <button
        class="mobile-toggle md:hidden"
        type="button"
        onclick="const nav = document.getElementById('site-nav'); nav?.classList.toggle('open'); this.setAttribute('aria-expanded', nav?.classList.contains('open') ? 'true' : 'false');"
        aria-label="{{ __('ui.aria.open_menu') }}"
        aria-expanded="false"
        aria-controls="site-nav"
      >☰</button>

      <div id="site-nav" class="nav-links hidden md:flex items-center space-x-8" aria-label="{{ __('ui.aria.main_navigation') }}">
        @foreach($copy['links'] as $item)
          <a
            class="font-['Manrope'] transition-colors duration-300 {{ ($activePage ?? '') === ($item['key'] ?? '') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-teal-600' }}"
            href="{{ $item['href'] }}"
          >{{ $item['label'] }}</a>
        @endforeach
      </div>

      <div class="flex items-center space-x-3 md:space-x-6">
        <div class="hidden lg:inline-flex items-center rounded-full border border-slate-200 bg-white/80 p-1" data-locale-switcher aria-label="{{ __('ui.aria.locale_switcher') }}">
          <a href="{{ request()->fullUrlWithQuery(['lang' => 'kk']) }}" class="px-2 py-1 text-[10px] font-semibold rounded-full @if($pageLang === 'kk') bg-slate-900 text-white @else text-slate-600 @endif">KK</a>
          <a href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}" class="px-2 py-1 text-[10px] font-semibold rounded-full @if($pageLang === 'ru') bg-slate-900 text-white @else text-slate-600 @endif">RU</a>
          <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}" class="px-2 py-1 text-[10px] font-semibold rounded-full @if($pageLang === 'en') bg-slate-900 text-white @else text-slate-600 @endif">EN</a>
        </div>

        @if($isAuthenticated)
          <button type="button" id="shared-logout-btn" class="font-['Manrope'] text-sm font-semibold text-teal-700 hover:opacity-80 transition-all">{{ $copy['logout'] }}</button>
        @else
          <a href="{{ $routeWithLang('/login') }}" class="font-['Manrope'] text-sm font-semibold text-teal-700 hover:opacity-80 transition-all">{{ $copy['guest'] }}</a>
        @endif

        <a href="{{ $routeWithLang('/account') }}" aria-label="{{ $copy['account'] }}" class="text-blue-950 hover:text-teal-700 transition-colors duration-300">
          <span class="material-symbols-outlined text-2xl cursor-pointer" data-icon="account_circle">account_circle</span>
        </a>
      </div>
    </div>
  </nav>
</header>
