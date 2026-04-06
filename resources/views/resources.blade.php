@extends('layouts.public')

@section('title', 'Электронные ресурсы — Библиотека КазУТБ')

@section('head')
<style>
  .resource-hero-stats {
    display: flex;
    justify-content: center;
    gap: 48px;
    margin-top: 36px;
  }
  .resource-hero-stats .rh-stat {
    text-align: center;
  }
  .resource-hero-stats .rh-stat strong {
    display: block;
    font-size: 36px;
    font-weight: 900;
    letter-spacing: -1px;
    background: linear-gradient(135deg, var(--blue), var(--violet));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .resource-hero-stats .rh-stat span {
    font-size: 14px;
    color: var(--muted);
    font-weight: 600;
  }

  /* Compact local catalog banner */
  .local-catalog-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 28px 32px;
    background: var(--surface-glass);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
  }
  .local-catalog-banner h2 { margin: 0 0 6px; font-size: 22px; font-weight: 800; }
  .local-catalog-banner p { margin: 0; color: var(--muted); font-size: 15px; line-height: 1.6; }
  .local-catalog-banner .btn { white-space: nowrap; flex-shrink: 0; }

  /* Compact access guide */
  .access-inline {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
  }
  .access-chip {
    padding: 12px 18px;
    border-radius: 14px;
    font-size: 14px;
    line-height: 1.4;
    border: 1px solid var(--border);
    background: var(--surface-glass);
  }
  .access-chip--campus { border-color: rgba(59,130,246,.2); background: rgba(59,130,246,.04); }
  .access-chip--remote { border-color: rgba(124,58,237,.2); background: rgba(124,58,237,.04); }
  .access-chip--open { border-color: rgba(34,197,94,.2); background: rgba(34,197,94,.04); }

  .resource-section-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    align-items: start;
  }
  .resource-section-info {
    padding: 8px 0;
  }
  .resource-section-info h2 {
    margin: 0 0 12px;
    font-size: clamp(26px, 3.5vw, 38px);
    font-weight: 900;
    letter-spacing: -1px;
  }
  .resource-section-info p {
    color: var(--muted);
    font-size: 16px;
    line-height: 1.75;
    margin: 0 0 20px;
  }
  .resource-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 14px;
  }
  .resource-list-item {
    display: flex;
    gap: 16px;
    padding: 20px;
    border-radius: var(--radius-md);
    background: var(--surface-glass);
    border: 1px solid var(--border);
    transition: transform .2s, box-shadow .2s;
  }
  .resource-list-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
  }
  .resource-list-item .rli-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 22px;
    flex-shrink: 0;
    color: #fff;
  }
  .resource-list-item .rli-icon--blue { background: linear-gradient(135deg, var(--blue), var(--cyan)); }
  .resource-list-item .rli-icon--violet { background: linear-gradient(135deg, var(--violet), var(--pink)); }
  .resource-list-item .rli-icon--green { background: linear-gradient(135deg, var(--green), var(--cyan)); }
  .resource-list-item .rli-icon--pink { background: linear-gradient(135deg, var(--pink), #f97316); }
  .resource-list-item h4 { margin: 0 0 4px; font-size: 17px; font-weight: 700; }
  .resource-list-item p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.6; }

  .access-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }
  .access-card {
    background: var(--surface-glass);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 32px 28px;
    text-align: center;
    transition: transform .25s, box-shadow .25s;
  }
  .access-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
  }
  .access-card .ac-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    display: grid;
    place-items: center;
    font-size: 28px;
    margin: 0 auto 18px;
    color: #fff;
  }
  .access-card h3 { margin: 0 0 10px; font-size: 20px; font-weight: 800; }
  .access-card p { margin: 0 0 18px; color: var(--muted); font-size: 15px; line-height: 1.65; }
  .access-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
  }
  .access-badge--campus { background: rgba(59,130,246,.1); color: var(--blue); }
  .access-badge--remote { background: rgba(124,58,237,.1); color: var(--violet); }
  .access-badge--open { background: rgba(34,197,94,.1); color: var(--green); }

  .faq-list {
    display: grid;
    gap: 16px;
    max-width: 800px;
    margin: 32px auto 0;
  }
  .faq-item {
    padding: 24px;
    border-radius: var(--radius-md);
    background: var(--surface-glass);
    border: 1px solid var(--border);
  }
  .faq-item h4 { margin: 0 0 8px; font-size: 17px; font-weight: 700; }
  .faq-item p { margin: 0; color: var(--muted); font-size: 15px; line-height: 1.65; }

  @media (max-width: 900px) {
    .resource-section-grid { grid-template-columns: 1fr; }
    .access-inline { flex-direction: column; }
    .local-catalog-banner { flex-direction: column; align-items: flex-start; }
    .resource-hero-stats { gap: 24px; flex-wrap: wrap; }
  }

  @media (max-width: 680px) {
    .resource-hero-stats { gap: 16px; justify-content: space-around; }
    .resource-hero-stats .rh-stat strong { font-size: 28px; }
    .resource-hero-stats .rh-stat span { font-size: 12px; }
    .resource-list-item { padding: 16px; gap: 12px; }
    .resource-list-item .rli-icon { width: 40px; height: 40px; font-size: 18px; border-radius: 12px; }
    .resource-list-item h4 { font-size: 15px; }
    .resource-list-item p { font-size: 13px; }
    .access-card { padding: 20px 16px; }
    .access-card .ac-icon { width: 52px; height: 52px; font-size: 24px; }
    .access-card h3 { font-size: 18px; }
    .access-card p { font-size: 14px; }
    .faq-item { padding: 18px; }
    .faq-item h4 { font-size: 15px; }
    .faq-item p { font-size: 14px; }
    .faq-list { margin-top: 20px; gap: 12px; }
  }

  @media (max-width: 480px) {
    .resource-hero-stats { flex-direction: column; gap: 8px; align-items: center; }
    .resource-hero-stats .rh-stat { display: flex; gap: 8px; align-items: baseline; }
    .resource-hero-stats .rh-stat strong { font-size: 24px; }
    .resource-list-item .rli-icon { width: 36px; height: 36px; font-size: 16px; }
    .access-card { padding: 16px; }
    .ext-filter-bar { gap: 6px; }
    .ext-filter-btn { padding: 6px 12px; font-size: 12px; }
  }

  /* External resources filter bar */
  .ext-filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
  }
  .ext-filter-btn {
    padding: 8px 18px;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: var(--surface-glass);
    font-size: 14px;
    font-weight: 600;
    color: var(--muted);
    cursor: pointer;
    transition: all .2s;
  }
  .ext-filter-btn:hover {
    border-color: var(--blue);
    color: var(--blue);
  }
  .ext-filter-btn--active {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
  }
  .ext-filter-btn--active:hover {
    color: #fff;
  }

  /* External resources grid */
  .ext-resources-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
  }
  .ext-resource-card {
    background: var(--surface-glass);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 28px;
    transition: transform .25s, box-shadow .25s;
    display: flex;
    flex-direction: column;
  }
  .ext-resource-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow);
  }
  .ext-resource-card__header {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 14px;
  }
  .ext-resource-card__icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 22px;
    flex-shrink: 0;
    color: #fff;
  }
  .ext-resource-card__icon--blue { background: linear-gradient(135deg, var(--blue), var(--cyan)); }
  .ext-resource-card__icon--violet { background: linear-gradient(135deg, var(--violet), var(--pink)); }
  .ext-resource-card__icon--green { background: linear-gradient(135deg, var(--green), var(--cyan)); }
  .ext-resource-card__icon--pink { background: linear-gradient(135deg, var(--pink), #f97316); }
  .ext-resource-card__title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
  }
  .ext-resource-card__provider {
    font-size: 13px;
    color: var(--muted);
    margin-top: 2px;
  }
  .ext-resource-card__desc {
    color: var(--muted);
    font-size: 14px;
    line-height: 1.65;
    margin: 0 0 16px;
    flex: 1;
  }
  .ext-resource-card__footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-top: auto;
  }
  .ext-resource-card__badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
  }
  .ext-resource-card__actions {
    display: flex;
    gap: 8px;
    margin-top: 14px;
  }
  .ext-resource-card__actions a,
  .ext-resource-card__actions button {
    font-size: 13px;
    font-weight: 600;
    padding: 7px 16px;
    border-radius: 10px;
    text-decoration: none;
    transition: all .2s;
    cursor: pointer;
  }
  .ext-resource-card__actions .ext-link-btn {
    background: var(--blue);
    color: #fff;
    border: none;
  }
  .ext-resource-card__actions .ext-link-btn:hover {
    opacity: .85;
  }
  .ext-resource-card__actions .ext-shortlist-btn {
    background: transparent;
    color: var(--muted);
    border: 1px solid var(--border);
  }
  .ext-resource-card__actions .ext-shortlist-btn:hover {
    border-color: var(--violet);
    color: var(--violet);
  }
  .ext-resource-card__actions .ext-shortlist-btn--added {
    border-color: var(--green);
    color: var(--green);
    pointer-events: none;
  }
  .ext-resource-card__expiry {
    font-size: 12px;
    color: var(--muted);
    margin-left: auto;
  }

  @media (max-width: 680px) {
    .ext-resources-grid { grid-template-columns: 1fr; }
    .ext-resource-card { padding: 20px; }
    .ext-resource-card__icon { width: 40px; height: 40px; font-size: 18px; }
    .ext-resource-card__title { font-size: 16px; }
  }
</style>
@endsection

@section('content')
<div class="page-hero">
  <div class="container">
    <div class="eyebrow">Электронные ресурсы</div>
    <h1>Цифровые коллекции и научные базы данных</h1>
    <p>Доступ к электронным учебникам, международным научным базам, лицензированным платформам и открытым образовательным ресурсам.</p>
    <div class="resource-hero-stats">
      <div class="rh-stat"><strong id="stat-total">—</strong><span>внешних ресурсов</span></div>
      <div class="rh-stat"><strong>50 000+</strong><span>электронных документов</span></div>
      <div class="rh-stat"><strong>24/7</strong><span>удалённый доступ</span></div>
      <div class="rh-stat"><strong>3</strong><span>режима доступа</span></div>
    </div>
  </div>
</div>

{{-- Local library — compact banner --}}
<section class="page-section">
  <div class="container">
    <div class="local-catalog-banner">
      <div>
        <h2>Фонд библиотеки КазУТБ</h2>
        <p>Более 50 000 единиц: учебники, монографии, методические материалы и периодика в электронном и печатном формате.</p>
      </div>
      <a href="/catalog" class="btn btn-primary">Перейти в каталог</a>
    </div>
  </div>
</section>

{{-- External licensed resources section — loaded from API --}}
<section class="page-section">
  <div class="container">
    <div class="section-head">
      <div>
        <div class="eyebrow eyebrow--violet">Внешние лицензированные ресурсы</div>
        <h2>Подписные платформы и научные базы данных</h2>
        <p>Внешние электронные ресурсы, доступные студентам и преподавателям КазУТБ по подписке или в открытом доступе. Это не материалы библиотечного фонда — каждый ресурс размещён на внешней платформе со своими условиями доступа.</p>
      </div>
    </div>

    <div class="ext-filter-bar" id="ext-filter-bar">
      <button class="ext-filter-btn ext-filter-btn--active" data-filter="all">Все</button>
    </div>

    <div id="ext-resources-loading" style="text-align:center; padding:32px;">
      <div style="display:inline-block;width:28px;height:28px;border:3px solid #e5e7eb;border-top-color:var(--blue);border-radius:50%;animation:spin .7s linear infinite;"></div>
      <p style="margin:8px 0 0; color:var(--muted); font-size:14px;">Загрузка ресурсов...</p>
    </div>

    <div id="ext-resources-grid" class="ext-resources-grid" style="display:none;"></div>
  </div>
</section>

<section class="page-section">
  <div class="container">
    <h2 style="margin-bottom: 16px;">Режимы доступа</h2>
    <div class="access-inline">
      <div class="access-chip access-chip--campus">🏫 <strong>Из кампуса</strong> — автоматически через Wi-Fi и компьютеры залов</div>
      <div class="access-chip access-chip--remote">🌐 <strong>Удалённо</strong> — через личный кабинет библиотеки</div>
      <div class="access-chip access-chip--open">🔓 <strong>Открытый доступ</strong> — без ограничений</div>
    </div>
  </div>
</section>

<section class="page-section">
  <div class="container">
    <div class="section-head section-head-centered">
      <div>
        <h2>Частые вопросы</h2>
        <p>Ответы на основные вопросы о работе с электронными ресурсами библиотеки.</p>
      </div>
    </div>

    <div class="faq-list">
      <div class="faq-item">
        <h4>Как получить удалённый доступ?</h4>
        <p>Войдите в <a href="/account" style="color:var(--blue);font-weight:600;text-decoration:none;">личный кабинет</a> — после авторизации подписные ресурсы будут доступны из любой точки.</p>
      </div>
      <div class="faq-item">
        <h4>Можно ли скачивать материалы?</h4>
        <p>Зависит от лицензии ресурса. Некоторые материалы доступны только для просмотра.</p>
      </div>
      <div class="faq-item">
        <h4>Нужна помощь с подбором литературы?</h4>
        <p>Обратитесь в библиографический отдел через <a href="/contacts" style="color:var(--blue);font-weight:600;text-decoration:none;">контакты</a> или используйте <a href="/for-teachers" style="color:var(--blue);font-weight:600;text-decoration:none;">раздел для преподавателей</a>.</p>
      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')
<script>
(function() {
  const API_URL = '/api/v1/external-resources';
  const SHORTLIST_API = '/api/v1/shortlist';
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;

  const accessBadgeClass = {
    campus: 'access-badge--campus',
    remote_auth: 'access-badge--remote',
    open: 'access-badge--open'
  };

  const iconColorMap = {
    electronic_library: 'blue',
    research_database: 'violet',
    open_access: 'green',
    analytics: 'pink'
  };

  let allResources = [];
  let categories = {};
  let accessTypes = {};
  let shortlistedIds = new Set();

  function escapeHtml(text) {
    if (!text) return '';
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
  }

  function formatExpiry(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    const months = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
    return `до ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
  }

  function renderFilterBar() {
    const bar = document.getElementById('ext-filter-bar');
    const usedCats = [...new Set(allResources.map(r => r.category))];
    let html = '<button class="ext-filter-btn ext-filter-btn--active" data-filter="all">Все (' + allResources.length + ')</button>';
    usedCats.forEach(cat => {
      const info = categories[cat] || {};
      const count = allResources.filter(r => r.category === cat).length;
      html += `<button class="ext-filter-btn" data-filter="${escapeHtml(cat)}">${escapeHtml(info.icon || '')} ${escapeHtml(info.label || cat)} (${count})</button>`;
    });
    bar.innerHTML = html;
    bar.querySelectorAll('.ext-filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        bar.querySelectorAll('.ext-filter-btn').forEach(b => b.classList.remove('ext-filter-btn--active'));
        btn.classList.add('ext-filter-btn--active');
        renderResources(btn.dataset.filter);
      });
    });
  }

  function renderResources(filter) {
    const grid = document.getElementById('ext-resources-grid');
    const filtered = filter === 'all' ? allResources : allResources.filter(r => r.category === filter);

    grid.innerHTML = filtered.map(r => {
      const catInfo = categories[r.category] || {};
      const accInfo = accessTypes[r.access_type] || {};
      const color = iconColorMap[r.category] || 'blue';
      const badgeClass = accessBadgeClass[r.access_type] || 'access-badge--campus';
      const inShortlist = shortlistedIds.has('ext:' + r.slug);

      return `
        <div class="ext-resource-card" data-slug="${escapeHtml(r.slug)}">
          <div class="ext-resource-card__header">
            <div class="ext-resource-card__icon ext-resource-card__icon--${color}">${escapeHtml(catInfo.icon || '📄')}</div>
            <div>
              <h3 class="ext-resource-card__title">${escapeHtml(r.title)}</h3>
              <div class="ext-resource-card__provider">${escapeHtml(r.provider)}</div>
            </div>
          </div>
          <p class="ext-resource-card__desc">${escapeHtml(r.description)}</p>
          <div class="ext-resource-card__footer">
            <span class="ext-resource-card__badge ${badgeClass}">${escapeHtml(accInfo.label || r.access_type)}</span>
            ${r.expiry_date ? `<span class="ext-resource-card__expiry">Действует ${formatExpiry(r.expiry_date)}</span>` : ''}
          </div>
          <div class="ext-resource-card__actions">
            ${r.url ? `<a href="${escapeHtml(r.url)}" target="_blank" rel="noopener" class="ext-link-btn">Перейти ↗</a>` : ''}
            <button class="ext-shortlist-btn ${inShortlist ? 'ext-shortlist-btn--added' : ''}"
              onclick="addExtToShortlist(this, '${escapeHtml(r.slug)}')"
              ${inShortlist ? 'disabled' : ''}>
              ${inShortlist ? '✓ В подборке' : '+ В подборку'}
            </button>
          </div>
        </div>
      `;
    }).join('');

    grid.style.display = 'grid';
  }

  window.addExtToShortlist = async function(btn, slug) {
    const resource = allResources.find(r => r.slug === slug);
    if (!resource) return;

    btn.disabled = true;
    btn.textContent = '...';

    try {
      const res = await fetch(SHORTLIST_API, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF
        },
        credentials: 'same-origin',
        body: JSON.stringify({
          identifier: 'ext:' + slug,
          title: resource.title,
          type: 'external_resource',
          provider: resource.provider,
          url: resource.url || null,
          access_type: resource.access_type
        })
      });

      if (res.ok || res.status === 409) {
        btn.textContent = '✓ В подборке';
        btn.classList.add('ext-shortlist-btn--added');
        shortlistedIds.add('ext:' + slug);
      } else {
        btn.textContent = '+ В подборку';
        btn.disabled = false;
      }
    } catch (e) {
      btn.textContent = '+ В подборку';
      btn.disabled = false;
    }
  };

  async function loadShortlistState() {
    try {
      const res = await fetch(SHORTLIST_API, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
      if (res.ok) {
        const json = await res.json();
        (json.data || []).forEach(item => {
          if (item.identifier) shortlistedIds.add(item.identifier);
        });
      }
    } catch (_) {}
  }

  async function init() {
    const loading = document.getElementById('ext-resources-loading');
    try {
      const [resResponse] = await Promise.all([
        fetch(API_URL, { headers: { Accept: 'application/json' } }),
        loadShortlistState()
      ]);

      if (!resResponse.ok) throw new Error('API error');

      const json = await resResponse.json();
      allResources = json.data || [];
      categories = json.meta?.categories || {};
      accessTypes = json.meta?.access_types || {};

      document.getElementById('stat-total').textContent = allResources.length + '+';
      loading.style.display = 'none';
      renderFilterBar();
      renderResources('all');
    } catch (e) {
      loading.innerHTML = '<p style="color:var(--muted);">Не удалось загрузить внешние ресурсы.</p>';
      console.error('External resources load error:', e);
    }
  }

  init();
})();
</script>
@endsection
