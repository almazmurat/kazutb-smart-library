@php
  $lang = app()->getLocale();
  $accountTitle = [
    'ru' => 'Кабинет читателя — Digital Library',
    'kk' => 'Оқырман кабинеті — Digital Library',
    'en' => 'Reader account — Digital Library',
  ][$lang] ?? 'Кабинет читателя — Digital Library';
  $profileType = $sessionUser['profile_type'] ?? null;
  $routeWithLang = static function (string $path, array $query = []) use ($lang): string {
    $normalizedPath = '/' . ltrim($path, '/');

    if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
      $query['lang'] = $lang;
    }

    $query = array_filter($query, static fn ($value) => $value !== null && $value !== '');

    return $normalizedPath . ($query ? ('?' . http_build_query($query)) : '');
  };
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ $accountTitle }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Newsreader:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/shell.css">
  <style>
    :root {
      --bg: #f8f9fa;
      --surface: #ffffff;
      --border: rgba(195, 198, 209, .55);
      --text: #191c1d;
      --muted: #43474f;
      --blue: #001e40;
      --cyan: #14696d;
      --success: #14696d;
      --warning: #5d4201;
      --shadow: 0 18px 48px rgba(25, 28, 29, .05);
      --shadow-soft: 0 10px 28px rgba(25, 28, 29, .035);
      --radius-xl: 8px;
      --radius-lg: 6px;
      --radius-md: 4px;
      --container: 1360px;
    }

    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Manrope', system-ui, sans-serif;
      color: var(--text);
      background: #f8f9fa;
    }

    a { color: inherit; text-decoration: none; }
    .container { width: min(100% - 32px, var(--container)); margin: 0 auto; }

    .topbar {
      position: sticky;
      top: 0;
      z-index: 40;
      background: rgba(255,255,255,.82);
      backdrop-filter: blur(18px);
      border-bottom: 1px solid var(--border);
    }

    .nav {
      min-height: 84px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 18px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 14px;
      font-weight: 900;
      letter-spacing: -.3px;
      cursor: pointer;
    }

    .brand small {
      display: block;
      color: var(--muted);
      margin-top: 3px;
      font-weight: 500;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 24px;
      font-weight: 600;
      color: #334155;
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--blue);
    }

    .nav-actions { display: flex; gap: 12px; }

    .btn {
      border: 0;
      cursor: pointer;
      font: inherit;
      border-radius: 6px;
      padding: 12px 18px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .2s ease, color .2s ease, border-color .2s ease, box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1);
      font-weight: 700;
      font-size: 13px;
    }

    .btn:hover { transform: translate3d(0, -1px, 0); }
    .btn-primary { color: white; background: linear-gradient(135deg, var(--blue), #003366); box-shadow: var(--shadow-soft); }
    .btn-ghost { background: transparent; border: 1px solid var(--border); color: var(--text); box-shadow: none; }

    .page { padding: 34px 0 70px; }

    .profile-grid {
      display: grid;
      grid-template-columns: 1.05fr .95fr;
      gap: 24px;
      margin-bottom: 24px;
    }

    .card {
      border-radius: var(--radius-xl);
      background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      padding: 28px;
      position: relative;
      overflow: hidden;
      transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .card:hover {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 16px 34px rgba(25, 28, 29, .05);
      border-color: rgba(0,30,64,.12);
    }

    .profile-head {
      display: flex;
      align-items: center;
      gap: 18px;
      margin-bottom: 20px;
    }

    .avatar {
      width: 72px;
      height: 72px;
      border-radius: 8px;
      display: grid;
      place-items: center;
      font-size: 28px;
      font-weight: 800;
      color: white;
      background: linear-gradient(140deg, #17354d, #2f6966);
      box-shadow: 0 16px 30px rgba(23,53,77,.18);
    }

    .profile-name {
      margin: 0;
      font-size: 32px;
      letter-spacing: -.03em;
      font-family: 'Newsreader', Georgia, serif;
      font-weight: 600;
      color: var(--blue);
    }

    .profile-sub {
      margin: 6px 0 0;
      color: var(--muted);
      font-size: 14px;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }

    .stat {
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      background: #fff;
      padding: 16px;
      box-shadow: none;
      transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .stat:hover {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 12px 26px rgba(25, 28, 29, .04);
      border-color: rgba(20,105,109,.18);
    }

    .stat strong {
      display: block;
      font-size: 28px;
      margin-bottom: 6px;
    }

    .stat span {
      font-size: 13px;
      color: var(--muted);
    }

    .alerts {
      display: grid;
      gap: 12px;
      align-content: start;
    }

    .status-stack {
      display: grid;
      gap: 16px;
      align-content: start;
    }

    .status-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 10px;
    }

    .status-tile {
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      background: #fff;
      padding: 14px;
    }

    .status-label {
      display: block;
      font-size: 11px;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: 6px;
      font-weight: 700;
    }

    .status-value {
      font-size: 14px;
      font-weight: 700;
      color: var(--blue);
      line-height: 1.4;
    }

    .detail-list {
      display: grid;
      gap: 10px;
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .detail-item {
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      background: rgba(255,255,255,.88);
      padding: 14px 16px;
    }

    .detail-item strong {
      display: block;
      margin-bottom: 4px;
      font-size: 12px;
      color: var(--muted);
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .08em;
    }

    .detail-item span {
      display: block;
      font-size: 14px;
      line-height: 1.5;
      color: var(--text);
    }

    .dashboard-main {
      display: grid;
      grid-template-columns: minmax(0, 1.25fr) minmax(320px, .75fr);
      gap: 24px;
      align-items: start;
    }

    .side-stack {
      display: grid;
      gap: 24px;
    }

    .activity-list {
      display: grid;
      gap: 12px;
    }

    .activity-item {
      display: grid;
      gap: 6px;
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      background: #fff;
      padding: 14px 16px;
    }

    .activity-item strong {
      font-size: 14px;
      color: var(--blue);
    }

    .activity-item span {
      font-size: 13px;
      color: var(--muted);
      line-height: 1.5;
    }

    .quick-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 14px;
    }

    .quick-card {
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      background: #fff;
      padding: 18px;
      transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
      display: grid;
      gap: 8px;
    }

    .quick-card:hover {
      transform: translate3d(0, -2px, 0);
      border-color: rgba(20,105,109,.22);
      box-shadow: 0 12px 26px rgba(25, 28, 29, .04);
    }

    .quick-card strong {
      color: var(--blue);
      font-size: 16px;
    }

    .quick-card span {
      color: var(--muted);
      font-size: 13px;
      line-height: 1.5;
    }

    .identity-actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 18px;
    }

    .alert {
      border-radius: var(--radius-lg);
      padding: 14px 16px;
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: none;
      font-size: 14px;
      line-height: 1.65;
    }

    .alert.warning { border-color: rgba(93,66,1,.18); background: rgba(93,66,1,.06); color: #5d4201; }
    .alert.success { border-color: rgba(20,105,109,.18); background: rgba(20,105,109,.06); color: #14696d; }

    .section-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 14px;
    }

    .section-head h2 {
      margin: 0;
      font-size: 28px;
      letter-spacing: -.03em;
      font-family: 'Newsreader', Georgia, serif;
      font-weight: 600;
      color: var(--blue);
    }

    .section-head p {
      margin: 0;
      color: var(--muted);
      font-size: 14px;
    }

    .showcase {
      border-radius: var(--radius-xl);
      background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(247,248,249,.96));
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      padding: 24px;
      transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .showcase:hover {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 16px 34px rgba(25, 28, 29, .05);
      border-color: rgba(0,30,64,.12);
    }

    .book-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 16px;
    }

    .showcase .book-card:nth-child(3n + 2) {
      transform: translate3d(0, 10px, 0);
    }

    .showcase .book-card:nth-child(3n + 2):hover {
      transform: translate3d(0, 4px, 0) rotateX(0.5deg);
    }

    .book-card {
      padding: 16px;
      border-radius: var(--radius-lg);
      background: #fff;
      border: 1px solid var(--border);
      box-shadow: none;
      transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), background 0.2s ease, border-color 0.2s ease, box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1);
      cursor: pointer;
      transform-style: preserve-3d;
    }

    .book-card:hover {
      transform: translate3d(0, -3px, 0) rotateX(0.5deg);
      background: rgba(243,244,245,.96);
      border-color: rgba(20,105,109,.22);
      box-shadow: 0 14px 28px rgba(25, 28, 29, .05);
    }

    .book-preview {
      height: 190px;
      border-radius: var(--radius-lg);
      padding: 14px;
      display: flex;
      align-items: flex-end;
      position: relative;
      overflow: hidden;
      margin-bottom: 12px;
      background: linear-gradient(180deg, #003366 0%, #001e40 100%);
    }

    .book-preview::before {
      content: "";
      position: absolute;
      inset: 0 auto 0 0;
      width: 6px;
      background: rgba(255,255,255,.12);
    }

    .book-preview small {
      position: absolute;
      left: 14px;
      top: 14px;
      color: rgba(255,255,255,.58);
      font-size: 10px;
      letter-spacing: .16em;
      text-transform: uppercase;
      font-weight: 700;
    }

    .book-preview h3 {
      position: relative;
      z-index: 1;
      margin: 0;
      color: #f1d08e;
      font-size: 17px;
      line-height: 1.06;
      letter-spacing: -.4px;
      font-weight: 700;
    }

    .book-grid .book-card:nth-child(2) .book-preview,
    .book-grid .book-card:nth-child(5) .book-preview {
      background: linear-gradient(180deg, #8f1f1f 0%, #6d1111 100%);
    }

    .book-grid .book-card:nth-child(3) .book-preview,
    .book-grid .book-card:nth-child(6) .book-preview {
      background: linear-gradient(180deg, #205f43 0%, #134935 100%);
    }

    .book-title {
      font-size: 16px;
      font-weight: 600;
      font-family: 'Newsreader', Georgia, serif;
      margin: 0 0 5px;
      line-height: 1.3;
      color: var(--blue);
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .book-meta {
      color: var(--muted);
      font-size: 12px;
      line-height: 1.4;
    }

    .loading {
      position: relative;
      border-radius: 12px;
      border: 1px dashed rgba(195,198,209,.72);
      background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
      padding: 24px;
      text-align: center;
      color: var(--muted);
      box-shadow: var(--shadow-soft);
      overflow: hidden;
    }

    .empty-state {
      min-height: 240px;
      display: grid;
      place-items: center;
      text-align: center;
      gap: 14px;
      padding: 30px 26px;
      border-radius: 12px;
      border: 1px dashed rgba(195,198,209,.66);
      background: linear-gradient(180deg, rgba(255,255,255,.985), rgba(244,245,246,.92));
      box-shadow: var(--shadow-soft);
    }

    .empty-state--compact {
      min-height: 208px;
      padding: 24px 20px;
    }

    .empty-state-icon {
      display: inline-grid;
      place-items: center;
      width: 56px;
      height: 56px;
      border-radius: 999px;
      background: rgba(243,244,245,.92);
      border: 1px solid rgba(195,198,209,.44);
      color: var(--blue);
      font-size: 24px;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.75);
    }

    .empty-state-title {
      margin: 0;
      font-family: 'Newsreader', Georgia, serif;
      font-size: 28px;
      line-height: 1;
      letter-spacing: -.04em;
      color: var(--blue);
    }

    .empty-state-body {
      margin: 0;
      max-width: 520px;
      font-size: 13px;
      line-height: 1.75;
      color: var(--muted);
    }

    .empty-state-actions {
      display: flex;
      gap: 10px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 2px;
    }

    .empty-state-actions a,
    .empty-state-actions button {
      min-height: 40px;
    }

    .workspace-balance-note {
      padding: 16px 18px;
      border-radius: 8px;
      border: 1px solid rgba(195,198,209,.42);
      background: rgba(243,244,245,.58);
      font-size: 12px;
      line-height: 1.7;
      color: var(--muted);
    }

    .workspace-balance-note strong {
      color: var(--blue);
    }

    .loading::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,.45) 45%, transparent 100%);
      transform: translateX(-120%);
      animation: loadingSweep 2.8s linear infinite;
      pointer-events: none;
      opacity: .75;
    }

    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes loadingSweep { to { transform: translateX(120%); } }
    .spinner {
      display: inline-block;
      width: 32px; height: 32px;
      border: 3px solid #e5e7eb;
      border-top-color: var(--blue);
      border-radius: 50%;
      animation: spin .7s linear infinite;
    }

    .toast {
      position: fixed;
      top: 92px;
      right: 24px;
      z-index: 1000;
      min-width: 320px;
      max-width: 440px;
      padding: 14px 16px;
      border-radius: 8px;
      background: #fff;
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
      display: flex;
      align-items: flex-start;
      gap: 12px;
      transform: translateX(120%);
      transition: transform .35s cubic-bezier(.4,0,.2,1);
      font-size: 14px;
      line-height: 1.6;
    }
    .toast.visible { transform: translateX(0); }
    .toast.success { border-color: rgba(20,105,109,.22); background: rgba(20,105,109,.06); }
    .toast.error { border-color: rgba(186,26,26,.22); background: rgba(186,26,26,.06); }
    .toast-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
    .toast-body { flex: 1; }
    .toast-title { font-weight: 700; margin-bottom: 2px; }
    .toast-close {
      background: none; border: none; cursor: pointer;
      font-size: 18px; color: var(--muted); padding: 0; line-height: 1;
    }

    .modal-overlay {
      position: fixed; inset: 0; z-index: 900;
      background: rgba(25,28,29,.18);
      backdrop-filter: blur(8px);
      display: flex; align-items: center; justify-content: center;
      opacity: 0; pointer-events: none;
      transition: opacity .25s ease;
    }
    .modal-overlay.visible { opacity: 1; pointer-events: auto; }
    .modal-box {
      background: #fff;
      border-radius: 8px;
      padding: 28px;
      max-width: 420px;
      width: 90%;
      box-shadow: var(--shadow);
      text-align: center;
      border: 1px solid var(--border);
    }
    .modal-box h3 { margin: 0 0 8px; font-size: 20px; }
    .modal-box p { margin: 0 0 24px; color: var(--muted); font-size: 15px; }
    .modal-actions { display: flex; gap: 12px; justify-content: center; }
    .modal-actions .btn { min-width: 120px; padding: 12px 20px; font-size: 14px; }

    @media (max-width: 1200px) {
      .profile-grid { grid-template-columns: 1fr; }
      .dashboard-main { grid-template-columns: 1fr; }
      .book-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .stats { grid-template-columns: repeat(2, 1fr); }
      .quick-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .showcase .book-card:nth-child(3n + 2) { transform: none; }
    }

    @media (max-width: 900px) {
      .nav-links { display: none; }
      .stats { grid-template-columns: 1fr; }
      .status-grid,
      .detail-list,
      .quick-grid { grid-template-columns: 1fr; }
      .mobile-toggle { display: inline-grid; place-items: center; min-width: 44px; min-height: 44px; }
    }

    @media (max-width: 680px) {
      .container { width: min(100% - 20px, var(--container)); }
      .card,
      .showcase { padding: 20px; }
      .book-grid { grid-template-columns: 1fr; }
      .profile-name { font-size: 24px; }
      .nav-actions .btn { padding: 12px 16px; min-height: 44px; }
      .nav { min-height: 64px; }
    }

    @media (max-width: 480px) {
      .container { width: min(100% - 16px, var(--container)); }
      .card,
      .showcase { padding: 16px; }
      .profile-name { font-size: 20px; }
      .brand-text { font-size: 13px; }
      .brand-text small { font-size: 10.5px; }
    }

    .page {
      padding: 42px 0 82px;
    }

    .account-workspace {
      display: grid;
      grid-template-columns: 240px minmax(0, 1fr);
      gap: 32px;
      align-items: start;
    }

    .account-sidebar {
      position: sticky;
      top: 108px;
      align-self: start;
      display: grid;
      gap: 22px;
      padding: 28px 18px 22px;
      border-radius: 8px;
      background: linear-gradient(180deg, rgba(244,245,246,.98), rgba(238,239,240,.9));
      border: 1px solid rgba(195, 198, 209, .48);
      box-shadow: var(--shadow-soft);
      min-height: calc(100vh - 160px);
    }

    .sidebar-branding {
      display: grid;
      gap: 7px;
      padding: 2px 10px 18px;
      border-bottom: 1px solid rgba(195,198,209,.42);
    }

    .sidebar-branding strong {
      font-family: 'Newsreader', Georgia, serif;
      font-size: 29px;
      line-height: 1;
      color: var(--blue);
      letter-spacing: -.03em;
    }

    .sidebar-branding span,
    .sidebar-branding small {
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--cyan);
    }

    .sidebar-member-card {
      display: grid;
      gap: 12px;
      padding: 18px 18px 16px;
      border-radius: 8px;
      background: rgba(255,255,255,.74);
      border: 1px solid rgba(195,198,209,.44);
    }

    .sidebar-member-card .avatar {
      width: 52px;
      height: 52px;
      font-size: 19px;
      box-shadow: none;
    }

    .sidebar-member-card p {
      margin: 0;
    }

    .sidebar-member-label {
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: var(--cyan);
    }

    .sidebar-profile-name {
      margin: 2px 0 0;
      font-size: 20px;
      line-height: 1.04;
      letter-spacing: -.03em;
      font-family: 'Newsreader', Georgia, serif;
      color: var(--blue);
    }

    .sidebar-profile-sub {
      margin-top: 6px;
      color: var(--muted);
      font-size: 12px;
      line-height: 1.5;
    }

    .sidebar-nav {
      display: grid;
      gap: 8px;
    }

    .sidebar-nav a,
    .sidebar-nav button {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      width: 100%;
      padding: 12px 14px;
      border-radius: 6px;
      border: 1px solid rgba(195,198,209,0);
      background: transparent;
      color: var(--muted);
      font-size: 13px;
      font-weight: 700;
      text-align: left;
      transition: background .2s ease, border-color .2s ease, color .2s ease, transform .18s ease;
    }

    .sidebar-nav a:hover,
    .sidebar-nav button:hover {
      transform: translate3d(1px, 0, 0);
      background: rgba(255,255,255,.78);
      border-color: rgba(195,198,209,.46);
      color: var(--blue);
    }

    .sidebar-nav a.active {
      color: var(--cyan);
      background: rgba(255,255,255,.9);
      border-color: rgba(20,105,109,.14);
      box-shadow: inset -2px 0 0 var(--cyan);
    }

    .sidebar-nav-label {
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }

    .sidebar-icon {
      display: inline-grid;
      place-items: center;
      width: 22px;
      height: 22px;
      border-radius: 4px;
      background: rgba(0,30,64,.05);
      color: var(--blue);
      font-size: 12px;
      font-weight: 900;
    }

    .sidebar-meta {
      margin-top: auto;
      display: grid;
      gap: 14px;
      padding: 18px 10px 0;
      border-top: 1px solid rgba(195,198,209,.42);
    }

    .sidebar-meta p {
      margin: 0;
      font-size: 12px;
      line-height: 1.65;
      color: var(--muted);
    }

    .workspace-canvas {
      min-width: 0;
      display: grid;
      gap: 28px;
    }

    .workspace-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
      padding: 10px 4px 18px;
    }

    .workspace-kicker {
      display: inline-block;
      margin-bottom: 8px;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--cyan);
    }

    .workspace-title {
      margin: 0;
      font-family: 'Newsreader', Georgia, serif;
      font-size: 50px;
      line-height: .98;
      letter-spacing: -.05em;
      color: var(--blue);
    }

    .workspace-subtitle {
      margin: 14px 0 0;
      font-size: 13px;
      color: var(--muted);
      line-height: 1.75;
      max-width: 660px;
    }

    .workspace-header-tools {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .workspace-icon-btn {
      display: inline-grid;
      place-items: center;
      width: 40px;
      height: 40px;
      border-radius: 999px;
      border: 1px solid rgba(195,198,209,.48);
      background: rgba(255,255,255,.84);
      color: var(--blue);
      font-size: 15px;
      box-shadow: var(--shadow-soft);
    }

    .workspace-profile-chip {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 194px;
      padding: 7px 12px 7px 8px;
      border-radius: 999px;
      background: rgba(243,244,245,.9);
      border: 1px solid rgba(195,198,209,.48);
      box-shadow: var(--shadow-soft);
    }

    .workspace-profile-chip .avatar,
    .profile-chip-avatar {
      width: 34px;
      height: 34px;
      font-size: 14px;
      border-radius: 999px;
      box-shadow: none;
    }

    .workspace-profile-chip strong {
      display: block;
      font-size: 13px;
      color: var(--blue);
    }

    .workspace-profile-chip span {
      display: block;
      font-size: 11px;
      color: var(--muted);
      margin-top: 2px;
    }

    .workspace-shell {
      display: grid;
      grid-template-columns: minmax(0, 1.65fr) minmax(300px, .8fr);
      gap: 28px;
      align-items: start;
    }

    .workspace-main {
      min-width: 0;
      display: flex;
      flex-direction: column;
      gap: 28px;
    }

    .metric-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 22px;
    }

    .metric-card {
      position: relative;
      overflow: hidden;
      min-height: 168px;
      padding: 28px 28px 24px;
      border-radius: 8px;
      border: 1px solid rgba(195,198,209,.42);
      background: linear-gradient(180deg, rgba(244,245,246,.96), rgba(239,240,241,.84));
      box-shadow: var(--shadow-soft);
    }

    .metric-card::after {
      content: attr(data-icon);
      position: absolute;
      top: 22px;
      right: 22px;
      font-size: 56px;
      line-height: 1;
      opacity: .06;
    }

    .metric-card--accent {
      background: linear-gradient(180deg, rgba(255,255,255,.99), rgba(251,247,239,.95));
    }

    .metric-label {
      display: inline-block;
      margin-bottom: 14px;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--cyan);
    }

    .metric-value {
      margin: 0;
      font-family: 'Newsreader', Georgia, serif;
      font-size: 52px;
      line-height: .92;
      letter-spacing: -.06em;
      color: var(--blue);
    }

    .metric-caption {
      margin: 8px 0 0;
      font-size: 13px;
      color: var(--muted);
    }

    .metric-footnote {
      margin-top: 24px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .02em;
      color: var(--muted);
    }

    .metric-footnote.warn { color: #a16207; }
    .metric-footnote.good { color: var(--success); }

    .workspace-panel,
    .rail-panel,
    .rail-note {
      border-radius: 8px;
      border: 1px solid rgba(195,198,209,.42);
      background: linear-gradient(180deg, rgba(255,255,255,.985), rgba(248,249,250,.955));
      box-shadow: var(--shadow-soft);
      padding: 28px;
    }

    .workspace-panel--loans {
      min-height: 460px;
      display: grid;
      align-content: start;
    }

    .workspace-panel--activity {
      min-height: 300px;
      display: grid;
      align-content: start;
    }

    .rail-panel--waitlist {
      min-height: 320px;
      display: grid;
      align-content: start;
    }

    .rail-panel--shortlist {
      min-height: 290px;
      display: grid;
      align-content: start;
    }

    .panel-head {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 22px;
    }

    .panel-head h2,
    .panel-head h3 {
      margin: 0;
      font-family: 'Newsreader', Georgia, serif;
      font-size: 32px;
      line-height: .98;
      letter-spacing: -.045em;
      color: var(--blue);
    }

    .panel-head p {
      margin: 8px 0 0;
      font-size: 13px;
      color: var(--muted);
      line-height: 1.7;
    }

    .panel-link {
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: var(--cyan);
      white-space: nowrap;
    }

    .loan-list,
    .reservation-list,
    .activity-list,
    .quick-action-list,
    .rail-stat-list {
      display: grid;
      gap: 12px;
      align-content: start;
    }

    #book-grid {
      min-height: 356px;
    }

    #activity-list {
      min-height: 214px;
    }

    #reservations-grid {
      min-height: 236px;
    }

    .loan-row {
      display: grid;
      grid-template-columns: 72px minmax(0, 1fr) auto auto;
      gap: 20px;
      align-items: center;
      padding: 22px 22px 20px;
      border-radius: 8px;
      background: rgba(255,255,255,.94);
      border: 1px solid rgba(195,198,209,.42);
      transition: background .2s ease, border-color .2s ease, transform .18s ease;
    }

    .loan-row:hover,
    .reservation-row:hover,
    .quick-action:hover {
      transform: translate3d(0, -2px, 0);
      border-color: rgba(20,105,109,.2);
      background: #fff;
    }

    .loan-cover,
    .reservation-cover {
      position: relative;
      width: 54px;
      height: 84px;
      border-radius: 4px;
      overflow: hidden;
      background: linear-gradient(180deg, #003366 0%, #001e40 100%);
      color: #f1d08e;
      padding: 10px;
      display: flex;
      align-items: flex-end;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.08), 0 12px 24px rgba(0,30,64,.06);
    }

    .loan-cover::before,
    .reservation-cover::before {
      content: '';
      position: absolute;
      inset: 0 auto 0 0;
      width: 5px;
      background: rgba(255,255,255,.12);
    }

    .loan-cover small,
    .reservation-cover small {
      position: absolute;
      left: 10px;
      top: 10px;
      font-size: 9px;
      font-weight: 800;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: rgba(255,255,255,.55);
    }

    .loan-cover span,
    .reservation-cover span {
      position: relative;
      z-index: 1;
      font-family: 'Newsreader', Georgia, serif;
      font-size: 13px;
      line-height: 1.05;
      font-weight: 700;
    }

    .loan-meta,
    .reservation-meta {
      min-width: 0;
      display: grid;
      gap: 7px;
    }

    .loan-kicker,
    .reservation-kicker {
      font-size: 9px;
      font-weight: 800;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: #8e9098;
    }

    .loan-title,
    .reservation-title {
      margin: 0;
      font-family: 'Newsreader', Georgia, serif;
      font-size: 23px;
      line-height: 1.02;
      letter-spacing: -.04em;
      color: var(--blue);
    }

    .loan-author,
    .reservation-copy {
      font-size: 13px;
      color: var(--muted);
      line-height: 1.6;
    }

    .loan-facts,
    .reservation-facts {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .loan-facts span,
    .reservation-facts span {
      display: inline-flex;
      align-items: center;
      min-height: 26px;
      padding: 4px 9px;
      border-radius: 999px;
      background: rgba(243,244,245,.8);
      border: 1px solid rgba(195,198,209,.4);
      color: var(--muted);
      font-size: 10px;
      font-weight: 700;
    }

    .loan-due,
    .reservation-status {
      min-width: 126px;
      text-align: right;
      display: grid;
      gap: 8px;
    }

    .loan-due-label,
    .reservation-status-label {
      font-size: 9px;
      font-weight: 800;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: var(--cyan);
    }

    .loan-due-label.warn { color: #ba1a1a; }
    .loan-due-label.soon { color: #a16207; }

    .loan-due-date,
    .reservation-status-date {
      font-size: 13px;
      font-weight: 700;
      color: var(--blue);
    }

    .loan-actions {
      display: grid;
      gap: 10px;
      justify-items: end;
      min-width: 158px;
    }

    .loan-actions .btn,
    .reservation-row .btn {
      min-height: 40px;
      padding: 9px 14px;
      font-size: 11px;
    }

    .reservation-row {
      display: grid;
      grid-template-columns: 52px minmax(0, 1fr);
      gap: 16px;
      align-items: start;
      padding: 18px;
      border-radius: 8px;
      background: rgba(255,255,255,.94);
      border: 1px solid rgba(195,198,209,.42);
      transition: background .2s ease, border-color .2s ease, transform .18s ease;
    }

    .reservation-cover {
      width: 52px;
      height: 76px;
    }

    .reservation-topline {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 10px;
    }

    .reservation-actions {
      margin-top: 10px;
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .activity-item {
      position: relative;
      display: grid;
      grid-template-columns: 36px minmax(0, 1fr);
      column-gap: 14px;
      row-gap: 3px;
      align-items: start;
      padding: 18px 18px 18px 20px;
      border-radius: 8px;
      border: 1px solid rgba(195,198,209,.42);
      background: rgba(247,248,249,.78);
    }

    .activity-item::before {
      content: '';
      position: absolute;
      left: 0;
      top: 12px;
      bottom: 12px;
      width: 3px;
      border-radius: 8px 0 0 8px;
      background: rgba(20,105,109,.24);
    }

    .activity-icon {
      display: inline-grid;
      place-items: center;
      width: 30px;
      height: 30px;
      border-radius: 999px;
      background: rgba(255,255,255,.9);
      border: 1px solid rgba(195,198,209,.42);
      font-size: 14px;
      color: var(--cyan);
      margin-top: 1px;
    }

    .activity-copy {
      display: grid;
      gap: 5px;
      min-width: 0;
    }

    .activity-item strong {
      font-size: 14px;
      color: var(--blue);
    }

    .activity-body {
      font-size: 12px;
      color: var(--text);
      line-height: 1.7;
    }

    .activity-date {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: #8e9098;
    }

    .workspace-rail {
      min-width: 0;
      display: grid;
      gap: 22px;
      align-content: start;
    }

    .rail-panel h4,
    .rail-note h4 {
      margin: 0 0 16px;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: var(--cyan);
    }

    .quick-action {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      padding: 16px 17px;
      border-radius: 8px;
      background: rgba(255,255,255,.9);
      border: 1px solid rgba(195,198,209,.4);
      transition: background .2s ease, border-color .2s ease, transform .18s ease;
    }

    .quick-action strong {
      display: block;
      font-size: 13px;
      color: var(--blue);
      margin-bottom: 5px;
    }

    .quick-action span {
      font-size: 11px;
      color: var(--muted);
      line-height: 1.7;
    }

    .quick-action-arrow {
      font-size: 16px;
      color: #8e9098;
    }

    .rail-note {
      background: linear-gradient(180deg, rgba(0,30,64,.96), rgba(8,37,67,.94));
      border-color: rgba(0,30,64,.18);
      color: #eff6ff;
      padding: 30px 28px;
    }

    .rail-note h4 {
      color: rgba(247,189,72,.92);
    }

    .rail-note .alert {
      background: rgba(255,255,255,.07);
      border-color: rgba(255,255,255,.1);
      color: rgba(255,255,255,.9);
      line-height: 1.8;
    }

    .rail-note .detail-list {
      grid-template-columns: 1fr;
      gap: 12px;
      margin-top: 18px;
    }

    .rail-note .detail-item {
      background: rgba(255,255,255,.06);
      border-color: rgba(255,255,255,.08);
    }

    .rail-note .detail-item strong {
      color: rgba(255,255,255,.58);
    }

    .rail-note .detail-item span {
      color: rgba(255,255,255,.96);
    }

    .rail-shortlist-stats {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 12px;
      margin-bottom: 18px;
    }

    .rail-stat {
      padding: 16px 12px;
      border-radius: 6px;
      background: rgba(243,244,245,.66);
      border: 1px solid rgba(195,198,209,.38);
    }

    .rail-stat strong {
      display: block;
      font-size: 28px;
      line-height: 1;
      color: var(--blue);
      margin-bottom: 6px;
      font-family: 'Newsreader', Georgia, serif;
    }

    .rail-stat span {
      display: block;
      font-size: 10px;
      color: var(--muted);
      line-height: 1.55;
    }

    .workspace-footer-note {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
      padding: 18px 4px 0;
      border-top: 1px solid rgba(195,198,209,.34);
      color: var(--muted);
      font-size: 12px;
      line-height: 1.7;
    }

    .workspace-footer-note strong {
      color: var(--blue);
    }

    @media (max-width: 1260px) {
      .account-workspace {
        grid-template-columns: 220px minmax(0, 1fr);
      }

      .workspace-shell {
        grid-template-columns: 1fr;
      }

      .workspace-rail {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .rail-note,
      #workbench-section {
        grid-column: 1 / -1;
      }
    }

    @media (max-width: 1080px) {
      .account-workspace {
        grid-template-columns: 1fr;
      }

      .account-sidebar {
        position: static;
        min-height: 0;
      }

      .sidebar-nav {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .metric-grid {
        grid-template-columns: 1fr;
      }

      .workspace-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .workspace-profile-chip {
        min-width: 0;
      }

      .loan-row {
        grid-template-columns: 64px minmax(0, 1fr);
      }

      .workspace-panel--loans,
      .workspace-panel--activity,
      .rail-panel--waitlist,
      .rail-panel--shortlist,
      #book-grid,
      #activity-list,
      #reservations-grid,
      .empty-state,
      .empty-state--compact {
        min-height: 0;
      }

      .loan-due,
      .loan-actions {
        grid-column: 2;
        text-align: left;
        justify-items: start;
      }
    }

    @media (max-width: 820px) {
      .sidebar-nav,
      .workspace-rail,
      .rail-shortlist-stats {
        grid-template-columns: 1fr;
      }

      .workspace-title {
        font-size: 34px;
      }

      .panel-head h2,
      .panel-head h3,
      .loan-title,
      .reservation-title {
        font-size: 24px;
      }

      .loan-row,
      .reservation-row {
        padding: 16px;
      }

      .empty-state-actions {
        flex-direction: column;
      }
    }
  </style>
</head>
<body class="site-shell">
  @include('partials.navbar', ['activePage' => 'account'])

  <main class="page">
    <div class="container">
      <section class="account-workspace" data-account-dashboard-shell>
        <aside class="account-sidebar">
          <div class="sidebar-branding">
            <small>{{ ['ru' => 'Academic Resource Center', 'kk' => 'Academic Resource Center', 'en' => 'Academic Resource Center'][$lang] }}</small>
            <strong>{{ ['ru' => 'КазТБУ Library', 'kk' => 'КазТБУ Library', 'en' => 'KazUTB Library'][$lang] }}</strong>
            <span>{{ ['ru' => 'Reader Workspace', 'kk' => 'Reader Workspace', 'en' => 'Reader Workspace'][$lang] }}</span>
          </div>

          @php
            $profileType = $sessionUser['profile_type'] ?? null;
            $role = $sessionUser['role'] ?? 'reader';
            $roleLabel = match(true) {
              $profileType === 'teacher' => ['ru' => 'Преподаватель', 'kk' => 'Оқытушы', 'en' => 'Faculty'][$lang],
              $profileType === 'student' => ['ru' => 'Студент', 'kk' => 'Студент', 'en' => 'Student'][$lang],
              $role === 'librarian' => ['ru' => 'Библиотекарь', 'kk' => 'Кітапханашы', 'en' => 'Librarian'][$lang],
              $role === 'admin' => ['ru' => 'Администратор', 'kk' => 'Әкімші', 'en' => 'Administrator'][$lang],
              default => ['ru' => 'Читатель', 'kk' => 'Оқырман', 'en' => 'Reader'][$lang],
            };
          @endphp

          <div class="sidebar-member-card">
            <div id="profile-avatar" class="avatar">{{ strtoupper(substr($sessionUser['name'] ?? 'U', 0, 1)) }}</div>
            <div>
              <p class="sidebar-member-label">{{ ['ru' => 'Member access', 'kk' => 'Member access', 'en' => 'Member access'][$lang] }}</p>
              <h1 id="profile-name" class="sidebar-profile-name">{{ $sessionUser['name'] ?? ['ru' => 'Гость библиотеки', 'kk' => 'Кітапхана қонағы', 'en' => 'Library guest'][$lang] }}</h1>
              <p id="profile-sub" class="sidebar-profile-sub">{{ $sessionUser['ad_login'] ?? ['ru' => 'не указан', 'kk' => 'көрсетілмеген', 'en' => 'not specified'][$lang] }} · {{ $roleLabel }}</p>
            </div>
          </div>

          <nav class="sidebar-nav" aria-label="Account workspace navigation">
            <a href="{{ $routeWithLang('/account') }}" class="active">
              <span class="sidebar-nav-label"><span class="sidebar-icon">▣</span>{{ ['ru' => 'Кабинет', 'kk' => 'Кабинет', 'en' => 'Dashboard'][$lang] }}</span>
            </a>
            <a href="{{ $routeWithLang('/catalog') }}">
              <span class="sidebar-nav-label"><span class="sidebar-icon">⌕</span>{{ ['ru' => 'Каталог', 'kk' => 'Каталог', 'en' => 'Catalog'][$lang] }}</span>
            </a>
            <a href="{{ $routeWithLang('/resources') }}">
              <span class="sidebar-nav-label"><span class="sidebar-icon">▤</span>{{ ['ru' => 'Ресурсы', 'kk' => 'Ресурстар', 'en' => 'Resources'][$lang] }}</span>
            </a>
            <a href="{{ $routeWithLang('/discover') }}">
              <span class="sidebar-nav-label"><span class="sidebar-icon">↗</span>{{ ['ru' => 'Навигация', 'kk' => 'Навигация', 'en' => 'Discover'][$lang] }}</span>
            </a>
            <a href="{{ $routeWithLang('/shortlist') }}">
              <span class="sidebar-nav-label"><span class="sidebar-icon">☰</span>{{ ['ru' => 'Подборка', 'kk' => 'Топтама', 'en' => 'Shortlist'][$lang] }}</span>
            </a>
          </nav>

          <div class="sidebar-meta">
            <p>{{ ['ru' => 'Рабочая зона читателя использует реальные account, loans, reservations и shortlist данные библиотеки.', 'kk' => 'Оқырманның жұмыс аймағы кітапхананың нақты account, loans, reservations және shortlist деректерін қолданады.', 'en' => 'The reader workspace uses live library account, loans, reservations, and shortlist data.'][$lang] }}</p>
            <button id="logout-btn" class="btn btn-primary" type="button">{{ ['ru' => 'Выйти из сессии', 'kk' => 'Сессиядан шығу', 'en' => 'Sign out'][$lang] }}</button>
          </div>
        </aside>

        <div class="workspace-canvas">
          <header class="workspace-header">
            <div>
              <span class="workspace-kicker">{{ ['ru' => 'Member Access', 'kk' => 'Member Access', 'en' => 'Member Access'][$lang] }}</span>
              <h1 class="workspace-title">{{ ['ru' => 'Member Dashboard', 'kk' => 'Member Dashboard', 'en' => 'Member Dashboard'][$lang] }}</h1>
              <p class="workspace-subtitle">{{ ['ru' => 'Кабинет читателя в формате dashboard workspace: реальные выдачи, бронирования, подборка и текущий статус доступа внутри библиотечной платформы.', 'kk' => 'Dashboard workspace форматындағы оқырман кабинеті: нақты берілімдер, броньдар, топтама және кітапхана платформасындағы ағымдағы қолжетімділік күйі.', 'en' => 'Reader account as a dashboard workspace with live loans, reservations, shortlist context, and the current access state inside the library platform.'][$lang] }}</p>
            </div>
            <div class="workspace-header-tools">
              <a class="workspace-icon-btn" href="{{ $routeWithLang('/catalog') }}" aria-label="{{ ['ru' => 'Открыть каталог', 'kk' => 'Каталогты ашу', 'en' => 'Open catalog'][$lang] }}">⌕</a>
              <div class="workspace-profile-chip">
                <div id="profile-chip-avatar" class="profile-chip-avatar avatar">{{ strtoupper(substr($sessionUser['name'] ?? 'U', 0, 1)) }}</div>
                <div>
                  <strong id="profile-chip-name">{{ $sessionUser['name'] ?? ['ru' => 'Гость библиотеки', 'kk' => 'Кітапхана қонағы', 'en' => 'Library guest'][$lang] }}</strong>
                  <span id="profile-chip-sub">{{ $roleLabel }}</span>
                </div>
              </div>
            </div>
          </header>

          <div class="workspace-shell">
            <div class="workspace-main">
              <section class="metric-grid" aria-label="Dashboard status cards">
                <article class="metric-card" data-icon="▣">
                  <span class="metric-label">{{ ['ru' => 'Active status', 'kk' => 'Active status', 'en' => 'Active status'][$lang] }}</span>
                  <p id="active-loans-count" class="metric-value">—</p>
                  <p class="metric-caption">{{ ['ru' => 'Текущие выдачи', 'kk' => 'Ағымдағы берілімдер', 'en' => 'Current loans'][$lang] }}</p>
                  <div id="metric-loans-note" class="metric-footnote">{{ ['ru' => 'Сводка по срокам загружается', 'kk' => 'Мерзімдер сводкасы жүктелуде', 'en' => 'Due-date snapshot loading'][$lang] }}</div>
                </article>

                <article class="metric-card" data-icon="◔">
                  <span class="metric-label">{{ ['ru' => 'Waitlist', 'kk' => 'Waitlist', 'en' => 'Waitlist'][$lang] }}</span>
                  <p id="reservations-count" class="metric-value">—</p>
                  <p class="metric-caption">{{ ['ru' => 'Активные бронирования', 'kk' => 'Белсенді броньдар', 'en' => 'Active reservations'][$lang] }}</p>
                  <div id="reservations-note" class="metric-footnote">{{ ['ru' => 'Проверяем готовность к выдаче', 'kk' => 'Беруге дайын статусын тексереміз', 'en' => 'Checking pickup readiness'][$lang] }}</div>
                </article>

                <article class="metric-card metric-card--accent" data-icon="◈">
                  <span class="metric-label">{{ ['ru' => 'Standing', 'kk' => 'Standing', 'en' => 'Standing'][$lang] }}</span>
                  <p id="standing-title" class="metric-value" style="font-size:38px;">{{ ['ru' => 'Проверка', 'kk' => 'Тексеру', 'en' => 'Checking'][$lang] }}</p>
                  <p class="metric-caption">{{ ['ru' => 'Профиль и цифровой доступ', 'kk' => 'Профиль және цифрлық қолжетімділік', 'en' => 'Profile and digital access'][$lang] }}</p>
                  <div id="standing-note" class="metric-footnote">{{ ['ru' => 'Синхронизация статуса читателя', 'kk' => 'Оқырман мәртебесін синхрондау', 'en' => 'Syncing reader standing'][$lang] }}</div>
                </article>
              </section>

              <section class="workspace-panel workspace-panel--loans">
                <div class="panel-head">
                  <div>
                    <h2>{{ ['ru' => 'Мои книги', 'kk' => 'Менің кітаптарым', 'en' => 'My books'][$lang] }}</h2>
                    <p>{{ ['ru' => 'Текущие и недавние выдачи из библиотечного фонда в Stitch-подобной рабочей ленте.', 'kk' => 'Кітапхана қорынан алынған ағымдағы және соңғы берілімдер Stitch-ке жақын жұмыс лентасында.', 'en' => 'Current and recent loans from the library collection in a Stitch-like working stream.'][$lang] }}</p>
                  </div>
                  <div id="loan-tabs" style="display:flex; gap:8px; flex-wrap:wrap; justify-content:flex-end;">
                    <button class="btn btn-ghost loan-tab active" data-status="active" onclick="switchLoanTab('active')" style="font-size:12px; padding:10px 14px;">{{ ['ru' => 'Активные', 'kk' => 'Белсенді', 'en' => 'Active'][$lang] }}</button>
                    <button class="btn btn-ghost loan-tab" data-status="returned" onclick="switchLoanTab('returned')" style="font-size:12px; padding:10px 14px;">{{ ['ru' => 'Возвращённые', 'kk' => 'Қайтарылған', 'en' => 'Returned'][$lang] }}</button>
                    <button class="btn btn-ghost loan-tab" data-status="all" onclick="switchLoanTab('all')" style="font-size:12px; padding:10px 14px;">{{ ['ru' => 'Все', 'kk' => 'Барлығы', 'en' => 'All'][$lang] }}</button>
                  </div>
                </div>

                <div id="book-grid" class="loan-list">
                  <div class="loading"><div class="spinner"></div><p style="margin:8px 0 0;">{{ ['ru' => 'Загрузка выдач...', 'kk' => 'Берілімдер жүктелуде...', 'en' => 'Loading loans...'][$lang] }}</p></div>
                </div>
              </section>

              <section class="workspace-panel workspace-panel--activity">
                <div class="panel-head">
                  <div>
                    <h2>{{ ['ru' => 'Недавняя активность', 'kk' => 'Соңғы әрекеттер', 'en' => 'Recent activity'][$lang] }}</h2>
                    <p>{{ ['ru' => 'Реальные события по выдачам, бронированиям, профилю и подборке.', 'kk' => 'Берілімдер, броньдар, профиль және топтама бойынша нақты оқиғалар.', 'en' => 'Live events across loans, reservations, profile linkage, and shortlist work.'][$lang] }}</p>
                  </div>
                </div>
                <div id="activity-list" class="activity-list">
                  <div class="loading"><div class="spinner"></div><p style="margin:8px 0 0;">{{ ['ru' => 'Собираем активность...', 'kk' => 'Әрекеттер жиналуда...', 'en' => 'Gathering activity...'][$lang] }}</p></div>
                </div>
                <div class="workspace-balance-note">
                  <strong>{{ ['ru' => 'Следующий шаг:', 'kk' => 'Келесі қадам:', 'en' => 'Next step:'][$lang] }}</strong>
                  {{ ['ru' => 'если текущая активность ещё небольшая, используйте каталог, waitlist и подборку как основные рабочие маршруты кабинета.', 'kk' => 'егер ағымдағы белсенділік әлі аз болса, каталог, waitlist және топтаманы кабинеттегі негізгі жұмыс маршруттары ретінде пайдаланыңыз.', 'en' => 'if current activity is still light, use the catalog, waitlist, and shortlist as the main working routes inside the dashboard.'][$lang] }}
                </div>
              </section>
            </div>

            <aside class="workspace-rail">
              <section class="rail-panel rail-panel--waitlist">
                <h4>{{ ['ru' => 'Quick Actions', 'kk' => 'Quick Actions', 'en' => 'Quick Actions'][$lang] }}</h4>
                <div class="quick-action-list">
                  <a href="{{ $routeWithLang('/catalog') }}" class="quick-action">
                    <div>
                      <strong>{{ ['ru' => 'Открыть каталог', 'kk' => 'Каталогты ашу', 'en' => 'Open catalog'][$lang] }}</strong>
                      <span>{{ ['ru' => 'Искать книги, открыть карточку и продолжить к бронированию.', 'kk' => 'Кітаптарды іздеу, карточканы ашу және броньға өту.', 'en' => 'Search titles, open records, and continue to reservation flows.'][$lang] }}</span>
                    </div>
                    <span class="quick-action-arrow">›</span>
                  </a>
                  <a href="{{ $routeWithLang('/shortlist') }}" class="quick-action">
                    <div>
                      <strong>{{ ['ru' => 'Открыть подборку', 'kk' => 'Топтаманы ашу', 'en' => 'Open shortlist'][$lang] }}</strong>
                      <span>{{ ['ru' => 'Вернуться к сохранённым книгам и рабочему draft-списку.', 'kk' => 'Сақталған кітаптар мен жұмыс draft-тізіміне оралу.', 'en' => 'Return to saved books and the working draft list.'][$lang] }}</span>
                    </div>
                    <span class="quick-action-arrow">›</span>
                  </a>
                  <a href="{{ $routeWithLang('/discover') }}" class="quick-action">
                    <div>
                      <strong>{{ ['ru' => 'УДК-навигация', 'kk' => 'ӘОЖ навигациясы', 'en' => 'UDC navigation'][$lang] }}</strong>
                      <span>{{ ['ru' => 'Переход в каталог через академические направления.', 'kk' => 'Каталогқа академиялық бағыттар арқылы өту.', 'en' => 'Move into the catalog through academic subject routes.'][$lang] }}</span>
                    </div>
                    <span class="quick-action-arrow">›</span>
                  </a>
                  <a href="{{ $routeWithLang('/resources') }}" class="quick-action">
                    <div>
                      <strong>{{ ['ru' => 'Электронные ресурсы', 'kk' => 'Электрондық ресурстар', 'en' => 'Resources'][$lang] }}</strong>
                      <span>{{ ['ru' => 'Открыть лицензионные базы и цифровые материалы.', 'kk' => 'Лицензиялық базалар мен цифрлық материалдарды ашу.', 'en' => 'Open licensed databases and digital materials.'][$lang] }}</span>
                    </div>
                    <span class="quick-action-arrow">›</span>
                  </a>
                </div>
              </section>

              <section class="rail-panel">
                <h4>{{ ['ru' => 'Waitlist', 'kk' => 'Waitlist', 'en' => 'Waitlist'][$lang] }}</h4>
                <div id="reservations-grid" class="reservation-list">
                  <div class="loading"><div class="spinner"></div><p style="margin:8px 0 0;">{{ ['ru' => 'Загрузка бронирований...', 'kk' => 'Броньдар жүктелуде...', 'en' => 'Loading reservations...'][$lang] }}</p></div>
                </div>
              </section>

              <section id="workbench-section" class="rail-panel rail-panel--shortlist">
                <div class="panel-head" style="margin-bottom:14px;">
                  <div>
                    <h3 style="font-size:28px;"> {{ ['ru' => 'Подборка и сохранённые действия', 'kk' => 'Топтама және сақталған әрекеттер', 'en' => 'Shortlist and saved work'][$lang] }}</h3>
                    <p>{{ $profileType === 'teacher'
                        ? ['ru' => 'Рабочая подборка для силлабуса, курса и академической навигации.', 'kk' => 'Силлабус, курс және академиялық навигацияға арналған жұмыс топтамасы.', 'en' => 'Working shortlist for syllabus, course, and academic navigation.'][$lang]
                        : ['ru' => 'Сохранённые книги и ресурсы, к которым удобно вернуться позже.', 'kk' => 'Кейін қайта оралуға ыңғайлы сақталған кітаптар мен ресурстар.', 'en' => 'Saved books and resources you can return to later.'][$lang] }}</p>
                  </div>
                  <a href="{{ $routeWithLang('/shortlist') }}" class="panel-link">{{ ['ru' => 'Открыть', 'kk' => 'Ашу', 'en' => 'Open'][$lang] }}</a>
                </div>

                <div id="workbench-loading" style="text-align:center; padding:18px 0;">
                  <div class="spinner"></div>
                  <p style="margin:8px 0 0; color:var(--muted); font-size:13px;">{{ ['ru' => 'Загрузка подборки...', 'kk' => 'Топтама жүктелуде...', 'en' => 'Loading shortlist...'][$lang] }}</p>
                </div>

                <div id="workbench-empty" style="display:none;">
                  <div class="loading" style="text-align:center; border-style:dashed;">
                    <span style="font-size:28px;">📚</span>
                    <p style="margin:8px 0 0; font-weight:600;">{{ ['ru' => 'Подборка пока пуста', 'kk' => 'Топтама әзірге бос', 'en' => 'The shortlist is empty for now'][$lang] }}</p>
                    <p style="margin:6px 0 0; color:var(--muted); font-size:13px;">{{ $profileType === 'teacher'
                      ? ['ru' => 'Добавляйте книги из каталога и электронные ресурсы для подготовки силлабуса.', 'kk' => 'Силлабус дайындау үшін каталогтан кітаптар мен электрондық ресурстарды қосыңыз.', 'en' => 'Add catalog titles and electronic resources to assemble the syllabus list.'][$lang]
                      : ['ru' => 'Сохраняйте книги и цифровые ресурсы, чтобы быстро вернуться к ним позже.', 'kk' => 'Кейін жылдам оралу үшін кітаптар мен цифрлық ресурстарды сақтаңыз.', 'en' => 'Save books and digital resources so you can return to them quickly later.'][$lang] }}</p>
                    <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap; margin-top:14px;">
                      <a href="{{ $routeWithLang('/catalog') }}" style="color:var(--blue); font-weight:700; font-size:13px;">{{ ['ru' => 'Каталог →', 'kk' => 'Каталог →', 'en' => 'Catalog →'][$lang] }}</a>
                      <a href="{{ $routeWithLang('/resources') }}" style="color:var(--blue); font-weight:700; font-size:13px;">{{ ['ru' => 'Ресурсы →', 'kk' => 'Ресурстар →', 'en' => 'Resources →'][$lang] }}</a>
                    </div>
                  </div>
                </div>

                <div id="workbench-content" style="display:none;">
                  <div id="workbench-draft-info" style="margin-bottom:14px;"></div>
                  <div class="rail-shortlist-stats">
                    <div class="rail-stat">
                      <strong id="wb-total">0</strong>
                      <span>{{ ['ru' => 'Всего источников', 'kk' => 'Барлық дереккөз', 'en' => 'Total sources'][$lang] }}</span>
                    </div>
                    <div class="rail-stat">
                      <strong id="wb-books">0</strong>
                      <span>{{ ['ru' => 'Книги', 'kk' => 'Кітаптар', 'en' => 'Books'][$lang] }}</span>
                    </div>
                    <div class="rail-stat">
                      <strong id="wb-external">0</strong>
                      <span>{{ ['ru' => 'E-resources', 'kk' => 'E-resources', 'en' => 'E-resources'][$lang] }}</span>
                    </div>
                  </div>
                  <div style="display:grid; gap:10px;">
                    <a href="{{ $routeWithLang('/shortlist') }}" class="btn btn-primary" style="width:100%; font-size:13px; padding:12px 18px;">{{ ['ru' => 'Редактировать и экспортировать', 'kk' => 'Өңдеу және экспорттау', 'en' => 'Edit and export'][$lang] }}</a>
                    <a href="{{ $routeWithLang('/catalog') }}" class="btn btn-ghost" style="width:100%; font-size:13px; padding:12px 18px;">{{ ['ru' => 'Добавить из каталога', 'kk' => 'Каталогтан қосу', 'en' => 'Add from catalog'][$lang] }}</a>
                  </div>
                </div>
              </section>

              <section class="rail-note">
                <h4>{{ ['ru' => 'Access note', 'kk' => 'Access note', 'en' => 'Access note'][$lang] }}</h4>
                <div id="account-status-alert" class="alert warning">
                  {{ ['ru' => 'Сессия продолжается внутри библиотеки, а проверка учётных данных идёт через CRM API.', 'kk' => 'Сессия кітапхана ішінде жалғасады, ал тіркелгі деректерін тексеру CRM API арқылы жүреді.', 'en' => 'The session stays inside the library interface while credentials are verified through the CRM API.'][$lang] }}
                </div>

                <div class="detail-list">
                  <div class="detail-item">
                    <strong>{{ ['ru' => 'Профиль', 'kk' => 'Профиль', 'en' => 'Profile'][$lang] }}</strong>
                    <span id="reader-link-chip">{{ ['ru' => 'Проверяется', 'kk' => 'Тексерілуде', 'en' => 'Checking'][$lang] }}</span>
                  </div>
                  <div class="detail-item">
                    <strong>{{ ['ru' => 'Код читателя', 'kk' => 'Оқырман коды', 'en' => 'Reader code'][$lang] }}</strong>
                    <span id="reader-legacy-code">{{ ['ru' => 'Уточняется', 'kk' => 'Нақтылануда', 'en' => 'Pending'][$lang] }}</span>
                  </div>
                  <div class="detail-item">
                    <strong>{{ ['ru' => 'Основной email', 'kk' => 'Негізгі email', 'en' => 'Primary email'][$lang] }}</strong>
                    <span id="reader-primary-email">{{ $sessionUser['email'] ?? ['ru' => 'не указан', 'kk' => 'көрсетілмеген', 'en' => 'not specified'][$lang] }}</span>
                  </div>
                  <div class="detail-item">
                    <strong>{{ ['ru' => 'Регистрация', 'kk' => 'Тіркелу', 'en' => 'Registration'][$lang] }}</strong>
                    <span id="reader-registration">{{ ['ru' => 'Появится после связи профиля', 'kk' => 'Профиль байланысқаннан кейін көрінеді', 'en' => 'Will appear after profile matching'][$lang] }}</span>
                  </div>
                  <div class="detail-item">
                    <strong>{{ ['ru' => 'Проверки профиля', 'kk' => 'Профиль тексерістері', 'en' => 'Profile review tasks'][$lang] }}</strong>
                    <span id="reader-review-tasks">{{ ['ru' => 'Загрузка…', 'kk' => 'Жүктелуде…', 'en' => 'Loading…'][$lang] }}</span>
                  </div>
                </div>
              </section>
            </aside>
          </div>

          <div class="workspace-footer-note">
            <div>
              <strong>{{ ['ru' => 'Reader workspace', 'kk' => 'Reader workspace', 'en' => 'Reader workspace'][$lang] }}</strong>
              {{ ['ru' => 'сохраняет реальные renew, reservations, shortlist и session flows без mock-данных.', 'kk' => 'нақты renew, reservations, shortlist және session flow-ларды mock-дерексіз сақтайды.', 'en' => 'keeps real renew, reservations, shortlist, and session flows with no mock data.'][$lang] }}
            </div>
            <div>{{ ['ru' => 'Связанные зоны: каталог, discover, resources, shortlist.', 'kk' => 'Байланысқан аймақтар: каталог, discover, resources, shortlist.', 'en' => 'Connected zones: catalog, discover, resources, shortlist.'][$lang] }}</div>
          </div>
        </div>
      </section>
    </div>
  </main>

  <div id="toast-container"></div>
  <div id="confirm-modal" class="modal-overlay">
    <div class="modal-box">
      <h3 id="modal-title">{{ ['ru' => 'Подтверждение', 'kk' => 'Растау', 'en' => 'Confirmation'][$lang] }}</h3>
      <p id="modal-message"></p>
      <div class="modal-actions">
        <button id="modal-confirm" class="btn btn-primary">{{ ['ru' => 'Да, продлить', 'kk' => 'Иә, ұзарту', 'en' => 'Yes, renew'][$lang] }}</button>
        <button id="modal-cancel" class="btn btn-ghost">{{ ['ru' => 'Отмена', 'kk' => 'Бас тарту', 'en' => 'Cancel'][$lang] }}</button>
      </div>
    </div>
  </div>

  @include('partials.footer')

  <script>
    const ACCOUNT_LOANS_ENDPOINT = '/api/v1/account/loans';
    const ACCOUNT_LOANS_SUMMARY_ENDPOINT = '/api/v1/account/loans/summary';
    const ACCOUNT_RESERVATIONS_ENDPOINT = '/api/v1/account/reservations';
    const ACCOUNT_SUMMARY_ENDPOINT = '/api/v1/account/summary';
    const ME_ENDPOINT = '/api/v1/me';
    const AUTH_USER_KEY = 'library.auth.user';
    const ACCOUNT_LANG = @json($lang);
    const ACCOUNT_DATE_LOCALE = ACCOUNT_LANG === 'kk' ? 'kk-KZ' : ACCOUNT_LANG === 'en' ? 'en-US' : 'ru-RU';
    const dashboardState = {
      summary: null,
      hasReaderProfile: true,
      loans: { active: [], returned: [] },
      reservations: [],
      shortlist: null,
    };
    const ACCOUNT_I18N_MAP = {!! json_encode([
      'ru' => [
        'guest' => 'Гость библиотеки', 'notSpecified' => 'не указан', 'loginRole' => 'Логин: {login} · Роль: {role}',
        'overdue' => 'Просрочено', 'dueSoon' => 'Срок скоро', 'returned' => 'Возвращено', 'active' => 'Активно',
        'renewals' => 'Продлений: {count}/{max}', 'renew14' => 'Продлить на 14 дней', 'book' => 'Книга', 'inventory' => 'Инв. №', 'issued' => 'Выдано', 'due' => 'Срок', 'returnedOn' => 'Возвращено',
        'profileMissingTitle' => 'Профиль читателя не найден', 'profileMissingBody' => 'Обратитесь к библиотекарю для привязки вашего аккаунта к читательскому профилю.',
        'noActiveTitle' => 'Нет активных выдач', 'noActiveBody' => 'У вас сейчас нет книг на руках.', 'noReturnedTitle' => 'Нет истории возвратов', 'noReturnedBody' => 'Возвращённые книги будут отображаться здесь.', 'noAllTitle' => 'Нет записей о выдачах', 'noAllBody' => 'Когда вы возьмёте книгу, она появится здесь.',
        'openCatalog' => 'Перейти в каталог →', 'renewLoanTitle' => 'Продление выдачи', 'renewLoanMessage' => 'Продлить выдачу на 14 дней? Оставшиеся продления уменьшатся на 1.', 'renewFail' => 'Не удалось продлить выдачу', 'renewBlocked' => 'Продление невозможно', 'networkError' => 'Ошибка сети',
        'legacyMissing' => 'Профиль читателя пока не связан с библиотечной записью. Обратитесь к библиотекарю для проверки данных.', 'summaryError' => 'Не удалось загрузить сводку кабинета', 'apiError' => 'Ошибка API', 'loadLoansError' => 'Не удалось загрузить данные о выдачах',
        'reservationLabel' => 'Бронь', 'year' => 'Год', 'reservedAt' => 'Забронировано', 'validUntil' => 'Действует до', 'reason' => 'Причина', 'cancelConfirm' => 'Вы уверены, что хотите отменить это бронирование?', 'cancelFail' => 'Не удалось отменить бронирование.', 'cancelNetwork' => 'Ошибка сети. Попробуйте ещё раз.', 'loadReservationsError' => 'Не удалось загрузить бронирования.',
        'noReservationsTitle' => 'Нет активных бронирований', 'noReservationsBody' => 'Используйте каталог, чтобы забронировать издание, когда экземпляр станет доступен.', 'openCatalogPlain' => 'Открыть каталог →',
        'activityEmptyTitle' => 'История пока пуста', 'activityEmptyBody' => 'Когда появятся выдачи, бронирования или сохранённые книги, они отобразятся здесь.', 'activityLoanDue' => 'Срок возврата: {date}', 'activityLoanReturned' => 'Книга возвращена {date}', 'activityReservation' => 'Бронирование: {status}', 'activityShortlist' => 'Последнее обновление подборки: {date}', 'activityProfile' => 'Профиль связан и готов к использованию', 'statusLinked' => 'Связан с читателем', 'statusPending' => 'Связь уточняется', 'reviewTasksNone' => 'Открытых задач нет', 'reviewTasksSome' => 'Открыто задач: {count}', 'registeredAt' => 'Зарегистрирован: {date}', 'reregisteredAt' => 'Перерегистрация: {date}'
      ],
      'kk' => [
        'guest' => 'Кітапхана қонағы', 'notSpecified' => 'көрсетілмеген', 'loginRole' => 'Логин: {login} · Рөл: {role}',
        'overdue' => 'Мерзімі өткен', 'dueSoon' => 'Жақында тапсыру', 'returned' => 'Қайтарылған', 'active' => 'Белсенді',
        'renewals' => 'Ұзартулар: {count}/{max}', 'renew14' => '14 күнге ұзарту', 'book' => 'Кітап', 'inventory' => 'Инв. №', 'issued' => 'Берілді', 'due' => 'Мерзімі', 'returnedOn' => 'Қайтарылды',
        'profileMissingTitle' => 'Оқырман профилі табылмады', 'profileMissingBody' => 'Аккаунтыңызды оқырман профиліне байланыстыру үшін кітапханашыға жүгініңіз.',
        'noActiveTitle' => 'Белсенді берілім жоқ', 'noActiveBody' => 'Қазір қолыңызда кітап жоқ.', 'noReturnedTitle' => 'Қайтарым тарихы жоқ', 'noReturnedBody' => 'Қайтарылған кітаптар осында көрсетіледі.', 'noAllTitle' => 'Берілім жазбалары жоқ', 'noAllBody' => 'Кітап алған кезде ол осында пайда болады.',
        'openCatalog' => 'Каталогқа өту →', 'renewLoanTitle' => 'Берілімді ұзарту', 'renewLoanMessage' => 'Берілімді 14 күнге ұзартасыз ба? Қалған ұзартулар 1-ге азаяды.', 'renewFail' => 'Берілімді ұзарту мүмкін болмады', 'renewBlocked' => 'Ұзарту мүмкін емес', 'networkError' => 'Желі қатесі',
        'legacyMissing' => 'Оқырман профилі әлі кітапхана жазбасымен байланыспаған. Деректерді тексеру үшін кітапханашыға жүгініңіз.', 'summaryError' => 'Кабинет деректерін жүктеу мүмкін болмады', 'apiError' => 'API қатесі', 'loadLoansError' => 'Берілім деректерін жүктеу мүмкін болмады',
        'reservationLabel' => 'Бронь', 'year' => 'Жыл', 'reservedAt' => 'Брондалған', 'validUntil' => 'Жарамды мерзімі', 'reason' => 'Себеп', 'cancelConfirm' => 'Бұл броньды шынымен тоқтатқыңыз келе ме?', 'cancelFail' => 'Броньды тоқтату мүмкін болмады.', 'cancelNetwork' => 'Желі қатесі. Қайта көріңіз.', 'loadReservationsError' => 'Броньдарды жүктеу мүмкін болмады.',
        'noReservationsTitle' => 'Белсенді броньдар жоқ', 'noReservationsBody' => 'Дана қолжетімді болғанда оны брондау үшін каталогты пайдаланыңыз.', 'openCatalogPlain' => 'Каталогты ашу →',
        'activityEmptyTitle' => 'Тарих әзірге бос', 'activityEmptyBody' => 'Берілім, бронь немесе сақталған кітаптар пайда болғанда, олар осында көрінеді.', 'activityLoanDue' => 'Қайтару мерзімі: {date}', 'activityLoanReturned' => 'Кітап қайтарылды: {date}', 'activityReservation' => 'Бронь: {status}', 'activityShortlist' => 'Топтаманың соңғы жаңартылуы: {date}', 'activityProfile' => 'Профиль байланыстырылған және дайын', 'statusLinked' => 'Оқырман профиліне байланысқан', 'statusPending' => 'Байланыс нақтылануда', 'reviewTasksNone' => 'Ашық тапсырма жоқ', 'reviewTasksSome' => 'Ашық тапсырмалар: {count}', 'registeredAt' => 'Тіркелген күні: {date}', 'reregisteredAt' => 'Қайта тіркелу: {date}'
      ],
      'en' => [
        'guest' => 'Library guest', 'notSpecified' => 'not specified', 'loginRole' => 'Login: {login} · Role: {role}',
        'overdue' => 'Overdue', 'dueSoon' => 'Due soon', 'returned' => 'Returned', 'active' => 'Active',
        'renewals' => 'Renewals: {count}/{max}', 'renew14' => 'Renew for 14 days', 'book' => 'Book', 'inventory' => 'Inv. No.', 'issued' => 'Issued', 'due' => 'Due', 'returnedOn' => 'Returned',
        'profileMissingTitle' => 'Reader profile not found', 'profileMissingBody' => 'Contact a librarian to link your account to a library reader profile.',
        'noActiveTitle' => 'No active loans', 'noActiveBody' => 'You do not have any books on loan right now.', 'noReturnedTitle' => 'No return history', 'noReturnedBody' => 'Returned books will appear here.', 'noAllTitle' => 'No loan records yet', 'noAllBody' => 'When you borrow a book it will appear here.',
        'openCatalog' => 'Open catalog →', 'renewLoanTitle' => 'Renew loan', 'renewLoanMessage' => 'Renew this loan for 14 days? Remaining renewals will decrease by 1.', 'renewFail' => 'Unable to renew the loan', 'renewBlocked' => 'Renewal unavailable', 'networkError' => 'Network error',
        'legacyMissing' => 'The reader profile is not yet linked to a library record. Contact a librarian to verify the data.', 'summaryError' => 'Unable to load the account summary', 'apiError' => 'API error', 'loadLoansError' => 'Unable to load loan data',
        'reservationLabel' => 'Reservation', 'year' => 'Year', 'reservedAt' => 'Reserved at', 'validUntil' => 'Valid until', 'reason' => 'Reason', 'cancelConfirm' => 'Are you sure you want to cancel this reservation?', 'cancelFail' => 'Unable to cancel the reservation.', 'cancelNetwork' => 'Network error. Please try again.', 'loadReservationsError' => 'Unable to load reservations.',
        'noReservationsTitle' => 'No active reservations', 'noReservationsBody' => 'Use the catalog to reserve a title when a copy becomes available.', 'openCatalogPlain' => 'Open catalog →',
        'activityEmptyTitle' => 'No activity yet', 'activityEmptyBody' => 'Loans, reservations, and saved shortlist work will appear here once you start using the account.', 'activityLoanDue' => 'Due date: {date}', 'activityLoanReturned' => 'Returned on {date}', 'activityReservation' => 'Reservation: {status}', 'activityShortlist' => 'Shortlist last updated: {date}', 'activityProfile' => 'Profile linked and ready to use', 'statusLinked' => 'Linked to reader profile', 'statusPending' => 'Profile link pending', 'reviewTasksNone' => 'No open review tasks', 'reviewTasksSome' => 'Open review tasks: {count}', 'registeredAt' => 'Registered: {date}', 'reregisteredAt' => 'Reregistered: {date}'
      ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
    const ACCOUNT_I18N = ACCOUNT_I18N_MAP[ACCOUNT_LANG] || ACCOUNT_I18N_MAP.ru;

    function withLang(path) {
      const url = new URL(path, window.location.origin);
      if (ACCOUNT_LANG !== 'ru' && !url.searchParams.has('lang')) {
        url.searchParams.set('lang', ACCOUNT_LANG);
      }
      return `${url.pathname}${url.search}`;
    }
    let currentLoanTab = 'active';

    function getCsrfToken() {
      return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function normalizeText(value, fallback = '') {
      if (!value || typeof value !== 'string') return fallback;
      return value.trim() || fallback;
    }

    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function getAuthUser() {
      const raw = localStorage.getItem(AUTH_USER_KEY);
      if (!raw) return null;

      try {
        return JSON.parse(raw);
      } catch (_) {
        return null;
      }
    }

    function updateProfileFromAuth(user) {
      if (!user) return;

      const name = normalizeText(user?.name, ACCOUNT_I18N.guest);
      const login = normalizeText(user?.ad_login || user?.login || user?.email, ACCOUNT_I18N.notSpecified);
      const role = normalizeText(user?.role, 'reader');

      const avatar = document.getElementById('profile-avatar');
      const profileName = document.getElementById('profile-name');
      const profileSub = document.getElementById('profile-sub');
      const chipAvatar = document.getElementById('profile-chip-avatar');
      const chipName = document.getElementById('profile-chip-name');
      const chipSub = document.getElementById('profile-chip-sub');

      if (avatar) avatar.textContent = name.charAt(0).toUpperCase();
      if (profileName) profileName.textContent = name;
      if (profileSub) profileSub.textContent = ACCOUNT_I18N.loginRole.replace('{login}', login).replace('{role}', role);
      if (chipAvatar) chipAvatar.textContent = name.charAt(0).toUpperCase();
      if (chipName) chipName.textContent = name;
      if (chipSub) chipSub.textContent = ACCOUNT_I18N.loginRole.replace('{login}', login).replace('{role}', role);
    }

    function redirectToLogin() {
      const redirectTo = encodeURIComponent(window.location.pathname + window.location.search);
      window.location.href = withLang(`/login?redirect=${redirectTo}`);
    }

    async function getSessionUser() {
      const response = await fetch(ME_ENDPOINT, {
        headers: {
          Accept: 'application/json',
        },
      });

      if (!response.ok) {
        return null;
      }

      const payload = await response.json().catch(() => ({}));
      if (payload?.authenticated !== true || !payload?.user) {
        return null;
      }

      return payload.user;
    }

    function formatLoanData(loan) {
      const dueDate = loan.dueAt ? new Date(loan.dueAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
      const issuedDate = loan.issuedAt ? new Date(loan.issuedAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
      const isOverdue = loan.isOverdue === true;
      const isDueSoon = loan.isDueSoon === true;

      return {
        id: loan.id || '',
        copyId: loan.copyId || '',
        status: loan.status || 'active',
        dueDate,
        issuedDate,
        isOverdue,
        isDueSoon,
        renewCount: loan.renewCount || 0,
        maxRenewals: loan.maxRenewals || 3,
        canRenew: loan.canRenew === true,
        renewBlockReason: loan.renewBlockReason || null,
        returnedAt: loan.returnedAt ? new Date(loan.returnedAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : null,
        book: loan.book || {},
      };
    }

    function loanStatusBadge(data) {
      if (data.isOverdue) return `<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;color:#ba1a1a;background:rgba(186,26,26,.08);border:1px solid rgba(186,26,26,.16);">${ACCOUNT_I18N.overdue}</span>`;
      if (data.isDueSoon) return `<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;color:#5d4201;background:rgba(93,66,1,.08);border:1px solid rgba(93,66,1,.16);">${ACCOUNT_I18N.dueSoon}</span>`;
      if (data.status === 'returned') return `<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;color:#14696d;background:rgba(20,105,109,.08);border:1px solid rgba(20,105,109,.16);">${ACCOUNT_I18N.returned}</span>`;
      return `<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;color:#001e40;background:rgba(0,30,64,.05);border:1px solid rgba(195,198,209,.55);">${ACCOUNT_I18N.active}</span>`;
    }

    function renderLoanCard(loan) {
      const data = formatLoanData(loan);
      const bookTitle = data.book?.title || null;
      const bookAuthor = data.book?.author || null;
      const bookIsbn = data.book?.isbn || null;
      const invNumber = data.book?.inventoryNumber || null;
      const canRenew = data.canRenew === true;
      const maxRenewals = data.maxRenewals || 3;
      const renewBlockReason = data.renewBlockReason || null;

      const gradientColor = data.isOverdue
        ? 'background: linear-gradient(180deg, #7b1f1f 0%, #5e1515 100%);'
        : (data.isDueSoon
          ? 'background: linear-gradient(180deg, #6a4c12 0%, #5d4201 100%);'
          : (data.status === 'returned'
            ? 'background: linear-gradient(180deg, #59616c 0%, #43474f 100%); opacity: 0.88;'
            : ''));

      const displayTitle = bookTitle
        ? escapeHtml(bookTitle.substring(0, 60)) + (bookTitle.length > 60 ? '…' : '')
        : `${ACCOUNT_I18N.book} ${escapeHtml(data.copyId.substring(0, 12))}…`;

      const previewTitle = bookTitle
        ? escapeHtml(bookTitle.substring(0, 40)) + (bookTitle.length > 40 ? '…' : '')
        : `#${escapeHtml(data.id.substring(0, 8))}`;

      const renewProgress = data.status === 'active'
        ? `<span>${ACCOUNT_I18N.renewals.replace('{count}', data.renewCount).replace('{max}', maxRenewals)}</span>`
        : '';

      let renewSection = '';
      if (canRenew) {
        renewSection = `<button id="renew-btn-${escapeHtml(data.id)}" class="btn btn-primary" onclick="readerRenew('${escapeHtml(data.id)}')">${ACCOUNT_I18N.renew14}</button>`;
      } else if (data.status === 'active' && renewBlockReason) {
        renewSection = `<div style="padding:10px 12px;background:rgba(186,26,26,.06);border:1px solid rgba(186,26,26,.16);border-radius:6px;font-size:12px;color:#ba1a1a;text-align:center;line-height:1.5;">${escapeHtml(renewBlockReason)}</div>`;
      }

      const dueLabelClass = data.isOverdue ? 'warn' : (data.isDueSoon ? 'soon' : '');
      const dueLabel = data.status === 'returned' ? ACCOUNT_I18N.returnedOn : ACCOUNT_I18N.due;
      const dueValue = data.status === 'returned' ? (data.returnedAt || '—') : data.dueDate;
      const kicker = bookAuthor ? escapeHtml(bookAuthor.substring(0, 30)) : ACCOUNT_I18N.book;
      const facts = [
        loanStatusBadge(data),
        bookIsbn ? `<span>ISBN: ${escapeHtml(bookIsbn)}</span>` : '',
        invNumber ? `<span>${ACCOUNT_I18N.inventory}: ${escapeHtml(invNumber)}</span>` : '',
        `<span>${ACCOUNT_I18N.issued}: ${data.issuedDate}</span>`,
        renewProgress,
      ].filter(Boolean).join('');

      return `
        <article class="loan-row">
          <div class="loan-cover" style="${gradientColor}">
            <small>${kicker}</small>
            <span>${previewTitle}</span>
          </div>
          <div class="loan-meta">
            <div class="loan-kicker">${data.copyId ? escapeHtml(data.copyId) : ACCOUNT_I18N.book}</div>
            <h3 class="loan-title">${displayTitle}</h3>
            ${bookAuthor ? `<div class="loan-author">${escapeHtml(bookAuthor)}</div>` : ''}
            <div class="loan-facts">${facts}</div>
          </div>
          <div class="loan-due">
            <span class="loan-due-label ${dueLabelClass}">${escapeHtml(dueLabel)}</span>
            <span class="loan-due-date">${escapeHtml(dueValue)}</span>
          </div>
          <div class="loan-actions">
            ${renewSection}
          </div>
        </article>
      `;
    }

    function renderNoLoansMessage(hasReaderProfile = true, tab = 'active') {
      if (!hasReaderProfile) {
        return `
          <div class="empty-state">
            <span class="empty-state-icon">📋</span>
            <p class="empty-state-title" style="color:#92400e;">${ACCOUNT_I18N.profileMissingTitle}</p>
            <p class="empty-state-body" style="color:#a16207; max-width: 560px;">${ACCOUNT_I18N.profileMissingBody}</p>
            <div class="empty-state-actions">
              <a href="${withLang('/contacts')}" class="btn btn-ghost">${ACCOUNT_LANG === 'kk' ? 'Кітапханамен байланысу' : ACCOUNT_LANG === 'en' ? 'Contact library' : 'Связаться с библиотекой'}</a>
              <a href="${withLang('/catalog')}" class="btn btn-primary">${ACCOUNT_I18N.openCatalog.replace(' →', '')}</a>
            </div>
          </div>
        `;
      }
      const messages = {
        active: { icon: '📚', title: ACCOUNT_I18N.noActiveTitle, sub: ACCOUNT_I18N.noActiveBody },
        returned: { icon: '✓', title: ACCOUNT_I18N.noReturnedTitle, sub: ACCOUNT_I18N.noReturnedBody },
        all: { icon: '📖', title: ACCOUNT_I18N.noAllTitle, sub: ACCOUNT_I18N.noAllBody },
      };
      const m = messages[tab] || messages.active;
      return `
        <div class="empty-state">
          <span class="empty-state-icon">${m.icon}</span>
          <p class="empty-state-title">${m.title}</p>
          <p class="empty-state-body">${m.sub}</p>
          <div class="empty-state-actions">
            <a href="${withLang('/catalog')}" class="btn btn-primary">${ACCOUNT_I18N.openCatalog.replace(' →', '')}</a>
            <a href="${withLang('/discover')}" class="btn btn-ghost">${ACCOUNT_LANG === 'kk' ? 'ӘОЖ навигациясы' : ACCOUNT_LANG === 'en' ? 'Browse subjects' : 'Навигация по УДК'}</a>
          </div>
        </div>
      `;
    }

    function showToast(type, title, message, duration = 4500) {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      toast.className = `toast ${type}`;
      const icon = type === 'success' ? '✓' : '✕';
      toast.innerHTML = `
        <span class="toast-icon">${icon}</span>
        <div class="toast-body">
          <div class="toast-title">${title}</div>
          <div>${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">×</button>
      `;
      container.appendChild(toast);
      requestAnimationFrame(() => toast.classList.add('visible'));
      setTimeout(() => { toast.classList.remove('visible'); setTimeout(() => toast.remove(), 400); }, duration);
    }

    function showConfirmModal(title, message) {
      return new Promise(resolve => {
        const overlay = document.getElementById('confirm-modal');
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-message').textContent = message;
        overlay.classList.add('visible');
        const confirm = document.getElementById('modal-confirm');
        const cancel = document.getElementById('modal-cancel');
        function cleanup(result) {
          overlay.classList.remove('visible');
          confirm.replaceWith(confirm.cloneNode(true));
          cancel.replaceWith(cancel.cloneNode(true));
          resolve(result);
        }
        confirm.addEventListener('click', () => cleanup(true), { once: true });
        cancel.addEventListener('click', () => cleanup(false), { once: true });
        overlay.addEventListener('click', e => { if (e.target === overlay) cleanup(false); }, { once: true });
      });
    }

    async function readerRenew(loanId) {
      const confirmed = await showConfirmModal(ACCOUNT_I18N.renewLoanTitle, ACCOUNT_I18N.renewLoanMessage);
      if (!confirmed) return;

      const btn = document.getElementById(`renew-btn-${loanId}`);
      if (btn) {
        btn.disabled = true;
        btn.style.opacity = '0.6';
        btn.textContent = ACCOUNT_LANG === 'kk' ? '⏳ Ұзартылуда…' : ACCOUNT_LANG === 'en' ? '⏳ Renewing…' : '⏳ Продление…';
      }

      try {
        const resp = await fetch(`/api/v1/account/loans/${loanId}/renew`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
          },
          body: JSON.stringify({})
        });

        if (resp.status === 401) { redirectToLogin(); return; }

        const data = await resp.json();

        if (resp.ok && data.success) {
          const newDue = data.data?.dueAt ? new Date(data.data.dueAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
          const renewsLeft = (data.data?.maxRenewals || 3) - (data.data?.renewCount || 0);
          const toastTitle = ACCOUNT_LANG === 'kk' ? '✓ Ұзартылды!' : ACCOUNT_LANG === 'en' ? '✓ Renewed!' : '✓ Продлено!';
          const toastMessage = ACCOUNT_LANG === 'kk'
            ? `Жаңа мерзім: ${newDue}. Қалған ұзартулар: ${renewsLeft}`
            : ACCOUNT_LANG === 'en'
              ? `New due date: ${newDue}. Renewals left: ${renewsLeft}`
              : `Новый срок: ${newDue}. Осталось продлений: ${renewsLeft}`;
          showToast('success', toastTitle, toastMessage);
          loadBooks();
          loadLoanSummary();
        } else {
          const errorMsg = data.message || data.error || ACCOUNT_I18N.renewFail;
          showToast('error', ACCOUNT_I18N.renewBlocked, errorMsg);
          if (btn) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.textContent = `🔄 ${ACCOUNT_I18N.renew14}`;
          }
        }
      } catch (err) {
        showToast('error', ACCOUNT_I18N.networkError, err.message);
        if (btn) {
          btn.disabled = false;
          btn.style.opacity = '1';
          btn.textContent = `🔄 ${ACCOUNT_I18N.renew14}`;
        }
      }
    }

    function updateStats(loanSummary) {
      const activeEl = document.getElementById('active-loans-count');
      const overdueEl = document.getElementById('overdue-loans-count');
      const dueSoonEl = document.getElementById('due-soon-loans-count');
      const returnedEl = document.getElementById('returned-loans-count');
      const metricNote = document.getElementById('metric-loans-note');

      const activeLoans = Number(loanSummary?.activeLoans ?? 0);
      const overdueLoans = Number(loanSummary?.overdueLoans ?? 0);
      const dueSoonLoans = Number(loanSummary?.dueSoonLoans ?? 0);
      const returnedLoans = Number(loanSummary?.returnedLoans ?? 0);

      if (activeEl) activeEl.textContent = String(activeLoans);
      if (overdueEl) {
        const overdue = overdueLoans;
        overdueEl.textContent = String(overdue);
        overdueEl.style.color = overdue > 0 ? '#991b1b' : 'inherit';
      }
      if (dueSoonEl) {
        const dueSoon = dueSoonLoans;
        dueSoonEl.textContent = String(dueSoon);
        dueSoonEl.style.color = dueSoon > 0 ? '#92400e' : 'inherit';
      }
      if (returnedEl) returnedEl.textContent = String(returnedLoans);

      if (metricNote) {
        if (overdueLoans > 0) {
          metricNote.textContent = `${overdueLoans} ${ACCOUNT_I18N.overdue}`;
          metricNote.className = 'metric-footnote warn';
        } else if (dueSoonLoans > 0) {
          metricNote.textContent = `${dueSoonLoans} ${ACCOUNT_I18N.dueSoon}`;
          metricNote.className = 'metric-footnote warn';
        } else if (activeLoans > 0) {
          metricNote.textContent = `${returnedLoans} ${ACCOUNT_I18N.returned.toLowerCase ? ACCOUNT_I18N.returned.toLowerCase() : ACCOUNT_I18N.returned}`;
          metricNote.className = 'metric-footnote good';
        } else {
          metricNote.textContent = ACCOUNT_LANG === 'kk' ? 'Қолда белсенді берілім жоқ' : ACCOUNT_LANG === 'en' ? 'No active loans on hand' : 'Активных выдач на руках нет';
          metricNote.className = 'metric-footnote';
        }
      }
    }

    function updateStatusAlert(summary) {
      const alertEl = document.getElementById('account-status-alert');
      if (!alertEl) return;

      const linked = summary?.reader?.linked === true;
      const legacyCode = normalizeText(summary?.reader?.legacyCode, ACCOUNT_I18N.notSpecified);
      const primaryEmail = normalizeText(summary?.reader?.primaryEmail, ACCOUNT_I18N.notSpecified);

      if (!linked) {
        alertEl.textContent = ACCOUNT_I18N.legacyMissing;
        return;
      }

      alertEl.textContent = ACCOUNT_LANG === 'kk'
        ? `Оқырман профилі байланыстырылған. Код: ${legacyCode}. Негізгі email: ${primaryEmail}.`
        : ACCOUNT_LANG === 'en'
          ? `Reader profile linked. Code: ${legacyCode}. Primary email: ${primaryEmail}.`
          : `Профиль читателя связан. Код: ${legacyCode}. Основной email: ${primaryEmail}.`;
    }

    function formatDate(value) {
      if (!value) return '—';

      const date = new Date(value);
      if (Number.isNaN(date.getTime())) return String(value);

      return date.toLocaleDateString(ACCOUNT_DATE_LOCALE);
    }

    function updateAccessSnapshot(summary) {
      const linked = summary?.reader?.linked === true;
      const reader = summary?.reader || {};
      const stats = summary?.stats || {};

      const linkChip = document.getElementById('reader-link-chip');
      const legacy = document.getElementById('reader-legacy-code');
      const email = document.getElementById('reader-primary-email');
      const registration = document.getElementById('reader-registration');
      const reviewTasks = document.getElementById('reader-review-tasks');
      const standingTitle = document.getElementById('standing-title');
      const standingNote = document.getElementById('standing-note');
      const openTasks = Number(stats?.openReaderReviewTasks || 0);

      if (linkChip) {
        linkChip.textContent = linked ? ACCOUNT_I18N.statusLinked : ACCOUNT_I18N.statusPending;
      }
      if (legacy) {
        legacy.textContent = normalizeText(reader?.legacyCode, ACCOUNT_I18N.notSpecified);
      }
      if (email) {
        email.textContent = normalizeText(reader?.primaryEmail, normalizeText(summary?.user?.email, ACCOUNT_I18N.notSpecified));
      }
      if (registration) {
        if (reader?.reregistrationAt) {
          registration.textContent = ACCOUNT_I18N.reregisteredAt.replace('{date}', formatDate(reader.reregistrationAt));
        } else if (reader?.registrationAt) {
          registration.textContent = ACCOUNT_I18N.registeredAt.replace('{date}', formatDate(reader.registrationAt));
        } else {
          registration.textContent = ACCOUNT_LANG === 'kk' ? 'Профиль байланысқаннан кейін көрінеді' : ACCOUNT_LANG === 'en' ? 'Will appear after profile matching' : 'Появится после связи профиля';
        }
      }
      if (reviewTasks) {
        reviewTasks.textContent = openTasks > 0
          ? ACCOUNT_I18N.reviewTasksSome.replace('{count}', String(openTasks))
          : ACCOUNT_I18N.reviewTasksNone;
      }
      if (standingTitle) {
        standingTitle.textContent = linked ? ACCOUNT_I18N.statusLinked : ACCOUNT_I18N.statusPending;
      }
      if (standingNote) {
        standingNote.textContent = linked
          ? (openTasks > 0 ? ACCOUNT_I18N.reviewTasksSome.replace('{count}', String(openTasks)) : ACCOUNT_I18N.reviewTasksNone)
          : ACCOUNT_I18N.profileMissingBody;
        standingNote.className = linked ? 'metric-footnote good' : 'metric-footnote warn';
      }
    }

    function renderActivity() {
      const container = document.getElementById('activity-list');
      if (!container) return;

      const events = [];

      if (dashboardState.summary?.reader?.linked && (dashboardState.summary?.reader?.registrationAt || dashboardState.summary?.reader?.reregistrationAt)) {
        events.push({
          date: dashboardState.summary?.reader?.registrationAt || dashboardState.summary?.reader?.reregistrationAt,
          kind: 'profile',
          title: ACCOUNT_I18N.activityProfile,
          body: normalizeText(dashboardState.summary?.reader?.legacyCode, ACCOUNT_I18N.notSpecified),
        });
      }

      for (const loan of [...dashboardState.loans.active, ...dashboardState.loans.returned]) {
        if (loan?.returnedAt) {
          events.push({
            date: loan.returnedAt,
            kind: 'returned',
            title: normalizeText(loan?.book?.title, ACCOUNT_I18N.book),
            body: ACCOUNT_I18N.activityLoanReturned.replace('{date}', formatDate(loan.returnedAt)),
          });
          continue;
        }

        if (loan?.dueAt) {
          events.push({
            date: loan.dueAt,
            kind: loan?.isOverdue ? 'warning' : 'loan',
            title: normalizeText(loan?.book?.title, ACCOUNT_I18N.book),
            body: ACCOUNT_I18N.activityLoanDue.replace('{date}', formatDate(loan.dueAt)),
          });
        }
      }

      for (const reservation of dashboardState.reservations) {
        events.push({
          date: reservation?.reservedAt || reservation?.expiresAt || new Date().toISOString(),
          kind: 'reservation',
          title: normalizeText(reservation?.book?.title, ACCOUNT_I18N.reservationLabel),
          body: ACCOUNT_I18N.activityReservation.replace('{status}', reservation?.status || '—'),
        });
      }

      if (dashboardState.shortlist?.lastAddedAt) {
        events.push({
          date: dashboardState.shortlist.lastAddedAt,
          kind: 'shortlist',
          title: ACCOUNT_LANG === 'kk' ? 'Топтама' : ACCOUNT_LANG === 'en' ? 'Shortlist' : 'Подборка',
          body: ACCOUNT_I18N.activityShortlist.replace('{date}', formatDate(dashboardState.shortlist.lastAddedAt)),
        });
      }

      events.sort((left, right) => new Date(right.date).getTime() - new Date(left.date).getTime());
      const items = events.slice(0, 5);

      if (items.length === 0) {
        container.innerHTML = `<div class="empty-state empty-state--compact"><span class="empty-state-icon">◌</span><p class="empty-state-title">${ACCOUNT_I18N.activityEmptyTitle}</p><p class="empty-state-body">${ACCOUNT_I18N.activityEmptyBody}</p><div class="empty-state-actions"><a href="${withLang('/shortlist')}" class="btn btn-ghost">${ACCOUNT_LANG === 'kk' ? 'Топтаманы ашу' : ACCOUNT_LANG === 'en' ? 'Open shortlist' : 'Открыть подборку'}</a></div></div>`;
        return;
      }

      const iconMap = {
        profile: '◈',
        returned: '↺',
        loan: '◔',
        warning: '!',
        reservation: '☷',
        shortlist: '☰',
      };

      container.innerHTML = items.map((item) => `
        <article class="activity-item">
          <span class="activity-icon">${iconMap[item.kind] || '•'}</span>
          <div class="activity-copy">
            <strong>${escapeHtml(item.title)}</strong>
            <span class="activity-body">${escapeHtml(item.body)}</span>
            <span class="activity-date">${escapeHtml(formatDate(item.date))}</span>
          </div>
        </article>
      `).join('');
    }

    async function fetchLoans(status) {
      const response = await fetch(`${ACCOUNT_LOANS_ENDPOINT}?status=${status}`, {
        headers: { Accept: 'application/json' },
      });

      if (response.status === 401) {
        redirectToLogin();
        return { data: [], hasReaderProfile: false };
      }

      if (!response.ok) {
        throw new Error(ACCOUNT_I18N.apiError);
      }

      const payload = await response.json();

      return {
        data: Array.isArray(payload?.data) ? payload.data : [],
        hasReaderProfile: !String(payload?.message || '').includes('No linked reader'),
      };
    }

    function renderLoansForTab(tab) {
      const grid = document.getElementById('book-grid');
      const loans = tab === 'all'
        ? [...dashboardState.loans.active, ...dashboardState.loans.returned]
        : [...(dashboardState.loans[tab] || [])];

      if (!loans.length) {
        grid.innerHTML = renderNoLoansMessage(dashboardState.hasReaderProfile, tab);
        return;
      }

      grid.innerHTML = loans.map(renderLoanCard).join('');
    }

    async function loadAccountSummary() {
      const response = await fetch(ACCOUNT_SUMMARY_ENDPOINT, {
        headers: {
          Accept: 'application/json',
        },
      });

      if (response.status === 401) { redirectToLogin(); return; }
      if (!response.ok) {
        throw new Error(ACCOUNT_I18N.summaryError);
      }

      const payload = await response.json().catch(() => ({}));
      const data = payload?.data || {};

      dashboardState.summary = data;
      dashboardState.hasReaderProfile = data?.reader?.linked !== false;

      updateStatusAlert(data);
      updateAccessSnapshot(data);
      renderActivity();

      const sessionUser = data?.user || null;
      const readerName = normalizeText(data?.reader?.fullName);
      if (sessionUser && readerName) {
        updateProfileFromAuth({
          ...sessionUser,
          name: readerName,
        });
      }
    }

    function switchLoanTab(status) {
      currentLoanTab = status;
      document.querySelectorAll('.loan-tab').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.status === status);
        if (btn.dataset.status === status) {
          btn.style.background = 'var(--blue)';
          btn.style.color = '#fff';
          btn.style.borderColor = 'var(--blue)';
        } else {
          btn.style.background = '';
          btn.style.color = '';
          btn.style.borderColor = '';
        }
      });
      renderLoansForTab(status);
    }

    async function loadBooks(tab) {
      tab = tab || currentLoanTab;
      const grid = document.getElementById('book-grid');
      grid.innerHTML = `<div class="loading"><div class="spinner"></div><p style="margin:8px 0 0;">${({ ru: 'Загрузка выдач...', kk: 'Берілімдер жүктелуде...', en: 'Loading loans...' })[ACCOUNT_LANG]}</p></div>`;

      try {
        const [activeResult, returnedResult] = await Promise.all([
          fetchLoans('active'),
          fetchLoans('returned'),
        ]);

        dashboardState.loans.active = activeResult.data;
        dashboardState.loans.returned = returnedResult.data;
        dashboardState.hasReaderProfile = dashboardState.summary?.reader?.linked === false
          ? false
          : (activeResult.hasReaderProfile || returnedResult.hasReaderProfile);

        renderLoansForTab(tab);
        renderActivity();
      } catch (error) {
        console.error(error);
        grid.innerHTML = `<div class="loading">${ACCOUNT_I18N.loadLoansError}</div>`;
      }
    }

    function updateReservationsMetric(reservations) {
      const totalEl = document.getElementById('reservations-count');
      const noteEl = document.getElementById('reservations-note');
      const readyCount = reservations.filter((reservation) => reservation?.status === 'READY').length;
      const pendingCount = reservations.filter((reservation) => reservation?.status === 'PENDING').length;

      if (totalEl) {
        totalEl.textContent = String(reservations.length);
      }

      if (noteEl) {
        if (readyCount > 0) {
          noteEl.textContent = ACCOUNT_LANG === 'kk'
            ? `${readyCount} беруге дайын`
            : ACCOUNT_LANG === 'en'
              ? `${readyCount} ready for pickup`
              : `${readyCount} готово к выдаче`;
          noteEl.className = 'metric-footnote good';
        } else if (pendingCount > 0) {
          noteEl.textContent = ACCOUNT_LANG === 'kk'
            ? `${pendingCount} сұрау күтілуде`
            : ACCOUNT_LANG === 'en'
              ? `${pendingCount} requests pending`
              : `${pendingCount} запросов в ожидании`;
          noteEl.className = 'metric-footnote';
        } else {
          noteEl.textContent = ACCOUNT_LANG === 'kk'
            ? 'Белсенді waitlist жоқ'
            : ACCOUNT_LANG === 'en'
              ? 'No active waitlist items'
              : 'Активных waitlist-элементов нет';
          noteEl.className = 'metric-footnote';
        }
      }
    }

    async function loadLoanSummary() {
      try {
        const response = await fetch(ACCOUNT_LOANS_SUMMARY_ENDPOINT, {
          headers: { Accept: 'application/json' },
        });
        if (response.ok) {
          const payload = await response.json();
          updateStats(payload?.data || {});
        }
      } catch (e) { /* silent */ }
    }

    function reservationStatusBadge(status) {
      const labels = {
        PENDING: ACCOUNT_LANG === 'kk' ? '⏳ Күтілуде' : ACCOUNT_LANG === 'en' ? '⏳ Pending' : '⏳ Ожидание',
        READY: ACCOUNT_LANG === 'kk' ? '✓ Беруге дайын' : ACCOUNT_LANG === 'en' ? '✓ Ready for pickup' : '✓ Готово к выдаче',
        FULFILLED: ACCOUNT_LANG === 'kk' ? '📚 Берілді' : ACCOUNT_LANG === 'en' ? '📚 Issued' : '📚 Выдано',
        CANCELLED: ACCOUNT_LANG === 'kk' ? '✕ Бас тартылды' : ACCOUNT_LANG === 'en' ? '✕ Cancelled' : '✕ Отменено',
        EXPIRED: ACCOUNT_LANG === 'kk' ? '⌛ Мерзімі аяқталды' : ACCOUNT_LANG === 'en' ? '⌛ Expired' : '⌛ Истекло',
      };
      const map = {
        'PENDING': { label: labels.PENDING, color: '#92400e', bg: '#fef3c7' },
        'READY': { label: labels.READY, color: '#065f46', bg: '#d1fae5' },
        'FULFILLED': { label: labels.FULFILLED, color: '#1e40af', bg: '#dbeafe' },
        'CANCELLED': { label: labels.CANCELLED, color: '#991b1b', bg: '#fee2e2' },
        'EXPIRED': { label: labels.EXPIRED, color: '#6b7280', bg: '#f3f4f6' },
      };
      const s = map[status] || { label: status, color: '#6b7280', bg: '#f3f4f6' };
      return `<span style="display:inline-block; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; color:${s.color}; background:${s.bg};">${s.label}</span>`;
    }

    function renderReservationCard(res) {
      const bookTitle = res.book?.title || ACCOUNT_I18N.book;
      const isbn = res.book?.isbn || '';
      const year = res.book?.publishYear || '';
      const reservedAt = res.reservedAt ? new Date(res.reservedAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
      const expiresAt = res.expiresAt ? new Date(res.expiresAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
      const isActive = res.status === 'PENDING' || res.status === 'READY';
      const canCancel = isActive;

      return `
        <article class="reservation-row" data-reservation-id="${escapeHtml(res.id)}">
          <div class="reservation-cover" style="${isActive ? 'background: linear-gradient(180deg, #0369a1 0%, #0284c7 100%);' : 'background: linear-gradient(180deg, #475569 0%, #64748b 100%); opacity: 0.85;'}">
            <small>${ACCOUNT_I18N.reservationLabel}</small>
            <span>${escapeHtml(bookTitle.substring(0, 28))}${bookTitle.length > 28 ? '…' : ''}</span>
          </div>
          <div class="reservation-meta">
            <div class="reservation-topline">
              <div>
                <div class="reservation-kicker">${ACCOUNT_I18N.reservationLabel}</div>
                <h3 class="reservation-title">${escapeHtml(bookTitle.substring(0, 42))}${bookTitle.length > 42 ? '…' : ''}</h3>
              </div>
              <div class="reservation-status">${reservationStatusBadge(res.status)}</div>
            </div>
            <div class="reservation-copy">${isbn ? `ISBN: ${escapeHtml(isbn)} · ` : ''}${year ? `${ACCOUNT_I18N.year}: ${escapeHtml(String(year))}` : ''}</div>
            <div class="reservation-facts">
              <span>${ACCOUNT_I18N.reservedAt}: ${reservedAt}</span>
              <span>${ACCOUNT_I18N.validUntil}: ${expiresAt}</span>
            </div>
            ${res.cancelReasonCode ? `<div class="reservation-copy" style="color:#991b1b;">${ACCOUNT_I18N.reason}: ${escapeHtml(res.cancelReasonCode)}</div>` : ''}
            ${canCancel ? `<div class="reservation-actions"><button class="btn btn-ghost" onclick="cancelReservation('${escapeHtml(res.id)}')" style="color:#991b1b; border-color:#fecaca;">${ACCOUNT_LANG === 'kk' ? 'Броньды тоқтату' : ACCOUNT_LANG === 'en' ? 'Cancel reservation' : 'Отменить бронь'}</button></div>` : ''}
          </div>
        </article>
      `;
    }

    async function cancelReservation(reservationId) {
      if (!confirm(ACCOUNT_I18N.cancelConfirm)) return;

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      try {
        const res = await fetch(`/api/v1/account/reservations/${encodeURIComponent(reservationId)}/cancel`, {
          method: 'POST',
          headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
          credentials: 'same-origin',
        });

        const json = await res.json();
        if (res.ok && json.success) {
          loadReservations();
        } else {
          alert(json.message || ACCOUNT_I18N.cancelFail);
        }
      } catch (e) {
        alert(ACCOUNT_I18N.cancelNetwork);
      }
    }

    async function loadReservations() {
      const grid = document.getElementById('reservations-grid');
      try {
        const response = await fetch(ACCOUNT_RESERVATIONS_ENDPOINT, {
          headers: { Accept: 'application/json' },
        });

        if (response.status === 401) {
          redirectToLogin();
          return;
        }

        if (!response.ok) throw new Error('Ошибка API');

        const payload = await response.json();
        const reservations = Array.isArray(payload?.data) ? payload.data : [];
        dashboardState.reservations = reservations;
        updateReservationsMetric(reservations);
        renderActivity();

        if (!reservations.length) {
          grid.innerHTML = `<div class="empty-state empty-state--compact"><span class="empty-state-icon">◔</span><p class="empty-state-title">${ACCOUNT_I18N.noReservationsTitle}</p><p class="empty-state-body">${ACCOUNT_I18N.noReservationsBody}</p><div class="empty-state-actions"><a href="${withLang('/catalog')}" class="btn btn-primary">${ACCOUNT_I18N.openCatalogPlain.replace(' →', '')}</a></div></div>`;
          return;
        }

        grid.innerHTML = reservations.map(renderReservationCard).join('');
      } catch (error) {
        console.error(error);
        grid.innerHTML = `<div class="loading">${ACCOUNT_I18N.loadReservationsError}</div>`;
      }
    }

    async function loadWorkbench() {
      const loading = document.getElementById('workbench-loading');
      const empty = document.getElementById('workbench-empty');
      const content = document.getElementById('workbench-content');

      try {
        const response = await fetch('/api/v1/shortlist/summary', {
          headers: { Accept: 'application/json' },
        });

        if (!response.ok) throw new Error('Shortlist summary failed');

        const payload = await response.json();
        const data = payload?.data || {};
        dashboardState.shortlist = data;
        renderActivity();

        loading.style.display = 'none';

        if ((data.total || 0) === 0) {
          empty.style.display = 'block';
          content.style.display = 'none';
          return;
        }

        empty.style.display = 'none';
        content.style.display = 'block';

        document.getElementById('wb-total').textContent = data.total || 0;
        document.getElementById('wb-books').textContent = data.books || 0;
        document.getElementById('wb-external').textContent = data.external || 0;

        const draftInfo = document.getElementById('workbench-draft-info');
        const draft = data.draft || {};
        let html = '';

        if (draft.persistent) {
          const savedLabel = ACCOUNT_LANG === 'kk' ? 'Аккаунтқа сақталған' : ACCOUNT_LANG === 'en' ? 'Saved to account' : 'Сохранено в аккаунт';
          html += `<div style="margin-bottom:8px;"><span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;background:rgba(20,105,109,.08);color:#14696d;border:1px solid rgba(20,105,109,.16);">${savedLabel}</span></div>`;
        }

        if (draft.title || draft.notes) {
          html += '<div style="padding:14px 18px; border-radius:8px; border:1px solid var(--border); background:#fff; box-shadow:none;">';
          if (draft.title) {
            html += `<div style="font-weight:700; font-size:16px; margin-bottom:4px;">${escapeHtml(draft.title)}</div>`;
          }
          if (draft.notes) {
            html += `<div style="color:var(--muted); font-size:13px; line-height:1.5;">${escapeHtml(draft.notes)}</div>`;
          }
          html += '</div>';
        }

        draftInfo.innerHTML = html;
      } catch (err) {
        loading.style.display = 'none';
        empty.style.display = 'block';
        console.error('Workbench load error:', err);
      }
    }

    (document.getElementById('logout-btn') || document.getElementById('shared-logout-btn'))?.addEventListener('click', async () => {
      try {
        await fetch('/api/v1/logout', {
          method: 'POST',
          headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
          },
        });
      } catch (_) {
        // Best-effort logout: local cleanup still happens below.
      }

      localStorage.removeItem(AUTH_USER_KEY);
      window.location.href = withLang('/login');
    });

    (async () => {
      const sessionUser = await getSessionUser();

      if (sessionUser) {
        localStorage.setItem(AUTH_USER_KEY, JSON.stringify(sessionUser));
        updateProfileFromAuth(sessionUser);
        await Promise.all([
          loadAccountSummary().catch((error) => {
            console.error(error);
          }),
          loadBooks('active'),
          loadLoanSummary(),
          loadReservations(),
          document.getElementById('workbench-section') ? loadWorkbench() : Promise.resolve(),
        ]);
        return;
      }

      // Temporary transition fallback: if stale local user exists, clear it and force real session login.
      if (getAuthUser()) {
        localStorage.removeItem(AUTH_USER_KEY);
      }

      redirectToLogin();
    })();
  </script>
</body>
</html>
