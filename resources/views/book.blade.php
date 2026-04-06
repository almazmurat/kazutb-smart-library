<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Просмотр книги — Library Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/shell.css">
    <style>
        :root {
            --bg: #f5f7fb;
            --surface: #ffffff;
            --surface-soft: #f8fbff;
            --border: rgba(15, 23, 42, .08);
            --text: #14213d;
            --muted: #64748b;
            --blue: #3b82f6;
            --cyan: #06b6d4;
            --violet: #7c3aed;
            --pink: #ec4899;
            --gold: #d6a85f;
            --success: #16a34a;
            --warning: #f59e0b;
            --shadow: 0 20px 50px rgba(15, 23, 42, .08);
            --shadow-soft: 0 12px 26px rgba(15, 23, 42, .05);
            --radius-xl: 30px;
            --radius-lg: 24px;
            --radius-md: 18px;
            --container: 1650px;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 10% 10%, rgba(59,130,246,.08), transparent 18%),
                radial-gradient(circle at 90% 10%, rgba(236,72,153,.06), transparent 16%),
                linear-gradient(180deg, #ffffff 0%, #f7f9fd 42%, #f3f6fb 100%);
        }

        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }

        .container {
            width: min(100% - 32px, var(--container));
            margin: 0 auto;
        }

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

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            border: 0;
            cursor: pointer;
            font: inherit;
            border-radius: 16px;
            padding: 14px 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: .25s ease;
            font-weight: 700;
        }

        .btn:hover { transform: translateY(-2px); }

        .btn-primary {
            color: white;
            background: linear-gradient(135deg, var(--blue), var(--cyan));
            box-shadow: 0 16px 30px rgba(59,130,246,.22);
        }

        .btn-secondary {
            color: white;
            background: linear-gradient(135deg, var(--pink), var(--violet));
            box-shadow: 0 16px 30px rgba(124,58,237,.18);
        }

        .btn-ghost {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text);
            box-shadow: var(--shadow-soft);
        }

        .page {
            padding: 34px 0 70px;
        }

        .breadcrumbs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 22px;
        }

        .breadcrumbs span:last-child {
            color: var(--text);
            font-weight: 600;
        }

        .layout {
            display: grid;
            grid-template-columns: .88fr 1.12fr;
            gap: 22px;
            align-items: start;
        }

        .card {
            background: rgba(255,255,255,.95);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            border-radius: var(--radius-xl);
        }

        .book-panel {
            padding: 26px;
            position: sticky;
            top: 104px;
        }

        .book-cover-wrap {
            border-radius: 24px;
            min-height: 580px;
            padding: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at 20% 20%, rgba(59,130,246,.10), transparent 18%),
                radial-gradient(circle at 80% 20%, rgba(236,72,153,.08), transparent 18%),
                linear-gradient(180deg, #f4f7ff 0%, #eef3fc 100%);
            overflow: hidden;
        }

        .book-mockup {
            width: 310px;
            max-width: 100%;
            height: 450px;
            border-radius: 20px;
            padding: 26px 24px 26px 30px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            position: relative;
            background: linear-gradient(180deg, #2d4268 0%, #223758 100%);
            box-shadow: 0 24px 45px rgba(37, 58, 97, .24);
            overflow: hidden;
        }

        .book-mockup::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 14px;
            background: rgba(0,0,0,.18);
        }

        .book-mockup::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,0));
        }

        .cover-top {
            position: absolute;
            top: 28px;
            left: 30px;
            right: 24px;
            z-index: 1;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: rgba(255,255,255,.55);
        }

        .cover-title {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #f1d08e;
            font-size: 40px;
            line-height: .95;
            letter-spacing: -1.3px;
            max-width: 220px;
        }

        .cover-author {
            position: relative;
            z-index: 1;
            margin-top: 18px;
            color: rgba(255,255,255,.72);
            font-size: 15px;
            font-weight: 500;
        }

        .cover-badge {
            position: absolute;
            right: 22px;
            top: 22px;
            z-index: 1;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,.12);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            backdrop-filter: blur(10px);
        }

        .mini-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 18px;
        }

        .mini-action {
            padding: 14px;
            border-radius: 18px;
            background: var(--surface-soft);
            border: 1px solid var(--border);
            text-align: center;
            font-weight: 600;
            color: #334155;
        }

        .details-card {
            padding: 28px;
        }

        .badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
        }

        .badge {
            padding: 10px 14px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
            border: 1px solid transparent;
        }

        .badge-blue {
            background: rgba(59,130,246,.08);
            color: var(--blue);
            border-color: rgba(59,130,246,.12);
        }

        .badge-green {
            background: rgba(22,163,74,.08);
            color: var(--success);
            border-color: rgba(22,163,74,.12);
        }

        .title {
            margin: 0;
            font-size: clamp(34px, 5vw, 56px);
            line-height: .98;
            letter-spacing: -1.8px;
            max-width: 760px;
        }

        .subtitle {
            margin: 14px 0 0;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.8;
            max-width: 840px;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin: 28px 0;
        }

        .meta-item {
            padding: 18px;
            border-radius: 20px;
            background: var(--surface-soft);
            border: 1px solid var(--border);
        }

        .meta-label {
            display: block;
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 6px;
        }

        .meta-value {
            display: block;
            font-weight: 800;
            font-size: 17px;
            color: var(--text);
        }

        .section-title {
            margin: 0 0 14px;
            font-size: 24px;
            letter-spacing: -.6px;
        }

        .text-block {
            color: var(--muted);
            line-height: 1.9;
            font-size: 16px;
        }

        .action-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 24px 0 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-top: 24px;
        }

        .info-card {
            padding: 22px;
        }

        .info-list {
            display: grid;
            gap: 14px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-child { border-bottom: 0; padding-bottom: 0; }

        .info-row span:first-child {
            color: var(--muted);
        }

        .info-row span:last-child {
            font-weight: 700;
            text-align: right;
        }

        .status-box {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            border-radius: 20px;
            margin-top: 20px;
            background: linear-gradient(135deg, rgba(22,163,74,.08), rgba(6,182,212,.06));
            border: 1px solid rgba(22,163,74,.12);
        }

        .status-box.unavailable {
            background: linear-gradient(135deg, rgba(239,68,68,.08), rgba(236,72,153,.06));
            border-color: rgba(239,68,68,.12);
        }

        .status-box strong {
            display: block;
            margin-bottom: 6px;
            font-size: 18px;
        }

        .status-box p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
        }

        .status-pill {
            padding: 10px 14px;
            border-radius: 999px;
            background: #fff;
            color: var(--success);
            font-weight: 800;
            white-space: nowrap;
            border: 1px solid rgba(22,163,74,.12);
        }

        .status-pill.unavailable {
            color: #dc2626;
            border-color: rgba(239,68,68,.12);
        }

        .cards-section {
            margin-top: 22px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .book-card {
            padding: 18px;
            border-radius: 24px;
            background: #fff;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-soft);
        }

        .book-preview {
            height: 220px;
            border-radius: 18px;
            padding: 18px;
            display: flex;
            align-items: flex-end;
            background: linear-gradient(180deg, #2d4268 0%, #223758 100%);
            position: relative;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .book-card:nth-child(2) .book-preview {
            background: linear-gradient(180deg, #8f1f1f 0%, #6d1111 100%);
        }

        .book-card:nth-child(3) .book-preview {
            background: linear-gradient(180deg, #205f43 0%, #134935 100%);
        }

        .book-preview::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 10px;
            background: rgba(0,0,0,.18);
        }

        .book-preview small {
            position: absolute;
            left: 18px;
            top: 18px;
            color: rgba(255,255,255,.58);
            font-size: 11px;
            letter-spacing: .16em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .book-preview h4 {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #f1d08e;
            font-size: 24px;
            line-height: 1;
            letter-spacing: -.8px;
            max-width: 150px;
        }

        .book-card-title {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .book-card p {
            margin: 0 0 16px;
            color: var(--muted);
            line-height: 1.7;
            font-size: 14px;
        }

        .book-card .btn {
            width: 100%;
        }

        .locations-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 10px;
        }

        .locations-table th,
        .locations-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .locations-table th {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
        }

        .locations-table .avail-count {
            font-weight: 700;
            color: var(--success);
        }

        .locations-table .zero-count {
            color: var(--muted);
        }

        .authors-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .author-chip {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 999px;
            background: var(--surface-soft);
            border: 1px solid var(--border);
            font-size: 13px;
        }

        .review-notice {
            margin-top: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            background: #fef3c7;
            border: 1px solid rgba(245, 158, 11, .2);
            font-size: 13px;
            color: #92400e;
        }

        .review-notice .reason-badge {
            display: inline-flex;
            padding: 2px 8px;
            border-radius: 999px;
            background: #ffedd5;
            border: 1px solid rgba(154, 52, 18, .15);
            font-size: 11px;
            color: #9a3412;
            margin: 0 2px;
        }

        .classification-section {
            margin-top: 16px;
            padding: 16px;
            background: linear-gradient(135deg, rgba(124,58,237,.04), rgba(59,130,246,.04));
            border: 1px solid rgba(124,58,237,.12);
            border-radius: 16px;
        }

        .classification-section h4 {
            margin: 0 0 10px;
            font-size: 13px;
            font-weight: 600;
            color: #6d28d9;
            letter-spacing: .02em;
        }

        .classification-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .classification-chip {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }

        .classification-chip.specialization {
            background: rgba(124,58,237,.08);
            color: #6d28d9;
            border: 1px solid rgba(124,58,237,.15);
        }

        .classification-chip.department {
            background: rgba(59,130,246,.08);
            color: #2563eb;
            border: 1px solid rgba(59,130,246,.15);
        }

        .classification-chip.faculty {
            background: rgba(6,182,212,.08);
            color: #0891b2;
            border: 1px solid rgba(6,182,212,.15);
        }

        .classification-chip:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
        }

        .loading {
            text-align: center;
            padding: 3rem;
            font-size: 1.125rem;
            color: var(--muted);
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner {
            display: inline-block;
            width: 32px; height: 32px;
            border: 3px solid #e5e7eb;
            border-top-color: var(--blue);
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        .error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin: 2rem 0;
            text-align: center;
        }

        @media (max-width: 1120px) {
            .layout,
            .info-grid,
            .cards-section,
            .meta-grid {
                grid-template-columns: 1fr 1fr;
            }

            .layout { grid-template-columns: 1fr; }
            .book-panel { position: static; }
            .meta-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 860px) {
            .nav-links { display: none; }
            .meta-grid,
            .info-grid,
            .cards-section,
            .mini-actions {
                grid-template-columns: 1fr;
            }

            .action-row {
                flex-direction: column;
            }

            .action-row .btn,
            .nav-actions .btn {
                width: 100%;
                min-height: 44px;
            }

            .nav-actions { display: none; }
            .book-cover-wrap { min-height: 360px; }
            .book-mockup {
                width: 220px;
                height: 320px;
            }
            .cover-title { font-size: 28px; }
            .mobile-toggle { display: inline-grid; place-items: center; min-width: 44px; min-height: 44px; }
        }

        @media (max-width: 560px) {
            .container { width: min(100% - 20px, var(--container)); }
            .nav { min-height: 64px; }
            .brand-text { font-size: 13px; }
            .brand-text small { font-size: 10.5px; }
            .title { letter-spacing: -1px; font-size: 24px; }
            .details-card,
            .book-panel,
            .info-card { padding: 18px; }
            .book-cover-wrap { padding: 16px; min-height: 280px; }
            .book-mockup { width: 180px; height: 260px; }
            .cover-title { font-size: 22px; }
            .meta-item { padding: 14px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="container nav">
            <a href="/" class="brand">
                <div class="brand-badge">
                    <img src="/logo.png" alt="Logo" class="logo-img">
                </div>
                <div>
                    <div id="brand-title">КАЗАХСКИЙ УНИВЕРСИТЕТ ТЕХНОЛОГИИ и БИЗНЕСА</div>
                    <small id="brand-subtitle">Страница просмотра книги</small>
                </div>
            </a>

            <nav class="nav-links">
                <a href="/">Главная</a>
                <a href="/catalog">Каталог</a>
                <a href="/resources">Ресурсы</a>
                <a href="/services">Сервисы</a>
                <a href="/news">Новости</a>
                <a href="/about">О библиотеке</a>
                <a href="/contacts">Контакты</a>
            </nav>

            <div class="nav-actions">
                @if(session('library.user'))
                    <a href="/account" class="btn btn-ghost">Кабинет</a>
                    <button type="button" class="btn btn-primary" id="shared-logout-btn">Выйти</button>
                @else
                    <a href="/login" class="btn btn-ghost">Войти</a>
                    <a href="/account" class="btn btn-primary">Личный кабинет</a>
                @endif
            </div>
        </div>
    </header>

    <main class="page">
        <div class="container">
            <div id="loading" class="loading"><div class="spinner"></div><p style="margin:8px 0 0;">Загрузка информации о книге...</p></div>
            <div id="error" class="error" style="display: none;"></div>
            <div id="content"></div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        const isbn = window.location.pathname.split('/').pop();
        const BOOK_DB_API_ENDPOINT = '/api/v1/book-db/';

        async function loadBook() {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const content = document.getElementById('content');

            try {
                const book = await fetchBookWithFallback(isbn);

                if (!book) {
                    throw new Error('Книга не найдена');
                }

                renderBook(book);
                loading.style.display = 'none';
            } catch (err) {
                loading.style.display = 'none';
                error.style.display = 'block';
                error.innerHTML = `
                    <strong>Ошибка:</strong> ${err.message}
                    <div style="margin-top: 1rem;">
                        <a href="/" class="btn btn-ghost">Вернуться в каталог</a>
                    </div>
                `;
            }
        }

        async function fetchBookWithFallback(identifier) {
            const encodedIdentifier = encodeURIComponent(identifier);
            const endpoints = [
                `${BOOK_DB_API_ENDPOINT}${encodedIdentifier}`,
            ];

            let lastError = null;

            for (const endpoint of endpoints) {
                try {
                    const response = await fetch(endpoint);

                    if (!response.ok) {
                        if (response.status === 404) {
                            lastError = new Error('Книга не найдена');
                            continue;
                        }

                        throw new Error(`API Error: ${response.status}`);
                    }

                    const result = await response.json();
                    if (result?.data) {
                        return result.data;
                    }
                } catch (error) {
                    lastError = error;
                }
            }

            throw lastError || new Error('Книга не найдена');
        }

        function normalizeText(value, fallback = '') {
            if (!value) return fallback;
            if (typeof value !== 'string') return fallback;
            return value.trim() || fallback;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderBook(book) {
            const content = document.getElementById('content');
            const title = escapeHtml(normalizeText(book?.title?.display || book?.title?.raw, 'Без названия'));
            const author = escapeHtml(normalizeText(book?.primaryAuthor || 'Неизвестный автор'));
            const publisher = escapeHtml(normalizeText(book?.publisher?.name || 'Издатель не указан'));
            const isbn = escapeHtml(normalizeText(book?.isbn?.raw || 'ISBN не указан'));
            const year = escapeHtml(normalizeText(book?.publicationYear || 'Год не указан'));
            const language = escapeHtml(normalizeText(book?.language?.raw || 'Язык неизвестен'));
            const available = book?.copies?.available || 0;
            const total = book?.copies?.total || 0;
            const subtitle = normalizeText(book?.title?.subtitle);
            const authors = Array.isArray(book?.authors) ? book.authors : [];
            const locations = Array.isArray(book?.availability?.locations) ? book.availability.locations : [];
            const needsReview = book?.quality?.needsReview === true;
            const reviewCodes = Array.isArray(book?.quality?.reviewReasonCodes) ? book.quality.reviewReasonCodes : [];
            const classification = Array.isArray(book?.classification) ? book.classification : [];

            const isAvailable = available > 0;

            document.title = `${title} - KazTBU Library`;

            const authorsHtml = authors.length > 1
                ? `<div class="authors-list">${authors.map(a => `<span class="author-chip">${escapeHtml(a.name || a)}</span>`).join('')}</div>`
                : escapeHtml(author);

            const locationsTableHtml = locations.length
                ? `<table class="locations-table">
                    <thead><tr>
                        <th>Подразделение</th>
                        <th>Кампус</th>
                        <th>Пункт выдачи</th>
                        <th>Всего</th>
                        <th>Доступно</th>
                    </tr></thead>
                    <tbody>${locations.map(loc => {
                        const avail = loc.copies?.available || 0;
                        const tot = loc.copies?.total || 0;
                        return `<tr>
                            <td>${escapeHtml(loc.institutionUnit?.name || '—')}</td>
                            <td>${escapeHtml(loc.campus?.name || '—')}</td>
                            <td>${escapeHtml(loc.servicePoint?.name || '—')}</td>
                            <td>${tot}</td>
                            <td class="${avail > 0 ? 'avail-count' : 'zero-count'}">${avail}</td>
                        </tr>`;
                    }).join('')}</tbody>
                   </table>`
                : '<p style="color:var(--muted);font-size:14px;">Информация о местах хранения недоступна</p>';

            const reviewHtml = needsReview && reviewCodes.length
                ? `<div class="review-notice">
                    ⚠ Данные этого документа проходят проверку: ${reviewCodes.map(c => `<span class="reason-badge">${escapeHtml(c)}</span>`).join(' ')}
                   </div>`
                : '';

            const classificationHtml = classification.length > 0
                ? `<div class="classification-section">
                    <h4>📚 Направления подготовки</h4>
                    <div class="classification-chips">
                        ${classification.map(c => {
                            const kind = c.sourceKind || 'department';
                            const url = '/catalog?subject_id=' + encodeURIComponent(c.id) + '&subject_label=' + encodeURIComponent(c.label);
                            return `<a href="${url}" class="classification-chip ${kind}" title="Показать все книги: ${escapeHtml(c.label)}">${escapeHtml(c.label)}</a>`;
                        }).join('')}
                    </div>
                   </div>`
                : '';

            content.innerHTML = `
                <div class="breadcrumbs">
                    <span>Главная</span>
                    <span>•</span>
                    <a href="/catalog">Каталог</a>
                    <span>•</span>
                    <span>${escapeHtml(title.substring(0, 50))}</span>
                </div>

                <section class="layout">
                    <aside class="card book-panel">
                        <div class="book-cover-wrap">
                            <div class="book-mockup">
                                <div class="cover-badge">${escapeHtml(year)}</div>
                                <div class="cover-top">Каталог</div>
                                <h1 class="cover-title">${escapeHtml(title.substring(0, 40))}</h1>
                                <div class="cover-author">${escapeHtml(author.substring(0, 30))}</div>
                            </div>
                        </div>
                    </aside>

                    <div>
                        <section class="card details-card">
                            <div class="badges">
                                <span class="badge badge-${isAvailable ? 'green' : 'blue'}">${isAvailable ? '✓ Доступно сейчас' : '✗ Недоступно'}</span>
                                ${book?.isbn?.isValid === false && isbn !== 'ISBN не указан' ? '<span class="badge badge-blue">ISBN не валиден</span>' : ''}
                            </div>

                            <h2 class="title">${escapeHtml(title)}</h2>
                            ${subtitle ? `<p class="subtitle">${escapeHtml(subtitle)}</p>` : ''}

                            <div class="meta-grid">
                                <div class="meta-item">
                                    <span class="meta-label">${authors.length > 1 ? 'Авторы' : 'Автор'}</span>
                                    <span class="meta-value">${authorsHtml}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Год издания</span>
                                    <span class="meta-value">${escapeHtml(year)}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Язык</span>
                                    <span class="meta-value">${escapeHtml(language)}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Доступно</span>
                                    <span class="meta-value">${available} из ${total}</span>
                                </div>
                            </div>

                            ${reviewHtml}
                            ${classificationHtml}

                            <div class="status-box ${isAvailable ? '' : 'unavailable'}">
                                <div>
                                    <strong>${isAvailable ? 'Экземпляр доступен для выдачи' : 'Все экземпляры выданы'}</strong>
                                    <p>${isAvailable ? `В фонде ${available} доступных экземпляров из ${total}.` : `Все ${total} экземпляров в данный момент выданы.`}</p>
                                </div>
                                <div class="status-pill ${isAvailable ? '' : 'unavailable'}">${isAvailable ? 'В наличии' : 'Недоступно'}</div>
                            </div>

                            <div class="action-row">
                                <button class="btn btn-primary" disabled style="opacity:.5; cursor:not-allowed;" title="Функция резервирования в разработке">Забронировать книгу</button>
                                <a href="/catalog" class="btn btn-ghost">Вернуться в каталог</a>
                            </div>
                        </section>

                        <section class="info-grid">
                            <div class="card info-card">
                                <h3 class="section-title">Характеристики</h3>
                                <div class="info-list">
                                    <div class="info-row"><span>ISBN</span><span>${escapeHtml(isbn)}</span></div>
                                    <div class="info-row"><span>Издательство</span><span>${escapeHtml(publisher)}</span></div>
                                    <div class="info-row"><span>Язык издания</span><span>${escapeHtml(language)}</span></div>
                                    <div class="info-row"><span>Год издания</span><span>${escapeHtml(year)}</span></div>
                                    <div class="info-row"><span>Всего экземпляров</span><span>${total}</span></div>
                                    <div class="info-row"><span>Доступно сейчас</span><span style="color: ${isAvailable ? 'var(--success)' : '#dc2626'};">${available}</span></div>
                                </div>
                            </div>

                            <div class="card info-card">
                                <h3 class="section-title">Наличие по пунктам выдачи</h3>
                                ${locationsTableHtml}
                            </div>
                        </section>
                    </div>
                </section>
            `;
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
            window.location.href = '/login';
        });
        @endif

        loadBook();
    </script>
</body>
</html>
