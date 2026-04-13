@php
  $lang = app()->getLocale();
  $catalogTitle = [
    'ru' => 'Каталог книг — Digital Library',
    'kk' => 'Кітаптар каталогы — Digital Library',
    'en' => 'Book catalog — Digital Library',
  ][$lang] ?? 'Каталог книг — Digital Library';
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ $catalogTitle }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Newsreader:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/shell.css">
  <style>
    :root {
      --bg: #f8f9fa;
      --surface: #ffffff;
      --surface-soft: #f3f4f5;
      --border: rgba(195, 198, 209, .55);
      --text: #191c1d;
      --muted: #43474f;
      --blue: #001e40;
      --cyan: #14696d;
      --violet: #453000;
      --pink: #2a1c00;
      --gold: #e9c176;
      --success: #14696d;
      --shadow: 0 12px 32px rgba(25, 28, 29, .04);
      --shadow-soft: 0 6px 16px rgba(25, 28, 29, .03);
      --radius-xl: 2px;
      --radius-lg: 2px;
      --radius-md: 2px;
      --container: 1280px;
    }

    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Manrope', system-ui, sans-serif;
      color: var(--text);
      background: var(--bg);
      background-attachment: scroll;
    }

    body.site-shell::before,
    body.site-shell::after {
      content: none;
      display: none;
    }

    .btn,
    .input,
    .select,
    .chip,
    .btn-clear-filters,
    .active-filter-chip,
    .active-filter-reset,
    .tag,
    .icon-btn,
    .page-btn,
    .book-card,
    .book-cover,
    .book-stage,
    .sort-box {
      border-radius: var(--radius-md) !important;
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

    .nav-links a:hover { color: var(--blue); }

    .nav-actions { display: flex; gap: 12px; }

    .btn {
      border: 0;
      cursor: pointer;
      font: inherit;
      border-radius: var(--radius-lg);
      padding: 14px 22px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: .25s ease;
      font-weight: 700;
    }

    .btn:hover { transform: none; }
    .btn-primary { color: white; background: linear-gradient(135deg, var(--blue), #003366); box-shadow: var(--shadow-soft); border-radius: 999px; }
    .btn-ghost { background: transparent; border: 1px solid var(--border); color: var(--text); box-shadow: none; }

    .page { padding: 34px 0 70px; }

    .hero {
      background: #ffffff;
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      border-radius: var(--radius-xl);
      padding: 30px;
      margin-bottom: 22px;
      overflow: hidden;
      position: relative;
      color: var(--text);
      transition: box-shadow .2s ease, border-color .16s ease;
    }

    .hero::before {
      content: none;
      display: none;
    }

    .hero:hover {
      transform: none;
      box-shadow: var(--shadow);
      border-color: var(--border);
    }

    .hero:hover::before {
      transform: none;
    }

    .eyebrow {
      display: inline-flex;
      padding: 0;
      border-radius: 0;
      background: transparent;
      border: 0;
      color: var(--cyan);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .14em;
      text-transform: uppercase;
    }

    .hero h1 {
      margin: 16px 0 12px;
      font-family: 'Newsreader', Georgia, serif;
      font-size: clamp(34px, 5vw, 58px);
      line-height: .98;
      letter-spacing: -1.8px;
      color: var(--blue);
    }

    .hero p {
      margin: 0;
      color: var(--muted);
      font-size: 17px;
      line-height: 1.8;
      max-width: 900px;
    }

    .search-wrap {
      display: grid;
      grid-template-columns: 1.2fr .8fr .6fr auto;
      gap: 12px;
      margin-top: 24px;
      padding: 8px;
      border-radius: var(--radius-xl);
      background: rgba(255,255,255,.82);
      border: 1px solid rgba(195,198,209,.45);
      box-shadow: var(--shadow-soft);
      transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .16s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .search-wrap:focus-within {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 18px 38px rgba(25,28,29,.06);
      border-color: rgba(0,30,64,.12);
    }

    .input, .select {
      width: 100%;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--text);
      border-radius: 999px;
      padding: 15px 16px;
      outline: none;
      font: inherit;
      box-shadow: none;
      transition: border-color .16s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .16s cubic-bezier(0.2, 0.8, 0.2, 1), background .16s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .input:focus, .select:focus {
      border-color: rgba(0,30,64,.14);
      box-shadow: 0 0 0 4px rgba(0,30,64,.06);
      background: rgba(255,255,255,.98);
    }

    .layout {
      display: grid;
      grid-template-columns: 280px 1fr;
      gap: 24px;
      align-items: start;
    }

    .card {
      background: rgba(255,255,255,.98);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      border-radius: var(--radius-xl);
    }

    .filters {
      padding: 24px;
      position: sticky;
      top: var(--shell-sticky-offset);
      background: linear-gradient(180deg, rgba(255,255,255,.99), rgba(247,248,249,.97));
      overflow: visible;
    }

    .filter-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      margin-bottom: 16px;
      flex-wrap: wrap;
    }

    .filter-title {
      margin: 0;
      font-size: 22px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .filter-badge {
      font-size: 12px;
      font-weight: 800;
      background: linear-gradient(135deg, var(--blue), var(--cyan));
      color: #fff;
      border-radius: 999px;
      min-width: 24px;
      height: 24px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0 7px;
      box-shadow: 0 8px 18px rgba(0,30,64,.12);
    }

    .btn-clear-filters {
      font-size: 13px;
      font-weight: 700;
      color: var(--muted);
      background: rgba(255,255,255,.82);
      border: 1px solid var(--border);
      border-radius: 999px;
      padding: 7px 14px;
      cursor: pointer;
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), color .18s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .btn-clear-filters:hover {
      color: #dc2626;
      border-color: rgba(220, 38, 38, .35);
      background: rgba(220, 38, 38, .04);
      transform: translate3d(0, -1px, 0);
    }

    .mobile-filter-toggle {
      display: none;
    }

    #filters-body {
      display: grid;
      gap: 14px;
      align-items: start;
    }

    .filter-group {
      margin: 0;
      padding-bottom: 16px;
      border-bottom: 1px solid rgba(195,198,209,.45);
    }

    .filter-group:last-child {
      padding-bottom: 0;
      border-bottom: 0;
    }

    .filter-label {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      font-size: 14px;
      font-weight: 800;
      margin-bottom: 12px;
      color: #334155;
    }

    .chips { display: flex; flex-wrap: wrap; gap: 10px; }

    .chip {
      padding: 10px 14px;
      border-radius: 999px;
      background: rgba(255,255,255,.92);
      border: 1px solid rgba(195,198,209,.7);
      font-size: 13px;
      font-weight: 700;
      color: #334155;
      box-shadow: var(--shadow-soft);
      cursor: pointer;
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
      font-family: inherit;
      line-height: 1.4;
    }

    .chip:hover {
      border-color: rgba(0,30,64,.22);
      background: rgba(0,30,64,.04);
      transform: translate3d(0, -1px, 0);
      box-shadow: 0 10px 22px rgba(25,28,29,.04);
    }

    .chip.active {
      background: linear-gradient(135deg, rgba(0,30,64,.08), rgba(20,105,109,.08));
      color: var(--blue);
      border-color: rgba(0,30,64,.14);
    }

    .preset-grid {
      display: grid;
      gap: 8px;
    }

    .preset-chip {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      width: 100%;
      padding: 12px 14px;
      border-radius: 14px;
      border: 1px solid rgba(195,198,209,.65);
      background: rgba(255,255,255,.9);
      color: var(--text);
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: var(--shadow-soft);
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .preset-chip span:last-child {
      color: var(--muted);
      font-size: 12px;
      font-weight: 600;
    }

    .preset-chip:hover,
    .preset-chip.active {
      transform: translate3d(0, -1px, 0);
      border-color: rgba(20,105,109,.24);
      background: linear-gradient(135deg, rgba(0,30,64,.04), rgba(20,105,109,.05));
      box-shadow: 0 12px 24px rgba(25,28,29,.05);
    }

    .filter-meta {
      margin-top: 10px;
      color: var(--muted);
      font-size: 12px;
      line-height: 1.55;
    }

    .range-row {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 10px;
      margin-top: 12px;
    }

    .range-field {
      display: grid;
      gap: 6px;
    }

    .range-field span {
      color: var(--muted);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
    }

    .range-input,
    .subject-search,
    .subject-select {
      width: 100%;
      padding: 11px 13px;
      border: 1px solid rgba(195,198,209,.72);
      border-radius: 12px;
      background: rgba(255,255,255,.94);
      font-size: 13px;
      font-family: inherit;
      color: var(--text);
      transition: border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .subject-search {
      margin-bottom: 10px;
    }

    .range-input:focus,
    .subject-search:focus,
    .subject-select:focus {
      outline: none;
      border-color: rgba(0,30,64,.18);
      box-shadow: 0 0 0 4px rgba(0,30,64,.05);
      background: #fff;
    }

    .filter-footer {
      display: grid;
      gap: 10px;
      margin-top: 18px;
    }

    .advanced-filters {
      margin-top: 6px;
      position: relative;
      display: grid;
      gap: 10px;
    }

    .advanced-toggle {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      border: 1px solid rgba(195,198,209,.72);
      background: #fff;
      color: var(--text);
      padding: 11px 12px;
      border-radius: var(--radius-md);
      cursor: pointer;
      font: inherit;
      font-weight: 700;
    }

    .advanced-toggle span:last-child {
      color: var(--muted);
      font-size: 16px;
      font-weight: 800;
      letter-spacing: 0;
      text-transform: none;
    }

    .advanced-panel {
      display: none;
      gap: 10px;
      border: 1px solid rgba(195,198,209,.72);
      border-radius: var(--radius-md);
      background: #fff;
      padding: 10px;
      position: absolute;
      top: calc(100% + 8px);
      left: 0;
      right: 0;
      z-index: 30;
      box-shadow: 0 18px 34px rgba(25, 28, 29, .12);
      max-height: min(65vh, 480px);
      overflow-y: auto;
    }

    .advanced-panel.open {
      display: grid;
    }

    .advanced-grid {
      display: grid;
      gap: 8px;
    }

    .advanced-field {
      display: grid;
      gap: 6px;
    }

    .advanced-field span {
      color: var(--muted);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
    }

    .advanced-input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid rgba(195,198,209,.72);
      border-radius: var(--radius-md);
      background: #fff;
      font-size: 13px;
      font-family: inherit;
      color: var(--text);
      transition: border-color .16s ease, box-shadow .16s ease;
    }

    .advanced-input:focus {
      outline: none;
      border-color: rgba(0,30,64,.18);
      box-shadow: 0 0 0 3px rgba(0,30,64,.05);
    }

    /* Stitch-like refinement overrides */
    .page { padding: 20px 0 48px; }
    .hero {
      border: 0;
      border-bottom: 1px solid #d9dee6;
      box-shadow: none;
      border-radius: 0;
      padding: 18px 0 20px;
      margin-bottom: 16px;
      background: transparent;
    }

    .eyebrow { display: none; }

    .hero h1 {
      margin: 0 0 8px;
      font-size: clamp(30px, 3.2vw, 36px);
      line-height: 1.14;
      letter-spacing: -.2px;
      color: #0d2c57;
      font-weight: 600;
    }

    .hero-accent {
      color: #0f7f8a;
      font-style: italic;
      font-weight: 500;
    }

    .hero p {
      max-width: 760px;
      font-size: 14px;
      line-height: 1.45;
      color: #4f5b69;
      margin-bottom: 12px;
    }

    .search-wrap {
      margin-top: 10px;
      padding: 0;
      border: 0;
      box-shadow: none;
      background: transparent;
      gap: 10px;
      grid-template-columns: 1fr 120px !important;
    }

    .search-wrap .input {
      border-radius: 0;
      border: 1px solid #d5dbe5;
      min-height: 44px;
      padding: 10px 14px;
    }

    .search-wrap .btn-primary {
      min-height: 44px;
      border-radius: 0;
      padding: 10px 14px;
      letter-spacing: .06em;
      text-transform: uppercase;
      font-size: 12px;
      background: #002450;
      box-shadow: none;
    }

    .layout {
      grid-template-columns: 292px 1fr;
      gap: 18px;
      align-items: start;
    }

    .filters {
      padding: 14px 18px 16px;
      border-radius: 0;
      background: #f6f7f9;
      border: 1px solid #e0e4eb;
      box-shadow: none;
      position: sticky;
      top: calc(var(--shell-sticky-offset) + 4px);
      max-height: calc(100vh - var(--shell-sticky-offset) - 16px);
      display: grid;
      grid-template-rows: auto minmax(0, 1fr) auto;
      gap: 10px;
    }

    .filter-header {
      margin-bottom: 10px;
      padding-bottom: 10px;
      border-bottom: 1px solid #dbe1ea;
      display: grid;
      grid-template-columns: minmax(0, 1fr) auto;
      align-items: center;
      column-gap: 8px;
      row-gap: 6px;
    }

    .filter-header-left {
      display: flex;
      align-items: center;
      gap: 6px;
      flex: 1;
      min-width: 0;
      overflow: hidden;
    }

    .filter-title {
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .22em;
      text-transform: uppercase;
      color: #0b2a55;
      font-family: 'Manrope', 'Inter', sans-serif;
      flex: 1;
      min-width: 0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .filter-badge {
      flex-shrink: 0;
    }

    .btn-clear-filters {
      margin: 0;
      justify-self: end;
      white-space: nowrap;
      border-radius: 0;
      padding: 6px 10px;
      font-size: 12px;
      line-height: 1;
    }

    .filter-title-icon {
      width: 22px;
      height: 22px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 0;
      background: transparent;
      color: #667282;
      cursor: pointer;
      margin-left: 4px;
      padding: 0;
      font-size: 15px;
      line-height: 1;
    }

    .filter-title-icon:hover {
      color: #0b2a55;
    }

    #filters-body {
      display: flex;
      flex-direction: column;
      gap: 12px;
      min-height: 0;
      overflow-y: auto;
      padding-right: 4px;
    }

    .filter-group {
      border-bottom: 1px solid #dce2ea;
      padding-bottom: 12px;
      margin: 0;
    }

    .filter-group--advanced { order: 0; }

    .range-row {
      display: flex;
      align-items: center;
      gap: 6px;
      margin-top: 0;
    }

    .year-sep {
      color: #8896a5;
      font-weight: 700;
      font-size: 13px;
      flex-shrink: 0;
    }

    .filter-label {
      margin-bottom: 10px;
      font-size: 11px;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: #667282;
      font-weight: 800;
    }

    .range-input,
    .subject-select,
    .institution-select {
      border-radius: 0;
      min-height: 34px;
      padding: 8px 10px;
      font-size: 12px;
      border: 1px solid #cfd7e2;
      background: #fff;
    }

    .subject-search { display: none; }

    .institution-select {
      width: 100%;
      font-family: inherit;
      color: #0b2a55;
    }

    .advanced-filters {
      margin: 0;
      display: grid;
      gap: 8px;
      border: 0;
      padding: 0;
    }

    .advanced-panel {
      display: none;
      gap: 8px;
      border: 1px solid #d7dee8;
      border-radius: 0;
      background: #fff;
      padding: 8px;
      position: static;
      box-shadow: none;
      max-height: none;
      overflow: visible;
    }

    .advanced-panel.open {
      display: grid;
      margin-bottom: 12px;
      border-bottom: 1px solid #dce2ea;
      padding-bottom: 10px;
    }

    .advanced-grid {
      display: grid;
      gap: 8px;
    }

    .advanced-field {
      display: grid;
      gap: 6px;
    }

    .advanced-field span {
      font-size: 10px;
      color: #6f7b89;
      letter-spacing: .14em;
      text-transform: uppercase;
      font-weight: 800;
    }

    .advanced-input {
      width: 100%;
      min-height: 34px;
      padding: 8px 10px;
      border: 1px solid #cfd7e2;
      border-radius: 0;
      background: #fff;
      font-size: 12px;
      font-family: inherit;
      color: #0b2a55;
    }

    .check-list {
      display: grid;
      gap: 8px;
    }

    .check-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 500;
      color: #5a6673;
    }

    .check-item input {
      width: 14px;
      height: 14px;
      accent-color: #0f7f8a;
    }

    .chips {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 6px;
    }

    .chip {
      border-radius: 0;
      min-height: 30px;
      padding: 6px 8px;
      text-align: center;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      border: 1px solid #cfd7e2;
      background: #fff;
      box-shadow: none;
    }

    .chip.active {
      background: #0b2a55;
      color: #fff;
      border-color: #0b2a55;
    }

    .filter-footer {
      margin-top: 8px;
      padding-top: 10px;
      border-top: 1px solid #dce2ea;
      background: linear-gradient(180deg, rgba(246,247,249,0), rgba(246,247,249,.95) 36%);
    }
    .filter-footer .btn-primary {
      border-radius: 0;
      background: #e9ecf1;
      color: #0b2a55;
      box-shadow: none;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: .12em;
      border: 1px solid #d5dce6;
      min-height: 36px;
    }

    .results {
      border-radius: 0;
      border: 1px solid #e0e4eb;
      box-shadow: none;
      padding: 16px 18px 20px;
      background: #fff;
    }

    .results-top {
      margin-bottom: 14px;
      padding-bottom: 10px;
      border-bottom: 1px solid #dde2ea;
      gap: 10px;
    }

    .results-top strong {
      font-size: 22px;
      font-family: 'Newsreader', Georgia, serif;
      font-weight: 600;
      color: #0d2c57;
    }

    .results-top p {
      margin-top: 4px;
      font-size: 13px;
      color: #5a6673;
    }

    .sort-box {
      min-width: 0;
      width: fit-content;
      border-radius: 0;
      padding: 8px 10px;
      border: 1px solid #ccd5e2;
      box-shadow: 0 4px 10px rgba(11, 42, 85, .06);
      background: #fdfefe;
      grid-template-columns: auto minmax(210px, 250px);
    }

    .sort-label {
      font-size: 10px;
      letter-spacing: .14em;
      color: #75808e;
    }

    .sort-select {
      min-width: 210px;
      border-radius: 0;
      padding: 8px 34px 8px 10px;
      font-size: 13px;
      border: 1px solid #c6d1e0;
      background: #fff;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.8);
      font-weight: 700;
    }

    .active-filters {
      gap: 6px;
      margin-bottom: 10px;
    }

    .active-filter-chip {
      border-radius: 0;
      padding: 4px 8px;
      font-size: 10px;
      letter-spacing: .03em;
      border-color: #bed2da;
      background: #e4f1f3;
      color: #0d5c67;
    }

    .active-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      align-items: center;
      margin-bottom: 18px;
    }
    .active-filter-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 14px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 700;
      background: rgba(0,30,64,.07);
      color: var(--blue);
      border: 1px solid rgba(0,30,64,.12);
      cursor: pointer;
      transition: background .15s;
    }
    .active-filter-chip:hover { background: rgba(0,30,64,.12); }
    .active-filter-reset {
      padding: 6px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
    }
    .active-filter-reset:hover { color: #334155; }

    .check-list { display: grid; gap: 8px; }

    .check-item {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 8px;
      color: #5a6673;
      font-weight: 500;
    }

    .check-item input {
      width: 14px;
      height: 14px;
      accent-color: #0f7f8a;
      cursor: pointer;
    }
    .check-item span:last-child { color: #5a6673; font-size: 13px; }

    .results {
      position: relative;
      padding: 24px;
      background: #ffffff;
      overflow: hidden;
      min-height: 640px;
    }

    .results::before {
      content: none;
      display: none;
      pointer-events: none;
    }

    .results-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      margin-bottom: 18px;
      padding-bottom: 14px;
      border-bottom: 1px solid rgba(195,198,209,.55);
      flex-wrap: wrap;
    }

    .results-top strong { font-size: 22px; }
    .results-top p { margin: 6px 0 0; color: var(--muted); }

    .sort-box {
      display: grid;
      grid-template-columns: auto minmax(240px, 1fr);
      align-items: center;
      gap: 12px;
      padding: 10px 12px;
      border-radius: var(--radius-md);
      background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(246,248,250,.95));
      border: 1px solid rgba(182,189,203,.65);
      box-shadow: 0 8px 20px rgba(25,28,29,.05);
      min-width: 360px;
    }

    .sort-label {
      color: var(--muted);
      font-weight: 700;
      font-size: 13px;
      letter-spacing: .04em;
      text-transform: uppercase;
      white-space: nowrap;
    }

    .sort-select-wrap {
      position: relative;
    }

    .sort-select-wrap::after {
      content: '▾';
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #4b5563;
      font-size: 13px;
      pointer-events: none;
    }

    .sort-select {
      width: 100%;
      min-width: 240px;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      border: 1px solid rgba(177,185,199,.78);
      background: #fff;
      color: var(--text);
      border-radius: var(--radius-md);
      padding: 10px 34px 10px 12px;
      font: inherit;
      font-weight: 700;
      line-height: 1.3;
      transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .sort-select:hover {
      border-color: rgba(0,30,64,.25);
      background: rgba(255,255,255,.99);
    }

    .sort-select:focus {
      outline: none;
      border-color: rgba(0,30,64,.35);
      box-shadow: 0 0 0 3px rgba(0,30,64,.08);
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 22px;
      align-items: stretch;
    }

    .book-card {
      position: relative;
      display: flex;
      flex-direction: column;
      min-height: 100%;
      padding: 18px;
      border-radius: calc(var(--radius-xl) + 2px);
      background: linear-gradient(180deg, rgba(255,255,255,.99), rgba(245,247,248,.96));
      border: 1px solid rgba(195,198,209,.7);
      box-shadow: 0 10px 24px rgba(25,28,29,.03);
      cursor: pointer;
      overflow: hidden;
      min-height: 820px;
    }

    .book-card::before {
      content: none;
    }

    .book-stage {
      position: relative;
      height: 420px;
      margin-bottom: 16px;
      perspective: none;
      transform-style: flat;
    }

    .book-body {
      position: absolute;
      inset: 0;
      z-index: 0;
      border-radius: var(--radius-md);
      padding: 16px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
      background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(250,250,250,.94));
      border: 1px solid rgba(195,198,209,.3);
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.4);
    }

    .book-body-copy {
      display: grid;
      gap: 12px;
      min-height: 0;
    }

    .book-body-label {
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: #7c6e54;
      margin-bottom: 8px;
    }

    .book-body-preview {
      font-size: 12px;
      line-height: 1.65;
      color: #625740;
      max-width: 100%;
    }

    .book-body-meta {
      display: grid;
      gap: 7px;
      align-content: start;
    }

    .book-body-meta-row {
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      gap: 12px;
      padding-top: 7px;
      border-top: 1px solid rgba(124, 110, 84, .18);
      font-size: 11px;
    }

    .book-body-meta-row span {
      color: #7c6e54;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
    }

    .book-body-meta-row strong {
      color: #403623;
      font-size: 11px;
      text-align: right;
      word-break: break-word;
      max-width: 52%;
    }

    .book-body-stat {
      align-self: flex-start;
      display: inline-flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 6px;
      padding: 6px 10px;
      border-radius: 999px;
      background: rgba(0,30,64,.05);
      color: var(--blue);
      font-size: 11px;
      font-weight: 800;
      white-space: nowrap;
    }

    .book-cover {
      position: absolute;
      inset: 0;
      z-index: 2;
      border-radius: var(--radius-md);
      padding: 16px;
      transform-origin: left center;
      transform-style: preserve-3d;
      backface-visibility: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.06), 0 12px 24px rgba(25,28,29,.1);
      isolation: isolate;
      transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    }

    .book-cover::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,.04), transparent 42%, rgba(0,0,0,.06) 100%);
      pointer-events: none;
    }

    .book-cover::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,0) 28%, rgba(0,0,0,.05) 100%);
      pointer-events: none;
    }

    .book-card:hover .book-cover { transform: rotateY(-100deg); }

    .cover-top {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 6px;
      max-width: 100%;
    }

    .cover-code {
      padding: 5px 10px;
      border-radius: 999px;
      background: rgba(255,255,255,.09);
      color: rgba(255,255,255,.88);
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      max-width: 100%;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .cover-bottom {
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      gap: 10px;
      min-height: 0;
    }

    .cover-isbn {
      color: rgba(255,255,255,.68);
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .12em;
      text-transform: uppercase;
    }

    .cover-isbn strong {
      display: block;
      margin-top: 4px;
      color: rgba(255,255,255,.92);
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: none;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .tone-navy { background: linear-gradient(180deg, #263d63 0%, #172943 100%); }
    .tone-wine { background: linear-gradient(180deg, #6f1f24 0%, #4f1115 100%); }
    .tone-forest { background: linear-gradient(180deg, #235544 0%, #15392e 100%); }
    .tone-wood { background: linear-gradient(180deg, #5a3d2d 0%, #39241a 100%); }

    .cover-year {
      align-self: flex-start;
      padding: 8px 12px;
      border-radius: 999px;
      background: rgba(255,255,255,.14);
      color: #fff;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .06em;
      backdrop-filter: blur(12px);
    }

    .cover-kicker {
      color: rgba(255,255,255,.6);
      font-size: 10px;
      letter-spacing: .18em;
      text-transform: uppercase;
      font-weight: 700;
      margin-bottom: 8px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      overflow-wrap: anywhere;
    }

    .cover-title {
      margin: 0;
      color: #f3d99d;
      font-family: 'Newsreader', Georgia, serif;
      font-size: clamp(1.7rem, 1.9vw, 2.2rem);
      line-height: 1.04;
      letter-spacing: -.8px;
      text-shadow: 1px 1px 0 rgba(0,0,0,.28);
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      overflow-wrap: anywhere;
      hyphens: auto;
    }

    .cover-subline {
      margin-top: 12px;
      color: rgba(255,255,255,.76);
      font-size: 11px;
      font-weight: 600;
      letter-spacing: .06em;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      overflow-wrap: anywhere;
    }

    .cover-hint {
      margin-top: 8px;
      color: rgba(255,255,255,.42);
      font-size: 9px;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
    }

    .meta-row {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 10px;
    }

    .tag {
      padding: 8px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 800;
      background: rgba(0,30,64,.07);
      color: var(--blue);
    }

    .tag.green {
      background: rgba(20,105,109,.08);
      color: var(--success);
    }

    .tag.subject {
      background: rgba(42,28,0,.07);
      color: var(--violet);
      font-size: 11px;
      padding: 5px 10px;
    }

    .active-subject-banner {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 18px;
      background: linear-gradient(135deg, rgba(0,30,64,.03), rgba(20,105,109,.05));
      border: 1px solid rgba(0,30,64,.10);
      border-radius: 12px;
      margin-bottom: 16px;
      font-size: 14px;
      color: var(--text);
    }

    .active-subject-banner strong {
      color: var(--blue);
    }

    .active-subject-banner .clear-btn {
      margin-left: auto;
      padding: 5px 12px;
      border-radius: 999px;
      border: 1px solid rgba(0,30,64,.12);
      background: #fff;
      font-size: 12px;
      font-weight: 700;
      color: var(--blue);
      cursor: pointer;
    }

    .book-copy {
      display: flex;
      flex: 1;
      flex-direction: column;
    }

    .book-title {
      margin: 0 0 8px;
      font-size: 22px;
      line-height: 1.15;
      min-height: 76px;
      overflow-wrap: anywhere;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .book-desc {
      margin: 0 0 14px;
      color: var(--muted);
      line-height: 1.7;
      font-size: 14px;
      min-height: 28px;
    }

    .book-info {
      display: grid;
      gap: 10px;
      margin-top: auto;
      margin-bottom: 16px;
    }

    .book-info div {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 10px;
      font-size: 14px;
      padding-bottom: 10px;
      border-bottom: 1px solid var(--border);
    }

    .book-info div:last-child { border-bottom: 0; padding-bottom: 0; }
    .book-info span:first-child { color: var(--muted); }
    .book-info span:last-child { font-weight: 700; text-align: right; max-width: 58%; word-break: break-word; overflow-wrap: anywhere; }

    .book-actions {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 10px;
      margin-top: auto;
    }

    .icon-btn {
      width: 50px;
      height: 48px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: none;
      font-size: 18px;
      cursor: pointer;
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), border-color 0.2s ease, background-color 0.2s ease, color 0.2s ease, box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .icon-btn:hover {
      background: rgba(20,105,109,.06);
      border-color: var(--cyan);
      transform: translate3d(0, -1px, 0);
      box-shadow: 0 10px 22px rgba(25,28,29,.04);
    }

    .icon-btn.shortlisted {
      background: rgba(20,105,109,.08);
      border-color: var(--cyan);
      color: var(--cyan);
    }

    .catalog-state-card {
      grid-column: 1 / -1;
      display: grid;
      place-items: center;
      gap: 10px;
      min-height: 220px;
      padding: 28px 24px;
      border-radius: 18px;
      border: 1px solid rgba(195,198,209,.65);
      background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
      box-shadow: var(--shadow-soft);
      text-align: center;
    }

    .catalog-state-card strong {
      color: var(--blue);
      font-size: 18px;
    }

    .catalog-state-card p {
      margin: 0;
      max-width: 440px;
      color: var(--muted);
      line-height: 1.7;
    }

    .catalog-state-card--error {
      border-color: rgba(186,26,26,.18);
      background: linear-gradient(180deg, rgba(255,248,248,.98), rgba(255,240,240,.95));
    }

    .catalog-state-card--error strong,
    .catalog-state-card--error p {
      color: #8a1d1d;
    }

    .catalog-state-card__icon {
      display: inline-grid;
      place-items: center;
      width: 52px;
      height: 52px;
      border-radius: 16px;
      background: rgba(0,30,64,.06);
      color: var(--blue);
      font-size: 24px;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.7);
    }

    .pagination {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 24px;
      flex-wrap: wrap;
    }

    .page-btn {
      min-width: 46px;
      height: 46px;
      border-radius: 8px;
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: var(--shadow-soft);
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
    }

    .page-btn:hover {
      border-color: var(--blue);
      background: rgba(0,30,64,.04);
      transform: translate3d(0, -1px, 0);
    }

    .page-btn.active {
      color: white;
      border-color: transparent;
      background: linear-gradient(135deg, var(--blue), var(--cyan));
    }

    @media (max-width: 1200px) {
      .grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 920px) {
      .layout { grid-template-columns: 1fr; }
      .filters {
        position: static;
        top: auto;
        max-height: none;
        grid-template-rows: auto auto auto;
      }
      #filters-body {
        overflow: visible;
        padding-right: 0;
      }
      .grid { grid-template-columns: 1fr; }
      .search-wrap { grid-template-columns: 1fr auto !important; }
      .sort-box {
        width: 100%;
        min-width: 0;
        grid-template-columns: 1fr;
      }
      .mobile-toggle { display: inline-grid; place-items: center; min-width: 44px; min-height: 44px; }
    }

    @media (max-width: 560px) {
      .container { width: min(100% - 20px, var(--container)); }
      .hero, .filters, .results { padding: 18px; }
      .grid { grid-template-columns: 1fr; }
      .range-row { flex-wrap: wrap; }
      .book-stage { height: 380px; }
      .book-actions { grid-template-columns: 1fr; }
      .icon-btn { width: 100%; height: 50px; min-height: 44px; }
      .search-wrap { grid-template-columns: 1fr; }
      .nav { min-height: 64px; }
      .brand-text { font-size: 13px; }
      .brand-text small { font-size: 10.5px; }
      .hero h1 { font-size: 24px; }
    }

    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
<body class="site-shell">
  @include('partials.navbar', ['activePage' => 'catalog'])

  <main class="page">
    <div class="container">
      <section class="hero">
        <div class="eyebrow">{{ ['ru' => 'Каталог университетской библиотеки', 'kk' => 'Университет кітапханасының каталогы', 'en' => 'University library catalog'][$lang] }}</div>
        <h1>{{ ['ru' => 'Digital Library', 'kk' => 'Digital Library', 'en' => 'Digital Library'][$lang] }} <span class="hero-accent">Academic Catalog</span></h1>
        <p>{{ ['ru' => 'Удобный поиск по фонду библиотеки с фильтрами по категориям, году издания, языку и доступности.', 'kk' => 'Категория, басылым жылы, тіл және қолжетімділік бойынша сүзгілері бар ыңғайлы іздеу.', 'en' => 'Search the library collection with filters for category, publication year, language, and availability.'][$lang] }}</p>

        <div class="search-wrap" style="grid-template-columns: 1fr auto;">
          <input class="input" id="search-input" type="text" placeholder="{{ ['ru' => 'Поиск по названию, автору, ISBN или ключевому слову', 'kk' => 'Атауы, авторы, ISBN немесе кілтсөз бойынша іздеу', 'en' => 'Search by title, author, ISBN, or keyword'][$lang] }}" onkeydown="if(event.key==='Enter'){applyFilters()}" />
          <button class="btn btn-primary" onclick="applyFilters()">{{ ['ru' => 'Найти', 'kk' => 'Іздеу', 'en' => 'Search'][$lang] }}</button>
        </div>
      </section>

      <section class="layout">
        <aside class="card filters" id="filters-panel">
          <div class="filter-header">
            <div class="filter-header-left">
              <span class="filter-title">{{ ['ru' => 'Расширенные фильтры', 'kk' => 'Кеңейтілген сүзгілер', 'en' => 'Advanced Filters'][$lang] }}</span>
              <button type="button" class="filter-title-icon" onclick="toggleAdvancedFilters()" aria-controls="advanced-filters-panel" aria-expanded="false">⚙</button>
              <span id="advanced-toggle-state" style="display:none">⚙</span>
              <span id="filter-count-badge" class="filter-badge" style="display:none;"></span>
            </div>
            <button type="button" class="btn-clear-filters" id="clear-filters-btn" style="display:none;" onclick="clearAllFilters()">✕ {{ ['ru' => 'Сбросить', 'kk' => 'Тазарту', 'en' => 'Reset'][$lang] }}</button>
          </div>
          <div class="advanced-filters" id="advanced-filters-wrap">
            <div class="advanced-panel" id="advanced-filters-panel">
              <div class="advanced-grid">
                <label class="advanced-field">
                  <span>{{ ['ru' => 'Название', 'kk' => 'Атауы', 'en' => 'Title'][$lang] }}</span>
                  <input class="advanced-input" id="adv-title" type="text" placeholder="{{ ['ru' => 'Например: Статистика', 'kk' => 'Мысалы: Статистика', 'en' => 'Example: Statistics'][$lang] }}" onkeydown="if(event.key==='Enter'){applyFilters()}">
                </label>
                <label class="advanced-field">
                  <span>{{ ['ru' => 'Автор', 'kk' => 'Автор', 'en' => 'Author'][$lang] }}</span>
                  <input class="advanced-input" id="adv-author" type="text" placeholder="{{ ['ru' => 'ФИО автора', 'kk' => 'Автор аты-жөні', 'en' => 'Author name'][$lang] }}" onkeydown="if(event.key==='Enter'){applyFilters()}">
                </label>
                <label class="advanced-field">
                  <span>{{ ['ru' => 'Издатель', 'kk' => 'Баспа', 'en' => 'Publisher'][$lang] }}</span>
                  <input class="advanced-input" id="adv-publisher" type="text" placeholder="{{ ['ru' => 'Название издателя', 'kk' => 'Баспа атауы', 'en' => 'Publisher name'][$lang] }}" onkeydown="if(event.key==='Enter'){applyFilters()}">
                </label>
                <label class="advanced-field">
                  <span>ISBN</span>
                  <input class="advanced-input" id="adv-isbn" type="text" placeholder="978..." onkeydown="if(event.key==='Enter'){applyFilters()}">
                </label>
                <label class="advanced-field">
                  <span>{{ ['ru' => 'УДК', 'kk' => 'ӘОЖ', 'en' => 'UDC'][$lang] }}</span>
                  <input class="advanced-input" id="adv-udc" type="text" placeholder="5, 004, ..." onkeydown="if(event.key==='Enter'){applyFilters()}">
                </label>
              </div>
            </div>
          </div>

          <div id="filters-body">

          <div class="filter-group filter-group--year">
            <span class="filter-label">{{ ['ru' => 'Период публикации', 'kk' => 'Жарияланым кезеңі', 'en' => 'Publication period'][$lang] }}</span>
            <div class="range-row">
              <input type="number" id="year-from-input" class="range-input" min="1900" max="2100" placeholder="2020">
              <span class="year-sep">—</span>
              <input type="number" id="year-to-input" class="range-input" min="1900" max="2100" placeholder="2026">
            </div>
          </div>

          <div class="filter-group filter-group--availability">
            <span class="filter-label">{{ ['ru' => 'Тип ресурса', 'kk' => 'Ресурс түрі', 'en' => 'Resource type'][$lang] }}</span>
            <div class="check-list">
              <label class="check-item"><input type="checkbox" id="filter-available-only" checked onchange="applyFilters()"> <span>{{ ['ru' => 'Электронные (E-Book/PDF)', 'kk' => 'Электронды (E-Book/PDF)', 'en' => 'Digital (E-Book/PDF)'][$lang] }}</span></label>
              <label class="check-item"><input type="checkbox" id="filter-physical-only" onchange="applyFilters()"> <span>{{ ['ru' => 'Физический фонд', 'kk' => 'Физикалық қор', 'en' => 'Physical Collection'][$lang] }}</span></label>
            </div>
          </div>

          <div class="filter-group filter-group--language">
            <span class="filter-label">{{ ['ru' => 'Язык', 'kk' => 'Тіл', 'en' => 'Language'][$lang] }}</span>
            <div class="chips" id="language-chips">
              <button type="button" class="chip active" data-lang="">{{ ['ru' => 'Все', 'kk' => 'Барлығы', 'en' => 'All'][$lang] }}</button>
              <button type="button" class="chip" data-lang="en">ENGLISH</button>
              <button type="button" class="chip" data-lang="kk">KAZAKH</button>
              <button type="button" class="chip" data-lang="ru">RUSSIAN</button>
            </div>
          </div>

          <div class="filter-group filter-group--subject" id="subject-filter-group">
            <span class="filter-label">{{ ['ru' => 'Классификация УДК', 'kk' => 'ӘОЖ классификациясы', 'en' => 'UDC classification'][$lang] }}</span>
            <select class="subject-select" id="subject-select" onchange="applyFilters()">
              <option value="">{{ ['ru' => 'Все категории', 'kk' => 'Барлық санат', 'en' => 'All categories'][$lang] }}</option>
            </select>
          </div>

          <div class="filter-group filter-group--institution">
            <span class="filter-label">{{ ['ru' => 'Институциональная коллекция', 'kk' => 'Институционалдық жинақ', 'en' => 'Institutional collection'][$lang] }}</span>
            <select class="institution-select" id="institution-select">
              <option value="college_library">{{ ['ru' => 'Библиотека Колледжа', 'kk' => 'Колледж кітапханасы', 'en' => 'College Library'][$lang] }}</option>
              <option value="economic_library">{{ ['ru' => 'Экономическая библиотека', 'kk' => 'Экономикалық кітапхана', 'en' => 'Economic Library'][$lang] }}</option>
              <option value="technology_library">{{ ['ru' => 'Технологическая библиотека', 'kk' => 'Технологиялық кітапхана', 'en' => 'Technology Library'][$lang] }}</option>
              <option value="ktslib">KTSLIB</option>
            </select>
          </div>

          </div>{{-- /filters-body --}}

          <div class="filter-footer">
            <button class="btn btn-primary" onclick="applyFilters()">{{ ['ru' => 'Применить фильтры', 'kk' => 'Сүзгілерді қолдану', 'en' => 'Apply filters'][$lang] }}</button>
          </div>
        </aside>

        <div class="card results">
          <div id="active-subject-banner"></div>
          <div class="results-top">
            <div>
              <strong id="results-count">{{ ['ru' => 'Найдено 0 книг', 'kk' => '0 кітап табылды', 'en' => 'Found 0 books'][$lang] }}</strong>
              <p>{{ ['ru' => 'Подборка учебной и научной литературы по выбранным параметрам.', 'kk' => 'Таңдалған параметрлер бойынша оқу және ғылыми әдебиеттер топтамасы.', 'en' => 'A filtered selection of course and research literature.'][$lang] }}</p>
            </div>
            <div class="sort-box">
              <span class="sort-label">{{ ['ru' => 'Сортировка', 'kk' => 'Сұрыптау', 'en' => 'Sort by'][$lang] }}</span>
              <span class="sort-select-wrap">
              <select class="sort-select" id="sort-select" onchange="applyFilters()">
                <option value="popular">{{ ['ru' => 'Сначала популярные', 'kk' => 'Алдымен танымалдары', 'en' => 'Most relevant first'][$lang] }}</option>
                <option value="newest">{{ ['ru' => 'Сначала новые', 'kk' => 'Алдымен жаңалары', 'en' => 'Newest first'][$lang] }}</option>
                <option value="title">{{ ['ru' => 'По названию', 'kk' => 'Атауы бойынша', 'en' => 'By title'][$lang] }}</option>
                <option value="author">{{ ['ru' => 'По автору', 'kk' => 'Автор бойынша', 'en' => 'By author'][$lang] }}</option>
              </select>
              </span>
            </div>
          </div>

          <div id="active-filters" class="active-filters" style="display:none"></div>
          <div class="grid" id="catalog-grid"></div>

          <div class="pagination" id="pagination"></div>
        </div>
      </section>
    </div>
  </main>

  @include('partials.footer')

  <script>
    const API_ENDPOINT = '/api/v1/catalog-db';
    const SHORTLIST_API = '/api/v1/shortlist';
    const CATALOG_LANG = @json($lang);
    const CATALOG_I18N_MAP = {
      ru: {
        untitled: 'Отсутствует название', authorMissing: 'Отсутствует автор', publisherMissing: 'Отсутствует издатель', languageMissing: 'Отсутствует язык',
        yearMissing: 'Отсутствует год', isbnMissing: 'Отсутствует ISBN', udcMissing: 'Отсутствует УДК', locationMissing: 'Отсутствует локация', trackMissing: 'Отсутствует направление',
        copies: 'экз.', unavailable: 'Нет в наличии', author: 'Автор', year: 'Год', language: 'Язык', viewBook: 'Смотреть книгу',
        addShortlist: 'В подборку', removeShortlist: 'Убрать из подборки', updateCatalog: 'Обновляем каталог', noResultBadge: 'Нет результата', noBooks: 'Книги не найдены', tryChange: 'Попробуйте изменить параметры поиска.',
        foundZero: 'Найдено 0 книг', foundMany: 'Найдено {count} книг', retry: 'Повтор', loadError: 'Ошибка загрузки каталога', refreshPage: 'Попробуйте обновить страницу.',
        yearLabel: 'Год', availableOnly: 'В наличии', resetAll: 'Сбросить все', subjectFilter: 'Фильтр по направлению', clear: 'Сбросить',
        faculties: 'Факультеты', departments: 'Кафедры', specializations: 'Специальности', login: 'Войти', shareCopied: 'Ссылка скопирована', shareView: 'Поделиться видом', insideRecord: 'Внутри книги', previewAvailable: 'Доступно {available} из {total}. При открытии записи можно проверить экземпляры, ISBN и тематическую привязку.', previewUnavailable: 'Экземпляры временно недоступны. Откройте запись, чтобы проверить библиографию и расположение.', loadingBody: 'Подбираем более точную выдачу по активным фильтрам.', isbnLabel: 'ISBN', udcLabel: 'УДК', publisherLabel: 'Издатель', copiesLabel: 'Экземпляры', trackLabel: 'Направление', locationLabel: 'Локация', coverHint: 'Открыть превью'
      },
      kk: {
        untitled: 'Атауы жоқ', authorMissing: 'Автор жоқ', publisherMissing: 'Баспа жоқ', languageMissing: 'Тіл жоқ',
        yearMissing: 'Жылы жоқ', isbnMissing: 'ISBN жоқ', udcMissing: 'ӘОЖ жоқ', locationMissing: 'Орналасуы жоқ', trackMissing: 'Бағыты жоқ',
        copies: 'дана', unavailable: 'Қолжетімсіз', author: 'Автор', year: 'Жыл', language: 'Тіл', viewBook: 'Кітапты ашу',
        addShortlist: 'Топтамаға қосу', removeShortlist: 'Топтамадан алу', updateCatalog: 'Каталог жаңартылуда', noResultBadge: 'Нәтиже жоқ', noBooks: 'Кітаптар табылмады', tryChange: 'Іздеу параметрлерін өзгертіп көріңіз.',
        foundZero: '0 кітап табылды', foundMany: '{count} кітап табылды', retry: 'Қайталау', loadError: 'Каталогты жүктеу қатесі', refreshPage: 'Бетті жаңартып көріңіз.',
        yearLabel: 'Жыл', availableOnly: 'Қолда бар', resetAll: 'Барлығын тазарту', subjectFilter: 'Бағыт бойынша сүзгі', clear: 'Тазарту',
        faculties: 'Факультеттер', departments: 'Кафедралар', specializations: 'Мамандандырулар', login: 'Кіру', shareCopied: 'Сілтеме көшірілді', shareView: 'Көріністі бөлісу', insideRecord: 'Кітап ішінде', previewAvailable: '{total} дананың {available}-і қолжетімді. Жазбаны ашқанда даналар, ISBN және тақырыптық байланыс көрінеді.', previewUnavailable: 'Даналар уақытша қолжетімсіз. Жазбаны ашып библиография мен орналасуды тексеріңіз.', loadingBody: 'Белсенді сүзгілер бойынша дәлірек нәтижелер жиналуда.', isbnLabel: 'ISBN', udcLabel: 'ӘОЖ', publisherLabel: 'Баспа', copiesLabel: 'Даналар', trackLabel: 'Бағыт', locationLabel: 'Орналасуы', coverHint: 'Превью ашу'
      },
      en: {
        untitled: 'Missed title', authorMissing: 'Missed author', publisherMissing: 'Missed publisher', languageMissing: 'Missed language',
        yearMissing: 'Missed year', isbnMissing: 'Missed ISBN', udcMissing: 'Missed UDC', locationMissing: 'Missed location', trackMissing: 'Missed track',
        copies: 'copies', unavailable: 'Unavailable', author: 'Author', year: 'Year', language: 'Language', viewBook: 'Open book',
        addShortlist: 'Add to shortlist', removeShortlist: 'Remove from shortlist', updateCatalog: 'Refreshing catalog', noResultBadge: 'No result', noBooks: 'No books found', tryChange: 'Try adjusting the search parameters.',
        foundZero: 'Found 0 books', foundMany: 'Found {count} books', retry: 'Retry', loadError: 'Catalog load error', refreshPage: 'Try refreshing the page.',
        yearLabel: 'Year', availableOnly: 'Available', resetAll: 'Reset all', subjectFilter: 'Subject filter', clear: 'Clear',
        faculties: 'Faculties', departments: 'Departments', specializations: 'Specializations', login: 'Sign in', shareCopied: 'Link copied', shareView: 'Share view', insideRecord: 'Inside the book', previewAvailable: '{available} of {total} copies are ready now. Open the record for copies, ISBN, and subject placement.', previewUnavailable: 'Copies are currently unavailable. Open the record to review bibliography and location.', loadingBody: 'Refining the catalog view around your active filters.', isbnLabel: 'ISBN', udcLabel: 'UDC', publisherLabel: 'Publisher', copiesLabel: 'Copies', trackLabel: 'Track', locationLabel: 'Location', coverHint: 'Open preview'
      }
    };
    const CATALOG_I18N = CATALOG_I18N_MAP[CATALOG_LANG] || CATALOG_I18N_MAP.ru;
    const ADV_FILTER_UI = @php
      $advFilterMap = [
        'ru' => ['on' => 'ВКЛ', 'off' => 'ВЫКЛ', 'title' => 'Название', 'author' => 'Автор', 'publisher' => 'Издатель', 'isbn' => 'ISBN', 'udc' => 'УДК'],
        'kk' => ['on' => 'ҚОСУ', 'off' => 'ӨШІРУ', 'title' => 'Атауы', 'author' => 'Автор', 'publisher' => 'Баспа', 'isbn' => 'ISBN', 'udc' => 'ӘОЖ'],
        'en' => ['on' => 'ON', 'off' => 'OFF', 'title' => 'Title', 'author' => 'Author', 'publisher' => 'Publisher', 'isbn' => 'ISBN', 'udc' => 'UDC'],
      ];
      $advFilterUI = $advFilterMap[$lang] ?? $advFilterMap['en'];
    @endphp @json($advFilterUI);

    function withLang(path) {
      const url = new URL(path, window.location.origin);
      if (CATALOG_LANG !== 'ru' && !url.searchParams.has('lang')) {
        url.searchParams.set('lang', CATALOG_LANG);
      }
      return `${url.pathname}${url.search}`;
    }
    let currentPage = 1;
    let totalPages = 1;
    let activeSubjectId = '';
    let activeSubjectLabel = '';
    let subjectsData = null;
    let shortlistState = {};

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function getActiveLanguage() {
      const active = document.querySelector('#language-chips .chip.active');
      return active ? active.dataset.lang || '' : '';
    }

    function getYearParams() {
      const customFrom = document.getElementById('year-from-input')?.value?.trim();
      const customTo = document.getElementById('year-to-input')?.value?.trim();
      if (customFrom || customTo) {
        return { year_from: customFrom || '', year_to: customTo || '' };
      }

      return {};
    }

    function getAdvancedFilters() {
      return {
        title: document.getElementById('adv-title')?.value?.trim() || '',
        author: document.getElementById('adv-author')?.value?.trim() || '',
        publisher: document.getElementById('adv-publisher')?.value?.trim() || '',
        isbn: document.getElementById('adv-isbn')?.value?.trim() || '',
        udc: document.getElementById('adv-udc')?.value?.trim() || '',
      };
    }

    function hasAdvancedFilters() {
      const advanced = getAdvancedFilters();
      return Object.values(advanced).some(Boolean);
    }

    function toggleAdvancedFilters(forceOpen = null) {
      const panel = document.getElementById('advanced-filters-panel');
      const state = document.getElementById('advanced-toggle-state');
      const toggleButtons = document.querySelectorAll('.filter-title-icon');
      if (!panel || !state) return;
      const open = forceOpen === null ? !panel.classList.contains('open') : Boolean(forceOpen);
      panel.classList.toggle('open', open);
      state.textContent = open ? '▴' : '⚙';
      toggleButtons.forEach((btn) => btn.setAttribute('aria-expanded', open ? 'true' : 'false'));
    }

    function setChipValue(selector, dataKey, value = '') {
      document.querySelectorAll(selector).forEach((chip) => {
        chip.classList.toggle('active', (chip.dataset[dataKey] || '') === value);
      });
    }

    function filterSubjectOptions(value) {
      const query = (value || '').trim().toLowerCase();
      const select = document.getElementById('subject-select');
      if (!select) return;

      Array.from(select.options).forEach((option, index) => {
        if (index === 0 || !query) {
          option.hidden = false;
          return;
        }

        const haystack = `${option.dataset.label || ''} ${option.textContent || ''}`.toLowerCase();
        option.hidden = !haystack.includes(query);
      });
    }

    function syncCatalogUrl(params) {
      const url = new URL(window.location.href);
      ['q', 'page', 'sort', 'language', 'year_from', 'year_to', 'available_only', 'subject_id', 'subject_label', 'title', 'author', 'publisher', 'isbn', 'udc'].forEach((key) => {
        url.searchParams.delete(key);
      });

      ['q', 'sort', 'language', 'year_from', 'year_to', 'available_only', 'subject_id', 'title', 'author', 'publisher', 'isbn', 'udc'].forEach((key) => {
        const value = params.get(key);
        if (value) {
          url.searchParams.set(key, value);
        }
      });

      if (currentPage > 1) {
        url.searchParams.set('page', String(currentPage));
      }

      if (activeSubjectLabel) {
        url.searchParams.set('subject_label', activeSubjectLabel);
      }

      history.replaceState(null, '', `${url.pathname}${url.search}`);
    }

    function formatBookData(book) {
      const classification = Array.isArray(book.classification) ? book.classification : [];
      const locations = Array.isArray(book.availability?.locations) ? book.availability.locations : [];
      const specialization = classification.find(c => c.sourceKind === 'specialization');
      const department = classification.find(c => c.sourceKind === 'department');
      const subject = classification.find(c => c.sourceKind === 'subject');
      const firstLocation = locations.find((location) => {
        return location?.institutionUnit?.name || location?.campus?.name || location?.servicePoint?.name;
      });
      const locationName = firstLocation
        ? (firstLocation.institutionUnit?.name || firstLocation.campus?.name || firstLocation.servicePoint?.name || CATALOG_I18N.locationMissing)
        : CATALOG_I18N.locationMissing;

      return {
        title: book.title?.display || book.title?.raw || CATALOG_I18N.untitled,
        author: book.primaryAuthor || CATALOG_I18N.authorMissing,
        publisher: book.publisher?.name || CATALOG_I18N.publisherMissing,
        year: book.publicationYear || CATALOG_I18N.yearMissing,
        language: book.language?.raw || book.language?.code || CATALOG_I18N.languageMissing,
        available: book.copies?.available || 0,
        total: book.copies?.total || 0,
        isbn: book.isbn?.raw || CATALOG_I18N.isbnMissing,
        udc: book.udc?.raw || subject?.label || CATALOG_I18N.udcMissing,
        location: locationName,
        id: book.id || '',
        specialization: specialization ? specialization.label : '',
        department: department ? department.label : '',
        subjectId: specialization ? specialization.id : (department ? department.id : ''),
      };
    }

    function renderBookCard(book, index = 0) {
      const data = formatBookData(book);
      const isAvailable = data.available > 0;
      const identifier = data.isbn || data.id;
      const tone = ['tone-navy', 'tone-wine', 'tone-forest', 'tone-wood'][index % 4];
      const subjectBadge = data.specialization
        ? `<span class="tag subject" title="${escapeHtml(data.specialization)}">${escapeHtml(data.specialization.length > 25 ? data.specialization.substring(0, 25) + '…' : data.specialization)}</span>`
        : (data.department ? `<span class="tag subject" title="${escapeHtml(data.department)}">${escapeHtml(data.department.length > 25 ? data.department.substring(0, 25) + '…' : data.department)}</span>` : '');
      const isbnValue = data.isbn || '—';
      const udcValue = data.udc || '—';
      const shortIsbn = isbnValue.length > 18 ? `${isbnValue.substring(0, 18)}…` : isbnValue;

      const isShortlisted = shortlistState[identifier] || false;

      const trackValue = data.specialization || data.department || CATALOG_I18N.trackMissing;
      const previewSummary = isAvailable
        ? CATALOG_I18N.previewAvailable.replace('{available}', data.available).replace('{total}', data.total)
        : CATALOG_I18N.previewUnavailable;

      return `
        <article class="book-card" onclick="goToBook('${escapeHtml(identifier)}')">
          <div class="book-stage">
            <div class="book-body">
              <div style="display: grid; gap: 12px; min-height: 0;">
                <div>
                  <div class="book-body-label">${CATALOG_I18N.insideRecord}</div>
                  <div style="font-size: 12px; line-height: 1.6; color: #625740; max-width: 100%; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">${escapeHtml(previewSummary)}</div>
                </div>
                <div style="display: grid; gap: 7px; align-content: start;">
                  <div class="book-body-meta-row"><span>${CATALOG_I18N.publisherLabel}</span><strong>${escapeHtml(data.publisher.substring(0, 40))}</strong></div>
                  <div class="book-body-meta-row"><span>${CATALOG_I18N.copiesLabel}</span><strong>${isAvailable ? `${data.available}/${data.total}` : CATALOG_I18N.unavailable}</strong></div>
                  <div class="book-body-meta-row"><span>${CATALOG_I18N.locationLabel}</span><strong>${escapeHtml(String(data.location).substring(0, 40))}</strong></div>
                  <div class="book-body-meta-row"><span>${CATALOG_I18N.trackLabel}</span><strong>${escapeHtml(String(trackValue).substring(0, 34))}</strong></div>
                </div>
              </div>
              <span style="align-self: flex-start; display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 2px; background: rgba(0,30,64,.05); color: var(--blue); font-size: 11px; font-weight: 800; white-space: normal;">${escapeHtml(data.location)} · ${escapeHtml(data.language)} · ${escapeHtml(String(data.year))}</span>
            </div>
            <div class="book-cover ${tone}">
              <div class="cover-top">
                <span class="cover-year">${escapeHtml(String(data.year))}</span>
                <span class="cover-code">${CATALOG_I18N.udcLabel}: ${escapeHtml(udcValue)}</span>
              </div>
              <div class="cover-bottom">
                <div>
                <div class="cover-kicker">${escapeHtml(data.publisher.substring(0, 24))}</div>
                <h3 class="cover-title">${escapeHtml(data.title.substring(0, 42))}</h3>
                <div class="cover-subline">${escapeHtml(data.author.substring(0, 36))}</div>
                <div class="cover-hint">${CATALOG_I18N.coverHint}</div>
                </div>
                <div class="cover-isbn">${CATALOG_I18N.isbnLabel}<strong>${escapeHtml(shortIsbn)}</strong></div>
              </div>
            </div>
          </div>
          <div class="meta-row">
            <span class="tag">${escapeHtml(String(data.year))}</span>
            <span class="tag ${isAvailable ? 'green' : ''}">${isAvailable ? `${data.available} ${CATALOG_I18N.copies}` : CATALOG_I18N.unavailable}</span>
            ${subjectBadge}
          </div>
          <div class="book-copy">
            <h3 class="book-title">${escapeHtml(data.title)}</h3>
            <p class="book-desc">${escapeHtml(data.publisher)}</p>
            <div class="book-info">
              <div><span>${CATALOG_I18N.author}</span><span>${escapeHtml(data.author.substring(0, 40))}</span></div>
              <div><span>${CATALOG_I18N.language}</span><span>${escapeHtml(data.language)}</span></div>
              <div><span>${CATALOG_I18N.locationLabel}</span><span>${escapeHtml(data.location)}</span></div>
            </div>
          </div>
          <div class="book-actions">
            <button class="btn btn-primary" onclick="event.stopPropagation(); goToBook('${escapeHtml(identifier)}')">${CATALOG_I18N.viewBook}</button>
            <button class="icon-btn ${isShortlisted ? 'shortlisted' : ''}" onclick="event.stopPropagation(); toggleShortlist(this, ${JSON.stringify(JSON.stringify(data))})" title="${isShortlisted ? CATALOG_I18N.removeShortlist : CATALOG_I18N.addShortlist}" aria-label="${isShortlisted ? CATALOG_I18N.removeShortlist : CATALOG_I18N.addShortlist}">${isShortlisted ? '★' : '☆'}</button>
          </div>
        </article>
      `;
    }

    async function loadCatalog() {
      const searchInput = document.getElementById('search-input');
      const sortSelect = document.getElementById('sort-select');
      const grid = document.getElementById('catalog-grid');
      const resultsCount = document.getElementById('results-count');

      try {
        grid.innerHTML = `<div class="catalog-state-card"><div class="catalog-state-card__icon"><div class="spinner"></div></div><strong>${CATALOG_I18N.updateCatalog}</strong><p>${CATALOG_I18N.loadingBody}</p></div>`;

        const params = new URLSearchParams();
        if (searchInput.value) params.set('q', searchInput.value);
        params.set('page', currentPage);
        params.set('limit', 12);
        params.set('sort', sortSelect.value);

        const lang = getActiveLanguage();
        if (lang) params.set('language', lang);

        const yearParams = getYearParams();
        if (yearParams.year_from) params.set('year_from', yearParams.year_from);
        if (yearParams.year_to) params.set('year_to', yearParams.year_to);

        const advanced = getAdvancedFilters();
        if (advanced.title) params.set('title', advanced.title);
        if (advanced.author) params.set('author', advanced.author);
        if (advanced.publisher) params.set('publisher', advanced.publisher);
        if (advanced.isbn) params.set('isbn', advanced.isbn);
        if (advanced.udc) params.set('udc', advanced.udc);

        const availableOnly = document.getElementById('filter-available-only');
        if (availableOnly && availableOnly.checked) params.set('available_only', '1');

        if (activeSubjectId) params.set('subject_id', activeSubjectId);
        syncCatalogUrl(params);

        const response = await fetch(`${API_ENDPOINT}?${params}`, {
          headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('API Error');

        const data = await response.json();
        const books = data.data || [];
        const meta = data.meta || {};

        if (books.length === 0) {
          grid.innerHTML = `<div class="catalog-state-card"><div class="catalog-state-card__icon">🔎</div><strong>${CATALOG_I18N.noBooks}</strong><p>${CATALOG_I18N.tryChange}</p><button type="button" class="btn btn-ghost" onclick="clearAllFilters()">${CATALOG_I18N.resetAll}</button></div>`;
          resultsCount.textContent = CATALOG_I18N.foundZero;
          document.getElementById('pagination').innerHTML = '';
        } else {
          const identifiers = books.map(b => {
            const d = formatBookData(b);
            return d.isbn || d.id;
          }).filter(Boolean);
          await loadShortlistState(identifiers);
          grid.innerHTML = books.map((item, index) => renderBookCard(item, index)).join('');
          resultsCount.textContent = CATALOG_I18N.foundMany.replace('{count}', meta.total || books.length);
          totalPages = meta.totalPages || 1;
          renderPagination();
        }
        renderActiveFilters();
        updateFilterBadge();
      } catch (err) {
        grid.innerHTML = `<div class="catalog-state-card catalog-state-card--error"><div class="catalog-state-card__icon">!</div><strong>${CATALOG_I18N.loadError}</strong><p>${CATALOG_I18N.refreshPage}</p><button type="button" class="btn btn-ghost" onclick="loadCatalog()">${CATALOG_I18N.retry}</button></div>`;
        console.error(err);
      }
    }

    function renderActiveFilters() {
      const container = document.getElementById('active-filters');
      if (!container) return;
      const chips = [];
      const searchVal = document.getElementById('search-input')?.value?.trim();
      if (searchVal) chips.push({ label: `«${searchVal}»`, clear: () => { document.getElementById('search-input').value = ''; } });

      const lang = getActiveLanguage();
      if (lang) {
        const labels = { ru: 'Русский', kk: 'Қазақша', en: 'English' };
        chips.push({ label: labels[lang] || lang, clear: () => { setChipValue('#language-chips .chip', 'lang', ''); } });
      }

      const yp = getYearParams();
      if (yp.year_from || yp.year_to) {
        chips.push({
          label: `${yp.year_from || '…'}–${yp.year_to || '…'}`,
          clear: () => {
            const yearFromInput = document.getElementById('year-from-input');
            const yearToInput = document.getElementById('year-to-input');
            if (yearFromInput) yearFromInput.value = '';
            if (yearToInput) yearToInput.value = '';
          }
        });
      }

      const avail = document.getElementById('filter-available-only');
      if (avail?.checked) chips.push({ label: CATALOG_I18N.availableOnly, clear: () => { avail.checked = false; } });
      if (activeSubjectLabel) chips.push({ label: activeSubjectLabel, clear: () => { activeSubjectId = ''; activeSubjectLabel = ''; } });

      const advanced = getAdvancedFilters();
      if (advanced.title) chips.push({ label: `${ADV_FILTER_UI.title}: ${advanced.title}`, clear: () => { const el = document.getElementById('adv-title'); if (el) el.value = ''; } });
      if (advanced.author) chips.push({ label: `${ADV_FILTER_UI.author}: ${advanced.author}`, clear: () => { const el = document.getElementById('adv-author'); if (el) el.value = ''; } });
      if (advanced.publisher) chips.push({ label: `${ADV_FILTER_UI.publisher}: ${advanced.publisher}`, clear: () => { const el = document.getElementById('adv-publisher'); if (el) el.value = ''; } });
      if (advanced.isbn) chips.push({ label: `${ADV_FILTER_UI.isbn}: ${advanced.isbn}`, clear: () => { const el = document.getElementById('adv-isbn'); if (el) el.value = ''; } });
      if (advanced.udc) chips.push({ label: `${ADV_FILTER_UI.udc}: ${advanced.udc}`, clear: () => { const el = document.getElementById('adv-udc'); if (el) el.value = ''; } });

      if (chips.length === 0) {
        container.style.display = 'none';
        return;
      }

      container.style.display = 'flex';
      container.innerHTML = chips.map(c => `<button type="button" class="active-filter-chip" onclick="this._clear()">${escapeHtml(c.label)} ✕</button>`).join('') +
        `<button type="button" class="active-filter-reset" onclick="clearAllFilters()">${CATALOG_I18N.resetAll}</button>`;
      container.querySelectorAll('.active-filter-chip').forEach((el, i) => {
        el._clear = () => {
          chips[i].clear();
          applyFilters();
        };
      });
    }

    function clearAllFilters() {
      const searchInput = document.getElementById('search-input');
      const yearFromInput = document.getElementById('year-from-input');
      const yearToInput = document.getElementById('year-to-input');
      const subjectSearch = document.getElementById('subject-search');
      const subjectSelect = document.getElementById('subject-select');
      const sortSelect = document.getElementById('sort-select');
      const avail = document.getElementById('filter-available-only');
      const advTitle = document.getElementById('adv-title');
      const advAuthor = document.getElementById('adv-author');
      const advPublisher = document.getElementById('adv-publisher');
      const advIsbn = document.getElementById('adv-isbn');
      const advUdc = document.getElementById('adv-udc');

      if (searchInput) searchInput.value = '';
      if (yearFromInput) yearFromInput.value = '';
      if (yearToInput) yearToInput.value = '';
      if (subjectSearch) subjectSearch.value = '';
      if (subjectSelect) subjectSelect.value = '';
      if (sortSelect) sortSelect.value = 'popular';
      if (avail) avail.checked = false;
      if (advTitle) advTitle.value = '';
      if (advAuthor) advAuthor.value = '';
      if (advPublisher) advPublisher.value = '';
      if (advIsbn) advIsbn.value = '';
      if (advUdc) advUdc.value = '';

      setChipValue('#language-chips .chip', 'lang', '');
      activeSubjectId = '';
      activeSubjectLabel = '';
      toggleAdvancedFilters(false);
      updateSubjectBanner();
      applyFilters();
    }

    function renderPagination() {
      const pagination = document.getElementById('pagination');
      if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
      }

      let html = '';
      if (currentPage > 1) {
        html += `<button class="page-btn" onclick="changePage(${currentPage - 1})">←</button>`;
      }

      const start = Math.max(1, currentPage - 2);
      const end = Math.min(totalPages, currentPage + 2);

      if (start > 1) {
        html += `<button class="page-btn" onclick="changePage(1)">1</button>`;
        if (start > 2) html += '<span style="color:var(--muted);">...</span>';
      }

      for (let i = start; i <= end; i++) {
        html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
      }

      if (end < totalPages) {
        if (end < totalPages - 1) html += '<span style="color:var(--muted);">...</span>';
        html += `<button class="page-btn" onclick="changePage(${totalPages})">${totalPages}</button>`;
      }

      if (currentPage < totalPages) {
        html += `<button class="page-btn" onclick="changePage(${currentPage + 1})">→</button>`;
      }

      pagination.innerHTML = html;
    }

    function changePage(page) {
      currentPage = page;
      loadCatalog();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function goToBook(identifier) {
      if (!identifier) return;
      window.location.href = withLang(`/book/${encodeURIComponent(identifier)}`);
    }

    async function toggleShortlist(btn, dataJson) {
      const data = JSON.parse(dataJson);
      const identifier = data.isbn || data.id;
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

      if (shortlistState[identifier]) {
        // Remove
        try {
          const res = await fetch(`${SHORTLIST_API}/${encodeURIComponent(identifier)}`, {
            method: 'DELETE',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
            credentials: 'same-origin',
          });
          if (res.ok) {
            shortlistState[identifier] = false;
            btn.textContent = '☆';
            btn.classList.remove('shortlisted');
            btn.title = CATALOG_I18N.addShortlist;
          }
        } catch (e) { console.error(e); }
      } else {
        // Add
        try {
          const res = await fetch(SHORTLIST_API, {
            method: 'POST',
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
            },
            credentials: 'same-origin',
            body: JSON.stringify({
              identifier: identifier,
              title: data.title,
              author: data.author,
              publisher: data.publisher,
              year: String(data.year || ''),
              language: data.language,
              isbn: data.isbn,
              available: data.available,
              total: data.total,
            }),
          });
          if (res.ok || res.status === 201 || res.status === 409) {
            shortlistState[identifier] = true;
            btn.textContent = '★';
            btn.classList.add('shortlisted');
            btn.title = CATALOG_I18N.removeShortlist;
          }
        } catch (e) { console.error(e); }
      }
    }

    async function loadShortlistState(identifiers) {
      if (!identifiers.length) return;
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      try {
        const res = await fetch(`${SHORTLIST_API}/check`, {
          method: 'POST',
          headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
          },
          credentials: 'same-origin',
          body: JSON.stringify({ identifiers }),
        });
        if (res.ok) {
          const json = await res.json();
          shortlistState = { ...shortlistState, ...(json.data || {}) };
        }
      } catch (e) { console.warn('Shortlist check failed:', e); }
    }

    function applyFilters() {
      currentPage = 1;
      loadCatalog();
      updateFilterBadge();
    }

    function countActiveFilters() {
      let count = 0;
      if (document.getElementById('filter-available-only')?.checked) count++;
      if (getActiveLanguage()) count++;
      const yearParams = getYearParams();
      if (yearParams.year_from || yearParams.year_to) count++;
      if (activeSubjectId || document.getElementById('subject-select')?.value) count++;
      const advanced = getAdvancedFilters();
      if (advanced.title) count++;
      if (advanced.author) count++;
      if (advanced.publisher) count++;
      if (advanced.isbn) count++;
      if (advanced.udc) count++;
      return count;
    }

    function updateFilterBadge() {
      const count = countActiveFilters();
      const badge = document.getElementById('filter-count-badge');
      const clearBtn = document.getElementById('clear-filters-btn');
      const mobileCount = document.getElementById('mobile-filter-count');
      if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-flex' : 'none';
      }
      if (clearBtn) {
        clearBtn.style.display = count > 0 ? 'block' : 'none';
      }
      if (mobileCount) {
        mobileCount.textContent = count > 0 ? `(${count})` : '';
      }
    }

    function toggleFilters() {
      const body = document.getElementById('filters-body');
      body?.classList.toggle('open');
    }

    @if(session('library.user'))
    document.getElementById('shared-logout-btn')?.addEventListener('click', async () => {
      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        await fetch('/api/v1/logout', {
          method: 'POST',
          headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
        });
      } catch (_) {}
      localStorage.removeItem('library.auth.user');
      window.location.href = withLang('/login');
    });
    @endif

    // Chip filter behavior — click activates chip and reloads catalog
    document.querySelectorAll('#language-chips .chip').forEach(chip => {
      chip.addEventListener('click', function() {
        this.parentElement.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        applyFilters();
      });
    });

    // Keep resource-type selection in 3 valid modes only:
    // digital only, physical only, or both. Prevent "none selected".
    (function initResourceTypeModes() {
      const digital = document.getElementById('filter-available-only');
      const physical = document.getElementById('filter-physical-only');
      if (!digital || !physical) return;

      const ensureAtLeastOneChecked = (changed) => {
        if (!digital.checked && !physical.checked) {
          changed.checked = true;
        }
      };

      digital.addEventListener('change', () => ensureAtLeastOneChecked(digital));
      physical.addEventListener('change', () => ensureAtLeastOneChecked(physical));
    })();

    ['year-from-input', 'year-to-input'].forEach((id) => {
      const input = document.getElementById(id);
      input?.addEventListener('input', () => {
        updateFilterBadge();
      });
      input?.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
          event.preventDefault();
          applyFilters();
        }
      });
    });

    ['adv-title', 'adv-author', 'adv-publisher', 'adv-isbn', 'adv-udc'].forEach((id) => {
      const input = document.getElementById(id);
      input?.addEventListener('input', () => {
        if ((input.value || '').trim()) {
          toggleAdvancedFilters(true);
        }
        updateFilterBadge();
      });
    });

    document.addEventListener('click', (event) => {
      const panel = document.getElementById('advanced-filters-panel');
      const toggles = Array.from(document.querySelectorAll('.filter-title-icon'));
      if (!panel || !toggles.length) return;
      const target = event.target;
      if (!(target instanceof Element)) return;
      if (!panel.classList.contains('open')) return;
      const clickedToggle = toggles.some((toggle) => toggle.contains(target));
      if (panel.contains(target) || clickedToggle) return;
      toggleAdvancedFilters(false);
    });

    // Read URL params for deep-linking from discovery pages
    (function() {
      const urlParams = new URLSearchParams(window.location.search);
      const urlQ = urlParams.get('q');
      const urlSort = urlParams.get('sort');
      const urlLanguage = urlParams.get('language');
      const urlYearFrom = urlParams.get('year_from');
      const urlYearTo = urlParams.get('year_to');
      const urlAvailableOnly = urlParams.get('available_only');
      const urlSubjectId = urlParams.get('subject_id');
      const urlSubjectLabel = urlParams.get('subject_label');
      const urlTitle = urlParams.get('title');
      const urlAuthor = urlParams.get('author');
      const urlPublisher = urlParams.get('publisher');
      const urlIsbn = urlParams.get('isbn');
      const urlUdc = urlParams.get('udc');
      const urlPage = Number(urlParams.get('page') || '1');

      if (urlQ && document.getElementById('search-input')) {
        document.getElementById('search-input').value = urlQ;
      }
      if (urlSort && document.getElementById('sort-select')) {
        document.getElementById('sort-select').value = urlSort;
      }
      if (urlLanguage) {
        setChipValue('#language-chips .chip', 'lang', urlLanguage);
      }
      if (urlAvailableOnly === '1' && document.getElementById('filter-available-only')) {
        document.getElementById('filter-available-only').checked = true;
      }
      if (urlYearFrom || urlYearTo) {
        const yearFromInput = document.getElementById('year-from-input');
        const yearToInput = document.getElementById('year-to-input');
        if (yearFromInput) yearFromInput.value = urlYearFrom || '';
        if (yearToInput) yearToInput.value = urlYearTo || '';
      }
      if (urlPage > 1) {
        currentPage = urlPage;
      }
      if (urlSubjectId) {
        activeSubjectId = urlSubjectId;
        activeSubjectLabel = urlSubjectLabel ? decodeURIComponent(urlSubjectLabel) : '';
      }
      if (urlTitle && document.getElementById('adv-title')) document.getElementById('adv-title').value = urlTitle;
      if (urlAuthor && document.getElementById('adv-author')) document.getElementById('adv-author').value = urlAuthor;
      if (urlPublisher && document.getElementById('adv-publisher')) document.getElementById('adv-publisher').value = urlPublisher;
      if (urlIsbn && document.getElementById('adv-isbn')) document.getElementById('adv-isbn').value = urlIsbn;
      if (urlUdc && document.getElementById('adv-udc')) document.getElementById('adv-udc').value = urlUdc;
      if (urlTitle || urlAuthor || urlPublisher || urlIsbn || urlUdc) {
        toggleAdvancedFilters(true);
      }
    })();

    function updateSubjectBanner() {
      const banner = document.getElementById('active-subject-banner');
      if (!banner) return;
      if (activeSubjectId && activeSubjectLabel) {
        banner.innerHTML = `<div class="active-subject-banner">
          <span>📚</span>
          <span>${CATALOG_I18N.subjectFilter}: <strong>${escapeHtml(activeSubjectLabel)}</strong></span>
          <button class="clear-btn" onclick="clearSubjectFilter()">✕ ${CATALOG_I18N.clear}</button>
        </div>`;
      } else {
        banner.innerHTML = '';
      }
    }

    function clearSubjectFilter() {
      activeSubjectId = '';
      activeSubjectLabel = '';
      const sel = document.getElementById('subject-select');
      const search = document.getElementById('subject-search');
      if (sel) sel.value = '';
      if (search) search.value = '';
      updateSubjectBanner();
      const url = new URL(window.location);
      url.searchParams.delete('subject_id');
      url.searchParams.delete('subject_label');
      history.replaceState(null, '', url.toString());
      applyFilters();
    }

    async function loadSubjects() {
      try {
        const res = await fetch('/api/v1/subjects', { headers: { Accept: 'application/json' } });
        if (!res.ok) return;
        subjectsData = await res.json();
        const sel = document.getElementById('subject-select');
        if (!sel) return;

        const addGroup = (label, items) => {
          if (!items || items.length === 0) return;
          const group = document.createElement('optgroup');
          group.label = label;
          items.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = `${item.label} (${item.documentCount})`;
            opt.dataset.label = item.label;
            group.appendChild(opt);
          });
          sel.appendChild(group);
        };

        addGroup(CATALOG_I18N.faculties, subjectsData.faculties);
        addGroup(CATALOG_I18N.departments, subjectsData.departments);
        addGroup(CATALOG_I18N.specializations, subjectsData.specializations);

        if (activeSubjectId) {
          sel.value = activeSubjectId;
          if (!activeSubjectLabel) {
            const opt = sel.querySelector(`option[value="${activeSubjectId}"]`);
            if (opt) activeSubjectLabel = opt.dataset.label;
          }
        }
        updateSubjectBanner();
      } catch (e) {
        console.warn('Failed to load subjects:', e);
      }
    }

    // Handle subject select change
    document.getElementById('subject-select')?.addEventListener('change', function() {
      activeSubjectId = this.value;
      const opt = this.options[this.selectedIndex];
      activeSubjectLabel = opt && opt.dataset ? (opt.dataset.label || '') : '';
      updateSubjectBanner();
    });

    // Initial load
    loadSubjects();
    loadCatalog();
    updateFilterBadge();
  </script>
</body>
</html>
