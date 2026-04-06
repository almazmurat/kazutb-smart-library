@extends('layouts.public')

@section('title', 'Подборка литературы — Библиотека КазУТБ')

@section('content')
  <section class="page-hero">
    <div class="container">
      <div class="eyebrow eyebrow--violet">Подборка литературы</div>
      <h1>Черновик списка литературы</h1>
      <p>Собирайте книги из каталога и электронные ресурсы для подготовки силлабуса. Выберите формат — скопируйте или распечатайте готовый список.</p>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div id="shortlist-loading" style="text-align:center; padding:48px;">
        <div style="display:inline-block;width:32px;height:32px;border:3px solid #e5e7eb;border-top-color:var(--blue);border-radius:50%;animation:spin .7s linear infinite;"></div>
        <p style="margin:8px 0 0; color:var(--muted);">Загрузка подборки...</p>
      </div>

      <div id="shortlist-empty" style="display:none; text-align:center; padding:64px 24px;">
        <div style="font-size:64px; margin-bottom:16px;">📋</div>
        <h2 style="margin:0 0 12px; font-size:24px;">Подборка пуста</h2>
        <p style="color:var(--muted); max-width:480px; margin:0 auto 24px; line-height:1.7;">
          Добавляйте книги из <a href="/catalog" style="color:var(--blue); font-weight:600;">каталога</a> или <a href="/resources" style="color:var(--blue); font-weight:600;">электронные ресурсы</a>, нажимая кнопку «В подборку». Собранные источники появятся здесь.
        </p>
        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
          <a href="/catalog" class="btn btn-primary">Открыть каталог</a>
          <a href="/resources" class="btn btn-ghost">Электронные ресурсы</a>
          <a href="/for-teachers" class="btn btn-ghost">Для преподавателей</a>
        </div>
      </div>

      <div id="shortlist-content" style="display:none;">
        {{-- Cabinet navigation --}}
        @if(session('library.user'))
        <div style="margin-bottom:18px;">
          <a href="/account" style="color:var(--blue, #3b82f6); font-size:14px; font-weight:600; text-decoration:none;">← Вернуться в кабинет</a>
        </div>
        @endif

        {{-- Draft metadata block --}}
        <div id="draft-meta-block" class="draft-meta-block">
          <div id="draft-persistence-badge" class="draft-persistence-badge" style="display:none;"></div>
          <div class="draft-meta-fields">
            <div class="draft-field-group">
              <label for="draft-title" class="draft-label">Название черновика</label>
              <input type="text" id="draft-title" class="draft-input" placeholder="Например: Литература для дисциплины «Информатика»" maxlength="500">
            </div>
            <div class="draft-field-group">
              <label for="draft-notes" class="draft-label">Заметки</label>
              <textarea id="draft-notes" class="draft-textarea" placeholder="Заметки для себя: семестр, группа, комментарии..." maxlength="2000" rows="2"></textarea>
            </div>
          </div>
          <div id="draft-save-status" class="draft-save-status"></div>
        </div>

        {{-- Header with stats and actions --}}
        <div class="shortlist-header">
          <div>
            <h2 style="margin:0 0 4px; font-size:22px;">Выбранные источники</h2>
            <p style="margin:0; color:var(--muted); font-size:14px;">
              <span id="shortlist-count">0</span> <span id="shortlist-count-label">источников</span> в подборке
              <span id="shortlist-type-summary" style="margin-left:4px;"></span>
            </p>
          </div>
          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn-ghost" onclick="window.print()" title="Печать списка литературы">🖨 Печать</button>
            <button class="btn btn-ghost" onclick="clearShortlist()" style="color:var(--danger);">🗑 Очистить</button>
          </div>
        </div>

        {{-- Items grouped by type --}}
        <div id="shortlist-books-section" class="shortlist-type-section" style="display:none;">
          <div class="shortlist-type-heading">
            <span class="shortlist-type-icon">📚</span>
            <h3>Основная литература</h3>
            <span id="shortlist-books-count" class="shortlist-type-count"></span>
          </div>
          <div id="shortlist-books" class="shortlist-grid"></div>
        </div>

        <div id="shortlist-external-section" class="shortlist-type-section" style="display:none;">
          <div class="shortlist-type-heading">
            <span class="shortlist-type-icon">🌐</span>
            <h3>Электронные ресурсы</h3>
            <span id="shortlist-external-count" class="shortlist-type-count"></span>
          </div>
          <div id="shortlist-external" class="shortlist-grid"></div>
        </div>

        {{-- Bibliography export block --}}
        <div id="bibliography-block" class="bibliography-export-block">
          <div class="bibliography-export-header">
            <div>
              <h3 style="margin:0 0 4px; font-size:18px;">📄 Список литературы</h3>
              <p style="color:var(--muted); font-size:13px; margin:0;">Готовый текст для вставки в силлабус или документ</p>
            </div>
            <div class="bibliography-format-controls">
              <label class="bibliography-format-label" for="bib-format">Формат:</label>
              <select id="bib-format" class="bibliography-format-select" onchange="loadExport()">
                <option value="numbered">Нумерованный список</option>
                <option value="grouped" selected>По разделам</option>
                <option value="syllabus">Для силлабуса</option>
              </select>
            </div>
          </div>

          <div id="bibliography-text" class="bibliography-text-area"></div>

          <div class="bibliography-export-actions">
            <button class="btn btn-primary" onclick="copyBibliography()" id="copy-bib-btn">📋 Скопировать текст</button>
            <button class="btn btn-ghost" onclick="window.print()">🖨 Печать</button>
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
    background: var(--surface-glass, #fff);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 24px);
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
    padding: 10px 14px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--bg-soft, #f8fafc);
    font-size: 15px;
    font-family: inherit;
    color: var(--text, #1a1a1a);
    transition: border-color .2s;
    resize: vertical;
  }

  .draft-input:focus, .draft-textarea:focus {
    outline: none;
    border-color: var(--blue);
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
    background: rgba(22,163,74,.08);
    color: #16a34a;
    border: 1px solid rgba(22,163,74,.16);
  }

  .draft-persistence-badge.session-only {
    background: rgba(245,158,11,.08);
    color: #b45309;
    border: 1px solid rgba(245,158,11,.16);
  }

  .shortlist-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
  }

  .shortlist-type-section {
    margin-bottom: 28px;
  }

  .shortlist-type-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--border, #e5e7eb);
  }

  .shortlist-type-heading h3 {
    margin: 0;
    font-size: 17px;
    font-weight: 700;
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
    background: var(--surface-glass, #fff);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 24px);
    transition: box-shadow .2s;
  }

  .shortlist-item:hover {
    box-shadow: var(--shadow-soft);
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
    border-radius: 14px;
    border: 1px solid var(--border);
    background: #fff;
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
    padding: 28px;
    background: var(--surface-glass, #fff);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 24px);
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
    border-radius: 12px;
    background: var(--bg-soft, #f8fafc);
    font-size: 13px;
    font-weight: 600;
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
    background: var(--bg-soft, #f8fafc);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 22px;
    font-size: 14px;
    line-height: 1.85;
    white-space: pre-wrap;
    font-family: 'Inter', sans-serif;
    min-height: 80px;
    color: var(--text, #1a1a1a);
  }

  .bibliography-export-actions {
    display: flex;
    gap: 10px;
    margin-top: 18px;
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

  let currentItems = [];
  let draftSaveTimer = null;

  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function pluralItems(n) {
    if (n % 10 === 1 && n % 100 !== 11) return 'источник';
    if ([2,3,4].includes(n % 10) && ![12,13,14].includes(n % 100)) return 'источника';
    return 'источников';
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

      if (!res.ok) throw new Error('Failed to load');

      const json = await res.json();
      const items = json.data || [];
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

      const books = items.filter(i => (i.type || 'book') !== 'external_resource');
      const external = items.filter(i => i.type === 'external_resource');

      // Type summary
      const summaryParts = [];
      if (books.length > 0) summaryParts.push(`${books.length} книг`);
      if (external.length > 0) summaryParts.push(`${external.length} эл. ресурсов`);
      document.getElementById('shortlist-type-summary').textContent =
        summaryParts.length === 2 ? `(${summaryParts.join(', ')})` : '';

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
    const title = escapeHtml(item.title || 'Без названия');
    const author = escapeHtml(item.author || '');
    const publisher = escapeHtml(item.publisher || '');
    const provider = escapeHtml(item.provider || '');
    const year = escapeHtml(item.year || '');
    const language = escapeHtml(item.language || '');
    const isbn = escapeHtml(item.isbn || '');
    const accessType = item.access_type || '';

    const accessLabels = {
      campus: 'Из кампуса',
      remote_auth: 'По авторизации',
      open: 'Свободный доступ'
    };

    const tags = [
      year ? `<span class="tag">${year}</span>` : '',
      language ? `<span class="tag">${language}</span>` : '',
      isbn ? `<span class="tag">ISBN: ${isbn}</span>` : '',
      isExternal && accessType ? `<span class="tag" style="background:rgba(124,58,237,.08);color:var(--violet);">${escapeHtml(accessLabels[accessType] || accessType)}</span>` : '',
    ].filter(Boolean).join('');

    const linkHref = isExternal && item.url
      ? item.url
      : `/book/${encodeURIComponent(identifier)}`;
    const linkTarget = isExternal ? ' target="_blank" rel="noopener"' : '';
    const linkSuffix = isExternal ? ' ↗' : '';

    return `
      <div class="shortlist-item" data-identifier="${escapeHtml(identifier)}">
        <div class="shortlist-item-info">
          <h4><a href="${linkHref}"${linkTarget}>${num}. ${title}${linkSuffix}</a></h4>
          ${author ? `<div style="color:var(--muted); font-size:14px;">${author}</div>` : ''}
          ${publisher ? `<div style="color:var(--muted); font-size:13px;">${publisher}</div>` : ''}
          ${isExternal && provider ? `<div style="color:var(--muted); font-size:13px;">Платформа: ${provider}</div>` : ''}
          <div class="shortlist-item-meta">${tags}</div>
        </div>
        <button class="shortlist-remove-btn" onclick="removeItem('${escapeHtml(identifier)}')">✕ Убрать</button>
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

      if (!res.ok) throw new Error('Export failed');
      const json = await res.json();
      block.textContent = json.data?.text || '';
    } catch (err) {
      // Client-side fallback
      block.textContent = currentItems.map((item, idx) => {
        const parts = [];
        if (item.author) parts.push(item.author);
        parts.push(item.title || 'Без названия');
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
    if (!confirm('Очистить всю подборку?')) return;

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
    navigator.clipboard.writeText(text).then(() => {
      if (btn) {
        const orig = btn.innerHTML;
        btn.innerHTML = '✓ Скопировано';
        btn.style.background = 'var(--green, #16a34a)';
        setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; }, 2000);
      }
    }).catch(() => {
      const ta = document.createElement('textarea');
      ta.value = text;
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
      if (btn) {
        const orig = btn.innerHTML;
        btn.innerHTML = '✓ Скопировано';
        setTimeout(() => { btn.innerHTML = orig; }, 2000);
      }
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
        if (draft.persistent) {
          badge.textContent = '☁ Сохраняется в аккаунте';
          badge.className = 'draft-persistence-badge persistent';
        } else {
          badge.textContent = '⏳ Только в текущей сессии';
          badge.className = 'draft-persistence-badge session-only';
        }
        badge.style.display = '';
      }
    } catch (err) {
      console.error('Draft meta load error:', err);
    }
  }

  function saveDraftMeta() {
    clearTimeout(draftSaveTimer);
    const statusEl = document.getElementById('draft-save-status');
    if (statusEl) statusEl.textContent = 'Сохранение...';

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
          statusEl.textContent = res.ok ? '✓ Сохранено' : 'Ошибка сохранения';
          setTimeout(() => { statusEl.textContent = ''; }, 3000);
        }
      } catch (err) {
        if (statusEl) statusEl.textContent = 'Ошибка сети';
      }
    }, 800);
  }

  document.getElementById('draft-title')?.addEventListener('input', saveDraftMeta);
  document.getElementById('draft-notes')?.addEventListener('input', saveDraftMeta);

  loadShortlist();
  loadDraftMeta();
</script>
@endsection
