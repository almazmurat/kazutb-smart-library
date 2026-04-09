@extends('layouts.public', ['activePage' => 'shortlist'])

@php
  $lang = app()->getLocale();
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $withLang = function (string $path, array $query = []) use ($lang): string {
      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }

      $queryString = http_build_query(array_filter($query, static fn ($value) => $value !== null && $value !== ''));
      return $path . ($queryString !== '' ? ('?' . $queryString) : '');
  };

  $copy = [
      'ru' => [
          'title' => 'Подборка литературы — Digital Library',
          'hero_eyebrow' => 'Подборка литературы',
          'hero_title' => 'Черновик списка литературы',
          'hero_body' => 'Собирайте книги из каталога и электронные ресурсы для подготовки силлабуса. Выберите формат — скопируйте или распечатайте готовый список.',
          'loading' => 'Загрузка подборки...',
          'empty_icon' => 'Подборка',
          'empty_title' => 'Подборка пока пуста',
          'empty_body' => 'Добавляйте издания из каталога или прикрепляйте записи из электронных ресурсов. Сохранённый набор появится здесь для экспорта.',
          'open_catalog' => 'Открыть каталог',
          'resources' => 'Ресурсы',
          'browse_subjects' => 'Поиск по направлениям',
          'back_to_account' => '← Вернуться в кабинет',
          'draft_title_label' => 'Название черновика',
          'draft_title_placeholder' => 'Например: Литература для дисциплины «Информатика»',
          'draft_notes_label' => 'Заметки',
          'draft_notes_placeholder' => 'Заметки для себя: семестр, группа, комментарии...',
          'selected_sources' => 'Выбранные источники',
          'items_suffix' => 'в подборке',
          'print' => '🖨 Печать',
          'clear' => '🗑 Очистить',
          'books_title' => 'Основная литература',
          'external_title' => 'Электронные ресурсы',
          'bibliography_title' => '📄 Список литературы',
          'bibliography_body' => 'Готовый текст для вставки в силлабус или документ',
          'format' => 'Формат:',
          'format_numbered' => 'Нумерованный список',
          'format_grouped' => 'По разделам',
          'format_syllabus' => 'Для силлабуса',
          'copy_text' => '📋 Скопировать текст',
      ],
      'kk' => [
          'title' => 'Әдебиет іріктемесі — Digital Library',
          'hero_eyebrow' => 'Әдебиет іріктемесі',
          'hero_title' => 'Әдебиет тізімінің жұмыс нұсқасы',
          'hero_body' => 'Силлабус дайындау үшін каталогтағы кітаптарды және электрондық ресурстарды жинаңыз. Қажетті форматты таңдап, дайын тізімді көшіріп не басып шығарыңыз.',
          'loading' => 'Іріктеме жүктелуде...',
          'empty_icon' => 'Іріктеме',
          'empty_title' => 'Іріктеме әзірге бос',
          'empty_body' => 'Каталогтан басылымдарды қосыңыз немесе электрондық ресурстардан қажетті материалдарды тіркеңіз. Сақталған жиынтық экспорт үшін осында көрсетіледі.',
          'open_catalog' => 'Каталогты ашу',
          'resources' => 'Ресурстар',
          'browse_subjects' => 'Тақырыптарды қарау',
          'back_to_account' => '← Кабинетке оралу',
          'draft_title_label' => 'Жұмыс нұсқасының атауы',
          'draft_title_placeholder' => 'Мысалы: «Информатика» пәніне арналған әдебиет',
          'draft_notes_label' => 'Ескертпелер',
          'draft_notes_placeholder' => 'Өзіңізге арналған белгі: семестр, топ, түсініктеме...',
          'selected_sources' => 'Таңдалған дереккөздер',
          'items_suffix' => 'іріктемеде',
          'print' => '🖨 Басып шығару',
          'clear' => '🗑 Тазарту',
          'books_title' => 'Негізгі әдебиет',
          'external_title' => 'Электрондық ресурстар',
          'bibliography_title' => '📄 Әдебиет тізімі',
          'bibliography_body' => 'Силлабусқа немесе құжатқа енгізуге дайын мәтін',
          'format' => 'Формат:',
          'format_numbered' => 'Нөмірленген тізім',
          'format_grouped' => 'Бөлімдер бойынша',
          'format_syllabus' => 'Силлабус үшін',
          'copy_text' => '📋 Мәтінді көшіру',
      ],
      'en' => [
          'title' => 'Shortlist — Digital Library',
          'hero_eyebrow' => 'Teaching shortlist',
          'hero_title' => 'Draft reading list',
          'hero_body' => 'Collect catalog titles and electronic resources for syllabus preparation. Choose the output format, then copy or print the final list.',
          'loading' => 'Loading shortlist...',
          'empty_icon' => 'Shortlist',
          'empty_title' => 'The shortlist is empty',
          'empty_body' => 'Add titles from the catalog or attach relevant entries from research resources. The saved set will appear here for export.',
          'open_catalog' => 'Open catalog',
          'resources' => 'Resources',
          'browse_subjects' => 'Browse subjects',
          'back_to_account' => '← Return to account',
          'draft_title_label' => 'Draft title',
          'draft_title_placeholder' => 'For example: Reading list for “Computer Science”',
          'draft_notes_label' => 'Notes',
          'draft_notes_placeholder' => 'Working notes: semester, group, comments...',
          'selected_sources' => 'Selected sources',
          'items_suffix' => 'in shortlist',
          'print' => '🖨 Print',
          'clear' => '🗑 Clear',
          'books_title' => 'Core readings',
          'external_title' => 'Electronic resources',
          'bibliography_title' => '📄 Bibliography',
          'bibliography_body' => 'Ready-to-use text for a syllabus or working document',
          'format' => 'Format:',
          'format_numbered' => 'Numbered list',
          'format_grouped' => 'Grouped by section',
          'format_syllabus' => 'Syllabus format',
          'copy_text' => '📋 Copy text',
      ],
  ][$lang];
@endphp

@section('title', $copy['title'])

@section('content')
  <section class="page-hero">
    <div class="container">
      <div class="eyebrow eyebrow--violet">{{ $copy['hero_eyebrow'] }}</div>
      <h1>{{ $copy['hero_title'] }}</h1>
      <p>{{ $copy['hero_body'] }}</p>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div id="shortlist-loading" class="shortlist-state shortlist-state--loading">
        <div class="shortlist-spinner"></div>
        <p>{{ $copy['loading'] }}</p>
      </div>

      <div id="shortlist-empty" class="shortlist-state" style="display:none;">
        <div class="shortlist-state-icon">{{ $copy['empty_icon'] }}</div>
        <h2>{{ $copy['empty_title'] }}</h2>
        <p>{{ $copy['empty_body'] }}</p>
        <div class="shortlist-state-actions">
          <a href="{{ $withLang('/catalog') }}" class="btn btn-primary">{{ $copy['open_catalog'] }}</a>
          <a href="{{ $withLang('/resources') }}" class="btn btn-ghost">{{ $copy['resources'] }}</a>
          <a href="{{ $withLang('/discover') }}" class="btn btn-ghost">{{ $copy['browse_subjects'] }}</a>
        </div>
      </div>

      <div id="shortlist-content" style="display:none;">
        {{-- Cabinet navigation --}}
        @if(session('library.user'))
        <div style="margin-bottom:18px;">
          <a href="{{ $withLang('/account') }}" style="color:var(--blue); font-size:14px; font-weight:600; text-decoration:none;">{{ $copy['back_to_account'] }}</a>
        </div>
        @endif

        {{-- Draft metadata block --}}
        <div id="draft-meta-block" class="draft-meta-block">
          <div id="draft-persistence-badge" class="draft-persistence-badge" style="display:none;"></div>
          <div class="draft-meta-fields">
            <div class="draft-field-group">
              <label for="draft-title" class="draft-label">{{ $copy['draft_title_label'] }}</label>
              <input type="text" id="draft-title" class="draft-input" placeholder="{{ $copy['draft_title_placeholder'] }}" maxlength="500">
            </div>
            <div class="draft-field-group">
              <label for="draft-notes" class="draft-label">{{ $copy['draft_notes_label'] }}</label>
              <textarea id="draft-notes" class="draft-textarea" placeholder="{{ $copy['draft_notes_placeholder'] }}" maxlength="2000" rows="2"></textarea>
            </div>
          </div>
          <div id="draft-save-status" class="draft-save-status"></div>
        </div>

        {{-- Header with stats and actions --}}
        <div class="shortlist-header">
          <div>
            <h2 style="margin:0 0 4px; font-size:22px;">{{ $copy['selected_sources'] }}</h2>
            <p style="margin:0; color:var(--muted); font-size:14px;">
              <span id="shortlist-count">0</span> <span id="shortlist-count-label">0</span> {{ $copy['items_suffix'] }}
              <span id="shortlist-type-summary" style="margin-left:4px;"></span>
            </p>
          </div>
          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn-ghost" onclick="window.print()" title="{{ $copy['print'] }}">{{ $copy['print'] }}</button>
            <button class="btn btn-ghost" onclick="clearShortlist()" style="color:var(--danger);">{{ $copy['clear'] }}</button>
          </div>
        </div>

        {{-- Items grouped by type --}}
        <div id="shortlist-books-section" class="shortlist-type-section" style="display:none;">
          <div class="shortlist-type-heading">
            <span class="shortlist-type-icon">📚</span>
            <h3>{{ $copy['books_title'] }}</h3>
            <span id="shortlist-books-count" class="shortlist-type-count"></span>
          </div>
          <div id="shortlist-books" class="shortlist-grid"></div>
        </div>

        <div id="shortlist-external-section" class="shortlist-type-section" style="display:none;">
          <div class="shortlist-type-heading">
            <span class="shortlist-type-icon">🌐</span>
            <h3>{{ $copy['external_title'] }}</h3>
            <span id="shortlist-external-count" class="shortlist-type-count"></span>
          </div>
          <div id="shortlist-external" class="shortlist-grid"></div>
        </div>

        {{-- Bibliography export block --}}
        <div id="bibliography-block" class="bibliography-export-block">
          <div class="bibliography-export-header">
            <div>
              <h3 style="margin:0 0 4px; font-size:18px;">{{ $copy['bibliography_title'] }}</h3>
              <p style="color:var(--muted); font-size:13px; margin:0;">{{ $copy['bibliography_body'] }}</p>
            </div>
            <div class="bibliography-format-controls">
              <label class="bibliography-format-label" for="bib-format">{{ $copy['format'] }}</label>
              <select id="bib-format" class="bibliography-format-select" onchange="loadExport()">
                <option value="numbered">{{ $copy['format_numbered'] }}</option>
                <option value="grouped" selected>{{ $copy['format_grouped'] }}</option>
                <option value="syllabus">{{ $copy['format_syllabus'] }}</option>
              </select>
            </div>
          </div>

          <div id="bibliography-text" class="bibliography-text-area"></div>

          <div class="bibliography-export-actions">
            <button class="btn btn-primary" onclick="copyBibliography()" id="copy-bib-btn">{{ $copy['copy_text'] }}</button>
            <button class="btn btn-ghost" onclick="window.print()">{{ $copy['print'] }}</button>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .draft-meta-block {
    margin-bottom: 24px;
    padding: 20px 22px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 6px);
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .draft-meta-block:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(0,30,64,.12);
  }

  .draft-meta-fields {
    display: grid;
    gap: 12px;
  }

  .draft-field-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .draft-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .04em;
  }

  .draft-input, .draft-textarea {
    padding: 10px 0;
    border: 0;
    border-bottom: 1px solid var(--border);
    border-radius: 0;
    background: transparent;
    font-size: 15px;
    font-family: inherit;
    color: var(--text, #1a1a1a);
    transition: border-color .2s, background .2s ease;
    resize: vertical;
  }

  .draft-input:focus, .draft-textarea:focus {
    outline: none;
    border-color: var(--blue);
    background: rgba(255,255,255,.42);
  }

  .draft-save-status {
    font-size: 12px;
    color: var(--muted);
    margin-top: 6px;
    min-height: 18px;
  }

  .draft-persistence-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .03em;
    margin-bottom: 10px;
  }

  .draft-persistence-badge.persistent {
    background: rgba(20,105,109,.08);
    color: #14696d;
    border: 1px solid rgba(20,105,109,.16);
  }

  .draft-persistence-badge.session-only {
    background: rgba(93,66,1,.08);
    color: #5d4201;
    border: 1px solid rgba(93,66,1,.16);
  }

  .shortlist-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
    padding: 16px 18px;
    border-radius: var(--radius-lg, 6px);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    border: 1px solid var(--border);
    box-shadow: 0 10px 24px rgba(25,28,29,.03);
  }

  .shortlist-type-section {
    margin-bottom: 28px;
    padding: 16px;
    border-radius: var(--radius-lg, 6px);
    background: rgba(255,255,255,.72);
    border: 1px solid rgba(195,198,209,.45);
  }

  .shortlist-type-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 0;
  }

  .shortlist-type-heading h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    font-family: 'Newsreader', Georgia, serif;
    color: var(--blue);
  }

  .shortlist-type-icon {
    font-size: 20px;
  }

  .shortlist-type-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 24px;
    height: 24px;
    padding: 0 8px;
    border-radius: 999px;
    background: var(--bg-soft, #f0f4ff);
    border: 1px solid var(--border);
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
  }

  .shortlist-grid {
    display: grid;
    gap: 12px;
  }

  .shortlist-item {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: start;
    gap: 16px;
    padding: 18px 22px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 6px);
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), background .2s ease, box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .shortlist-item:hover {
    box-shadow: 0 14px 28px rgba(25,28,29,.05);
    background: rgba(243,244,245,.96);
    border-color: rgba(20,105,109,.18);
    transform: translate3d(0, -2px, 0);
  }

  .shortlist-item-info h4 {
    margin: 0 0 4px;
    font-size: 16px;
    font-weight: 700;
  }

  .shortlist-item-info h4 a {
    color: inherit;
    text-decoration: none;
    transition: color .2s;
  }

  .shortlist-item-info h4 a:hover {
    color: var(--blue);
  }

  .shortlist-item-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 6px;
  }

  .shortlist-item-meta .tag {
    display: inline-flex;
    padding: 3px 10px;
    border-radius: 999px;
    background: var(--bg-soft, #f0f4ff);
    border: 1px solid var(--border);
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
  }

  .shortlist-remove-btn {
    padding: 8px 16px;
    border-radius: var(--radius-md, 4px);
    border: 1px solid var(--border);
    background: transparent;
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
  }

  .shortlist-remove-btn:hover {
    border-color: var(--danger, #dc2626);
    color: var(--danger, #dc2626);
    background: rgba(220, 38, 38, .05);
  }

  /* Bibliography export block */
  .bibliography-export-block {
    margin-top: 36px;
    padding: 24px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 6px);
    box-shadow: none;
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .bibliography-export-block:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25,28,29,.05);
    border-color: rgba(0,30,64,.12);
  }

  .bibliography-export-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 20px;
    flex-wrap: wrap;
  }

  .bibliography-format-controls {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .bibliography-format-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
    white-space: nowrap;
  }

  .bibliography-format-select {
    padding: 7px 14px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: var(--bg-soft, #f8fafc);
    font-size: 13px;
    font-weight: 700;
    color: var(--text, #1a1a1a);
    cursor: pointer;
    transition: border-color .2s;
    -webkit-appearance: none;
    appearance: none;
    padding-right: 28px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236b7280'%3E%3Cpath d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
  }

  .bibliography-format-select:focus {
    outline: none;
    border-color: var(--blue);
  }

  .bibliography-text-area {
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 18px;
    font-size: 14px;
    line-height: 1.85;
    white-space: pre-wrap;
    font-family: 'Manrope', sans-serif;
    min-height: 80px;
    color: var(--text, #1a1a1a);
  }

  .bibliography-export-actions {
    display: flex;
    gap: 10px;
    margin-top: 18px;
    flex-wrap: wrap;
  }

  .shortlist-state {
    position: relative;
    overflow: hidden;
    text-align: center;
    padding: 48px 24px;
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 6px);
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .shortlist-state::after {
    content: '';
    position: absolute;
    inset: -40px -40px auto auto;
    width: 160px;
    height: 160px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(20,105,109,.08), transparent 72%);
    pointer-events: none;
  }

  .shortlist-state--loading {
    background: var(--bg-soft, #f8fafc);
  }

  .shortlist-state:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25,28,29,.05);
    border-color: rgba(0,30,64,.12);
  }

  .shortlist-spinner {
    display: inline-block;
    width: 32px;
    height: 32px;
    border: 3px solid rgba(195,198,209,.55);
    border-top-color: var(--blue);
    border-radius: 50%;
    animation: spin .7s linear infinite;
  }

  .shortlist-state-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 90px;
    height: 36px;
    padding: 0 14px;
    margin-bottom: 12px;
    border-radius: 999px;
    background: rgba(0,30,64,.05);
    color: var(--blue);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    box-shadow: 0 10px 20px rgba(25,28,29,.03);
  }

  .shortlist-state h2 {
    margin: 0 0 10px;
    font-size: 26px;
    font-family: 'Newsreader', Georgia, serif;
    font-weight: 600;
    color: var(--blue);
  }

  .shortlist-state p {
    color: var(--muted);
    max-width: 520px;
    margin: 0 auto 20px;
    line-height: 1.7;
  }

  .shortlist-state a {
    color: var(--blue);
    font-weight: 700;
  }

  .shortlist-state-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
  }

  @keyframes spin { to { transform: rotate(360deg); } }

  @media (max-width: 680px) {
    .shortlist-item {
      grid-template-columns: 1fr;
    }
    .shortlist-remove-btn {
      width: 100%;
      text-align: center;
    }
    .bibliography-export-header {
      flex-direction: column;
    }
    .bibliography-format-controls {
      width: 100%;
    }
    .bibliography-format-select {
      flex: 1;
    }
  }

  /* Print styles */
  @media print {
    .topbar, .nav, .footer, .page-hero,
    .shortlist-header, .shortlist-type-section,
    .bibliography-export-actions, .bibliography-format-controls,
    .bibliography-export-header > div:first-child > p,
    .shortlist-remove-btn, button, .nav-actions, .btn,
    .draft-meta-block {
      display: none !important;
    }
    body { background: #fff; }
    .bibliography-export-block {
      border: none;
      padding: 0;
      margin: 0;
      box-shadow: none;
    }
    .bibliography-export-header > div:first-child > h3 {
      font-size: 16pt;
      margin-bottom: 12pt;
    }
    .bibliography-text-area {
      border: none;
      background: #fff;
      padding: 0;
      font-size: 11pt;
      line-height: 1.6;
    }
    .page-section { padding: 0; }
    .container { max-width: 100%; padding: 0 24px; }
  }
</style>
@endsection

@section('scripts')
<script>
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;
  const API_BASE = '/api/v1/shortlist';
  const SHORTLIST_LANG = @json($lang);
  const SHORTLIST_I18N_MAP = {!! json_encode([
    'ru' => [
      'untitled' => 'Без названия',
      'platformPrefix' => 'Платформа',
      'remove' => '✕ Убрать',
      'campus' => 'Только в кампусе',
      'remote_auth' => 'Доступ по авторизации',
      'open' => 'Открытый доступ',
      'loadFailed' => 'Не удалось загрузить подборку',
      'exportFailed' => 'Не удалось подготовить экспорт',
      'clearConfirm' => 'Очистить всю подборку?',
      'copied' => '✓ Скопировано',
      'savedToAccount' => '☁ Сохраняется в аккаунте',
      'sessionOnly' => '⏳ Только в текущей сессии',
      'saving' => 'Сохранение...',
      'saved' => '✓ Сохранено',
      'saveError' => 'Ошибка сохранения',
      'networkError' => 'Ошибка сети',
      'booksCount' => 'книг',
      'externalCount' => 'эл. ресурсов',
    ],
    'kk' => [
      'untitled' => 'Атауы жоқ',
      'platformPrefix' => 'Платформа',
      'remove' => '✕ Алып тастау',
      'campus' => 'Тек кампуста',
      'remote_auth' => 'Авторизация арқылы',
      'open' => 'Ашық қолжетімділік',
      'loadFailed' => 'Іріктемені жүктеу мүмкін болмады',
      'exportFailed' => 'Экспортты дайындау мүмкін болмады',
      'clearConfirm' => 'Бүкіл іріктемені тазарту керек пе?',
      'copied' => '✓ Көшірілді',
      'savedToAccount' => '☁ Аккаунтта сақталады',
      'sessionOnly' => '⏳ Тек ағымдағы сессияда',
      'saving' => 'Сақталуда...',
      'saved' => '✓ Сақталды',
      'saveError' => 'Сақтау қатесі',
      'networkError' => 'Желі қатесі',
      'booksCount' => 'кітап',
      'externalCount' => 'эл. ресурс',
    ],
    'en' => [
      'untitled' => 'Untitled',
      'platformPrefix' => 'Platform',
      'remove' => '✕ Remove',
      'campus' => 'Campus only',
      'remote_auth' => 'Authenticated access',
      'open' => 'Open access',
      'loadFailed' => 'Unable to load the shortlist',
      'exportFailed' => 'Unable to prepare the export',
      'clearConfirm' => 'Clear the entire shortlist?',
      'copied' => '✓ Copied',
      'savedToAccount' => '☁ Saved to account',
      'sessionOnly' => '⏳ Session only',
      'saving' => 'Saving...',
      'saved' => '✓ Saved',
      'saveError' => 'Save failed',
      'networkError' => 'Network error',
      'booksCount' => 'books',
      'externalCount' => 'e-resources',
    ],
  ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
  const SHORTLIST_I18N = SHORTLIST_I18N_MAP[SHORTLIST_LANG] || SHORTLIST_I18N_MAP.ru;

  let currentItems = [];
  let draftSaveTimer = null;

  function withLang(path) {
    const url = new URL(path, window.location.origin);
    if (SHORTLIST_LANG !== 'ru' && !url.searchParams.has('lang')) {
      url.searchParams.set('lang', SHORTLIST_LANG);
    }
    return url.pathname + url.search;
  }

  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function pluralItems(n) {
    if (SHORTLIST_LANG === 'ru') {
      if (n % 10 === 1 && n % 100 !== 11) return 'источник';
      if ([2, 3, 4].includes(n % 10) && ![12, 13, 14].includes(n % 100)) return 'источника';
      return 'источников';
    }

    if (SHORTLIST_LANG === 'kk') {
      return 'дереккөз';
    }

    return n === 1 ? 'source' : 'sources';
  }

  async function loadShortlist() {
    const loading = document.getElementById('shortlist-loading');
    const empty = document.getElementById('shortlist-empty');
    const content = document.getElementById('shortlist-content');

    try {
      const res = await fetch(API_BASE, {
        headers: { Accept: 'application/json' },
        credentials: 'same-origin',
      });

      if (!res.ok) throw new Error(SHORTLIST_I18N.loadFailed);

      const json = await res.json();
      const items = Array.isArray(json.data) ? json.data : [];
      currentItems = items;
      loading.style.display = 'none';

      if (items.length === 0) {
        empty.style.display = 'block';
        content.style.display = 'none';
        return;
      }

      empty.style.display = 'none';
      content.style.display = 'block';

      document.getElementById('shortlist-count').textContent = items.length;
      document.getElementById('shortlist-count-label').textContent = pluralItems(items.length);

      const books = items.filter((item) => (item.type || 'book') !== 'external_resource');
      const external = items.filter((item) => item.type === 'external_resource');
      const summaryParts = [];

      if (books.length > 0) summaryParts.push(`${books.length} ${SHORTLIST_I18N.booksCount}`);
      if (external.length > 0) summaryParts.push(`${external.length} ${SHORTLIST_I18N.externalCount}`);

      document.getElementById('shortlist-type-summary').textContent = summaryParts.length ? `(${summaryParts.join(', ')})` : '';

      renderGroupedItems(books, external);
      loadExport();
    } catch (err) {
      loading.style.display = 'none';
      empty.style.display = 'block';
      console.error('Shortlist load error:', err);
    }
  }

  function renderGroupedItems(books, external) {
    const booksSection = document.getElementById('shortlist-books-section');
    const externalSection = document.getElementById('shortlist-external-section');

    if (books.length > 0) {
      booksSection.style.display = 'block';
      document.getElementById('shortlist-books-count').textContent = books.length;
      document.getElementById('shortlist-books').innerHTML = books.map((item, idx) =>
        renderItemCard(item, idx + 1, false)
      ).join('');
    } else {
      booksSection.style.display = 'none';
    }

    if (external.length > 0) {
      externalSection.style.display = 'block';
      document.getElementById('shortlist-external-count').textContent = external.length;
      document.getElementById('shortlist-external').innerHTML = external.map((item, idx) =>
        renderItemCard(item, idx + 1, true)
      ).join('');
    } else {
      externalSection.style.display = 'none';
    }
  }

  function renderItemCard(item, num, isExternal) {
    const identifier = item.identifier || '';
    const title = escapeHtml(item.title || SHORTLIST_I18N.untitled);
    const author = escapeHtml(item.author || '');
    const publisher = escapeHtml(item.publisher || '');
    const provider = escapeHtml(item.provider || '');
    const year = escapeHtml(item.year || '');
    const language = escapeHtml(item.language || '');
    const isbn = escapeHtml(item.isbn || '');
    const accessType = item.access_type || '';

    const accessLabels = {
      campus: SHORTLIST_I18N.campus,
      remote_auth: SHORTLIST_I18N.remote_auth,
      open: SHORTLIST_I18N.open,
    };

    const tags = [
      year ? `<span class="tag">${year}</span>` : '',
      language ? `<span class="tag">${language}</span>` : '',
      isbn ? `<span class="tag">ISBN: ${isbn}</span>` : '',
      isExternal && accessType
        ? `<span class="tag" style="background:rgba(20,105,109,.08);color:var(--cyan);">${escapeHtml(accessLabels[accessType] || accessType)}</span>`
        : '',
    ].filter(Boolean).join('');

    const linkHref = isExternal && item.url ? item.url : withLang(`/book/${encodeURIComponent(identifier)}`);
    const linkTarget = isExternal ? ' target="_blank" rel="noopener"' : '';
    const linkSuffix = isExternal ? ' ↗' : '';

    return `
      <div class="shortlist-item" data-identifier="${escapeHtml(identifier)}">
        <div class="shortlist-item-info">
          <h4><a href="${linkHref}"${linkTarget}>${num}. ${title}${linkSuffix}</a></h4>
          ${author ? `<div style="color:var(--muted); font-size:14px;">${author}</div>` : ''}
          ${publisher ? `<div style="color:var(--muted); font-size:13px;">${publisher}</div>` : ''}
          ${isExternal && provider ? `<div style="color:var(--muted); font-size:13px;">${SHORTLIST_I18N.platformPrefix}: ${provider}</div>` : ''}
          <div class="shortlist-item-meta">${tags}</div>
        </div>
        <button class="shortlist-remove-btn" onclick="removeItem('${escapeHtml(identifier)}')">${SHORTLIST_I18N.remove}</button>
      </div>
    `;
  }

  async function loadExport() {
    const format = document.getElementById('bib-format').value;
    const block = document.getElementById('bibliography-text');

    try {
      const res = await fetch(`${API_BASE}/export?format=${encodeURIComponent(format)}`, {
        headers: { Accept: 'application/json' },
        credentials: 'same-origin',
      });

      if (!res.ok) throw new Error(SHORTLIST_I18N.exportFailed);
      const json = await res.json();
      block.textContent = json.data?.text || '';
    } catch (err) {
      block.textContent = currentItems.map((item, idx) => {
        const parts = [];
        if (item.author) parts.push(item.author);
        parts.push(item.title || SHORTLIST_I18N.untitled);
        if (item.publisher) parts.push(item.publisher);
        if (item.year) parts.push(item.year);
        return `${idx + 1}. ${parts.join('. ')}.`;
      }).join('\n');
    }
  }

  async function removeItem(identifier) {
    try {
      const res = await fetch(`${API_BASE}/${encodeURIComponent(identifier)}`, {
        method: 'DELETE',
        headers: {
          Accept: 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        credentials: 'same-origin',
      });

      if (res.ok) {
        loadShortlist();
      }
    } catch (err) {
      console.error('Remove error:', err);
    }
  }

  async function clearShortlist() {
    if (!confirm(SHORTLIST_I18N.clearConfirm)) return;

    try {
      await fetch(`${API_BASE}/clear`, {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        credentials: 'same-origin',
      });
      loadShortlist();
    } catch (err) {
      console.error('Clear error:', err);
    }
  }

  function copyBibliography() {
    const text = document.getElementById('bibliography-text')?.textContent;
    if (!text) return;

    const btn = document.getElementById('copy-bib-btn');
    const showCopied = () => {
      if (!btn) return;
      const orig = btn.innerHTML;
      btn.innerHTML = SHORTLIST_I18N.copied;
      btn.style.background = 'var(--cyan, #14696d)';
      setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; }, 2000);
    };

    navigator.clipboard.writeText(text).then(showCopied).catch(() => {
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
      showCopied();
    });
  }

  async function loadDraftMeta() {
    try {
      const res = await fetch(`${API_BASE}/summary`, {
        headers: { Accept: 'application/json' },
        credentials: 'same-origin',
      });
      if (!res.ok) return;
      const json = await res.json();
      const draft = json.data?.draft || {};

      const titleInput = document.getElementById('draft-title');
      const notesInput = document.getElementById('draft-notes');
      if (titleInput && draft.title) titleInput.value = draft.title;
      if (notesInput && draft.notes) notesInput.value = draft.notes;

      const badge = document.getElementById('draft-persistence-badge');
      if (badge) {
        badge.textContent = draft.persistent ? SHORTLIST_I18N.savedToAccount : SHORTLIST_I18N.sessionOnly;
        badge.className = `draft-persistence-badge ${draft.persistent ? 'persistent' : 'session-only'}`;
        badge.style.display = '';
      }
    } catch (err) {
      console.error('Draft meta load error:', err);
    }
  }

  function saveDraftMeta() {
    clearTimeout(draftSaveTimer);
    const statusEl = document.getElementById('draft-save-status');
    if (statusEl) statusEl.textContent = SHORTLIST_I18N.saving;

    draftSaveTimer = setTimeout(async () => {
      const title = document.getElementById('draft-title')?.value || '';
      const notes = document.getElementById('draft-notes')?.value || '';

      try {
        const res = await fetch(`${API_BASE}/draft`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
          },
          credentials: 'same-origin',
          body: JSON.stringify({ title: title || null, notes: notes || null }),
        });

        if (statusEl) {
          statusEl.textContent = res.ok ? SHORTLIST_I18N.saved : SHORTLIST_I18N.saveError;
          setTimeout(() => { statusEl.textContent = ''; }, 3000);
        }
      } catch (err) {
        if (statusEl) statusEl.textContent = SHORTLIST_I18N.networkError;
      }
    }, 800);
  }

  document.getElementById('draft-title')?.addEventListener('input', saveDraftMeta);
  document.getElementById('draft-notes')?.addEventListener('input', saveDraftMeta);

  loadShortlist();
  loadDraftMeta();
</script>
@endsection
