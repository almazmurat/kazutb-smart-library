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
              ['key' => 'catalog',   'label' => 'Каталог',   'href' => $routeWithLang('/catalog')],
              ['key' => 'discover',  'label' => 'Открытия',  'href' => $routeWithLang('/discover')],
              ['key' => 'resources', 'label' => 'Ресурсы',   'href' => $routeWithLang('/resources')],
              ['key' => 'news',      'label' => 'Новости',   'href' => $routeWithLang('/news')],
              ['key' => 'events',    'label' => 'События',   'href' => $routeWithLang('/events')],
          ],
          'institution' => [
              'label' => 'Об институте',
              'aria'  => 'Раздел «Об институте»',
              'items' => [
                  ['key' => 'about',      'label' => 'О библиотеке',     'href' => $routeWithLang('/about')],
                  ['key' => 'leadership', 'label' => 'Руководство',      'href' => $routeWithLang('/leadership')],
                  ['key' => 'rules',      'label' => 'Правила',          'href' => $routeWithLang('/rules')],
                  ['key' => 'contacts',   'label' => 'Контакты',         'href' => $routeWithLang('/contacts')],
              ],
          ],
          'guest'        => 'Войти',
          'logout'       => 'Выйти',
          'dashboard'    => 'Кабинет',
          'lang_aria'    => 'Переключатель языка',
      ],
      'kk' => [
          'links' => [
              ['key' => 'catalog',   'label' => 'Каталог',   'href' => $routeWithLang('/catalog')],
              ['key' => 'discover',  'label' => 'Ашылымдар', 'href' => $routeWithLang('/discover')],
              ['key' => 'resources', 'label' => 'Ресурстар', 'href' => $routeWithLang('/resources')],
              ['key' => 'news',      'label' => 'Жаңалықтар','href' => $routeWithLang('/news')],
              ['key' => 'events',    'label' => 'Іс-шаралар','href' => $routeWithLang('/events')],
          ],
          'institution' => [
              'label' => 'Институт туралы',
              'aria'  => '«Институт туралы» бөлімі',
              'items' => [
                  ['key' => 'about',      'label' => 'Кітапхана туралы', 'href' => $routeWithLang('/about')],
                  ['key' => 'leadership', 'label' => 'Басшылық',         'href' => $routeWithLang('/leadership')],
                  ['key' => 'rules',      'label' => 'Ережелер',         'href' => $routeWithLang('/rules')],
                  ['key' => 'contacts',   'label' => 'Байланыс',         'href' => $routeWithLang('/contacts')],
              ],
          ],
          'guest'        => 'Кіру',
          'logout'       => 'Шығу',
          'dashboard'    => 'Кабинет',
          'lang_aria'    => 'Тіл ауыстырғыш',
      ],
      'en' => [
          'links' => [
              ['key' => 'catalog',   'label' => 'Catalog',   'href' => $routeWithLang('/catalog')],
              ['key' => 'discover',  'label' => 'Discover',  'href' => $routeWithLang('/discover')],
              ['key' => 'resources', 'label' => 'Resources', 'href' => $routeWithLang('/resources')],
              ['key' => 'news',      'label' => 'News',      'href' => $routeWithLang('/news')],
              ['key' => 'events',    'label' => 'Events',    'href' => $routeWithLang('/events')],
          ],
          'institution' => [
              'label' => 'Institution',
              'aria'  => 'Institution menu',
              'items' => [
                  ['key' => 'about',      'label' => 'About',      'href' => $routeWithLang('/about')],
                  ['key' => 'leadership', 'label' => 'Leadership', 'href' => $routeWithLang('/leadership')],
                  ['key' => 'rules',      'label' => 'Rules',      'href' => $routeWithLang('/rules')],
                  ['key' => 'contacts',   'label' => 'Contacts',   'href' => $routeWithLang('/contacts')],
              ],
          ],
          'guest'        => 'Sign in',
          'logout'       => 'Sign out',
          'dashboard'    => 'Dashboard',
          'lang_aria'    => 'Language switcher',
      ],
  ][$pageLang];

  $localeLabels = ['kk' => 'KK', 'ru' => 'RU', 'en' => 'EN'];
@endphp
<header class="top-0 sticky z-50 transition-all">
  <nav class="bg-slate-50/80 backdrop-blur-md text-blue-950 border-b border-slate-200/60">
    <div class="flex justify-between items-center px-6 md:px-8 py-4 w-full max-w-screen-2xl mx-auto gap-4">
      <a href="{{ $routeWithLang('/') }}" class="inline-flex items-center text-lg md:text-xl font-['Newsreader'] tracking-tight text-blue-950 font-medium whitespace-nowrap align-middle" aria-label="{{ __('ui.brand.home_aria') }}">
        <img src="{{ asset('logo.png') }}" alt="{{ __('ui.brand.title') }} logo" class="navbar-brand-logo sr-only" loading="eager" decoding="async">
        <span class="align-middle leading-tight">{{ __('ui.brand.title') }}</span>
      </a>

      <button
        class="mobile-toggle md:hidden"
        type="button"
        onclick="const nav = document.getElementById('site-nav'); nav?.classList.toggle('open'); this.setAttribute('aria-expanded', nav?.classList.contains('open') ? 'true' : 'false');"
        aria-label="{{ __('ui.aria.open_menu') }}"
        aria-expanded="false"
        aria-controls="site-nav"
      >☰</button>

      <div id="site-nav" class="nav-links hidden md:flex items-center space-x-6 lg:space-x-8" aria-label="{{ __('ui.aria.main_navigation') }}">
        @foreach($copy['links'] as $item)
          <a
            class="font-['Manrope'] text-sm transition-colors duration-300 {{ ($activePage ?? '') === ($item['key'] ?? '') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-teal-600' }}"
            href="{{ $item['href'] }}"
          >{{ $item['label'] }}</a>
        @endforeach

        @php
          $institutionKeys = ['about', 'leadership', 'rules', 'contacts'];
          $institutionActive = in_array(($activePage ?? ''), $institutionKeys, true);
        @endphp
        <details class="nav-disclosure relative" @if($institutionActive) open @endif>
          <summary
            class="font-['Manrope'] text-sm cursor-pointer list-none inline-flex items-center gap-1 transition-colors duration-300 {{ $institutionActive ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-teal-600' }}"
            aria-label="{{ $copy['institution']['aria'] }}"
          >
            <span>{{ $copy['institution']['label'] }}</span>
            <span aria-hidden="true" class="text-xs">▾</span>
          </summary>
          <div class="nav-disclosure-panel absolute right-0 mt-2 min-w-[12rem] bg-white border border-slate-200 rounded-md shadow-lg py-2 z-50">
            @foreach($copy['institution']['items'] as $item)
              <a
                class="block px-4 py-2 font-['Manrope'] text-sm transition-colors {{ ($activePage ?? '') === ($item['key'] ?? '') ? 'text-teal-700 font-semibold bg-slate-50' : 'text-slate-600 hover:text-teal-700 hover:bg-slate-50' }}"
                href="{{ $item['href'] }}"
              >{{ $item['label'] }}</a>
            @endforeach
          </div>
        </details>
      </div>

      <div class="flex items-center space-x-2 md:space-x-3 lg:space-x-4">
        <div class="locale-switcher hidden sm:inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white/70 px-1 py-0.5 text-xs font-['Manrope']" data-locale-switcher role="group" aria-label="{{ $copy['lang_aria'] }}">
          @foreach(['kk', 'ru', 'en'] as $locale)
            <a
              href="{{ request()->fullUrlWithQuery(['lang' => $locale]) }}"
              class="px-2 py-0.5 rounded-full transition-colors {{ $pageLang === $locale ? 'bg-teal-600 text-white font-semibold' : 'text-slate-600 hover:text-teal-700' }}"
              hreflang="{{ $locale }}"
              data-locale="{{ $locale }}"
              @if($pageLang === $locale) aria-current="true" @endif
            >{{ $localeLabels[$locale] }}</a>
          @endforeach
        </div>

        @if($isAuthenticated)
          <a href="{{ $routeWithLang('/dashboard') }}" class="font-['Manrope'] text-sm font-semibold text-teal-700 hover:opacity-80 transition-all whitespace-nowrap">{{ $copy['dashboard'] }}</a>
          <button type="button" id="shared-logout-btn" class="font-['Manrope'] text-sm font-semibold text-slate-500 hover:text-teal-700 transition-all">{{ $copy['logout'] }}</button>
        @else
          <a href="{{ $routeWithLang('/login') }}" class="font-['Manrope'] text-sm font-semibold text-teal-700 hover:opacity-80 transition-all">{{ $copy['guest'] }}</a>
        @endif

        <a href="{{ $routeWithLang('/dashboard') }}" aria-label="{{ $copy['dashboard'] }}" class="text-blue-950 hover:text-teal-700 transition-colors duration-300 flex items-center">
          <span class="material-symbols-outlined text-2xl md:text-[28px] cursor-pointer align-middle leading-none" data-icon="account_circle">account_circle</span>
        </a>
      </div>
    </div>
  </nav>
</header>
