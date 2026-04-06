@extends('layouts.public')

@section('title', 'Подборка литературы — Библиотека КазУТБ')

@section('content')
  <section class="page-hero">
    <div class="container">
      <div class="eyebrow eyebrow--violet">Подборка литературы</div>
      <h1>Ваш черновик списка литературы</h1>
      <p>Собирайте книги из каталога для подготовки силлабуса или списка рекомендуемой литературы. Добавляйте книги со страниц каталога и детальных описаний.</p>
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
          Добавляйте книги из <a href="/catalog" style="color:var(--blue); font-weight:600;">каталога</a> или со страниц книг, нажимая кнопку «В подборку». Собранные книги появятся здесь.
        </p>
        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
          <a href="/catalog" class="btn btn-primary">Открыть каталог</a>
          <a href="/for-teachers" class="btn btn-ghost">Для преподавателей</a>
        </div>
      </div>

      <div id="shortlist-content" style="display:none;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
          <div>
            <h2 style="margin:0 0 4px; font-size:22px;">Выбранные книги</h2>
            <p style="margin:0; color:var(--muted); font-size:14px;">
              <span id="shortlist-count">0</span> книг в подборке
            </p>
          </div>
          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn-ghost" onclick="copyBibliography()" title="Скопировать как текстовый список">📋 Скопировать список</button>
            <button class="btn btn-ghost" onclick="clearShortlist()" style="color:var(--danger);">🗑 Очистить</button>
          </div>
        </div>

        <div id="shortlist-items" class="shortlist-grid"></div>

        <div id="bibliography-block" class="card" style="margin-top:32px; padding:28px;">
          <h3 style="margin:0 0 16px; font-size:18px;">📄 Список литературы (черновик)</h3>
          <p style="color:var(--muted); font-size:13px; margin:0 0 16px;">Текстовый формат для вставки в документ. Скопируйте и отредактируйте при необходимости.</p>
          <div id="bibliography-text" style="background:var(--bg-soft, #f8fafc); border:1px solid var(--border); border-radius:var(--radius-sm, 12px); padding:20px; font-size:14px; line-height:1.8; white-space:pre-wrap; font-family:'Inter', sans-serif;"></div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .shortlist-grid {
    display: grid;
    gap: 16px;
  }

  .shortlist-item {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: start;
    gap: 16px;
    padding: 20px 24px;
    background: var(--surface-glass, #fff);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg, 24px);
    transition: box-shadow .2s;
  }

  .shortlist-item:hover {
    box-shadow: var(--shadow-soft);
  }

  .shortlist-item-info h3 {
    margin: 0 0 6px;
    font-size: 17px;
    font-weight: 700;
  }

  .shortlist-item-info h3 a {
    color: inherit;
    text-decoration: none;
    transition: color .2s;
  }

  .shortlist-item-info h3 a:hover {
    color: var(--blue);
  }

  .shortlist-item-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
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

  @keyframes spin { to { transform: rotate(360deg); } }

  @media (max-width: 680px) {
    .shortlist-item {
      grid-template-columns: 1fr;
    }
    .shortlist-remove-btn {
      width: 100%;
      text-align: center;
    }
  }
</style>
@endsection

@section('scripts')
<script>
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;
  const API_BASE = '/api/v1/shortlist';

  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
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

      loading.style.display = 'none';

      if (items.length === 0) {
        empty.style.display = 'block';
        content.style.display = 'none';
        return;
      }

      empty.style.display = 'none';
      content.style.display = 'block';
      document.getElementById('shortlist-count').textContent = items.length;
      renderItems(items);
      renderBibliography(items);
    } catch (err) {
      loading.style.display = 'none';
      empty.style.display = 'block';
      console.error('Shortlist load error:', err);
    }
  }

  function renderItems(items) {
    const grid = document.getElementById('shortlist-items');
    grid.innerHTML = items.map((item, idx) => {
      const identifier = item.identifier || '';
      const title = escapeHtml(item.title || 'Без названия');
      const author = escapeHtml(item.author || '');
      const publisher = escapeHtml(item.publisher || '');
      const provider = escapeHtml(item.provider || '');
      const year = escapeHtml(item.year || '');
      const language = escapeHtml(item.language || '');
      const isbn = escapeHtml(item.isbn || '');
      const isExternal = item.type === 'external_resource';
      const accessType = item.access_type || '';

      const accessLabels = {
        campus: 'Из кампуса',
        remote_auth: 'По авторизации',
        open: 'Свободный доступ'
      };

      const tags = [
        isExternal ? '<span class="tag" style="background:rgba(124,58,237,.1);color:var(--violet);">Внешний ресурс</span>' : '',
        year ? `<span class="tag">${year}</span>` : '',
        language ? `<span class="tag">${language}</span>` : '',
        isbn ? `<span class="tag">ISBN: ${isbn}</span>` : '',
        isExternal && accessType ? `<span class="tag">${escapeHtml(accessLabels[accessType] || accessType)}</span>` : '',
      ].filter(Boolean).join('');

      const linkHref = isExternal && item.url
        ? item.url
        : `/book/${encodeURIComponent(identifier)}`;
      const linkTarget = isExternal ? ' target="_blank" rel="noopener"' : '';
      const linkSuffix = isExternal ? ' ↗' : '';

      return `
        <div class="shortlist-item" data-identifier="${escapeHtml(identifier)}">
          <div class="shortlist-item-info">
            <h3><a href="${linkHref}"${linkTarget}>${idx + 1}. ${title}${linkSuffix}</a></h3>
            ${author ? `<div style="color:var(--muted); font-size:14px;">${author}</div>` : ''}
            ${publisher ? `<div style="color:var(--muted); font-size:13px;">${publisher}</div>` : ''}
            ${isExternal && provider ? `<div style="color:var(--muted); font-size:13px;">Платформа: ${provider}</div>` : ''}
            <div class="shortlist-item-meta">${tags}</div>
          </div>
          <button class="shortlist-remove-btn" onclick="removeItem('${escapeHtml(identifier)}')">✕ Убрать</button>
        </div>
      `;
    }).join('');
  }

  function renderBibliography(items) {
    const block = document.getElementById('bibliography-text');
    const lines = items.map((item, idx) => {
      const parts = [];
      if (item.type === 'external_resource') {
        parts.push('[Внешний ресурс]');
        parts.push(item.title || 'Без названия');
        if (item.provider) parts.push('Платформа: ' + item.provider);
        if (item.url) parts.push(item.url);
      } else {
        if (item.author) parts.push(item.author);
        parts.push(item.title || 'Без названия');
        if (item.publisher) parts.push(item.publisher);
        if (item.year) parts.push(item.year);
        if (item.isbn) parts.push(`ISBN ${item.isbn}`);
      }
      return `${idx + 1}. ${parts.join('. ')}.`;
    });
    block.textContent = lines.join('\n');
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

    navigator.clipboard.writeText(text).then(() => {
      const btn = document.querySelector('[onclick="copyBibliography()"]');
      if (btn) {
        const orig = btn.textContent;
        btn.textContent = '✓ Скопировано';
        setTimeout(() => { btn.textContent = orig; }, 2000);
      }
    }).catch(() => {
      // Fallback for older browsers
      const ta = document.createElement('textarea');
      ta.value = text;
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
    });
  }

  loadShortlist();
</script>
@endsection
