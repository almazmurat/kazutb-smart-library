<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Читалка — Library Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -webkit-user-drag: none;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2b160f, #120906);
            font-family: 'Georgia', serif;
        }

        img, a, button {
            -webkit-user-drag: none;
            user-drag: none;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 22px;
            padding: 20px;
            width: 100%;
            height: 100%;
        }

        .scene {
            perspective: 2500px;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .book {
            position: relative;
            width: 900px;
            max-width: 95vw;
            height: 600px;
            max-height: 82vh;
        }

        .left-page {
            position: absolute;
            left: 0;
            top: 0;
            width: 50%;
            height: 100%;
            background: #f2ead8;
            border-radius: 14px 0 0 14px;
            box-shadow:
                inset -8px 0 20px rgba(0,0,0,.08),
                0 20px 45px rgba(0,0,0,.28);
            overflow: hidden;
            z-index: 1;
        }

        .left-page::before,
        .sheet-face::before {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                to bottom,
                transparent 0px,
                transparent 31px,
                rgba(80, 60, 35, 0.08) 32px
            );
            pointer-events: none;
        }

        .left-content,
        .page-content {
            position: relative;
            z-index: 2;
            padding: 52px 42px 34px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .left-content h2,
        .page-content h2 {
            font-size: 34px;
            line-height: 1.1;
            margin-bottom: 20px;
            color: #4b2f1f;
        }

        .left-content p,
        .page-content p {
            font-size: 18px;
            line-height: 1.8;
            color: #4d4138;
            text-align: justify;
            word-break: normal;
            overflow-wrap: break-word;
        }

        .page-number {
            font-size: 14px;
            text-align: center;
            color: rgba(0,0,0,.55);
            margin-top: 24px;
        }

        .spine {
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            width: 20px;
            height: 100%;
            background: linear-gradient(
                to right,
                rgba(0,0,0,.35),
                rgba(255,255,255,.08),
                rgba(0,0,0,.28)
            );
            z-index: 30;
            pointer-events: none;
        }

        .page {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            transform-origin: left center;
            transform-style: preserve-3d;
            transition: transform 0.9s cubic-bezier(.77,0,.18,1);
            cursor: pointer;
        }

        .page.flipped {
            transform: rotateY(-180deg);
        }

        .sheet-face {
            position: absolute;
            inset: 0;
            background: #f5ecd9;
            overflow: hidden;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        .page-front {
            border-radius: 0 14px 14px 0;
            box-shadow: inset -10px 0 18px rgba(0,0,0,.08);
        }

        .page-back {
            border-radius: 14px 0 0 14px;
            box-shadow: inset 10px 0 18px rgba(0,0,0,.08);
            transform: rotateY(180deg);
        }

        .controls {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .controls button {
            border: none;
            padding: 12px 22px;
            border-radius: 999px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
            background: linear-gradient(135deg, #efcf97, #c7904c);
            color: #2e1b10;
            box-shadow: 0 10px 22px rgba(0,0,0,.25);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .controls button:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(0,0,0,.3);
        }

        .controls button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .header-info {
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 100;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .close-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: rgba(0,0,0,.3);
            border: 1px solid rgba(255,255,255,.2);
            color: #fff;
            cursor: pointer;
            font-size: 20px;
            font-weight: 700;
            transition: all .2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: rgba(0,0,0,.5);
        }

        .cabinet-btn {
            padding: 10px 18px;
            border-radius: 999px;
            background: linear-gradient(135deg, #3b82f6, #06b6d4);
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            transition: all .2s ease;
            display: none;
        }

        .cabinet-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59,130,246,.3);
        }

        .book-info {
            background: rgba(0,0,0,.4);
            backdrop-filter: blur(10px);
            padding: 12px 18px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.1);
            display: none;
        }

        .book-title-header {
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            margin: 0;
        }

        .hint {
            color: rgba(255,255,255,.72);
            font-size: 14px;
            text-align: center;
        }

        .loading {
            color: rgba(255,255,255,.7);
            font-size: 16px;
        }

        .error {
            color: #ff6b6b;
            font-size: 16px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .book {
                height: 460px;
            }

            .left-content,
            .page-content {
                padding: 30px 22px 24px;
            }

            .left-content h2,
            .page-content h2 {
                font-size: 24px;
                margin-bottom: 14px;
            }

            .left-content p,
            .page-content p {
                font-size: 14px;
                line-height: 1.65;
            }

            .header-info {
                flex-direction: column;
                gap: 6px;
            }

            .book-info {
                display: none;
            }
        }

        @media (max-width: 520px) {
            .book {
                height: 390px;
            }

            .controls button {
                padding: 10px 16px;
                font-size: 14px;
            }

            .hint {
                font-size: 12px;
            }

            .left-content h2,
            .page-content h2 {
                font-size: 20px;
            }

            .left-content p,
            .page-content p {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="header-info">
        <button class="close-btn" id="closeBtn" title="Закрыть">✕</button>
        <button class="cabinet-btn" id="cabinetBtn" title="Личный кабинет">Личный кабинет</button>
    </div>

    <div class="wrapper">
        <div class="scene">
            <div class="book" id="book">
                <div class="left-page">
                    <div class="left-content" id="leftStatic">
                        <div>
                            <h2>Загрузка...</h2>
                            <p>Подождите, пока книга загружается...</p>
                        </div>
                        <div class="page-number">-</div>
                    </div>
                </div>

                <div class="spine"></div>
                <div id="pagesContainer"></div>
            </div>
        </div>

        <div class="controls">
            <button id="prevBtn" type="button">← Назад</button>
            <button id="nextBtn" type="button">Вперёд →</button>
        </div>

    </div>

    <script>
        const ME_ENDPOINT = '/api/v1/me';
        const isbn = "{{ $isbn }}";
        const CANONICAL_API = `/api/v1/book-db/${encodeURIComponent(isbn)}`;
        const FALLBACK_API = `/api/v1/catalog-external?q=${encodeURIComponent(isbn)}&limit=1`;

        let pages = [];
        let current = 0;
        let pageElements = [];
        let bookData = null;

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

        function generatePages(book) {
            const title = normalizeText(book?.title?.display || book?.title?.raw, 'Без названия');
            const author = normalizeText(book?.primaryAuthor, 'Автор не указан');
            const publisher = normalizeText(book?.publisher?.name, 'Издательство');
            const year = normalizeText(book?.year, '2026');
            const description = normalizeText(
                book?.description || 'Это издание представляет собой ценный ресурс для студентов, преподавателей и исследователей.',
                'Описание отсутствует'
            );

            return [
                {
                    front: {
                        title: "Титул",
                        text: `${escapeHtml(title)}<br><br>Автор: ${escapeHtml(author)}<br>Издательство: ${escapeHtml(publisher)}<br>Год: ${escapeHtml(year)}`,
                        num: "Обложка"
                    },
                    back: {
                        title: "О книге",
                        text: escapeHtml(description),
                        num: "1"
                    }
                },
                {
                    front: {
                        title: "Содержание",
                        text: `<strong>1. Введение</strong><br>2. Основные концепции<br>3. Практические примеры<br>4. Методология исследования<br>5. Результаты и выводы<br>6. Заключение`,
                        num: "2"
                    },
                    back: {
                        title: "Введение",
                        text: "Данная книга представляет собой систематический обзор современных подходов и методов, применяемых в различных областях науки и практики. Каждый раздел сопровождается примерами и материалами для лучшего понимания.",
                        num: "3"
                    }
                },
                {
                    front: {
                        title: "Основные концепции",
                        text: "Понимание фундаментальных принципов является ключом к успешному применению знаний. В этом разделе мы рассмотрим ключевые идеи и их практическое применение в современном мире.",
                        num: "4"
                    },
                    back: {
                        title: "Практические примеры",
                        text: "Теория приобретает смысл только в применении. Здесь представлены реальные примеры использования изученных концепций в различных ситуациях и контекстах.",
                        num: "5"
                    }
                },
                {
                    front: {
                        title: "Методология",
                        text: "Методологический подход обеспечивает структурированность исследования. В этом разделе объясняется, как был проведен анализ и какие методы использовались.",
                        num: "6"
                    },
                    back: {
                        title: "Итоги",
                        text: "Результаты исследования показывают важность комплексного подхода. Электронный формат позволяет вам удобно навигировать и изучать материал в своем темпе.",
                        num: "7"
                    }
                }
            ];
        }

        function renderPages() {
            const container = document.getElementById('pagesContainer');
            container.innerHTML = '';
            pageElements = [];

            pages.forEach((pageData, index) => {
                const pageEl = document.createElement('div');
                pageEl.className = 'page';
                pageEl.style.zIndex = 20 - index;

                const frontEl = document.createElement('div');
                frontEl.className = 'sheet-face page-front';
                frontEl.innerHTML = `
                    <div class="page-content">
                        <div>
                            <h2>${pageData.front.title}</h2>
                            <p>${pageData.front.text}</p>
                        </div>
                        <div class="page-number">${pageData.front.num}</div>
                    </div>
                `;

                const backEl = document.createElement('div');
                backEl.className = 'sheet-face page-back';
                backEl.innerHTML = `
                    <div class="page-content">
                        <div>
                            <h2>${pageData.back.title}</h2>
                            <p>${pageData.back.text}</p>
                        </div>
                        <div class="page-number">${pageData.back.num}</div>
                    </div>
                `;

                pageEl.appendChild(frontEl);
                pageEl.appendChild(backEl);

                pageEl.addEventListener('click', () => {
                    if (pageElements.indexOf(pageEl) === current) {
                        nextPage();
                    } else if (pageElements.indexOf(pageEl) === current - 1) {
                        prevPage();
                    }
                });

                container.appendChild(pageEl);
                pageElements.push(pageEl);
            });

            updateBook();
        }

        function updateLeftPage() {
            const leftStatic = document.getElementById('leftStatic');
            const pageIndex = Math.floor(current / 2);

            if (current === 0) {
                leftStatic.innerHTML = `
                    <div>
                        <h2>${bookData?.title?.display || bookData?.title?.raw || 'Книга'}</h2>
                        <p>Нажимай на страницу или используй кнопки, чтобы перелистывать её как настоящую.</p>
                    </div>
                    <div class="page-number">Обложка</div>
                `;
            } else if (current > 0 && pages[current - 1]) {
                const pageData = pages[current - 1];
                leftStatic.innerHTML = `
                    <div>
                        <h2>${pageData.front.title}</h2>
                        <p>${pageData.front.text.replace(/<br>/g, ' ')}</p>
                    </div>
                    <div class="page-number">${pageData.front.num}</div>
                `;
            }
        }

        function updateBook() {
            pageElements.forEach((page, index) => {
                if (index < current) {
                    page.classList.add('flipped');
                    page.style.zIndex = index + 1;
                } else {
                    page.classList.remove('flipped');
                    page.style.zIndex = 20 - index;
                }
            });

            updateLeftPage();
            updateButtons();
        }

        function updateButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            prevBtn.disabled = current === 0;
            nextBtn.disabled = current >= pageElements.length;
        }

        async function updateAuthButtons() {
            const cabinetBtn = document.getElementById('cabinetBtn');
            if (!cabinetBtn) {
                return;
            }

            let authenticated = false;
            try {
                const response = await fetch(ME_ENDPOINT, {
                    headers: { Accept: 'application/json' },
                });

                if (response.ok) {
                    const payload = await response.json().catch(() => ({}));
                    authenticated = payload?.authenticated === true;
                }
            } catch (_) {
                authenticated = false;
            }

            if (authenticated) {
                cabinetBtn.style.display = 'inline-block';
            } else {
                cabinetBtn.style.display = 'none';
            }
        }

        function nextPage() {
            if (current < pageElements.length) {
                current++;
                updateBook();
            }
        }

        function prevPage() {
            if (current > 0) {
                current--;
                updateBook();
            }
        }

        document.getElementById('prevBtn').addEventListener('click', prevPage);
        document.getElementById('nextBtn').addEventListener('click', nextPage);
        document.getElementById('closeBtn')?.addEventListener('click', () => {
            window.history.back();
        });
        document.getElementById('cabinetBtn')?.addEventListener('click', () => {
            window.location.href = '/account';
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') nextPage();
            if (e.key === 'ArrowLeft') prevPage();
        });

        document.addEventListener('selectstart', (e) => e.preventDefault());
        document.addEventListener('dragstart', (e) => e.preventDefault());
        document.addEventListener('contextmenu', (e) => e.preventDefault());
        document.addEventListener('copy', (e) => e.preventDefault());
        document.addEventListener('cut', (e) => e.preventDefault());

        async function loadBook() {
            const wrapper = document.querySelector('.wrapper');

            try {
                // Try canonical DB-backed API first
                let response = await fetch(CANONICAL_API, {
                    headers: { Accept: 'application/json' },
                });

                if (response.ok) {
                    const data = await response.json();
                    // Canonical endpoint returns { data: object, success: true }
                    bookData = data?.data || null;
                    // Normalize field differences: canonical uses publicationYear, legacy uses year
                    if (bookData && bookData.publicationYear && !bookData.year) {
                        bookData.year = String(bookData.publicationYear);
                    }
                } else {
                    // Fallback to external proxy (transitional compatibility)
                    console.warn('Canonical API failed, falling back to external proxy');
                    response = await fetch(FALLBACK_API, {
                        headers: { Accept: 'application/json' },
                    });
                    if (!response.ok) throw new Error('Ошибка загрузки книги');
                    const data = await response.json();
                    // Legacy endpoint returns { data: array }
                    bookData = data?.data?.[0] || null;
                }

                if (!bookData) throw new Error('Книга не найдена');

                const title = normalizeText(bookData?.title?.display || bookData?.title?.raw, 'Без названия');
                document.title = `${title} - Читалка`;

                pages = generatePages(bookData);
                renderPages();
            } catch (error) {
                console.error(error);
                wrapper.innerHTML = `<div class="error"><strong>Ошибка:</strong> ${escapeHtml(error?.message || 'Не удалось загрузить книгу')}</div>`;
            }
        }

        loadBook();
        updateAuthButtons();
    </script>
</body>
</html>
</body>
</html>
