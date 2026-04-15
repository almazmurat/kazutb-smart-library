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
@endphp
<footer class="site-footer">
  <div class="container footer-prelude">
    <div class="footer-prelude__intro">
      <div class="footer-kicker">{{ __('ui.brand.title') }}</div>
      <p>{{ __('ui.footer.description') }}</p>
    </div>
    <div class="footer-badge-row" aria-label="{{ __('ui.aria.platform_features') }}">
      <span class="footer-badge">{{ __('ui.footer.badge_catalog') }}</span>
      <span class="footer-badge">{{ __('ui.footer.badge_resources') }}</span>
      <span class="footer-badge">{{ __('ui.footer.badge_portal') }}</span>
    </div>
  </div>

  <div class="container footer-grid">
    <div class="footer-col footer-col--brand">
      <div class="footer-brand">
        <span class="brand-mark brand-mark--logo">
          <img src="{{ asset('logo.png') }}" alt="{{ __('ui.brand.title') }} logo" class="logo-img logo-img--brand" loading="lazy" decoding="async">
        </span>
        <div class="footer-brand-name">
          {{ __('ui.brand.title') }}
          <small>{{ __('ui.brand.subtitle') }}</small>
        </div>
      </div>
      <p>{{ __('ui.footer.description') }}</p>
    </div>

    <div class="footer-col">
      <div class="footer-title">{{ __('ui.footer.explore') }}</div>
      <a href="{{ $routeWithLang('/catalog') }}">{{ __('ui.nav.catalog') }}</a>
      <a href="{{ $routeWithLang('/resources') }}">{{ __('ui.nav.resources') }}</a>
      <a href="{{ $routeWithLang('/discover') }}">{{ __('ui.footer.subjects') }}</a>
      <a href="{{ $routeWithLang('/about') }}">{{ __('ui.nav.about') }}</a>
    </div>

    <div class="footer-col">
      <div class="footer-title">{{ __('ui.footer.portal') }}</div>
      <a href="{{ $routeWithLang('/shortlist') }}">{{ __('ui.footer.shortlist') }}</a>
      @if($isAuthenticated)
        <a href="{{ $routeWithLang('/account') }}">{{ __('ui.nav.account') }}</a>
      @else
        <a href="{{ $routeWithLang('/login') }}">{{ __('ui.footer.secure_access') }}</a>
      @endif
      <a href="{{ $routeWithLang('/account') }}">{{ __('ui.actions.open_portal') }}</a>
    </div>

    <div class="footer-col footer-col--support">
      <div class="footer-title">{{ __('ui.footer.support') }}</div>
      <p>{{ __('ui.footer.support_copy') }}</p>
      <a href="{{ $routeWithLang('/contacts') }}">{{ __('ui.footer.contact_librarian') }}</a>
      <a href="tel:+77172645858">+7 (7172) 64-58-58</a>
      <p class="footer-note">{{ __('ui.footer.hours') }}</p>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>© {{ date('Y') }} {{ __('ui.brand.title') }}. {{ __('ui.footer.copyright') }}</p>
    <div class="footer-bottom-links">
      <a href="{{ $routeWithLang('/') }}">{{ __('ui.nav.home') }}</a>
      <a href="{{ $routeWithLang('/catalog') }}">{{ __('ui.nav.catalog') }}</a>
      <a href="{{ $routeWithLang('/contacts') }}">{{ __('ui.footer.contact_librarian') }}</a>
    </div>
  </div>
</footer>
