<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Dashboard</title>
    <style>
        :root {
            --bg: #f8f9fa;
            --paper: rgba(255, 255, 255, 0.96);
            --ink: #191c1d;
            --muted: #43474f;
            --line: rgba(195, 198, 209, 0.55);
            --accent: #001e40;
            --accent-soft: rgba(0, 30, 64, 0.05);
            --warn: #5d4201;
            --warn-soft: rgba(93, 66, 1, 0.10);
            --danger: #ba1a1a;
            --danger-soft: rgba(186, 26, 26, 0.08);
            --ok: #14696d;
            --ok-soft: rgba(20, 105, 109, 0.10);
            --shadow: 0 12px 32px rgba(25, 28, 29, 0.04);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Manrope', system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(0, 30, 64, 0.04), transparent 20%),
                linear-gradient(180deg, #fbfcfc 0%, var(--bg) 100%);
        }

        a { color: inherit; }

        .shell {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 48px;
        }

        .hero {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 28px;
            box-shadow: var(--shadow);
        }

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
            font-size: clamp(32px, 5vw, 48px);
            line-height: 1.05;
            font-weight: 600;
            font-family: 'Newsreader', Georgia, serif;
            letter-spacing: -0.03em;
            color: var(--accent);
        }

        .subtitle {
            margin: 0;
            color: var(--muted);
            font-size: 17px;
            max-width: 760px;
        }

        .status-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

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
            border-radius: 8px;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            text-decoration: none;
            font-size: 15px;
            transition: background-color .18s ease, border-color .18s ease, transform .18s ease;
        }

        .nav-link:hover {
            transform: translate3d(0, -1px, 0);
            border-color: rgba(0,30,64,.14);
            background: rgba(243,244,245,.96);
        }

        .nav-link.primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
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

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 18px;
            margin-top: 22px;
        }

        .panel {
            grid-column: span 12;
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 22px;
            box-shadow: var(--shadow);
        }

        .panel h2 {
            margin: 0 0 14px;
            font-size: 20px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .metric {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 16px;
            min-height: 124px;
            transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .metric:hover {
            transform: translate3d(0, -2px, 0);
            border-color: rgba(20,105,109,.18);
            box-shadow: 0 12px 24px rgba(25, 28, 29, 0.04);
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
            margin-top: 10px;
            font-size: 14px;
            color: var(--muted);
        }

        .metric.alert {
            background: linear-gradient(180deg, #fff8f7 0%, #fff 100%);
        }

        .metric.soft {
            background: linear-gradient(180deg, #f8fcfd 0%, #fff 100%);
        }

        .issue-list {
            display: grid;
            gap: 10px;
        }

        .issue-item {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 16px;
            border-radius: 8px;
            background: #fff;
            border: 1px solid var(--line);
            font-size: 15px;
            transition: transform .18s ease, border-color .18s ease, background-color .18s ease;
        }

        .issue-item:hover {
            transform: translate3d(0, -1px, 0);
            border-color: rgba(0,30,64,.12);
            background: rgba(243,244,245,.96);
        }

        .issue-count {
            font-weight: 700;
        }

        .source-note {
            margin-top: 14px;
            font-size: 13px;
            color: var(--muted);
        }

        .error-box {
            margin-top: 18px;
            padding: 14px 16px;
            border-radius: 8px;
            background: var(--danger-soft);
            color: var(--danger);
            border: 1px solid rgba(153, 27, 27, 0.15);
            display: none;
        }

        @media (max-width: 920px) {
            .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 640px) {
            .shell { width: min(100% - 20px, 1120px); }
            .hero, .panel { padding: 18px; border-radius: 8px; }
            .cards { grid-template-columns: 1fr; }
            .issue-item { flex-direction: column; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="hero">
            <div class="eyebrow">Internal Read-Only Dashboard</div>
            <h1>Состояние библиотечной базы</h1>
            <p class="subtitle">
                Компактная внутренняя обзорная страница, которая читает только уже существующие DB-backed summary endpoints.
            </p>
            <div class="nav-row">
                <a class="nav-link primary" href="/internal/review">Открыть список quality issues</a>
                <a class="nav-link primary" href="/internal/stewardship">Data Stewardship</a>
                <a class="nav-link primary" href="/internal/circulation">Circulation Desk</a>
                <a class="nav-link" href="/catalog">Перейти в каталог</a>
            </div>
            <div class="status-row" id="status-row">
                <div class="status-chip">Загрузка library summary…</div>
                <div class="status-chip">Загрузка review summary…</div>
            </div>
            <div class="error-box" id="dashboard-error"></div>
        </section>

        <section class="grid">
            <article class="panel">
                <h2>Core Library Metrics</h2>
                <div class="cards" id="core-metrics"></div>
            </article>

            <article class="panel">
                <h2>Data Quality Signals</h2>
                <div class="cards" id="quality-metrics"></div>
            </article>

            <article class="panel">
                <h2>Top Issue Codes</h2>
                <div class="issue-list" id="top-issue-codes"></div>
                <div class="source-note" id="source-note"></div>
            </article>
        </section>
    </main>

    <script>
        const HEALTH_SUMMARY_ENDPOINT = '/api/v1/library/health-summary';
        const REVIEW_SUMMARY_ENDPOINT = '/api/v1/review/issues-summary?top_limit=5';

        function formatNumber(value) {
            const number = Number(value ?? 0);
            return Number.isFinite(number) ? number.toLocaleString('ru-RU') : '0';
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = String(text ?? '');
            return div.innerHTML;
        }

        function metricCard(label, value, note = '', cardClass = '') {
            return `
                <div class="metric ${cardClass}">
                    <div class="metric-label">${escapeHtml(label)}</div>
                    <div class="metric-value">${escapeHtml(formatNumber(value))}</div>
                    <div class="metric-note">${escapeHtml(note)}</div>
                </div>
            `;
        }

        async function fetchJson(url) {
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            return response.json();
        }

        function renderStatus(healthOk, reviewOk) {
            const statusRow = document.getElementById('status-row');
            statusRow.innerHTML = `
                <div class="status-chip ${healthOk ? 'ok' : 'warn'}">Library health: ${healthOk ? 'OK' : 'Unavailable'}</div>
                <div class="status-chip ${reviewOk ? 'ok' : 'warn'}">Review summary: ${reviewOk ? 'OK' : 'Unavailable'}</div>
            `;
        }

        function renderHealthSummary(payload) {
            const data = payload?.data || {};
            const coreMetrics = document.getElementById('core-metrics');
            const qualityMetrics = document.getElementById('quality-metrics');

            coreMetrics.innerHTML = [
                metricCard('Documents', data.totalDocuments, 'Всего библиографических записей', 'soft'),
                metricCard('Copies', data.totalCopies, 'Всего инвентарных экземпляров', 'soft'),
                metricCard('Readers', data.totalReaders, 'Всего читательских записей', 'soft'),
                metricCard('Quality Issues', data.totalQualityIssues, 'Всего зарегистрированных quality issues', 'alert'),
            ].join('');

            qualityMetrics.innerHTML = [
                metricCard('Duplicate ISBN Groups', data.duplicateIsbnGroups, 'Потенциальные дубликаты по ISBN', 'alert'),
                metricCard('Orphan Copies', data.orphanCopies, 'Экземпляры без связанного документа', 'alert'),
                metricCard('Without ISBN', data.documentsWithoutIsbn, 'Документы без нормализованного ISBN', 'alert'),
                metricCard('Without Author', data.documentsWithoutAuthor, 'Документы без авторской связи', 'alert'),
                metricCard('Needs Review Docs', data.documentsNeedsReview, 'Документы с флагом проверки'),
                metricCard('Needs Review Copies', data.copiesNeedsReview, 'Экземпляры с флагом проверки'),
                metricCard('Needs Review Readers', data.readersNeedsReview, 'Читатели с флагом проверки'),
                metricCard('Without Publisher', data.documentsWithoutPublisher, 'Документы без издателя'),
            ].join('');
        }

        function renderReviewSummary(payload) {
            const data = payload?.data || {};
            const list = document.getElementById('top-issue-codes');
            const sourceNote = document.getElementById('source-note');
            const topIssueCodes = Array.isArray(data.topIssueCodes) ? data.topIssueCodes : [];

            list.innerHTML = [
                metricCard('Critical', data.criticalCount, 'Критические issues', 'alert'),
                metricCard('High', data.highCount, 'Высокий приоритет', 'alert'),
                metricCard('Open', data.openCount, 'Открытые issues', 'soft'),
            ].join('');

            const issueItems = topIssueCodes.map((item) => `
                <div class="issue-item">
                    <span>${escapeHtml(item.issueCode || 'Unknown issue')}</span>
                    <span class="issue-count">${escapeHtml(formatNumber(item.count))}</span>
                </div>
            `).join('');

            list.innerHTML += issueItems || '<div class="issue-item"><span>Нет данных по issue codes</span><span class="issue-count">0</span></div>';
            sourceNote.textContent = `Sources: ${payload?.source || 'review.quality_issues'}`;
        }

        async function loadDashboard() {
            const errorBox = document.getElementById('dashboard-error');
            const [healthResult, reviewResult] = await Promise.allSettled([
                fetchJson(HEALTH_SUMMARY_ENDPOINT),
                fetchJson(REVIEW_SUMMARY_ENDPOINT),
            ]);

            const healthOk = healthResult.status === 'fulfilled';
            const reviewOk = reviewResult.status === 'fulfilled';

            renderStatus(healthOk, reviewOk);

            if (healthOk) {
                renderHealthSummary(healthResult.value);
            }

            if (reviewOk) {
                renderReviewSummary(reviewResult.value);
            }

            if (!healthOk || !reviewOk) {
                errorBox.style.display = 'block';
                errorBox.textContent = 'Часть summary endpoints недоступна. Страница остаётся в read-only режиме и показывает доступные блоки.';
            }
        }

        loadDashboard().catch(() => {
            const errorBox = document.getElementById('dashboard-error');
            errorBox.style.display = 'block';
            errorBox.textContent = 'Не удалось загрузить dashboard summary данные.';
        });
    </script>
</body>
</html>
