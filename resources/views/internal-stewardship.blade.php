<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Stewardship — Internal</title>
    <style>
        :root {
            --bg: #f4f1ea;
            --paper: #fffdf9;
            --ink: #1f2937;
            --muted: #6b7280;
            --line: rgba(31, 41, 55, 0.12);
            --accent: #124559;
            --accent-soft: #d9e9ee;
            --danger: #991b1b;
            --danger-soft: #fee2e2;
            --warn: #9a3412;
            --warn-soft: #ffedd5;
            --ok: #166534;
            --ok-soft: #dcfce7;
            --shadow: 0 16px 40px rgba(31, 41, 55, 0.08);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Georgia, 'Times New Roman', serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(18, 69, 89, 0.08), transparent 22%),
                linear-gradient(180deg, #fcfaf5 0%, var(--bg) 100%);
        }

        a { color: inherit; }

        .shell {
            width: min(1200px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 48px;
        }

        .hero, .panel {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: var(--shadow);
        }

        .hero { padding: 28px; }
        .panel { padding: 22px; margin-top: 20px; }

        .eyebrow {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 13px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        h1 {
            margin: 16px 0 8px;
            font-size: clamp(30px, 4vw, 44px);
            line-height: 1.05;
            font-weight: 700;
        }

        h2 { margin: 0 0 14px; font-size: 20px; }

        .subtitle, .meta, .empty { color: var(--muted); }

        /* Navigation row */
        .nav-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 16px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            text-decoration: none;
            font-size: 15px;
        }

        .nav-link.primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        /* Tab bar */
        .tab-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 20px;
        }

        .tab-btn {
            padding: 10px 20px;
            border-radius: 14px 14px 0 0;
            border: 1px solid var(--line);
            border-bottom: none;
            background: #fff;
            color: var(--muted);
            font: inherit;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }

        .tab-btn.active {
            background: var(--paper);
            color: var(--accent);
            font-weight: 700;
            border-color: var(--accent);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Summary cards grid */
        .cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .metric {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 16px;
            min-height: 110px;
        }

        .metric-label {
            color: var(--muted);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .metric-value {
            margin-top: 10px;
            font-size: 34px;
            font-weight: 700;
            line-height: 1;
        }

        .metric-note {
            margin-top: 8px;
            font-size: 13px;
            color: var(--muted);
        }

        .metric.alert {
            background: linear-gradient(180deg, #fff8f7 0%, #fff 100%);
        }

        .metric.soft {
            background: linear-gradient(180deg, #f8fcfd 0%, #fff 100%);
        }

        .metric.accent-bg {
            background: linear-gradient(180deg, var(--accent-soft) 0%, #fff 100%);
        }

        /* Toolbar */
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
            margin-bottom: 16px;
        }

        .field {
            display: grid;
            gap: 6px;
            min-width: 160px;
        }

        .field label {
            font-size: 13px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .select, .input, .button {
            min-height: 44px;
            border-radius: 14px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            padding: 0 14px;
            font: inherit;
            font-size: 14px;
        }

        .button {
            cursor: pointer;
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
            padding: 0 18px;
        }

        .button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .button.secondary {
            background: #fff;
            color: var(--ink);
            border-color: var(--line);
        }

        .button.small {
            min-height: 36px;
            padding: 0 12px;
            font-size: 13px;
            border-radius: 10px;
        }

        .button.danger {
            background: var(--danger);
            border-color: var(--danger);
            color: #fff;
        }

        .button.warn-outline {
            background: #fff;
            border-color: var(--warn);
            color: var(--warn);
        }

        /* Table */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 11px;
            border: 1px solid var(--line);
            background: #fff;
            margin: 1px 2px;
        }

        .badge.reason {
            background: var(--warn-soft);
            color: var(--warn);
            border-color: rgba(154, 52, 18, 0.15);
        }

        .badge.entity {
            background: var(--accent-soft);
            color: var(--accent);
            border-color: rgba(18, 69, 89, 0.15);
        }

        .truncate-id {
            font-family: monospace;
            font-size: 12px;
            color: var(--muted);
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }

        /* Pager */
        .pager {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-top: 16px;
        }

        .pager-actions {
            display: flex;
            gap: 8px;
        }

        /* Inline action form */
        .action-row td {
            background: #fafaf7;
            border-bottom: 2px solid var(--accent);
            padding: 14px 10px;
        }

        .action-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: end;
        }

        .action-form .field { min-width: 200px; }

        .action-form textarea {
            width: 100%;
            min-height: 60px;
            border-radius: 10px;
            border: 1px solid var(--line);
            padding: 10px;
            font: inherit;
            font-size: 14px;
            resize: vertical;
        }

        /* Status / feedback */
        .status-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .status-chip {
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 13px;
            border: 1px solid var(--line);
            background: #fff;
        }

        .status-chip.ok {
            background: var(--ok-soft);
            color: var(--ok);
            border-color: rgba(22, 101, 52, 0.18);
        }

        .status-chip.warn {
            background: var(--warn-soft);
            color: var(--warn);
            border-color: rgba(154, 52, 18, 0.18);
        }

        .error-box {
            margin-top: 16px;
            padding: 14px 16px;
            border-radius: 16px;
            background: var(--danger-soft);
            color: var(--danger);
            border: 1px solid rgba(153, 27, 27, 0.15);
            display: none;
        }

        .success-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 14px 20px;
            border-radius: 14px;
            background: var(--ok-soft);
            color: var(--ok);
            border: 1px solid rgba(22, 101, 52, 0.2);
            font-size: 14px;
            z-index: 1000;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s, transform 0.3s;
            pointer-events: none;
        }

        .success-toast.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Reason code list in overview */
        .reason-list { display: grid; gap: 8px; }

        .reason-item {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            padding: 12px 14px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--line);
            font-size: 14px;
        }

        .reason-item-left {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .reason-count {
            font-weight: 700;
            font-size: 16px;
        }

        @media (max-width: 920px) {
            .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 640px) {
            .shell { width: min(100% - 20px, 1200px); }
            .hero, .panel { padding: 18px; border-radius: 18px; }
            .cards { grid-template-columns: 1fr; }
            .pager { flex-direction: column; align-items: flex-start; }
            .tab-btn { font-size: 13px; padding: 8px 14px; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="hero">
            <div class="eyebrow">Internal Data Stewardship</div>
            <h1>Очереди проверки данных</h1>
            <p class="subtitle">
                Рабочая страница библиотекаря для просмотра и разрешения issues по копиям, документам и читателям.
            </p>
            <div class="nav-row">
                <a class="nav-link primary" href="/internal/dashboard">Dashboard</a>
                <a class="nav-link" href="/internal/review">Quality Issues</a>
                <a class="nav-link" href="/catalog">Каталог</a>
            </div>
            <div class="status-row" id="status-row">
                <div class="status-chip">Загрузка…</div>
            </div>
            <div class="error-box" id="global-error"></div>
        </section>

        <nav class="tab-bar" id="tab-bar">
            <button class="tab-btn active" data-tab="overview">Обзор</button>
            <button class="tab-btn" data-tab="copies">Копии</button>
            <button class="tab-btn" data-tab="documents">Документы</button>
            <button class="tab-btn" data-tab="readers">Читатели</button>
        </nav>

        <!-- ═══════ OVERVIEW TAB ═══════ -->
        <section class="tab-content active" id="tab-overview">
            <div class="panel">
                <h2>Triage Summary</h2>
                <div class="cards" id="triage-cards">
                    <div class="metric soft"><div class="metric-label">Загрузка…</div></div>
                </div>
            </div>
            <div class="panel">
                <h2>Quality Issues</h2>
                <div class="cards" id="qi-cards">
                    <div class="metric soft"><div class="metric-label">Загрузка…</div></div>
                </div>
            </div>
            <div class="panel">
                <h2>Top Reason Codes (все сущности)</h2>
                <div class="reason-list" id="top-reasons">
                    <div class="reason-item"><span class="meta">Загрузка…</span></div>
                </div>
            </div>
        </section>

        <!-- ═══════ COPIES TAB ═══════ -->
        <section class="tab-content" id="tab-copies">
            <div class="panel">
                <h2>Copy Review Summary</h2>
                <div class="cards" id="copy-summary-cards">
                    <div class="metric soft"><div class="metric-label">Загрузка…</div></div>
                </div>
            </div>
            <div class="panel">
                <div class="toolbar">
                    <div class="field">
                        <label for="copy-reason-filter">Reason Code</label>
                        <input id="copy-reason-filter" class="input" type="text" placeholder="e.g. MISSING_DOCUMENT">
                    </div>
                    <button class="button" onclick="loadCopyQueue(1)">Применить</button>
                    <button class="button secondary" onclick="document.getElementById('copy-reason-filter').value='';loadCopyQueue(1)">Сбросить</button>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Copy ID</th>
                                <th>Документ</th>
                                <th>Филиал</th>
                                <th>Сигла</th>
                                <th>Reason Codes</th>
                                <th>Обновлено</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody id="copy-queue-body">
                            <tr><td colspan="7" class="empty">Загрузка…</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="pager">
                    <div id="copy-page-info" class="meta"></div>
                    <div class="pager-actions">
                        <button id="copy-prev" class="button secondary small" onclick="loadCopyQueue(copyPage-1)">← Prev</button>
                        <button id="copy-next" class="button secondary small" onclick="loadCopyQueue(copyPage+1)">Next →</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════ DOCUMENTS TAB ═══════ -->
        <section class="tab-content" id="tab-documents">
            <div class="panel">
                <h2>Document Review Summary</h2>
                <div class="cards" id="doc-summary-cards">
                    <div class="metric soft"><div class="metric-label">Загрузка…</div></div>
                </div>
            </div>
            <div class="panel">
                <div class="toolbar">
                    <div class="field">
                        <label for="doc-reason-filter">Reason Code</label>
                        <input id="doc-reason-filter" class="input" type="text" placeholder="e.g. DUPLICATE_ISBN">
                    </div>
                    <button class="button" onclick="loadDocQueue(1)">Применить</button>
                    <button class="button secondary" onclick="document.getElementById('doc-reason-filter').value='';loadDocQueue(1)">Сбросить</button>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Document ID</th>
                                <th>Заголовок</th>
                                <th>ISBN</th>
                                <th>Reason Codes</th>
                                <th>Обновлено</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody id="doc-queue-body">
                            <tr><td colspan="6" class="empty">Загрузка…</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="pager">
                    <div id="doc-page-info" class="meta"></div>
                    <div class="pager-actions">
                        <button id="doc-prev" class="button secondary small" onclick="loadDocQueue(docPage-1)">← Prev</button>
                        <button id="doc-next" class="button secondary small" onclick="loadDocQueue(docPage+1)">Next →</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════ READERS TAB ═══════ -->
        <section class="tab-content" id="tab-readers">
            <div class="panel">
                <h2>Reader Review Summary</h2>
                <div class="cards" id="reader-summary-cards">
                    <div class="metric soft"><div class="metric-label">Загрузка…</div></div>
                </div>
            </div>
            <div class="panel">
                <div class="toolbar">
                    <div class="field">
                        <label for="reader-reason-filter">Reason Code</label>
                        <input id="reader-reason-filter" class="input" type="text" placeholder="e.g. MISSING_CONTACT">
                    </div>
                    <button class="button" onclick="loadReaderQueue(1)">Применить</button>
                    <button class="button secondary" onclick="document.getElementById('reader-reason-filter').value='';loadReaderQueue(1)">Сбросить</button>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Reader ID</th>
                                <th>ФИО</th>
                                <th>Код (legacy)</th>
                                <th>Регистрация</th>
                                <th>Reason Codes</th>
                                <th>Обновлено</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody id="reader-queue-body">
                            <tr><td colspan="7" class="empty">Загрузка…</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="pager">
                    <div id="reader-page-info" class="meta"></div>
                    <div class="pager-actions">
                        <button id="reader-prev" class="button secondary small" onclick="loadReaderQueue(readerPage-1)">← Prev</button>
                        <button id="reader-next" class="button secondary small" onclick="loadReaderQueue(readerPage+1)">Next →</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="success-toast" id="toast"></div>

    <script>
        // ═══════════════════════════════════════
        // Helpers
        // ═══════════════════════════════════════
        function esc(text) {
            const d = document.createElement('div');
            d.textContent = String(text ?? '');
            return d.innerHTML;
        }

        function fmt(n) {
            const v = Number(n ?? 0);
            return Number.isFinite(v) ? v.toLocaleString('ru-RU') : '0';
        }

        function shortId(uuid) {
            return uuid ? uuid.substring(0, 8) + '…' : '—';
        }

        function shortDate(iso) {
            if (!iso) return '—';
            try {
                const d = new Date(iso);
                return d.toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' });
            } catch { return iso; }
        }

        function reasonBadges(codes) {
            if (!Array.isArray(codes) || !codes.length) return '<span class="meta">—</span>';
            return codes.map(c => `<span class="badge reason">${esc(c)}</span>`).join('');
        }

        function metricHtml(label, value, note, cls) {
            return `<div class="metric ${cls || ''}">
                <div class="metric-label">${esc(label)}</div>
                <div class="metric-value">${esc(fmt(value))}</div>
                ${note ? `<div class="metric-note">${esc(note)}</div>` : ''}
            </div>`;
        }

        async function api(url, opts) {
            const resp = await fetch(url, {
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                ...opts,
            });
            if (!resp.ok) {
                const body = await resp.json().catch(() => ({}));
                throw { status: resp.status, ...body };
            }
            return resp.json();
        }

        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('visible');
            setTimeout(() => t.classList.remove('visible'), 3000);
        }

        function showError(msg) {
            const e = document.getElementById('global-error');
            e.textContent = msg;
            e.style.display = 'block';
            setTimeout(() => { e.style.display = 'none'; }, 8000);
        }

        // ═══════════════════════════════════════
        // Tab navigation
        // ═══════════════════════════════════════
        const tabLoadedFlags = { overview: false, copies: false, documents: false, readers: false };

        document.getElementById('tab-bar').addEventListener('click', (e) => {
            const btn = e.target.closest('.tab-btn');
            if (!btn) return;
            const tab = btn.dataset.tab;

            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');

            if (!tabLoadedFlags[tab]) {
                tabLoadedFlags[tab] = true;
                if (tab === 'copies') { loadCopySummary(); loadCopyQueue(1); }
                if (tab === 'documents') { loadDocSummary(); loadDocQueue(1); }
                if (tab === 'readers') { loadReaderSummary(); loadReaderQueue(1); }
            }
        });

        // ═══════════════════════════════════════
        // OVERVIEW TAB
        // ═══════════════════════════════════════
        async function loadOverview() {
            try {
                const data = await api('/api/v1/internal/review/triage-summary');
                const d = data.data || {};
                const byEntity = d.byEntity || {};
                const qi = d.qualityIssues || {};
                const topRC = d.topReasonCodes || [];

                document.getElementById('triage-cards').innerHTML = [
                    metricHtml('Всего нерешённых', d.totalUnresolved, 'Копии + документы + читатели', 'alert'),
                    metricHtml('Копии', byEntity.copies?.needsReviewCount, `из ${fmt(byEntity.copies?.total)} всего`, 'soft'),
                    metricHtml('Документы', byEntity.documents?.needsReviewCount, `из ${fmt(byEntity.documents?.total)} всего`, 'soft'),
                    metricHtml('Читатели', byEntity.readers?.needsReviewCount, `из ${fmt(byEntity.readers?.total)} всего`, 'soft'),
                ].join('');

                document.getElementById('qi-cards').innerHTML = [
                    metricHtml('Quality Issues', qi.total, 'Всего записей', 'soft'),
                    metricHtml('Открытых', qi.openCount, '', 'warn-soft'),
                    metricHtml('Critical', qi.criticalCount, '', 'alert'),
                    metricHtml('High', qi.highCount, '', 'alert'),
                ].join('');

                if (topRC.length) {
                    document.getElementById('top-reasons').innerHTML = topRC.map(r => `
                        <div class="reason-item">
                            <div class="reason-item-left">
                                <span class="badge reason">${esc(r.reasonCode)}</span>
                                ${(r.entities || []).map(e => `<span class="badge entity">${esc(e)}</span>`).join('')}
                            </div>
                            <span class="reason-count">${fmt(r.count)}</span>
                        </div>
                    `).join('');
                } else {
                    document.getElementById('top-reasons').innerHTML = '<div class="reason-item"><span class="meta">Нет данных</span></div>';
                }

                document.getElementById('status-row').innerHTML = '<div class="status-chip ok">Stewardship API: OK</div>';
            } catch (err) {
                document.getElementById('status-row').innerHTML = '<div class="status-chip warn">Stewardship API: ошибка загрузки</div>';
                showError('Не удалось загрузить triage summary: ' + (err.message || JSON.stringify(err)));
            }
        }

        // ═══════════════════════════════════════
        // COPIES TAB
        // ═══════════════════════════════════════
        let copyPage = 1;
        let copyTotalPages = 1;

        async function loadCopySummary() {
            try {
                const data = await api('/api/v1/internal/review/copies-summary');
                const d = data.data || {};
                const topRC = d.topReasonCodes || [];
                document.getElementById('copy-summary-cards').innerHTML = [
                    metricHtml('Всего копий', d.totalCopies, '', 'soft'),
                    metricHtml('Требуют проверки', d.needsReviewCount, '', 'alert'),
                    metricHtml('Решено', d.resolvedCount, '', 'accent-bg'),
                    metricHtml('Top Reason', topRC[0]?.reasonCode || '—', topRC[0] ? `${fmt(topRC[0].count)} записей` : '', 'soft'),
                ].join('');
            } catch { /* summary is optional */ }
        }

        async function loadCopyQueue(page) {
            page = Math.max(1, page || 1);
            copyPage = page;
            const reason = document.getElementById('copy-reason-filter').value.trim();
            const params = new URLSearchParams({ page, limit: 20 });
            if (reason) params.set('reason_code', reason);

            try {
                const data = await api(`/api/v1/internal/review/copies?${params}`);
                const items = data.data || [];
                const meta = data.meta || {};
                copyTotalPages = meta.totalPages || 1;

                document.getElementById('copy-page-info').textContent = `Стр. ${meta.page || page} из ${copyTotalPages} (всего: ${fmt(meta.total)})`;
                document.getElementById('copy-prev').disabled = copyPage <= 1;
                document.getElementById('copy-next').disabled = copyPage >= copyTotalPages;

                if (!items.length) {
                    document.getElementById('copy-queue-body').innerHTML = '<tr><td colspan="7" class="empty">Нет копий в очереди проверки.</td></tr>';
                    return;
                }

                document.getElementById('copy-queue-body').innerHTML = items.map(item => {
                    const id = item.copyIdentity?.id || '';
                    return `
                    <tr id="copy-row-${id}">
                        <td><span class="truncate-id" title="${esc(id)}">${esc(shortId(id))}</span></td>
                        <td>${esc(item.parentDocument?.titleRaw || '—')}</td>
                        <td>${esc(item.branch?.branchId ? shortId(item.branch.branchId) : '—')}</td>
                        <td>${esc(item.location?.siglaId ? shortId(item.location.siglaId) : '—')}</td>
                        <td>${reasonBadges(item.lifecycle?.reviewReasonCodes)}</td>
                        <td>${esc(shortDate(item.updatedAt))}</td>
                        <td>
                            <button class="button small" onclick="toggleCopyResolve('${esc(id)}')">Решить</button>
                        </td>
                    </tr>
                    <tr class="action-row" id="copy-action-${id}" style="display:none">
                        <td colspan="7">
                            <div class="action-form">
                                <div class="field" style="flex:1">
                                    <label>Заметка (опционально)</label>
                                    <textarea id="copy-note-${id}" placeholder="Описание решения…"></textarea>
                                </div>
                                <button class="button small" onclick="resolveCopy('${esc(id)}')">Подтвердить</button>
                                <button class="button small secondary" onclick="toggleCopyResolve('${esc(id)}')">Отмена</button>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
            } catch (err) {
                document.getElementById('copy-queue-body').innerHTML = `<tr><td colspan="7" class="empty">Ошибка загрузки: ${esc(err.message || '')}</td></tr>`;
            }
        }

        function toggleCopyResolve(id) {
            const row = document.getElementById('copy-action-' + id);
            row.style.display = row.style.display === 'none' ? '' : 'none';
        }

        async function resolveCopy(id) {
            const note = document.getElementById('copy-note-' + id)?.value || '';
            try {
                await api(`/api/v1/internal/review/copies/${id}/resolve`, {
                    method: 'POST',
                    body: JSON.stringify({ resolution_note: note || null }),
                });
                showToast('Копия помечена как решённая');
                loadCopySummary();
                loadCopyQueue(copyPage);
                loadOverview();
            } catch (err) {
                showError('Ошибка: ' + (err.message || err.error || 'Unknown'));
            }
        }

        // ═══════════════════════════════════════
        // DOCUMENTS TAB
        // ═══════════════════════════════════════
        let docPage = 1;
        let docTotalPages = 1;

        async function loadDocSummary() {
            try {
                const data = await api('/api/v1/internal/review/documents-summary');
                const d = data.data || {};
                const topRC = d.topReasonCodes || [];
                document.getElementById('doc-summary-cards').innerHTML = [
                    metricHtml('Всего документов', d.totalDocuments, '', 'soft'),
                    metricHtml('Требуют проверки', d.needsReviewCount, '', 'alert'),
                    metricHtml('Решено', d.resolvedCount, '', 'accent-bg'),
                    metricHtml('Top Reason', topRC[0]?.reasonCode || '—', topRC[0] ? `${fmt(topRC[0].count)} записей` : '', 'soft'),
                ].join('');
            } catch { /* summary is optional */ }
        }

        async function loadDocQueue(page) {
            page = Math.max(1, page || 1);
            docPage = page;
            const reason = document.getElementById('doc-reason-filter').value.trim();
            const params = new URLSearchParams({ page, limit: 20 });
            if (reason) params.set('reason_code', reason);

            try {
                const data = await api(`/api/v1/internal/review/documents?${params}`);
                const items = data.data || [];
                const meta = data.meta || {};
                docTotalPages = meta.totalPages || 1;

                document.getElementById('doc-page-info').textContent = `Стр. ${meta.page || page} из ${docTotalPages} (всего: ${fmt(meta.total)})`;
                document.getElementById('doc-prev').disabled = docPage <= 1;
                document.getElementById('doc-next').disabled = docPage >= docTotalPages;

                if (!items.length) {
                    document.getElementById('doc-queue-body').innerHTML = '<tr><td colspan="6" class="empty">Нет документов в очереди проверки.</td></tr>';
                    return;
                }

                document.getElementById('doc-queue-body').innerHTML = items.map(item => {
                    const id = item.documentIdentity?.id || '';
                    return `
                    <tr id="doc-row-${id}">
                        <td><span class="truncate-id" title="${esc(id)}">${esc(shortId(id))}</span></td>
                        <td>${esc(item.title?.titleRaw || item.title?.titleNormalized || '—')}</td>
                        <td>${esc(item.documentIdentity?.isbnRaw || item.documentIdentity?.isbnNormalized || '—')}</td>
                        <td>${reasonBadges(item.lifecycle?.reviewReasonCodes)}</td>
                        <td>${esc(shortDate(item.updatedAt))}</td>
                        <td>
                            <button class="button small" onclick="toggleDocAction('${esc(id)}')">Решить</button>
                            <button class="button small warn-outline" onclick="toggleDocFlag('${esc(id)}')">Флаг</button>
                        </td>
                    </tr>
                    <tr class="action-row" id="doc-resolve-${id}" style="display:none">
                        <td colspan="6">
                            <div class="action-form">
                                <div class="field" style="flex:1">
                                    <label>Заметка решения (опционально)</label>
                                    <textarea id="doc-resolve-note-${id}" placeholder="Описание решения…"></textarea>
                                </div>
                                <button class="button small" onclick="resolveDoc('${esc(id)}')">Подтвердить</button>
                                <button class="button small secondary" onclick="toggleDocAction('${esc(id)}')">Отмена</button>
                            </div>
                        </td>
                    </tr>
                    <tr class="action-row" id="doc-flag-${id}" style="display:none">
                        <td colspan="6">
                            <div class="action-form">
                                <div class="field">
                                    <label>Reason Codes (через запятую)</label>
                                    <input id="doc-flag-codes-${id}" class="input" type="text" placeholder="DUPLICATE_ISBN, MISSING_AUTHOR">
                                </div>
                                <div class="field" style="flex:1">
                                    <label>Заметка (опционально)</label>
                                    <textarea id="doc-flag-note-${id}" placeholder="Почему документ нужно проверить…"></textarea>
                                </div>
                                <button class="button small danger" onclick="flagDoc('${esc(id)}')">Отправить флаг</button>
                                <button class="button small secondary" onclick="toggleDocFlag('${esc(id)}')">Отмена</button>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
            } catch (err) {
                document.getElementById('doc-queue-body').innerHTML = `<tr><td colspan="6" class="empty">Ошибка загрузки: ${esc(err.message || '')}</td></tr>`;
            }
        }

        function toggleDocAction(id) {
            const row = document.getElementById('doc-resolve-' + id);
            document.getElementById('doc-flag-' + id).style.display = 'none';
            row.style.display = row.style.display === 'none' ? '' : 'none';
        }

        function toggleDocFlag(id) {
            const row = document.getElementById('doc-flag-' + id);
            document.getElementById('doc-resolve-' + id).style.display = 'none';
            row.style.display = row.style.display === 'none' ? '' : 'none';
        }

        async function resolveDoc(id) {
            const note = document.getElementById('doc-resolve-note-' + id)?.value || '';
            try {
                await api(`/api/v1/internal/review/documents/${id}/resolve`, {
                    method: 'POST',
                    body: JSON.stringify({ resolution_note: note || null }),
                });
                showToast('Документ помечен как решённый');
                loadDocSummary();
                loadDocQueue(docPage);
                loadOverview();
            } catch (err) {
                showError('Ошибка: ' + (err.message || err.error || 'Unknown'));
            }
        }

        async function flagDoc(id) {
            const codesRaw = document.getElementById('doc-flag-codes-' + id)?.value || '';
            const codes = codesRaw.split(',').map(s => s.trim().toUpperCase()).filter(Boolean);
            if (!codes.length) { showError('Укажите хотя бы один reason code'); return; }
            const note = document.getElementById('doc-flag-note-' + id)?.value || '';
            try {
                await api(`/api/v1/internal/review/documents/${id}/flag`, {
                    method: 'POST',
                    body: JSON.stringify({ reason_codes: codes, flag_note: note || null }),
                });
                showToast('Документ отмечен для проверки');
                loadDocSummary();
                loadDocQueue(docPage);
                loadOverview();
            } catch (err) {
                showError('Ошибка: ' + (err.message || err.error || 'Unknown'));
            }
        }

        // ═══════════════════════════════════════
        // READERS TAB
        // ═══════════════════════════════════════
        let readerPage = 1;
        let readerTotalPages = 1;

        async function loadReaderSummary() {
            try {
                const data = await api('/api/v1/internal/review/readers-summary');
                const d = data.data || {};
                const topRC = d.topReasonCodes || [];
                document.getElementById('reader-summary-cards').innerHTML = [
                    metricHtml('Всего читателей', d.totalReaders, '', 'soft'),
                    metricHtml('Требуют проверки', d.needsReviewCount, '', 'alert'),
                    metricHtml('Решено', d.resolvedCount, '', 'accent-bg'),
                    metricHtml('Top Reason', topRC[0]?.reasonCode || '—', topRC[0] ? `${fmt(topRC[0].count)} записей` : '', 'soft'),
                ].join('');
            } catch { /* summary is optional */ }
        }

        async function loadReaderQueue(page) {
            page = Math.max(1, page || 1);
            readerPage = page;
            const reason = document.getElementById('reader-reason-filter').value.trim();
            const params = new URLSearchParams({ page, limit: 20 });
            if (reason) params.set('reason_code', reason);

            try {
                const data = await api(`/api/v1/internal/review/readers?${params}`);
                const items = data.data || [];
                const meta = data.meta || {};
                readerTotalPages = meta.totalPages || 1;

                document.getElementById('reader-page-info').textContent = `Стр. ${meta.page || page} из ${readerTotalPages} (всего: ${fmt(meta.total)})`;
                document.getElementById('reader-prev').disabled = readerPage <= 1;
                document.getElementById('reader-next').disabled = readerPage >= readerTotalPages;

                if (!items.length) {
                    document.getElementById('reader-queue-body').innerHTML = '<tr><td colspan="7" class="empty">Нет читателей в очереди проверки.</td></tr>';
                    return;
                }

                document.getElementById('reader-queue-body').innerHTML = items.map(item => {
                    const id = item.readerIdentity?.id || '';
                    return `
                    <tr id="reader-row-${id}">
                        <td><span class="truncate-id" title="${esc(id)}">${esc(shortId(id))}</span></td>
                        <td>${esc(item.readerIdentity?.fullName || '—')}</td>
                        <td>${esc(item.readerIdentity?.legacyCode || '—')}</td>
                        <td>${esc(shortDate(item.lifecycle?.registrationAt))}</td>
                        <td>${reasonBadges(item.lifecycle?.reviewReasonCodes)}</td>
                        <td>${esc(shortDate(item.updatedAt))}</td>
                        <td>
                            <button class="button small" onclick="toggleReaderResolve('${esc(id)}')">Решить</button>
                        </td>
                    </tr>
                    <tr class="action-row" id="reader-action-${id}" style="display:none">
                        <td colspan="7">
                            <div class="action-form">
                                <div class="field" style="flex:1">
                                    <label>Заметка (опционально)</label>
                                    <textarea id="reader-note-${id}" placeholder="Описание решения…"></textarea>
                                </div>
                                <button class="button small" onclick="resolveReader('${esc(id)}')">Подтвердить</button>
                                <button class="button small secondary" onclick="toggleReaderResolve('${esc(id)}')">Отмена</button>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
            } catch (err) {
                document.getElementById('reader-queue-body').innerHTML = `<tr><td colspan="7" class="empty">Ошибка загрузки: ${esc(err.message || '')}</td></tr>`;
            }
        }

        function toggleReaderResolve(id) {
            const row = document.getElementById('reader-action-' + id);
            row.style.display = row.style.display === 'none' ? '' : 'none';
        }

        async function resolveReader(id) {
            const note = document.getElementById('reader-note-' + id)?.value || '';
            try {
                await api(`/api/v1/internal/review/readers/${id}/resolve`, {
                    method: 'POST',
                    body: JSON.stringify({ resolution_note: note || null }),
                });
                showToast('Читатель помечен как решённый');
                loadReaderSummary();
                loadReaderQueue(readerPage);
                loadOverview();
            } catch (err) {
                showError('Ошибка: ' + (err.message || err.error || 'Unknown'));
            }
        }

        // ═══════════════════════════════════════
        // Initial load
        // ═══════════════════════════════════════
        tabLoadedFlags.overview = true;
        loadOverview();
    </script>
</body>
</html>
