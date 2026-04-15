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

  $copy = [
      'ru' => [
          'brand' => 'КазТБУ',
          'desc' => 'Каталог, электронные ресурсы и читательские сервисы в едином интерфейсе КазТБУ.',
          'navigation' => 'Навигация',
          'support' => 'Поддержка',
          'nav_links' => [
              ['label' => 'Главная', 'href' => $routeWithLang('/')],
              ['label' => 'Каталог', 'href' => $routeWithLang('/catalog')],
          ],
          'support_links' => [
              ['label' => 'Подборка', 'href' => $routeWithLang('/shortlist')],
              ['label' => 'Открыть кабинет', 'href' => $routeWithLang('/account')],
          ],
          'contact_link' => 'Связаться с библиотекарем',
          'contact_href' => $routeWithLang('/contacts'),
          'copyright' => 'Институциональный библиотечный интерфейс.',
      ],
      'kk' => [
          'brand' => 'КазТБУ',
          'desc' => 'Каталог, электрондық ресурстар және оқырман сервистері КазТБУ-дың бірыңғай интерфейсінде берілген.',
          'navigation' => 'Навигация',
          'support' => 'Қолдау',
          'nav_links' => [
              ['label' => 'Басты бет', 'href' => $routeWithLang('/')],
              ['label' => 'Каталог', 'href' => $routeWithLang('/catalog')],
          ],
          'support_links' => [
              ['label' => 'Іріктеме', 'href' => $routeWithLang('/shortlist')],
              ['label' => 'Кабинетті ашу', 'href' => $routeWithLang('/account')],
          ],
          'contact_link' => 'Кітапханашымен байланысу',
          'contact_href' => $routeWithLang('/contacts'),
          'copyright' => 'Институционалдық кітапхана интерфейсі.',
      ],
      'en' => [
          'brand' => 'KazUTB',
          'desc' => 'Catalog search, electronic resources, and reader services in one KazTBU library interface.',
          'navigation' => 'Navigation',
          'support' => 'Support',
          'nav_links' => [
              ['label' => 'Home', 'href' => $routeWithLang('/')],
              ['label' => 'Catalog', 'href' => $routeWithLang('/catalog')],
          ],
          'support_links' => [
              ['label' => 'Shortlist', 'href' => $routeWithLang('/shortlist')],
              ['label' => 'Open portal', 'href' => $routeWithLang('/account')],
          ],
          'contact_link' => 'Contact Librarian',
          'contact_href' => $routeWithLang('/contacts'),
          'copyright' => 'Institutional library interface.',
      ],
  ][$pageLang];
@endphp
<footer class="site-footer bg-slate-100 text-blue-950 full-width py-12 px-8 border-t border-slate-200/20">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-screen-2xl mx-auto">
    <div class="space-y-6">
      <div class="inline-flex items-center gap-3 font-['Newsreader'] font-bold text-blue-950 text-2xl">
        <img src="{{ asset('logo.png') }}" alt="{{ __('ui.brand.title') }} logo" class="w-10 h-10 rounded-full object-contain bg-white p-1 shadow-sm" loading="lazy" decoding="async">
        <span>{{ $copy['brand'] }}</span>
      </div>
      <p class="font-['Manrope'] text-sm tracking-wide max-w-sm opacity-80">
        {{ $copy['desc'] }}
      </p>
      <div class="text-slate-500 text-xs">
        © {{ date('Y') }} {{ __('ui.brand.title') }}. {{ $copy['copyright'] }}
      </div>
    </div>
    <div class="grid grid-cols-2 gap-8">
      <div class="flex flex-col space-y-4">
        <h5 class="text-primary font-bold text-sm font-body uppercase tracking-wider">{{ $copy['navigation'] }}</h5>
        @foreach($copy['nav_links'] as $item)
          <a class="text-slate-500 hover:text-teal-600 transition-colors text-sm" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
        @endforeach
      </div>
      <div class="flex flex-col space-y-4">
        <h5 class="text-primary font-bold text-sm font-body uppercase tracking-wider">{{ $copy['support'] }}</h5>
        @foreach($copy['support_links'] as $item)
          <a class="text-slate-500 hover:text-teal-600 transition-colors text-sm" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
        @endforeach
        <a class="text-slate-500 hover:text-teal-600 transition-colors text-sm" href="{{ $copy['contact_href'] }}">{{ $copy['contact_link'] }}</a>
      </div>
    </div>
  </div>
</footer>
