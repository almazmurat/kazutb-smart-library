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
          'brand'       => 'KazUTB Smart Library',
          'desc'        => 'Каталог, электронные ресурсы и читательские сервисы в едином интерфейсе KazUTB Smart Library.',
          'col_explore' => 'Навигация',
          'col_updates' => 'Обновления',
          'col_inst'    => 'Институт',
          'col_account' => 'Поддержка',
          'col_lang'    => 'Язык',
          'explore' => [
              ['label' => 'Главная',  'href' => $routeWithLang('/')],
              ['label' => 'Каталог',  'href' => $routeWithLang('/catalog')],
              ['label' => 'Открытия', 'href' => $routeWithLang('/discover')],
              ['label' => 'Ресурсы',  'href' => $routeWithLang('/resources')],
          ],
          'updates' => [
              ['label' => 'Новости', 'href' => $routeWithLang('/news')],
              ['label' => 'События', 'href' => $routeWithLang('/events')],
          ],
          'institution' => [
              ['label' => 'О библиотеке',     'href' => $routeWithLang('/about')],
              ['label' => 'Руководство',      'href' => $routeWithLang('/leadership')],
              ['label' => 'Правила библиотеки','href' => $routeWithLang('/rules')],
              ['label' => 'Контакты',         'href' => $routeWithLang('/contacts')],
          ],
          'account' => [
              ['label' => 'Подборка',       'href' => $routeWithLang('/shortlist')],
              ['label' => 'Открыть кабинет','href' => $routeWithLang('/dashboard')],
              ['label' => 'Связаться с библиотекарем', 'href' => $routeWithLang('/contacts')],
          ],
          'auth_signin'  => 'Войти',
          'auth_signin_href' => $routeWithLang('/login'),
          'copyright'   => 'Институциональный библиотечный интерфейс.',
          'lang_aria'   => 'Переключатель языка',
      ],
      'kk' => [
          'brand'       => 'KazUTB Smart Library',
          'desc'        => 'Каталог, электрондық ресурстар және оқырман сервистері KazUTB Smart Library-дің бірыңғай интерфейсінде берілген.',
          'col_explore' => 'Навигация',
          'col_updates' => 'Жаңартулар',
          'col_inst'    => 'Институт',
          'col_account' => 'Қолдау',
          'col_lang'    => 'Тіл',
          'explore' => [
              ['label' => 'Басты бет', 'href' => $routeWithLang('/')],
              ['label' => 'Каталог',   'href' => $routeWithLang('/catalog')],
              ['label' => 'Ашылымдар', 'href' => $routeWithLang('/discover')],
              ['label' => 'Ресурстар', 'href' => $routeWithLang('/resources')],
          ],
          'updates' => [
              ['label' => 'Жаңалықтар', 'href' => $routeWithLang('/news')],
              ['label' => 'Іс-шаралар', 'href' => $routeWithLang('/events')],
          ],
          'institution' => [
              ['label' => 'Кітапхана туралы',     'href' => $routeWithLang('/about')],
              ['label' => 'Басшылық',             'href' => $routeWithLang('/leadership')],
              ['label' => 'Кітапхана ережелері',  'href' => $routeWithLang('/rules')],
              ['label' => 'Байланыс',             'href' => $routeWithLang('/contacts')],
          ],
          'account' => [
              ['label' => 'Іріктеме',         'href' => $routeWithLang('/shortlist')],
              ['label' => 'Кабинетті ашу',    'href' => $routeWithLang('/dashboard')],
              ['label' => 'Кітапханашымен байланысу', 'href' => $routeWithLang('/contacts')],
          ],
          'auth_signin'  => 'Кіру',
          'auth_signin_href' => $routeWithLang('/login'),
          'copyright'   => 'Институционалдық кітапхана интерфейсі.',
          'lang_aria'   => 'Тіл ауыстырғыш',
      ],
      'en' => [
          'brand'       => 'KazUTB Smart Library',
          'desc'        => 'Catalog search, electronic resources, and reader services in one KazUTB Smart Library interface.',
          'col_explore' => 'Navigation',
          'col_updates' => 'Updates',
          'col_inst'    => 'Institution',
          'col_account' => 'Support',
          'col_lang'    => 'Language',
          'explore' => [
              ['label' => 'Home',      'href' => $routeWithLang('/')],
              ['label' => 'Catalog',   'href' => $routeWithLang('/catalog')],
              ['label' => 'Discover',  'href' => $routeWithLang('/discover')],
              ['label' => 'Resources', 'href' => $routeWithLang('/resources')],
          ],
          'updates' => [
              ['label' => 'News',   'href' => $routeWithLang('/news')],
              ['label' => 'Events', 'href' => $routeWithLang('/events')],
          ],
          'institution' => [
              ['label' => 'About',         'href' => $routeWithLang('/about')],
              ['label' => 'Leadership',    'href' => $routeWithLang('/leadership')],
              ['label' => 'Library Rules', 'href' => $routeWithLang('/rules')],
              ['label' => 'Contacts',      'href' => $routeWithLang('/contacts')],
          ],
          'account' => [
              ['label' => 'Shortlist',         'href' => $routeWithLang('/shortlist')],
              ['label' => 'Open portal',       'href' => $routeWithLang('/dashboard')],
              ['label' => 'Contact Librarian', 'href' => $routeWithLang('/contacts')],
          ],
          'auth_signin'  => 'Sign in',
          'auth_signin_href' => $routeWithLang('/login'),
          'copyright'   => 'Institutional library interface.',
          'lang_aria'   => 'Language switcher',
      ],
  ][$pageLang];

  $localeLabels = ['kk' => 'Қазақша', 'ru' => 'Русский', 'en' => 'English'];
@endphp
<footer class="site-footer bg-slate-100 text-blue-950 full-width py-12 px-8 border-t border-slate-200/20">
  <div class="w-full max-w-screen-2xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <div class="lg:col-span-4 space-y-6">
        <div class="inline-flex items-center gap-2 md:gap-3 font-['Newsreader'] font-bold text-blue-950 text-2xl align-middle">
          <img src="{{ asset('logo.png') }}" alt="{{ __('ui.brand.title') }} logo" class="w-12 h-12 md:w-14 md:h-14 rounded-full object-contain bg-white p-1.5 shadow-sm ring-1 ring-slate-200 align-middle" loading="lazy" decoding="async">
          <span class="align-middle leading-tight">{{ $copy['brand'] }}</span>
        </div>
        <p class="font-['Manrope'] text-sm tracking-wide max-w-sm opacity-80">
          {{ $copy['desc'] }}
        </p>
      </div>

      <div class="lg:col-span-8 grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="flex flex-col space-y-3">
          <h5 class="text-primary font-bold text-sm font-body uppercase tracking-wider">{{ $copy['col_explore'] }}</h5>
          @foreach($copy['explore'] as $item)
            <a class="text-slate-500 hover:text-teal-600 transition-colors text-sm" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
          @endforeach
        </div>

        <div class="flex flex-col space-y-3">
          <h5 class="text-primary font-bold text-sm font-body uppercase tracking-wider">{{ $copy['col_updates'] }}</h5>
          @foreach($copy['updates'] as $item)
            <a class="text-slate-500 hover:text-teal-600 transition-colors text-sm" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
          @endforeach
        </div>

        <div class="flex flex-col space-y-3">
          <h5 class="text-primary font-bold text-sm font-body uppercase tracking-wider">{{ $copy['col_inst'] }}</h5>
          @foreach($copy['institution'] as $item)
            <a class="text-slate-500 hover:text-teal-600 transition-colors text-sm" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
          @endforeach
        </div>

        <div class="flex flex-col space-y-3">
          <h5 class="text-primary font-bold text-sm font-body uppercase tracking-wider">{{ $copy['col_account'] }}</h5>
          @foreach($copy['account'] as $item)
            <a class="text-slate-500 hover:text-teal-600 transition-colors text-sm" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
          @endforeach
          @unless($isAuthenticated)
            <a class="text-slate-500 hover:text-teal-600 transition-colors text-sm" href="{{ $copy['auth_signin_href'] }}">{{ $copy['auth_signin'] }}</a>
          @endunless
        </div>
      </div>
    </div>

    <div class="mt-10 pt-6 border-t border-slate-200/60 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div class="text-slate-500 text-xs">
        © {{ date('Y') }} {{ __('ui.brand.title') }}. {{ $copy['copyright'] }}
      </div>
      <div class="locale-switcher inline-flex items-center gap-2 text-xs font-['Manrope']" data-locale-switcher role="group" aria-label="{{ $copy['lang_aria'] }}">
        <span class="text-slate-400 uppercase tracking-wider mr-1">{{ $copy['col_lang'] }}:</span>
        @foreach(['kk', 'ru', 'en'] as $locale)
          <a
            href="{{ request()->fullUrlWithQuery(['lang' => $locale]) }}"
            class="px-2 py-0.5 rounded-full transition-colors {{ $pageLang === $locale ? 'bg-teal-600 text-white font-semibold' : 'text-slate-500 hover:text-teal-700 hover:bg-white' }}"
            hreflang="{{ $locale }}"
            data-locale="{{ $locale }}"
            @if($pageLang === $locale) aria-current="true" @endif
          >{{ $localeLabels[$locale] }}</a>
        @endforeach
      </div>
    </div>
  </div>
</footer>
