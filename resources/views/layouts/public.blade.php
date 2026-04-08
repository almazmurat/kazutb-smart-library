@php
  $pageLang = request()->query('lang', 'ru');
  $pageLang = in_array($pageLang, ['kk', 'ru', 'en'], true) ? $pageLang : 'ru';
@endphp
<!DOCTYPE html>
<html lang="{{ $pageLang }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Библиотека КазУТБ')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Newsreader:opsz,wght@6..72,400;6..72,500;6..72,700;6..72,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/shell.css">
  @yield('head')
</head>
<body class="{{ trim('site-shell ' . $__env->yieldContent('body_class')) }}">
  <a href="#main-content" class="skip-link">Перейти к основному содержимому</a>

  @include('partials.navbar', ['activePage' => $activePage ?? ''])

  <main id="main-content" class="page-main" tabindex="-1">
    @yield('content')
  </main>

  @include('partials.footer')

  @if(session('library.user'))
  <script>
    document.getElementById('shared-logout-btn')?.addEventListener('click', async () => {
      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        await fetch('/api/v1/logout', {
          method: 'POST',
          headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
        });
      } catch (_) {}
      localStorage.removeItem('library.auth.user');
      window.location.href = '/login';
    });
  </script>
  @endif

  @yield('scripts')
</body>
</html>
