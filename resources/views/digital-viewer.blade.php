@extends('layouts.public', ['activePage' => ''])

@section('title', 'Электронный просмотр — Digital Library')

@section('head')
<style>
  .viewer-wrap {
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - 160px);
  }
  .viewer-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 12px 24px;
    background: var(--surface-glass);
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
  }
  .viewer-toolbar-left {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
  }
  .viewer-title {
    font-weight: 700;
    font-size: 15px;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 500px;
  }
  .viewer-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: var(--muted);
    flex-shrink: 0;
  }
  .viewer-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    background: rgba(59,130,246,.08);
    color: var(--blue);
  }
  .viewer-frame {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    min-height: 600px;
  }
  .viewer-frame iframe,
  .viewer-frame embed {
    width: 100%;
    height: 100%;
    border: none;
    min-height: 600px;
  }
  .viewer-error,
  .viewer-denied {
    text-align: center;
    padding: 48px 24px;
    max-width: 500px;
  }
  .viewer-error h2,
  .viewer-denied h2 {
    font-size: 22px;
    font-weight: 800;
    margin: 0 0 12px;
  }
  .viewer-error p,
  .viewer-denied p {
    color: var(--muted);
    font-size: 15px;
    line-height: 1.65;
    margin: 0 0 20px;
  }
  .viewer-denied .lock-icon {
    font-size: 48px;
    margin-bottom: 16px;
  }
  .viewer-loading {
    text-align: center;
    padding: 48px;
    color: var(--muted);
  }
  .viewer-loading .spinner {
    width: 36px;
    height: 36px;
    border: 3px solid #e2e8f0;
    border-top-color: var(--blue);
    border-radius: 50%;
    animation: spin .8s linear infinite;
    margin: 0 auto 12px;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  @media (max-width: 768px) {
    .viewer-toolbar { padding: 10px 16px; flex-wrap: wrap; }
    .viewer-title { max-width: 200px; font-size: 14px; }
    .viewer-frame { min-height: 400px; }
    .viewer-frame iframe { min-height: 400px; }
  }
</style>
@endsection

@section('content')
<div class="viewer-wrap" id="viewer-root">
  <div class="viewer-loading" id="viewer-loading">
    <div class="spinner"></div>
    <p>Загрузка материала...</p>
  </div>
</div>

<script>
  const MATERIAL_ID = @json($materialId);
  const STREAM_URL = `/api/v1/digital-materials/${encodeURIComponent(MATERIAL_ID)}/stream`;
  const IS_AUTHENTICATED = @json(session('library.user') !== null);

  async function initViewer() {
    const root = document.getElementById('viewer-root');
    const loading = document.getElementById('viewer-loading');

    try {
      const resp = await fetch(STREAM_URL, { method: 'HEAD' });

      if (resp.status === 403) {
        const data = await (await fetch(STREAM_URL)).json().catch(() => null);
        const reason = data?.error || 'Доступ к материалу ограничен.';
        loading.remove();
        const loginBtn = !IS_AUTHENTICATED
          ? `<a href="/login?redirect=${encodeURIComponent(window.location.pathname)}" class="btn btn-primary" style="margin-bottom:8px;">Войти в систему</a>`
          : '';
        root.innerHTML = `
          <div class="viewer-toolbar">
            <div class="viewer-toolbar-left">
              <a href="javascript:history.back()" class="btn btn-ghost" style="padding:6px 14px;font-size:13px;">← Назад</a>
              <span class="viewer-title">Ограниченный доступ</span>
            </div>
          </div>
          <div class="viewer-frame">
            <div class="viewer-denied">
              <div class="lock-icon">🔒</div>
              <h2>Доступ ограничен</h2>
              <p>${escapeHtml(reason)}</p>
              ${loginBtn}
              <a href="javascript:history.back()" class="btn btn-ghost">Вернуться</a>
            </div>
          </div>`;
        return;
      }

      if (resp.status === 404) {
        loading.remove();
        root.innerHTML = `
          <div class="viewer-toolbar">
            <div class="viewer-toolbar-left">
              <a href="javascript:history.back()" class="btn btn-ghost" style="padding:6px 14px;font-size:13px;">← Назад</a>
              <span class="viewer-title">Не найдено</span>
            </div>
          </div>
          <div class="viewer-frame">
            <div class="viewer-error">
              <h2>Материал не найден</h2>
              <p>Запрошенный электронный материал не существует или был удалён.</p>
              <a href="/catalog" class="btn btn-primary">Перейти в каталог</a>
            </div>
          </div>`;
        return;
      }

      if (!resp.ok) {
        throw new Error('Ошибка загрузки');
      }

      const contentType = resp.headers.get('Content-Type') || '';
      const contentDisp = resp.headers.get('Content-Disposition') || '';
      const filenameMatch = contentDisp.match(/filename="?([^"]+)"?/);
      const filename = filenameMatch ? filenameMatch[1] : 'document';
      const fileSize = resp.headers.get('Content-Length');
      const isPdf = contentType.includes('pdf');

      loading.remove();

      const toolbarHtml = `
        <div class="viewer-toolbar">
          <div class="viewer-toolbar-left">
            <a href="javascript:history.back()" class="btn btn-ghost" style="padding:6px 14px;font-size:13px;">← Назад</a>
            <span class="viewer-title">${escapeHtml(filename)}</span>
          </div>
          <div class="viewer-meta">
            ${isPdf ? '<span class="viewer-badge">📄 PDF</span>' : '<span class="viewer-badge">📁 Документ</span>'}
            ${fileSize ? `<span>${humanSize(parseInt(fileSize))}</span>` : ''}
          </div>
        </div>`;

      if (isPdf) {
        root.innerHTML = toolbarHtml +
          `<div class="viewer-frame">
            <iframe src="${STREAM_URL}#toolbar=0&navpanes=0" title="Просмотр документа"></iframe>
          </div>`;
      } else {
        root.innerHTML = toolbarHtml +
          `<div class="viewer-frame">
            <div class="viewer-error">
              <h2>Предпросмотр недоступен</h2>
              <p>Формат этого файла не поддерживает встроенный просмотр. Обратитесь в библиотеку для доступа.</p>
              <a href="javascript:history.back()" class="btn btn-primary">Вернуться</a>
            </div>
          </div>`;
      }

    } catch (err) {
      loading.remove();
      root.innerHTML = `
        <div class="viewer-frame">
          <div class="viewer-error">
            <h2>Ошибка</h2>
            <p>Не удалось загрузить материал. Попробуйте позже.</p>
            <a href="javascript:history.back()" class="btn btn-ghost">Вернуться</a>
          </div>
        </div>`;
    }
  }

  function escapeHtml(text) {
    if (!text) return '';
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
  }

  function humanSize(bytes) {
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(1) + ' МБ';
    if (bytes >= 1024) return Math.round(bytes / 1024) + ' КБ';
    return bytes + ' Б';
  }

  initViewer();
</script>
@endsection
