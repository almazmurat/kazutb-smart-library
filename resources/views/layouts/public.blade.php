<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Библиотека КазУТБ')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/shell.css">
  @yield('head')
</head>
<body>

  @include('partials.navbar', ['activePage' => $activePage ?? ''])

  <main>
    @yield('content')
  </main>

  @include('partials.footer')

  @yield('scripts')
</body>
</html>
